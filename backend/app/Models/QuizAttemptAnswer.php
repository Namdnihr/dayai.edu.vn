<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttemptAnswer extends Model
{
    use HasUlids;

    protected $fillable = [
        'tenant_id',
        'quiz_attempt_id',
        'assessment_question_id',
        'question_id',
        'selected_option_ids',
        'answer_text',
        'is_correct',
        'score_awarded',
        'teacher_feedback',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'selected_option_ids' => 'array',
            'is_correct' => 'boolean',
            'score_awarded' => 'decimal:2',
            'metadata' => 'array',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function quizAttempt(): BelongsTo { return $this->belongsTo(QuizAttempt::class); }
    public function assessmentQuestion(): BelongsTo { return $this->belongsTo(AssessmentQuestion::class); }
    public function question(): BelongsTo { return $this->belongsTo(Question::class); }
}
