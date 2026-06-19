<?php

namespace App\Filament\Pages;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Receivable;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class RevenueReport extends Page
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::DocumentChartBar;

    protected static string | UnitEnum | null $navigationGroup = 'Tài chính';

    protected static ?string $navigationLabel = 'Báo cáo doanh thu';

    protected static ?int $navigationSort = 70;

    protected string $view = 'filament.pages.revenue-report';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user?->hasRole('admin') || $user?->can('manage_finance') || false;
    }

    public function getTitle(): string
    {
        return 'Báo cáo doanh thu';
    }

    /**
     * @return array<string, int>
     */
    public function getSummary(): array
    {
        return [
            'order_total_vnd' => (int) Order::query()->sum('total_vnd'),
            'paid_vnd' => (int) Payment::query()
                ->where('status', 'completed')
                ->sum('amount_vnd'),
            'receivable_vnd' => (int) Receivable::query()->sum('balance_vnd'),
            'invoice_count' => Invoice::query()->count(),
        ];
    }

    /**
     * @return Collection<int, object>
     */
    public function getMonthlyRevenue(): Collection
    {
        return Payment::query()
            ->selectRaw("to_char(paid_at, 'YYYY-MM') as period")
            ->selectRaw('sum(amount_vnd) as paid_vnd')
            ->where('status', 'completed')
            ->whereNotNull('paid_at')
            ->groupBy('period')
            ->orderByDesc('period')
            ->limit(12)
            ->get();
    }

    /**
     * @return Collection<int, object>
     */
    public function getCourseRevenue(): Collection
    {
        return DB::table('order_items')
            ->join('courses', 'courses.id', '=', 'order_items.course_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->select('courses.name as course_name')
            ->selectRaw('sum(order_items.line_total_vnd) as sold_vnd')
            ->selectRaw('sum(case when orders.total_vnd > 0 then floor(orders.paid_vnd * order_items.line_total_vnd / orders.total_vnd) else 0 end) as paid_vnd')
            ->groupBy('courses.name')
            ->orderByDesc('sold_vnd')
            ->limit(10)
            ->get();
    }

    public function formatVnd(int|string|null $amount): string
    {
        return number_format((int) $amount, 0, ',', '.') . ' ₫';
    }
}
