<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgressReport extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'student_profile_id',
        'enrollment_id',
        'course_id',
        'class_group_id',
        'teacher_profile_id',
        'report_period',
        'title',
        'status',
        'overall_level',
        'progress_percent',
        'strengths',
        'improvements',
        'recommendation',
        'published_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'progress_percent' => 'integer',
            'published_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function studentProfile(): BelongsTo { return $this->belongsTo(StudentProfile::class); }
    public function enrollment(): BelongsTo { return $this->belongsTo(Enrollment::class); }
    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
    public function classGroup(): BelongsTo { return $this->belongsTo(ClassGroup::class); }
    public function teacherProfile(): BelongsTo { return $this->belongsTo(TeacherProfile::class); }
}
