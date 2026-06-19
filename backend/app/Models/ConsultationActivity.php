<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsultationActivity extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'lead_id',
        'person_id',
        'organization_id',
        'activity_type',
        'direction',
        'subject',
        'content',
        'outcome',
        'activity_at',
        'next_follow_up_at',
        'created_by_id',
    ];

    protected function casts(): array
    {
        return [
            'activity_at' => 'datetime',
            'next_follow_up_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (ConsultationActivity $activity): void {
            if (! $activity->lead_id) {
                return;
            }

            $activity->lead?->updateQuietly([
                'last_contacted_at' => $activity->activity_at,
                'next_follow_up_at' => $activity->next_follow_up_at,
            ]);
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

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
