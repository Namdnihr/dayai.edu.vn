<?php

namespace App\Services;

use App\Models\AffiliateClick;
use App\Models\AffiliateCommission;
use App\Models\AffiliateLink;
use App\Models\AffiliatePartner;
use App\Models\Lead;
use App\Models\Order;

class AffiliateCommissionService
{
    public function createPendingCommissionForOrder(Order $order): ?AffiliateCommission
    {
        $lead = $order->lead;

        if (! $lead instanceof Lead || blank($lead->affiliate_code)) {
            return null;
        }

        if ($order->affiliateCommission()->exists()) {
            return $order->affiliateCommission;
        }

        $partner = AffiliatePartner::query()
            ->where('tenant_id', $order->tenant_id)
            ->where('code', $lead->affiliate_code)
            ->where('status', 'active')
            ->first();

        $link = AffiliateLink::query()
            ->where('tenant_id', $order->tenant_id)
            ->where(function ($query) use ($lead, $partner): void {
                $query->where('code', $lead->referral_code)
                    ->when($partner, fn ($linkQuery) => $linkQuery->orWhere('affiliate_partner_id', $partner->id));
            })
            ->where('status', 'active')
            ->first();

        $commissionPercent = (int) (
            $link?->commission_percent
            ?? $partner?->default_commission_percent
            ?? 10
        );
        $orderTotal = (int) $order->total_vnd;

        return AffiliateCommission::query()->create([
            'tenant_id' => $order->tenant_id,
            'affiliate_partner_id' => $partner?->id ?? $link?->affiliate_partner_id,
            'affiliate_link_id' => $link?->id,
            'lead_id' => $lead->id,
            'order_id' => $order->id,
            'affiliate_code' => $lead->affiliate_code,
            'commission_percent' => $commissionPercent,
            'order_total_vnd' => $orderTotal,
            'commission_vnd' => (int) floor($orderTotal * $commissionPercent / 100),
            'status' => 'pending',
        ]);
    }

    public function recordClickForLead(Lead $lead, ?AffiliatePartner $partner, ?AffiliateLink $link, ?string $ipAddress = null, ?string $userAgent = null): AffiliateClick
    {
        return AffiliateClick::query()->create([
            'tenant_id' => $lead->tenant_id,
            'affiliate_partner_id' => $partner?->id ?? $link?->affiliate_partner_id,
            'affiliate_link_id' => $link?->id,
            'lead_id' => $lead->id,
            'affiliate_code' => $lead->affiliate_code,
            'referral_code' => $lead->referral_code,
            'click_id' => $lead->click_id,
            'landing_page' => $lead->landing_page,
            'referrer_url' => $lead->referrer_url,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'clicked_at' => now(),
        ]);
    }
}
