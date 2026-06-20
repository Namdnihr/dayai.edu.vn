<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseModule extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'course_id',
        'sort_order',
        'title',
        'description',
        'duration_minutes',
        'learning_objectives',
    ];

    protected function casts(): array
    {
        return [
            'learning_objectives' => 'array',
            'duration_minutes' => 'integer',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
    public function videoLessons(): HasMany { return $this->hasMany(VideoLesson::class)->orderBy('sort_order'); }
}
