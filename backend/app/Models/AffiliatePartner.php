<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AffiliatePartner extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'person_id',
        'organization_id',
        'name',
        'code',
        'partner_type',
        'default_commission_percent',
        'status',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'default_commission_percent' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function person(): BelongsTo { return $this->belongsTo(Person::class); }
    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
    public function links(): HasMany { return $this->hasMany(AffiliateLink::class); }
    public function clicks(): HasMany { return $this->hasMany(AffiliateClick::class); }
    public function commissions(): HasMany { return $this->hasMany(AffiliateCommission::class); }
}
