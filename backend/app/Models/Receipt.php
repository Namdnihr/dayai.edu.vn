<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id', 'payment_id', 'receipt_code', 'issued_at', 'issued_by_id',
        'payer_name', 'amount_vnd', 'content',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'amount_vnd' => 'integer',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function payment(): BelongsTo { return $this->belongsTo(Payment::class); }
    public function issuedBy(): BelongsTo { return $this->belongsTo(User::class, 'issued_by_id'); }

    public static function nextCode(string $tenantId): string
    {
        $nextNumber = static::query()->where('tenant_id', $tenantId)->count() + 1;

        do {
            $code = 'PT-' . str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (static::query()->where('tenant_id', $tenantId)->where('receipt_code', $code)->exists());

        return $code;
    }
}
