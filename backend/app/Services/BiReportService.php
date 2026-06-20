<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BiReportService
{
    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function admissionsBySource(): Collection
    {
        return DB::table('leads')
            ->leftJoin('lead_sources', 'lead_sources.id', '=', 'leads.lead_source_id')
            ->leftJoin('affiliate_partners', 'affiliate_partners.code', '=', 'leads.affiliate_code')
            ->selectRaw("coalesce(lead_sources.name, leads.utm_source, 'Chưa rõ') as source_name")
            ->selectRaw("coalesce(leads.utm_campaign, leads.referral_code, '-') as campaign")
            ->selectRaw("coalesce(affiliate_partners.name, leads.affiliate_code, '-') as affiliate")
            ->selectRaw('count(leads.id) as lead_count')
            ->selectRaw('sum(case when leads.converted_at is not null then 1 else 0 end) as converted_count')
            ->selectRaw('sum(coalesce(leads.expected_value_vnd, 0)) as expected_value_vnd')
            ->groupBy('source_name', 'campaign', 'affiliate')
            ->orderByDesc('lead_count')
            ->limit(200)
            ->get()
            ->map(fn ($row): array => (array) $row);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function revenueByCourse(): Collection
    {
        return DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('courses', 'courses.id', '=', 'order_items.course_id')
            ->leftJoin('branches', 'branches.id', '=', 'orders.branch_id')
            ->selectRaw("coalesce(courses.name, order_items.description, 'Chưa rõ') as course_name")
            ->selectRaw("coalesce(branches.name, 'Chưa rõ') as branch_name")
            ->selectRaw('count(distinct orders.id) as order_count')
            ->selectRaw('sum(order_items.line_total_vnd) as sold_vnd')
            ->selectRaw('sum(case when orders.total_vnd > 0 then floor(orders.paid_vnd * order_items.line_total_vnd / orders.total_vnd) else 0 end) as paid_vnd')
            ->selectRaw('sum(case when orders.total_vnd > 0 then floor(orders.balance_vnd * order_items.line_total_vnd / orders.total_vnd) else 0 end) as balance_vnd')
            ->groupBy('course_name', 'branch_name')
            ->orderByDesc('sold_vnd')
            ->limit(200)
            ->get()
            ->map(fn ($row): array => (array) $row);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function classPerformance(): Collection
    {
        return DB::table('class_groups')
            ->leftJoin('courses', 'courses.id', '=', 'class_groups.course_id')
            ->leftJoin('enrollments', 'enrollments.class_group_id', '=', 'class_groups.id')
            ->leftJoin('attendance_records', 'attendance_records.enrollment_id', '=', 'enrollments.id')
            ->leftJoin('progress_reports', function ($join): void {
                $join->on('progress_reports.enrollment_id', '=', 'enrollments.id')
                    ->where('progress_reports.status', '=', 'published');
            })
            ->select('class_groups.class_code', 'class_groups.name as class_name')
            ->selectRaw("coalesce(courses.name, 'Chưa rõ') as course_name")
            ->selectRaw('count(distinct enrollments.id) as enrollment_count')
            ->selectRaw("sum(case when attendance_records.status = 'present' then 1 else 0 end) as present_count")
            ->selectRaw("sum(case when attendance_records.status in ('absent', 'excused') then 1 else 0 end) as absent_count")
            ->selectRaw("sum(case when attendance_records.status = 'late' then 1 else 0 end) as late_count")
            ->selectRaw('round(avg(progress_reports.progress_percent)) as average_progress_percent')
            ->groupBy('class_groups.class_code', 'class_groups.name', 'course_name')
            ->orderBy('class_groups.name')
            ->limit(200)
            ->get()
            ->map(fn ($row): array => (array) $row);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function receivablesAging(): Collection
    {
        return DB::table('receivables')
            ->join('customer_accounts', 'customer_accounts.id', '=', 'receivables.customer_account_id')
            ->leftJoin('invoices', 'invoices.id', '=', 'receivables.invoice_id')
            ->select('customer_accounts.customer_code', 'customer_accounts.display_name', 'invoices.invoice_code')
            ->selectRaw('receivables.status')
            ->selectRaw('receivables.original_amount_vnd')
            ->selectRaw('receivables.paid_vnd')
            ->selectRaw('receivables.balance_vnd')
            ->selectRaw('receivables.due_date')
            ->selectRaw("case when receivables.due_date < current_date then current_date - receivables.due_date else 0 end as overdue_days")
            ->where('receivables.balance_vnd', '>', 0)
            ->orderByDesc('overdue_days')
            ->limit(200)
            ->get()
            ->map(fn ($row): array => (array) $row);
    }

    /**
     * @return array{filename: string, rows: Collection<int, array<string, mixed>>}
     */
    public function report(string $report): array
    {
        return match ($report) {
            'admissions-by-source' => ['filename' => 'admissions-by-source.csv', 'rows' => $this->admissionsBySource()],
            'revenue-by-course' => ['filename' => 'revenue-by-course.csv', 'rows' => $this->revenueByCourse()],
            'class-performance' => ['filename' => 'class-performance.csv', 'rows' => $this->classPerformance()],
            'receivables-aging' => ['filename' => 'receivables-aging.csv', 'rows' => $this->receivablesAging()],
            default => abort(404, 'Unknown BI report.'),
        };
    }

    public function toCsv(Collection $rows): string
    {
        if ($rows->isEmpty()) {
            return '';
        }

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, array_keys($rows->first()));

        foreach ($rows as $row) {
            fputcsv($handle, array_values($row));
        }

        rewind($handle);

        return stream_get_contents($handle) ?: '';
    }
}
