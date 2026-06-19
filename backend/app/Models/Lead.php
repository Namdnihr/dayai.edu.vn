<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Lead extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'lead_source_id',
        'campaign_id',
        'person_id',
        'organization_id',
        'assigned_user_id',
        'lead_type',
        'status',
        'priority',
        'full_name',
        'phone',
        'email',
        'company_name',
        'interested_course_id',
        'learning_goal',
        'message',
        'preferred_contact_method',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'last_contacted_at',
        'next_follow_up_at',
        'converted_at',
        'lost_reason',
        'metadata',
        'created_by_id',
        'updated_by_id',
    ];

    protected function casts(): array
    {
        return [
            'last_contacted_at' => 'datetime',
            'next_follow_up_at' => 'datetime',
            'converted_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Lead $lead): void {
            $user = Auth::user();

            if ($user) {
                $lead->created_by_id ??= $user->id;
                $lead->updated_by_id ??= $user->id;
                $lead->tenant_id ??= $user->tenant_id;
            }
        });

        static::updating(function (Lead $lead): void {
            if (Auth::id()) {
                $lead->updated_by_id = Auth::id();
            }
        });

        static::created(function (Lead $lead): void {
            $lead->writeActivityLog('lead.created', 'Tạo lead mới');

            if ($lead->assigned_user_id) {
                LeadAssignment::query()->create([
                    'tenant_id' => $lead->tenant_id,
                    'lead_id' => $lead->id,
                    'assigned_to_user_id' => $lead->assigned_user_id,
                    'assigned_by_user_id' => Auth::id(),
                    'assigned_at' => now(),
                    'note' => 'Phân công khi tạo lead',
                ]);
            }
        });

        static::updated(function (Lead $lead): void {
            if ($lead->wasChanged('status')) {
                $lead->writeActivityLog('lead.status_changed', 'Đổi trạng thái lead');
            }

            if ($lead->wasChanged('assigned_user_id') && $lead->assigned_user_id) {
                LeadAssignment::query()->create([
                    'tenant_id' => $lead->tenant_id,
                    'lead_id' => $lead->id,
                    'assigned_to_user_id' => $lead->assigned_user_id,
                    'assigned_by_user_id' => Auth::id(),
                    'assigned_at' => now(),
                    'note' => 'Đổi tư vấn viên phụ trách',
                ]);
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class, 'lead_source_id');
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(LeadAssignment::class);
    }

    public function consultationActivities(): HasMany
    {
        return $this->hasMany(ConsultationActivity::class);
    }

    public function trialRegistrations(): HasMany
    {
        return $this->hasMany(TrialRegistration::class);
    }

    public function writeActivityLog(string $action, string $description): void
    {
        ActivityLog::query()->create([
            'tenant_id' => $this->tenant_id,
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => self::class,
            'subject_id' => $this->id,
            'description' => $description,
            'old_values' => $this->getOriginal(),
            'new_values' => $this->getAttributes(),
        ]);
    }
}
