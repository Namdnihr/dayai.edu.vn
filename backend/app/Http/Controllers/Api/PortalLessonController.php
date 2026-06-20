<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PortalAuthToken;
use App\Models\StudentProfile;
use App\Models\VideoLesson;
use App\Models\VideoLessonProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortalLessonController extends Controller
{
    public function show(Request $request, string $slug): JsonResponse
    {
        $context = $this->resolveContext($request);

        if ($context instanceof JsonResponse) {
            return $context;
        }

        [$student, $portalAccess] = $context;
        $lesson = $this->resolveLesson($student, $slug);

        if (! $lesson) {
            return response()->json([
                'message' => 'Bài học không tồn tại hoặc bạn chưa có quyền truy cập.',
            ], 404);
        }

        $progress = VideoLessonProgress::query()
            ->where('student_profile_id', $student->id)
            ->where('video_lesson_id', $lesson->id)
            ->first();

        return response()->json([
            'access_role' => $portalAccess->access_role,
            'lesson' => $this->lessonPayload($lesson, $progress),
        ]);
    }

    public function updateProgress(Request $request, string $slug): JsonResponse
    {
        $validated = $request->validate([
            'progress_percent' => ['required', 'integer', 'min:0', 'max:100'],
            'last_position_seconds' => ['nullable', 'integer', 'min:0'],
        ]);

        $context = $this->resolveContext($request);

        if ($context instanceof JsonResponse) {
            return $context;
        }

        [$student] = $context;
        $lesson = $this->resolveLesson($student, $slug);

        if (! $lesson) {
            return response()->json([
                'message' => 'Bài học không tồn tại hoặc bạn chưa có quyền truy cập.',
            ], 404);
        }

        $enrollment = $student->enrollments
            ->first(fn ($enrollment) => $enrollment->course_id === $lesson->course_id && $enrollment->status === 'active');
        $progressPercent = (int) $validated['progress_percent'];
        $status = $progressPercent >= 95 ? 'completed' : 'in_progress';

        $progress = VideoLessonProgress::query()->updateOrCreate(
            [
                'student_profile_id' => $student->id,
                'video_lesson_id' => $lesson->id,
            ],
            [
                'tenant_id' => $student->tenant_id,
                'enrollment_id' => $enrollment?->id,
                'status' => $status,
                'progress_percent' => $progressPercent,
                'last_position_seconds' => (int) ($validated['last_position_seconds'] ?? 0),
                'started_at' => now(),
                'completed_at' => $status === 'completed' ? now() : null,
                'last_watched_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Đã cập nhật tiến độ bài học.',
            'progress' => $this->progressPayload($progress),
        ]);
    }

    /**
     * @return array{0: StudentProfile, 1: PortalAuthToken}|JsonResponse
     */
    protected function resolveContext(Request $request): array|JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'student_code' => ['required', 'string', 'max:50'],
            'portal_access_token' => ['required', 'string', 'size:64'],
        ]);

        $phone = preg_replace('/\s+/', '', $validated['phone']);
        $studentCode = trim($validated['student_code']);

        $student = StudentProfile::query()
            ->with(['person', 'guardians.guardianPerson', 'enrollments'])
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

        $portalAccess = PortalAuthToken::query()
            ->where('student_profile_id', $student->id)
            ->where('phone', $phone)
            ->where('student_code', $studentCode)
            ->where('access_token_hash', hash('sha256', $validated['portal_access_token']))
            ->whereNotNull('verified_at')
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->first();

        if (! $portalAccess) {
            return response()->json([
                'message' => 'Phiên portal không hợp lệ hoặc đã hết hạn. Vui lòng xác thực lại.',
            ], 401);
        }

        return [$student, $portalAccess];
    }

    protected function resolveLesson(StudentProfile $student, string $slug): ?VideoLesson
    {
        $courseIds = $student->enrollments
            ->where('status', 'active')
            ->pluck('course_id')
            ->filter()
            ->unique()
            ->values();

        return VideoLesson::query()
            ->with(['course', 'courseModule'])
            ->where('tenant_id', $student->tenant_id)
            ->where('slug', $slug)
            ->where('status', 'published')
            ->whereIn('access_level', ['public', 'student'])
            ->whereIn('course_id', $courseIds)
            ->first();
    }

    protected function lessonPayload(VideoLesson $lesson, ?VideoLessonProgress $progress): array
    {
        return [
            'title' => $lesson->title,
            'slug' => $lesson->slug,
            'summary' => $lesson->summary,
            'course' => $lesson->course?->name,
            'module' => $lesson->courseModule?->title,
            'video_provider' => $lesson->video_provider,
            'video_url' => $lesson->video_url,
            'duration_minutes' => $lesson->duration_minutes,
            'resources' => $lesson->resources ?? [],
            'progress' => $this->progressPayload($progress),
        ];
    }

    protected function progressPayload(?VideoLessonProgress $progress): array
    {
        return [
            'status' => $progress?->status ?? 'not_started',
            'progress_percent' => (int) ($progress?->progress_percent ?? 0),
            'last_position_seconds' => (int) ($progress?->last_position_seconds ?? 0),
            'last_watched_at' => $progress?->last_watched_at?->toDateTimeString(),
            'completed_at' => $progress?->completed_at?->toDateTimeString(),
        ];
    }
}
