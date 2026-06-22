<x-filament-panels::page>
    @php
        $summary = $this->getSummary();
        $monthlyRevenue = $this->getMonthlyRevenue();
        $courseRevenue = $this->getCourseRevenue();
        $maxMonthly = max(1, (int) $monthlyRevenue->max('paid_vnd'));
        $maxCourse = max(1, (int) $courseRevenue->max('sold_vnd'));
        $collectionRate = $summary['order_total_vnd'] > 0 ? (int) round($summary['paid_vnd'] * 100 / $summary['order_total_vnd']) : 0;
        $debtRate = $summary['order_total_vnd'] > 0 ? (int) round($summary['receivable_vnd'] * 100 / $summary['order_total_vnd']) : 0;
        $kpis = [
            ['label' => 'Doanh số đăng ký', 'value' => $this->formatVnd($summary['order_total_vnd']), 'hint' => 'Tổng giá trị đơn đăng ký', 'tone' => 'blue'],
            ['label' => 'Đã thu', 'value' => $this->formatVnd($summary['paid_vnd']), 'hint' => 'Tỷ lệ thu: ' . $collectionRate . '%', 'tone' => 'green'],
            ['label' => 'Công nợ còn lại', 'value' => $this->formatVnd($summary['receivable_vnd']), 'hint' => 'Tỷ trọng nợ: ' . $debtRate . '%', 'tone' => 'gold'],
            ['label' => 'Số hóa đơn', 'value' => number_format($summary['invoice_count']), 'hint' => 'Tổng hóa đơn đã ghi nhận', 'tone' => 'indigo'],
        ];
    @endphp

    <style>
        .dayai-report { display: grid; gap: 24px; }
        .dayai-report-hero { position: relative; overflow: hidden; border-radius: 28px; padding: 28px; color: #fff; background: radial-gradient(circle at 12% 16%, rgba(0,174,239,.48), transparent 30%), radial-gradient(circle at 88% 10%, rgba(245,180,0,.35), transparent 25%), linear-gradient(135deg,#031633 0%,#003a99 54%,#07111f 100%); box-shadow: 0 24px 80px rgba(0,58,153,.25); }
        .dayai-report-hero__row { position: relative; z-index: 1; display: flex; flex-wrap: wrap; align-items: end; justify-content: space-between; gap: 18px; }
        .dayai-eyebrow { margin: 0 0 10px; color: #bfefff; font-size: 12px; font-weight: 850; letter-spacing: .16em; text-transform: uppercase; }
        .dayai-title { margin: 0; color: #fff; font-size: clamp(30px,4vw,50px); line-height: 1.02; font-weight: 900; letter-spacing: -.04em; }
        .dayai-subtitle { margin: 14px 0 0; max-width: 740px; color: rgba(255,255,255,.78); font-size: 15px; line-height: 1.65; }
        .dayai-hero-stat { border: 1px solid rgba(255,255,255,.24); border-radius: 18px; padding: 14px 16px; background: rgba(255,255,255,.1); min-width: 190px; backdrop-filter: blur(16px); }
        .dayai-hero-stat strong { display: block; font-size: 30px; line-height: 1; }
        .dayai-hero-stat span { display: block; margin-top: 6px; color: rgba(255,255,255,.72); font-size: 12px; }
        .dayai-grid { display: grid; gap: 16px; }
        .dayai-grid--4 { grid-template-columns: repeat(4,minmax(0,1fr)); }
        .dayai-grid--2 { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .dayai-card { overflow: hidden; border: 1px solid rgba(148,163,184,.22); border-radius: 22px; background: var(--card-bg,#fff); box-shadow: 0 14px 40px rgba(15,23,42,.06); }
        .dark .dayai-card { --card-bg: #101827; border-color: rgba(255,255,255,.1); box-shadow: 0 14px 50px rgba(0,0,0,.24); }
        .dayai-kpi { position: relative; min-height: 145px; padding: 20px; }
        .dayai-kpi:before { content: ''; position: absolute; inset: 0 0 auto; height: 4px; background: var(--accent,#00aeef); }
        .dayai-tone-blue { --accent: #00aeef; } .dayai-tone-green { --accent:#22c55e; } .dayai-tone-gold { --accent:#f5b400; } .dayai-tone-indigo { --accent:#6366f1; }
        .dayai-kpi__label { color: #64748b; font-size: 13px; font-weight: 800; } .dark .dayai-kpi__label { color:#9ca3af; }
        .dayai-kpi__value { margin-top: 16px; color: #0f172a; font-size: 30px; line-height: 1.08; font-weight: 900; letter-spacing: -.04em; } .dark .dayai-kpi__value { color:#fff; }
        .dayai-kpi__hint { margin-top: 12px; color:#64748b; font-size: 13px; } .dark .dayai-kpi__hint { color:#aab3c2; }
        .dayai-section { padding: 20px; }
        .dayai-section__head { display:flex; align-items:start; justify-content:space-between; gap:16px; margin-bottom:16px; }
        .dayai-section__title { margin:0; color:#0f172a; font-size:17px; font-weight:900; letter-spacing:-.02em; } .dark .dayai-section__title { color:#fff; }
        .dayai-section__desc { margin:6px 0 0; color:#64748b; font-size:13px; line-height:1.6; } .dark .dayai-section__desc { color:#9ca3af; }
        .dayai-pill { display:inline-flex; border-radius:999px; padding:6px 10px; background:rgba(0,174,239,.12); color:#0076bd; font-size:11px; font-weight:850; text-transform:uppercase; letter-spacing:.08em; white-space:nowrap; } .dark .dayai-pill { background:rgba(0,174,239,.18); color:#7ddcff; }
        .dayai-table-wrap { overflow-x:auto; }
        .dayai-table { width:100%; border-collapse:separate; border-spacing:0; font-size:13px; }
        .dayai-table th { padding:12px 16px; background:#f8fafc; color:#64748b; border-bottom:1px solid rgba(148,163,184,.18); font-size:11px; font-weight:900; letter-spacing:.08em; text-transform:uppercase; text-align:left; } .dark .dayai-table th { background:rgba(255,255,255,.045); color:#aab3c2; border-color:rgba(255,255,255,.08); }
        .dayai-table td { padding:14px 16px; color:#334155; border-bottom:1px solid rgba(148,163,184,.14); vertical-align:middle; } .dark .dayai-table td { color:#d1d5db; border-color:rgba(255,255,255,.07); }
        .dayai-table tr:last-child td { border-bottom:0; } .dayai-strong { color:#0f172a; font-weight:850; } .dark .dayai-strong { color:#fff; } .dayai-right { text-align:right !important; }
        .dayai-bars { display:grid; gap:12px; }
        .dayai-bar-row { display:grid; grid-template-columns: 96px 1fr 130px; align-items:center; gap:12px; }
        .dayai-bar-label { color:#0f172a; font-size:13px; font-weight:800; } .dark .dayai-bar-label { color:#fff; }
        .dayai-bar-track { height:12px; overflow:hidden; border-radius:999px; background:#e2e8f0; } .dark .dayai-bar-track { background:rgba(255,255,255,.08); }
        .dayai-bar-fill { height:100%; border-radius:999px; background:linear-gradient(90deg,#00aeef,#003a99); }
        .dayai-bar-value { color:#334155; font-size:13px; font-weight:850; text-align:right; } .dark .dayai-bar-value { color:#d1d5db; }
        .dayai-empty { padding:28px 16px; color:#64748b; text-align:center; font-size:13px; } .dark .dayai-empty { color:#aab3c2; }
        @media (max-width:1100px){ .dayai-grid--4,.dayai-grid--2{grid-template-columns:repeat(2,minmax(0,1fr));} }
        @media (max-width:760px){ .dayai-grid--4,.dayai-grid--2{grid-template-columns:1fr;} .dayai-report-hero{padding:22px;} .dayai-bar-row{grid-template-columns:1fr;} .dayai-bar-value{text-align:left;} .dayai-section__head{display:block;} .dayai-pill{margin-top:10px;} }
    </style>

    <div class="dayai-report">
        <section class="dayai-report-hero">
            <div class="dayai-report-hero__row">
                <div>
                    <p class="dayai-eyebrow">DAYAI Finance Control</p>
                    <h1 class="dayai-title">Báo cáo doanh thu</h1>
                    <p class="dayai-subtitle">Theo dõi doanh số đăng ký, số tiền đã thu, công nợ còn lại và hiệu quả từng khóa học để ra quyết định vận hành nhanh hơn.</p>
                </div>
                <div class="dayai-hero-stat"><strong>{{ $collectionRate }}%</strong><span>Tỷ lệ thu trên doanh số</span></div>
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

        <section class="dayai-grid dayai-grid--2">
            <article class="dayai-card dayai-section">
                <div class="dayai-section__head"><div><h2 class="dayai-section__title">Doanh thu theo tháng</h2><p class="dayai-section__desc">Tính theo các thanh toán đã hoàn tất.</p></div><span class="dayai-pill">Monthly</span></div>
                <div class="dayai-bars">
                    @forelse ($monthlyRevenue as $row)
                        @php $width = max(4, (int) round(((int) $row->paid_vnd) * 100 / $maxMonthly)); @endphp
                        <div class="dayai-bar-row"><div class="dayai-bar-label">{{ $row->period }}</div><div class="dayai-bar-track"><div class="dayai-bar-fill" style="width: {{ $width }}%"></div></div><div class="dayai-bar-value">{{ $this->formatVnd($row->paid_vnd) }}</div></div>
                    @empty
                        <div class="dayai-empty">Chưa có thanh toán hoàn tất.</div>
                    @endforelse
                </div>
            </article>

            <article class="dayai-card dayai-section">
                <div class="dayai-section__head"><div><h2 class="dayai-section__title">Cơ cấu thu tiền</h2><p class="dayai-section__desc">So sánh phần đã thu và công nợ còn lại trên tổng doanh số.</p></div><span class="dayai-pill">Cashflow</span></div>
                <div class="dayai-bars">
                    <div class="dayai-bar-row"><div class="dayai-bar-label">Đã thu</div><div class="dayai-bar-track"><div class="dayai-bar-fill" style="width: {{ max(4, $collectionRate) }}%; background: linear-gradient(90deg,#22c55e,#16a34a);"></div></div><div class="dayai-bar-value">{{ $this->formatVnd($summary['paid_vnd']) }}</div></div>
                    <div class="dayai-bar-row"><div class="dayai-bar-label">Công nợ</div><div class="dayai-bar-track"><div class="dayai-bar-fill" style="width: {{ max(4, $debtRate) }}%; background: linear-gradient(90deg,#f5b400,#f97316);"></div></div><div class="dayai-bar-value">{{ $this->formatVnd($summary['receivable_vnd']) }}</div></div>
                </div>
            </article>
        </section>

        <section class="dayai-card dayai-section">
            <div class="dayai-section__head"><div><h2 class="dayai-section__title">Doanh thu theo khóa học</h2><p class="dayai-section__desc">So sánh doanh số đăng ký và số tiền đã thu theo từng khóa.</p></div><span class="dayai-pill">Course revenue</span></div>
            <div class="dayai-table-wrap">
                <table class="dayai-table">
                    <thead><tr><th>Khóa học</th><th>Quy mô doanh số</th><th class="dayai-right">Doanh số</th><th class="dayai-right">Đã thu</th><th class="dayai-right">Tỷ lệ thu</th></tr></thead>
                    <tbody>
                        @forelse ($courseRevenue as $row)
                            @php $courseWidth = max(4, (int) round(((int) $row->sold_vnd) * 100 / $maxCourse)); $rowRate = ((int) $row->sold_vnd) > 0 ? (int) round(((int) $row->paid_vnd) * 100 / ((int) $row->sold_vnd)) : 0; @endphp
                            <tr><td class="dayai-strong">{{ $row->course_name }}</td><td><div class="dayai-bar-track"><div class="dayai-bar-fill" style="width: {{ $courseWidth }}%"></div></div></td><td class="dayai-right">{{ $this->formatVnd($row->sold_vnd) }}</td><td class="dayai-right dayai-strong">{{ $this->formatVnd($row->paid_vnd) }}</td><td class="dayai-right dayai-strong">{{ $rowRate }}%</td></tr>
                        @empty
                            <tr><td colspan="5" class="dayai-empty">Chưa có đơn đăng ký khóa học.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-filament-panels::page>
