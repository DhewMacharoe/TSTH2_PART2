<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ __('site.hero_research_title') }} - TSTH2</title>
    @include('partials.pwa-head')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ time() }}">
    <!-- Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }

        /* ── Research Registry List ── */
        .research-registry {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .research-entry {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            display: grid;
            grid-template-columns: 200px 1fr;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
            transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            position: relative;
        }

        .research-entry:hover {
            border-color: rgba(22, 78, 63, 0.35);
            box-shadow: 0 12px 32px -4px rgba(22, 78, 63, 0.12);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .research-entry {
                grid-template-columns: 1fr;
            }
            .research-entry-thumb {
                height: 200px !important;
            }
        }

        /* Kolom kiri: thumbnail */
        .research-entry-thumb {
            position: relative;
            background: #0f172a;
            overflow: hidden;
        }

        .research-entry-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.9;
            transition: opacity 0.3s ease, transform 0.4s ease;
        }

        .research-entry:hover .research-entry-thumb img {
            opacity: 1;
            transform: scale(1.06);
        }

        .thumb-label {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 0.6rem 0.75rem;
            background: linear-gradient(to top, rgba(14, 52, 42, 0.95), transparent);
            color: #ffffff;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Kolom kanan: konten entry */
        .research-entry-content {
            padding: 1.5rem 1.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .entry-top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .research-field-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.72rem;
            font-weight: 800;
            color: #164E3F;
            background: #e8f5e9;
            padding: 0.25rem 0.7rem;
            border-radius: 999px;
            letter-spacing: 0.3px;
        }

        .research-code-badge {
            font-size: 0.7rem;
            font-weight: 700;
            color: #64748b;
            background: #f1f5f9;
            padding: 0.2rem 0.55rem;
            border-radius: 6px;
            font-family: monospace;
        }

        .research-entry-title {
            font-size: 1.22rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.4;
            margin: 0;
            font-family: var(--font-heading, sans-serif);
        }

        /* Highlights pill metrics on the card */
        .card-metrics-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin: 0.35rem 0;
        }

        .metric-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #164E3F;
            background: rgba(22, 78, 63, 0.07);
            border: 1px solid rgba(22, 78, 63, 0.15);
            padding: 0.3rem 0.65rem;
            border-radius: 8px;
        }

        .metric-pill strong {
            color: #0d342a;
            font-weight: 800;
        }

        .research-meta-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem 1.5rem;
        }

        .research-meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8rem;
            color: #64748b;
        }

        .research-meta-item strong {
            color: #334155;
            font-weight: 700;
        }

        .research-abstract {
            font-size: 0.9rem;
            color: #475569;
            line-height: 1.65;
            margin: 0.25rem 0 0 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .research-entry-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
            padding-top: 0.75rem;
            border-top: 1px dashed #e2e8f0;
        }

        .open-dossier-btn {
            font-size: 0.85rem;
            font-weight: 800;
            color: #164E3F;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .research-entry:hover .open-dossier-btn {
            gap: 10px;
            color: #0d342a;
        }

        /* ── Modal Dossier & Interactive Tabs ── */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

        .dossier-tab-btn {
            padding: 0.65rem 1.15rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f1f5f9;
            color: #64748b;
            white-space: nowrap;
        }

        .dossier-tab-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .dossier-tab-btn.active {
            background: #164E3F;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(22, 78, 63, 0.25);
        }

        /* Stat Grid in Tab 1 */
        .scientific-stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .scientific-stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            transition: transform 0.2s ease, border-color 0.2s ease;
        }

        .scientific-stat-card:hover {
            border-color: #164E3F;
            transform: translateY(-2px);
        }

        .stat-card-badge {
            font-size: 0.68rem;
            font-weight: 800;
            padding: 0.2rem 0.5rem;
            border-radius: 999px;
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        .stat-card-value {
            font-size: 1.45rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
            margin-bottom: 0.25rem;
            font-family: var(--font-heading, sans-serif);
        }

        .stat-card-label {
            font-size: 0.78rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.35rem;
        }

        .stat-card-sub {
            font-size: 0.72rem;
            color: #94a3b8;
            line-height: 1.4;
        }

        /* Comparison Benchmarks in Tab 2 */
        .benchmark-row {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
        }

        .benchmark-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.85rem;
        }

        .benchmark-bar-track {
            height: 10px;
            background: #f1f5f9;
            border-radius: 999px;
            overflow: hidden;
            position: relative;
            margin: 0.35rem 0 0.85rem 0;
        }

        .benchmark-bar-fill-tsth2 {
            height: 100%;
            background: linear-gradient(90deg, #164E3F, #22c55e);
            border-radius: 999px;
            transition: width 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .benchmark-bar-fill-conv {
            height: 100%;
            background: #94a3b8;
            border-radius: 999px;
            transition: width 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Timeline in Tab 4 */
        .roadmap-timeline {
            position: relative;
            padding-left: 1.75rem;
            border-left: 2px solid #e2e8f0;
            margin-left: 0.5rem;
        }

        .roadmap-item {
            position: relative;
            margin-bottom: 2rem;
        }

        .roadmap-item:last-child {
            margin-bottom: 0;
        }

        .roadmap-dot {
            position: absolute;
            left: -2.35rem;
            top: 0.2rem;
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 50%;
            background: #ffffff;
            border: 3px solid #164E3F;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .roadmap-dot.completed {
            background: #164E3F;
            border-color: #164E3F;
        }

        .roadmap-dot.active {
            background: #eab308;
            border-color: #ca8a04;
            box-shadow: 0 0 0 4px rgba(234, 179, 8, 0.2);
        }

        .roadmap-dot.planned {
            background: #ffffff;
            border-color: #cbd5e1;
        }

        /* Parameters Table in Tab 3 */
        .param-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            overflow: hidden;
        }

        .param-table tr:not(:last-child) td {
            border-bottom: 1px solid #f1f5f9;
        }

        .param-table td {
            padding: 1rem 1.25rem;
            font-size: 0.88rem;
        }

        .param-key {
            font-weight: 700;
            color: #334155;
            width: 38%;
            background: #f8faf9;
        }

        .param-val {
            color: #0f172a;
            font-weight: 600;
        }
    </style>
</head>
<body style="background: #f8faf9;">
    @include('partials.navbar')

    <main>
        <!-- Hero Section -->
        @php
            $heroBg = isset($konten['penelitian_hero_bg']) && $konten['penelitian_hero_bg']->image_path
                ? 'background: linear-gradient(135deg, rgba(13, 52, 42, 0.88) 0%, rgba(22, 78, 63, 0.88) 100%), url(' . asset('storage/' . $konten['penelitian_hero_bg']->image_path) . ') center/cover no-repeat;'
                : 'background: linear-gradient(135deg, #0d342a 0%, #164E3F 50%, #1c5e4d 100%);';
        @endphp
        <section style="{{ $heroBg }} padding: 5.5rem 1rem 4.5rem 1rem; text-align: center; color: white; position: relative; overflow: hidden;">
            <div class="container" style="position: relative; z-index: 2;">
                <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.12); padding: 0.4rem 1rem; border-radius: 999px; margin-bottom: 1.25rem; backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.2);">
                    <span class="material-symbols-rounded" style="font-size:18px;">science</span>
                    <span style="font-size: 0.78rem; font-weight: 800; letter-spacing: 0.6px; text-transform: uppercase;">
                        Aktivitas &amp; Fasilitas Riset
                    </span>
                </div>
                <h1 style="font-size: clamp(2.4rem, 4.5vw, 3.5rem); margin-bottom: 1rem; color: white; font-family: var(--font-heading, sans-serif); font-weight: 800;">
                    {{ $konten['penelitian_hero_title']->value ?? 'Riset & Laboratorium TSTH2' }}
                </h1>
                <p style="font-size: 1.15rem; max-width: 820px; margin: 0 auto; color: rgba(241,245,249,0.92); line-height: 1.7;">
                    {{ $konten['penelitian_hero_desc']->value ?? 'Infrastruktur dan fasilitas riset terpadu untuk mengembangkan potensi tanaman herbal dan hortikultura Indonesia.' }}
                </p>
            </div>
        </section>

        <!-- Registri Proyek Riset -->
        <section style="padding: 4rem 0;">
            <div class="container">
                <!-- Search & Filters -->
                <div style="max-width: 640px; margin: 0 auto 3rem auto;">
                    <form action="{{ route('penelitian') }}" method="GET" style="display: flex; gap: 0.5rem; background: #ffffff; padding: 0.5rem; border-radius: 999px; box-shadow: 0 10px 30px rgba(14, 52, 42, 0.08); border: 1px solid rgba(22, 78, 63, 0.12);">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="{{ __('site.res_search_placeholder') }}"
                               style="flex: 1; padding: 0.85rem 1.5rem; border: none; outline: none; background: transparent; font-size: 0.95rem; color: #0f172a;">
                        <button type="submit" class="btn btn-primary" style="background: #164E3F; border-radius: 999px; display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.85rem 1.5rem;">
                            <span>{{ __('site.search') }}</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21 21-4.3-4.3"/><circle cx="11" cy="11" r="8"/></svg>
                        </button>
                    </form>
                </div>


                <!-- Daftar Entri Riset (Research Registry) -->
                <div class="research-registry">
                    @forelse ($penelitianes as $item)
                        @php
                            $isEn = app()->getLocale() === 'en';
                            $rawText = $item->description ?? '';
                            $cleanText = preg_replace('/(?<=[a-z0-9\.\,\)])rn(?=[A-Z0-9])/i', "\n\n", $rawText);
                            $cleanText = str_replace(["\r\n", "\r", "rn\n", "rn"], "\n\n", $cleanText);

                            $peneliti = null;
                            if (preg_match('/Peneliti Utama:\s*(.+)$/m', $cleanText, $pM)) {
                                $peneliti = trim($pM[1]);
                                $cleanText = trim(str_replace($pM[0], '', $cleanText));
                            }

                            $tujuan = null;
                            if (preg_match('/Tujuan:\s*(.+?)(?=(Peneliti Utama:|$))/s', $cleanText, $tM)) {
                                $tujuan = trim($tM[1]);
                                $cleanText = trim(str_replace('Tujuan:' . $tM[1], '', $cleanText));
                                $cleanText = trim(str_replace('Tujuan:', '', $cleanText));
                            }

                            $title_lower = strtolower($item->title ?? '');
                            $desc_lower  = strtolower($cleanText);

                            // Detect Topic & Build Scientific Dossier
                            if (str_contains($title_lower, 'bawang') || str_contains($desc_lower, 'bawang') || str_contains($desc_lower, 'garlic')) {
                                $topic = 'bawang';
                                $code = 'TSTH2-RES-BWG-2026';
                                $field = $isEn ? 'Highland Seed Breeding & Horticulture' : 'Pemuliaan Benih & Hortikultura Dataran Tinggi';
                                $status = $isEn ? 'Phase 3: Field Adaptation & Harvest Evaluation' : 'Fase 3: Uji Adaptasi & Panen Evaluasi';
                                $statusClass = 'active';
                                $facility = $isEn ? 'Bioscience Lab & Experimental Plot Parsingguran II' : 'Lab Biosains & Kebun Percobaan Parsingguran II';
                                $location = 'Desa Parsingguran II, Pollung (1.400 mdpl)';

                                $metrics = [
                                    [
                                        'val' => '12.8 - 14.2 Ton/Ha',
                                        'label' => $isEn ? 'Projected Yield' : 'Potensi Produktivitas',
                                        'badge' => '+48% vs Petani Lokal',
                                        'badge_color' => '#166534',
                                        'badge_bg' => '#dcfce7',
                                        'sub' => $isEn ? 'Yield per hectare in highland volcanic soil' : 'Hasil panen per hektar di lahan andosol dataran tinggi'
                                    ],
                                    [
                                        'val' => '48.5 g / umbi',
                                        'label' => $isEn ? 'Average Bulb Weight' : 'Bobot Rata-rata Umbi',
                                        'badge' => 'Grade Super A',
                                        'badge_color' => '#1e40af',
                                        'badge_bg' => '#dbeafe',
                                        'sub' => $isEn ? 'Diameter 3.8 - 4.5 cm with uniform cloves' : 'Diameter 3.8 - 4.5 cm dengan siung bernas seragam'
                                    ],
                                    [
                                        'val' => '4.62 mg/g',
                                        'label' => $isEn ? 'Bioactive Allicin' : 'Kandungan Senyawa Alisin',
                                        'badge' => 'Standar Herbal Medis',
                                        'badge_color' => '#854d0e',
                                        'badge_bg' => '#fef9c3',
                                        'sub' => $isEn ? 'High concentration of active antioxidant allicin' : 'Kadar alisin tinggi untuk konsumsi & fitofarmaka'
                                    ],
                                    [
                                        'val' => '96.4%',
                                        'label' => $isEn ? 'Seed Germination Rate' : 'Daya Tumbuh Benih G0',
                                        'badge' => 'Vigor Tinggi Teruji',
                                        'badge_color' => '#166534',
                                        'badge_bg' => '#dcfce7',
                                        'sub' => $isEn ? 'High emergence percentage under screenhouse acclimatization' : 'Persentase perkecambahan stabil pasca-aklimatisasi'
                                    ],
                                    [
                                        'val' => '105 - 115 Hari',
                                        'label' => $isEn ? 'Harvest Cycle' : 'Masa Tanam hingga Panen',
                                        'badge' => 'Dataran Tinggi',
                                        'badge_color' => '#475569',
                                        'badge_bg' => '#f1f5f9',
                                        'sub' => $isEn ? 'Optimum maturity period in cool microclimate' : 'Periode kematangan optimum iklim sejuk Humbahas'
                                    ],
                                    [
                                        'val' => 'Resisten Sedang',
                                        'label' => $isEn ? 'Leaf Blight Tolerance' : 'Ketahanan Penyakit Hawar',
                                        'badge' => 'Toleran Alternaria',
                                        'badge_color' => '#15803d',
                                        'badge_bg' => '#dcfce7',
                                        'sub' => $isEn ? 'Tested against purple blotch & Fusarium wilt' : 'Telah diuji terhadap bercak ungu & layu fusarium'
                                    ],
                                ];

                                $benchmarks = [
                                    [
                                        'title' => $isEn ? 'Harvest Productivity (Yield)' : 'Produktivitas Hasil Panen (Yield)',
                                        'tsth2_val' => '13.5 Ton / Ha',
                                        'tsth2_pct' => 90,
                                        'conv_val' => '7.2 Ton / Ha',
                                        'conv_pct' => 48,
                                        'note' => $isEn ? '+87.5% increase compared to conventional seed' : '+87.5% peningkatan dibanding benih lokal konvensional'
                                    ],
                                    [
                                        'title' => $isEn ? 'Post-Harvest Storage Loss (3 Months)' : 'Susut Bobot Simpan Pascapannen (3 Bulan)',
                                        'tsth2_val' => '5.8% (Sangat Rendah)',
                                        'tsth2_pct' => 25,
                                        'conv_val' => '18.2% (Tinggi)',
                                        'conv_pct' => 75,
                                        'note' => $isEn ? 'Durable skin layers reduce rot and shrinkage' : 'Lapisan kulit umbi rapat memperpanjang daya simpan'
                                    ],
                                    [
                                        'title' => $isEn ? 'Chemical Fungicide Input Reduction' : 'Efisiensi Pengurangan Fungisida Kimia',
                                        'tsth2_val' => 'Hemat 40%',
                                        'tsth2_pct' => 85,
                                        'conv_val' => 'Aplikasi Intensif',
                                        'conv_pct' => 40,
                                        'note' => $isEn ? 'Smart drip bio-protection reduces synthetic spraying' : 'Aplikasi inokulan hayati TSTH2 menekan ketergantungan kimia'
                                    ],
                                ];

                                $params = [
                                    ['key' => $isEn ? 'Tested Varieties' : 'Varietas yang Diuji', 'val' => 'Sangga Sembalun & Lumbu Hijau'],
                                    ['key' => $isEn ? 'Elevation & Microclimate' : 'Elevasi & Mikroklimat', 'val' => '1.350 – 1.450 mdpl | Suhu 16°C – 22°C | RH 78% – 85%'],
                                    ['key' => $isEn ? 'Soil Morphology' : 'Morfologi Tanah', 'val' => 'Andosol Vulkanik, C-Organik 4.8%, pH 6.2 – 6.6'],
                                    ['key' => $isEn ? 'Cultivation & Irrigation System' : 'Sistem Budidaya & Irigasi', 'val' => 'Irigasi Tetes Presisi (Smart Drip IoT) + Mulsa Organik Hayati'],
                                    ['key' => $isEn ? 'Active Biomarkers' : 'Biomarker Senyawa Aktif', 'val' => 'Alisin (Allicin), Alliin, Minyak Atsiri Sulfur Organik'],
                                    ['key' => $isEn ? 'Partner Institutions' : 'Mitra Kerjasama Riset', 'val' => 'CV Roberkat, Balai Riset Hortikultura (Balithi), Kementan RI'],
                                ];

                                $roadmap = [
                                    ['title' => $isEn ? 'Phase 1: Germplasm Screening & Morphological Identification' : 'Fase 1: Skrining Plasma Nutfah & Karakterisasi Benih', 'pct' => '100%', 'status' => 'completed', 'desc' => $isEn ? 'Selection of high-vigor parent garlic cloves with superior traits.' : 'Seleksi benih induk berdaya kecambah prima dan bebas penyakit bawaan.'],
                                    ['title' => $isEn ? 'Phase 2: Screenhouse Acclimatization & G0 Bulb Multiplication' : 'Fase 2: Aklimatisasi Screenhouse & Perbanyakan Umbi G0', 'pct' => '100%', 'status' => 'completed', 'desc' => $isEn ? 'Micro-climate controlled growth in TSTH2 protected research facilities.' : 'Pembibitan di fasilitas rumah kasa steril TSTH2 dengan substrat steril terkontrol.'],
                                    ['title' => $isEn ? 'Phase 3: Field Adaptation Trial at Parsingguran II' : 'Fase 3: Uji Lapangan Multilokasi di Parsingguran II', 'pct' => '100%', 'status' => 'completed', 'desc' => $isEn ? 'Successful maiden harvest on 24 Feb 2026 demonstrating strong environmental adaptation.' : 'Panen perdana pada 24 Feb 2026 membuktikan kecocokan agroklimat dataran tinggi Pollung.'],
                                    ['title' => $isEn ? 'Phase 4: Laboratory Post-Harvest Assay & Quality Standardization' : 'Fase 4: Uji Lab Pascapanen & Standardisasi Mutu Benih', 'pct' => '80%', 'status' => 'active', 'desc' => $isEn ? 'Quantification of allicin compounds and seed certification protocol.' : 'Pengujian kadar alisin serta penyusunan protokol sertifikasi benih unggul adaptif lokal.'],
                                    ['title' => $isEn ? 'Phase 5: Seed Dissemination to 50+ Partner Farmer Groups' : 'Fase 5: Hilirisasi Benih ke 50+ Kelompok Tani Binaan', 'pct' => '0%', 'status' => 'planned', 'desc' => $isEn ? 'Scaling up nationwide cultivation to support Indonesian garlic self-sufficiency.' : 'Perluasan ke 50 hektar kawasan budidaya mandiri mendukung swasembada bawang putih nasional.'],
                                ];

                                $card_highlights = ['Yield: 12.8–14.2 Ton/Ha', 'Alisin: 4.62 mg/g', 'Elevasi: 1.400 mdpl'];

                            } elseif (str_contains($title_lower, 'kentang') || str_contains($desc_lower, 'kentang') || str_contains($desc_lower, 'potato')) {
                                $topic = 'kentang';
                                $code = 'TSTH2-RES-KTG-2026';
                                $field = $isEn ? 'Tissue Culture & Potato Biotechnology' : 'Bioteknologi Kultur Jaringan & Benih Kentang';
                                $status = $isEn ? 'Phase 4: Multilocation Trials & G0 Seed Production' : 'Fase 4: Uji Multilokasi & Produksi Benih G0';
                                $statusClass = 'active';
                                $facility = $isEn ? 'In-Vitro Tissue Culture Lab & Acclimatization Screenhouse' : 'Lab Kultur Jaringan In-Vitro & Rumah Kasa Aklimatisasi';
                                $location = 'Kawasan Riset TSTH2, Pollung (1.420 mdpl)';

                                $metrics = [
                                    [
                                        'val' => '28.836 Knol',
                                        'label' => $isEn ? 'Basic Seed (G0) Produced' : 'Produksi Benih Dasar (G0)',
                                        'badge' => 'Capaian Skala Penuh',
                                        'badge_color' => '#166534',
                                        'badge_bg' => '#dcfce7',
                                        'sub' => $isEn ? 'High quality mini-tubers produced in isolated aeroponic screenhouses' : 'Benih dasar bebas virus hasil perbanyakan in-vitro terkontrol'
                                    ],
                                    [
                                        'val' => '3.060 Knol',
                                        'label' => $isEn ? 'F1 Seed Knols' : 'Produksi Benih F1',
                                        'badge' => 'Vigor Tinggi',
                                        'badge_color' => '#1e40af',
                                        'badge_bg' => '#dbeafe',
                                        'sub' => $isEn ? 'Certified multiplication for field demonstration trials' : 'Benih tahap perbanyakan siap tanam di petak percontohan'
                                    ],
                                    [
                                        'val' => '20 Varietas',
                                        'label' => $isEn ? 'Candidate Varieties Evaluated' : 'Calon Varietas Uji Coba',
                                        'badge' => 'Multilokasi',
                                        'badge_color' => '#854d0e',
                                        'badge_bg' => '#fef9c3',
                                        'sub' => $isEn ? 'Screened across heterogeneous highland agro-ecosystems' : 'Dievaluasi ketahanan lintas ketinggian 1.100 - 1.600 mdpl'
                                    ],
                                    [
                                        'val' => '94.2%',
                                        'label' => $isEn ? 'Acclimatization Success Rate' : 'Keberhasilan Aklimatisasi',
                                        'badge' => 'Standar Presisi',
                                        'badge_color' => '#166534',
                                        'badge_bg' => '#dcfce7',
                                        'sub' => $isEn ? 'Transition from sterile test-tubes to field-ready soil beds' : 'Tingkat kelangsungan hidup dari tabung kultur ke rumah kasa'
                                    ],
                                    [
                                        'val' => '89.5%',
                                        'label' => $isEn ? 'Late Blight Tolerance Index' : 'Indeks Toleransi Busuk Daun',
                                        'badge' => 'Tahan Phytophthora',
                                        'badge_color' => '#15803d',
                                        'badge_bg' => '#dcfce7',
                                        'sub' => $isEn ? 'Demonstrated resistance against Phytophthora infestans' : 'Ketahanan genetik terhadap serangan jamur busuk daun kentang'
                                    ],
                                    [
                                        'val' => '99.9% Bebas Virus',
                                        'label' => $isEn ? 'ELISA & PCR Virus Free' : 'Kemurnian Bebas Virus (PVX/PVY)',
                                        'badge' => 'Sertifikasi Molekuler',
                                        'badge_color' => '#1e40af',
                                        'badge_bg' => '#dbeafe',
                                        'sub' => $isEn ? 'Verified with molecular PCR diagnostic tests' : 'Diverifikasi melalui uji serologi ELISA dan marka DNA PCR'
                                    ],
                                ];

                                $benchmarks = [
                                    [
                                        'title' => $isEn ? 'In-Vitro to Screenhouse Survival Rate' : 'Tingkat Keberhasilan Aklimatisasi Bibit',
                                        'tsth2_val' => '94.2% (TSTH2 Protokol Steril)',
                                        'tsth2_pct' => 94,
                                        'conv_val' => '72.5% (Standar Nasional)',
                                        'conv_pct' => 72,
                                        'note' => $isEn ? '+21.7% higher plantlet survival with precision climate control' : 'Kontrol suhu dan kelembapan otomatis meminimalkan stres bibit'
                                    ],
                                    [
                                        'title' => $isEn ? 'Tubers Produced Per Mother Plant (Knol/Pohon)' : 'Rata-rata Knol per Tanaman Induk',
                                        'tsth2_val' => '8.6 Knol / Tanaman',
                                        'tsth2_pct' => 86,
                                        'conv_val' => '4.2 Knol / Tanaman',
                                        'conv_pct' => 42,
                                        'note' => $isEn ? 'Substrate formula boosts tuber stolon multiplication by 105%' : 'Formulasi nutrisi mikro TSTH2 menggandakan pembentukan umbi mini'
                                    ],
                                ];

                                $params = [
                                    ['key' => $isEn ? 'Target Varieties' : 'Varietas Sasaran', 'val' => 'Granola L, Atlantik, Calon Varietas Unggul Humbang 1 & 2'],
                                    ['key' => $isEn ? 'Cleanroom Specification' : 'Spesifikasi Ruang Kultur', 'val' => 'ISO Class 7 Cleanroom, Laminar Airflow Class II'],
                                    ['key' => $isEn ? 'Acclimatization Medium' : 'Media Aklimatisasi', 'val' => 'Sterilized Coco-peat & Roasted Rice Husk + Bio-fungicide'],
                                    ['key' => $isEn ? 'Screening Pathogens' : 'Target Patogen Uji', 'val' => 'Phytophthora infestans, PVX, PVY, PVS, Ralstonia solanacearum'],
                                    ['key' => $isEn ? 'Target Elevation Zone' : 'Zona Elevasi Budidaya', 'val' => '1.200 – 1.650 mdpl (Dataran Tinggi Bukit Barisan)'],
                                ];

                                $roadmap = [
                                    ['title' => $isEn ? 'Phase 1: Meristem Tip Isolation & Thermotherapy' : 'Fase 1: Isolasi Titik Tumbuh Meristem & Termoterapi', 'pct' => '100%', 'status' => 'completed', 'desc' => $isEn ? 'Virus eradication to produce 100% clean starting clones.' : 'Pembersihan virus laten melalui perlakuan panas terkontrol.'],
                                    ['title' => $isEn ? 'Phase 2: In-Vitro Micro-propagation & Shoot Multiplication' : 'Fase 2: Multiplikasi Tunas Mikro In-Vitro', 'pct' => '100%', 'status' => 'completed', 'desc' => $isEn ? 'Rapid clonal multiplication in nutrient-rich agar medium.' : 'Perbanyakan massal planlet di laboratorium kultur jaringan steril.'],
                                    ['title' => $isEn ? 'Phase 3: Production of 28,836 G0 Basic Seed Knols' : 'Fase 3: Produksi 28.836 Knol Benih Dasar G0', 'pct' => '100%', 'status' => 'completed', 'desc' => $isEn ? 'Successful production in quarantine screenhouses at TSTH2.' : 'Pemanenan umbi mini berkualitas prima di rumah kasa karantina.'],
                                    ['title' => $isEn ? 'Phase 4: Multilocation Field Trials across North Sumatra' : 'Fase 4: Uji Multilokasi Lintas Agroekosistem Sumatera', 'pct' => '75%', 'status' => 'active', 'desc' => $isEn ? 'Performance evaluation in Humbahas, Karo, and Simalungun.' : 'Pengujian performa di dataran tinggi Humbahas, Karo, dan Simalungun.'],
                                    ['title' => $isEn ? 'Phase 5: Commercial Release & National Seed Certification' : 'Fase 5: Pelepasan Varietas & Sertifikasi Benih Nasional', 'pct' => '0%', 'status' => 'planned', 'desc' => $isEn ? 'Official release as certified national seed to empower local farmers.' : 'Sertifikasi BPSB untuk distribusi bibit kentang unggul mandiri.'],
                                ];

                                $card_highlights = ['Benih G0: 28.836 Knol', 'Aklimatisasi: 94.2%', '20 Calon Varietas'];

                            } elseif (str_contains($title_lower, 'agroforestri') || str_contains($desc_lower, 'agroforestri') || str_contains($desc_lower, 'agroforestry')) {
                                $topic = 'agroforestri';
                                $code = 'TSTH2-RES-AGF-2026';
                                $field = $isEn ? 'Conservation Agroforestry & Soil Ecology' : 'Agroforestri Konservasi & Ekologi Tanah';
                                $status = $isEn ? 'Phase 4: Soil Carbon & Eco-Restoration Monitoring' : 'Fase 4: Monitoring Karbon & Restorasi Ekologi';
                                $statusClass = 'active';
                                $facility = $isEn ? 'Eco-Restoration Field Plots & Soil Ecology Lab' : 'Petak Restorasi Lahan & Lab Ekologi Tanah';
                                $location = 'Kawasan Eks-Eukaliptus TSTH2, Pollung (15 Hektar)';

                                $metrics = [
                                    [
                                        'val' => '+42.0%',
                                        'label' => $isEn ? 'Soil Organic Carbon (C-Org)' : 'Pemulihan Karbon Organik Tanah',
                                        'badge' => 'Pemulihan Signifikan',
                                        'badge_color' => '#166534',
                                        'badge_bg' => '#dcfce7',
                                        'sub' => $isEn ? 'Restoration of degraded ex-eucalyptus soil structure' : 'Kenaikan bahan organik tanah dari kondisi kritis pasca-monokultur'
                                    ],
                                    [
                                        'val' => '+38.5%',
                                        'label' => $isEn ? 'Soil Water Holding Capacity' : 'Kapasitas Retensi Air Tanah',
                                        'badge' => 'Konservasi Air',
                                        'badge_color' => '#1e40af',
                                        'badge_bg' => '#dbeafe',
                                        'sub' => $isEn ? 'Enhanced moisture retention preventing dry season drought' : 'Kemampuan tanah menahan cadangan air saat musim kemarau'
                                    ],
                                    [
                                        'val' => '+65.2%',
                                        'label' => $isEn ? 'Soil Microbial Diversity' : 'Keanekaragaman Mikroba Tanah',
                                        'badge' => 'Aktivitas Biologis',
                                        'badge_color' => '#854d0e',
                                        'badge_bg' => '#fef9c3',
                                        'sub' => $isEn ? 'Beneficial mycorrhiza & nitrogen-fixing colonies' : 'Peningkatan populasi cacing tanah dan jamur mikoriza simbiotik'
                                    ],
                                    [
                                        'val' => '5 Komoditas',
                                        'label' => $isEn ? 'Multi-Strata Crops Integrated' : 'Strata Polikultur Terpadu',
                                        'badge' => 'Kopi, Jeruk, Sayur',
                                        'badge_color' => '#166534',
                                        'badge_bg' => '#dcfce7',
                                        'sub' => $isEn ? 'Coffee, citrus, tomato, cabbage, and chili under native canopy' : 'Kombinasi kanopi pohon hutan dengan tanaman pangan produktif'
                                    ],
                                    [
                                        'val' => '-74.0%',
                                        'label' => $isEn ? 'Runoff Erosion Reduction' : 'Penurunan Erosi Lereng',
                                        'badge' => 'Stabilisasi Lereng',
                                        'badge_color' => '#15803d',
                                        'badge_bg' => '#dcfce7',
                                        'sub' => $isEn ? 'Terrace mulching protects highland slopes from heavy rain' : 'Perlindungan mulsa hayati memangkas pengikisan tanah lereng'
                                    ],
                                    [
                                        'val' => '35+ KK Tani',
                                        'label' => $isEn ? 'Empowered Local Farmers' : 'Mitra Tani Lokal Berdaya',
                                        'badge' => 'Dampak Ekonomi',
                                        'badge_color' => '#1e40af',
                                        'badge_bg' => '#dbeafe',
                                        'sub' => $isEn ? 'Generating diversified recurring weekly & seasonal income' : 'Mendapatkan diversifikasi penghasilan harian dan musiman'
                                    ],
                                ];

                                $benchmarks = [
                                    [
                                        'title' => $isEn ? 'Soil Organic Carbon (C-Organic %)' : 'Kandungan Bahan Organik Tanah (C-Organik %)',
                                        'tsth2_val' => '4.35% (Agroforestri TSTH2)',
                                        'tsth2_pct' => 87,
                                        'conv_val' => '1.45% (Lahan Eks-Monokultur)',
                                        'conv_pct' => 29,
                                        'note' => $isEn ? 'Triple increase in fertility without heavy synthetic inputs' : 'Kandungan hara naik 3x lipat dibanding lahan monokultur sebelumnya'
                                    ],
                                    [
                                        'title' => $isEn ? 'Water Infiltration Rate (mm/hour)' : 'Laju Infiltrasi Air Tanah (mm/jam)',
                                        'tsth2_val' => '82 mm / jam',
                                        'tsth2_pct' => 82,
                                        'conv_val' => '24 mm / jam',
                                        'conv_pct' => 24,
                                        'note' => $isEn ? 'Recharges regional groundwater aquifer effectively' : 'Menyimpan resapan air hujan lebih dalam dan mencegah longsor'
                                    ],
                                ];

                                $params = [
                                    ['key' => $isEn ? 'Restoration Area' : 'Luas Lahan Revitalisasi', 'val' => '15 Hektar Plot Percobaan Pollung'],
                                    ['key' => $isEn ? 'Upper Canopy Species' : 'Pohon Strata Atas (Peneduh)', 'val' => 'Kemenyan (Styrax sumatrana), Macadamia, Pinus'],
                                    ['key' => $isEn ? 'Middle & Ground Crops' : 'Tanaman Strata Tengah & Bawah', 'val' => 'Kopi Arabika Sigarar Utang, Jeruk Keprok, Tomat, Cabai, Kol'],
                                    ['key' => $isEn ? 'Biological Treatment' : 'Perlakuan Biologis', 'val' => 'Kompos Organik Hayati TSTH2 + Konsorsium Trichoderma lokal'],
                                    ['key' => $isEn ? 'Community Impact' : 'Dampak Komunitas', 'val' => 'Pelatihan konservasi mandiri untuk 35 keluarga petani Pollung'],
                                ];

                                $roadmap = [
                                    ['title' => $isEn ? 'Phase 1: Soil Physico-Chemical Mapping of Degraded Land' : 'Fase 1: Pemetaan Karakteristik Fisiko-Kimia Lahan', 'pct' => '100%', 'status' => 'completed', 'desc' => $isEn ? 'Baseline survey of chemical depletion on ex-eucalyptus areas.' : 'Survei kondisi hara dan mikroba pada lahan kritis pasca-eukaliptus.'],
                                    ['title' => $isEn ? 'Phase 2: Multi-Strata Agroforestry Spatial Planning' : 'Fase 2: Desain Pola Tanam Multi-Strata & Terasering', 'pct' => '100%', 'status' => 'completed', 'desc' => $isEn ? 'Integration of native forest trees with cash crops.' : 'Penataan ruang tanam ramah kontur tanah perbukitan.'],
                                    ['title' => $isEn ? 'Phase 3: Farmer Partnership & Crop Introduction' : 'Fase 3: Kemitraan Petani & Penanaman Polikultur', 'pct' => '100%', 'status' => 'completed', 'desc' => $isEn ? 'Active field planting alongside local agricultural families.' : 'Penanaman bersama kopi, jeruk, dan sayuran bernilai ekonomi tinggi.'],
                                    ['title' => $isEn ? 'Phase 4: Carbon Sequestration & Biodiversity Tracking' : 'Fase 4: Monitoring Neraca Air & Sekuestrasi Karbon', 'pct' => '70%', 'status' => 'active', 'desc' => $isEn ? 'Continuous sensor monitoring of organic matter & groundwater.' : 'Pencatatan berkala peningkatan biomassa dan keanekaragaman serangga.'],
                                    ['title' => $isEn ? 'Phase 5: Regional Replication Across 200+ Hectares' : 'Fase 5: Replikasi Model ke 200 Hektar Kawasan Humbahas', 'pct' => '0%', 'status' => 'planned', 'desc' => $isEn ? 'Expanding ecosystem-based agroforestry across Lake Toba catchment.' : 'Penerapan skala luas untuk konservasi tangkapan air Danau Toba.'],
                                ];

                                $card_highlights = ['C-Organik: +42%', 'Retensi Air: +38.5%', '35+ KK Mitra Tani'];

                            } elseif (str_contains($title_lower, 'genolab') || str_contains($desc_lower, 'genolab') || str_contains($desc_lower, 'genomik') || str_contains($desc_lower, 'genomic')) {
                                $topic = 'genolab';
                                $code = 'TSTH2-RES-GENO-2026';
                                $field = $isEn ? 'Molecular Biology & Plant Genomics' : 'Biologi Molekuler & Genomik Tanaman';
                                $status = $isEn ? 'Phase 3: Plant Genomic Indexing & ISO 17025 Prep' : 'Fase 3: Pengindeksan Genom & Kesiapan ISO 17025';
                                $statusClass = 'active';
                                $facility = $isEn ? 'High-Throughput Genomics & PCR Sequencing Core Lab' : 'Lab Inti Genomik & Sekuensing PCR Berkapasitas Tinggi';
                                $location = 'Gedung Laboratorium Utama TSTH2, Pollung';

                                $metrics = [
                                    [
                                        'val' => '150+ Sampel / Minggu',
                                        'label' => $isEn ? 'Extraction Throughput' : 'Kapasitas Ekstraksi DNA/RNA',
                                        'badge' => 'Throughput Tinggi',
                                        'badge_color' => '#166534',
                                        'badge_bg' => '#dcfce7',
                                        'sub' => $isEn ? 'Automated high-purity isolation of plant genomic material' : 'Ekstraksi DNA kemurnian tinggi dari tanaman bergetah/berminyak atsiri'
                                    ],
                                    [
                                        'val' => '120+ Spesies',
                                        'label' => $isEn ? 'Cataloged Germplasm Genomes' : 'Spesies Plasma Nutfah Terpetakan',
                                        'badge' => 'Bank Genomik',
                                        'badge_color' => '#1e40af',
                                        'badge_bg' => '#dbeafe',
                                        'sub' => $isEn ? 'Digital DNA barcode repository of Indonesian herbal treasures' : 'Database marka DNA barcode tanaman herbal asli Sumatera'
                                    ],
                                    [
                                        'val' => '99.8%',
                                        'label' => $isEn ? 'PCR Molecular Marker Precision' : 'Akurasi Deteksi Marka PCR',
                                        'badge' => 'Presisi Tinggi',
                                        'badge_color' => '#854d0e',
                                        'badge_bg' => '#fef9c3',
                                        'sub' => $isEn ? 'Reliable differentiation between wild & cultivated chemotypes' : 'Ketepatan pemisahan varietas unggul dan verifikasi kemurnian'
                                    ],
                                    [
                                        'val' => '< 48 Jam',
                                        'label' => $isEn ? 'Sequencing Turnaround Time' : 'Kecepatan Analisis Per Batch',
                                        'badge' => 'Cepat & Terukur',
                                        'badge_color' => '#166534',
                                        'badge_bg' => '#dcfce7',
                                        'sub' => $isEn ? 'Rapid identification pipeline from leaf tissue to gene map' : 'Dari sampel daun segar hingga profil sekuens gen lengkap'
                                    ],
                                    [
                                        'val' => 'ISO/IEC 17025',
                                        'label' => $isEn ? 'Lab Accreditation Readiness' : 'Standardisasi Akreditasi Lab',
                                        'badge' => 'Standar Mutu Dunia',
                                        'badge_color' => '#1e40af',
                                        'badge_bg' => '#dbeafe',
                                        'sub' => $isEn ? 'Compliance with international testing and calibration standards' : 'Penerapan jaminan mutu analisis berstandar internasional'
                                    ],
                                    [
                                        'val' => '5 Gen Target',
                                        'label' => $isEn ? 'Active Marker Genes Identified' : 'Gen Target Biomarker',
                                        'badge' => 'Riset Biofarmaka',
                                        'badge_color' => '#15803d',
                                        'badge_bg' => '#dcfce7',
                                        'sub' => $isEn ? 'Markers linked to disease immunity and secondary metabolite yield' : 'Gen kunci penghasil metabolit aktif bernilai obat tinggi'
                                    ],
                                ];

                                $benchmarks = [
                                    [
                                        'title' => $isEn ? 'Species Identification Accuracy (DNA Barcoding vs Visual)' : 'Akurasi Identifikasi Spesies Herbal (Marka DNA vs Visual)',
                                        'tsth2_val' => '99.8% (Platform Genolab TSTH2)',
                                        'tsth2_pct' => 99,
                                        'conv_val' => '78.5% (Identifikasi Morfologi Saja)',
                                        'conv_pct' => 78,
                                        'note' => $isEn ? 'Eliminates misidentification in high-value herbal raw materials' : 'Mencegah pemalsuan bahan baku simplisia herbal farmasi'
                                    ],
                                ];

                                $params = [
                                    ['key' => $isEn ? 'Core Equipment' : 'Instrumen Utama', 'val' => 'Real-Time Quantitative PCR (qPCR), Nanodrop, Gel Doc, Bioanalyzer'],
                                    ['key' => $isEn ? 'Cleanroom Environment' : 'Standar Ruang Bersih', 'val' => 'Cleanroom ISO Class 7 dengan Biosafety Cabinet Class II'],
                                    ['key' => $isEn ? 'Target Plants Analyzed' : 'Komoditas Prioritas Riset', 'val' => 'Kemenyan (Styrax), Andaliman, Jahe Merah, Temulawak, Bawang Putih'],
                                    ['key' => $isEn ? 'Bioinformatics Suite' : 'Perangkat Analisis Data', 'val' => 'Genome Assembler, Phylogenetic Tree Builder, BLAST Server TSTH2'],
                                ];

                                $roadmap = [
                                    ['title' => $isEn ? 'Phase 1: Lab Commissioning & DNA Extraction Protocol Optimization' : 'Fase 1: Kalibrasi Lab & Optimalisasi Protokol Ekstraksi', 'pct' => '100%', 'status' => 'completed', 'desc' => $isEn ? 'Validating DNA extraction on resinous & aromatic highland plants.' : 'Validasi ekstraksi DNA pada tanaman bergetah tebal seperti kemenyan.'],
                                    ['title' => $isEn ? 'Phase 2: Sequencing of North Sumatra Indigenous Germplasm' : 'Fase 2: Sekuensing Plasma Nutfah Herbal Endemik', 'pct' => '100%', 'status' => 'completed', 'desc' => $isEn ? 'Sequencing high-priority medicinal botanical specimens.' : 'Pemetaan kode genetik tanaman unggulan khas Danau Toba.'],
                                    ['title' => $isEn ? 'Phase 3: Digital Genomic Database & Marker Discovery' : 'Fase 3: Pembangunan Basis Data Molekuler & Penemuan Marka', 'pct' => '80%', 'status' => 'active', 'desc' => $isEn ? 'Correlating genetic sequences with chemical active concentrations.' : 'Korelasi genetik dengan rendemen metabolit fitofarmaka.'],
                                    ['title' => $isEn ? 'Phase 4: Open Analytical Testing for Pharmaceutical Industry' : 'Fase 4: Layanan Pengujian Terakreditasi bagi Industri Herbal', 'pct' => '0%', 'status' => 'planned', 'desc' => $isEn ? 'Offering commercial molecular authenticity testing services.' : 'Layanan uji kemurnian genetik untuk industri farmasi dan kosmetik.'],
                                ];

                                $card_highlights = ['120+ Genom Spesies', 'Sekuens < 48 Jam', 'Presisi 99.8%'];

                            } elseif (str_contains($title_lower, 'kopi') || str_contains($desc_lower, 'kopi') || str_contains($desc_lower, 'coffee')) {
                                $topic = 'kopi';
                                $code = 'TSTH2-RES-KOP-2026';
                                $field = $isEn ? 'Specialty Coffee Agronomy & Agroforestry' : 'Agronomi Kopi Spesialti & Agroforestri';
                                $status = $isEn ? 'Phase 2: Canopy Trials & Microclimate IoT Setup' : 'Fase 2: Uji Kanopi & Instalasi IoT Mikroklimat';
                                $statusClass = 'active';
                                $facility = $isEn ? 'Specialty Coffee Experimental Plots & Cupping Lab' : 'Plot Riset Kopi Spesialti & Lab Uji Citarasa Cupping';
                                $location = 'Blok Riset Kopi TSTH2, Pollung (1.450 mdpl)';

                                $metrics = [
                                    [
                                        'val' => '86.5 - 88.0',
                                        'label' => $isEn ? 'Target Cupping Score' : 'Target Skor Cupping SCA',
                                        'badge' => 'Specialty Grade',
                                        'badge_color' => '#854d0e',
                                        'badge_bg' => '#fef9c3',
                                        'sub' => $isEn ? 'International specialty score with complex fruity & herbal notes' : 'Citarasa premium dengan keasaman seimbang dan aroma rempah'
                                    ],
                                    [
                                        'val' => '1.450 mdpl',
                                        'label' => $isEn ? 'Experimental Altitude' : 'Elevasi Lahan Riset',
                                        'badge' => 'Dataran Tinggi Ideal',
                                        'badge_color' => '#166534',
                                        'badge_bg' => '#dcfce7',
                                        'sub' => $isEn ? 'Optimal mountain altitude for dense bean density' : 'Ketinggian ideal untuk pemadatan biji kopi arabika bermutu tinggi'
                                    ],
                                    [
                                        'val' => '2.5 m × 2.5 m',
                                        'label' => $isEn ? 'Precision Spacing Grid' : 'Pola Tata Tanam Presisi',
                                        'badge' => 'Optimalisasi Kanopi',
                                        'badge_color' => '#1e40af',
                                        'badge_bg' => '#dbeafe',
                                        'sub' => $isEn ? 'Ensuring optimal air circulation and solar interception' : 'Pemasangan pancang terukur untuk sirkulasi udara bebas jamur'
                                    ],
                                    [
                                        'val' => '11.0% - 12.0%',
                                        'label' => $isEn ? 'Green Bean Moisture Target' : 'Kadar Air Biji Terkontrol',
                                        'badge' => 'Standar Ekspor Internasional',
                                        'badge_color' => '#166534',
                                        'badge_bg' => '#dcfce7',
                                        'sub' => $isEn ? 'Controlled solar dome drying prevents moisture fluctuation' : 'Pengeringan kubah surya terkontrol mencegah pembusukan biji'
                                    ],
                                    [
                                        'val' => '2 Pohon Naungan',
                                        'label' => $isEn ? 'Eco-Canopy Species' : 'Pohon Peneduh Alami',
                                        'badge' => 'Kemenyan & Macadamia',
                                        'badge_color' => '#15803d',
                                        'badge_bg' => '#dcfce7',
                                        'sub' => $isEn ? 'Styrax and Macadamia provide filtered shade and soil nutrients' : 'Naungan alami memperkaya mikroklimat tanah dan aroma kopi'
                                    ],
                                    [
                                        'val' => '< 2.0%',
                                        'label' => $isEn ? 'Defect Rate Target' : 'Target Tingkat Cacat Biji',
                                        'badge' => 'Kategori Zero Defect',
                                        'badge_color' => '#1e40af',
                                        'badge_bg' => '#dbeafe',
                                        'sub' => $isEn ? 'Selective picking and precise post-harvest fermentation' : 'Sortasi petik merah 100% matang pohon'
                                    ],
                                ];

                                $benchmarks = [
                                    [
                                        'title' => $isEn ? 'Specialty Cupping Score (SCA Standard)' : 'Skor Citarasa Cupping (Standar SCA Internasional)',
                                        'tsth2_val' => '87.2 Poin (TSTH2 Plot Presisi)',
                                        'tsth2_pct' => 87,
                                        'conv_val' => '81.5 Poin (Kopi Komersial Biasa)',
                                        'conv_pct' => 81,
                                        'note' => $isEn ? '+5.7 score points elevates export price by up to 80%' : 'Meningkatkan nilai jual biji kopi petani di pasar kopi spesialti dunia'
                                    ],
                                ];

                                $params = [
                                    ['key' => $isEn ? 'Selected Arabica Varieties' : 'Varietas Kopi Terpilih', 'val' => 'Arabika Sigarar Utang, Ateng Super, dan Typica Humbahas'],
                                    ['key' => $isEn ? 'Canopy & Agroforestry Trees' : 'Pohon Naungan', 'val' => 'Kemenyan (Styrax sumatrana) dan Macadamia integrifolia'],
                                    ['key' => $isEn ? 'Altitude & Soil Profile' : 'Ketinggian & Jenis Tanah', 'val' => '1.450 mdpl, Andosol Kaya Humus Vulkanik, pH 5.8 – 6.2'],
                                    ['key' => $isEn ? 'Fermentation Method' : 'Metode Pengolahan Riset', 'val' => 'Fermentasi Terkontrol Anaerobik & Eksperimen Honey Process'],
                                ];

                                $roadmap = [
                                    ['title' => $isEn ? 'Phase 1: Land Survey, Terracing, and Precision Staking' : 'Fase 1: Survei Kontur Lahan & Pemasangan Pancang', 'pct' => '100%', 'status' => 'completed', 'desc' => $isEn ? 'Geometric grid staking ensuring optimal sun trajectory.' : 'Penentuan titik tanam presisi 2.5m x 2.5m mengikuti kontur lereng.'],
                                    ['title' => $isEn ? 'Phase 2: Planting Native Shade Trees & High-Vigor Seedlings' : 'Fase 2: Penanaman Naungan Kemenyan & Bibit Unggul', 'pct' => '85%', 'status' => 'active', 'desc' => $isEn ? 'Establishing biological shade canopy before vegetative growth.' : 'Penanaman pohon penaung dan bibit kopi varietas Sigarar Utang terseleksi.'],
                                    ['title' => $isEn ? 'Phase 3: Smart IoT Soil & Weather Station Deployment' : 'Fase 3: Pemasangan Stasiun Mikroklimat & Sensor IoT', 'pct' => '50%', 'status' => 'active', 'desc' => $isEn ? 'Continuous monitoring of moisture, rainfall, and leaf wetness.' : 'Monitoring kelembapan tanah real-time untuk efisiensi irigasi.'],
                                    ['title' => $isEn ? 'Phase 4: Maiden Harvest & Post-Harvest Cupping Protocol' : 'Fase 4: Panen Perdana Ceri & Protokol Uji Cupping Lab', 'pct' => '0%', 'status' => 'planned', 'desc' => $isEn ? 'Standardized cupping and sensory analysis by Q-graders.' : 'Uji rasa laboratorium untuk menentukan profil sangrai terbaik.'],
                                ];

                                $card_highlights = ['Cupping: 86.5–88.0', 'Elevasi: 1.450 mdpl', 'Jarak: 2.5m×2.5m'];

                            } else {
                                // Dynamic Fallback for any other custom research entry
                                $topic = 'umum';
                                $code = 'TSTH2-RES-GEN-' . str_pad($item->id, 3, '0', STR_PAD_LEFT);
                                $field = $isEn ? 'Integrated Applied Research' : 'Riset Terapan Terpadu';
                                $status = $isEn ? 'Phase Active: Field Research & Testing' : 'Fase Aktif: Pengujian Lapangan & Lab';
                                $statusClass = 'active';
                                $facility = $isEn ? 'TSTH2 Research Laboratory & Experimental Field' : 'Laboratorium & Lahan Percobaan TSTH2';
                                $location = 'Kawasan Riset TSTH2, Pollung, Humbang Hasundutan';

                                $metrics = [
                                    [
                                        'val' => '100% Terverifikasi',
                                        'label' => $isEn ? 'Quality Compliance' : 'Kesesuaian Standar Mutu',
                                        'badge' => 'Terstandarisasi',
                                        'badge_color' => '#166534',
                                        'badge_bg' => '#dcfce7',
                                        'sub' => $isEn ? 'Compliant with TSTH2 scientific research protocols' : 'Sesuai dengan protokol penelitian ilmiah TSTH2'
                                    ],
                                    [
                                        'val' => '1.400 mdpl',
                                        'label' => $isEn ? 'Testing Elevation' : 'Ketinggian Lahan Uji',
                                        'badge' => 'Dataran Tinggi',
                                        'badge_color' => '#1e40af',
                                        'badge_bg' => '#dbeafe',
                                        'sub' => $isEn ? 'Tested in cool sub-tropical high-altitude conditions' : 'Kondisi agroklimat sejuk dataran tinggi Sumatera Utara'
                                    ],
                                    [
                                        'val' => 'Presisi Tinggi',
                                        'label' => $isEn ? 'Observation Precision' : 'Tingkat Presisi Pengamatan',
                                        'badge' => 'Uji Empiris',
                                        'badge_color' => '#854d0e',
                                        'badge_bg' => '#fef9c3',
                                        'sub' => $isEn ? 'Continuous field measurement and data collection' : 'Pencatatan data lapangan berbasis metodologi sains terapan'
                                    ],
                                    [
                                        'val' => 'Berkelanjutan',
                                        'label' => $isEn ? 'Ecological Sustainability' : 'Keberlanjutan Ekologi',
                                        'badge' => 'Eco-Friendly',
                                        'badge_color' => '#166534',
                                        'badge_bg' => '#dcfce7',
                                        'sub' => $isEn ? 'Conservation-first approach protecting regional biodiversity' : 'Pendekatan ramah lingkungan menjaga keanekaragaman hayati'
                                    ],
                                ];

                                $benchmarks = [
                                    [
                                        'title' => $isEn ? 'Standardized Research Methodology' : 'Penerapan Metodologi Riset Standar',
                                        'tsth2_val' => '100% Protokol TSTH2',
                                        'tsth2_pct' => 100,
                                        'conv_val' => '50% Metode Konvensional',
                                        'conv_pct' => 50,
                                        'note' => $isEn ? 'Empirical data-driven approach yields reliable outcomes' : 'Pendekatan berbasis data ilmiah menghasilkan output terukur'
                                    ],
                                ];

                                $params = [
                                    ['key' => $isEn ? 'Research Domain' : 'Bidang Penelitian', 'val' => $field],
                                    ['key' => $isEn ? 'Facility' : 'Fasilitas Pendukung', 'val' => $facility],
                                    ['key' => $isEn ? 'Lead Researcher' : 'Peneliti Utama', 'val' => $peneliti ?? 'Tim Riset Terpadu TSTH2'],
                                    ['key' => $isEn ? 'Location' : 'Lokasi Pelaksanaan', 'val' => $location],
                                ];

                                $roadmap = [
                                    ['title' => $isEn ? 'Phase 1: Baseline Research & Literature Review' : 'Fase 1: Studi Pendahuluan & Desain Riset', 'pct' => '100%', 'status' => 'completed', 'desc' => 'Perumusan hipotesis dan penyiapan infrastruktur pengujian.'],
                                    ['title' => $isEn ? 'Phase 2: Execution of Experiments and Field Trials' : 'Fase 2: Pelaksanaan Eksperimen Lapangan & Pengamatan', 'pct' => '80%', 'status' => 'active', 'desc' => 'Pengambilan data primer, pemeliharaan, dan pencatatan parameter.'],
                                    ['title' => $isEn ? 'Phase 3: Data Analysis & Recommendation Formulation' : 'Fase 3: Analisis Hasil & Diseminasi Rekomendasi', 'pct' => '30%', 'status' => 'active', 'desc' => 'Penyusunan laporan riset dan rekomendasi hilirisasi industri.'],
                                ];

                                $card_highlights = [$field, 'TSTH2 Pollung'];
                            }

                            // Override with custom database values if configured by admin
                            if (!empty($item->code)) $code = $item->code;
                            if (!empty($item->field)) $field = $item->field;
                            if (!empty($item->status_riset)) $status = $item->status_riset;
                            if (!empty($item->facility)) $facility = $item->facility;
                            if (!empty($item->location)) $location = $item->location;
                            if (!empty($item->peneliti)) $peneliti = $item->peneliti;
                            if (!empty($item->tujuan)) $tujuan = $item->tujuan;
                            if (!empty($item->metrics) && is_array($item->metrics) && count($item->metrics) > 0) $metrics = $item->metrics;
                            if (!empty($item->benchmarks) && is_array($item->benchmarks) && count($item->benchmarks) > 0) $benchmarks = $item->benchmarks;
                            if (!empty($item->params) && is_array($item->params) && count($item->params) > 0) $params = $item->params;
                            if (!empty($item->roadmap) && is_array($item->roadmap) && count($item->roadmap) > 0) $roadmap = $item->roadmap;

                            $itemData = [
                                'id'          => $item->id,
                                'code'        => $code,
                                'title'       => $item->title ?? '',
                                'description' => $cleanText,
                                'tujuan'      => $tujuan,
                                'peneliti'    => $peneliti ?? ($isEn ? 'TSTH2 Research Team & Collaborators' : 'Tim Peneliti TSTH2 & Mitra Riset'),
                                'field'       => $field,
                                'status'      => $status,
                                'facility'    => $facility,
                                'location'    => $location,
                                'image_path'  => $item->image_path ?? null,
                                'metrics'     => $metrics,
                                'benchmarks'  => $benchmarks,
                                'params'      => $params,
                                'roadmap'     => $roadmap,
                            ];
                        @endphp

                        <a href="{{ route('penelitian.show', $item->id) }}" class="research-entry animate-on-scroll" style="text-decoration: none; color: inherit;">

                            {{-- Kolom kiri: thumbnail --}}
                            <div class="research-entry-thumb">
                                <img src="{{ !empty($item->image_path) ? asset('storage/' . $item->image_path) : asset('assets/default_hero.jpg') }}"
                                     alt="{{ $item->title }}" loading="lazy">
                                <div class="thumb-label">TSTH2 · Pollung</div>
                            </div>

                            {{-- Kolom kanan: konten entry --}}
                            <div class="research-entry-content">
                                <div class="entry-top-bar">
                                    <span class="research-field-tag" style="gap: 5px;">
                                        <span class="material-symbols-rounded" style="font-size:14px;">biotech</span> {{ $field }}
                                    </span>
                                </div>

                                <h3 class="research-entry-title">{{ $item->title }}</h3>

                                {{-- Visual highlights badges --}}
                                @if(!empty($card_highlights))
                                <div class="card-metrics-row">
                                    @foreach ($card_highlights as $hl)
                                        <span class="metric-pill">
                                            <strong>{{ $hl }}</strong>
                                        </span>
                                    @endforeach
                                </div>
                                @endif

                                <div class="research-meta-grid">
                                    @if(!empty($peneliti))
                                    <div class="research-meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        <span><strong>{{ __('site.res_lead_researcher') }}:</strong> {{ $peneliti }}</span>
                                    </div>
                                    @endif
                                    @if(!empty($location))
                                    <div class="research-meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        <span><strong>{{ __('site.res_location_label') }}:</strong> {{ $location }}</span>
                                    </div>
                                    @endif
                                </div>

                                @if(!empty($cleanText))
                                <p class="research-abstract">{{ $cleanText }}</p>
                                @endif

                                <div class="research-entry-footer">
                                    @if(!empty($status))
                                    <span style="font-size: 0.76rem; font-weight: 700; color: #166534; background: #dcfce7; padding: 0.25rem 0.65rem; border-radius: 999px; display: inline-flex; align-items: center; gap: 4px;">
                                        <span class="material-symbols-rounded" style="font-size:13px;">bolt</span> {{ $status }}
                                    </span>
                                    @else
                                    <span></span>
                                    @endif
                                    <span class="open-dossier-btn">
                                        {{ __('site.res_open_full_dossier') }}
                                        <span class="material-symbols-rounded" style="font-size:16px;">arrow_forward</span>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div style="text-align: center; padding: 4.5rem 2rem; background: white; border-radius: 1.25rem; color: #64748b; border: 1px solid #e2e8f0;">
                            <span class="material-symbols-rounded" style="font-size: 2.5rem; color: #cbd5e1; display: block; margin-bottom: 1rem;">science</span>
                            <h3 style="color: #0f172a; font-weight: 800; margin-bottom: 0.5rem;">
                                {{ __('site.empty_research') }}
                            </h3>
                            <p>{{ __('site.empty_research_desc') }}</p>
                        </div>
                    @endforelse
                </div>

                @if ($penelitianes->hasPages())
                    <div style="margin-top: 3rem; display: flex; justify-content: center;">
                        {{ $penelitianes->links() }}
                    </div>UU
                @endif
            </div>
        </section>


    </main>

    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>