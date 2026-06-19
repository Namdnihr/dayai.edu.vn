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
        'slug',
        'course_code',
        'audience_type',
        'level',
        'short_description',
        'description',
        'outcomes',
        'duration_hours',
        'default_session_count',
        'default_price_vnd',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'outcomes' => 'array',
            'duration_hours' => 'integer',
            'default_session_count' => 'integer',
            'default_price_vnd' => 'integer',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function modules(): HasMany { return $this->hasMany(CourseModule::class)->orderBy('sort_order'); }
    public function classGroups(): HasMany { return $this->hasMany(ClassGroup::class); }
    public function enrollments(): HasMany { return $this->hasMany(Enrollment::class); }
    public function certificates(): HasMany { return $this->hasMany(Certificate::class); }
}
