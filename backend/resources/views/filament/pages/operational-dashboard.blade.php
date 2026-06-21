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

        $kpis = [
            ['label' => 'Lead tổng', 'value' => number_format($summary['lead_count']), 'hint' => 'Mới: ' . number_format($summary['new_lead_count']), 'tone' => 'blue'],
            ['label' => 'Tỷ lệ chuyển đổi', 'value' => $summary['conversion_rate'] . '%', 'hint' => 'Đã chuyển: ' . number_format($summary['converted_lead_count']), 'tone' => 'green'],
            ['label' => 'Học viên đang học', 'value' => number_format($summary['active_enrollment_count']), 'hint' => 'Lớp hoạt động: ' . number_format($summary['active_class_count']), 'tone' => 'indigo'],
            ['label' => 'Tiến độ trung bình', 'value' => $summary['average_progress_percent'] . '%', 'hint' => 'Lịch sắp tới: ' . number_format($summary['upcoming_session_count']), 'tone' => 'gold'],
        ];

        $finance = [
            ['label' => 'Doanh số đăng ký', 'value' => $this->formatVnd($summary['order_total_vnd']), 'tone' => 'blue'],
            ['label' => 'Đã thu', 'value' => $this->formatVnd($summary['paid_vnd']), 'tone' => 'green'],
            ['label' => 'Công nợ', 'value' => $this->formatVnd($summary['receivable_vnd']), 'tone' => 'gold'],
        ];

        $readinessCards = [
            ['label' => 'Khóa đã public', 'value' => number_format($phase2Readiness['published_course_count'])],
            ['label' => 'Bài viết / Video public', 'value' => number_format($phase2Readiness['published_content_count']) . ' / ' . number_format($phase2Readiness['published_video_count'])],
            ['label' => 'Tiến độ video ghi nhận', 'value' => number_format($phase2Readiness['tracked_lesson_progress_count'])],
            ['label' => 'Lead có nguồn / affiliate', 'value' => number_format($phase2Readiness['lead_source_count']) . ' / ' . number_format($phase2Readiness['affiliate_lead_count'])],
            ['label' => 'Đối tác / click affiliate', 'value' => number_format($phase2Readiness['affiliate_partner_count']) . ' / ' . number_format($phase2Readiness['affiliate_click_count'])],
            ['label' => 'Hoa hồng chờ duyệt', 'value' => $this->formatVnd($phase2Readiness['pending_commission_vnd'])],
            ['label' => 'Học viên đang học', 'value' => number_format($phase2Readiness['active_student_count'])],
            ['label' => 'Báo cáo tiến bộ public', 'value' => number_format($phase2Readiness['published_progress_report_count'])],
        ];
    @endphp

    <style>
        .dayai-dashboard { display: grid; gap: 24px; }
        .dayai-hero { position: relative; overflow: hidden; border-radius: 28px; padding: 28px; color: #fff; background: radial-gradient(circle at 10% 20%, rgba(0, 174, 239, .48), transparent 28%), radial-gradient(circle at 82% 12%, rgba(245, 180, 0, .35), transparent 24%), linear-gradient(135deg, #031633 0%, #003a99 52%, #07111f 100%); box-shadow: 0 24px 80px rgba(0, 58, 153, .28); }
        .dayai-hero:after { content: ''; position: absolute; inset: -80px -120px auto auto; width: 360px; height: 360px; border-radius: 999px; background: rgba(255,255,255,.13); filter: blur(6px); }
        .dayai-hero__content { position: relative; z-index: 1; display: flex; flex-wrap: wrap; align-items: end; justify-content: space-between; gap: 18px; }
        .dayai-eyebrow { margin: 0 0 10px; font-size: 12px; font-weight: 800; letter-spacing: .16em; text-transform: uppercase; color: #bfefff; }
        .dayai-title { margin: 0; max-width: 760px; font-size: clamp(30px, 4vw, 52px); line-height: 1.02; font-weight: 850; letter-spacing: -.04em; }
        .dayai-subtitle { margin: 14px 0 0; max-width: 760px; color: rgba(255,255,255,.78); font-size: 15px; line-height: 1.7; }
        .dayai-hero__badge { border: 1px solid rgba(255,255,255,.24); border-radius: 18px; padding: 14px 16px; background: rgba(255,255,255,.1); backdrop-filter: blur(16px); min-width: 190px; }
        .dayai-hero__badge strong { display: block; font-size: 28px; line-height: 1; }
        .dayai-hero__badge span { display: block; margin-top: 6px; color: rgba(255,255,255,.72); font-size: 12px; }
        .dayai-grid { display: grid; gap: 16px; }
        .dayai-grid--4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .dayai-grid--3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .dayai-grid--2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .dayai-card { overflow: hidden; border: 1px solid rgba(148, 163, 184, .22); border-radius: 22px; background: var(--dayai-card-bg, #fff); box-shadow: 0 14px 40px rgba(15, 23, 42, .06); }
        .dark .dayai-card { --dayai-card-bg: #101827; border-color: rgba(255,255,255,.1); box-shadow: 0 14px 50px rgba(0,0,0,.24); }
        .dayai-kpi { position: relative; min-height: 145px; padding: 20px; }
        .dayai-kpi:before { content: ''; position: absolute; inset: 0 0 auto; height: 4px; background: var(--accent, #00aeef); }
        .dayai-kpi__label { color: #64748b; font-size: 13px; font-weight: 750; }
        .dark .dayai-kpi__label { color: #9ca3af; }
        .dayai-kpi__value { margin-top: 16px; color: #0f172a; font-size: 34px; line-height: 1; font-weight: 850; letter-spacing: -.04em; }
        .dark .dayai-kpi__value { color: #fff; }
        .dayai-kpi__hint { margin-top: 12px; color: #64748b; font-size: 13px; }
        .dark .dayai-kpi__hint { color: #aab3c2; }
        .dayai-tone-blue { --accent: #00aeef; }
        .dayai-tone-green { --accent: #22c55e; }
        .dayai-tone-indigo { --accent: #6366f1; }
        .dayai-tone-gold { --accent: #f5b400; }
        .dayai-section { padding: 20px; }
        .dayai-section__head { display: flex; align-items: start; justify-content: space-between; gap: 16px; margin-bottom: 16px; }
        .dayai-section__title { margin: 0; color: #0f172a; font-size: 17px; font-weight: 850; letter-spacing: -.02em; }
        .dark .dayai-section__title { color: #fff; }
        .dayai-section__desc { margin: 6px 0 0; color: #64748b; font-size: 13px; line-height: 1.6; }
        .dark .dayai-section__desc { color: #9ca3af; }
        .dayai-pill { display: inline-flex; align-items: center; border-radius: 999px; padding: 6px 10px; background: rgba(0, 174, 239, .12); color: #0076bd; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; white-space: nowrap; }
        .dark .dayai-pill { background: rgba(0, 174, 239, .18); color: #7ddcff; }
        .dayai-mini-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; }
        .dayai-mini { border-radius: 16px; padding: 15px; background: #f8fafc; border: 1px solid rgba(148, 163, 184, .16); }
        .dark .dayai-mini { background: rgba(255,255,255,.045); border-color: rgba(255,255,255,.08); }
        .dayai-mini__label { color: #64748b; font-size: 12px; line-height: 1.4; }
        .dark .dayai-mini__label { color: #aab3c2; }
        .dayai-mini__value { margin-top: 10px; color: #0f172a; font-size: 21px; font-weight: 850; letter-spacing: -.03em; }
        .dark .dayai-mini__value { color: #fff; }
        .dayai-table-wrap { overflow-x: auto; }
        .dayai-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 13px; }
        .dayai-table th { background: #f8fafc; color: #64748b; font-size: 11px; font-weight: 850; letter-spacing: .08em; text-transform: uppercase; text-align: left; padding: 12px 16px; border-bottom: 1px solid rgba(148, 163, 184, .18); }
        .dark .dayai-table th { background: rgba(255,255,255,.045); color: #aab3c2; border-color: rgba(255,255,255,.08); }
        .dayai-table td { color: #334155; padding: 14px 16px; border-bottom: 1px solid rgba(148, 163, 184, .14); vertical-align: middle; }
        .dark .dayai-table td { color: #d1d5db; border-color: rgba(255,255,255,.07); }
        .dayai-table tr:last-child td { border-bottom: 0; }
        .dayai-table .strong { color: #0f172a; font-weight: 800; }
        .dark .dayai-table .strong { color: #fff; }
        .dayai-right { text-align: right !important; }
        .dayai-list { display: grid; gap: 10px; }
        .dayai-list-item { display: flex; align-items: center; justify-content: space-between; gap: 14px; border-radius: 16px; padding: 13px 14px; background: #f8fafc; border: 1px solid rgba(148, 163, 184, .14); }
        .dark .dayai-list-item { background: rgba(255,255,255,.045); border-color: rgba(255,255,255,.08); }
        .dayai-list-item__title { color: #0f172a; font-size: 13px; font-weight: 800; }
        .dark .dayai-list-item__title { color: #fff; }
        .dayai-list-item__meta { margin-top: 4px; color: #64748b; font-size: 12px; }
        .dark .dayai-list-item__meta { color: #aab3c2; }
        .dayai-list-item__value { color: #0f172a; font-size: 13px; font-weight: 850; text-align: right; white-space: nowrap; }
        .dark .dayai-list-item__value { color: #fff; }
        .dayai-empty { padding: 28px 16px; color: #64748b; text-align: center; font-size: 13px; }
        .dark .dayai-empty { color: #aab3c2; }
        @media (max-width: 1100px) { .dayai-grid--4, .dayai-mini-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } .dayai-grid--3 { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 760px) { .dayai-hero { padding: 22px; } .dayai-grid--4, .dayai-grid--3, .dayai-grid--2, .dayai-mini-grid { grid-template-columns: 1fr; } .dayai-section__head { display: block; } .dayai-pill { margin-top: 10px; } }
    </style>

    <div class="dayai-dashboard">
        <section class="dayai-hero">
            <div class="dayai-hero__content">
                <div>
                    <p class="dayai-eyebrow">DAYAI Command Center</p>
                    <h1 class="dayai-title">Dashboard tuyển sinh & vận hành</h1>
                    <p class="dayai-subtitle">Theo dõi nhanh sức khỏe trung tâm: lead, doanh thu, lớp học, học viên, affiliate và automation trên cùng một màn hình.</p>
                </div>
                <div class="dayai-hero__badge">
                    <strong>{{ $summary['conversion_rate'] }}%</strong>
                    <span>Tỷ lệ chuyển đổi lead hiện tại</span>
                </div>
            </div>
        </section>

        <section class="dayai-grid dayai-grid--4">
            @foreach ($kpis as $kpi)
                <article class="dayai-card dayai-kpi dayai-tone-{{ $kpi['tone'] }}">
                    <div class="dayai-kpi__label">{{ $kpi['label'] }}</div>
                    <div class="dayai-kpi__value">{{ $kpi['value'] }}</div>
                    <div class="dayai-kpi__hint">{{ $kpi['hint'] }}</div>
                </article>
            @endforeach
        </section>

        <section class="dayai-grid dayai-grid--3">
            @foreach ($finance as $item)
                <article class="dayai-card dayai-kpi dayai-tone-{{ $item['tone'] }}">
                    <div class="dayai-kpi__label">{{ $item['label'] }}</div>
                    <div class="dayai-kpi__value">{{ $item['value'] }}</div>
                    <div class="dayai-kpi__hint">Theo dữ liệu đơn hàng / thanh toán / công nợ</div>
                </article>
            @endforeach
        </section>

        <section class="dayai-card dayai-section">
            <div class="dayai-section__head">
                <div>
                    <h2 class="dayai-section__title">Phase 2 readiness</h2>
                    <p class="dayai-section__desc">Bức tranh nhanh trước UAT/staging: nội dung, LMS, tuyển sinh, học viên, affiliate và báo cáo.</p>
                </div>
                <span class="dayai-pill">Sprint 27+</span>
            </div>
            <div class="dayai-mini-grid">
                @foreach ($readinessCards as $card)
                    <div class="dayai-mini">
                        <div class="dayai-mini__label">{{ $card['label'] }}</div>
                        <div class="dayai-mini__value">{{ $card['value'] }}</div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="dayai-grid dayai-grid--2">
            <article class="dayai-card dayai-section">
                <div class="dayai-section__head">
                    <div>
                        <h2 class="dayai-section__title">Phễu tuyển sinh</h2>
                        <p class="dayai-section__desc">Theo trạng thái lead hiện tại.</p>
                    </div>
                    <span class="dayai-pill">CRM</span>
                </div>
                <div class="dayai-list">
                    @forelse ($leadFunnel as $row)
                        <div class="dayai-list-item">
                            <div>
                                <div class="dayai-list-item__title">{{ $this->formatStatus($row->status) }}</div>
                                <div class="dayai-list-item__meta">Trạng thái lead</div>
                            </div>
                            <div class="dayai-list-item__value">{{ number_format($row->total) }}</div>
                        </div>
                    @empty
                        <div class="dayai-empty">Chưa có lead.</div>
                    @endforelse
                </div>
            </article>

            <article class="dayai-card dayai-section">
                <div class="dayai-section__head">
                    <div>
                        <h2 class="dayai-section__title">Hiệu quả nguồn lead</h2>
                        <p class="dayai-section__desc">So sánh lead và số đã chuyển đổi theo từng nguồn.</p>
                    </div>
                    <span class="dayai-pill">Attribution</span>
                </div>
                <div class="dayai-table-wrap">
                    <table class="dayai-table">
                        <thead><tr><th>Nguồn</th><th class="dayai-right">Lead</th><th class="dayai-right">Chuyển đổi</th></tr></thead>
                        <tbody>
                            @forelse ($leadSources as $row)
                                <tr><td class="strong">{{ $row->source_name }}</td><td class="dayai-right">{{ number_format($row->lead_count) }}</td><td class="dayai-right strong">{{ number_format($row->converted_count) }}</td></tr>
                            @empty
                                <tr><td colspan="3" class="dayai-empty">Chưa có dữ liệu nguồn lead.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        </section>

        <section class="dayai-grid dayai-grid--3">
            <article class="dayai-card dayai-section">
                <div class="dayai-section__head"><div><h2 class="dayai-section__title">Độ nóng lead</h2><p class="dayai-section__desc">Phân bổ lạnh / ấm / nóng và giá trị cơ hội.</p></div></div>
                <div class="dayai-list">
                    @forelse ($leadTemperatures as $row)
                        <div class="dayai-list-item"><div><div class="dayai-list-item__title">{{ $this->formatTemperature($row->temperature) }}</div><div class="dayai-list-item__meta">{{ number_format($row->total) }} lead</div></div><div class="dayai-list-item__value">{{ $this->formatVnd($row->expected_value_vnd) }}</div></div>
                    @empty
                        <div class="dayai-empty">Chưa có dữ liệu độ nóng lead.</div>
                    @endforelse
                </div>
            </article>

            <article class="dayai-card dayai-section">
                <div class="dayai-section__head"><div><h2 class="dayai-section__title">Lead quá hạn follow-up</h2><p class="dayai-section__desc">Lead cần xử lý ngay để tránh mất cơ hội.</p></div></div>
                <div class="dayai-list">
                    @forelse ($overdueLeads as $lead)
                        <div class="dayai-list-item"><div><div class="dayai-list-item__title">{{ $lead->full_name }}</div><div class="dayai-list-item__meta">{{ $lead->phone }} · {{ $lead->assignedUser?->name ?? 'Chưa phân công' }}</div></div><div class="dayai-list-item__value">{{ $lead->next_follow_up_at?->format('d/m H:i') }}</div></div>
                    @empty
                        <div class="dayai-empty">Không có lead quá hạn.</div>
                    @endforelse
                </div>
            </article>

            <article class="dayai-card dayai-section">
                <div class="dayai-section__head"><div><h2 class="dayai-section__title">Hiệu quả tư vấn viên</h2><p class="dayai-section__desc">Lead, chuyển đổi và giá trị cơ hội.</p></div></div>
                <div class="dayai-list">
                    @forelse ($salesPerformance as $row)
                        <div class="dayai-list-item"><div><div class="dayai-list-item__title">{{ $row->consultant_name }}</div><div class="dayai-list-item__meta">Chuyển đổi {{ number_format($row->converted_count) }}/{{ number_format($row->lead_count) }}</div></div><div class="dayai-list-item__value">{{ $this->formatVnd($row->expected_value_vnd) }}</div></div>
                    @empty
                        <div class="dayai-empty">Chưa có dữ liệu tư vấn viên.</div>
                    @endforelse
                </div>
            </article>
        </section>

        <section class="dayai-card dayai-section">
            <div class="dayai-section__head">
                <div><h2 class="dayai-section__title">Hiệu quả affiliate</h2><p class="dayai-section__desc">Doanh số, hoa hồng và khoản chờ duyệt theo đối tác.</p></div>
                <span class="dayai-pill">Affiliate</span>
            </div>
            <div class="dayai-table-wrap">
                <table class="dayai-table">
                    <thead><tr><th>Đối tác</th><th class="dayai-right">Số commission</th><th class="dayai-right">Doanh số</th><th class="dayai-right">Hoa hồng</th><th class="dayai-right">Chờ duyệt</th></tr></thead>
                    <tbody>
                        @forelse ($affiliatePerformance as $row)
                            <tr><td class="strong">{{ $row->partner_name }}</td><td class="dayai-right">{{ number_format($row->commission_count) }}</td><td class="dayai-right">{{ $this->formatVnd($row->order_total_vnd) }}</td><td class="dayai-right strong">{{ $this->formatVnd($row->commission_vnd) }}</td><td class="dayai-right strong">{{ $this->formatVnd($row->pending_commission_vnd) }}</td></tr>
                        @empty
                            <tr><td colspan="5" class="dayai-empty">Chưa có commission affiliate.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="dayai-card dayai-section">
            <div class="dayai-section__head">
                <div><h2 class="dayai-section__title">Automation health</h2><p class="dayai-section__desc">Theo dõi workflow chăm sóc lead, lịch học và công nợ.</p></div>
                <span class="dayai-pill">Workflow</span>
            </div>
            <div class="dayai-mini-grid">
                <div class="dayai-mini"><div class="dayai-mini__label">Workflow active</div><div class="dayai-mini__value">{{ number_format($automationHealth['active_workflow_count']) }}</div></div>
                <div class="dayai-mini"><div class="dayai-mini__label">Đã gửi</div><div class="dayai-mini__value">{{ number_format($automationHealth['sent_log_count']) }}</div></div>
                <div class="dayai-mini"><div class="dayai-mini__label">Lỗi gửi</div><div class="dayai-mini__value">{{ number_format($automationHealth['failed_log_count']) }}</div></div>
                <div class="dayai-mini"><div class="dayai-mini__label">Lần gửi gần nhất</div><div class="dayai-mini__value" style="font-size: 14px;">{{ $automationHealth['latest_sent_at'] ?? 'Chưa chạy' }}</div></div>
            </div>
            <div class="dayai-table-wrap" style="margin-top: 16px;">
                <table class="dayai-table">
                    <thead><tr><th>Workflow</th><th>Trigger</th><th>Kênh</th><th>Trạng thái</th></tr></thead>
                    <tbody>
                        @forelse ($automationRecentLogs as $log)
                            <tr><td class="strong">{{ $log->workflow?->name ?? 'Workflow' }}</td><td>{{ $log->trigger_type }}</td><td>{{ $log->channel }}</td><td class="strong">{{ $log->status }}</td></tr>
                        @empty
                            <tr><td colspan="4" class="dayai-empty">Chưa có log automation.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="dayai-grid dayai-grid--2">
            <article class="dayai-card dayai-section">
                <div class="dayai-section__head"><div><h2 class="dayai-section__title">Lịch học sắp tới</h2><p class="dayai-section__desc">Các buổi học cần vận hành trong thời gian tới.</p></div></div>
                <div class="dayai-list">
                    @forelse ($upcomingSessions as $session)
                        <div class="dayai-list-item"><div><div class="dayai-list-item__title">{{ $session->title }}</div><div class="dayai-list-item__meta">{{ $session->classGroup?->name }} · {{ $session->classGroup?->course?->name }}</div></div><div class="dayai-list-item__value">{{ $session->starts_at?->format('d/m H:i') }}</div></div>
                    @empty
                        <div class="dayai-empty">Chưa có lịch học sắp tới.</div>
                    @endforelse
                </div>
            </article>

            <article class="dayai-card dayai-section">
                <div class="dayai-section__head"><div><h2 class="dayai-section__title">Học viên cần chú ý</h2><p class="dayai-section__desc">Dựa trên điểm danh vắng / muộn / xin nghỉ.</p></div></div>
                <div class="dayai-list">
                    @forelse ($atRiskStudents as $student)
                        <div class="dayai-list-item"><div><div class="dayai-list-item__title">{{ $student->full_name }}</div><div class="dayai-list-item__meta">{{ $student->student_code }} · {{ $student->class_group_name ?? 'Chưa rõ lớp' }}</div></div><div class="dayai-list-item__value">Vắng {{ $student->absent_count }} · Muộn {{ $student->late_count }}</div></div>
                    @empty
                        <div class="dayai-empty">Chưa có học viên cần chú ý.</div>
                    @endforelse
                </div>
            </article>
        </section>

        <section class="dayai-card dayai-section">
            <div class="dayai-section__head">
                <div><h2 class="dayai-section__title">Doanh thu theo khóa</h2><p class="dayai-section__desc">Tổng doanh số và số đã thu theo từng khóa học.</p></div>
                <span class="dayai-pill">Finance</span>
            </div>
            <div class="dayai-table-wrap">
                <table class="dayai-table">
                    <thead><tr><th>Khóa học</th><th class="dayai-right">Doanh số</th><th class="dayai-right">Đã thu</th></tr></thead>
                    <tbody>
                        @forelse ($courseRevenue as $row)
                            <tr><td class="strong">{{ $row->course_name }}</td><td class="dayai-right">{{ $this->formatVnd($row->sold_vnd) }}</td><td class="dayai-right strong">{{ $this->formatVnd($row->paid_vnd) }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="dayai-empty">Chưa có doanh thu theo khóa.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-filament-panels::page>
