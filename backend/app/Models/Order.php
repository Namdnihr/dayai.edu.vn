<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id', 'branch_id', 'customer_account_id', 'lead_id', 'order_code',
        'order_type', 'status', 'ordered_at', 'subtotal_vnd', 'discount_vnd',
        'total_vnd', 'paid_vnd', 'balance_vnd', 'notes', 'created_by_id',
    ];

    protected function casts(): array
    {
        return [
            'ordered_at' => 'datetime',
            'subtotal_vnd' => 'integer',
            'discount_vnd' => 'integer',
            'total_vnd' => 'integer',
            'paid_vnd' => 'integer',
            'balance_vnd' => 'integer',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function customerAccount(): BelongsTo { return $this->belongsTo(CustomerAccount::class); }
    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by_id'); }
    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
    public function invoices(): HasMany { return $this->hasMany(Invoice::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }

    public function recalculateTotals(): void
    {
        $subtotal = (int) $this->items()->sum('line_total_vnd');
        $paid = (int) $this->payments()->where('status', 'completed')->sum('amount_vnd');
        $total = max(0, $subtotal - (int) $this->discount_vnd);
        $balance = max(0, $total - $paid);

        $this->forceFill([
            'subtotal_vnd' => $subtotal,
            'total_vnd' => $total,
            'paid_vnd' => $paid,
            'balance_vnd' => $balance,
            'status' => match (true) {
                $total <= 0 => 'draft',
                $paid <= 0 => $this->status === 'draft' ? 'confirmed' : $this->status,
                $balance <= 0 => 'paid',
                default => 'partially_paid',
            },
        ])->saveQuietly();
    }
}
