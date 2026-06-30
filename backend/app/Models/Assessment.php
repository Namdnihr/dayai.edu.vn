<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'course_id',
        'course_module_id',
        'video_lesson_id',
        'class_group_id',
        'title',
        'assessment_type',
        'status',
        'max_score',
        'weight_percent',
        'assessment_at',
        'description',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'max_score' => 'decimal:2',
            'weight_percent' => 'decimal:2',
            'assessment_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
    public function courseModule(): BelongsTo { return $this->belongsTo(CourseModule::class); }
    public function videoLesson(): BelongsTo { return $this->belongsTo(VideoLesson::class); }
    public function classGroup(): BelongsTo { return $this->belongsTo(ClassGroup::class); }
    public function results(): HasMany { return $this->hasMany(AssessmentResult::class); }
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'assessment_questions')
            ->withPivot(['tenant_id', 'sort_order', 'score', 'is_required', 'metadata'])
            ->withTimestamps();
    }
    public function assessmentQuestions(): HasMany { return $this->hasMany(AssessmentQuestion::class); }
    public function quizAttempts(): HasMany { return $this->hasMany(QuizAttempt::class); }
}
