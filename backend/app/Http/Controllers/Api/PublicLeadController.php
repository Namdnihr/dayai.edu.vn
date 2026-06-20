<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PublicLeadController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'lead_type' => ['required', Rule::in(['parent', 'student', 'business_owner', 'company'])],
            'company_name' => ['nullable', 'string', 'max:255'],
            'interested_course_id' => ['nullable', 'string', 'max:255'],
            'course_slug' => ['nullable', 'string', 'max:255'],
            'learning_goal' => ['nullable', 'string', 'max:2000'],
            'message' => ['nullable', 'string', 'max:2000'],
            'preferred_contact_method' => ['nullable', Rule::in(['phone', 'email', 'zalo'])],
            'request_type' => ['nullable', Rule::in(['consultation', 'trial'])],
            'utm_source' => ['nullable', 'string', 'max:255'],
            'utm_medium' => ['nullable', 'string', 'max:255'],
            'utm_campaign' => ['nullable', 'string', 'max:255'],
            'utm_content' => ['nullable', 'string', 'max:255'],
            'utm_term' => ['nullable', 'string', 'max:255'],
            'page_url' => ['nullable', 'string', 'max:1000'],
            'landing_page' => ['nullable', 'string', 'max:1000'],
            'referrer_url' => ['nullable', 'string', 'max:1000'],
            'affiliate_code' => ['nullable', 'string', 'max:255'],
            'referral_code' => ['nullable', 'string', 'max:255'],
            'click_id' => ['nullable', 'string', 'max:255'],
            'first_touch_source' => ['nullable', 'string', 'max:255'],
            'last_touch_source' => ['nullable', 'string', 'max:255'],
        ]);

        $tenant = Tenant::query()->where('code', 'dayai')->firstOrFail();
        $branch = Branch::query()->where('tenant_id', $tenant->id)->where('code', 'main')->first();
        $sourceCode = $this->resolveSourceCode($validated);
        $source = LeadSource::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'code' => $sourceCode,
            ],
            [
                'name' => $this->sourceName($sourceCode),
                'source_type' => $this->sourceType($sourceCode),
                'is_active' => true,
            ],
        );

        $lead = Lead::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch?->id,
            'lead_source_id' => $source->id,
            'lead_type' => $validated['lead_type'],
            'status' => ($validated['request_type'] ?? 'consultation') === 'trial' ? 'trial_requested' : 'new',
            'priority' => $validated['lead_type'] === 'company' ? 'high' : 'normal',
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'company_name' => $validated['company_name'] ?? null,
            'interested_course_id' => $validated['interested_course_id'] ?? null,
            'course_slug' => $validated['course_slug'] ?? null,
            'learning_goal' => $validated['learning_goal'] ?? null,
            'message' => $validated['message'] ?? null,
            'preferred_contact_method' => $validated['preferred_contact_method'] ?? 'phone',
            'utm_source' => $validated['utm_source'] ?? $sourceCode,
            'utm_medium' => $validated['utm_medium'] ?? null,
            'utm_campaign' => $validated['utm_campaign'] ?? null,
            'utm_content' => $validated['utm_content'] ?? null,
            'utm_term' => $validated['utm_term'] ?? null,
            'page_url' => $validated['page_url'] ?? null,
            'landing_page' => $validated['landing_page'] ?? ($validated['page_url'] ?? null),
            'referrer_url' => $validated['referrer_url'] ?? null,
            'affiliate_code' => $validated['affiliate_code'] ?? null,
            'referral_code' => $validated['referral_code'] ?? null,
            'click_id' => $validated['click_id'] ?? null,
            'first_touch_source' => $validated['first_touch_source'] ?? ($validated['utm_source'] ?? $sourceCode),
            'last_touch_source' => $validated['last_touch_source'] ?? ($validated['utm_source'] ?? $sourceCode),
            'metadata' => [
                'request_type' => $validated['request_type'] ?? 'consultation',
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
                'tracking_version' => 'sprint_23',
            ],
        ]);

        return response()->json([
            'message' => 'Lead đã được ghi nhận.',
            'lead_id' => $lead->id,
        ], 201);
    }

    private function resolveSourceCode(array $payload): string
    {
        if (filled($payload['affiliate_code'] ?? null) || filled($payload['referral_code'] ?? null)) {
            return 'affiliate';
        }

        $utmSource = strtolower((string) ($payload['utm_source'] ?? ''));

        return match ($utmSource) {
            'facebook', 'fb' => 'facebook',
            'zalo' => 'zalo',
            'google', 'google_ads', 'adwords' => 'google_ads',
            'tiktok', 'tik_tok' => 'tiktok',
            'youtube' => 'youtube',
            'seo', 'organic' => 'organic_search',
            'chatbot' => 'chatbot',
            'event', 'workshop' => 'event',
            default => 'website',
        };
    }

    private function sourceName(string $code): string
    {
        return match ($code) {
            'affiliate' => 'Affiliate / Đối tác giới thiệu',
            'google_ads' => 'Google Ads',
            'tiktok' => 'TikTok',
            'youtube' => 'YouTube',
            'organic_search' => 'SEO Organic',
            'chatbot' => 'Chatbot',
            'event' => 'Sự kiện / Workshop',
            'facebook' => 'Facebook',
            'zalo' => 'Zalo',
            default => 'Website',
        };
    }

    private function sourceType(string $code): string
    {
        return match ($code) {
            'affiliate' => 'affiliate',
            'google_ads', 'facebook', 'tiktok', 'youtube' => 'paid_media',
            'organic_search' => 'organic',
            'chatbot' => 'chatbot',
            'event' => 'event',
            'zalo' => 'social',
            default => 'website',
        };
    }
}
