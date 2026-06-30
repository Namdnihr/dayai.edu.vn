<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'subtitle',
        'slug',
        'course_code',
        'audience_type',
        'level',
        'learning_format',
        'short_description',
        'description',
        'outcomes',
        'duration_hours',
        'default_session_count',
        'default_price_vnd',
        'price_label',
        'thumbnail_url',
        'hero_image_url',
        'who_should_join',
        'prerequisites',
        'tools_covered',
        'primary_cta',
        'is_featured',
        'sort_order',
        'seo_title',
        'seo_description',
        'canonical_url',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'outcomes' => 'array',
            'who_should_join' => 'array',
            'prerequisites' => 'array',
            'tools_covered' => 'array',
            'duration_hours' => 'integer',
            'default_session_count' => 'integer',
            'default_price_vnd' => 'integer',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function modules(): HasMany { return $this->hasMany(CourseModule::class)->orderBy('sort_order'); }
    public function videoLessons(): HasMany { return $this->hasMany(VideoLesson::class)->orderBy('sort_order'); }
    public function questionBanks(): HasMany { return $this->hasMany(QuestionBank::class); }
    public function questions(): HasMany { return $this->hasMany(Question::class); }
    public function classGroups(): HasMany { return $this->hasMany(ClassGroup::class); }
    public function enrollments(): HasMany { return $this->hasMany(Enrollment::class); }
    public function certificates(): HasMany { return $this->hasMany(Certificate::class); }
}
