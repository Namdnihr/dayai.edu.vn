<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentResult extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'assessment_id',
        'student_profile_id',
        'enrollment_id',
        'teacher_profile_id',
        'score',
        'max_score',
        'level',
        'status',
        'feedback',
        'strengths',
        'improvements',
        'assessed_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'max_score' => 'decimal:2',
            'assessed_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function assessment(): BelongsTo { return $this->belongsTo(Assessment::class); }
    public function studentProfile(): BelongsTo { return $this->belongsTo(StudentProfile::class); }
    public function enrollment(): BelongsTo { return $this->belongsTo(Enrollment::class); }
    public function teacherProfile(): BelongsTo { return $this->belongsTo(TeacherProfile::class); }
}
