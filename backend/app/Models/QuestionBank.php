<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionBank extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'course_id',
        'course_module_id',
        'video_lesson_id',
        'name',
        'bank_type',
        'audience_type',
        'level',
        'status',
        'description',
        'tags',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
    public function courseModule(): BelongsTo { return $this->belongsTo(CourseModule::class); }
    public function videoLesson(): BelongsTo { return $this->belongsTo(VideoLesson::class); }
    public function questions(): HasMany { return $this->hasMany(Question::class); }
}
