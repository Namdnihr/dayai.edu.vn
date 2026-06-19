<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuardianRelation extends Model
{
    use HasUlids;

    protected $fillable = [
        'tenant_id',
        'guardian_person_id',
        'student_profile_id',
        'relation_type',
        'is_primary',
        'can_view_finance',
        'can_view_progress',
        'can_receive_notifications',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'can_view_finance' => 'boolean',
            'can_view_progress' => 'boolean',
            'can_receive_notifications' => 'boolean',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function guardianPerson(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'guardian_person_id');
    }

    public function studentProfile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class);
    }
}
