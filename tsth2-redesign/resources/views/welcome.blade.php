<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSTH2 - Taman Sains Teknologi Herbal dan Hortikultura</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>

    <!-- Navigation -->
    @include('partials.navbar')

    <!-- Hero Section -->
    @if(!empty($contents['home_hero_title']->value))
    <section class="hero" id="home">
        <div class="hero-slider">
            @forelse($homeBackgrounds ?? [] as $index => $bg)
            <div class="slide {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ asset('storage/' . $bg->image_path) }}" alt="TSTH2 Background">
                <div class="slide-overlay"></div>
            </div>
            @empty
            <div class="slide active">
                <img src="{{ asset('assets/default_hero.jpg') }}" alt="Default Background">
                <div class="slide-overlay"></div>
            </div>
            @endforelse
        </div>

        <div class="container">
            <div class="hero-content">
                <span class="badge">{{ $contents['home_hero_badge']->value ?? '' }}</span>
                <h1>{{ $contents['home_hero_title']->value ?? '' }}</h1>
                <p>{{ $contents['home_hero_desc']->value ?? '' }}</p>
                <div class="hero-actions">
                    <a href="#about" class="btn btn-primary">Discover More</a>
                    <a href="{{ route('katalog') }}" class="btn btn-glass" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); padding: 0.75rem 1.5rem; border-radius: 999px; color: white;">Lihat Katalog</a>
                </div>
            </div>
        </div>

        <div class="slider-controls">
            @foreach($homeBackgrounds ?? [] as $index => $bg)
            <div class="slider-dot {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"></div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- About Section -->
    @if(!empty($contents['home_about_title']->value))
    <section id="about" class="about section-padding">
        <div class="container">
            <div class="about-grid chunk-card">
                <div class="about-text">
                    <h2>{{ $contents['home_about_title']->value ?? '' }}</h2>
                    <p>
                        {{ $contents['home_about_desc']->value ?? '' }}
                    </p>
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 1rem; color: var(--color-text-dark); padding-top: 1rem;">
                        @foreach($aboutBullets as $bullet)
                        <li>✅ {{ $bullet->content }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="about-image" style="background-image: url('{{ isset($contents['home_about_bg']) && $contents['home_about_bg']->image_path ? asset('storage/' . $contents['home_about_bg']->image_path) : '' }}'); background-size: cover; background-position: center; min-height: 400px; border-radius: 1rem;">
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Features/Research Section -->
    @if(!empty($contents['home_research_title']->value))
    <section class="research-focus section-padding" id="research">
        <div class="container">
            <h2 class="section-title text-center animate-on-scroll">{{ $contents['home_research_title']->value ?? '' }}</h2>
            <p style="text-align: center; color: var(--color-text-muted); max-width: 600px; margin: 0 auto 3rem auto;" class="animate-on-scroll">{{ $contents['home_research_desc']->value ?? '' }}</p>
            
            <div class="research-grid">
                @foreach($homeResearches as $item)
                <div class="research-card chunk-card animate-on-scroll" style="background: {{ $loop->iteration % 2 == 1 ? '#164E3F' : '#9C7A26' }}; color: white; border: none;">
                    <h3 style="color: white;">{{ $item->title }}</h3>
                    <p style="color: rgba(255,255,255,0.9);">{{ Str::limit($item->description, 100) }}</p>
                    <button class="btn-lihat-detail" style="margin-top: 1rem; background: none; border: none; color: #f1c40f; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;" data-img-src="{{ $item->image_path ? asset('storage/' . $item->image_path) : '' }}" data-title="{{ $item->title }}" data-desc="{{ $item->description }}">Lihat Detail <span style="font-size: 1.2rem;">&rarr;</span></button>
                </div>
                @endforeach
            </div>
            <div style="text-align: center; margin-top: 2rem;">
                <a href="{{ route('research') }}" class="btn btn-outline" style="border-color: #164E3F; color: #164E3F;">Lihat Semua Riset</a>
            </div>
        </div>
    </section>
    @endif

    <!-- Programs Section -->
    <section class="programs section-padding" style="background: white;" id="programs">
        <div class="container">
            <h2 class="section-title text-center animate-on-scroll">Program Unggulan</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 3rem;">
                @foreach($homePrograms as $item)
                <div class="chunk-card animate-on-scroll" style="background: {{ $loop->iteration % 2 == 1 ? '#164E3F' : '#9C7A26' }}; color: white; border: none; padding: 2rem;">
                    <h3 style="font-family: var(--font-heading); margin-bottom: 1rem; color: white;">{{ $item->title }}</h3>
                    <p style="color: rgba(255,255,255,0.9); line-height: 1.6;">{{ Str::limit($item->description, 100) }}</p>
                </div>
                @endforeach
            </div>
            <div style="text-align: center; margin-top: 2rem;">
                <a href="{{ route('program') }}" class="btn btn-outline" style="border-color: #164E3F; color: #164E3F;">Lihat Semua Program</a>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section class="news section-padding" id="news">
        <div class="container">
            <h2 class="section-title text-center animate-on-scroll">Berita Terbaru</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem; margin-top: 3rem;">
                @foreach($homeNews as $item)
                <div class="animate-on-scroll" style="background: {{ $loop->iteration % 2 == 1 ? '#164E3F' : '#9C7A26' }}; color: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.3s;">
                    <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : asset('assets/default_hero.jpg') }}" alt="{{ $item->title }}" style="width: 100%; height: 180px; object-fit: cover;">
                    <div style="padding: 1.5rem;">
                        <span style="font-size: 0.8rem; color: #f1c40f; font-weight: 600; text-transform: uppercase;">{{ $item->category }}</span>
                        <h3 style="font-family: var(--font-heading); margin: 0.5rem 0; color: white; font-size: 1.1rem;">{{ Str::limit($item->title, 50) }}</h3>
                        <p style="color: rgba(255,255,255,0.9); font-size: 0.9rem; margin-bottom: 1rem;">{{ Str::limit($item->description, 80) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div style="text-align: center; margin-top: 2rem;">
                <a href="{{ route('news') }}" class="btn btn-outline" style="border-color: #164E3F; color: #164E3F;">Lihat Semua Berita</a>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery section-padding" style="background: white;" id="gallery">
        <div class="container">
            <h2 class="section-title text-center animate-on-scroll">Galeri Dokumentasi</h2>
            <div style="display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center; margin-top: 3rem;">
                @foreach($homeGalleries as $item)
                <div class="animate-on-scroll" style="width: 280px; background: {{ $loop->iteration % 2 == 1 ? '#164E3F' : '#9C7A26' }}; padding: 15px; border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; box-shadow: 2px 2px 5px rgba(0,0,0,0.1);">
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" style="width: 100%; height: 150px; border-bottom: 2px solid rgba(255,255,255,0.2); border-radius: 8px; margin-bottom: 10px; object-fit: cover;">
                    <h3 style="color: white; font-size: 1rem; text-align: center; margin: 0; padding-top: 5px;">{{ Str::limit($item->title, 40) }}</h3>
                </div>
                @endforeach
            </div>
            <div style="text-align: center; margin-top: 2rem;">
                <a href="{{ route('gallery') }}" class="btn btn-outline" style="border-color: #164E3F; color: #164E3F;">Lihat Semua Galeri</a>
            </div>
        </div>
    </section>

    <!-- Director Section -->
    @if(!empty($contents['home_director_name']->value))
    <section class="director section-padding">
        <div class="container director-container chunk-card animate-on-scroll" style="display: flex; gap: 2rem; align-items: center;">
            <div class="director-img-wrapper" style="flex: 1;">
                <img src="{{ isset($contents['home_director']) && $contents['home_director']->image_path ? asset('storage/' . $contents['home_director']->image_path) : '' }}" alt="Director Sri Fatmawati" style="width: 100%; border-radius: 1rem; object-fit: cover; aspect-ratio: 4/5;">
            </div>
            <div class="director-content" style="flex: 2;">
                <h3>{{ $contents['home_director_name']->value ?? '' }}</h3>
                <div class="director-title" style="color: var(--color-accent); font-weight: bold; margin-bottom: 1rem;">{{ $contents['home_director_title']->value ?? '' }}</div>
                <p>{{ $contents['home_director_desc']->value ?? '' }}</p>
                <div class="director-quote" style="margin-top: 1rem; font-style: italic; border-left: 4px solid var(--color-accent); padding-left: 1rem;">
                    {{ $contents['home_director_quote']->value ?? '' }}
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Footer -->
    @include('partials.footer')

    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
