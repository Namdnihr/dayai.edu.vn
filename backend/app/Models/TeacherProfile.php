<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherProfile extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'person_id',
        'teacher_code',
        'title',
        'bio',
        'specialties',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'specialties' => 'array',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function person(): BelongsTo { return $this->belongsTo(Person::class); }
    public function classGroups(): HasMany { return $this->hasMany(ClassGroup::class); }
    public function classSessions(): HasMany { return $this->hasMany(ClassSession::class); }
    public function assessmentResults(): HasMany { return $this->hasMany(AssessmentResult::class); }
    public function teacherComments(): HasMany { return $this->hasMany(TeacherComment::class); }
    public function progressReports(): HasMany { return $this->hasMany(ProgressReport::class); }
}
