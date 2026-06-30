<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Person;
use App\Models\StudentProfile;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PublicAccountController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $result = DB::transaction(function () use ($validated): array {
            $tenant = Tenant::query()->where('code', 'dayai')->firstOrFail();
            $branch = Branch::query()->where('tenant_id', $tenant->id)->where('code', 'main')->first();
            $email = strtolower($validated['email']);
            $phone = filled($validated['phone'] ?? null) ? preg_replace('/\s+/', '', (string) $validated['phone']) : null;

            $person = Person::query()
                ->where('tenant_id', $tenant->id)
                ->where('email', $email)
                ->first();

            if (! $person) {
                $person = Person::query()->create([
                    'tenant_id' => $tenant->id,
                    'branch_id' => $branch?->id,
                    'full_name' => $validated['full_name'],
                    'display_name' => $validated['full_name'],
                    'phone' => $phone,
                    'email' => $email,
                    'metadata' => ['source' => 'public_account_register'],
                ]);
            } else {
                $person->fill([
                    'full_name' => $person->full_name ?: $validated['full_name'],
                    'display_name' => $person->display_name ?: $validated['full_name'],
                    'phone' => $person->phone ?: $phone,
                    'email' => $person->email ?: $email,
                ])->save();
            }

            $user = User::query()
                ->where('tenant_id', $tenant->id)
                ->where('email', $email)
                ->first();

            if ($user && $user->email_verified_at) {
                return [
                    'message' => 'Email này đã có tài khoản. Vui lòng đăng nhập để học.',
                    'status' => 'already_verified',
                    'user' => $this->userPayload($user),
                ];
            }

            if (! $user) {
                $user = User::query()->create([
                    'tenant_id' => $tenant->id,
                    'person_id' => $person->id,
                    'name' => $validated['full_name'],
                    'email' => $email,
                    'phone' => $phone,
                    'password' => $validated['password'],
                    'status' => 'pending_email_verification',
                ]);
            } else {
                $user->forceFill([
                    'person_id' => $user->person_id ?: $person->id,
                    'name' => $user->name ?: $validated['full_name'],
                    'phone' => $user->phone ?: $phone,
                    'password' => $validated['password'],
                    'status' => 'pending_email_verification',
                ])->save();
            }

            $this->ensureStudentProfile($tenant, $branch, $person, 'email_pending');
            $token = Str::random(48);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                [
                    'token' => hash('sha256', $token),
                    'created_at' => now(),
                ],
            );

            return [
                'message' => 'Tài khoản đã được tạo. Vui lòng xác nhận email để kích hoạt học.',
                'status' => 'verification_required',
                'email' => $email,
                'demo_verification_token' => app()->environment('production') ? null : $token,
            ];
        });

        return response()->json($result, 201);
    }

    public function verifyEmail(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'token' => ['required', 'string', 'max:255'],
        ]);

        $result = DB::transaction(function () use ($validated): array {
            $tenant = Tenant::query()->where('code', 'dayai')->firstOrFail();
            $email = strtolower($validated['email']);
            $record = DB::table('password_reset_tokens')->where('email', $email)->first();

            if (! $record || ! hash_equals((string) $record->token, hash('sha256', $validated['token']))) {
                abort(422, 'Mã xác nhận email không hợp lệ hoặc đã hết hạn.');
            }

            $user = User::query()
                ->with('person.studentProfile')
                ->where('tenant_id', $tenant->id)
                ->where('email', $email)
                ->firstOrFail();

            $user->forceFill([
                'email_verified_at' => $user->email_verified_at ?? now(),
                'status' => 'active',
            ])->save();

            if ($user->person?->studentProfile && $user->person->studentProfile->status !== 'active') {
                $user->person->studentProfile->forceFill(['status' => 'active'])->save();
            }

            DB::table('password_reset_tokens')->where('email', $email)->delete();

            return [
                'message' => 'Email đã được xác nhận. Tài khoản học viên đã kích hoạt.',
                'status' => 'verified',
                'user' => $this->userPayload($user->fresh(['person.studentProfile'])),
            ];
        });

        return response()->json($result);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $tenant = Tenant::query()->where('code', 'dayai')->firstOrFail();
        $user = User::query()
            ->with('person.studentProfile')
            ->where('tenant_id', $tenant->id)
            ->where('email', strtolower($validated['email']))
            ->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Email hoặc mật khẩu không chính xác.'], 422);
        }

        if (! $user->email_verified_at || $user->status !== 'active') {
            return response()->json(['message' => 'Tài khoản chưa xác nhận email. Vui lòng kiểm tra email trước khi học.'], 403);
        }

        $user->forceFill(['last_login_at' => now()])->save();

        return response()->json([
            'message' => 'Đăng nhập thành công.',
            'status' => 'authenticated',
            'user' => $this->userPayload($user),
        ]);
    }

    private function ensureStudentProfile(Tenant $tenant, ?Branch $branch, Person $person, string $status): StudentProfile
    {
        return StudentProfile::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'person_id' => $person->id,
            ],
            [
                'branch_id' => $branch?->id,
                'student_code' => $this->uniqueStudentCode($tenant->id),
                'student_type' => 'adult',
                'entry_level' => 'beginner',
                'status' => $status,
                'metadata' => ['source' => 'public_account_register'],
            ],
        );
    }

    private function userPayload(User $user): array
    {
        $student = $user->person?->studentProfile;

        return [
            'id' => $user->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'email_verified' => (bool) $user->email_verified_at,
            'student_code' => $student?->student_code,
        ];
    }

    private function uniqueStudentCode(string $tenantId): string
    {
        do {
            $code = 'HV-' . now()->format('ymdHis') . '-' . Str::upper(Str::random(4));
        } while (StudentProfile::query()->where('tenant_id', $tenantId)->where('student_code', $code)->exists());

        return $code;
    }
}
