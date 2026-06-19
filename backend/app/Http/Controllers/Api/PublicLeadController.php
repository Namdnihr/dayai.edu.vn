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
            'learning_goal' => ['nullable', 'string', 'max:2000'],
            'message' => ['nullable', 'string', 'max:2000'],
            'preferred_contact_method' => ['nullable', Rule::in(['phone', 'email', 'zalo'])],
            'request_type' => ['nullable', Rule::in(['consultation', 'trial'])],
            'utm_source' => ['nullable', 'string', 'max:255'],
            'utm_medium' => ['nullable', 'string', 'max:255'],
            'utm_campaign' => ['nullable', 'string', 'max:255'],
        ]);

        $tenant = Tenant::query()->where('code', 'dayai')->firstOrFail();
        $branch = Branch::query()->where('tenant_id', $tenant->id)->where('code', 'main')->first();
        $source = LeadSource::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'code' => 'website',
            ],
            [
                'name' => 'Website',
                'source_type' => 'website',
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
            'learning_goal' => $validated['learning_goal'] ?? null,
            'message' => $validated['message'] ?? null,
            'preferred_contact_method' => $validated['preferred_contact_method'] ?? 'phone',
            'utm_source' => $validated['utm_source'] ?? 'website',
            'utm_medium' => $validated['utm_medium'] ?? null,
            'utm_campaign' => $validated['utm_campaign'] ?? null,
            'metadata' => [
                'request_type' => $validated['request_type'] ?? 'consultation',
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
            ],
        ]);

        return response()->json([
            'message' => 'Lead đã được ghi nhận.',
            'lead_id' => $lead->id,
        ], 201);
    }
}
