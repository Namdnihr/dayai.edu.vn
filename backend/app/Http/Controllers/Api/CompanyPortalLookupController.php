<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyPortalLookupController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'company_code' => ['required', 'string', 'max:100'],
        ]);

        $email = strtolower(trim($validated['email']));
        $companyCode = trim($validated['company_code']);

        $organization = Organization::query()
            ->with([
                'contacts.person',
                'studentProfiles.person',
                'studentProfiles.enrollments.course',
                'studentProfiles.enrollments.classGroup',
                'studentProfiles.enrollments.order',
                'studentProfiles.attendanceRecords',
                'studentProfiles.assessmentResults.assessment',
                'studentProfiles.progressReports',
                'studentProfiles.certificates.course',
                'customerAccounts.orders',
                'customerAccounts.invoices',
            ])
            ->where(function ($query) use ($companyCode): void {
                $query->where('tax_code', $companyCode)
                    ->orWhere('short_name', $companyCode);
            })
            ->whereHas('contacts.person', fn ($contactQuery) => $contactQuery->where('email', $email))
            ->first();

        if (! $organization) {
            return response()->json([
                'message' => 'Không tìm thấy công ty hoặc HR với thông tin đã nhập.',
            ], 404);
        }

        $students = $organization->studentProfiles
            ->map(function ($student): array {
                $enrollments = $student->enrollments;
                $attendanceRecords = $student->attendanceRecords;
                $progressReport = $student->progressReports
                    ->where('status', 'published')
                    ->sortByDesc('published_at')
                    ->first();
                $assessmentResult = $student->assessmentResults
                    ->where('status', 'published')
                    ->sortByDesc('assessed_at')
                    ->first();
                $certificate = $student->certificates
                    ->where('status', 'issued')
                    ->sortByDesc('issued_at')
                    ->first();

                return [
                    'student_code' => $student->student_code,
                    'full_name' => $student->person?->full_name,
                    'job_title' => $student->job_title,
                    'status' => $student->status,
                    'enrollments' => $enrollments->map(fn ($enrollment): array => [
                        'enrollment_code' => $enrollment->enrollment_code,
                        'status' => $enrollment->status,
                        'course' => $enrollment->course?->name,
                        'class_group' => $enrollment->classGroup?->name,
                    ])->values(),
                    'attendance' => [
                        'present' => $attendanceRecords->where('status', 'present')->count(),
                        'absent' => $attendanceRecords->whereIn('status', ['absent', 'excused'])->count(),
                        'late' => $attendanceRecords->where('status', 'late')->count(),
                    ],
                    'latest_assessment' => $assessmentResult ? [
                        'assessment' => $assessmentResult->assessment?->title,
                        'score' => $assessmentResult->score !== null ? (float) $assessmentResult->score : null,
                        'max_score' => (float) $assessmentResult->max_score,
                        'level' => $assessmentResult->level,
                    ] : null,
                    'latest_progress' => $progressReport ? [
                        'title' => $progressReport->title,
                        'overall_level' => $progressReport->overall_level,
                        'progress_percent' => $progressReport->progress_percent,
                        'published_at' => $progressReport->published_at?->toDateTimeString(),
                    ] : null,
                    'latest_certificate' => $certificate ? [
                        'certificate_code' => $certificate->certificate_code,
                        'title' => $certificate->title,
                        'course' => $certificate->course?->name,
                        'grade' => $certificate->grade,
                        'issued_at' => $certificate->issued_at?->toDateTimeString(),
                    ] : null,
                ];
            })
            ->values();

        $orders = $organization->customerAccounts
            ->flatMap(fn ($account) => $account->orders)
            ->unique('id')
            ->values();

        $contactPersonIds = $organization->contacts->pluck('person_id')->filter()->unique()->values();
        $studentIds = $organization->studentProfiles->pluck('id')->filter()->unique()->values();

        $notifications = Notification::query()
            ->where('tenant_id', $organization->tenant_id)
            ->where('status', 'published')
            ->where('channel', 'portal')
            ->where(function ($query) use ($organization, $contactPersonIds, $studentIds): void {
                $query->where('organization_id', $organization->id)
                    ->orWhereIn('person_id', $contactPersonIds)
                    ->orWhereIn('student_profile_id', $studentIds);
            })
            ->orderByDesc('published_at')
            ->limit(8)
            ->get()
            ->map(fn (Notification $notification): array => [
                'title' => $notification->title,
                'body' => $notification->body,
                'notification_type' => $notification->notification_type,
                'priority' => $notification->priority,
                'published_at' => $notification->published_at?->toDateTimeString(),
            ]);

        return response()->json([
            'organization' => [
                'name' => $organization->name,
                'short_name' => $organization->short_name,
                'tax_code' => $organization->tax_code,
                'industry' => $organization->industry,
                'company_size' => $organization->company_size,
                'status' => $organization->status,
            ],
            'hr_contacts' => $organization->contacts
                ->map(fn ($contact): array => [
                    'full_name' => $contact->person?->full_name,
                    'email' => $contact->person?->email,
                    'role' => $contact->contact_role,
                    'job_title' => $contact->job_title,
                ])
                ->values(),
            'summary' => [
                'student_count' => $students->count(),
                'active_enrollment_count' => $students
                    ->flatMap(fn ($student) => $student['enrollments'])
                    ->where('status', 'active')
                    ->count(),
                'average_progress_percent' => (int) round($students
                    ->pluck('latest_progress.progress_percent')
                    ->filter(fn ($progress) => $progress !== null)
                    ->avg() ?? 0),
                'total_vnd' => (int) $orders->sum('total_vnd'),
                'paid_vnd' => (int) $orders->sum('paid_vnd'),
                'balance_vnd' => (int) $orders->sum('balance_vnd'),
            ],
            'students' => $students,
            'notifications' => $notifications,
            'orders' => $orders->map(fn ($order): array => [
                'order_code' => $order->order_code,
                'status' => $order->status,
                'total_vnd' => $order->total_vnd,
                'paid_vnd' => $order->paid_vnd,
                'balance_vnd' => $order->balance_vnd,
            ])->values(),
        ]);
    }
}
