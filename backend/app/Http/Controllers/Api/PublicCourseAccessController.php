<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CustomerAccount;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Person;
use App\Models\StudentProfile;
use App\Models\Tenant;
use App\Models\VideoLesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PublicCourseAccessController extends Controller
{
    public function freeEnroll(Request $request): JsonResponse
    {
        $validated = $this->validateAccessRequest($request, ['free_funnel']);

        $result = DB::transaction(function () use ($validated, $request): array {
            $context = $this->resolveTenantContext();
            $course = $this->resolveCourse($context['tenant'], $validated, 0);
            $this->ensureStarterLessons($course);
            $learner = $this->resolveLearner($context['tenant'], $context['branch'], $validated);
            $lead = $this->createLead($context['tenant'], $context['branch'], $course, $learner['person'], $validated, $request, 'registered');
            $enrollment = $this->activateEnrollment($context['tenant'], $learner['student'], $course, null, 'Free funnel enrollment');

            return [
                'message' => 'DAYAI đã mở quyền học miễn phí cho bạn.',
                'access_status' => 'active',
                'flow' => 'free_funnel',
                'lead_id' => $lead->id,
                'enrollment_code' => $enrollment->enrollment_code,
                'student_code' => $learner['student']->student_code,
                'phone' => $learner['person']->phone,
                'email' => $learner['person']->email,
                'course_slug' => $course->slug,
                'portal_url' => '/portal',
            ];
        });

        return response()->json($result, 201);
    }

    public function checkout(Request $request): JsonResponse
    {
        $validated = $this->validateAccessRequest($request, ['paid']);

        $result = DB::transaction(function () use ($validated, $request): array {
            $context = $this->resolveTenantContext();
            $price = max(1, (int) ($validated['price_vnd'] ?? 0));
            $course = $this->resolveCourse($context['tenant'], $validated, $price);
            $this->ensureStarterLessons($course);
            $learner = $this->resolveLearner($context['tenant'], $context['branch'], $validated);
            $lead = $this->createLead($context['tenant'], $context['branch'], $course, $learner['person'], $validated, $request, 'consulting');
            $order = $this->createOrder($context['tenant'], $context['branch'], $course, $learner['student'], $learner['customer'], $lead, $price);
            $invoice = $this->createInvoice($context['tenant'], $order, $learner['customer']);

            return [
                'message' => 'Đơn thanh toán đã được tạo. Hoàn tất thanh toán để mở quyền học.',
                'access_status' => 'payment_required',
                'flow' => 'paid',
                'order_code' => $order->order_code,
                'invoice_code' => $invoice->invoice_code,
                'student_code' => $learner['student']->student_code,
                'phone' => $learner['person']->phone,
                'email' => $learner['person']->email,
                'amount_vnd' => $order->total_vnd,
                'course_slug' => $course->slug,
                'test_payment_url' => '/api/course-access/checkout/' . $order->order_code . '/mark-paid',
            ];
        });

        return response()->json($result, 201);
    }

    public function markPaid(Request $request, string $orderCode): JsonResponse
    {
        $result = DB::transaction(function () use ($orderCode): array {
            $tenant = Tenant::query()->where('code', 'dayai')->firstOrFail();
            $order = Order::query()
                ->with(['items.course', 'items.studentProfile.person', 'customerAccount', 'invoices'])
                ->where('tenant_id', $tenant->id)
                ->where('order_code', $orderCode)
                ->firstOrFail();

            $invoice = $order->invoices()->first()
                ?? $this->createInvoice($tenant, $order, $order->customerAccount);

            Payment::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'order_id' => $order->id,
                    'payment_code' => 'PAY-' . $order->order_code,
                ],
                [
                    'invoice_id' => $invoice->id,
                    'customer_account_id' => $order->customer_account_id,
                    'payment_method' => 'test_gateway',
                    'status' => 'completed',
                    'amount_vnd' => $order->total_vnd,
                    'paid_at' => now(),
                    'reference_no' => 'TEST-' . now()->format('YmdHis'),
                    'notes' => 'Test checkout confirmation from public course flow.',
                ],
            );

            $order->refresh();
            $order->recalculateTotals();
            $item = $order->items->first();
            $student = $item?->studentProfile;
            $course = $item?->course;

            if (! $student || ! $course) {
                abort(422, 'Đơn thanh toán thiếu học viên hoặc khóa học.');
            }

            $this->activateEnrollment($tenant, $student, $course, $order, 'Paid checkout enrollment');

            return [
                'message' => 'Thanh toán test đã hoàn tất. Học viên đã được mở quyền học.',
                'access_status' => 'active',
                'flow' => 'paid',
                'order_code' => $order->order_code,
                'student_code' => $student->student_code,
                'phone' => $student->person?->phone,
                'email' => $student->person?->email,
                'course_slug' => $course->slug,
                'portal_url' => '/portal',
            ];
        });

        return response()->json($result);
    }

    /**
     * @param array<int, string> $allowedFlows
     * @return array<string, mixed>
     */
    private function validateAccessRequest(Request $request, array $allowedFlows): array
    {
        return $request->validate([
            'flow' => ['required', Rule::in($allowedFlows)],
            'course_slug' => ['required', 'string', 'max:255'],
            'course_title' => ['required', 'string', 'max:255'],
            'course_description' => ['nullable', 'string', 'max:2000'],
            'price_vnd' => ['nullable', 'integer', 'min:0', 'max:200000000'],
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'required_without:email', 'string', 'max:30'],
            'email' => ['nullable', 'required_without:phone', 'email', 'max:255'],
            'learning_goal' => ['nullable', 'string', 'max:2000'],
            'page_url' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    /**
     * @return array{tenant: Tenant, branch: ?Branch}
     */
    private function resolveTenantContext(): array
    {
        $tenant = Tenant::query()->where('code', 'dayai')->firstOrFail();
        $branch = Branch::query()->where('tenant_id', $tenant->id)->where('code', 'main')->first();

        return ['tenant' => $tenant, 'branch' => $branch];
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function resolveCourse(Tenant $tenant, array $payload, int $price): Course
    {
        $slug = Str::slug((string) $payload['course_slug']);
        $course = Course::query()
            ->where('tenant_id', $tenant->id)
            ->where('slug', $slug)
            ->first();

        if (! $course) {
            $course = Course::query()->create([
                'tenant_id' => $tenant->id,
                'name' => $payload['course_title'],
                'slug' => $slug,
                'course_code' => $this->uniqueCode($tenant->id, 'CRS', Course::class, 'course_code'),
                'audience_type' => 'mixed',
                'level' => 'beginner',
                'learning_format' => 'online',
                'short_description' => $payload['course_description'] ?? null,
                'description' => $payload['course_description'] ?? null,
                'outcomes' => ['Hoàn thành lộ trình học trên DAYAI LMS'],
                'duration_hours' => 2,
                'default_session_count' => 1,
                'default_price_vnd' => $price,
                'price_label' => $price > 0 ? number_format($price, 0, ',', '.') . 'đ' : 'Miễn phí',
                'primary_cta' => $price > 0 ? 'Thanh toán để học' : 'Học miễn phí',
                'status' => 'published',
            ]);
        } elseif ($course->default_price_vnd !== $price && $price > 0) {
            $course->forceFill([
                'default_price_vnd' => $price,
                'price_label' => number_format($price, 0, ',', '.') . 'đ',
            ])->save();
        }

        return $course;
    }

    private function ensureStarterLessons(Course $course): void
    {
        if ($course->modules()->exists() && $course->videoLessons()->where('status', 'published')->exists()) {
            return;
        }

        $module = CourseModule::query()->firstOrCreate(
            [
                'tenant_id' => $course->tenant_id,
                'course_id' => $course->id,
                'sort_order' => 1,
            ],
            [
                'title' => 'Bắt đầu học',
                'description' => 'Module nhập môn để học viên có thể test portal và LMS.',
                'duration_minutes' => 45,
                'learning_objectives' => ['Làm quen khóa học', 'Thực hành bài học đầu tiên'],
            ],
        );

        $lessons = [
            ['Tổng quan khóa học', 'Mục tiêu, cách học và cách theo dõi tiến độ trên DAYAI Portal.'],
            ['Thực hành đầu tiên', 'Bài thực hành ngắn để kiểm tra quyền truy cập LMS.'],
            ['Checklist hoàn thành', 'Các bước cần hoàn thành trước khi chuyển sang module tiếp theo.'],
        ];

        foreach ($lessons as $index => [$title, $summary]) {
            VideoLesson::query()->firstOrCreate(
                [
                    'tenant_id' => $course->tenant_id,
                    'slug' => $course->slug . '-lesson-' . ($index + 1),
                ],
                [
                    'course_id' => $course->id,
                    'course_module_id' => $module->id,
                    'sort_order' => $index + 1,
                    'title' => $title,
                    'status' => 'published',
                    'video_provider' => 'external',
                    'video_url' => null,
                    'duration_minutes' => 15,
                    'access_level' => 'student',
                    'summary' => $summary,
                    'resources' => [],
                    'metadata' => ['generated_by' => 'public_course_access_flow'],
                    'published_at' => now(),
                ],
            );
        }
    }

    /**
     * @param array<string, mixed> $payload
     * @return array{person: Person, student: StudentProfile, customer: CustomerAccount}
     */
    private function resolveLearner(Tenant $tenant, ?Branch $branch, array $payload): array
    {
        $phone = filled($payload['phone'] ?? null) ? preg_replace('/\s+/', '', (string) $payload['phone']) : null;
        $email = filled($payload['email'] ?? null) ? strtolower((string) $payload['email']) : null;

        $person = Person::query()
            ->where('tenant_id', $tenant->id)
            ->where(function ($query) use ($phone, $email): void {
                $query
                    ->when($phone, fn ($phoneQuery) => $phoneQuery->where('phone', $phone))
                    ->when($email, fn ($emailQuery) => $emailQuery->orWhere('email', $email));
            })
            ->first();

        if (! $person) {
            $person = Person::query()->create([
                'tenant_id' => $tenant->id,
                'branch_id' => $branch?->id,
                'full_name' => $payload['full_name'],
                'display_name' => $payload['full_name'],
                'phone' => $phone,
                'email' => $email,
                'metadata' => ['source' => 'public_course_access'],
            ]);
        } else {
            $person->fill([
                'full_name' => $person->full_name ?: $payload['full_name'],
                'display_name' => $person->display_name ?: $payload['full_name'],
                'phone' => $person->phone ?: $phone,
                'email' => $person->email ?: $email,
            ])->save();
        }

        $student = StudentProfile::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'person_id' => $person->id,
            ],
            [
                'branch_id' => $branch?->id,
                'student_code' => $this->uniqueCode($tenant->id, 'HV', StudentProfile::class, 'student_code'),
                'student_type' => 'adult',
                'learning_goal' => $payload['learning_goal'] ?? null,
                'entry_level' => 'beginner',
                'status' => 'active',
                'metadata' => ['source' => 'public_course_access'],
            ],
        );

        if ($student->status !== 'active') {
            $student->forceFill(['status' => 'active'])->save();
        }

        $customer = CustomerAccount::query()
            ->where('tenant_id', $tenant->id)
            ->where(function ($query) use ($person, $phone, $email): void {
                $query->where('person_id', $person->id)
                    ->when($phone, fn ($phoneQuery) => $phoneQuery->orWhere('phone', $phone))
                    ->when($email, fn ($emailQuery) => $emailQuery->orWhere('email', $email));
            })
            ->first();

        if (! $customer) {
            $customer = CustomerAccount::query()->create([
                'tenant_id' => $tenant->id,
                'branch_id' => $branch?->id,
                'customer_code' => $this->uniqueCode($tenant->id, 'KH', CustomerAccount::class, 'customer_code'),
                'account_type' => 'individual',
                'person_id' => $person->id,
                'display_name' => $person->full_name,
                'phone' => $phone,
                'email' => $email,
                'status' => 'active',
                'metadata' => ['source' => 'public_course_access'],
            ]);
        }

        return ['person' => $person, 'student' => $student, 'customer' => $customer];
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function createLead(Tenant $tenant, ?Branch $branch, Course $course, Person $person, array $payload, Request $request, string $status): Lead
    {
        return Lead::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch?->id,
            'person_id' => $person->id,
            'lead_type' => 'student',
            'status' => $status,
            'priority' => $status === 'registered' ? 'high' : 'normal',
            'temperature' => $status === 'registered' ? 'hot' : 'warm',
            'full_name' => $person->full_name,
            'phone' => $person->phone,
            'email' => $person->email,
            'interested_course_id' => $course->id,
            'expected_value_vnd' => $course->default_price_vnd,
            'course_slug' => $course->slug,
            'learning_goal' => $payload['learning_goal'] ?? null,
            'message' => $status === 'registered' ? 'Public free enrollment' : 'Public paid checkout started',
            'preferred_contact_method' => 'phone',
            'utm_source' => 'website',
            'page_url' => $payload['page_url'] ?? null,
            'landing_page' => $payload['page_url'] ?? null,
            'metadata' => [
                'flow' => $payload['flow'],
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
            ],
        ]);
    }

    private function createOrder(Tenant $tenant, ?Branch $branch, Course $course, StudentProfile $student, CustomerAccount $customer, Lead $lead, int $price): Order
    {
        $order = Order::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch?->id,
            'customer_account_id' => $customer->id,
            'lead_id' => $lead->id,
            'order_code' => $this->uniqueCode($tenant->id, 'ORD', Order::class, 'order_code'),
            'order_type' => 'b2c',
            'status' => 'confirmed',
            'ordered_at' => now(),
            'discount_vnd' => 0,
            'notes' => 'Public paid checkout pending payment.',
        ]);

        OrderItem::query()->create([
            'tenant_id' => $tenant->id,
            'order_id' => $order->id,
            'course_id' => $course->id,
            'student_profile_id' => $student->id,
            'description' => $course->name,
            'quantity' => 1,
            'unit_price_vnd' => $price,
            'discount_vnd' => 0,
        ]);

        return $order->fresh();
    }

    private function createInvoice(Tenant $tenant, Order $order, CustomerAccount $customer): Invoice
    {
        return Invoice::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'order_id' => $order->id,
            ],
            [
                'customer_account_id' => $customer->id,
                'invoice_code' => $this->uniqueCode($tenant->id, 'INV', Invoice::class, 'invoice_code'),
                'status' => 'issued',
                'issued_at' => now(),
                'due_date' => now()->addDays(3)->toDateString(),
                'amount_vnd' => $order->total_vnd,
                'paid_vnd' => 0,
                'balance_vnd' => $order->total_vnd,
                'notes' => 'Auto invoice for public course checkout.',
            ],
        );
    }

    private function activateEnrollment(Tenant $tenant, StudentProfile $student, Course $course, ?Order $order, string $note): Enrollment
    {
        $enrollment = Enrollment::query()
            ->where('student_profile_id', $student->id)
            ->where('course_id', $course->id)
            ->whereNull('class_group_id')
            ->first();

        if ($enrollment) {
            $enrollment->forceFill([
                'tenant_id' => $tenant->id,
                'order_id' => $order?->id ?? $enrollment->order_id,
                'status' => 'active',
                'started_at' => $enrollment->started_at ?? now(),
                'notes' => $note,
            ])->save();

            return $enrollment;
        }

        return Enrollment::query()->create([
            'tenant_id' => $tenant->id,
            'student_profile_id' => $student->id,
            'course_id' => $course->id,
            'order_id' => $order?->id,
            'enrollment_code' => $this->uniqueCode($tenant->id, 'ENR', Enrollment::class, 'enrollment_code'),
            'status' => 'active',
            'enrolled_at' => now(),
            'started_at' => now(),
            'notes' => $note,
        ]);
    }

    /**
     * @param class-string $modelClass
     */
    private function uniqueCode(string $tenantId, string $prefix, string $modelClass, string $column): string
    {
        do {
            $code = $prefix . '-' . now()->format('ymdHis') . '-' . Str::upper(Str::random(4));
        } while ($modelClass::query()->where('tenant_id', $tenantId)->where($column, $code)->exists());

        return $code;
    }
}
