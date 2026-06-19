<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceRecord extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'class_session_id',
        'student_profile_id',
        'enrollment_id',
        'status',
        'checked_in_at',
        'minutes_late',
        'absence_reason',
        'teacher_note',
        'checked_by_id',
    ];

    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
            'minutes_late' => 'integer',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function classSession(): BelongsTo { return $this->belongsTo(ClassSession::class); }
    public function studentProfile(): BelongsTo { return $this->belongsTo(StudentProfile::class); }
    public function enrollment(): BelongsTo { return $this->belongsTo(Enrollment::class); }
    public function checkedBy(): BelongsTo { return $this->belongsTo(User::class, 'checked_by_id'); }
}
