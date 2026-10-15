<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Berita Terkini - TSTH2</title>
    @include('partials.pwa-head')

    <!-- Sembunyikan elemen Alpine sebelum dimuat -->
    <style>
        [x-cloak] { display: none !important; }
        .berita-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 280px), 1fr));
            gap: 1.5rem;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ filemtime(public_path('css/landing.css')) }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body style="background: #f8faf9;">
    @include('partials.navbar')

    <main style="padding-top: 80px;">
        <!-- Executive Hero Banner -->
        <section style="background: linear-gradient(135deg, #0d342a 0%, #164E3F 50%, #1c5e4d 100%); padding: 5.5rem 1rem; text-align: center; color: white; position: relative; overflow: hidden;">
            <div class="container" style="position: relative; z-index: 2;">
                <span class="hero-badge">Informasi & Kegiatan TSTH2</span>
                <h1 style="font-size: clamp(2.4rem, 4.5vw, 3.5rem); margin-bottom: 1rem; color: white; font-family: var(--font-heading, sans-serif); font-weight: 800;">
                    {{ $konten['berita_hero_title']->value ?? 'Berita & Artikel' }}
                </h1>
                <p style="font-size: 1.15rem; max-width: 750px; margin: 0 auto; color: rgba(241,245,249,0.92); line-height: 1.65;">
                    {{ $konten['berita_hero_desc']->value ?? 'Informasi terkini seputar kegiatan riset, program, dan perkembangan Taman Sains Teknologi Herbal dan Hortikultura Indonesia.' }}
                </p>
            </div>
        </section>

        <!-- Konten Area -->
        <section style="padding: 4rem 0;" x-data="{ open: false, activeItem: {} }">
            <div class="container">
                <!-- Search Bar -->
                <div style="max-width: 620px; margin: 0 auto 4rem auto;">
                    <form action="{{ route('berita') }}" method="GET" style="display: flex; gap: 0.5rem; background: #ffffff; padding: 0.5rem; border-radius: 999px; box-shadow: 0 10px 30px rgba(14, 52, 42, 0.08); border: 1px solid rgba(22, 78, 63, 0.12);">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita atau kata kunci..." style="flex: 1; padding: 0.85rem 1.5rem; border: none; outline: none; background: transparent; font-size: 1rem; color: #0f172a;">
                        <button type="submit" class="btn btn-primary" style="background: #164E3F; border-radius: 999px; display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.85rem 1.5rem;">
                            <span>Cari</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21 21-4.3-4.3"/><circle cx="11" cy="11" r="8"/></svg>
                        </button>
                    </form>
                </div>

                <!-- Berita Grid -->
               <!-- Berita Grid -->
<div class="berita-grid">
    @forelse ($berita as $item)
        @php
            $isGreen = $loop->iteration % 2 === 1;
            $cardBgClass = $isGreen ? 'poster-card-green' : 'poster-card-gold';
            $badgeLabel = $item->category ?? 'Informasi';
            $tanggal = $item->date ? \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') : '-';
            
            $itemArray = [
                'id' => $item->id ?? null,
                'title' => $item->title ?? '',
                'description' => $item->description ?? '',
                'category' => $badgeLabel,
                'image_path' => $item->image_path ?? null,
                'formatted_date' => $tanggal,
            ];
        @endphp
        
        <div class="poster-card {{ $cardBgClass }} animate-on-scroll"
             style="cursor: pointer; min-height: 380px;"
             @click="activeItem = @js($itemArray); open = true">

            <!-- Header: Logo TSTH2 & Badge Kategori -->
            <div class="poster-header">
                <div class="poster-logo">
                    <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo">
                    <div class="poster-logo-text">TSTH2<br>SCIENCE PARK</div>
                </div>
                <span class="poster-badge">{{ $badgeLabel }}</span>
            </div>

            <!-- Inner Frame -->
            <div class="poster-inner-frame">
                <!-- Gambar Berita -->
                <div class="poster-image-center">
                    <img src="{{ !empty($item->image_path) ? asset('storage/' . $item->image_path) : asset('assets/default_hero.jpg') }}"
                         alt="{{ $item->title }}" loading="lazy">
                </div>

                <!-- Teks -->
                <div style="margin-top: 0.75rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: white; line-height: 1.3; margin-bottom: 0.5rem; font-family: var(--font-heading, sans-serif);">
                        {{ Str::limit($item->title, 70) }}
                    </h3>
                    <p style="font-size: 0.85rem; color: rgba(255,255,255,0.82); line-height: 1.55; margin: 0;">
                        {{ Str::limit($item->description, 90) }}
                    </p>
                </div>

                <!-- CTA Pill -->
                <div class="poster-pill-tag" style="margin-top: 1rem; display: flex; align-items: center; gap: 0.4rem;">
                    <span>{{ $tanggal }}</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: white; border-radius: 1.5rem; color: #64748b;">
            <h3 style="color: #0f172a; margin-bottom: 0.5rem; font-weight: 800;">Belum Ada Berita</h3>
            <p>Informasi terbaru akan segera hadir di sini.</p>
        </div>
    @endforelse
</div>

<!-- Detail Modal -->
<div x-show="open"
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; background-color: rgba(0,0,0,0.75); backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; padding: 1.5rem;">
    
    <div style="background-color: white; border-radius: 1.25rem; width: 100%; max-width: 800px; max-height: 90vh; margin: auto; display: flex; flex-direction: column; box-shadow: 0 25px 60px -12px rgba(0,0,0,0.35); overflow: hidden;" @click.away="open = false">

                    <!-- Modal Header -->
                    <div style="padding: 1.25rem 1.75rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #164E3F, #1a6050);">
                        <div>
                            <span style="font-size: 0.78rem; font-weight: 800; color: #fef08a; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 0.2rem;" x-text="activeItem.category"></span>
                            <h3 style="font-size: 1.2rem; font-weight: 800; color: #ffffff; margin: 0; font-family: var(--font-heading, sans-serif); line-height: 1.3;" x-text="activeItem.title"></h3>
                        </div>
                        <button @click="open = false" type="button" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white; font-size: 1.4rem; font-weight: bold; cursor: pointer; line-height: 1; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">&times;</button>
                    </div>

                    <!-- Modal Body -->
                    <div style="padding: 1.75rem; overflow-y: auto; flex-grow: 1;">
                        <template x-if="activeItem.image_path">
                            <img :src="`/storage/${activeItem.image_path}`" style="width: 100%; border-radius: 0.75rem; margin-bottom: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); object-fit: contain; max-height: 380px; display: block; margin-left: auto; margin-right: auto;">
                        </template>
                        <template x-if="!activeItem.image_path">
                            <img src="{{ asset('assets/default_hero.jpg') }}" style="width: 100%; border-radius: 0.75rem; margin-bottom: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); object-fit: cover; max-height: 280px; display: block;">
                        </template>
                        <span style="color: #9C7A26; font-size: 0.88rem; font-weight: 700; margin-bottom: 1rem; display: block;" x-text="activeItem.formatted_date"></span>
                        <div style="color: #374151; line-height: 1.85; white-space: pre-wrap; font-size: 1.05rem; text-align: left;" x-text="activeItem.description"></div>
                    </div>

                    <!-- Modal Footer -->
                    <div style="padding: 1rem 1.75rem; border-top: 1px solid #e2e8f0; background: #f8faf9; text-align: right;">
                        <button @click="open = false" type="button" class="btn btn-primary" style="background: #164E3F; padding: 0.5rem 1.5rem; border-radius: 0.5rem; color: white; border: none; cursor: pointer;">Tutup</button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>