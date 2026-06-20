<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
                'message' => 'Bạn đã yêu cầu mã quá nhiều lần. Vui lòng thử lại sau.',
            ], 429);
        }

        RateLimiter::hit($rateKey, 300);

        $student = $this->findStudent($phone, $studentCode);

        if (! $student) {
            return response()->json([
                'message' => 'Không tìm thấy học viên với thông tin đã nhập.',
            ], 404);
        }

        $code = (string) random_int(100000, 999999);

        $token = PortalAuthToken::query()->create([
            'tenant_id' => $student->tenant_id,
            'student_profile_id' => $student->id,
            'person_id' => $student->person_id,
            'phone' => $phone,
            'student_code' => $studentCode,
            'code_hash' => Hash::make($code),
            'channel' => 'demo',
            'expires_at' => now()->addMinutes(10),
        ]);

        return response()->json([
            'message' => 'Mã xác thực đã được tạo. Ở môi trường demo, mã được trả về trực tiếp để kiểm thử.',
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
            return response()->json([
                'message' => 'Mã xác thực đã hết hạn hoặc không hợp lệ.',
            ], 422);
        }

        if ($token->attempt_count >= 5) {
            return response()->json([
                'message' => 'Bạn đã nhập sai quá số lần cho phép. Vui lòng yêu cầu mã mới.',
            ], 429);
        }

        if (! Hash::check($validated['code'], $token->code_hash)) {
            $token->increment('attempt_count');

            return response()->json([
                'message' => 'Mã xác thực không đúng.',
            ], 422);
        }

        $accessToken = Str::random(64);

        $token->forceFill([
            'access_token_hash' => hash('sha256', $accessToken),
            'verified_at' => now(),
        ])->save();

        return response()->json([
            'message' => 'Xác thực portal thành công.',
            'portal_access_token' => $accessToken,
            'expires_at' => $token->expires_at?->toDateTimeString(),
        ]);
    }

    protected function findStudent(string $phone, string $studentCode): ?StudentProfile
    {
        return StudentProfile::query()
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
    }
}
