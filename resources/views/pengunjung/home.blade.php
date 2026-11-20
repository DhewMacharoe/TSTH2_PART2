<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="description"
        content="TSTH2 — Indonesian Herbal and Horticultural Science and Technology Park. National research center under Kemendiktisaintek & IT Del.">
    <title>TSTH2 — {{ __('site.home_hero_default_title') }}</title>
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
    @if (!empty($konten['home_hero_title']->value) || app()->getLocale() == 'en')
        <section class="hero" id="home">
            <div class="hero-slider">
                @forelse($homeBackgrounds ?? [] as $index => $bg)
                    <div class="slide {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $bg->image_path) }}" alt="TSTH2 Science Park Facility"
                            loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
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
                    <h1>{{ $konten['home_hero_title']->value ?? 'Inovasi Herbal & Hortikultura Indonesia' }}</h1>
                    <p>{{ $konten['home_hero_desc']->value ?? 'Pusat riset nasional yang diprakarsai oleh Pemerintah Indonesia di bawah Kementerian Pendidikan Tinggi, Sains, dan Teknologi, berkolaborasi dengan Institut Teknologi Del.' }}</p>
                </div>
            </div>

            <div class="slider-controls">
                @foreach ($homeBackgrounds ?? [] as $index => $bg)
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
         ABOUT SECTION
         ════════════════════════════════════ -->
    @if (!empty($konten['home_about_title']->value) || app()->getLocale() == 'en')
        <section id="about" class="about section-padding">
            <div class="container">
                <div class="about-grid">
                    <div class="about-text">
                        <h2>{{ $konten['home_about_title']->value ?? 'Apa Itu TSTH2?' }}</h2>
                        <p>
                            {{ $konten['home_about_desc']->value ?? 'TSTH2 (Indonesian Herbal and Horticultural Science and Technology Park) merupakan pusat riset nasional yang diprakarsai oleh Pemerintah Indonesia untuk mendorong inovasi herbal dan hortikultura di Sumatera Utara. Didirikan di bawah Kementerian Pendidikan Tinggi, Sains, dan Teknologi, dengan tata kelola berkolaborasi bersama Institut Teknologi Del.' }}
                        </p>

                        <div class="bullet-list">
                            @foreach ($aboutBullets ?? [] as $bullet)
                                <div class="bullet-item animate-on-scroll">
                                    <span class="bullet-icon-svg">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="3">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                    </span>
                                    <span>{{ $bullet->value ?? $bullet->content }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div style="margin-top: 2.5rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                            <a href="{{ route('profil-public') }}" class="btn btn-primary"
                                style="background: linear-gradient(135deg, #164E3F, #1f6b57);">
                                <span>{{ __('site.home_about_btn_profile') }}</span>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </a>
                            <a href="{{ route('penelitian') }}" class="btn btn-outline"
                                style="border-color: #9C7A26; color: #9C7A26;">
                                <span>{{ __('site.home_about_btn_research') }}</span>
                            </a>
                        </div>
                    </div>

                    <div class="about-image-wrapper">
                        <div class="about-image animate-on-scroll"
                            style="background-image: url('{{ isset($konten['home_about_bg']) && $konten['home_about_bg']->image_path ? asset('storage/' . $konten['home_about_bg']->image_path) : asset('assets/default_hero.jpg') }}');">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- ════════════════════════════════════
         DIRECTOR / LEADERSHIP SECTION
         ════════════════════════════════════ -->
    @if (!empty(optional($konten['home_director_name'] ?? null)->value))
        <section class="director section-padding">
            <div class="container">
                <div class="director-container animate-on-scroll">
                    <div class="director-img-wrapper">
                        <img src="{{ isset($konten['home_director']) && $konten['home_director']->image_path ? asset('storage/' . $konten['home_director']->image_path) : asset('assets/default_hero.jpg') }}"
                            alt="Management TSTH2">
                    </div>
                    <div class="director-content">
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
         RESEARCH / FACILITIES CARDS SECTION
         ════════════════════════════════════ -->
    @if (!empty($konten['home_penelitian_title']->value) || app()->getLocale() == 'en')
        <section class="penelitian-focus" id="penelitian">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">
                        {{ $konten['home_penelitian_title']->value ?? 'Fasilitas & Penelitian Utama' }}</h2>
                    <p class="section-desc">
                        {{ $konten['home_penelitian_desc']->value ?? 'Mengoptimalkan potensi keanekaragaman hayati nusantara melalui pendekatan riset terpadu dan fasilitas mutakhir.' }}</p>
                </div>

                <div class="penelitian-grid">
                    @foreach ($homePenelitianes ?? [] as $item)
                        @php
                            $isGreen = $loop->iteration % 2 === 1;
                            $cardClass = $isGreen ? 'executive-card-green' : 'executive-card-orange';
                            $tagLabels = [
                                'Laboratorium Riset',
                                'Bioteknologi',
                                'Genolab',
                                'Fitofarmaka',
                                'Plasma Nutfah',
                                'Distilasi',
                            ];
                            $tagIdx = ($loop->iteration - 1) % count($tagLabels);
                            $itemImg = !empty($item->image_path) ? asset('storage/' . $item->image_path) : asset('assets/default_hero.jpg');
                            $itemTag = !empty($item->field) ? $item->field : $tagLabels[$tagIdx];
                        @endphp
                        <div class="executive-card {{ $cardClass }} animate-on-scroll">
                            {{-- Faded Background Image & Gradient Overlay --}}
                            <div class="exec-card-bg" style="background-image: url('{{ $itemImg }}');"></div>
                            <div class="exec-card-overlay"></div>

                            <div class="exec-card-content">
                                <span class="exec-tag">{{ $itemTag }}</span>
                                <h3>{{ $item->title }}</h3>
                                <p>{{ Str::limit($item->description, 140) }}</p>
                            </div>
                            <div class="exec-card-footer">
                                <button class="btn-card-action btn-open-modal" data-title="{{ $item->title }}"
                                    data-desc="{{ $item->description }}"
                                    data-img="{{ $itemImg }}"
                                    data-url="{{ route('penelitian.show', $item->id) }}">
                                    <span>{{ __('site.view_detail') }}</span>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2.5">
                                        <path d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="text-align: center; margin-top: 4rem;">
                    <a href="{{ route('penelitian') }}" class="btn btn-outline"
                        style="border-color: #164E3F; color: #164E3F; padding: 0.85rem 2.5rem;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18" />
                        </svg>
                        <span>{{ __('site.home_research_all_btn') }}</span>
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
                <h2 class="section-title">Kolaborasi Riset Internasional</h2>
                <p class="section-desc">TSTH2 terus membangun jejaring dan kerja sama riset bersama perguruan tinggi terkemuka, lembaga penelitian internasional, dan mitra strategis global.</p>
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
                <h2 class="section-title">{{ $konten['home_news_title']->value ?? 'Berita Terkini' }}</h2>
                <p class="section-desc">{{ $konten['home_news_desc']->value ?? 'Kabar terbaru seputar kegiatan riset, kunjungan kerja delegasi, dan agenda strategis TSTH2.' }}</p>
            </div>

            <div class="berita-grid">
                @foreach ($homeBerita ?? [] as $item)
                    <div class="news-card animate-on-scroll" style="cursor: pointer;"
                        onclick="window.location.href='{{ route('berita') }}'">
                        <div class="news-img-wrapper">
                            <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : asset('assets/default_hero.jpg') }}"
                                alt="{{ $item->title }}" class="news-img" loading="lazy">
                            <div class="news-img-overlay"></div>
                        </div>
                        <div class="news-content">
                            <span class="news-cat">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor">
                                    <circle cx="12" cy="12" r="10" />
                                </svg>
                                {{ $item->category ?? 'Informasi' }}
                            </span>
                            <h3>{{ Str::limit($item->title, 60) }}</h3>
                            <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 1rem; line-height: 1.6;">
                                {{ Str::limit($item->description, 90) }}</p>
                            <div
                                style="font-size: 0.85rem; font-weight: 700; color: #164E3F; display: flex; align-items: center; gap: 0.35rem;">
                                <span>{{ __('site.read_more') }}</span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="text-align: center; margin-top: 4rem;">
                <a href="{{ route('berita') }}" class="btn btn-outline"
                    style="border-color: #164E3F; color: #164E3F; padding: 0.85rem 2.5rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path
                            d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2" />
                    </svg>
                    <span>{{ __('site.home_news_archive_btn') }}</span>
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
                <h2 class="section-title">{{ $konten['home_gallery_title']->value ?? 'Galeri Dokumentasi Fasilitas' }}</h2>
                <p class="section-desc">{{ $konten['home_gallery_desc']->value ?? 'Dokumentasi resmi infrastruktur laboratorium, lahan budidaya, dan kunjungan delegasi strategis.' }}</p>
            </div>

            <div class="galeri-grid">
                @foreach ($homeGalleries ?? [] as $item)
                    @php
                        $photos = is_array($item->photos) ? $item->photos : [];
                        $coverPhoto =
                            count($photos) > 0 ? asset('storage/' . $photos[0]) : asset('assets/default_hero.jpg');
                        $totalPhotos = count($photos);
                    @endphp
                    <div class="gallery-card animate-on-scroll"
                        onclick="window.location.href='{{ route('galeri') }}'">
                        @if ($totalPhotos > 1)
                            <span class="gallery-count">{{ $totalPhotos }} {{ __('site.photo') }}</span>
                        @endif
                        <img src="{{ $coverPhoto }}" alt="{{ $item->title }}" loading="lazy">
                        <div class="gallery-overlay">
                            <h4 style="font-size: 1.05rem; color: white; margin-bottom: 0.3rem; font-weight: 700;">
                                {{ Str::limit($item->title, 48) }}</h4>
                            <p
                                style="font-size: 0.78rem; color: rgba(255,255,255,0.75); display: flex; align-items: center; gap: 0.3rem; margin:0;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path d="m5 12 5 5L20 7" />
                                </svg>
                                {{ __('site.home_gallery_click_hint') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="text-align: center; margin-top: 4rem;">
                <a href="{{ route('galeri') }}" class="btn btn-outline"
                    style="border-color: #164E3F; color: #164E3F; padding: 0.85rem 2.5rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <circle cx="9" cy="9" r="2" />
                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                    </svg>
                    <span>{{ __('site.home_gallery_archive_btn') }}</span>
                </a>
            </div>
        </div>
    </section>



    <!-- ════════════════════════════════════
         RESEARCH DETAIL MODAL
         ════════════════════════════════════ -->
    <div class="modal-backdrop" id="detailModal" aria-hidden="true">
        <div class="modal-card">
            <button type="button" id="modalTopCloseBtn" aria-label="{{ __('site.close') }}"
                style="position: absolute; top: 1rem; right: 1rem; z-index: 10; width: 38px; height: 38px; border-radius: 50%; background: rgba(0, 0, 0, 0.6); color: #ffffff; border: none; font-size: 1.3rem; cursor: pointer; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); transition: all 0.2s;">&times;</button>
            <img id="modalImg" src="" alt="Detail Image" class="modal-header-img">
            <div class="modal-body">
                <h3 id="modalTitle" class="modal-title"></h3>
                <p id="modalDesc" class="modal-desc" style="white-space: pre-wrap;"></p>
                <div style="display: flex; flex-direction: column; gap: 0.65rem; margin-top: 1.5rem;">
                    <a id="modalDetailLink" href="#" class="btn btn-outline"
                        style="width: 100%; border-color: #164E3F; color: #164E3F; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; text-decoration: none; padding: 0.75rem 1rem; border-radius: 12px;">
                        <span>Buka Dossier Riset Lengkap</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </a>
                    <button class="btn btn-primary" id="modalCloseBtn"
                        style="width: 100%; background: linear-gradient(135deg, #164E3F, #1f6b57); border-radius: 12px; padding: 0.75rem 1rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M18 6 6 18M6 6l12 12" />
                        </svg>
                        {{ __('site.close') }}
                    </button>
                </div>
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
                }, {
                    passive: true
                });
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
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
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
            const modalTopCloseBtn = document.getElementById('modalTopCloseBtn');

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
                        const detailLink = document.getElementById('modalDetailLink');
                        if (detailLink) {
                            detailLink.href = this.dataset.url || '#';
                        }
                        modal.classList.add('active');
                        modal.setAttribute('aria-hidden', 'false');
                        document.body.style.overflow = 'hidden';
                    }
                });
            });

            if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeModal);
            if (modalTopCloseBtn) modalTopCloseBtn.addEventListener('click', closeModal);

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
                        const siblings = Array.from(entry.target.parentElement.querySelectorAll(
                            '.animate-on-scroll'));
                        const idx = siblings.indexOf(entry.target);
                        const delay = idx >= 0 ? idx * 80 : 0;

                        setTimeout(() => {
                            entry.target.classList.add('visible');
                        }, delay);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.08,
                rootMargin: '0px 0px -60px 0px'
            });

            document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
        });
    </script>
</body>

</html>
