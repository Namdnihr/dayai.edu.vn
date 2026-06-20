<?php

namespace Tests\Feature;

use App\Models\AffiliateClick;
use App\Models\AffiliateCommission;
use App\Models\AffiliateLink;
use App\Models\AffiliatePartner;
use App\Models\Branch;
use App\Models\CustomerAccount;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Person;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliatePortalLookupTest extends TestCase
{
    use RefreshDatabase;

    public function test_affiliate_partner_can_lookup_performance(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'DAYAI',
            'code' => 'dayai',
            'status' => 'active',
        ]);

        $branch = Branch::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Cơ sở chính',
            'code' => 'main',
            'status' => 'active',
        ]);

        $partner = AffiliatePartner::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Partner A',
            'code' => 'PARTNER-A',
            'partner_type' => 'creator',
            'default_commission_percent' => 12,
            'status' => 'active',
        ]);

        $link = AffiliateLink::query()->create([
            'tenant_id' => $tenant->id,
            'affiliate_partner_id' => $partner->id,
            'code' => 'REF-A',
            'campaign' => 'creator_a',
            'target_url' => 'https://dayai.edu.vn/khoa-hoc/ai-can-ban?aff=PARTNER-A&ref=REF-A',
            'commission_percent' => 15,
            'status' => 'active',
        ]);

        $lead = Lead::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'lead_type' => 'student',
            'status' => 'registered',
            'full_name' => 'Affiliate Lead',
            'phone' => '0901777000',
            'course_slug' => 'ai-can-ban',
            'affiliate_code' => 'PARTNER-A',
            'referral_code' => 'REF-A',
            'temperature' => 'hot',
            'converted_at' => now(),
        ]);

        AffiliateClick::query()->create([
            'tenant_id' => $tenant->id,
            'affiliate_partner_id' => $partner->id,
            'affiliate_link_id' => $link->id,
            'lead_id' => $lead->id,
            'affiliate_code' => 'PARTNER-A',
            'referral_code' => 'REF-A',
            'click_id' => 'CLICK-A',
            'clicked_at' => now(),
        ]);

        $person = Person::query()->create([
            'tenant_id' => $tenant->id,
            'full_name' => 'Buyer A',
            'phone' => '0901777000',
        ]);

        $customer = CustomerAccount::query()->create([
            'tenant_id' => $tenant->id,
            'person_id' => $person->id,
            'customer_code' => 'KH-AFF-PORTAL',
            'account_type' => 'individual',
            'display_name' => 'Buyer A',
            'phone' => '0901777000',
            'status' => 'active',
        ]);

        $order = Order::query()->create([
            'tenant_id' => $tenant->id,
            'customer_account_id' => $customer->id,
            'lead_id' => $lead->id,
            'order_code' => 'ORD-AFF-PORTAL',
            'order_type' => 'b2c',
            'status' => 'confirmed',
            'ordered_at' => now(),
            'total_vnd' => 4000000,
            'paid_vnd' => 0,
            'balance_vnd' => 4000000,
        ]);

        AffiliateCommission::query()->where('order_id', $order->id)->update([
            'status' => 'approved',
        ]);

        $response = $this->postJson('/api/affiliate-portal/lookup', [
            'partner_code' => 'PARTNER-A',
            'link_code' => 'REF-A',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('partner.code', 'PARTNER-A')
            ->assertJsonPath('summary.link_count', 1)
            ->assertJsonPath('summary.click_count', 1)
            ->assertJsonPath('summary.lead_count', 1)
            ->assertJsonPath('summary.converted_lead_count', 1)
            ->assertJsonPath('summary.approved_commission_vnd', 600000)
            ->assertJsonPath('links.0.code', 'REF-A')
            ->assertJsonPath('recent_leads.0.phone', '0901***000')
            ->assertJsonPath('recent_commissions.0.order_code', 'ORD-AFF-PORTAL');
    }

    public function test_affiliate_portal_rejects_unknown_partner(): void
    {
        $response = $this->postJson('/api/affiliate-portal/lookup', [
            'partner_code' => 'UNKNOWN',
        ]);

        $response->assertNotFound();
    }
}
