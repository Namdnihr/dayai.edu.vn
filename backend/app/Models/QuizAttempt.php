<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizAttempt extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'assessment_id',
        'student_profile_id',
        'enrollment_id',
        'attempt_code',
        'attempt_no',
        'status',
        'score',
        'max_score',
        'correct_count',
        'question_count',
        'started_at',
        'submitted_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'attempt_no' => 'integer',
            'score' => 'decimal:2',
            'max_score' => 'decimal:2',
            'correct_count' => 'integer',
            'question_count' => 'integer',
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function assessment(): BelongsTo { return $this->belongsTo(Assessment::class); }
    public function studentProfile(): BelongsTo { return $this->belongsTo(StudentProfile::class); }
    public function enrollment(): BelongsTo { return $this->belongsTo(Enrollment::class); }
    public function answers(): HasMany { return $this->hasMany(QuizAttemptAnswer::class); }
}
