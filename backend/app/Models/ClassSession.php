<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassSession extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'class_group_id',
        'course_module_id',
        'teacher_profile_id',
        'session_no',
        'title',
        'starts_at',
        'ends_at',
        'status',
        'location',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'session_no' => 'integer',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function classGroup(): BelongsTo { return $this->belongsTo(ClassGroup::class); }
    public function courseModule(): BelongsTo { return $this->belongsTo(CourseModule::class); }
    public function teacherProfile(): BelongsTo { return $this->belongsTo(TeacherProfile::class); }
    public function attendanceRecords(): HasMany { return $this->hasMany(AttendanceRecord::class); }
    public function teacherComments(): HasMany { return $this->hasMany(TeacherComment::class); }
}
