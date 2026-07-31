<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\PortalAuthToken;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\StudentProfile;
use App\Services\QuizAttemptScoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PortalQuizController extends Controller
{
    public function start(Request $request, Assessment $assessment): JsonResponse
    {
        $context = $this->resolvePortalContext($request);

        if ($context instanceof JsonResponse) {
            return $context;
        }

        [$student] = $context;

        $assessment = $this->assessmentQuery($student)
            ->whereKey($assessment->id)
            ->first();

        if (! $assessment) {
            return response()->json(['message' => 'Không tìm thấy bài kiểm tra phù hợp với học viên.'], 404);
        }

        $assessment->load(['assessmentQuestions.question.options']);

        if ($assessment->assessmentQuestions->isEmpty()) {
            return response()->json(['message' => 'Bài kiểm tra chưa có câu hỏi.'], 422);
        }

        $enrollment = $student->enrollments
            ->first(fn ($enrollment) => $assessment->course_id === null || $enrollment->course_id === $assessment->course_id);

        $attempt = DB::transaction(function () use ($assessment, $student, $enrollment): QuizAttempt {
            $attemptNo = QuizAttempt::query()
                ->where('assessment_id', $assessment->id)
                ->where('student_profile_id', $student->id)
                ->count() + 1;

            return QuizAttempt::query()->create([
                'tenant_id' => $student->tenant_id,
                'assessment_id' => $assessment->id,
                'student_profile_id' => $student->id,
                'enrollment_id' => $enrollment?->id,
                'attempt_code' => 'QA-' . Str::upper(Str::random(10)),
                'attempt_no' => $attemptNo,
                'status' => 'in_progress',
                'max_score' => $assessment->assessmentQuestions->sum(fn ($item) => (float) $item->score),
                'question_count' => $assessment->assessmentQuestions->count(),
                'started_at' => now(),
            ]);
        });

        return response()->json([
            'attempt' => $this->serializeAttempt($attempt->load('assessment')),
            'assessment' => $this->serializeAssessment($assessment, includeQuestions: true),
        ]);
    }

    public function submit(Request $request, string $attemptCode): JsonResponse
    {
        $context = $this->resolvePortalContext($request);

        if ($context instanceof JsonResponse) {
            return $context;
        }

        [$student] = $context;

        $validated = $request->validate([
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.assessment_question_id' => ['required', 'string', 'exists:assessment_questions,id'],
            'answers.*.selected_option_ids' => ['nullable', 'array'],
            'answers.*.selected_option_ids.*' => ['string', 'exists:question_options,id'],
            'answers.*.answer_text' => ['nullable', 'string', 'max:5000'],
        ]);

        $attempt = QuizAttempt::query()
            ->with(['assessment.assessmentQuestions.question.options'])
            ->where('attempt_code', $attemptCode)
            ->where('student_profile_id', $student->id)
            ->where('tenant_id', $student->tenant_id)
            ->first();

        if (! $attempt) {
            return response()->json(['message' => 'Không tìm thấy lượt làm bài.'], 404);
        }

        if ($attempt->status !== 'in_progress') {
            return response()->json([
                'message' => 'Lượt làm bài này không còn ở trạng thái đang làm.',
                'attempt' => $this->serializeAttempt($attempt),
            ], 422);
        }

        $answerMap = collect($validated['answers'])->keyBy('assessment_question_id');

        DB::transaction(function () use ($attempt, $answerMap): void {
            $score = 0;
            $correctCount = 0;

            foreach ($attempt->assessment->assessmentQuestions as $assessmentQuestion) {
                $question = $assessmentQuestion->question;
                $submitted = $answerMap->get($assessmentQuestion->id, []);
                $selectedOptionIds = collect($submitted['selected_option_ids'] ?? [])
                    ->filter()
                    ->map(fn ($id) => (string) $id)
                    ->unique()
                    ->sort()
                    ->values()
                    ->all();
                $answerText = trim((string) ($submitted['answer_text'] ?? ''));

                [$isCorrect, $scoreAwarded] = $this->gradeQuestion($assessmentQuestion, $selectedOptionIds, $answerText);

                if ($isCorrect === true) {
                    $correctCount++;
                }

                $score += $scoreAwarded;

                QuizAttemptAnswer::query()->updateOrCreate(
                    [
                        'quiz_attempt_id' => $attempt->id,
                        'assessment_question_id' => $assessmentQuestion->id,
                    ],
                    [
                        'tenant_id' => $attempt->tenant_id,
                        'question_id' => $question->id,
                        'selected_option_ids' => $selectedOptionIds,
                        'answer_text' => $answerText !== '' ? $answerText : null,
                        'is_correct' => $isCorrect,
                        'score_awarded' => $scoreAwarded,
                    ],
                );
            }

            $attempt->forceFill([
                'status' => 'submitted',
                'score' => $score,
                'correct_count' => $correctCount,
                'submitted_at' => now(),
            ])->save();
        });

        $attempt->refresh()->load(['assessment', 'answers.question.options']);

        if ($this->isFullyAutoGradable($attempt)) {
            app(QuizAttemptScoringService::class)->syncAssessmentResult($attempt);
        }

        return response()->json([
            'message' => 'Đã nộp bài kiểm tra thành công.',
            'attempt' => $this->serializeAttempt($attempt, includeAnswers: true),
        ]);
    }

    /**
     * @return array{0: StudentProfile, 1: PortalAuthToken}|JsonResponse
     */
    protected function resolvePortalContext(Request $request): array|JsonResponse
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
            return response()->json(['message' => 'Không tìm thấy học viên với thông tin đã nhập.'], 404);
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
            return response()->json(['message' => 'Phiên portal không hợp lệ hoặc đã hết hạn. Vui lòng xác thực lại.'], 401);
        }

        return [$student, $portalAccess];
    }

    protected function assessmentQuery(StudentProfile $student)
    {
        $courseIds = $student->enrollments->pluck('course_id')->filter()->unique();
        $classGroupIds = $student->enrollments->pluck('class_group_id')->filter()->unique();

        return Assessment::query()
            ->withCount('assessmentQuestions')
            ->where('tenant_id', $student->tenant_id)
            ->where('status', 'published')
            ->whereIn('assessment_type', ['entry', 'quiz', 'practice', 'final'])
            ->where(function ($query) use ($courseIds, $classGroupIds): void {
                $query->whereIn('course_id', $courseIds)
                    ->orWhereIn('class_group_id', $classGroupIds);
            });
    }

    protected function gradeQuestion(AssessmentQuestion $assessmentQuestion, array $selectedOptionIds, string $answerText): array
    {
        $question = $assessmentQuestion->question;
        $score = (float) $assessmentQuestion->score;

        if (in_array($question->question_type, ['single_choice', 'multiple_choice', 'true_false'], true)) {
            $correctOptionIds = $question->options
                ->where('is_correct', true)
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->sort()
                ->values()
                ->all();

            $isCorrect = $selectedOptionIds === $correctOptionIds;

            return [$isCorrect, $isCorrect ? $score : 0.0];
        }

        return [null, 0.0];
    }

    protected function isFullyAutoGradable(QuizAttempt $attempt): bool
    {
        $attempt->loadMissing('assessment.assessmentQuestions.question');

        return $attempt->assessment->assessmentQuestions
            ->every(fn (AssessmentQuestion $assessmentQuestion): bool => in_array(
                $assessmentQuestion->question->question_type,
                ['single_choice', 'multiple_choice', 'true_false'],
                true,
            ));
    }

    protected function serializeAssessment(Assessment $assessment, bool $includeQuestions = false): array
    {
        $payload = [
            'id' => $assessment->id,
            'title' => $assessment->title,
            'assessment_type' => $assessment->assessment_type,
            'course' => $assessment->course?->name,
            'module' => $assessment->courseModule?->title,
            'video' => $assessment->videoLesson?->title,
            'description' => $assessment->description,
            'max_score' => (float) $assessment->max_score,
            'question_count' => (int) ($assessment->assessment_questions_count ?? $assessment->assessmentQuestions->count()),
            'assessment_at' => $assessment->assessment_at?->toDateTimeString(),
        ];

        if ($includeQuestions) {
            $payload['questions'] = $assessment->assessmentQuestions
                ->sortBy('sort_order')
                ->map(fn (AssessmentQuestion $assessmentQuestion): array => [
                    'assessment_question_id' => $assessmentQuestion->id,
                    'question_id' => $assessmentQuestion->question_id,
                    'sort_order' => $assessmentQuestion->sort_order,
                    'score' => (float) $assessmentQuestion->score,
                    'question_type' => $assessmentQuestion->question->question_type,
                    'difficulty' => $assessmentQuestion->question->difficulty,
                    'prompt' => $assessmentQuestion->question->prompt,
                    'options' => $assessmentQuestion->question->options
                        ->map(fn ($option): array => [
                            'id' => $option->id,
                            'sort_order' => $option->sort_order,
                            'content' => $option->content,
                        ])
                        ->values(),
                ])
                ->values();
        }

        return $payload;
    }

    protected function serializeAttempt(QuizAttempt $attempt, bool $includeAnswers = false): array
    {
        $payload = [
            'attempt_code' => $attempt->attempt_code,
            'assessment_id' => $attempt->assessment_id,
            'assessment' => $attempt->assessment?->title,
            'attempt_no' => $attempt->attempt_no,
            'status' => $attempt->status,
            'score' => $attempt->score !== null ? (float) $attempt->score : null,
            'max_score' => (float) $attempt->max_score,
            'correct_count' => $attempt->correct_count,
            'question_count' => $attempt->question_count,
            'started_at' => $attempt->started_at?->toDateTimeString(),
            'submitted_at' => $attempt->submitted_at?->toDateTimeString(),
        ];

        if ($includeAnswers) {
            $payload['answers'] = $attempt->answers
                ->map(fn (QuizAttemptAnswer $answer): array => [
                    'question_id' => $answer->question_id,
                    'prompt' => $answer->question?->prompt,
                    'selected_option_ids' => $answer->selected_option_ids ?? [],
                    'answer_text' => $answer->answer_text,
                    'is_correct' => $answer->is_correct,
                    'score_awarded' => (float) $answer->score_awarded,
                ])
                ->values();
        }

        return $payload;
    }
}
