<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherComment extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'student_profile_id',
        'enrollment_id',
        'class_session_id',
        'teacher_profile_id',
        'comment_type',
        'visibility',
        'title',
        'comment',
        'rating',
        'commented_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'commented_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function studentProfile(): BelongsTo { return $this->belongsTo(StudentProfile::class); }
    public function enrollment(): BelongsTo { return $this->belongsTo(Enrollment::class); }
    public function classSession(): BelongsTo { return $this->belongsTo(ClassSession::class); }
    public function teacherProfile(): BelongsTo { return $this->belongsTo(TeacherProfile::class); }
}
