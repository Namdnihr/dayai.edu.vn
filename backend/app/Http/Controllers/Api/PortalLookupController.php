<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\CustomerAccount;
use App\Models\GuardianRelation;
use App\Models\Notification;
use App\Models\PortalAuthToken;
use App\Models\QuizAttempt;
use App\Models\StudentProfile;
use App\Models\VideoLesson;
use App\Models\VideoLessonProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortalLookupController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'student_code' => ['required', 'string', 'max:50'],
            'portal_access_token' => ['required', 'string', 'size:64'],
        ]);

        $phone = preg_replace('/\s+/', '', $validated['phone']);
        $studentCode = trim($validated['student_code']);

        $student = StudentProfile::query()
            ->with([
                'person',
                'guardians.guardianPerson',
                'enrollments.course.modules.videoLessons',
                'enrollments.classGroup.sessions',
                'enrollments.order',
                'attendanceRecords.classSession.classGroup',
                'assessmentResults.assessment',
                'assessmentResults.teacherProfile.person',
                'quizAttempts.assessment',
                'teacherComments.classSession',
                'teacherComments.teacherProfile.person',
                'progressReports.teacherProfile.person',
                'certificates.course',
                'certificates.classGroup',
            ])
            ->where(function ($query) use ($studentCode, $phone): void {
                $query->where('student_code', $studentCode)
                    ->whereHas('person', fn ($personQuery) => $personQuery->where('phone', $phone));
            })
            ->orWhere(function ($query) use ($studentCode, $phone): void {
                $query->where('student_code', $studentCode)
                    ->whereHas('guardians.guardianPerson', fn ($guardianQuery) => $guardianQuery->where('phone', $phone));
            })
            ->first();

        if (! $student) {
            return response()->json([
                'message' => 'Không tìm thấy học viên với thông tin đã nhập.',
            ], 404);
        }

        $portalAccess = $this->resolveValidPortalAccess($student, $phone, $studentCode, $validated['portal_access_token']);

        if (! $portalAccess) {
            return response()->json([
                'message' => 'Phiên portal không hợp lệ hoặc đã hết hạn. Vui lòng xác thực lại.',
            ], 401);
        }

        $customerAccount = $this->findCustomerAccount($student, $phone);
        $commentVisibility = $portalAccess->access_role === 'guardian'
            ? ['guardian', 'student']
            : ['student'];

        $enrollments = $student->enrollments
            ->map(fn ($enrollment): array => [
                'enrollment_code' => $enrollment->enrollment_code,
                'status' => $enrollment->status,
                'course' => $enrollment->course?->name,
                'class_group' => $enrollment->classGroup?->name,
                'schedule_note' => $enrollment->classGroup?->schedule_note,
                'started_at' => $enrollment->started_at?->toDateString(),
                'order_code' => $enrollment->order?->order_code,
            ])
            ->values();

        $upcomingSessions = $student->enrollments
            ->flatMap(fn ($enrollment) => $enrollment->classGroup?->sessions ?? collect())
            ->sortBy('starts_at')
            ->take(6)
            ->map(fn ($session): array => [
                'title' => $session->title,
                'class_group' => $session->classGroup?->name,
                'starts_at' => $session->starts_at?->toDateTimeString(),
                'ends_at' => $session->ends_at?->toDateTimeString(),
                'status' => $session->status,
                'location' => $session->location,
            ])
            ->values();

        $attendance = $student->attendanceRecords
            ->sortByDesc(fn ($record) => $record->classSession?->starts_at)
            ->take(10)
            ->map(fn ($record): array => [
                'session' => $record->classSession?->title,
                'class_group' => $record->classSession?->classGroup?->name,
                'status' => $record->status,
                'checked_in_at' => $record->checked_in_at?->toDateTimeString(),
                'teacher_note' => $record->teacher_note,
            ])
            ->values();

        $attendanceSummary = [
            'total' => $student->attendanceRecords->count(),
            'present' => $student->attendanceRecords->where('status', 'present')->count(),
            'absent' => $student->attendanceRecords->where('status', 'absent')->count(),
            'late' => $student->attendanceRecords->where('status', 'late')->count(),
            'excused' => $student->attendanceRecords->where('status', 'excused')->count(),
        ];

        $courseIds = $student->enrollments->pluck('course_id')->filter()->unique()->values();

        $assessmentResults = $student->assessmentResults
            ->where('status', 'published')
            ->sortByDesc('assessed_at')
            ->take(10)
            ->map(fn ($result): array => [
                'assessment' => $result->assessment?->title,
                'assessment_type' => $result->assessment?->assessment_type,
                'score' => $result->score !== null ? (float) $result->score : null,
                'max_score' => (float) $result->max_score,
                'level' => $result->level,
                'feedback' => $result->feedback,
                'strengths' => $result->strengths,
                'improvements' => $result->improvements,
                'teacher' => $result->teacherProfile?->person?->full_name,
                'assessed_at' => $result->assessed_at?->toDateTimeString(),
            ])
            ->values();

        $classGroupIds = $student->enrollments->pluck('class_group_id')->filter()->unique()->values();
        $availableAssessments = Assessment::query()
            ->with(['course', 'courseModule', 'videoLesson'])
            ->withCount('assessmentQuestions')
            ->where('tenant_id', $student->tenant_id)
            ->where('status', 'published')
            ->whereIn('assessment_type', ['entry', 'quiz', 'practice', 'final'])
            ->where(function ($query) use ($courseIds, $classGroupIds): void {
                $query->whereIn('course_id', $courseIds)
                    ->orWhereIn('class_group_id', $classGroupIds);
            })
            ->orderByDesc('assessment_at')
            ->get()
            ->map(fn (Assessment $assessment): array => [
                'id' => $assessment->id,
                'title' => $assessment->title,
                'assessment_type' => $assessment->assessment_type,
                'course' => $assessment->course?->name,
                'module' => $assessment->courseModule?->title,
                'video' => $assessment->videoLesson?->title,
                'description' => $assessment->description,
                'max_score' => (float) $assessment->max_score,
                'question_count' => (int) $assessment->assessment_questions_count,
                'assessment_at' => $assessment->assessment_at?->toDateTimeString(),
            ])
            ->values();

        $quizAttempts = $student->quizAttempts
            ->sortByDesc('created_at')
            ->take(10)
            ->map(fn (QuizAttempt $attempt): array => [
                'attempt_code' => $attempt->attempt_code,
                'assessment_id' => $attempt->assessment_id,
                'assessment' => $attempt->assessment?->title,
                'attempt_no' => $attempt->attempt_no,
                'status' => $attempt->status,
                'score' => $attempt->score !== null ? (float) $attempt->score : null,
                'max_score' => (float) $attempt->max_score,
                'correct_count' => $attempt->correct_count,
                'question_count' => $attempt->question_count,
                'submitted_at' => $attempt->submitted_at?->toDateTimeString(),
            ])
            ->values();

        $teacherComments = $student->teacherComments
            ->whereIn('visibility', $commentVisibility)
            ->sortByDesc('commented_at')
            ->take(10)
            ->map(fn ($comment): array => [
                'title' => $comment->title,
                'comment_type' => $comment->comment_type,
                'comment' => $comment->comment,
                'rating' => $comment->rating,
                'session' => $comment->classSession?->title,
                'teacher' => $comment->teacherProfile?->person?->full_name,
                'commented_at' => $comment->commented_at?->toDateTimeString(),
            ])
            ->values();

        $progressReports = $student->progressReports
            ->where('status', 'published')
            ->sortByDesc('published_at')
            ->take(5)
            ->map(fn ($report): array => [
                'title' => $report->title,
                'report_period' => $report->report_period,
                'overall_level' => $report->overall_level,
                'progress_percent' => $report->progress_percent,
                'strengths' => $report->strengths,
                'improvements' => $report->improvements,
                'recommendation' => $report->recommendation,
                'teacher' => $report->teacherProfile?->person?->full_name,
                'published_at' => $report->published_at?->toDateTimeString(),
            ])
            ->values();

        $orders = $student->enrollments
            ->pluck('order')
            ->filter()
            ->unique('id')
            ->values();

        $finance = [
            'customer' => $customerAccount?->display_name,
            'total_vnd' => (int) $orders->sum('total_vnd'),
            'paid_vnd' => (int) $orders->sum('paid_vnd'),
            'balance_vnd' => (int) $orders->sum('balance_vnd'),
            'orders' => $orders->map(fn ($order): array => [
                'order_code' => $order->order_code,
                'status' => $order->status,
                'total_vnd' => $order->total_vnd,
                'paid_vnd' => $order->paid_vnd,
                'balance_vnd' => $order->balance_vnd,
            ])->values(),
        ];

        $lessonProgress = VideoLessonProgress::query()
            ->where('tenant_id', $student->tenant_id)
            ->where('student_profile_id', $student->id)
            ->get()
            ->keyBy('video_lesson_id');

        $lmsCourses = $student->enrollments
            ->where('status', 'active')
            ->filter(fn ($enrollment) => $enrollment->course !== null)
            ->map(function ($enrollment) use ($lessonProgress): array {
                $lessonCount = 0;
                $progressTotal = 0;

                $modules = $enrollment->course->modules
                    ->map(function ($module) use ($lessonProgress, &$lessonCount, &$progressTotal): array {
                        $lessons = $module->videoLessons
                            ->where('status', 'published')
                            ->whereIn('access_level', ['public', 'student'])
                            ->sortBy('sort_order')
                            ->map(function (VideoLesson $lesson) use ($lessonProgress, &$lessonCount, &$progressTotal): array {
                                $progress = $lessonProgress->get($lesson->id);
                                $progressPercent = (int) ($progress?->progress_percent ?? 0);

                                $lessonCount++;
                                $progressTotal += $progressPercent;

                                return [
                                    'title' => $lesson->title,
                                    'slug' => $lesson->slug,
                                    'summary' => $lesson->summary,
                                    'duration_minutes' => $lesson->duration_minutes,
                                    'access_level' => $lesson->access_level,
                                    'video_url' => $lesson->video_url,
                                    'thumbnail_url' => $lesson->thumbnail_url,
                                    'resources' => $lesson->resources ?? [],
                                    'progress' => [
                                        'status' => $progress?->status ?? 'not_started',
                                        'progress_percent' => $progressPercent,
                                        'last_position_seconds' => (int) ($progress?->last_position_seconds ?? 0),
                                        'last_watched_at' => $progress?->last_watched_at?->toDateTimeString(),
                                        'completed_at' => $progress?->completed_at?->toDateTimeString(),
                                    ],
                                ];
                            })
                            ->values();

                        return [
                            'title' => $module->title,
                            'description' => $module->description,
                            'duration_minutes' => $module->duration_minutes,
                            'learning_objectives' => $module->learning_objectives ?? [],
                            'lessons' => $lessons,
                        ];
                    })
                    ->values();

                return [
                    'enrollment_code' => $enrollment->enrollment_code,
                    'course' => $enrollment->course->name,
                    'course_slug' => $enrollment->course->slug,
                    'status' => $enrollment->status,
                    'progress_percent' => $lessonCount > 0 ? (int) round($progressTotal / $lessonCount) : 0,
                    'modules' => $modules,
                ];
            })
            ->values();

        $overallLmsProgress = $lmsCourses->count() > 0
            ? (int) round($lmsCourses->avg('progress_percent'))
            : 0;

        $videos = VideoLesson::query()
            ->where('tenant_id', $student->tenant_id)
            ->where('status', 'published')
            ->whereIn('access_level', ['public', 'student'])
            ->where(function ($query) use ($courseIds): void {
                $query->whereNull('course_id')
                    ->orWhereIn('course_id', $courseIds);
            })
            ->orderByDesc('published_at')
            ->limit(6)
            ->get()
            ->map(function (VideoLesson $video) use ($lessonProgress): array {
                $progress = $lessonProgress->get($video->id);

                return [
                    'title' => $video->title,
                    'slug' => $video->slug,
                    'summary' => $video->summary,
                    'duration_minutes' => $video->duration_minutes,
                    'access_level' => $video->access_level,
                    'thumbnail_url' => $video->thumbnail_url,
                    'progress_percent' => (int) ($progress?->progress_percent ?? 0),
                    'progress_status' => $progress?->status ?? 'not_started',
                ];
            });

        $personIds = collect([$student->person_id])
            ->merge($student->guardians->pluck('guardian_person_id'))
            ->filter()
            ->unique()
            ->values();

        $notifications = Notification::query()
            ->where('tenant_id', $student->tenant_id)
            ->where('status', 'published')
            ->where('channel', 'portal')
            ->where(function ($query) use ($student, $personIds): void {
                $query->where('student_profile_id', $student->id)
                    ->orWhereIn('person_id', $personIds);
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

        $certificates = $student->certificates
            ->where('status', 'issued')
            ->sortByDesc('issued_at')
            ->map(fn ($certificate): array => [
                'certificate_code' => $certificate->certificate_code,
                'title' => $certificate->title,
                'course' => $certificate->course?->name,
                'class_group' => $certificate->classGroup?->name,
                'final_score' => $certificate->final_score !== null ? (float) $certificate->final_score : null,
                'grade' => $certificate->grade,
                'issued_at' => $certificate->issued_at?->toDateTimeString(),
                'verification_token' => $certificate->verification_token,
                'file_path' => $certificate->file_path,
            ])
            ->values();

        return response()->json([
            'student' => [
                'student_code' => $student->student_code,
                'full_name' => $student->person?->full_name,
                'student_type' => $student->student_type,
                'learning_goal' => $student->learning_goal,
                'status' => $student->status,
                'portal_access_role' => $portalAccess->access_role,
            ],
            'summary' => [
                'active_enrollments' => $student->enrollments->where('status', 'active')->count(),
                'next_session' => $upcomingSessions->first(),
                'latest_progress_percent' => $progressReports->first()['progress_percent'] ?? null,
                'attendance' => $attendanceSummary,
                'finance_balance_vnd' => (int) $finance['balance_vnd'],
                'unread_notifications' => $notifications->count(),
            ],
            'guardians' => $student->guardians->map(fn (GuardianRelation $guardian): array => [
                'full_name' => $guardian->guardianPerson?->full_name,
                'phone' => $guardian->guardianPerson?->phone,
                'relation_type' => $guardian->relation_type,
            ])->values(),
            'enrollments' => $enrollments,
            'upcoming_sessions' => $upcomingSessions,
            'attendance' => $attendance,
            'assessment_results' => $assessmentResults,
            'available_assessments' => $availableAssessments,
            'quiz_attempts' => $quizAttempts,
            'teacher_comments' => $teacherComments,
            'progress_reports' => $progressReports,
            'finance' => $finance,
            'lms' => [
                'overall_progress_percent' => $overallLmsProgress,
                'courses' => $lmsCourses,
            ],
            'videos' => $videos,
            'notifications' => $notifications,
            'certificates' => $certificates,
        ]);
    }

    protected function findCustomerAccount(StudentProfile $student, string $phone): ?CustomerAccount
    {
        $guardianPersonIds = $student->guardians->pluck('guardian_person_id');

        return CustomerAccount::query()
            ->where('tenant_id', $student->tenant_id)
            ->where(function ($query) use ($student, $phone, $guardianPersonIds): void {
                $query->where('phone', $phone)
                    ->orWhere('person_id', $student->person_id)
                    ->orWhereIn('person_id', $guardianPersonIds);
            })
            ->first();
    }

    protected function resolveValidPortalAccess(StudentProfile $student, string $phone, string $studentCode, string $accessToken): ?PortalAuthToken
    {
        return PortalAuthToken::query()
            ->where('student_profile_id', $student->id)
            ->where('phone', $phone)
            ->where('student_code', $studentCode)
            ->where('access_token_hash', hash('sha256', $accessToken))
            ->whereNotNull('verified_at')
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->first();
    }
}
