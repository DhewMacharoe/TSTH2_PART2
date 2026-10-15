<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Profil & Tata Kelola - TSTH2</title>
    @include('partials.pwa-head')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ time() }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body style="background: #f8faf9;">
    @include('partials.navbar')

    <main style="padding-top: 80px;">
        <!-- Executive Hero Banner -->
        <section style="background: linear-gradient(135deg, #0d342a 0%, #164E3F 50%, #1c5e4d 100%); padding: 5.5rem 1rem; text-align: center; color: white; position: relative; overflow: hidden;">
            <div class="container" style="position: relative; z-index: 2;">
                <h1 style="font-size: clamp(2.4rem, 4.5vw, 3.5rem); margin-bottom: 1rem; color: white; font-family: var(--font-heading); font-weight: 800;">
                    Profil & Tata Kelola TSTH2
                </h1>
                <p style="font-size: 1.15rem; max-width: 800px; margin: 0 auto; color: rgba(241,245,249,0.92); line-height: 1.65;">
                    Pusat riset nasional yang diprakarsai oleh Pemerintah Indonesia untuk inovasi herbal dan hortikultura di Sumatera Utara di bawah Kementerian Pendidikan Tinggi, Sains, dan Teknologi bekerja sama dengan Institut Teknologi Del.
                </p>
            </div>
        </section>

        <!-- Governance Card Summary -->
        <section class="section-padding">
            <div class="container">
                <div style="background: #ffffff; border-radius: 1.5rem; padding: 3rem; box-shadow: 0 16px 40px rgba(14, 52, 42, 0.08); border: 1px solid rgba(22, 78, 63, 0.12); margin-bottom: 4rem;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2.5rem; align-items: center;">
                        <div>
                            <span class="section-tag">Governance & Authority</span>
                            <h2 style="font-size: 1.85rem; color: #0f172a; margin-bottom: 1rem; font-weight: 800;">Prakarsa Pemerintah RI</h2>
                            <p style="color: #475569; font-size: 1.05rem; line-height: 1.7; margin-bottom: 1.5rem;">
                                TSTH2 didirikan sebagai bukti komitmen Pemerintah Indonesia dalam mengoptimalkan keanekaragaman hayati nusantara. Tata kelola operasional dan pengembangan akademik riset dijalankan secara kolaboratif bersama **Institut Teknologi Del**.
                            </p>
                        </div>
                        <div style="background: #f0fdf4; border-radius: 1.25rem; padding: 2rem; border: 1px solid rgba(22, 78, 63, 0.18);">
                            <h4 style="font-size: 1.1rem; color: #164E3F; margin-bottom: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                Kerangka Kelembagaan
                            </h4>
                            <ul style="display: flex; flex-direction: column; gap: 0.85rem; font-size: 0.95rem; color: #1e293b; font-weight: 600;">
                                <li style="display: flex; align-items: center; gap: 0.5rem;">✓ Kemendiktisaintek Republik Indonesia</li>
                                <li style="display: flex; align-items: center; gap: 0.5rem;">✓ Institut Teknologi Del (IT Del)</li>
                                <li style="display: flex; align-items: center; gap: 0.5rem;">✓ Standar Akreditasi ISO 17025</li>
                                <li style="display: flex; align-items: center; gap: 0.5rem;">✓ Kemitraan Profesor & Peneliti Global</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Vision Banner -->
                <div class="section-header">
                    <span class="section-tag">Landasan Strategis</span>
                    <h2 class="section-title">Visi & Misi TSTH2</h2>
                    <p class="section-desc">Pilar panduan seluruh kegiatan riset, konservasi genetik, dan hilirisasi produk herbal & hortikultura nasional.</p>
                </div>

                <div class="executive-card executive-card-green" style="margin-bottom: 3rem; text-align: center; padding: 3rem 2.5rem; background: #ffffff;">
                    <div>
                        <div class="exec-icon-box" style="margin: 0 auto 1.25rem auto; background: #f0fdf4; color: #164E3F;">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="m16 10-4 4-2-2"/></svg>
                        </div>
                        <span class="exec-tag">Official Vision Statement</span>
                        <h3 style="font-size: 1.75rem; margin-bottom: 1rem; color: #0f172a;">Visi TSTH2</h3>
                        <p style="font-size: 1.15rem; max-width: 860px; margin: 0 auto; color: #334155; line-height: 1.7; font-weight: 500;">
                            "To become a leading herbal and horticultural research center in Indonesia that produces globally competitive, sustainable innovations and technologies with social impact."
                        </p>
                        <p style="font-size: 0.95rem; color: #64748b; margin-top: 0.5rem; font-style: italic;">
                            (Menjadi pusat riset herbal dan hortikultura terkemuka di Indonesia yang menghasilkan inovasi dan teknologi berkelanjutan, berdaya saing global, serta memberikan dampak sosial yang nyata.)
                        </p>
                    </div>
                </div>

                <!-- 5 Executive Mission Cards -->
                <h3 style="text-align: center; margin-bottom: 2rem; color: #0f172a; font-size: 1.6rem; font-weight: 800;">Misi Strategis Institusi</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                    <!-- Misi 1 -->
                    <div class="poster-card poster-card-green">
                        <div class="poster-header">
                            <div class="poster-logo">
                                <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo">
                                <div class="poster-logo-text">TSTH2 SCIENCE PARK</div>
                            </div>
                            <span class="poster-badge">Misi 01</span>
                        </div>
                        <div class="poster-inner-frame">
                            <div>
                                <h3 style="font-size: 1.35rem; font-weight: 800; color: white; line-height: 1.25; margin-bottom: 0.6rem; font-family: var(--font-heading);">Inovasi Sumber Daya Genetik</h3>
                                <p style="font-size: 0.88rem; color: rgba(255,255,255,0.85); line-height: 1.5; margin: 0;">To advance herbal and horticultural innovation by developing superior genetic resources.</p>
                            </div>
                            <div class="poster-pill-tag">
                                <span>Pengembangan Varietas Unggul</span>
                            </div>
                        </div>
                    </div>

                    <!-- Misi 2 -->
                    <div class="poster-card poster-card-gold">
                        <div class="poster-header">
                            <div class="poster-logo">
                                <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo">
                                <div class="poster-logo-text">TSTH2 SCIENCE PARK</div>
                            </div>
                            <span class="poster-badge">Misi 02</span>
                        </div>
                        <div class="poster-inner-frame">
                            <div>
                                <h3 style="font-size: 1.35rem; font-weight: 800; color: white; line-height: 1.25; margin-bottom: 0.6rem; font-family: var(--font-heading);">Produk Mutu Tinggi</h3>
                                <p style="font-size: 0.88rem; color: rgba(255,255,255,0.85); line-height: 1.5; margin: 0;">Producing high-quality herbal and horticultural products for global competitiveness.</p>
                            </div>
                            <div class="poster-pill-tag">
                                <span>Standar Kualitas Ekspor</span>
                            </div>
                        </div>
                    </div>

                    <!-- Misi 3 -->
                    <div class="poster-card poster-card-green">
                        <div class="poster-header">
                            <div class="poster-logo">
                                <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo">
                                <div class="poster-logo-text">TSTH2 SCIENCE PARK</div>
                            </div>
                            <span class="poster-badge">Misi 03</span>
                        </div>
                        <div class="poster-inner-frame">
                            <div>
                                <h3 style="font-size: 1.35rem; font-weight: 800; color: white; line-height: 1.25; margin-bottom: 0.6rem; font-family: var(--font-heading);">Multi-Stakeholder Collaboration</h3>
                                <p style="font-size: 0.88rem; color: rgba(255,255,255,0.85); line-height: 1.5; margin: 0;">Strengthening multi-stakeholder collaboration among government, academia, and industry.</p>
                            </div>
                            <div class="poster-pill-tag">
                                <span>Kemitraan Lintas Sektor</span>
                            </div>
                        </div>
                    </div>

                    <!-- Misi 4 -->
                    <div class="poster-card poster-card-gold">
                        <div class="poster-header">
                            <div class="poster-logo">
                                <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo">
                                <div class="poster-logo-text">TSTH2 SCIENCE PARK</div>
                            </div>
                            <span class="poster-badge">Misi 04</span>
                        </div>
                        <div class="poster-inner-frame">
                            <div>
                                <h3 style="font-size: 1.35rem; font-weight: 800; color: white; line-height: 1.25; margin-bottom: 0.6rem; font-family: var(--font-heading);">Sustainable Benefits</h3>
                                <p style="font-size: 0.88rem; color: rgba(255,255,255,0.85); line-height: 1.5; margin: 0;">Delivering sustainable benefits to society and the environment through scientific advancements.</p>
                            </div>
                            <div class="poster-pill-tag">
                                <span>Keberlanjutan Lingkungan</span>
                            </div>
                        </div>
                    </div>

                    <!-- Misi 5 -->
                    <div class="poster-card poster-card-green">
                        <div class="poster-header">
                            <div class="poster-logo">
                                <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo">
                                <div class="poster-logo-text">TSTH2 SCIENCE PARK</div>
                            </div>
                            <span class="poster-badge">Misi 05</span>
                        </div>
                        <div class="poster-inner-frame">
                            <div>
                                <h3 style="font-size: 1.35rem; font-weight: 800; color: white; line-height: 1.25; margin-bottom: 0.6rem; font-family: var(--font-heading);">Social Impact Innovation</h3>
                                <p style="font-size: 0.88rem; color: rgba(255,255,255,0.85); line-height: 1.5; margin: 0;">Promote innovations that positively impact society, farmer welfare, and environmental conservation.</p>
                            </div>
                            <div class="poster-pill-tag">
                                <span>Dampak Sosial Nyata</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Leadership Section -->
        @if(!empty($konten['home_director_name']->value))
        <section class="director section-padding">
            <div class="container director-container animate-on-scroll">
                <div class="director-img-wrapper">
                    <img src="{{ isset($konten['home_director']) && $konten['home_director']->image_path ? asset('storage/' . $konten['home_director']->image_path) : asset('assets/default_hero.jpg') }}" alt="Management TSTH2">
                </div>
                <div class="director-content">
                    <span class="section-tag">Jajaran Kepemimpinan</span>
                    <h3>{{ $konten['home_director_name']->value ?? '' }}</h3>
                    <div class="director-title">{{ $konten['home_director_title']->value ?? '' }}</div>
                    <p>{{ $konten['home_director_desc']->value ?? '' }}</p>
                    <div class="director-quote">
                        {{ $konten['home_director_quote']->value ?? '' }}
                    </div>
                </div>
            </div>
        </section>
        @endif
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
