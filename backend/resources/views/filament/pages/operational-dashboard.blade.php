<x-filament-panels::page>
    @php
        $summary = $this->getSummary();
        $leadFunnel = $this->getLeadFunnel();
        $leadSources = $this->getLeadSources();
        $leadTemperatures = $this->getLeadTemperatures();
        $overdueLeads = $this->getOverdueLeads();
        $salesPerformance = $this->getSalesPerformance();
        $upcomingSessions = $this->getUpcomingSessions();
        $atRiskStudents = $this->getAtRiskStudents();
        $courseRevenue = $this->getCourseRevenue();
        $phase2Readiness = $this->getPhase2Readiness();
        $affiliatePerformance = $this->getAffiliatePerformance();
        $automationHealth = $this->getAutomationHealth();
        $automationRecentLogs = $this->getAutomationRecentLogs();
    @endphp

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Lead tổng</div>
            <div class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">{{ number_format($summary['lead_count']) }}</div>
            <div class="mt-1 text-xs text-gray-500">Mới: {{ number_format($summary['new_lead_count']) }}</div>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Tỷ lệ chuyển đổi</div>
            <div class="mt-2 text-2xl font-semibold text-success-600">{{ $summary['conversion_rate'] }}%</div>
            <div class="mt-1 text-xs text-gray-500">Đã chuyển: {{ number_format($summary['converted_lead_count']) }}</div>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Đang học</div>
            <div class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">{{ number_format($summary['active_enrollment_count']) }}</div>
            <div class="mt-1 text-xs text-gray-500">Lớp hoạt động: {{ number_format($summary['active_class_count']) }}</div>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Tiến độ TB</div>
            <div class="mt-2 text-2xl font-semibold text-primary-600">{{ $summary['average_progress_percent'] }}%</div>
            <div class="mt-1 text-xs text-gray-500">Lịch sắp tới: {{ number_format($summary['upcoming_session_count']) }}</div>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Doanh số đăng ký</div>
            <div class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">{{ $this->formatVnd($summary['order_total_vnd']) }}</div>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Đã thu</div>
            <div class="mt-2 text-2xl font-semibold text-success-600">{{ $this->formatVnd($summary['paid_vnd']) }}</div>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Công nợ</div>
            <div class="mt-2 text-2xl font-semibold text-warning-600">{{ $this->formatVnd($summary['receivable_vnd']) }}</div>
        </div>
    </div>

    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-950 dark:text-white">Phase 2 readiness</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Bức tranh nhanh trước UAT/staging: nội dung, LMS, tuyển sinh, học viên và báo cáo.</p>
            </div>
            <div class="text-xs font-semibold uppercase tracking-wide text-primary-600">Sprint 27</div>
        </div>

        <div class="mt-5 grid gap-4 md:grid-cols-4">
            <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                <div class="text-xs text-gray-500 dark:text-gray-400">Khóa đã public</div>
                <div class="mt-2 text-xl font-semibold text-gray-950 dark:text-white">{{ number_format($phase2Readiness['published_course_count']) }}</div>
            </div>
            <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                <div class="text-xs text-gray-500 dark:text-gray-400">Bài viết/video public</div>
                <div class="mt-2 text-xl font-semibold text-gray-950 dark:text-white">{{ number_format($phase2Readiness['published_content_count']) }} / {{ number_format($phase2Readiness['published_video_count']) }}</div>
            </div>
            <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                <div class="text-xs text-gray-500 dark:text-gray-400">Tiến độ video đã ghi nhận</div>
                <div class="mt-2 text-xl font-semibold text-primary-600">{{ number_format($phase2Readiness['tracked_lesson_progress_count']) }}</div>
            </div>
            <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                <div class="text-xs text-gray-500 dark:text-gray-400">Lead có nguồn / affiliate</div>
                <div class="mt-2 text-xl font-semibold text-gray-950 dark:text-white">{{ number_format($phase2Readiness['lead_source_count']) }} / {{ number_format($phase2Readiness['affiliate_lead_count']) }}</div>
            </div>
            <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                <div class="text-xs text-gray-500 dark:text-gray-400">Đối tác / click affiliate</div>
                <div class="mt-2 text-xl font-semibold text-gray-950 dark:text-white">{{ number_format($phase2Readiness['affiliate_partner_count']) }} / {{ number_format($phase2Readiness['affiliate_click_count']) }}</div>
            </div>
            <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                <div class="text-xs text-gray-500 dark:text-gray-400">Hoa hồng chờ duyệt</div>
                <div class="mt-2 text-xl font-semibold text-warning-600">{{ $this->formatVnd($phase2Readiness['pending_commission_vnd']) }}</div>
            </div>
            <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                <div class="text-xs text-gray-500 dark:text-gray-400">Học viên đang học</div>
                <div class="mt-2 text-xl font-semibold text-gray-950 dark:text-white">{{ number_format($phase2Readiness['active_student_count']) }}</div>
            </div>
            <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                <div class="text-xs text-gray-500 dark:text-gray-400">Báo cáo tiến bộ public</div>
                <div class="mt-2 text-xl font-semibold text-gray-950 dark:text-white">{{ number_format($phase2Readiness['published_progress_report_count']) }}</div>
            </div>
        </div>
    </div>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="border-b border-gray-200 px-5 py-4 dark:border-white/10">
            <h2 class="text-base font-semibold text-gray-950 dark:text-white">Hiệu quả affiliate</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Theo dõi doanh số, hoa hồng và khoản chờ duyệt theo đối tác.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-left text-sm dark:divide-white/10">
                <thead>
                    <tr class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        <th class="px-5 py-3">Đối tác</th>
                        <th class="px-5 py-3 text-right">Số commission</th>
                        <th class="px-5 py-3 text-right">Doanh số</th>
                        <th class="px-5 py-3 text-right">Hoa hồng</th>
                        <th class="px-5 py-3 text-right">Chờ duyệt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                    @forelse ($affiliatePerformance as $row)
                        <tr>
                            <td class="px-5 py-3 font-medium text-gray-950 dark:text-white">{{ $row->partner_name }}</td>
                            <td class="px-5 py-3 text-right text-gray-700 dark:text-gray-200">{{ number_format($row->commission_count) }}</td>
                            <td class="px-5 py-3 text-right text-gray-700 dark:text-gray-200">{{ $this->formatVnd($row->order_total_vnd) }}</td>
                            <td class="px-5 py-3 text-right font-semibold text-gray-950 dark:text-white">{{ $this->formatVnd($row->commission_vnd) }}</td>
                            <td class="px-5 py-3 text-right font-semibold text-warning-600">{{ $this->formatVnd($row->pending_commission_vnd) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">Chưa có commission affiliate.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="border-b border-gray-200 px-5 py-4 dark:border-white/10">
            <h2 class="text-base font-semibold text-gray-950 dark:text-white">Automation health</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Theo dõi workflow chăm sóc lead, lịch học và công nợ.</p>
        </div>
        <div class="grid gap-4 p-5 md:grid-cols-4">
            <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                <div class="text-xs text-gray-500 dark:text-gray-400">Workflow active</div>
                <div class="mt-2 text-xl font-semibold text-gray-950 dark:text-white">{{ number_format($automationHealth['active_workflow_count']) }}</div>
            </div>
            <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                <div class="text-xs text-gray-500 dark:text-gray-400">Đã gửi</div>
                <div class="mt-2 text-xl font-semibold text-success-600">{{ number_format($automationHealth['sent_log_count']) }}</div>
            </div>
            <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                <div class="text-xs text-gray-500 dark:text-gray-400">Lỗi gửi</div>
                <div class="mt-2 text-xl font-semibold text-danger-600">{{ number_format($automationHealth['failed_log_count']) }}</div>
            </div>
            <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                <div class="text-xs text-gray-500 dark:text-gray-400">Lần gửi gần nhất</div>
                <div class="mt-2 text-sm font-semibold text-gray-950 dark:text-white">{{ $automationHealth['latest_sent_at'] ?? 'Chưa chạy' }}</div>
            </div>
        </div>
        <div class="overflow-x-auto border-t border-gray-200 dark:border-white/10">
            <table class="w-full divide-y divide-gray-200 text-left text-sm dark:divide-white/10">
                <thead>
                    <tr class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        <th class="px-5 py-3">Workflow</th>
                        <th class="px-5 py-3">Trigger</th>
                        <th class="px-5 py-3">Kênh</th>
                        <th class="px-5 py-3">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                    @forelse ($automationRecentLogs as $log)
                        <tr>
                            <td class="px-5 py-3 font-medium text-gray-950 dark:text-white">{{ $log->workflow?->name ?? 'Workflow' }}</td>
                            <td class="px-5 py-3 text-gray-700 dark:text-gray-200">{{ $log->trigger_type }}</td>
                            <td class="px-5 py-3 text-gray-700 dark:text-gray-200">{{ $log->channel }}</td>
                            <td class="px-5 py-3 font-semibold text-gray-950 dark:text-white">{{ $log->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">Chưa có log automation.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="border-b border-gray-200 px-5 py-4 dark:border-white/10">
                <h2 class="text-base font-semibold text-gray-950 dark:text-white">Phễu tuyển sinh</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Theo trạng thái lead hiện tại.</p>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-white/10">
                @forelse ($leadFunnel as $row)
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $this->formatStatus($row->status) }}</span>
                        <span class="text-sm font-semibold text-gray-950 dark:text-white">{{ number_format($row->total) }}</span>
                    </div>
                @empty
                    <div class="px-5 py-8 text-sm text-gray-500 dark:text-gray-400">Chưa có lead.</div>
                @endforelse
            </div>
        </div>

        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="border-b border-gray-200 px-5 py-4 dark:border-white/10">
                <h2 class="text-base font-semibold text-gray-950 dark:text-white">Hiệu quả nguồn lead</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">So sánh lead và số đã chuyển đổi.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-left text-sm dark:divide-white/10">
                    <thead>
                        <tr class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            <th class="px-5 py-3">Nguồn</th>
                            <th class="px-5 py-3 text-right">Lead</th>
                            <th class="px-5 py-3 text-right">Chuyển đổi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                        @forelse ($leadSources as $row)
                            <tr>
                                <td class="px-5 py-3 font-medium text-gray-950 dark:text-white">{{ $row->source_name }}</td>
                                <td class="px-5 py-3 text-right text-gray-700 dark:text-gray-200">{{ number_format($row->lead_count) }}</td>
                                <td class="px-5 py-3 text-right font-semibold text-gray-950 dark:text-white">{{ number_format($row->converted_count) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">Chưa có dữ liệu nguồn lead.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="border-b border-gray-200 px-5 py-4 dark:border-white/10">
                <h2 class="text-base font-semibold text-gray-950 dark:text-white">Độ nóng lead</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Phân bổ lead lạnh/ấm/nóng và giá trị cơ hội.</p>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-white/10">
                @forelse ($leadTemperatures as $row)
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $this->formatTemperature($row->temperature) }}</span>
                        <span class="text-sm font-semibold text-gray-950 dark:text-white">{{ number_format($row->total) }} · {{ $this->formatVnd($row->expected_value_vnd) }}</span>
                    </div>
                @empty
                    <div class="px-5 py-8 text-sm text-gray-500 dark:text-gray-400">Chưa có dữ liệu độ nóng lead.</div>
                @endforelse
            </div>
        </div>

        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="border-b border-gray-200 px-5 py-4 dark:border-white/10">
                <h2 class="text-base font-semibold text-gray-950 dark:text-white">Lead quá hạn follow-up</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Các lead cần xử lý ngay để tránh mất cơ hội.</p>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-white/10">
                @forelse ($overdueLeads as $lead)
                    <div class="px-5 py-3">
                        <div class="text-sm font-semibold text-gray-950 dark:text-white">{{ $lead->full_name }}</div>
                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $lead->phone }} · {{ $lead->assignedUser?->name ?? 'Chưa phân công' }} · {{ $lead->next_follow_up_at?->format('d/m H:i') }}</div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-sm text-gray-500 dark:text-gray-400">Không có lead quá hạn.</div>
                @endforelse
            </div>
        </div>

        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="border-b border-gray-200 px-5 py-4 dark:border-white/10">
                <h2 class="text-base font-semibold text-gray-950 dark:text-white">Hiệu quả tư vấn viên</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Lead, chuyển đổi và giá trị cơ hội theo người phụ trách.</p>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-white/10">
                @forelse ($salesPerformance as $row)
                    <div class="flex items-center justify-between gap-4 px-5 py-3">
                        <div>
                            <div class="text-sm font-semibold text-gray-950 dark:text-white">{{ $row->consultant_name }}</div>
                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Chuyển đổi {{ number_format($row->converted_count) }}/{{ number_format($row->lead_count) }}</div>
                        </div>
                        <div class="text-right text-xs font-semibold text-gray-950 dark:text-white">{{ $this->formatVnd($row->expected_value_vnd) }}</div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-sm text-gray-500 dark:text-gray-400">Chưa có dữ liệu tư vấn viên.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="border-b border-gray-200 px-5 py-4 dark:border-white/10">
                <h2 class="text-base font-semibold text-gray-950 dark:text-white">Lịch học sắp tới</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Các buổi học cần vận hành trong thời gian tới.</p>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-white/10">
                @forelse ($upcomingSessions as $session)
                    <div class="px-5 py-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-sm font-semibold text-gray-950 dark:text-white">{{ $session->title }}</div>
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $session->classGroup?->name }} · {{ $session->classGroup?->course?->name }}</div>
                            </div>
                            <div class="text-right text-xs font-medium text-gray-600 dark:text-gray-300">{{ $session->starts_at?->format('d/m H:i') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-sm text-gray-500 dark:text-gray-400">Chưa có lịch học sắp tới.</div>
                @endforelse
            </div>
        </div>

        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="border-b border-gray-200 px-5 py-4 dark:border-white/10">
                <h2 class="text-base font-semibold text-gray-950 dark:text-white">Học viên cần chú ý</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Dựa trên điểm danh vắng/muộn/xin nghỉ.</p>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-white/10">
                @forelse ($atRiskStudents as $student)
                    <div class="flex items-center justify-between px-5 py-3">
                        <div>
                            <div class="text-sm font-semibold text-gray-950 dark:text-white">{{ $student->full_name }}</div>
                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $student->student_code }} · {{ $student->class_group_name ?? 'Chưa rõ lớp' }}</div>
                        </div>
                        <div class="text-right text-xs font-semibold text-warning-600">Vắng {{ $student->absent_count }} · Muộn {{ $student->late_count }}</div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-sm text-gray-500 dark:text-gray-400">Chưa có học viên cần chú ý.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="border-b border-gray-200 px-5 py-4 dark:border-white/10">
            <h2 class="text-base font-semibold text-gray-950 dark:text-white">Doanh thu theo khóa</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tổng doanh số và số đã thu theo từng khóa học.</p>
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
                            <td colspan="3" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">Chưa có doanh thu theo khóa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
