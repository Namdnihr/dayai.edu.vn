<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'question_bank_id',
        'course_id',
        'course_module_id',
        'video_lesson_id',
        'question_type',
        'difficulty',
        'status',
        'prompt',
        'explanation',
        'default_score',
        'time_limit_seconds',
        'tags',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'default_score' => 'decimal:2',
            'time_limit_seconds' => 'integer',
            'tags' => 'array',
            'metadata' => 'array',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function questionBank(): BelongsTo { return $this->belongsTo(QuestionBank::class); }
    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
    public function courseModule(): BelongsTo { return $this->belongsTo(CourseModule::class); }
    public function videoLesson(): BelongsTo { return $this->belongsTo(VideoLesson::class); }
    public function options(): HasMany { return $this->hasMany(QuestionOption::class)->orderBy('sort_order'); }
    public function assessments(): BelongsToMany
    {
        return $this->belongsToMany(Assessment::class, 'assessment_questions')
            ->withPivot(['tenant_id', 'sort_order', 'score', 'is_required', 'metadata'])
            ->withTimestamps();
    }
    public function assessmentQuestions(): HasMany { return $this->hasMany(AssessmentQuestion::class); }
}
