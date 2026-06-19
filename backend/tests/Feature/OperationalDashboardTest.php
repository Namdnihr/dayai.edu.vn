<?php

namespace Tests\Feature;

use App\Filament\Pages\OperationalDashboard;
use App\Models\AttendanceRecord;
use App\Models\Branch;
use App\Models\ClassGroup;
use App\Models\ClassSession;
use App\Models\Course;
use App\Models\CustomerAccount;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Person;
use App\Models\ProgressReport;
use App\Models\Receivable;
use App\Models\StudentProfile;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OperationalDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_operational_dashboard_returns_core_metrics(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'DAYAI',
            'code' => 'dayai',
            'status' => 'active',
        ]);

        $branch = Branch::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Cơ sở chính',
            'code' => 'main',
            'status' => 'active',
        ]);

        $source = LeadSource::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Website',
            'code' => 'website',
            'source_type' => 'website',
            'is_active' => true,
        ]);

        Lead::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'lead_source_id' => $source->id,
            'lead_type' => 'student',
            'status' => 'new',
            'full_name' => 'Lead mới',
            'phone' => '0901000001',
        ]);

        Lead::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'lead_source_id' => $source->id,
            'lead_type' => 'student',
            'status' => 'registered',
            'full_name' => 'Lead chuyển đổi',
            'phone' => '0901000002',
            'converted_at' => now(),
        ]);

        $person = Person::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'full_name' => 'Học viên Dashboard',
            'phone' => '0901888000',
        ]);

        $student = StudentProfile::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'person_id' => $person->id,
            'student_code' => 'HV-DASH',
            'student_type' => 'university_student',
            'status' => 'active',
        ]);

        $course = Course::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'AI Dashboard',
            'slug' => 'ai-dashboard',
            'course_code' => 'AI-DASH',
            'default_price_vnd' => 3000000,
            'status' => 'published',
        ]);

        $classGroup = ClassGroup::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'course_id' => $course->id,
            'class_code' => 'DASH-01',
            'name' => 'Dashboard Class',
            'status' => 'enrolling',
        ]);

        $customer = CustomerAccount::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'person_id' => $person->id,
            'customer_code' => 'KH-DASH',
            'account_type' => 'individual',
            'display_name' => 'Học viên Dashboard',
            'status' => 'active',
        ]);

        $session = ClassSession::query()->create([
            'tenant_id' => $tenant->id,
            'class_group_id' => $classGroup->id,
            'session_no' => 1,
            'title' => 'Buổi dashboard',
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(2),
            'status' => 'scheduled',
        ]);

        $order = Order::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'customer_account_id' => $customer->id,
            'order_code' => 'ORD-DASH',
            'order_type' => 'b2c',
            'status' => 'partially_paid',
            'ordered_at' => now(),
            'total_vnd' => 3000000,
            'paid_vnd' => 1000000,
            'balance_vnd' => 2000000,
        ]);

        OrderItem::query()->create([
            'tenant_id' => $tenant->id,
            'order_id' => $order->id,
            'course_id' => $course->id,
            'student_profile_id' => $student->id,
            'description' => 'AI Dashboard',
            'quantity' => 1,
            'unit_price_vnd' => 3000000,
            'line_total_vnd' => 3000000,
        ]);

        $invoice = Invoice::query()->create([
            'tenant_id' => $tenant->id,
            'order_id' => $order->id,
            'customer_account_id' => $customer->id,
            'invoice_code' => 'INV-DASH',
            'status' => 'issued',
            'issued_at' => now(),
            'due_date' => now()->addDays(7)->toDateString(),
            'amount_vnd' => 3000000,
            'paid_vnd' => 1000000,
            'balance_vnd' => 2000000,
        ]);

        Payment::query()->create([
            'tenant_id' => $tenant->id,
            'invoice_id' => $invoice->id,
            'order_id' => $order->id,
            'customer_account_id' => $customer->id,
            'payment_code' => 'PAY-DASH',
            'payment_method' => 'bank_transfer',
            'status' => 'completed',
            'amount_vnd' => 1000000,
            'paid_at' => now(),
        ]);

        $enrollment = Enrollment::query()->create([
            'tenant_id' => $tenant->id,
            'student_profile_id' => $student->id,
            'course_id' => $course->id,
            'class_group_id' => $classGroup->id,
            'order_id' => $order->id,
            'enrollment_code' => 'ENR-DASH',
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        AttendanceRecord::query()->create([
            'tenant_id' => $tenant->id,
            'class_session_id' => $session->id,
            'student_profile_id' => $student->id,
            'enrollment_id' => $enrollment->id,
            'status' => 'late',
        ]);

        ProgressReport::query()->create([
            'tenant_id' => $tenant->id,
            'student_profile_id' => $student->id,
            'enrollment_id' => $enrollment->id,
            'course_id' => $course->id,
            'class_group_id' => $classGroup->id,
            'title' => 'Báo cáo dashboard',
            'status' => 'published',
            'progress_percent' => 50,
            'published_at' => now(),
        ]);

        $dashboard = new OperationalDashboard();
        $summary = $dashboard->getSummary();

        $this->assertSame(2, $summary['lead_count']);
        $this->assertSame(1, $summary['new_lead_count']);
        $this->assertSame(50, $summary['conversion_rate']);
        $this->assertSame(1, $summary['active_enrollment_count']);
        $this->assertSame(3000000, $summary['order_total_vnd']);
        $this->assertSame(1000000, $summary['paid_vnd']);
        $this->assertSame(2000000, $summary['receivable_vnd']);
        $this->assertSame(50, $summary['average_progress_percent']);
        $this->assertTrue($dashboard->getLeadFunnel()->isNotEmpty());
        $this->assertTrue($dashboard->getLeadSources()->isNotEmpty());
        $this->assertTrue($dashboard->getUpcomingSessions()->isNotEmpty());
        $this->assertTrue($dashboard->getAtRiskStudents()->isNotEmpty());
        $this->assertTrue($dashboard->getCourseRevenue()->isNotEmpty());
    }
}
