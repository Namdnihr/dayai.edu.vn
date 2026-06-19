<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassGroup extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'course_id',
        'teacher_profile_id',
        'class_code',
        'name',
        'learning_format',
        'start_date',
        'end_date',
        'max_students',
        'status',
        'schedule_note',
        'location',
        'organization_id',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'max_students' => 'integer',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
    public function teacherProfile(): BelongsTo { return $this->belongsTo(TeacherProfile::class); }
    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
    public function sessions(): HasMany { return $this->hasMany(ClassSession::class); }
    public function enrollments(): HasMany { return $this->hasMany(Enrollment::class); }
    public function certificates(): HasMany { return $this->hasMany(Certificate::class); }
}
