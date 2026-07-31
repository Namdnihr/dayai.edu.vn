<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\PortalAuthToken;
use App\Models\StudentProfile;
use App\Models\VideoLesson;
use App\Models\VideoLessonProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $courseLessons = VideoLesson::query()
            ->select('video_lessons.*')
            ->leftJoin('course_modules', 'course_modules.id', '=', 'video_lessons.course_module_id')
            ->with(['courseModule'])
            ->where('video_lessons.tenant_id', $student->tenant_id)
            ->where('video_lessons.course_id', $lesson->course_id)
            ->where('video_lessons.status', 'published')
            ->whereIn('video_lessons.access_level', ['public', 'student'])
            ->orderByRaw('COALESCE(course_modules.sort_order, 999999)')
            ->orderBy('video_lessons.sort_order')
            ->orderBy('video_lessons.created_at')
            ->get();

        $progressRecords = VideoLessonProgress::query()
            ->where('student_profile_id', $student->id)
            ->whereIn('video_lesson_id', $courseLessons->pluck('id'))
            ->get()
            ->keyBy('video_lesson_id');

        $progress = $progressRecords->get($lesson->id);

        return response()->json([
            'access_role' => $portalAccess->access_role,
            'lesson' => $this->lessonPayload($lesson, $progress),
            'navigation' => $this->navigationPayload($lesson, $courseLessons, $progressRecords),
            'outline' => $this->outlinePayload($courseLessons, $progressRecords),
            'assessments' => $this->assessmentPayload($lesson, $student),
        ]);
    }

    public function updateProgress(Request $request, string $slug): JsonResponse
    {
        $validated = $request->validate([
            'progress_percent' => ['required', 'integer', 'min:0', 'max:100'],
            'last_position_seconds' => ['nullable', 'integer', 'min:0'],
            'checkpoint_answers' => ['sometimes', 'array', 'max:50'],
            'checkpoint_answers.*.checkpoint_id' => ['required', 'string', 'max:100'],
            'checkpoint_answers.*.selected_option_id' => ['required', 'string', 'max:100'],
            'learner_notes' => ['sometimes', 'nullable', 'string', 'max:20000'],
            'practice_sessions' => ['sometimes', 'array', 'max:20'],
            'practice_sessions.*.prompt_id' => ['required', 'string', 'max:100'],
            'practice_sessions.*.result' => ['nullable', 'string', 'max:12000'],
            'practice_sessions.*.reflection' => ['nullable', 'string', 'max:5000'],
            'practice_sessions.*.confidence' => ['nullable', 'integer', 'min:1', 'max:5'],
            'practice_sessions.*.updated_at' => ['nullable', 'date'],
            'attention_metrics' => ['sometimes', 'array'],
            'attention_metrics.hidden_pause_count' => ['sometimes', 'integer', 'min:0', 'max:100000'],
            'attention_metrics.idle_pause_count' => ['sometimes', 'integer', 'min:0', 'max:100000'],
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
        $checkpointDefinitions = collect(data_get($lesson->metadata, 'interactive_learning.checkpoints', []))
            ->filter(fn ($checkpoint): bool => is_array($checkpoint) && filled($checkpoint['id'] ?? null))
            ->keyBy('id');
        $existingProgress = VideoLessonProgress::query()
            ->firstOrNew([
                'student_profile_id' => $student->id,
                'video_lesson_id' => $lesson->id,
            ]);
        $checkpointAnswers = array_key_exists('checkpoint_answers', $validated)
            ? collect($validated['checkpoint_answers'])
                ->filter(function (array $answer) use ($checkpointDefinitions): bool {
                    $checkpoint = $checkpointDefinitions->get($answer['checkpoint_id']);

                    if (! is_array($checkpoint)) {
                        return false;
                    }

                    return collect($checkpoint['options'] ?? [])
                        ->contains(fn ($option): bool => is_array($option) && ($option['id'] ?? null) === $answer['selected_option_id']);
                })
                ->map(function (array $answer) use ($checkpointDefinitions): array {
                    $checkpoint = $checkpointDefinitions->get($answer['checkpoint_id']);

                    return [
                        'checkpoint_id' => $answer['checkpoint_id'],
                        'selected_option_id' => $answer['selected_option_id'],
                        'is_correct' => ($checkpoint['correct_option_id'] ?? null) === $answer['selected_option_id'],
                        'answered_at' => now()->toIso8601String(),
                    ];
                })
                ->unique('checkpoint_id')
                ->values()
                ->all()
            : data_get($existingProgress->interaction_state, 'checkpoint_answers', []);
        $progressPercent = (int) $validated['progress_percent'];

        if ($progressPercent >= 95 && $checkpointDefinitions->isNotEmpty()) {
            $answeredCheckpointIds = collect($checkpointAnswers)->pluck('checkpoint_id')->unique();
            $missingCheckpointIds = $checkpointDefinitions->keys()->diff($answeredCheckpointIds);

            if ($missingCheckpointIds->isNotEmpty()) {
                return response()->json([
                    'message' => 'Bạn cần trả lời đủ các câu hỏi nhanh trước khi hoàn thành bài học.',
                    'pending_checkpoints' => $missingCheckpointIds->values()->all(),
                ], 422);
            }
        }

        $status = $progressPercent >= 95 ? 'completed' : 'in_progress';

        $interactionState = $existingProgress->interaction_state ?? [];
        $interactionState['checkpoint_answers'] = $checkpointAnswers;

        if (array_key_exists('practice_sessions', $validated)) {
            $interactionState['practice_sessions'] = collect($validated['practice_sessions'])
                ->map(fn (array $session): array => [
                    'prompt_id' => $session['prompt_id'],
                    'result' => trim((string) ($session['result'] ?? '')),
                    'reflection' => trim((string) ($session['reflection'] ?? '')),
                    'confidence' => isset($session['confidence']) ? (int) $session['confidence'] : null,
                    'updated_at' => $session['updated_at'] ?? now()->toIso8601String(),
                ])
                ->unique('prompt_id')
                ->values()
                ->all();
        }

        if (array_key_exists('attention_metrics', $validated)) {
            $interactionState['attention_metrics'] = [
                'hidden_pause_count' => (int) data_get($validated, 'attention_metrics.hidden_pause_count', 0),
                'idle_pause_count' => (int) data_get($validated, 'attention_metrics.idle_pause_count', 0),
            ];
        }

        $existingProgress->fill([
            'tenant_id' => $student->tenant_id,
            'enrollment_id' => $enrollment?->id,
            'status' => $status,
            'progress_percent' => $progressPercent,
            'last_position_seconds' => (int) ($validated['last_position_seconds'] ?? 0),
            'interaction_state' => $interactionState,
            'started_at' => $existingProgress->started_at ?? now(),
            'completed_at' => $status === 'completed' ? ($existingProgress->completed_at ?? now()) : null,
            'last_watched_at' => now(),
        ]);

        if (array_key_exists('learner_notes', $validated)) {
            $existingProgress->learner_notes = $validated['learner_notes'];
        }

        $existingProgress->save();

        return response()->json([
            'message' => 'Đã cập nhật tiến độ bài học.',
            'progress' => $this->progressPayload($existingProgress),
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
            'video_url' => $this->resolveVideoUrl($lesson),
            'duration_minutes' => $lesson->duration_minutes,
            'resources' => $this->resolveResources($lesson->resources ?? []),
            'interactive_learning' => data_get($lesson->metadata, 'interactive_learning'),
            'progress' => $this->progressPayload($progress),
        ];
    }

    protected function navigationPayload(VideoLesson $lesson, $courseLessons, $progressRecords): array
    {
        $orderedLessons = $courseLessons->values();
        $currentIndex = $orderedLessons->search(fn (VideoLesson $courseLesson): bool => $courseLesson->id === $lesson->id);
        $previousLesson = $currentIndex !== false && $currentIndex > 0 ? $orderedLessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex !== false && $currentIndex < $orderedLessons->count() - 1 ? $orderedLessons[$currentIndex + 1] : null;

        return [
            'previous_lesson' => $previousLesson ? $this->compactLessonPayload($previousLesson, $progressRecords->get($previousLesson->id)) : null,
            'next_lesson' => $nextLesson ? $this->compactLessonPayload($nextLesson, $progressRecords->get($nextLesson->id)) : null,
        ];
    }

    protected function outlinePayload($courseLessons, $progressRecords): array
    {
        return $courseLessons
            ->groupBy(fn (VideoLesson $lesson): string => $lesson->course_module_id ?: 'uncategorized')
            ->map(function ($lessons): array {
                $firstLesson = $lessons->first();

                return [
                    'module_id' => $firstLesson?->course_module_id,
                    'module' => $firstLesson?->courseModule?->title ?? 'Bài học độc lập',
                    'lessons' => $lessons->values(),
                ];
            })
            ->values()
            ->map(fn (array $module): array => [
                'module_id' => $module['module_id'],
                'module' => $module['module'],
                'lessons' => $module['lessons']
                    ->map(fn (VideoLesson $outlineLesson): array => $this->compactLessonPayload($outlineLesson, $progressRecords->get($outlineLesson->id)))
                    ->values()
                    ->all(),
            ])
            ->all();
    }

    protected function compactLessonPayload(VideoLesson $lesson, ?VideoLessonProgress $progress): array
    {
        return [
            'title' => $lesson->title,
            'slug' => $lesson->slug,
            'duration_minutes' => $lesson->duration_minutes,
            'progress' => $this->progressPayload($progress),
        ];
    }

    protected function assessmentPayload(VideoLesson $lesson, StudentProfile $student): array
    {
        return Assessment::query()
            ->withCount('assessmentQuestions')
            ->with(['quizAttempts' => fn ($query) => $query
                ->where('student_profile_id', $student->id)
                ->latest('submitted_at')
                ->latest()])
            ->where('tenant_id', $student->tenant_id)
            ->where('status', 'published')
            ->where(function ($query) use ($lesson): void {
                $query->where('video_lesson_id', $lesson->id)
                    ->orWhere(function ($moduleQuery) use ($lesson): void {
                        $moduleQuery->whereNull('video_lesson_id')
                            ->where('course_module_id', $lesson->course_module_id);
                    });
            })
            ->orderBy('assessment_at')
            ->get()
            ->map(function (Assessment $assessment): array {
                $latestAttempt = $assessment->quizAttempts->first();

                return [
                    'id' => $assessment->id,
                    'title' => $assessment->title,
                    'assessment_type' => $assessment->assessment_type,
                    'description' => $assessment->description,
                    'max_score' => (float) $assessment->max_score,
                    'question_count' => (int) $assessment->assessment_questions_count,
                    'latest_attempt' => $latestAttempt ? [
                        'attempt_code' => $latestAttempt->attempt_code,
                        'status' => $latestAttempt->status,
                        'score' => $latestAttempt->score !== null ? (float) $latestAttempt->score : null,
                        'max_score' => (float) $latestAttempt->max_score,
                        'submitted_at' => $latestAttempt->submitted_at?->toDateTimeString(),
                    ] : null,
                ];
            })
            ->values()
            ->all();
    }

    protected function progressPayload(?VideoLessonProgress $progress): array
    {
        return [
            'status' => $progress?->status ?? 'not_started',
            'progress_percent' => (int) ($progress?->progress_percent ?? 0),
            'last_position_seconds' => (int) ($progress?->last_position_seconds ?? 0),
            'last_watched_at' => $progress?->last_watched_at?->toDateTimeString(),
            'completed_at' => $progress?->completed_at?->toDateTimeString(),
            'checkpoint_answers' => data_get($progress?->interaction_state, 'checkpoint_answers', []),
            'practice_sessions' => data_get($progress?->interaction_state, 'practice_sessions', []),
            'attention_metrics' => data_get($progress?->interaction_state, 'attention_metrics', [
                'hidden_pause_count' => 0,
                'idle_pause_count' => 0,
            ]),
            'learner_notes' => $progress?->learner_notes ?? '',
        ];
    }

    protected function resolveVideoUrl(VideoLesson $lesson): ?string
    {
        if ($lesson->video_provider === 'internal' && filled($lesson->video_storage_path)) {
            return Storage::disk('public')->url($lesson->video_storage_path);
        }

        return $lesson->video_url;
    }

    protected function resolveResources(array $resources): array
    {
        return collect($resources)
            ->filter(fn ($resource): bool => is_array($resource))
            ->map(function (array $resource): array {
                if (filled($resource['file_path'] ?? null)) {
                    $resource['url'] = Storage::disk('public')->url($resource['file_path']);
                }

                return $resource;
            })
            ->values()
            ->all();
    }
}
