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
        .dayai-dashboard { display: grid; gap: 24px; font-family: ui-sans-serif, system-ui, sans-serif; }
        .dayai-hero { position: relative; overflow: hidden; border-radius: 24px; padding: 32px; color: #fff; background: radial-gradient(circle at 10% 20%, rgba(2, 132, 199, 0.3), transparent 45%), radial-gradient(circle at 90% 80%, rgba(79, 70, 229, 0.3), transparent 45%), linear-gradient(135deg, #090d16 0%, #1e1b4b 100%); box-shadow: 0 20px 50px rgba(9, 9, 11, 0.3); border: 1px solid rgba(255, 255, 255, 0.08); transition: all 0.3s ease; }
        .dayai-hero::after { content: ''; position: absolute; top: -50%; right: -20%; width: 300px; height: 300px; border-radius: 50%; background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%); filter: blur(40px); pointer-events: none; }
        .dayai-hero__content { position: relative; z-index: 1; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 20px; }
        .dayai-eyebrow { margin: 0 0 8px; font-size: 11px; font-weight: 800; letter-spacing: 0.2em; text-transform: uppercase; color: #38bdf8; text-shadow: 0 2px 10px rgba(56, 189, 248, 0.3); }
        .dayai-title { margin: 0; max-width: 760px; font-size: clamp(26px, 3.5vw, 44px); line-height: 1.1; font-weight: 850; letter-spacing: -0.03em; background: linear-gradient(135deg, #fff 40%, #e2e8f0 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .dayai-subtitle { margin: 12px 0 0; max-width: 700px; color: #94a3b8; font-size: 14px; line-height: 1.6; }
        .dayai-hero__badge { border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 16px 20px; background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); min-width: 200px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); text-align: center; }
        .dayai-hero__badge strong { display: block; font-size: 32px; line-height: 1; font-weight: 900; background: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .dayai-hero__badge span { display: block; margin-top: 6px; color: #94a3b8; font-size: 12px; font-weight: 500; }
        .dayai-grid { display: grid; gap: 20px; }
        .dayai-grid--4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .dayai-grid--3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .dayai-grid--2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .dayai-card { overflow: hidden; border-radius: 20px; background: #ffffff; border: 1px solid rgba(226, 232, 240, 0.8); box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03), 0 1px 3px rgba(15, 23, 42, 0.02); transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
        .dayai-card:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06), 0 2px 8px rgba(15, 23, 42, 0.04); }
        .dark .dayai-card { background: #111827; border-color: rgba(255, 255, 255, 0.05); box-shadow: 0 4px 24px rgba(0, 0, 0, 0.2); }
        .dark .dayai-card:hover { box-shadow: 0 12px 40px rgba(0, 0, 0, 0.35); }
        .dayai-kpi { position: relative; min-height: 140px; padding: 24px; display: flex; flex-direction: column; justify-content: space-between; }
        .dayai-kpi::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--accent, #0ea5e9); border-top-left-radius: 20px; border-bottom-left-radius: 20px; }
        .dayai-kpi__label { color: #64748b; font-size: 13px; font-weight: 600; letter-spacing: 0.01em; }
        .dark .dayai-kpi__label { color: #94a3b8; }
        .dayai-kpi__value { margin-top: 12px; color: #0f172a; font-size: 32px; line-height: 1; font-weight: 800; letter-spacing: -0.03em; }
        .dark .dayai-kpi__value { color: #f8fafc; }
        .dayai-kpi__hint { margin-top: 12px; color: #64748b; font-size: 12px; display: flex; align-items: center; gap: 4px; }
        .dark .dayai-kpi__hint { color: #94a3b8; }
        .dayai-tone-blue { --accent: #0284c7; }
        .dayai-tone-green { --accent: #16a34a; }
        .dayai-tone-indigo { --accent: #4f46e5; }
        .dayai-tone-gold { --accent: #d97706; }
        .dayai-section { padding: 24px; }
        .dayai-section__head { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 20px; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 16px; }
        .dark .dayai-section__head { border-bottom-color: rgba(255, 255, 255, 0.05); }
        .dayai-section__title { margin: 0; color: #0f172a; font-size: 18px; font-weight: 800; letter-spacing: -0.02em; }
        .dark .dayai-section__title { color: #f8fafc; }
        .dayai-section__desc { margin: 4px 0 0; color: #64748b; font-size: 13px; line-height: 1.5; }
        .dark .dayai-section__desc { color: #94a3b8; }
        .dayai-pill { display: inline-flex; align-items: center; border-radius: 8px; padding: 4px 8px; background: rgba(2, 132, 199, 0.08); color: #0284c7; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
        .dark .dayai-pill { background: rgba(56, 189, 248, 0.12); color: #38bdf8; }
        .dayai-btn-primary { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 8px 16px; font-size: 13px; font-weight: 700; color: #fff; background: linear-gradient(135deg, #0284c7 0%, #4f46e5 100%); border-radius: 10px; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.2); transition: all 0.2s ease-in-out; cursor: pointer; border: none; outline: none; }
        .dayai-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(2, 132, 199, 0.35); filter: brightness(1.08); }
        .dayai-btn-primary:active { transform: translateY(0); }
        .dayai-btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none; }
        .dayai-spinner { display: inline-block; width: 14px; height: 14px; border: 2px solid rgba(255, 255, 255, 0.3); border-radius: 50%; border-top-color: #fff; animation: dayai-spin 1s ease-in-out infinite; }
        @keyframes dayai-spin { to { transform: rotate(360deg); } }
        .dayai-mini-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
        .dayai-mini { border-radius: 14px; padding: 18px; background: #f8fafc; border: 1px solid rgba(226, 232, 240, 0.8); transition: background-color 0.2s ease; }
        .dark .dayai-mini { background: rgba(255, 255, 255, 0.02); border-color: rgba(255, 255, 255, 0.04); }
        .dayai-mini__label { color: #64748b; font-size: 12px; font-weight: 500; }
        .dark .dayai-mini__label { color: #94a3b8; }
        .dayai-mini__value { margin-top: 8px; color: #0f172a; font-size: 22px; font-weight: 800; letter-spacing: -0.02em; }
        .dark .dayai-mini__value { color: #f8fafc; }
        .dayai-table-wrap { overflow-x: auto; margin: 0 -24px -24px; }
        .dayai-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 13px; }
        .dayai-table th { background: #f8fafc; color: #475569; font-size: 11px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; text-align: left; padding: 12px 24px; border-bottom: 1px solid rgba(226, 232, 240, 0.8); }
        .dark .dayai-table th { background: rgba(255, 255, 255, 0.02); color: #94a3b8; border-bottom-color: rgba(255, 255, 255, 0.05); }
        .dayai-table td { color: #334155; padding: 14px 24px; border-bottom: 1px solid rgba(226, 232, 240, 0.5); vertical-align: middle; }
        .dark .dayai-table td { color: #cbd5e1; border-bottom-color: rgba(255, 255, 255, 0.03); }
        .dayai-table tr:last-child td { border-bottom: 0; }
        .dayai-table .strong { color: #0f172a; font-weight: 700; }
        .dark .dayai-table .strong { color: #f8fafc; }
        .dayai-right { text-align: right !important; }
        .dayai-list { display: grid; gap: 12px; }
        .dayai-list-item { display: flex; align-items: center; justify-content: space-between; gap: 16px; border-radius: 14px; padding: 14px 18px; background: #f8fafc; border: 1px solid rgba(226, 232, 240, 0.5); transition: all 0.2s ease; }
        .dayai-list-item:hover { background: #f1f5f9; border-color: rgba(226, 232, 240, 0.8); }
        .dark .dayai-list-item { background: rgba(255, 255, 255, 0.02); border-color: rgba(255, 255, 255, 0.04); }
        .dark .dayai-list-item:hover { background: rgba(255, 255, 255, 0.04); border-color: rgba(255, 255, 255, 0.08); }
        .dayai-list-item__title { color: #0f172a; font-size: 13px; font-weight: 700; }
        .dark .dayai-list-item__title { color: #f8fafc; }
        .dayai-list-item__meta { margin-top: 4px; color: #64748b; font-size: 12px; }
        .dark .dayai-list-item__meta { color: #94a3b8; }
        .dayai-list-item__value { color: #0f172a; font-size: 13px; font-weight: 800; text-align: right; white-space: nowrap; }
        .dark .dayai-list-item__value { color: #f8fafc; }
        .dayai-empty { padding: 32px 24px; color: #64748b; text-align: center; font-size: 13px; }
        .dark .dayai-empty { color: #94a3b8; }
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
                <div style="display: flex; align-items: center; gap: 12px;">
                    <button 
                        type="button" 
                        wire:click="runAutomation" 
                        wire:loading.attr="disabled"
                        class="dayai-btn-primary"
                    >
                        <span wire:loading wire:target="runAutomation" class="dayai-spinner"></span>
                        <span wire:loading.remove wire:target="runAutomation">Chạy thử Automation ngay</span>
                        <span wire:loading wire:target="runAutomation">Đang xử lý...</span>
                    </button>
                    <span class="dayai-pill">Workflow</span>
                </div>
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
