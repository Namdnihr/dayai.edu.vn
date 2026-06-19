<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id', 'invoice_id', 'order_id', 'customer_account_id', 'payment_code',
        'payment_method', 'status', 'amount_vnd', 'paid_at', 'reference_no',
        'notes', 'created_by_id',
    ];

    protected function casts(): array
    {
        return [
            'amount_vnd' => 'integer',
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (Payment $payment): void {
            if ($payment->status !== 'completed') {
                return;
            }

            $payment->invoice?->applyPayments();

            Receipt::query()->firstOrCreate(
                ['payment_id' => $payment->id],
                [
                    'tenant_id' => $payment->tenant_id,
                    'receipt_code' => Receipt::nextCode($payment->tenant_id),
                    'issued_at' => $payment->paid_at,
                    'issued_by_id' => $payment->created_by_id,
                    'payer_name' => $payment->customerAccount?->display_name ?? 'Khách hàng',
                    'amount_vnd' => $payment->amount_vnd,
                    'content' => 'Thu học phí theo payment ' . $payment->payment_code,
                ],
            );

            ActivityLog::query()->create([
                'tenant_id' => $payment->tenant_id,
                'user_id' => $payment->created_by_id,
                'action' => 'payment.completed',
                'subject_type' => self::class,
                'subject_id' => $payment->id,
                'description' => 'Ghi nhận thanh toán học phí.',
                'new_values' => $payment->getAttributes(),
            ]);
        });
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function invoice(): BelongsTo { return $this->belongsTo(Invoice::class); }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function customerAccount(): BelongsTo { return $this->belongsTo(CustomerAccount::class); }
    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by_id'); }
    public function receipt(): HasOne { return $this->hasOne(Receipt::class); }
}
