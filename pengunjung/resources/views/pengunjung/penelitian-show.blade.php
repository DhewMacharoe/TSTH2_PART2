<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $item->title }} - Riset TSTH2</title>
    @include('partials.pwa-head')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        body { background: #f8faf9; }
        .detail-hero {
            background: linear-gradient(135deg, #0d342a 0%, #164E3F 55%, #1c5e4d 100%);
            padding: 5.5rem 1rem 3.5rem 1rem;
            position: relative; overflow: hidden; color: #ffffff !important;
        }
        .detail-hero h1,
        .detail-hero h2,
        .detail-hero h3,
        .detail-hero p,
        .detail-hero span,
        .detail-hero a {
            color: #ffffff;
        }
        .detail-hero h1 {
            color: #ffffff !important;
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.4);
        }
        .detail-hero::before { content:''; position:absolute; top:-80px;right:-80px; width:340px;height:340px;background:rgba(255,255,255,0.04);border-radius:50%; }
        .dc, .detail-container { max-width: 1280px; margin: 0 auto; padding: 0 2rem; }
        .detail-grid { display: grid; grid-template-columns: 1fr 350px; gap: 2.25rem; padding: 2.5rem 0 5rem 0; }
        .detail-main, .detail-sidebar { min-width: 0; }
        @media(max-width: 960px){ .detail-grid{ grid-template-columns: 1fr; } }
        @media(max-width: 640px){ .dc, .detail-container { padding: 0 1rem; } }
        .ds { background:#fff; border:1px solid #e2e8f0; border-radius:1.5rem; padding:2rem; margin-bottom:1.5rem; box-shadow:0 2px 12px rgba(15,23,42,.03); }
        .st { font-size:1.05rem;font-weight:800;color:#0f172a;margin:0 0 1.25rem 0;display:flex;align-items:center;gap:.6rem;padding-bottom:.85rem;border-bottom:2px solid #f1f5f9; }
        .si { width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:#e8f5e9;color:#164E3F;flex-shrink:0; }
        /* Metrics */
        .mg { display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem; }
        .mc { background:#f8faf9;border:1px solid #e2e8f0;border-radius:1rem;padding:1.25rem;transition:border-color .2s,transform .2s; }
        .mc:hover { border-color:#164E3F;transform:translateY(-2px); }
        .mv { font-size:1.35rem;font-weight:800;color:#0f172a;line-height:1.2;margin-bottom:.2rem; }
        .ml { font-size:.78rem;font-weight:700;color:#475569;margin-bottom:.3rem; }
        .ms { font-size:.71rem;color:#94a3b8;line-height:1.4; }
        /* Roadmap */
        .rt { position:relative;padding-left:2rem;border-left:2px solid #e2e8f0;margin-left:.5rem; }
        .rs { position:relative;margin-bottom:1.75rem; }
        .rs:last-child{margin-bottom:0;}
        .rd { position:absolute;left:-2.65rem;top:.2rem;width:1.2rem;height:1.2rem;border-radius:50%;border:3px solid #164E3F;background:#fff; }
        .rd.completed{background:#164E3F;border-color:#164E3F;}
        .rd.active{background:#eab308;border-color:#ca8a04;box-shadow:0 0 0 4px rgba(234,179,8,.2);}
        .rd.planned{background:#fff;border-color:#cbd5e1;}
        /* Params table */
        .pt { width:100%;border-collapse:separate;border-spacing:0;background:#fff;border:1px solid #e2e8f0;border-radius:1rem;overflow:hidden;font-size:.88rem; }
        .pt tr:not(:last-child) td{border-bottom:1px solid #f1f5f9;}
        .pt td{padding:.95rem 1.25rem;}
        .pk{font-weight:700;color:#334155;width:38%;background:#f8faf9;}
        .pv{color:#0f172a;font-weight:600;}
        /* Benchmark bars */
        .bc{background:#f8faf9;border:1px solid #e2e8f0;border-radius:1rem;padding:1.25rem;margin-bottom:1rem;}
        .bc:last-child{margin-bottom:0;}
        .bt{font-size:.9rem;font-weight:800;color:#0f172a;}
        .bn{font-size:.72rem;font-weight:700;color:#166534;background:#dcfce7;padding:.2rem .65rem;border-radius:999px;}
        .btr{height:10px;background:#f1f5f9;border-radius:999px;overflow:hidden;margin:.35rem 0 .65rem 0;}
        .bf1{height:100%;background:linear-gradient(90deg,#164E3F,#22c55e);border-radius:999px;}
        .bf2{height:100%;background:#94a3b8;border-radius:999px;}
        .bl{display:flex;justify-content:space-between;font-size:.78rem;font-weight:700;color:#475569;margin-bottom:.25rem;}
        /* Sidebar */
        .sc{background:#fff;border:1px solid #e2e8f0;border-radius:1.25rem;padding:1.5rem;margin-bottom:1.25rem;box-shadow:0 2px 10px rgba(0,0,0,.03);}
        .ir{display:flex;align-items:flex-start;gap:.75rem;padding:.65rem 0;border-bottom:1px solid #f1f5f9;font-size:.85rem;}
        .ir:last-child{border-bottom:none;padding-bottom:0;}
        .il{font-weight:700;color:#334155;min-width:85px;flex-shrink:0;}
        .iv{color:#475569;font-weight:600;}
        .rc{display:flex;gap:.85rem;padding:.85rem;border-radius:1rem;border:1px solid #e2e8f0;text-decoration:none;color:inherit;transition:border-color .2s,background .2s;margin-bottom:.75rem;}
        .rc:hover{border-color:#164E3F;background:#f8faf9;}
        .rc:last-child{margin-bottom:0;}
        .rth{width:60px;height:60px;border-radius:.75rem;object-fit:cover;flex-shrink:0;}
        .rtp{width:60px;height:60px;border-radius:.75rem;background:#f1f5f9;flex-shrink:0;display:flex;align-items:center;justify-content:center;}

        /* Dossier Pagination & Tabs */
        .dossier-nav-wrapper {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 0.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.04);
            position: sticky;
            top: 80px;
            z-index: 20;
        }
        .dossier-nav-bar {
            display: flex;
            gap: 0.5rem;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .dossier-nav-bar::-webkit-scrollbar { display: none; }
        .dossier-tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.15rem;
            border-radius: 0.85rem;
            font-size: 0.85rem;
            font-weight: 700;
            color: #475569;
            background: transparent;
            border: none;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
        }
        .dossier-tab-btn:hover {
            color: #164E3F;
            background: #f0fdf4;
        }
        .dossier-tab-btn.active {
            color: #ffffff;
            background: #164E3F;
            box-shadow: 0 4px 12px rgba(22, 78, 63, 0.25);
        }
        .dossier-tab-icon {
            font-size: 1.15rem;
        }
        .dossier-tab-badge {
            font-size: 0.7rem;
            padding: 0.15rem 0.5rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.25);
            color: inherit;
            font-weight: 800;
        }
        .dossier-tab-btn:not(.active) .dossier-tab-badge {
            background: #f1f5f9;
            color: #64748b;
        }
        .dossier-pane {
            display: none;
            animation: fadeInDossier 0.25s ease-out;
        }
        .dossier-pane.active {
            display: block;
        }
        @keyframes fadeInDossier {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .dossier-pagination-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 0.85rem 1.25rem;
            margin-top: 1.5rem;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
        }
        .dossier-page-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.6rem 1.25rem;
            border-radius: 0.75rem;
            font-size: 0.85rem;
            font-weight: 700;
            font-family: inherit;
            color: #164E3F;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            cursor: pointer;
            transition: all 0.2s;
        }
        .dossier-page-btn:hover:not(:disabled) {
            background: #164E3F;
            color: #ffffff;
            border-color: #164E3F;
        }
        .dossier-page-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            background: #f1f5f9;
            border-color: #e2e8f0;
            color: #94a3b8;
        }
        .dossier-page-indicator {
            font-size: 0.82rem;
            font-weight: 700;
            color: #64748b;
        }
        .dossier-page-indicator strong, #currentDossierNum {
            color: #164E3F;
            font-weight: 800;
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    @php
        $isEn     = app()->getLocale() === 'en';
        $rawText  = $item->description ?? '';
        $cleanTxt = str_replace(["\r\n","\r","rn\n","rn"], "\n\n", $rawText);

        $peneliti = $item->peneliti ?? null;
        $tujuan   = $item->tujuan   ?? null;

        if (!$peneliti && preg_match('/Peneliti Utama:\s*(.+)$/m', $cleanTxt, $pM)) {
            $peneliti = trim($pM[1]);
            $cleanTxt = trim(str_replace($pM[0], '', $cleanTxt));
        }
        if (!$tujuan && preg_match('/Tujuan:\s*(.+?)(?=(Peneliti Utama:|$))/s', $cleanTxt, $tM)) {
            $tujuan   = trim($tM[1]);
            $cleanTxt = trim(str_replace('Tujuan:'.$tM[1], '', $cleanTxt));
            $cleanTxt = trim(str_replace('Tujuan:', '', $cleanTxt));
        }

        $code       = $item->code        ?? null;
        $field      = $item->field       ?? null;
        $status     = $item->status_riset ?? null;
        $facility   = $item->facility    ?? null;
        $location   = $item->location    ?? null;
        $metrics    = (is_array($item->metrics)    && count($item->metrics)    > 0) ? $item->metrics    : [];
        $benchmarks = (is_array($item->benchmarks) && count($item->benchmarks) > 0) ? $item->benchmarks : [];
        $params     = (is_array($item->params)     && count($item->params)     > 0) ? $item->params     : [];
        $roadmap    = (is_array($item->roadmap)    && count($item->roadmap)    > 0) ? $item->roadmap    : [];

        $dossierTabs = [];
        if (!empty($cleanTxt) || $tujuan || $item->image_path) {
            $dossierTabs[] = ['id' => 'uraian', 'title' => 'Uraian Riset', 'icon' => 'description'];
        }
        if (count($metrics) > 0) {
            $dossierTabs[] = ['id' => 'metrics', 'title' => 'Metrik & Capaian', 'icon' => 'analytics', 'badge' => count($metrics)];
        }
        if (count($roadmap) > 0) {
            $dossierTabs[] = ['id' => 'roadmap', 'title' => 'Roadmap & Fase', 'icon' => 'route', 'badge' => count($roadmap)];
        }
        if (count($params) > 0) {
            $dossierTabs[] = ['id' => 'params', 'title' => 'Parameter Teknis', 'icon' => 'tune', 'badge' => count($params)];
        }
        if (count($benchmarks) > 0) {
            $dossierTabs[] = ['id' => 'benchmarks', 'title' => 'Komparasi Lapangan', 'icon' => 'balance', 'badge' => count($benchmarks)];
        }
    @endphp

    <main style="padding-top: 110px;">
        {{-- Hero --}}
        @php
            $heroBg = isset($konten['penelitian_hero_bg']) && $konten['penelitian_hero_bg']->image_path
                ? 'background: linear-gradient(135deg, rgba(13, 52, 42, 0.88) 0%, rgba(22, 78, 63, 0.88) 100%), url(' . asset('storage/' . $konten['penelitian_hero_bg']->image_path) . ') center/cover no-repeat;'
                : 'background: linear-gradient(135deg, #0d342a 0%, #164E3F 50%, #1c5e4d 100%);';
        @endphp
        <div class="detail-hero" style="{{ $heroBg }}">
            <div class="detail-container" style="position:relative;z-index:2;">
                <a href="{{ route('penelitian') }}"
                   style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);color:white;font-size:.78rem;font-weight:700;padding:.4rem 1rem;border-radius:999px;text-decoration:none;margin-bottom:1.75rem;backdrop-filter:blur(8px);">
                    <span class="material-symbols-rounded" style="font-size:16px;">arrow_back</span>
                    Kembali ke Daftar Riset
                </a>

                @if($field)
                <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.16);padding:.35rem .95rem;border-radius:999px;margin-bottom:1rem;border:1px solid rgba(255,255,255,.28);color:#ffffff !important;">
                    <span class="material-symbols-rounded" style="font-size:16px;color:#a7f3d0 !important;">biotech</span>
                    <span style="font-size:.75rem;font-weight:800;letter-spacing:.5px;text-transform:uppercase;color:#ffffff !important;">{{ $field }}</span>
                </div>
                @endif

                <h1 style="font-size:clamp(1.85rem,4vw,3rem);font-weight:800;line-height:1.2;margin:0 0 1.25rem 0;max-width:860px;color:#ffffff !important;text-shadow:0 2px 14px rgba(0,0,0,0.45);font-family:var(--font-heading, sans-serif);">
                    {{ $item->title }}
                </h1>

                <div style="display:flex;flex-wrap:wrap;gap:.65rem;">
                    @if($status)
                    <span style="background:rgba(34,197,94,.25);border:1px solid rgba(34,197,94,.4);color:#bbf7d0 !important;font-size:.76rem;font-weight:700;padding:.3rem .8rem;border-radius:999px;display:inline-flex;align-items:center;gap:5px;">
                        <span class="material-symbols-rounded" style="font-size:14px;color:#bbf7d0 !important;">bolt</span>{{ $status }}
                    </span>
                    @endif
                    @if($location)
                    <span style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);color:#ffffff !important;font-size:.76rem;font-weight:600;padding:.3rem .8rem;border-radius:999px;display:inline-flex;align-items:center;gap:5px;">
                        <span class="material-symbols-rounded" style="font-size:14px;color:#ffffff !important;">location_on</span>{{ $location }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="detail-container">
            <div class="detail-grid">

                {{-- Main Column --}}
                <div class="detail-main">

                    {{-- Featured Image (Persistent so it does not shift or jump during tab switches) --}}
                    @if($item->image_path)
                    <div style="margin-bottom:1.5rem;border-radius:1.5rem;overflow:hidden;box-shadow:0 8px 32px rgba(15,23,42,.08);border:1px solid #e2e8f0;">
                        <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->title }}"
                             style="width:100%;max-height:440px;object-fit:cover;display:block;">
                    </div>
                    @endif

                    {{-- Dossier Tab Bar (Pagination Steps) --}}
                    @if(count($dossierTabs) > 1)
                    <div class="dossier-nav-wrapper">
                        <div class="dossier-nav-bar" id="dossierNavBar">
                            @foreach($dossierTabs as $index => $tab)
                            <button type="button" class="dossier-tab-btn {{ $index === 0 ? 'active' : '' }}" data-target="{{ $tab['id'] }}" data-index="{{ $index }}">
                                <span class="material-symbols-rounded dossier-tab-icon">{{ $tab['icon'] }}</span>
                                <span class="dossier-tab-label">{{ $tab['title'] }}</span>
                                @if(isset($tab['badge']))
                                    <span class="dossier-tab-badge">{{ $tab['badge'] }}</span>
                                @endif
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- PANE 1: Uraian Riset --}}
                    @if(!empty($cleanTxt) || $tujuan)
                    <div class="dossier-pane active" id="pane-uraian">
                        <div class="ds">
                            <h2 class="st">
                                <div class="si"><span class="material-symbols-rounded" style="font-size:18px;">description</span></div>
                                Uraian Riset
                            </h2>
                            @if($tujuan)
                            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:1rem;padding:1rem 1.25rem;margin-bottom:1.25rem;">
                                <p style="font-size:.82rem;font-weight:800;color:#166534;text-transform:uppercase;letter-spacing:.4px;margin-bottom:.35rem;">Tujuan Riset</p>
                                <p style="font-size:.95rem;color:#166534;font-weight:600;line-height:1.6;margin:0;">{{ $tujuan }}</p>
                            </div>
                            @endif
                            <div style="color:#334155;font-size:1.02rem;line-height:1.85;white-space:pre-wrap;">{{ $cleanTxt }}</div>
                        </div>
                    </div>
                    @endif

                    {{-- PANE 2: Metrics --}}
                    @if(count($metrics)>0)
                    <div class="dossier-pane" id="pane-metrics">
                        <div class="ds">
                            <h2 class="st">
                                <div class="si" style="background:#f0fdf4;color:#166534;"><span class="material-symbols-rounded" style="font-size:18px;">analytics</span></div>
                                Metrik &amp; Capaian
                            </h2>
                            <div class="mg">
                                @foreach($metrics as $m)
                                <div class="mc">
                                    @if(!empty($m['badge']))
                                    <span style="font-size:.66rem;font-weight:800;padding:.2rem .55rem;border-radius:999px;display:inline-block;margin-bottom:.6rem;color:{{ $m['badge_color'] ?? '#166534' }};background:{{ $m['badge_bg'] ?? '#dcfce7' }};">{{ $m['badge'] }}</span>
                                    @endif
                                    <div class="mv">{{ $m['val'] ?? '' }}</div>
                                    <div class="ml">{{ $m['label'] ?? '' }}</div>
                                    @if(!empty($m['sub']))<div class="ms">{{ $m['sub'] }}</div>@endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- PANE 3: Roadmap --}}
                    @if(count($roadmap)>0)
                    <div class="dossier-pane" id="pane-roadmap">
                        <div class="ds">
                            <h2 class="st">
                                <div class="si" style="background:#eff6ff;color:#1d4ed8;"><span class="material-symbols-rounded" style="font-size:18px;">route</span></div>
                                Roadmap &amp; Fase Riset
                            </h2>
                            <div class="rt">
                                @foreach($roadmap as $step)
                                <div class="rs">
                                    <div class="rd {{ $step['status'] ?? 'planned' }}"></div>
                                    @if(!empty($step['pct']))<div style="font-size:.68rem;font-weight:800;color:#64748b;margin-bottom:.3rem;">{{ $step['pct'] }}</div>@endif
                                    <div style="font-size:.92rem;font-weight:800;color:#0f172a;margin-bottom:.25rem;">{{ $step['title'] ?? '' }}</div>
                                    @if(!empty($step['desc']))<div style="font-size:.83rem;color:#64748b;line-height:1.5;">{{ $step['desc'] }}</div>@endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- PANE 4: Parameters --}}
                    @if(count($params)>0)
                    <div class="dossier-pane" id="pane-params">
                        <div class="ds">
                            <h2 class="st">
                                <div class="si" style="background:#faf5ff;color:#7c3aed;"><span class="material-symbols-rounded" style="font-size:18px;">tune</span></div>
                                Parameter Teknis &amp; Agroklimat
                            </h2>
                            <table class="pt">
                                @foreach($params as $p)
                                <tr><td class="pk">{{ $p['key'] ?? '' }}</td><td class="pv">{{ $p['val'] ?? '' }}</td></tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    @endif

                    {{-- PANE 5: Benchmarks --}}
                    @if(count($benchmarks)>0)
                    <div class="dossier-pane" id="pane-benchmarks">
                        <div class="ds">
                            <h2 class="st">
                                <div class="si" style="background:#fffbeb;color:#d97706;"><span class="material-symbols-rounded" style="font-size:18px;">balance</span></div>
                                Komparasi Lapangan
                            </h2>
                            @foreach($benchmarks as $b)
                            <div class="bc">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.85rem;">
                                    <div class="bt">{{ $b['title'] ?? '' }}</div>
                                    @if(!empty($b['note']))<span class="bn">{{ $b['note'] }}</span>@endif
                                </div>
                                <div class="bl"><span style="color:#166534;font-weight:800;">TSTH2</span><span style="color:#166534;">{{ $b['tsth2_val'] ?? '' }}</span></div>
                                <div class="btr"><div class="bf1" style="width:{{ $b['tsth2_pct'] ?? 0 }}%;"></div></div>
                                <div class="bl" style="margin-top:.5rem;"><span style="color:#64748b;">Konvensional</span><span style="color:#64748b;">{{ $b['conv_val'] ?? '' }}</span></div>
                                <div class="btr"><div class="bf2" style="width:{{ $b['conv_pct'] ?? 0 }}%;"></div></div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Pagination Controls Footer --}}
                    @if(count($dossierTabs) > 1)
                    <div class="dossier-pagination-footer">
                        <button type="button" id="prevDossierBtn" class="dossier-page-btn" disabled>
                            <span class="material-symbols-rounded" style="font-size:18px;">chevron_left</span>
                            <span>Sebelumnya</span>
                        </button>
                        <div class="dossier-page-indicator">
                            Dossier <strong id="currentDossierNum">1</strong> dari <span id="totalDossierNum">{{ count($dossierTabs) }}</span>
                        </div>
                        <button type="button" id="nextDossierBtn" class="dossier-page-btn">
                            <span>Selanjutnya</span>
                            <span class="material-symbols-rounded" style="font-size:18px;">chevron_right</span>
                        </button>
                    </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="detail-sidebar">
                    <div class="sc">
                        <h3 style="font-size:.9rem;font-weight:800;color:#0f172a;margin:0 0 1rem 0;display:flex;align-items:center;gap:.5rem;">
                            <span class="material-symbols-rounded" style="font-size:18px;color:#164E3F;">info</span>
                            Informasi Riset
                        </h3>
                        @if($field)<div class="ir"><span class="il">Bidang</span><span class="iv">{{ $field }}</span></div>@endif
                        @if($status)<div class="ir"><span class="il">Status</span><span class="iv" style="color:#166534;">{{ $status }}</span></div>@endif
                        @if($peneliti)<div class="ir"><span class="il">Peneliti</span><span class="iv">{{ $peneliti }}</span></div>@endif
                        @if($facility)<div class="ir"><span class="il">Fasilitas</span><span class="iv">{{ $facility }}</span></div>@endif
                        @if($location)<div class="ir"><span class="il">Lokasi</span><span class="iv">{{ $location }}</span></div>@endif
                    </div>

                    <a href="{{ route('penelitian') }}"
                       style="display:flex;align-items:center;justify-content:center;gap:6px;background:#164E3F;color:white;font-weight:800;font-size:.88rem;padding:.85rem 1.5rem;border-radius:1rem;text-decoration:none;text-align:center;margin-bottom:1.25rem;box-shadow:0 4px 14px rgba(22,78,63,.25);">
                        <span class="material-symbols-rounded" style="font-size:18px;">arrow_back</span>
                        Kembali ke Semua Riset
                    </a>

                    @if($terkait->count()>0)
                    <div class="sc">
                        <h3 style="font-size:.9rem;font-weight:800;color:#0f172a;margin:0 0 1rem 0;display:flex;align-items:center;gap:.5rem;">
                            <span class="material-symbols-rounded" style="font-size:18px;color:#164E3F;">science</span>
                            Riset Terkait
                        </h3>
                        @foreach($terkait as $r)
                        <a href="{{ route('penelitian.show', $r->id) }}" class="rc">
                            @if($r->image_path)
                            <img src="{{ asset('storage/'.$r->image_path) }}" alt="{{ $r->title }}" class="rth">
                            @else
                            <div class="rtp"><span class="material-symbols-rounded" style="font-size:22px;color:#cbd5e1;">science</span></div>
                            @endif
                            <div>
                                <p style="font-size:.84rem;font-weight:700;color:#0f172a;margin:0 0 .25rem 0;line-height:1.3;">{{ Str::limit($r->title,55) }}</p>
                                @if($r->field)<span style="font-size:.71rem;font-weight:700;color:#164E3F;background:#e8f5e9;padding:.15rem .5rem;border-radius:999px;">{{ Str::limit($r->field,30) }}</span>@endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.dossier-tab-btn');
            const panes = document.querySelectorAll('.dossier-pane');
            const prevBtn = document.getElementById('prevDossierBtn');
            const nextBtn = document.getElementById('nextDossierBtn');
            const currentNum = document.getElementById('currentDossierNum');
            let currentIndex = 0;

            function setDossierTab(index) {
                if (index < 0 || index >= tabs.length) return;
                currentIndex = index;

                tabs.forEach((tab, i) => {
                    if (i === currentIndex) {
                        tab.classList.add('active');
                        const navBar = document.getElementById('dossierNavBar');
                        if (navBar) {
                            const scrollLeft = tab.offsetLeft - (navBar.clientWidth / 2) + (tab.clientWidth / 2);
                            navBar.scrollTo({ left: scrollLeft, behavior: 'smooth' });
                        }
                    } else {
                        tab.classList.remove('active');
                    }
                });

                const targetId = tabs[currentIndex].getAttribute('data-target');
                panes.forEach(pane => {
                    if (pane.id === 'pane-' + targetId) {
                        pane.classList.add('active');
                    } else {
                        pane.classList.remove('active');
                    }
                });

                if (currentNum) currentNum.textContent = (currentIndex + 1);
                if (prevBtn) prevBtn.disabled = (currentIndex === 0);
                if (nextBtn) nextBtn.disabled = (currentIndex === tabs.length - 1);
            }

            tabs.forEach((tab, idx) => {
                tab.addEventListener('click', () => {
                    setDossierTab(idx);
                });
            });

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    if (currentIndex > 0) setDossierTab(currentIndex - 1);
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    if (currentIndex < tabs.length - 1) setDossierTab(currentIndex + 1);
                });
            }
        });
    </script>
</body>
</html>

