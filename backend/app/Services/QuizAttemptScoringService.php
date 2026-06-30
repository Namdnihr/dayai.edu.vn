<?php

namespace App\Services;

use App\Models\AssessmentResult;
use App\Models\QuizAttempt;

class QuizAttemptScoringService
{
    public function recalculate(QuizAttempt $attempt, bool $markGraded = false): QuizAttempt
    {
        $attempt->loadMissing(['assessment.assessmentQuestions', 'answers', 'studentProfile']);

        $score = (float) $attempt->answers->sum(fn ($answer) => (float) $answer->score_awarded);
        $correctCount = $attempt->answers->where('is_correct', true)->count();
        $maxScore = (float) $attempt->assessment?->assessmentQuestions->sum(fn ($item) => (float) $item->score);
        $questionCount = (int) $attempt->assessment?->assessmentQuestions->count();

        $attempt->forceFill([
            'score' => $score,
            'max_score' => $maxScore,
            'correct_count' => $correctCount,
            'question_count' => $questionCount,
            'status' => $markGraded ? 'graded' : $attempt->status,
            'submitted_at' => $attempt->submitted_at ?? now(),
        ])->save();

        if ($markGraded) { $this->syncAssessmentResult($attempt); }

        return $attempt->refresh();
    }

    public function syncAssessmentResult(QuizAttempt $attempt): void
    {
        $attempt->loadMissing(['assessment', 'answers', 'studentProfile']);

        $percent = (float) $attempt->max_score > 0 ? ((float) $attempt->score / (float) $attempt->max_score) * 100 : 0;
        $level = match (true) { $percent >= 85 => 'excellent', $percent >= 60 => 'on_track', default => 'needs_support' };
        $feedback = $attempt->answers->pluck('teacher_feedback')->filter()->implode("\n");

        AssessmentResult::query()->updateOrCreate(
            ['tenant_id' => $attempt->tenant_id, 'assessment_id' => $attempt->assessment_id, 'student_profile_id' => $attempt->student_profile_id],
            [
                'enrollment_id' => $attempt->enrollment_id,
                'score' => $attempt->score,
                'max_score' => $attempt->max_score,
                'level' => $level,
                'status' => 'published',
                'feedback' => $feedback !== '' ? $feedback : "K\u{1EBF}t qu\u{1EA3} \u{111}\u{1B0}\u{1EE3}c c\u{1EAD}p nh\u{1EAD}t t\u{1EEB} l\u{1B0}\u{1EE3}t l\u{E0}m b\u{E0}i " . $attempt->attempt_code . '.',
                'strengths' => $level === 'excellent' ? "Ho\u{E0}n th\u{E0}nh t\u{1ED1}t b\u{E0}i ki\u{1EC3}m tra v\u{E0} th\u{1EC3} hi\u{1EC7}n kh\u{1EA3} n\u{103}ng v\u{1EAD}n d\u{1EE5}ng ki\u{1EBF}n th\u{1EE9}c." : null,
                'improvements' => $level === 'needs_support' ? "C\u{1EA7}n \u{F4}n l\u{1EA1}i ki\u{1EBF}n th\u{1EE9}c n\u{1EC1}n t\u{1EA3}ng v\u{E0} th\u{1EF1}c h\u{E0}nh th\u{EA}m v\u{1EDB}i mentor." : null,
                'assessed_at' => now(),
            ],
        );
    }
}
