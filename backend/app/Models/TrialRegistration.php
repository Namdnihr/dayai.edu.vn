<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrialRegistration extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'lead_id',
        'person_id',
        'student_profile_id',
        'course_id',
        'preferred_date',
        'preferred_time',
        'scheduled_session_id',
        'status',
        'note',
        'created_by_id',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (TrialRegistration $trialRegistration): void {
            if ($trialRegistration->lead_id && in_array($trialRegistration->status, ['scheduled', 'attended', 'converted'], true)) {
                $trialRegistration->lead?->updateQuietly([
                    'status' => 'trial_scheduled',
                ]);
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
