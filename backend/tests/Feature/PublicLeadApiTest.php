<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Lead;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicLeadApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_lead_api_creates_website_lead_with_tracking(): void
    {
        $this->createDayaiTenant();

        $response = $this->postJson('/api/leads', [
            'full_name' => 'Lead K01 Test',
            'phone' => '0901999888',
            'email' => 'lead-k01@example.com',
            'lead_type' => 'parent',
            'interested_course_id' => 'AI-FUNDAMENTALS',
            'course_slug' => 'ai-can-ban',
            'learning_goal' => 'Muốn đăng ký học thử AI cho con.',
            'request_type' => 'trial',
            'preferred_contact_method' => 'phone',
            'utm_source' => 'website',
            'utm_medium' => 'course_landing',
            'utm_campaign' => 'k01_ai_can_ban',
            'utm_content' => 'hero_cta',
            'utm_term' => 'hoc ai can ban',
            'page_url' => 'https://dayai.edu.vn/khoa-hoc/ai-can-ban?utm_campaign=k01_ai_can_ban',
            'landing_page' => 'https://dayai.edu.vn/khoa-hoc/ai-can-ban',
            'referrer_url' => 'https://google.com',
            'click_id' => 'CLICK-001',
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure(['message', 'lead_id']);

        $this->assertDatabaseHas('leads', [
            'full_name' => 'Lead K01 Test',
            'phone' => '0901999888',
            'lead_type' => 'parent',
            'status' => 'trial_requested',
            'course_slug' => 'ai-can-ban',
            'utm_campaign' => 'k01_ai_can_ban',
            'utm_content' => 'hero_cta',
            'utm_term' => 'hoc ai can ban',
            'click_id' => 'CLICK-001',
        ]);

        $lead = Lead::query()->where('phone', '0901999888')->first();

        $this->assertSame('website', $lead?->source?->code);
        $this->assertSame('website', $lead?->first_touch_source);
    }

    public function test_public_lead_api_creates_affiliate_lead_when_ref_code_exists(): void
    {
        $this->createDayaiTenant();

        $response = $this->postJson('/api/leads', [
            'full_name' => 'Affiliate Lead',
            'phone' => '0901777000',
            'lead_type' => 'student',
            'interested_course_id' => 'AI-FUNDAMENTALS',
            'course_slug' => 'ai-can-ban',
            'request_type' => 'consultation',
            'utm_source' => 'affiliate',
            'utm_medium' => 'partner_link',
            'utm_campaign' => 'partner_nguyen_a',
            'affiliate_code' => 'PARTNER-A',
            'referral_code' => 'REF-A',
            'click_id' => 'AFF-CLICK-001',
        ]);

        $response->assertCreated();

        $lead = Lead::query()->where('phone', '0901777000')->first();

        $this->assertSame('affiliate', $lead?->source?->code);
        $this->assertSame('PARTNER-A', $lead?->affiliate_code);
        $this->assertSame('REF-A', $lead?->referral_code);
        $this->assertSame('affiliate', $lead?->last_touch_source);
    }

    public function test_public_lead_api_validates_required_fields(): void
    {
        $response = $this->postJson('/api/leads', []);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['full_name', 'phone', 'lead_type']);
    }

    private function createDayaiTenant(): Tenant
    {
        $tenant = Tenant::query()->create([
            'name' => 'DAYAI',
            'code' => 'dayai',
            'status' => 'active',
        ]);

        Branch::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Cơ sở chính',
            'code' => 'main',
            'status' => 'active',
        ]);

        return $tenant;
    }
}
