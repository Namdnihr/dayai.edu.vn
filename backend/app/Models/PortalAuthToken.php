<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortalAuthToken extends Model
{
    use HasUlids;

    protected $fillable = [
        'tenant_id',
        'student_profile_id',
        'person_id',
        'phone',
        'student_code',
        'access_role',
        'code_hash',
        'access_token_hash',
        'channel',
        'attempt_count',
        'expires_at',
        'verified_at',
        'revoked_at',
    ];

    protected function casts(): array
    {
        return [
            'attempt_count' => 'integer',
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function studentProfile(): BelongsTo { return $this->belongsTo(StudentProfile::class); }
    public function person(): BelongsTo { return $this->belongsTo(Person::class); }
}
