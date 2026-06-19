<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receivable extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id', 'invoice_id', 'customer_account_id', 'status',
        'original_amount_vnd', 'paid_vnd', 'balance_vnd', 'due_date',
    ];

    protected function casts(): array
    {
        return [
            'original_amount_vnd' => 'integer',
            'paid_vnd' => 'integer',
            'balance_vnd' => 'integer',
            'due_date' => 'date',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function invoice(): BelongsTo { return $this->belongsTo(Invoice::class); }
    public function customerAccount(): BelongsTo { return $this->belongsTo(CustomerAccount::class); }

    public function syncFromInvoice(): void
    {
        $invoice = $this->invoice;

        if (! $invoice) {
            return;
        }

        $this->forceFill([
            'original_amount_vnd' => $invoice->amount_vnd,
            'paid_vnd' => $invoice->paid_vnd,
            'balance_vnd' => $invoice->balance_vnd,
            'status' => match (true) {
                $invoice->balance_vnd <= 0 => 'paid',
                $invoice->paid_vnd > 0 => 'partially_paid',
                $invoice->due_date && $invoice->due_date->isPast() => 'overdue',
                default => 'open',
            },
            'due_date' => $invoice->due_date,
        ])->saveQuietly();
    }
}
