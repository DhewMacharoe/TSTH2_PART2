<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ __('site.services_hero_title') }} - TSTH2</title>
    @include('partials.pwa-head')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ time() }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body style="background: #f8faf9;" x-data="{ openModal: false, modalData: {} }">
    @include('partials.navbar')

    <main style="padding-top: 110px;">
        <!-- Executive Hero Banner -->
        <section style="background: linear-gradient(135deg, #0d342a 0%, #164E3F 50%, #1c5e4d 100%); padding: 5.5rem 1rem; text-align: center; color: white; position: relative; overflow: hidden;">
            <div class="container" style="position: relative; z-index: 2;">
                <h1 style="font-size: clamp(2.4rem, 4.5vw, 3.5rem); margin-bottom: 1rem; color: white; font-family: var(--font-heading); font-weight: 800;">
                    Layanan &amp; Laboratorium TSTH2
                </h1>
                <p style="font-size: 1.15rem; max-width: 800px; margin: 0 auto; color: rgba(241,245,249,0.92); line-height: 1.65;">
                    Penyediaan sarana laboratorium genomik, distilasi minyak atsiri skala pilot, persemaian bibit unggul, serta program riset kolaboratif bagi akademisi, peneliti internasional, dan industri.
                </p>
            </div>
        </section>

        <!-- Layanan Utama Grid -->
        <section class="section-padding">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Katalog Layanan Riset TSTH2</h2>
                    <p class="section-desc">Layanan laboratorium terpadu dengan standar kepatuhan pengujian ilmiah dan dukungan sensorik IoT presisi.</p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2.25rem;">
                    <!-- Service 1 -->
                    <div class="poster-card poster-card-green" style="cursor: pointer;" @click="modalData = { title: 'Uji Genomik & Sekuensing DNA', desc: 'Fasilitas Genolab TSTH2 menyediakan analisis molekuler tingkat lanjut menggunakan alat PCR dan Next-Generation Sequencing (NGS). Layanan ini diperuntukkan bagi peneliti, akademisi, serta industri farmasi yang membutuhkan kepastian profil genetik tanaman obat.', code: 'ISO 17025 Certified' }; openModal = true">
                        <div class="poster-header">
                            <div class="poster-logo">
                                <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo">
                                <div class="poster-logo-text">TAMAN SAINS TEKNOLOGI HERBAL &amp;<br>HORTIKULTURA INDONESIA (TSTH2)</div>
                            </div>
                            <span class="poster-badge">Laboratorium Genomik</span>
                        </div>
                        <div class="poster-inner-frame">
                            <div>
                                <h3 style="font-size: 1.35rem; font-weight: 800; color: white; line-height: 1.25; margin-bottom: 0.6rem; font-family: var(--font-heading);">Uji Genomik & Sekuensing DNA</h3>
                                <p style="font-size: 0.88rem; color: rgba(255,255,255,0.85); line-height: 1.5; margin: 0;">Layanan sekuensing DNA, karakterisasi genetik plasma nutfah, dan pengujian keaslian varietas tanaman herbal serta hortikultura langka.</p>
                            </div>
                            <div class="poster-image-center">
                                <img src="{{ asset('assets/default_hero.jpg') }}" alt="Laboratorium Genomik" loading="lazy">
                            </div>
                            <div class="poster-pill-tag">
                                <span>Spesifikasi Layanan &rarr;</span>
                            </div>
                        </div>
                    </div>

                    <!-- Service 2 -->
                    <div class="poster-card poster-card-gold" style="cursor: pointer;" @click="modalData = { title: 'Ekstraksi Minyak Atsiri Presisi', desc: 'Sistem distilasi presisi kami mampu mengekstraksi minyak atsiri berstandar mutu ekspor. Kami melayani pendampingan UMKM dan pengujian sampel minyak atsiri dari berbagai biomassa lokal.', code: 'Pilot Scale Processing' }; openModal = true">
                        <div class="poster-header">
                            <div class="poster-logo">
                                <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo">
                                <div class="poster-logo-text">TAMAN SAINS TEKNOLOGI HERBAL &amp;<br>HORTIKULTURA INDONESIA (TSTH2)</div>
                            </div>
                            <span class="poster-badge">Distilasi Skala Pilot</span>
                        </div>
                        <div class="poster-inner-frame">
                            <div>
                                <h3 style="font-size: 1.35rem; font-weight: 800; color: white; line-height: 1.25; margin-bottom: 0.6rem; font-family: var(--font-heading);">Ekstraksi Minyak Atsiri Presisi</h3>
                                <p style="font-size: 0.88rem; color: rgba(255,255,255,0.85); line-height: 1.5; margin: 0;">Fasilitas distilasi uap skala pilot untuk pengolahan getah kemenyan, andaliman, dan tanaman aromatik menjadi essential oil bermutu tinggi.</p>
                            </div>
                            <div class="poster-image-center">
                                <img src="{{ asset('assets/default_hero.jpg') }}" alt="Distilasi Skala Pilot" loading="lazy">
                            </div>
                            <div class="poster-pill-tag">
                                <span>Spesifikasi Layanan &rarr;</span>
                            </div>
                        </div>
                    </div>

                    <!-- Service 3 -->
                    <div class="poster-card poster-card-green" style="cursor: pointer;" @click="modalData = { title: 'Smart Greenhouse Research Space', desc: 'Greenhouse presisi TSTH2 dilengkapi sensorik IoT mutakhir untuk mensimulasikan berbagai kondisi agroklimatologi. Cocok untuk uji coba varietas baru dan kultur jaringan skala riset.', code: 'Fully Automated IoT Control' }; openModal = true">
                        <div class="poster-header">
                            <div class="poster-logo">
                                <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo">
                                <div class="poster-logo-text">TAMAN SAINS TEKNOLOGI HERBAL &amp;<br>HORTIKULTURA INDONESIA (TSTH2)</div>
                            </div>
                            <span class="poster-badge">Teknologi IoT Presisi</span>
                        </div>
                        <div class="poster-inner-frame">
                            <div>
                                <h3 style="font-size: 1.35rem; font-weight: 800; color: white; line-height: 1.25; margin-bottom: 0.6rem; font-family: var(--font-heading);">Smart Greenhouse Research Space</h3>
                                <p style="font-size: 0.88rem; color: rgba(255,255,255,0.85); line-height: 1.5; margin: 0;">Penyewaan space riset dan pengujian tanaman dalam lingkungan mikro terkontrol IoT (suhu, kelembaban, nutrisi AB mix otomatis).</p>
                            </div>
                            <div class="poster-image-center">
                                <img src="{{ asset('assets/default_hero.jpg') }}" alt="Smart Greenhouse" loading="lazy">
                            </div>
                            <div class="poster-pill-tag">
                                <span>Spesifikasi Layanan &rarr;</span>
                            </div>
                        </div>
                    </div>

                    <!-- Service 4 -->
                    <div class="poster-card poster-card-gold" style="cursor: pointer;" @click="modalData = { title: 'Persemaian & Sertifikasi Bibit', desc: 'Program kultur jaringan kami memastikan perbanyakan bibit tanaman unggul secara cepat, seragam, dan terbebas dari virus. Tersedia paket pendistribusian bibit untuk kemitraan pertanian.', code: 'Certified Seed Distribution' }; openModal = true">
                        <div class="poster-header">
                            <div class="poster-logo">
                                <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo">
                                <div class="poster-logo-text">TAMAN SAINS TEKNOLOGI HERBAL &amp;<br>HORTIKULTURA INDONESIA (TSTH2)</div>
                            </div>
                            <span class="poster-badge">Kultur Jaringan</span>
                        </div>
                        <div class="poster-inner-frame">
                            <div>
                                <h3 style="font-size: 1.35rem; font-weight: 800; color: white; line-height: 1.25; margin-bottom: 0.6rem; font-family: var(--font-heading);">Persemaian & Sertifikasi Bibit</h3>
                                <p style="font-size: 0.88rem; color: rgba(255,255,255,0.85); line-height: 1.5; margin: 0;">Penyediaan bibit bawang putih, kentang aeroponik, dan tanaman obat bersertifikat bebas penyakit untuk kemitraan pertanian.</p>
                            </div>
                            <div class="poster-image-center">
                                <img src="{{ asset('assets/default_hero.jpg') }}" alt="Persemaian Bibit" loading="lazy">
                            </div>
                            <div class="poster-pill-tag">
                                <span>Spesifikasi Layanan &rarr;</span>
                            </div>
                        </div>
                    </div>

                    <!-- Service 5 -->
                    <div class="poster-card poster-card-green" style="cursor: pointer;" @click="modalData = { title: 'Magang Riset & Visiting Scholar', desc: 'TSTH2 membuka pintu kolaborasi bagi mahasiswa, dosen, dan professor internasional untuk melakukan penelitian lapangan, magang industri, maupun kunjungan edukasi fasilitas botani.', code: 'Academic & Industry Exchange' }; openModal = true">
                        <div class="poster-header">
                            <div class="poster-logo">
                                <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo">
                                <div class="poster-logo-text">TAMAN SAINS TEKNOLOGI HERBAL &amp;<br>HORTIKULTURA INDONESIA (TSTH2)</div>
                            </div>
                            <span class="poster-badge">Pertukaran Akademisi</span>
                        </div>
                        <div class="poster-inner-frame">
                            <div>
                                <h3 style="font-size: 1.35rem; font-weight: 800; color: white; line-height: 1.25; margin-bottom: 0.6rem; font-family: var(--font-heading);">Magang Riset & Visiting Scholar</h3>
                                <p style="font-size: 0.88rem; color: rgba(255,255,255,0.85); line-height: 1.5; margin: 0;">Program edukasi botani, tur laboratorium, serta penerimaan mahasiswa & peneliti untuk magang riset hortikultura.</p>
                            </div>
                            <div class="poster-image-center">
                                <img src="{{ asset('assets/default_hero.jpg') }}" alt="Magang Riset" loading="lazy">
                            </div>
                            <div class="poster-pill-tag">
                                <span>Spesifikasi Layanan &rarr;</span>
                            </div>
                        </div>
                    </div>

                    <!-- Service 6 -->
                    <div class="poster-card poster-card-gold" style="cursor: pointer;" @click="modalData = { title: 'Analisis Mutu & Fitofarmaka', desc: 'Fasilitas pengujian parameter mutu fitofarmaka memastikan bahwa bahan alam yang diteliti memenuhi kriteria keamanan dan efikasi obat tradisional asli Indonesia.', code: 'Standardization & Assay' }; openModal = true">
                        <div class="poster-header">
                            <div class="poster-logo">
                                <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo">
                                <div class="poster-logo-text">TAMAN SAINS TEKNOLOGI HERBAL &amp;<br>HORTIKULTURA INDONESIA (TSTH2)</div>
                            </div>
                            <span class="poster-badge">Analisis Fitofarmaka</span>
                        </div>
                        <div class="poster-inner-frame">
                            <div>
                                <h3 style="font-size: 1.35rem; font-weight: 800; color: white; line-height: 1.25; margin-bottom: 0.6rem; font-family: var(--font-heading);">Analisis Mutu & Fitofarmaka</h3>
                                <p style="font-size: 0.88rem; color: rgba(255,255,255,0.85); line-height: 1.5; margin: 0;">Pengujian standar kualitas simplisia, kandungan antioksidan, serta analisis kadar bahan aktif tanaman herbal sebelum diproduksi masif.</p>
                            </div>
                            <div class="poster-image-center">
                                <img src="{{ asset('assets/default_hero.jpg') }}" alt="Analisis Mutu" loading="lazy">
                            </div>
                            <div class="poster-pill-tag">
                                <span>Spesifikasi Layanan &rarr;</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Layanan Modal -->
        <div x-show="openModal" style="position: fixed; inset: 0; z-index: 10000; background: rgba(14, 52, 42, 0.8); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; padding: 1.5rem;" x-cloak>
            <div style="background: #ffffff; border-radius: 1.5rem; max-width: 620px; width: 100%; padding: 2.5rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35); position: relative;" @click.away="openModal = false">
                <span style="display: inline-block; font-size: 0.75rem; font-weight: 800; background: #f0fdf4; color: #164E3F; border: 1px solid rgba(22, 78, 63, 0.2); padding: 0.25rem 0.75rem; border-radius: 4px; margin-bottom: 1rem;" x-text="modalData.code"></span>
                <h3 style="font-size: 1.75rem; color: #0f172a; margin-bottom: 1rem; font-weight: 800;" x-text="modalData.title"></h3>
                <p style="font-size: 1.05rem; line-height: 1.7; color: #475569; margin-bottom: 2rem;" x-text="modalData.desc"></p>
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <a href="mailto:info@tsth2.id" class="btn btn-primary" style="background: #164E3F;">Ajukan Permohonan Resmi</a>
                    <button @click="openModal = false" class="btn btn-outline" style="border-color: #cbd5e1; color: #475569;">Tutup</button>
                </div>
            </div>
        </div>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
