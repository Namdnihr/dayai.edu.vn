<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AffiliateLink extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'affiliate_partner_id',
        'course_id',
        'code',
        'campaign',
        'target_url',
        'commission_percent',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'commission_percent' => 'integer',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function partner(): BelongsTo { return $this->belongsTo(AffiliatePartner::class, 'affiliate_partner_id'); }
    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
    public function clicks(): HasMany { return $this->hasMany(AffiliateClick::class); }
    public function commissions(): HasMany { return $this->hasMany(AffiliateCommission::class); }
}
