<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentQuestion extends Model
{
    use HasUlids;

    protected $fillable = [
        'tenant_id',
        'assessment_id',
        'question_id',
        'sort_order',
        'score',
        'is_required',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'score' => 'decimal:2',
            'is_required' => 'boolean',
            'metadata' => 'array',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function assessment(): BelongsTo { return $this->belongsTo(Assessment::class); }
    public function question(): BelongsTo { return $this->belongsTo(Question::class); }
    public function attemptAnswers(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(QuizAttemptAnswer::class); }
}
