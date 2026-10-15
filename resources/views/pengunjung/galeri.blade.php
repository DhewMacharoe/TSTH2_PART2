<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Galeri Foto - TSTH2</title>
    @include('partials.pwa-head')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ filemtime(public_path('css/landing.css')) }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .thumbnail-strip {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 8px 0;
            scrollbar-width: thin;
        }
        .thumbnail-strip img {
            width: 70px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.2s, border 0.2s;
            border: 2px solid transparent;
        }
        .thumbnail-strip img.active-thumb {
            opacity: 1;
            border-color: #fef08a;
        }
    </style>
</head>
<body style="background: #f8faf9;">
    @include('partials.navbar')

    <main style="padding-top: 80px;" x-data="{ open: false, activeAlbum: {}, activePhotoIndex: 0 }">

        <!-- Executive Hero Banner -->
        <section style="background: linear-gradient(135deg, #0d342a 0%, #164E3F 50%, #1c5e4d 100%); padding: 5.5rem 1rem; text-align: center; color: white; position: relative; overflow: hidden;">
            <div class="container" style="position: relative; z-index: 2;">
                <span class="hero-badge">Dokumentasi Visual TSTH2</span>
                <h1 style="font-size: clamp(2.4rem, 4.5vw, 3.5rem); margin-bottom: 1rem; color: white; font-family: var(--font-heading); font-weight: 800;">
                    {{ $konten['galeri_hero_title']->value ?? 'Galeri Foto TSTH2' }}
                </h1>
                <p style="font-size: 1.15rem; max-width: 750px; margin: 0 auto; color: rgba(241,245,249,0.92); line-height: 1.65;">
                    {{ $konten['galeri_hero_desc']->value ?? 'Kumpulan dokumentasi kegiatan, fasilitas, dan momen penting di Taman Sains Teknologi Herbal dan Hortikultura Indonesia.' }}
                </p>
            </div>
        </section>

        <!-- Konten Galeri -->
        <section style="padding: 4rem 0;">
            <div class="container">

                <!-- Search Bar -->
                <div style="max-width: 620px; margin: 0 auto 4rem auto;">
                    <form action="{{ route('galeri') }}" method="GET" style="display: flex; gap: 0.5rem; background: #ffffff; padding: 0.5rem; border-radius: 999px; box-shadow: 0 10px 30px rgba(14, 52, 42, 0.08); border: 1px solid rgba(22, 78, 63, 0.12);">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari album galeri..." style="flex: 1; padding: 0.85rem 1.5rem; border: none; outline: none; background: transparent; font-size: 1rem; color: #0f172a;">
                        <button type="submit" class="btn btn-primary" style="background: #164E3F;">
                            <span>Cari</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21 21-4.3-4.3"/><circle cx="11" cy="11" r="8"/></svg>
                        </button>
                    </form>
                </div>

                <!-- Album Grid — Poster Style Selang-Seling -->
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem;">
                    @forelse($galeri as $item)
                    @php
                        $photoList   = is_array($item->photos) ? $item->photos : [];
                        $coverPhoto  = count($photoList) > 0 ? $photoList[0] : null;
                        $totalPhotos = count($photoList);
                        $isGreen     = $loop->iteration % 2 === 1;
                        $cardClass   = $isGreen ? 'poster-card-green' : 'poster-card-gold';
                    @endphp

                    <div class="poster-card {{ $cardClass }} animate-on-scroll"
                         style="cursor: pointer; min-height: 370px;"
                         @click="activeAlbum = {{ json_encode($item) }}; activePhotoIndex = 0; open = true">

                        <!-- Header: Logo & Badge Foto -->
                        <div class="poster-header">
                            <div class="poster-logo">
                                <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo">
                                <div class="poster-logo-text">TSTH2<br>SCIENCE PARK</div>
                            </div>
                            @if($totalPhotos > 0)
                                <span class="poster-badge">📷 {{ $totalPhotos }} Foto</span>
                            @else
                                <span class="poster-badge">Album</span>
                            @endif
                        </div>

                        <!-- Inner Frame -->
                        <div class="poster-inner-frame">
                            <!-- Cover Photo -->
                            <div class="poster-image-center">
                                @if($coverPhoto)
                                    <img src="{{ asset('storage/' . $coverPhoto) }}" alt="{{ $item->title }}" loading="lazy">
                                @else
                                    <img src="{{ asset('assets/default_hero.jpg') }}" alt="{{ $item->title }}" loading="lazy">
                                @endif
                            </div>

                            <!-- Teks -->
                            <div style="margin-top: 0.75rem;">
                                <h3 style="font-size: 1.15rem; font-weight: 800; color: white; line-height: 1.3; margin-bottom: 0.5rem; font-family: var(--font-heading);">
                                    {{ Str::limit($item->title, 70) }}
                                </h3>
                                <p style="font-size: 0.85rem; color: rgba(255,255,255,0.82); line-height: 1.55; margin: 0;">
                                    {{ Str::limit($item->description, 80) }}
                                </p>
                            </div>

                            <!-- CTA Pill -->
                            <div class="poster-pill-tag" style="margin-top: 1rem; display: flex; align-items: center; gap: 0.4rem;">
                                <span>Lihat Galeri Foto</span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </div>
                        </div>
                    </div>

                    @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: white; border-radius: 1.5rem; color: #64748b;">
                        <h3 style="color: #0f172a; margin-bottom: 0.5rem; font-weight: 800;">Belum Ada Album</h3>
                        <p>Dokumentasi foto akan segera ditambahkan.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- ── Lightbox Modal ── -->
            <div x-show="open"
                 x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="position: fixed; inset: 0; z-index: 9999; background-color: rgba(0,0,0,0.88); backdrop-filter: blur(10px);">

                <div style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; padding: 1.5rem;">
                    <div style="background-color: white; border-radius: 1.25rem; width: 100%; max-width: 920px; max-height: 92vh; display: flex; flex-direction: column; box-shadow: 0 25px 60px -12px rgba(0,0,0,0.5); overflow: hidden;"
                         @click.away="open = false">

                        <!-- Header Modal -->
                        <div style="padding: 1.25rem 1.75rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #164E3F, #1a6050);">
                            <div>
                                <h3 style="font-size: 1.2rem; font-weight: 800; color: #ffffff; margin: 0; font-family: var(--font-heading); line-height: 1.3;" x-text="activeAlbum.title"></h3>
                                <template x-if="activeAlbum.photos && activeAlbum.photos.length > 1">
                                    <span style="font-size: 0.8rem; font-weight: 600; color: #fef08a;" x-text="`Foto ${activePhotoIndex + 1} dari ${activeAlbum.photos.length}`"></span>
                                </template>
                            </div>
                            <button @click="open = false" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white; font-size: 1.4rem; font-weight: bold; cursor: pointer; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">&times;</button>
                        </div>

                        <!-- Main Image Area with Navigation -->
                        <div style="padding: 1.5rem; overflow-y: auto; flex-grow: 1; text-align: center; background: #090d16; display: flex; flex-direction: column; justify-content: center; align-items: center; position: relative;">

                            <template x-if="activeAlbum.photos && activeAlbum.photos.length > 0">
                                <div style="position: relative; width: 100%; display: flex; align-items: center; justify-content: center;">

                                    <!-- Prev Button -->
                                    <template x-if="activeAlbum.photos.length > 1">
                                        <button @click="activePhotoIndex = (activePhotoIndex - 1 + activeAlbum.photos.length) % activeAlbum.photos.length"
                                                style="position: absolute; left: 10px; background: rgba(255,255,255,0.2); color: white; border: none; border-radius: 50%; width: 44px; height: 44px; font-size: 1.2rem; cursor: pointer; backdrop-filter: blur(4px); z-index: 10; display: flex; align-items: center; justify-content: center;">
                                            ❮
                                        </button>
                                    </template>

                                    <!-- Main Photo -->
                                    <img :src="`/storage/${activeAlbum.photos[activePhotoIndex]}`"
                                         style="max-width: 100%; max-height: 54vh; border-radius: 0.75rem; object-fit: contain; box-shadow: 0 10px 25px rgba(0,0,0,0.5); transition: all 0.3s ease;">

                                    <!-- Next Button -->
                                    <template x-if="activeAlbum.photos.length > 1">
                                        <button @click="activePhotoIndex = (activePhotoIndex + 1) % activeAlbum.photos.length"
                                                style="position: absolute; right: 10px; background: rgba(255,255,255,0.2); color: white; border: none; border-radius: 50%; width: 44px; height: 44px; font-size: 1.2rem; cursor: pointer; backdrop-filter: blur(4px); z-index: 10; display: flex; align-items: center; justify-content: center;">
                                            ❯
                                        </button>
                                    </template>
                                </div>
                            </template>

                            <!-- Thumbnail Strip -->
                            <template x-if="activeAlbum.photos && activeAlbum.photos.length > 1">
                                <div class="thumbnail-strip" style="margin-top: 15px; width: 100%; justify-content: center;">
                                    <template x-for="(photo, index) in activeAlbum.photos" :key="index">
                                        <img :src="`/storage/${photo}`"
                                             :class="{ 'active-thumb': activePhotoIndex === index }"
                                             @click="activePhotoIndex = index">
                                    </template>
                                </div>
            </template>
                        </div>

                        <!-- Footer Description -->
                        <div style="padding: 1.25rem 1.75rem; background: #ffffff; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                            <div style="color: #334155; line-height: 1.6; font-size: 0.98rem;" x-text="activeAlbum.description"></div>
                            <button @click="open = false" class="btn btn-primary" style="background: #164E3F; flex-shrink: 0; margin-left: 1rem;">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
