<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AffiliateClick;
use App\Models\AffiliateCommission;
use App\Models\AffiliateLink;
use App\Models\AffiliatePartner;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AffiliatePortalLookupController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'partner_code' => ['required', 'string', 'max:100'],
            'link_code' => ['nullable', 'string', 'max:100'],
        ]);

        $partnerCode = trim($validated['partner_code']);
        $linkCode = trim($validated['link_code'] ?? '');

        $partner = AffiliatePartner::query()
            ->where('code', $partnerCode)
            ->where('status', 'active')
            ->first();

        if (! $partner) {
            return response()->json([
                'message' => 'Không tìm thấy đối tác affiliate đang hoạt động với mã đã nhập.',
            ], 404);
        }

        $linksQuery = AffiliateLink::query()
            ->where('affiliate_partner_id', $partner->id)
            ->where('status', 'active');

        if (filled($linkCode)) {
            $linksQuery->where('code', $linkCode);
        }

        $links = $linksQuery
            ->withCount('clicks')
            ->withSum('commissions as commission_vnd_sum', 'commission_vnd')
            ->orderBy('campaign')
            ->orderBy('code')
            ->get();

        if (filled($linkCode) && $links->isEmpty()) {
            return response()->json([
                'message' => 'Mã link không thuộc đối tác này hoặc đã ngừng hoạt động.',
            ], 404);
        }

        $linkIds = $links->pluck('id')->values();
        $linkCodes = $links->pluck('code')->filter()->values();

        $leadQuery = Lead::query()
            ->where('tenant_id', $partner->tenant_id)
            ->where(function ($query) use ($partner, $linkCodes): void {
                $query->where('affiliate_code', $partner->code);

                if ($linkCodes->isNotEmpty()) {
                    $query->orWhereIn('referral_code', $linkCodes);
                }
            });

        $clickQuery = AffiliateClick::query()
            ->where('tenant_id', $partner->tenant_id)
            ->where('affiliate_partner_id', $partner->id)
            ->when($linkIds->isNotEmpty(), fn ($query) => $query->whereIn('affiliate_link_id', $linkIds));

        $commissionQuery = AffiliateCommission::query()
            ->where('tenant_id', $partner->tenant_id)
            ->where('affiliate_partner_id', $partner->id)
            ->when($linkIds->isNotEmpty(), fn ($query) => $query->whereIn('affiliate_link_id', $linkIds));

        $commissionByStatus = (clone $commissionQuery)
            ->selectRaw('status, count(*) as count, sum(commission_vnd) as commission_vnd')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $recentLeads = (clone $leadQuery)
            ->orderByDesc('created_at')
            ->limit(8)
            ->get()
            ->map(fn (Lead $lead): array => [
                'full_name' => $lead->full_name,
                'phone' => $this->maskPhone($lead->phone),
                'status' => $lead->status,
                'temperature' => $lead->temperature,
                'course_slug' => $lead->course_slug,
                'referral_code' => $lead->referral_code,
                'created_at' => $lead->created_at?->toDateTimeString(),
            ]);

        $recentCommissions = (clone $commissionQuery)
            ->with(['lead', 'order', 'link'])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get()
            ->map(fn (AffiliateCommission $commission): array => [
                'order_code' => $commission->order?->order_code,
                'lead_name' => $commission->lead?->full_name,
                'link_code' => $commission->link?->code,
                'status' => $commission->status,
                'order_total_vnd' => $commission->order_total_vnd,
                'commission_percent' => $commission->commission_percent,
                'commission_vnd' => $commission->commission_vnd,
                'approved_at' => $commission->approved_at?->toDateTimeString(),
                'paid_at' => $commission->paid_at?->toDateTimeString(),
            ]);

        return response()->json([
            'partner' => [
                'name' => $partner->name,
                'code' => $partner->code,
                'partner_type' => $partner->partner_type,
                'default_commission_percent' => $partner->default_commission_percent,
                'status' => $partner->status,
            ],
            'summary' => [
                'link_count' => $links->count(),
                'click_count' => (clone $clickQuery)->count(),
                'lead_count' => (clone $leadQuery)->count(),
                'converted_lead_count' => (clone $leadQuery)->whereNotNull('converted_at')->count(),
                'commission_count' => (clone $commissionQuery)->count(),
                'pending_commission_vnd' => (int) ($commissionByStatus->get('pending')->commission_vnd ?? 0),
                'approved_commission_vnd' => (int) ($commissionByStatus->get('approved')->commission_vnd ?? 0),
                'paid_commission_vnd' => (int) ($commissionByStatus->get('paid')->commission_vnd ?? 0),
                'total_commission_vnd' => (int) (clone $commissionQuery)->sum('commission_vnd'),
            ],
            'links' => $links->map(fn (AffiliateLink $link): array => [
                'code' => $link->code,
                'campaign' => $link->campaign,
                'target_url' => $link->target_url,
                'commission_percent' => $link->commission_percent ?? $partner->default_commission_percent,
                'click_count' => (int) $link->clicks_count,
                'commission_vnd' => (int) ($link->commission_vnd_sum ?? 0),
            ])->values(),
            'recent_leads' => $recentLeads,
            'recent_commissions' => $recentCommissions,
        ]);
    }

    private function maskPhone(?string $phone): ?string
    {
        if (! $phone || strlen($phone) < 6) {
            return $phone;
        }

        return substr($phone, 0, 4) . '***' . substr($phone, -3);
    }
}
