<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id', 'order_id', 'customer_account_id', 'invoice_code', 'status',
        'issued_at', 'due_date', 'amount_vnd', 'paid_vnd', 'balance_vnd', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'due_date' => 'date',
            'amount_vnd' => 'integer',
            'paid_vnd' => 'integer',
            'balance_vnd' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (Invoice $invoice): void {
            Receivable::query()->firstOrCreate(
                ['tenant_id' => $invoice->tenant_id, 'invoice_id' => $invoice->id],
                [
                    'customer_account_id' => $invoice->customer_account_id,
                    'status' => 'open',
                    'original_amount_vnd' => $invoice->amount_vnd,
                    'paid_vnd' => 0,
                    'balance_vnd' => $invoice->amount_vnd,
                    'due_date' => $invoice->due_date,
                ],
            );
        });
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function customerAccount(): BelongsTo { return $this->belongsTo(CustomerAccount::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }
    public function receivable(): HasOne { return $this->hasOne(Receivable::class); }

    public function applyPayments(): void
    {
        $paid = (int) $this->payments()->where('status', 'completed')->sum('amount_vnd');
        $balance = max(0, (int) $this->amount_vnd - $paid);

        $this->forceFill([
            'paid_vnd' => $paid,
            'balance_vnd' => $balance,
            'status' => match (true) {
                $paid <= 0 => 'issued',
                $balance <= 0 => 'paid',
                default => 'partially_paid',
            },
        ])->saveQuietly();

        $this->receivable?->syncFromInvoice();
        $this->order?->recalculateTotals();
    }
}
