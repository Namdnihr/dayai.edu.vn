<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'student_profile_id',
        'enrollment_id',
        'course_id',
        'class_group_id',
        'certificate_code',
        'verification_token',
        'title',
        'status',
        'final_score',
        'grade',
        'issued_at',
        'expires_at',
        'file_path',
        'notes',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'final_score' => 'decimal:2',
            'issued_at' => 'datetime',
            'expires_at' => 'date',
            'metadata' => 'array',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function studentProfile(): BelongsTo { return $this->belongsTo(StudentProfile::class); }
    public function enrollment(): BelongsTo { return $this->belongsTo(Enrollment::class); }
    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
    public function classGroup(): BelongsTo { return $this->belongsTo(ClassGroup::class); }
}
