<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\PortalAuthToken;
use App\Models\StudentProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class PortalAuthController extends Controller
{
    public function requestCode(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'student_code' => ['required', 'string', 'max:50'],
        ]);

        $phone = preg_replace('/\s+/', '', $validated['phone']);
        $studentCode = trim($validated['student_code']);
        $rateKey = 'portal-auth:' . $request->ip() . ':' . $phone . ':' . $studentCode;

        if (RateLimiter::tooManyAttempts($rateKey, 5)) {
            return response()->json([
                'message' => 'B?n ?? y?u c?u m? qu? nhi?u l?n. Vui l?ng th? l?i sau.',
            ], 429);
        }

        RateLimiter::hit($rateKey, 300);

        $portalIdentity = $this->findPortalIdentity($phone, $studentCode);
        $student = $portalIdentity['student'];

        if (! $student) {
            $this->logPortalAuth(null, 'portal_auth_request_failed', $request, [
                'phone' => $phone,
                'student_code' => $studentCode,
                'reason' => 'not_found',
            ]);

            return response()->json([
                'message' => 'Kh?ng t?m th?y h?c vi?n v?i th?ng tin ?? nh?p.',
            ], 404);
        }

        $code = (string) random_int(100000, 999999);

        $token = PortalAuthToken::query()->create([
            'tenant_id' => $student->tenant_id,
            'student_profile_id' => $student->id,
            'person_id' => $portalIdentity['person_id'],
            'phone' => $phone,
            'student_code' => $studentCode,
            'access_role' => $portalIdentity['access_role'],
            'code_hash' => Hash::make($code),
            'channel' => 'demo',
            'expires_at' => now()->addMinutes(10),
        ]);

        $this->logPortalAuth($token, 'portal_auth_code_requested', $request, [
            'access_role' => $token->access_role,
            'channel' => $token->channel,
            'expires_at' => $token->expires_at?->toDateTimeString(),
        ]);

        return response()->json([
            'message' => 'M? x?c th?c ?? ???c t?o. ? m?i tr??ng demo, m? ???c tr? v? tr?c ti?p ?? ki?m th?.',
            'request_id' => $token->id,
            'expires_at' => $token->expires_at?->toDateTimeString(),
            'demo_otp' => app()->environment('production') ? null : $code,
        ]);
    }

    public function verifyCode(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'request_id' => ['required', 'string', 'max:50'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        $token = PortalAuthToken::query()
            ->whereKey($validated['request_id'])
            ->whereNull('revoked_at')
            ->first();

        if (! $token || $token->expires_at->isPast()) {
            $this->logPortalAuth($token, 'portal_auth_verify_failed', $request, [
                'reason' => 'expired_or_invalid',
            ]);

            return response()->json([
                'message' => 'M? x?c th?c ?? h?t h?n ho?c kh?ng h?p l?.',
            ], 422);
        }

        if ($token->attempt_count >= 5) {
            $this->logPortalAuth($token, 'portal_auth_verify_failed', $request, [
                'reason' => 'too_many_attempts',
                'attempt_count' => $token->attempt_count,
            ]);

            return response()->json([
                'message' => 'B?n ?? nh?p sai qu? s? l?n cho ph?p. Vui l?ng y?u c?u m? m?i.',
            ], 429);
        }

        if (! Hash::check($validated['code'], $token->code_hash)) {
            $token->increment('attempt_count');
            $token->refresh();

            $this->logPortalAuth($token, 'portal_auth_verify_failed', $request, [
                'reason' => 'wrong_code',
                'attempt_count' => $token->attempt_count,
            ]);

            return response()->json([
                'message' => 'M? x?c th?c kh?ng ??ng.',
            ], 422);
        }

        $accessToken = Str::random(64);

        $token->forceFill([
            'access_token_hash' => hash('sha256', $accessToken),
            'verified_at' => now(),
        ])->save();

        $this->logPortalAuth($token, 'portal_auth_verified', $request, [
            'access_role' => $token->access_role,
        ]);

        return response()->json([
            'message' => 'X?c th?c portal th?nh c?ng.',
            'portal_access_token' => $accessToken,
            'access_role' => $token->access_role,
            'expires_at' => $token->expires_at?->toDateTimeString(),
        ]);
    }

    /**
     * @return array{student: ?StudentProfile, person_id: ?string, access_role: string}
     */
    protected function findPortalIdentity(string $phone, string $studentCode): array
    {
        $student = StudentProfile::query()
            ->with(['person', 'guardians.guardianPerson'])
            ->where(function ($query) use ($studentCode, $phone): void {
                $query->where('student_code', $studentCode)
                    ->whereHas('person', fn ($personQuery) => $personQuery->where('phone', $phone));
            })
            ->orWhere(function ($query) use ($studentCode, $phone): void {
                $query->where('student_code', $studentCode)
                    ->whereHas('guardians.guardianPerson', fn ($guardianQuery) => $guardianQuery->where('phone', $phone));
            })
            ->first();

        if (! $student) {
            return [
                'student' => null,
                'person_id' => null,
                'access_role' => 'unknown',
            ];
        }

        if ($student->person?->phone === $phone) {
            return [
                'student' => $student,
                'person_id' => $student->person_id,
                'access_role' => 'student',
            ];
        }

        $guardian = $student->guardians
            ->first(fn ($guardian) => $guardian->guardianPerson?->phone === $phone);

        return [
            'student' => $student,
            'person_id' => $guardian?->guardian_person_id,
            'access_role' => 'guardian',
        ];
    }

    /**
     * @param array<string, mixed> $metadata
     */
    protected function logPortalAuth(?PortalAuthToken $token, string $action, Request $request, array $metadata = []): void
    {
        ActivityLog::query()->create([
            'tenant_id' => $token?->tenant_id,
            'action' => $action,
            'subject_type' => PortalAuthToken::class,
            'subject_id' => $token?->id,
            'description' => 'Portal authentication event.',
            'new_values' => $metadata,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
        ]);
    }
}
