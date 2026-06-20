<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateCommission extends Model
{
    use HasUlids;

    protected $fillable = [
        'tenant_id',
        'affiliate_partner_id',
        'affiliate_link_id',
        'lead_id',
        'order_id',
        'affiliate_code',
        'commission_percent',
        'order_total_vnd',
        'commission_vnd',
        'status',
        'approved_at',
        'paid_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'commission_percent' => 'integer',
            'order_total_vnd' => 'integer',
            'commission_vnd' => 'integer',
            'approved_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function partner(): BelongsTo { return $this->belongsTo(AffiliatePartner::class, 'affiliate_partner_id'); }
    public function link(): BelongsTo { return $this->belongsTo(AffiliateLink::class, 'affiliate_link_id'); }
    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}
