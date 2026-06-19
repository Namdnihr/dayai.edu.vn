<x-filament-panels::page>
    @php
        $summary = $this->getSummary();
        $monthlyRevenue = $this->getMonthlyRevenue();
        $courseRevenue = $this->getCourseRevenue();
    @endphp

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Doanh số đăng ký</div>
            <div class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">{{ $this->formatVnd($summary['order_total_vnd']) }}</div>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Đã thu</div>
            <div class="mt-2 text-2xl font-semibold text-success-600">{{ $this->formatVnd($summary['paid_vnd']) }}</div>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Công nợ còn lại</div>
            <div class="mt-2 text-2xl font-semibold text-warning-600">{{ $this->formatVnd($summary['receivable_vnd']) }}</div>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Số hóa đơn</div>
            <div class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">{{ number_format($summary['invoice_count']) }}</div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="border-b border-gray-200 px-5 py-4 dark:border-white/10">
                <h2 class="text-base font-semibold text-gray-950 dark:text-white">Doanh thu theo tháng</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tính theo thanh toán đã hoàn tất.</p>
            </div>

            <div class="divide-y divide-gray-200 dark:divide-white/10">
                @forelse ($monthlyRevenue as $row)
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $row->period }}</span>
                        <span class="text-sm font-semibold text-gray-950 dark:text-white">{{ $this->formatVnd($row->paid_vnd) }}</span>
                    </div>
                @empty
                    <div class="px-5 py-8 text-sm text-gray-500 dark:text-gray-400">Chưa có thanh toán hoàn tất.</div>
                @endforelse
            </div>
        </div>

        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="border-b border-gray-200 px-5 py-4 dark:border-white/10">
                <h2 class="text-base font-semibold text-gray-950 dark:text-white">Doanh thu theo khóa học</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">So sánh doanh số đăng ký và số tiền đã thu.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-left text-sm dark:divide-white/10">
                    <thead>
                        <tr class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            <th class="px-5 py-3">Khóa học</th>
                            <th class="px-5 py-3 text-right">Doanh số</th>
                            <th class="px-5 py-3 text-right">Đã thu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                        @forelse ($courseRevenue as $row)
                            <tr>
                                <td class="px-5 py-3 font-medium text-gray-950 dark:text-white">{{ $row->course_name }}</td>
                                <td class="px-5 py-3 text-right text-gray-700 dark:text-gray-200">{{ $this->formatVnd($row->sold_vnd) }}</td>
                                <td class="px-5 py-3 text-right font-semibold text-gray-950 dark:text-white">{{ $this->formatVnd($row->paid_vnd) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">Chưa có đơn đăng ký khóa học.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
