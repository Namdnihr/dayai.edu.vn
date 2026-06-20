<?php

namespace App\Filament\Pages;

use App\Services\BiReportService;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class BiReports extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowDownTray;

    protected static string|UnitEnum|null $navigationGroup = 'Báo cáo';

    protected static ?string $navigationLabel = 'BI Reports & Export';

    protected static ?int $navigationSort = 80;

    protected string $view = 'filament.pages.bi-reports';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user?->hasRole('admin') || $user?->can('view_dashboard') || $user?->can('manage_finance') || false;
    }

    public function getTitle(): string
    {
        return 'BI Reports & Export';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getReports(): array
    {
        $service = app(BiReportService::class);

        return [
            [
                'name' => 'Tuyển sinh theo nguồn',
                'slug' => 'admissions-by-source',
                'description' => 'Nguồn lead, campaign, affiliate, số lead và số chuyển đổi.',
                'rows' => $service->admissionsBySource()->count(),
            ],
            [
                'name' => 'Doanh thu theo khóa',
                'slug' => 'revenue-by-course',
                'description' => 'Doanh số, đã thu và công nợ phân bổ theo khóa/chi nhánh.',
                'rows' => $service->revenueByCourse()->count(),
            ],
            [
                'name' => 'Hiệu quả lớp học',
                'slug' => 'class-performance',
                'description' => 'Sĩ số, điểm danh và tiến độ trung bình theo lớp.',
                'rows' => $service->classPerformance()->count(),
            ],
            [
                'name' => 'Công nợ phải thu',
                'slug' => 'receivables-aging',
                'description' => 'Công nợ còn lại, hạn thanh toán và số ngày quá hạn.',
                'rows' => $service->receivablesAging()->count(),
            ],
        ];
    }
}
