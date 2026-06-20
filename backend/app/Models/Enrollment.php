<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'student_profile_id',
        'course_id',
        'class_group_id',
        'order_id',
        'enrollment_code',
        'status',
        'enrolled_at',
        'started_at',
        'completed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'enrolled_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function studentProfile(): BelongsTo { return $this->belongsTo(StudentProfile::class); }
    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
    public function classGroup(): BelongsTo { return $this->belongsTo(ClassGroup::class); }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function attendanceRecords(): HasMany { return $this->hasMany(AttendanceRecord::class); }
    public function assessmentResults(): HasMany { return $this->hasMany(AssessmentResult::class); }
    public function teacherComments(): HasMany { return $this->hasMany(TeacherComment::class); }
    public function progressReports(): HasMany { return $this->hasMany(ProgressReport::class); }
    public function certificates(): HasMany { return $this->hasMany(Certificate::class); }
    public function videoLessonProgress(): HasMany { return $this->hasMany(VideoLessonProgress::class); }
}
