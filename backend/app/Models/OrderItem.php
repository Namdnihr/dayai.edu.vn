<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id', 'order_id', 'course_id', 'class_group_id', 'student_profile_id',
        'description', 'quantity', 'unit_price_vnd', 'discount_vnd', 'line_total_vnd',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price_vnd' => 'integer',
            'discount_vnd' => 'integer',
            'line_total_vnd' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (OrderItem $item): void {
            $item->line_total_vnd = max(0, ($item->quantity * $item->unit_price_vnd) - $item->discount_vnd);
        });

        static::saved(fn (OrderItem $item) => $item->order?->recalculateTotals());
        static::deleted(fn (OrderItem $item) => $item->order?->recalculateTotals());
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
    public function classGroup(): BelongsTo { return $this->belongsTo(ClassGroup::class); }
    public function studentProfile(): BelongsTo { return $this->belongsTo(StudentProfile::class); }
}
