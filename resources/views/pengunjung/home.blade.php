<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="description" content="TSTH2 — Indonesian Herbal and Horticultural Science and Technology Park. National research center for herbal and horticultural innovation under Kemendiktisaintek & IT Del.">
    <title>TSTH2 — Indonesian Herbal &amp; Horticultural Science Park</title>
    @include('partials.pwa-head')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ time() }}">
</head>
<body>

    <!-- Navigation Bar & Topbar -->
    @include('partials.navbar')

    <!-- ════════════════════════════════════
         HERO SECTION — Full-Height Cinematic
         ════════════════════════════════════ -->
    @if(!empty($konten['home_hero_title']->value))
    <section class="hero" id="home">
        <div class="hero-slider">
            @forelse($homeBackgrounds ?? [] as $index => $bg)
            <div class="slide {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ asset('storage/' . $bg->image_path) }}" alt="TSTH2 Science Park Facility" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                <div class="slide-overlay"></div>
            </div>
            @empty
            <div class="slide active">
                <img src="{{ asset('assets/default_hero.jpg') }}" alt="TSTH2 Background">
                <div class="slide-overlay"></div>
            </div>
            @endforelse
        </div>

        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <span>Taman Sains Teknologi Herbal dan Hortikultura</span>
                </div>
                <h1>{{ $konten['home_hero_title']->value ?? 'Inovasi Herbal & Hortikultura Indonesia' }}</h1>
                <p>{{ $konten['home_hero_desc']->value ?? 'Pusat riset nasional yang diprakarsai oleh Pemerintah Indonesia di bawah Kementerian Pendidikan Tinggi, Sains, dan Teknologi, berkolaborasi dengan Institut Teknologi Del.' }}</p>
                <div class="hero-actions">
                    <a href="#about" class="btn btn-primary" style="background: linear-gradient(135deg, #164E3F, #1f6b57); border: none;">
                        <span>Jelajahi Profil TSTH2</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                    <a href="{{ route('penelitian') }}" class="btn btn-glass" style="border-color: rgba(156,122,38,0.6); background: rgba(156,122,38,0.2);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"/></svg>
                        <span>Riset &amp; Kegiatan</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="slider-controls">
            @foreach($homeBackgrounds ?? [] as $index => $bg)
            <div class="slider-dot {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"></div>
            @endforeach
        </div>

        <!-- Scroll Hint -->
        <div class="hero-scroll-hint">
            <span>Scroll</span>
            <div class="scroll-line"></div>
        </div>
    </section>
    @endif

    <!-- ════════════════════════════════════
         EXECUTIVE METRICS BANNER
         ════════════════════════════════════ -->
    <div class="container stats-banner">
        <div class="stats-grid">
            <div class="stat-item animate-on-scroll">
                <div class="stat-icon-box">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M10 2v7.5M14 2v7.5M8.5 2h7M14 9.5a4.5 4.5 0 1 1-4.5 4.5V9.5h4.5zM4.5 22h15"/></svg>
                </div>
                <div class="stat-info">
                    <h4>50+ Projects</h4>
                    <p>Riset &amp; Konservasi Plasma Nutfah</p>
                </div>
            </div>
            <div class="stat-item animate-on-scroll" style="--icon-bg: #fffbeb; --icon-color: #9C7A26; --icon-border: rgba(156,122,38,0.2);">
                <div class="stat-icon-box" style="background: #fffbeb; color: #9C7A26; border-color: rgba(156,122,38,0.2);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 22v-9"/><path d="M12 13C7 13 4 8 4 3c5 0 8 3 8 8z"/><path d="M12 13c5 0 8-5 8-10-5 0-8 3-8 8z"/></svg>
                </div>
                <div class="stat-info">
                    <h4>100+ Spesies</h4>
                    <p>Varietas Endemik Dataran Tinggi</p>
                </div>
            </div>
            <div class="stat-item animate-on-scroll">
                <div class="stat-icon-box">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div class="stat-info">
                    <h4>ISO 17025</h4>
                    <p>Genolab &amp; Distilasi Presisi</p>
                </div>
            </div>
            <div class="stat-item animate-on-scroll" style="border-right: none;">
                <div class="stat-icon-box" style="background: #fffbeb; color: #9C7A26; border-color: rgba(156,122,38,0.2);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                </div>
                <div class="stat-info">
                    <h4>Global Network</h4>
                    <p>Kerja Sama Riset Internasional</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════
         ABOUT SECTION
         ════════════════════════════════════ -->
    @if(!empty($konten['home_about_title']->value))
    <section id="about" class="about section-padding">
        <div class="container">
            <div class="about-grid">
                <div class="about-text">
                    <h2>{{ $konten['home_about_title']->value ?? 'Apa Itu <span>TSTH2</span>?' }}</h2>
                    <p>
                        {{ $konten['home_about_desc']->value ?? 'TSTH2 (Indonesian Herbal and Horticultural Science and Technology Park) merupakan pusat riset nasional yang diprakarsai oleh Pemerintah Indonesia untuk mendorong inovasi herbal dan hortikultura di Sumatera Utara. Didirikan di bawah Kementerian Pendidikan Tinggi, Sains, dan Teknologi, dengan tata kelola berkolaborasi bersama Institut Teknologi Del.' }}
                    </p>

                    <div class="bullet-list">
                        @foreach($aboutBullets ?? [] as $bullet)
                        <div class="bullet-item animate-on-scroll">
                            <span class="bullet-icon-svg">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </span>
                            <span>{{ $bullet->value ?? $bullet->content }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div style="margin-top: 2.5rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                        <a href="{{ route('profil-public') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #164E3F, #1f6b57);">
                            <span>Profil Lengkap TSTH2</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
                        </a>
                        <a href="{{ route('penelitian') }}" class="btn btn-outline" style="border-color: #9C7A26; color: #9C7A26;">
                            <span>Lihat Riset TSTH2</span>
                        </a>
                    </div>
                </div>

                <div class="about-image-wrapper">
                    <div class="about-image animate-on-scroll" style="background-image: url('{{ isset($konten['home_about_bg']) && $konten['home_about_bg']->image_path ? asset('storage/' . $konten['home_about_bg']->image_path) : asset('assets/default_hero.jpg') }}');">
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- ════════════════════════════════════
         RESEARCH / FACILITIES CARDS SECTION
         ════════════════════════════════════ -->
    @if(!empty($konten['home_penelitian_title']->value))
    <section class="penelitian-focus" id="penelitian">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10 2v7.5M14 2v7.5M8.5 2h7M14 9.5a4.5 4.5 0 1 1-4.5 4.5V9.5h4.5zM4.5 22h15"/></svg>
                    Riset &amp; Infrastruktur
                </span>
                <h2 class="section-title">{{ $konten['home_penelitian_title']->value ?? 'Fasilitas & Penelitian Utama' }}</h2>
                <p class="section-desc">{{ $konten['home_penelitian_desc']->value ?? 'Mengoptimalkan potensi keanekaragaman hayati nusantara melalui pendekatan riset terpadu dan fasilitas yang terus dikembangkan.' }}</p>
            </div>

            <div class="penelitian-grid">
                @foreach($homePenelitianes ?? [] as $item)
                @php
                    $isGreen = $loop->iteration % 2 === 1;
                    $cardClass = $isGreen ? 'executive-card-green' : 'executive-card-orange';
                    $tagLabels = ['Laboratorium Riset', 'Bioteknologi', 'Genolab', 'Fitofarmaka', 'Plasma Nutfah', 'Distilasi'];
                    $tagIdx = ($loop->iteration - 1) % count($tagLabels);
                @endphp
                <div class="executive-card {{ $cardClass }} animate-on-scroll">
                    <div>
                        <div class="exec-icon-box">
                            @if($isGreen)
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M10 2v7.5M14 2v7.5M8.5 2h7M14 9.5a4.5 4.5 0 1 1-4.5 4.5V9.5h4.5zM4.5 22h15"/></svg>
                            @else
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 22v-9"/><path d="M12 13C7 13 4 8 4 3c5 0 8 3 8 8z"/><path d="M12 13c5 0 8-5 8-10-5 0-8 3-8 8z"/></svg>
                            @endif
                        </div>
                        <span class="exec-tag">{{ $tagLabels[$tagIdx] }}</span>
                        <h3>{{ $item->title }}</h3>
                        <p>{{ Str::limit($item->description, 140) }}</p>
                    </div>
                    <div class="exec-card-footer">
                        <button class="btn-card-action btn-open-modal"
                            data-title="{{ $item->title }}"
                            data-desc="{{ $item->description }}"
                            data-img="{{ $item->image_path ? asset('storage/' . $item->image_path) : asset('assets/default_hero.jpg') }}">
                            <span>Lihat Spesifikasi</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <div style="text-align: center; margin-top: 4rem;">
                <a href="{{ route('penelitian') }}" class="btn btn-outline" style="border-color: #164E3F; color: #164E3F; padding: 0.85rem 2.5rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"/></svg>
                    <span>Lihat Seluruh Fasilitas Riset</span>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- ════════════════════════════════════
         INTERNATIONAL COLLABORATION
         ════════════════════════════════════ -->
    <section class="int-collab section-padding">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                    Global Ecosystem
                </span>
                <h2 class="section-title">Kolaborasi Riset</h2>
                <p class="section-desc">TSTH2 terus membangun jejaring dan kerja sama riset bersama perguruan tinggi, lembaga penelitian, dan mitra strategis nasional maupun internasional.</p>
            </div>

            <div class="partner-grid">
                <div class="partner-card animate-on-scroll">
                    <span class="partner-flag">🇯🇵</span>
                    <h4>Jepang</h4>
                    <p>Riset Kultur Jaringan &amp; Molekuler</p>
                </div>
                <div class="partner-card animate-on-scroll">
                    <span class="partner-flag">🇨🇳</span>
                    <h4>China</h4>
                    <p>Bioteknologi &amp; Smart Agriculture</p>
                </div>
                <div class="partner-card animate-on-scroll">
                    <span class="partner-flag">🇩🇪</span>
                    <h4>Jerman</h4>
                    <p>Pengujian Fitofarmaka &amp; ISO Lab</p>
                </div>
                <div class="partner-card animate-on-scroll">
                    <span class="partner-flag">🇳🇱</span>
                    <h4>Belanda</h4>
                    <p>Inovasi Hortikultura Dataran Tinggi</p>
                </div>
                <div class="partner-card animate-on-scroll">
                    <span class="partner-flag">🇺🇸</span>
                    <h4>Amerika Serikat</h4>
                    <p>Pemetaan Genomik Plasma Nutfah</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════
         NEWS & ARTICLES SECTION
         ════════════════════════════════════ -->
    <section class="berita section-padding" id="berita">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8z"/></svg>
                    Publikasi &amp; Informasi
                </span>
                <h2 class="section-title">Berita &amp; Kegiatan TSTH2</h2>
                <p class="section-desc">Kabar terbaru seputar kunjungan kerja delegasi, hasil riset laboratorium, dan agenda strategis TSTH2.</p>
            </div>

            <div class="berita-grid">
                @foreach($homeBerita ?? [] as $item)
                <div class="news-card animate-on-scroll" style="cursor: pointer;" onclick="window.location.href='{{ route('berita') }}'">
                    <div class="news-img-wrapper">
                        <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : asset('assets/default_hero.jpg') }}" alt="{{ $item->title }}" class="news-img" loading="lazy">
                        <div class="news-img-overlay"></div>
                    </div>
                    <div class="news-content">
                        <span class="news-cat">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                            {{ $item->category ?? 'Informasi' }}
                        </span>
                        <h3>{{ Str::limit($item->title, 60) }}</h3>
                        <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 1rem; line-height: 1.6;">{{ Str::limit($item->description, 90) }}</p>
                        <div style="font-size: 0.85rem; font-weight: 700; color: #164E3F; display: flex; align-items: center; gap: 0.35rem;">
                            <span>Baca Selengkapnya</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div style="text-align: center; margin-top: 4rem;">
                <a href="{{ route('berita') }}" class="btn btn-outline" style="border-color: #164E3F; color: #164E3F; padding: 0.85rem 2.5rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/></svg>
                    <span>Lihat Arsip Publikasi</span>
                </a>
            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════
         GALLERY SECTION
         ════════════════════════════════════ -->
    <section class="galeri section-padding" id="galeri">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                    Arsip Visual
                </span>
                <h2 class="section-title">Galeri Dokumentasi Fasilitas</h2>
                <p class="section-desc">Dokumentasi resmi infrastruktur laboratorium, lahan budidaya ex-situ, dan kunjungan delegasi strategis.</p>
            </div>

            <div class="galeri-grid">
                @foreach($homeGalleries ?? [] as $item)
                @php
                    $photos = is_array($item->photos) ? $item->photos : [];
                    $coverPhoto = count($photos) > 0 ? asset('storage/' . $photos[0]) : asset('assets/default_hero.jpg');
                    $totalPhotos = count($photos);
                @endphp
                <div class="gallery-card animate-on-scroll" onclick="window.location.href='{{ route('galeri') }}'">
                    @if($totalPhotos > 1)
                    <span class="gallery-count">{{ $totalPhotos }} Foto</span>
                    @endif
                    <img src="{{ $coverPhoto }}" alt="{{ $item->title }}" loading="lazy">
                    <div class="gallery-overlay">
                        <h4 style="font-size: 1.05rem; color: white; margin-bottom: 0.3rem; font-weight: 700;">{{ Str::limit($item->title, 48) }}</h4>
                        <p style="font-size: 0.78rem; color: rgba(255,255,255,0.75); display: flex; align-items: center; gap: 0.3rem; margin:0;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 5 5L20 7"/></svg>
                            Klik untuk buka album
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

            <div style="text-align: center; margin-top: 4rem;">
                <a href="{{ route('galeri') }}" class="btn btn-outline" style="border-color: #164E3F; color: #164E3F; padding: 0.85rem 2.5rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                    <span>Buka Arsip Galeri Dokumentasi</span>
                </a>
            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════
         DIRECTOR / LEADERSHIP SECTION
         ════════════════════════════════════ -->
    @if(!empty(optional($konten['home_director_name'] ?? null)->value))
    <section class="director section-padding">
        <div class="container">
            <div class="director-container animate-on-scroll">
                <div class="director-img-wrapper">
                    <img src="{{ isset($konten['home_director']) && $konten['home_director']->image_path ? asset('storage/' . $konten['home_director']->image_path) : asset('assets/default_hero.jpg') }}" alt="Management TSTH2">
                </div>
                <div class="director-content">
                    <span class="section-tag" style="background: #f0fdf4; color: #164E3F; margin-bottom: 1.25rem;">Tata Kelola Institusi</span>
                    <h3>{{ $konten['home_director_name']->value ?? '' }}</h3>
                    <div class="director-title">{{ $konten['home_director_title']->value ?? '' }}</div>
                    <p>{{ $konten['home_director_desc']->value ?? '' }}</p>
                    <div class="director-quote">
                        {{ $konten['home_director_quote']->value ?? '' }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- ════════════════════════════════════
         RESEARCH DETAIL MODAL
         ════════════════════════════════════ -->
    <div class="modal-backdrop" id="detailModal" aria-hidden="true">
        <div class="modal-card">
            <img id="modalImg" src="" alt="Detail Image" class="modal-header-img">
            <div class="modal-body">
                <h3 id="modalTitle" class="modal-title"></h3>
                <p id="modalDesc" class="modal-desc"></p>
                <button class="btn btn-primary" id="modalCloseBtn" style="width: 100%; background: linear-gradient(135deg, #164E3F, #1f6b57);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    Tutup Spesifikasi
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('partials.footer')

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ── HEADER SCROLL EFFECT ──
            const header = document.getElementById('header');
            if (header) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 60) {
                        header.classList.add('scrolled');
                    } else {
                        header.classList.remove('scrolled');
                    }
                }, { passive: true });
            }

            // ── HERO SLIDER ──
            const slides = document.querySelectorAll('.slide');
            const dots = document.querySelectorAll('.slider-dot');
            let currentSlide = 0;
            const slideCount = slides.length;

            function goToSlide(n) {
                if (slideCount === 0) return;
                slides.forEach(s => s.classList.remove('active'));
                dots.forEach(d => d.classList.remove('active'));
                currentSlide = (n + slideCount) % slideCount;
                if (slides[currentSlide]) slides[currentSlide].classList.add('active');
                if (dots[currentSlide]) dots[currentSlide].classList.add('active');
            }

            if (slideCount > 1) {
                setInterval(() => goToSlide(currentSlide + 1), 7000);
            }

            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => goToSlide(index));
            });

            // ── HERO SMOOTH SCROLL ──
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const targetAttr = this.getAttribute('href');
                    if (targetAttr.length > 1) {
                        const target = document.querySelector(targetAttr);
                        if (target) {
                            e.preventDefault();
                            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    }
                });
            });

            // ── RESEARCH MODAL ──
            const modal = document.getElementById('detailModal');
            const modalImg = document.getElementById('modalImg');
            const modalTitle = document.getElementById('modalTitle');
            const modalDesc = document.getElementById('modalDesc');
            const modalCloseBtn = document.getElementById('modalCloseBtn');

            function closeModal() {
                if (modal) {
                    modal.classList.remove('active');
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                }
            }

            document.querySelectorAll('.btn-open-modal').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (modal) {
                        modalTitle.textContent = this.dataset.title || '';
                        modalDesc.textContent = this.dataset.desc || '';
                        modalImg.src = this.dataset.img || '';
                        modal.classList.add('active');
                        modal.setAttribute('aria-hidden', 'false');
                        document.body.style.overflow = 'hidden';
                    }
                });
            });

            if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeModal);

            if (modal) {
                modal.addEventListener('click', e => {
                    if (e.target === modal) closeModal();
                });
            }

            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && modal && modal.classList.contains('active')) {
                    closeModal();
                }
            });

            // ── ANIMATE ON SCROLL ──
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const siblings = Array.from(entry.target.parentElement.querySelectorAll('.animate-on-scroll'));
                        const idx = siblings.indexOf(entry.target);
                        const delay = idx >= 0 ? idx * 80 : 0;

                        setTimeout(() => {
                            entry.target.classList.add('visible');
                        }, delay);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.08, rootMargin: '0px 0px -60px 0px' });

            document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
        });
    </script>
</body>
</html>