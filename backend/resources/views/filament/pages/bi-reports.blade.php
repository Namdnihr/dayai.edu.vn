<x-filament-panels::page>
    @php
        $reports = $this->getReports();
        $totalRows = collect($reports)->sum('rows');
        $availableReports = collect($reports)->where('rows', '>', 0)->count();
    @endphp

    <style>
        .dayai-bi { display: grid; gap: 24px; }
        .dayai-bi-hero { position: relative; overflow: hidden; border-radius: 28px; padding: 28px; color: #fff; background: radial-gradient(circle at 14% 18%, rgba(0,174,239,.48), transparent 30%), radial-gradient(circle at 86% 14%, rgba(245,180,0,.34), transparent 25%), linear-gradient(135deg,#031633 0%,#003a99 54%,#07111f 100%); box-shadow: 0 24px 80px rgba(0,58,153,.25); }
        .dayai-bi-hero__row { position: relative; z-index: 1; display: flex; flex-wrap: wrap; align-items: end; justify-content: space-between; gap: 18px; }
        .dayai-eyebrow { margin: 0 0 10px; color: #bfefff; font-size: 12px; font-weight: 850; letter-spacing: .16em; text-transform: uppercase; }
        .dayai-title { margin: 0; color: #fff; font-size: clamp(30px,4vw,50px); line-height: 1.02; font-weight: 900; letter-spacing: -.04em; }
        .dayai-subtitle { margin: 14px 0 0; max-width: 760px; color: rgba(255,255,255,.78); font-size: 15px; line-height: 1.65; }
        .dayai-hero-stat { border: 1px solid rgba(255,255,255,.24); border-radius: 18px; padding: 14px 16px; background: rgba(255,255,255,.1); min-width: 190px; backdrop-filter: blur(16px); }
        .dayai-hero-stat strong { display: block; font-size: 30px; line-height: 1; }
        .dayai-hero-stat span { display: block; margin-top: 6px; color: rgba(255,255,255,.72); font-size: 12px; }
        .dayai-grid { display: grid; gap: 16px; }
        .dayai-grid--4 { grid-template-columns: repeat(4,minmax(0,1fr)); }
        .dayai-grid--2 { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .dayai-card { overflow: hidden; border: 1px solid rgba(148,163,184,.22); border-radius: 22px; background: var(--card-bg,#fff); box-shadow: 0 14px 40px rgba(15,23,42,.06); }
        .dark .dayai-card { --card-bg:#101827; border-color: rgba(255,255,255,.1); box-shadow: 0 14px 50px rgba(0,0,0,.24); }
        .dayai-kpi { position: relative; min-height: 130px; padding: 20px; }
        .dayai-kpi:before { content: ''; position: absolute; inset: 0 0 auto; height: 4px; background: var(--accent,#00aeef); }
        .dayai-tone-blue { --accent:#00aeef; } .dayai-tone-green { --accent:#22c55e; } .dayai-tone-gold { --accent:#f5b400; } .dayai-tone-indigo { --accent:#6366f1; }
        .dayai-kpi__label { color:#64748b; font-size:13px; font-weight:800; } .dark .dayai-kpi__label { color:#9ca3af; }
        .dayai-kpi__value { margin-top:16px; color:#0f172a; font-size:32px; line-height:1; font-weight:900; letter-spacing:-.04em; } .dark .dayai-kpi__value { color:#fff; }
        .dayai-kpi__hint { margin-top:12px; color:#64748b; font-size:13px; } .dark .dayai-kpi__hint { color:#aab3c2; }
        .dayai-section { padding: 20px; }
        .dayai-section__head { display:flex; align-items:start; justify-content:space-between; gap:16px; margin-bottom:16px; }
        .dayai-section__title { margin:0; color:#0f172a; font-size:17px; font-weight:900; letter-spacing:-.02em; } .dark .dayai-section__title { color:#fff; }
        .dayai-section__desc { margin:6px 0 0; color:#64748b; font-size:13px; line-height:1.6; } .dark .dayai-section__desc { color:#9ca3af; }
        .dayai-pill { display:inline-flex; border-radius:999px; padding:6px 10px; background:rgba(0,174,239,.12); color:#0076bd; font-size:11px; font-weight:850; text-transform:uppercase; letter-spacing:.08em; white-space:nowrap; } .dark .dayai-pill { background:rgba(0,174,239,.18); color:#7ddcff; }
        .dayai-report-card { display:flex; flex-direction:column; justify-content:space-between; min-height:220px; padding:20px; }
        .dayai-report-card__top { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; }
        .dayai-report-card__title { margin:0; color:#0f172a; font-size:18px; font-weight:900; letter-spacing:-.02em; } .dark .dayai-report-card__title { color:#fff; }
        .dayai-report-card__desc { margin:10px 0 0; color:#64748b; font-size:13px; line-height:1.65; } .dark .dayai-report-card__desc { color:#aab3c2; }
        .dayai-row-badge { border-radius:999px; padding:6px 10px; background:#eff6ff; color:#003a99; font-size:12px; font-weight:850; white-space:nowrap; } .dark .dayai-row-badge { background:rgba(0,174,239,.16); color:#7ddcff; }
        .dayai-actions { display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; margin-top:20px; }
        .dayai-export { display:inline-flex; align-items:center; justify-content:center; border-radius:999px; padding:10px 14px; background:#003a99; color:#fff !important; font-size:13px; font-weight:850; text-decoration:none; box-shadow:0 12px 28px rgba(0,58,153,.22); }
        .dayai-export:hover { background:#002e7a; }
        .dayai-slug { color:#64748b; font-size:12px; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; } .dark .dayai-slug { color:#9ca3af; }
        .dayai-table-wrap { overflow-x:auto; }
        .dayai-table { width:100%; border-collapse:separate; border-spacing:0; font-size:13px; }
        .dayai-table th { padding:12px 16px; background:#f8fafc; color:#64748b; border-bottom:1px solid rgba(148,163,184,.18); font-size:11px; font-weight:900; letter-spacing:.08em; text-transform:uppercase; text-align:left; } .dark .dayai-table th { background:rgba(255,255,255,.045); color:#aab3c2; border-color:rgba(255,255,255,.08); }
        .dayai-table td { padding:14px 16px; color:#334155; border-bottom:1px solid rgba(148,163,184,.14); vertical-align:middle; } .dark .dayai-table td { color:#d1d5db; border-color:rgba(255,255,255,.07); }
        .dayai-table tr:last-child td { border-bottom:0; } .dayai-strong { color:#0f172a; font-weight:850; } .dark .dayai-strong { color:#fff; } .dayai-right { text-align:right !important; }
        @media (max-width:1100px){ .dayai-grid--4,.dayai-grid--2{grid-template-columns:repeat(2,minmax(0,1fr));} }
        @media (max-width:760px){ .dayai-grid--4,.dayai-grid--2{grid-template-columns:1fr;} .dayai-bi-hero{padding:22px;} .dayai-section__head{display:block;} .dayai-pill{margin-top:10px;} }
    </style>

    <div class="dayai-bi">
        <section class="dayai-bi-hero">
            <div class="dayai-bi-hero__row">
                <div>
                    <p class="dayai-eyebrow">DAYAI BI Export Hub</p>
                    <h1 class="dayai-title">BI Reports & Export</h1>
                    <p class="dayai-subtitle">Kho báo cáo CSV phục vụ đối soát số liệu, dựng dashboard BI chuyên sâu và bàn giao dữ liệu cho team vận hành, tài chính, tuyển sinh.</p>
                </div>
                <div class="dayai-hero-stat"><strong>{{ number_format($totalRows) }}</strong><span>Dòng dữ liệu sẵn sàng export</span></div>
            </div>
        </section>

        <section class="dayai-grid dayai-grid--4">
            <article class="dayai-card dayai-kpi dayai-tone-blue"><div class="dayai-kpi__label">Bộ báo cáo</div><div class="dayai-kpi__value">{{ number_format(count($reports)) }}</div><div class="dayai-kpi__hint">Tuyển sinh, doanh thu, lớp học, công nợ</div></article>
            <article class="dayai-card dayai-kpi dayai-tone-green"><div class="dayai-kpi__label">Có dữ liệu</div><div class="dayai-kpi__value">{{ number_format($availableReports) }}</div><div class="dayai-kpi__hint">Báo cáo có ít nhất 1 dòng</div></article>
            <article class="dayai-card dayai-kpi dayai-tone-gold"><div class="dayai-kpi__label">Tổng dòng</div><div class="dayai-kpi__value">{{ number_format($totalRows) }}</div><div class="dayai-kpi__hint">Tổng số dòng xuất CSV</div></article>
            <article class="dayai-card dayai-kpi dayai-tone-indigo"><div class="dayai-kpi__label">Định dạng</div><div class="dayai-kpi__value">CSV</div><div class="dayai-kpi__hint">Mở được bằng Excel / Google Sheets</div></article>
        </section>

        <section class="dayai-grid dayai-grid--2">
            @foreach ($reports as $report)
                <article class="dayai-card dayai-report-card">
                    <div>
                        <div class="dayai-report-card__top">
                            <div>
                                <h2 class="dayai-report-card__title">{{ $report['name'] }}</h2>
                                <p class="dayai-report-card__desc">{{ $report['description'] }}</p>
                            </div>
                            <span class="dayai-row-badge">{{ number_format($report['rows']) }} dòng</span>
                        </div>
                    </div>
                    <div class="dayai-actions">
                        <span class="dayai-slug">/{{ $report['slug'] }}.csv</span>
                        <a class="dayai-export" href="/api/reports/{{ $report['slug'] }}.csv" target="_blank">Export CSV</a>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="dayai-card dayai-section">
            <div class="dayai-section__head">
                <div><h2 class="dayai-section__title">Danh mục báo cáo BI</h2><p class="dayai-section__desc">Bảng tổng hợp để kiểm tra nhanh số dòng, endpoint và mục đích sử dụng từng báo cáo.</p></div>
                <span class="dayai-pill">Report registry</span>
            </div>
            <div class="dayai-table-wrap">
                <table class="dayai-table">
                    <thead><tr><th>Báo cáo</th><th>Mục đích</th><th>Endpoint</th><th class="dayai-right">Số dòng</th><th class="dayai-right">Thao tác</th></tr></thead>
                    <tbody>
                        @foreach ($reports as $report)
                            <tr>
                                <td class="dayai-strong">{{ $report['name'] }}</td>
                                <td>{{ $report['description'] }}</td>
                                <td><span class="dayai-slug">/api/reports/{{ $report['slug'] }}.csv</span></td>
                                <td class="dayai-right dayai-strong">{{ number_format($report['rows']) }}</td>
                                <td class="dayai-right"><a class="dayai-export" href="/api/reports/{{ $report['slug'] }}.csv" target="_blank">Tải CSV</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-filament-panels::page>
