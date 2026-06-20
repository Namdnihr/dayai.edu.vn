<x-filament-panels::page>
    @php
        $reports = $this->getReports();
    @endphp

    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <h2 class="text-base font-semibold text-gray-950 dark:text-white">Sprint 32 BI Reports</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Bộ báo cáo xuất CSV cho tuyển sinh, doanh thu, lớp học và công nợ. Dùng để đối chiếu số liệu trước khi làm dashboard BI sâu hơn.
        </p>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        @foreach ($reports as $report)
            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-950 dark:text-white">{{ $report['name'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-500 dark:text-gray-400">{{ $report['description'] }}</p>
                    </div>
                    <div class="rounded-full bg-primary-50 px-3 py-1 text-xs font-semibold text-primary-700 dark:bg-primary-500/10 dark:text-primary-300">
                        {{ number_format($report['rows']) }} dòng
                    </div>
                </div>
                <a
                    href="/api/reports/{{ $report['slug'] }}.csv"
                    class="mt-5 inline-flex rounded-full bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700"
                    target="_blank"
                >
                    Export CSV
                </a>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
