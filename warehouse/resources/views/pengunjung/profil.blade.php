<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ __('site.nav_profile') }} - TSTH2</title>
    @include('partials.pwa-head')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ time() }}">
    <!-- Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body style="background: #f8faf9;">
    @include('partials.navbar')

    <main>
        <!-- Executive Hero Banner -->
        <section style="background: linear-gradient(135deg, #0d342a 0%, #164E3F 50%, #1c5e4d 100%); padding: 5.5rem 1rem; text-align: center; color: white; position: relative; overflow: hidden;">
            <div class="container" style="position: relative; z-index: 2;">
                <h1 style="font-size: clamp(2.4rem, 4.5vw, 3.5rem); margin-bottom: 1rem; color: white; font-family: var(--font-heading); font-weight: 800;">
                    Profil TSTH2
                </h1>
                <p style="font-size: 1.15rem; max-width: 820px; margin: 0 auto; color: rgba(241,245,249,0.92); line-height: 1.7;">
                    Pusat riset nasional yang diprakarsai oleh Pemerintah Indonesia untuk inovasi herbal dan hortikultura di Sumatera Utara di bawah Kementerian Pendidikan Tinggi, Sains, dan Teknologi bekerja sama dengan Institut Teknologi Del.
                </p>
            </div>
        </section>

        <!-- Vision & Mission Section -->
        <section class="section-padding">
            <div class="container">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 460px), 1fr)); gap: 2rem; align-items: stretch; max-width: 1200px; margin: 0 auto;">
                    
                    <!-- VISION CARD -->
                    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; border-left: 5px solid #004d40; padding: 2.5rem 2.25rem; box-shadow: 0 4px 20px rgba(0,0,0,0.03); display: flex; flex-direction: column;">
                        <h2 style="color: #004d40; font-size: 1.5rem; font-weight: 800; font-family: var(--font-heading, sans-serif); letter-spacing: 0.5px; text-transform: uppercase; margin: 0 0 1.5rem 0;">
                            VISI
                        </h2>
                        <p style="color: #475569; font-size: 1.05rem; line-height: 1.75; margin: 0;">
                            Menjadi pusat riset herbal dan hortikultura terkemuka di Indonesia yang menghasilkan inovasi dan teknologi berkelanjutan, berdaya saing global, serta memberikan dampak sosial yang nyata.
                        </p>
                    </div>

                    <!-- MISSION CARD -->
                    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; border-left: 5px solid #004d40; padding: 2.5rem 2.25rem; box-shadow: 0 4px 20px rgba(0,0,0,0.03); display: flex; flex-direction: column;">
                        <h2 style="color: #004d40; font-size: 1.5rem; font-weight: 800; font-family: var(--font-heading, sans-serif); letter-spacing: 0.5px; text-transform: uppercase; margin: 0 0 1.5rem 0;">
                            MISI
                        </h2>
                        
                        <div style="display: flex; flex-direction: column;">
                            @php
                                $missionList = [
                                    'Memajukan inovasi herbal dan hortikultura melalui pengembangan sumber daya genetik unggul',
                                    'Menghasilkan produk herbal dan hortikultura berkualitas tinggi',
                                    'Memperkuat kolaborasi multipihak yang terpadu',
                                    'Memberikan manfaat berkelanjutan bagi masyarakat dan lingkungan',
                                    'Mendorong inovasi yang berdampak positif bagi masyarakat dan lingkungan',
                                ];
                            @endphp

                            @foreach($missionList as $item)
                            <div style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.85rem 0; {{ !$loop->last ? 'border-bottom: 1px solid #f1f5f9;' : '' }}">
                                <span class="material-symbols-rounded" style="color: #0d9488; font-size: 1.25rem; line-height: 1.4; flex-shrink: 0;">check</span>
                                <span style="color: #475569; font-size: 0.98rem; line-height: 1.6;">
                                    {{ $item }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Leadership Section: Direktur -->
        @if(!empty($konten['home_director_name']->value))
        <section class="director section-padding">
            <div class="container director-container animate-on-scroll">
                <div class="director-img-wrapper">
                    <img src="{{ isset($konten['home_director']) && $konten['home_director']->image_path ? asset('storage/' . $konten['home_director']->image_path) : asset('assets/default_hero.jpg') }}" alt="Direktur TSTH2">
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
        </section>
        @endif

        <!-- Leadership Section: Wakil Direktur -->
        @if(!empty(optional($konten['home_vice_director_name'] ?? null)->value))
        <section class="director section-padding" style="padding-top: 0;">
            <div class="container director-container animate-on-scroll" style="flex-direction: row-reverse;">
                <div class="director-img-wrapper">
                    <img src="{{ isset($konten['home_vice_director']) && $konten['home_vice_director']->image_path ? asset('storage/' . $konten['home_vice_director']->image_path) : asset('assets/default_hero.jpg') }}" alt="Wakil Direktur TSTH2">
                </div>
                <div class="director-content">
                    <h3>{{ $konten['home_vice_director_name']->value ?? '' }}</h3>
                    <div class="director-title">{{ $konten['home_vice_director_title']->value ?? '' }}</div>
                    <p>{{ $konten['home_vice_director_desc']->value ?? '' }}</p>
                    @if(!empty(optional($konten['home_vice_director_quote'] ?? null)->value))
                    <div class="director-quote">
                        {{ $konten['home_vice_director_quote']->value ?? '' }}
                    </div>
                    @endif
                </div>
            </div>
        </section>
        @endif
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
