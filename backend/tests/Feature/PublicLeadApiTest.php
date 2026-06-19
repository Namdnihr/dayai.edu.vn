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

    public function test_public_lead_api_creates_website_lead(): void
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

        $response = $this->postJson('/api/leads', [
            'full_name' => 'Lead K01 Test',
            'phone' => '0901999888',
            'email' => 'lead-k01@example.com',
            'lead_type' => 'parent',
            'interested_course_id' => 'AI-FUNDAMENTALS',
            'learning_goal' => 'Muốn đăng ký học thử AI cho con.',
            'request_type' => 'trial',
            'preferred_contact_method' => 'phone',
            'utm_source' => 'website',
            'utm_medium' => 'course_landing',
            'utm_campaign' => 'k01_ai_can_ban',
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure(['message', 'lead_id']);

        $this->assertDatabaseHas('leads', [
            'full_name' => 'Lead K01 Test',
            'phone' => '0901999888',
            'lead_type' => 'parent',
            'status' => 'trial_requested',
            'utm_campaign' => 'k01_ai_can_ban',
        ]);

        $lead = Lead::query()->where('phone', '0901999888')->first();

        $this->assertSame('website', $lead?->source?->code);
    }

    public function test_public_lead_api_validates_required_fields(): void
    {
        $response = $this->postJson('/api/leads', []);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['full_name', 'phone', 'lead_type']);
    }
}
