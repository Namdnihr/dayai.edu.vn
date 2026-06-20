<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateClick extends Model
{
    use HasUlids;

    protected $fillable = [
        'tenant_id',
        'affiliate_partner_id',
        'affiliate_link_id',
        'lead_id',
        'affiliate_code',
        'referral_code',
        'click_id',
        'landing_page',
        'referrer_url',
        'ip_address',
        'user_agent',
        'clicked_at',
    ];

    protected function casts(): array
    {
        return [
            'clicked_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function partner(): BelongsTo { return $this->belongsTo(AffiliatePartner::class, 'affiliate_partner_id'); }
    public function link(): BelongsTo { return $this->belongsTo(AffiliateLink::class, 'affiliate_link_id'); }
    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
}
