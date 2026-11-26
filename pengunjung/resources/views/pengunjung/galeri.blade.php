<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ __('site.hero_gallery_title') }} - TSTH2</title>
    @include('partials.pwa-head')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ filemtime(public_path('css/landing.css')) }}">
    <!-- Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }

        /* True Masonry Gallery Grid */
        .gallery-masonry {
            column-count: 3;
            column-gap: 1.25rem;
        }
        @media (max-width: 900px) {
            .gallery-masonry {
                column-count: 2;
                column-gap: 1rem;
            }
        }
        @media (max-width: 540px) {
            .gallery-masonry {
                column-count: 1;
            }
        }
        .gallery-masonry-item {
            break-inside: avoid;
            margin-bottom: 1.25rem;
            border-radius: 14px;
            overflow: hidden;
            cursor: pointer;
            background: #ffffff;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
            border: 2.5px solid transparent;
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.25s ease;
            position: relative;
        }
        .gallery-masonry-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.15);
            border-color: #164E3F;
        }
        .gallery-masonry-item.is-selected {
            border-color: #22c55e !important;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.25), 0 16px 32px rgba(15, 23, 42, 0.2);
        }
        .gallery-video-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: background 0.2s;
        }
        .gallery-masonry-item:hover .gallery-video-overlay {
            background: rgba(0, 0, 0, 0.5);
        }
        .gallery-play-badge {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(22, 78, 63, 0.9);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(4px);
        }
        .gallery-hover-overlay {
            position: absolute;
            inset: 0;
            background: rgba(13, 52, 42, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.25s ease;
        }
        .gallery-masonry-item:hover .gallery-hover-overlay {
            opacity: 1;
        }
        .gallery-zoom-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 0.45rem 0.95rem;
            background: rgba(255, 255, 255, 0.95);
            color: #164E3F;
            font-size: 0.8rem;
            font-weight: 800;
            border-radius: 999px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body style="background: #f8faf9;">
    @include('partials.navbar')

    <script id="galeri-albums-data" type="application/json">
        {!! json_encode($galeri->keyBy('id')) !!}
    </script>

    <main style="padding-top: 110px;" x-data="{ 
        open: false, 
        albums: JSON.parse(document.getElementById('galeri-albums-data')?.textContent || '{}'),
        activeAlbum: {}, 
        selectedMedia: null,
        openAlbum(id) {
            this.activeAlbum = this.albums[id] || {};
            this.selectedMedia = null;
            this.open = true;
        },
        isVideo(path) {
            if (!path) return false;
            return /\.(mp4|webm|ogg|mov|avi|mkv)$/i.test(path);
        }
    }">

        <!-- Executive Hero Banner -->
        <section style="background: linear-gradient(135deg, #0d342a 0%, #164E3F 50%, #1c5e4d 100%); padding: 5.5rem 1rem; text-align: center; color: white; position: relative; overflow: hidden;">
            <div class="container" style="position: relative; z-index: 2;">
                <h1 style="font-size: clamp(2.4rem, 4.5vw, 3.5rem); margin-bottom: 1rem; color: white; font-family: var(--font-heading); font-weight: 800;">
                    {{ $konten['galeri_hero_title']->value ?? 'Galeri Foto & Video' }}
                </h1>
                <p style="font-size: 1.15rem; max-width: 750px; margin: 0 auto; color: rgba(241,245,249,0.92); line-height: 1.65;">
                    {{ $konten['galeri_hero_desc']->value ?? 'Dokumentasi perjalanan riset, fasilitas laboratorium mutakhir, kunjungan resmi, dan aktivitas sains di TSTH2.' }}
                </p>
            </div>
        </section>

        <!-- Konten Galeri -->
        <section style="padding: 4rem 0;">
            <div class="container">

                <!-- Search Bar -->
                <div style="max-width: 620px; margin: 0 auto 4rem auto;">
                    <form action="{{ route('galeri') }}" method="GET" style="display: flex; gap: 0.5rem; background: #ffffff; padding: 0.5rem; border-radius: 999px; box-shadow: 0 10px 30px rgba(14, 52, 42, 0.08); border: 1px solid rgba(22, 78, 63, 0.12);">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('site.search_gallery_placeholder') }}" style="flex: 1; padding: 0.85rem 1.5rem; border: none; outline: none; background: transparent; font-size: 1rem; color: #0f172a;">
                        <button type="submit" class="btn btn-primary" style="background: #164E3F;">
                            <span>{{ __('site.search') }}</span>
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
                        $isCoverVideo = \App\Models\GaleriAlbum::isVideoFile($coverPhoto);
                        $stats       = $item->media_stats ?? ['photos' => count($photoList), 'videos' => 0, 'total' => count($photoList)];
                        $isGreen     = $loop->iteration % 2 === 1;
                        $cardClass   = $isGreen ? 'poster-card-green' : 'poster-card-gold';
                    @endphp

                    <div class="poster-card {{ $cardClass }} animate-on-scroll"
                         style="cursor: pointer; min-height: 370px;"
                         @click="openAlbum({{ $item->id }})">

                        <!-- Header: Logo & Badge Media -->
                        <div class="poster-header">
                            <div class="poster-logo">
                                <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo">
                                <div class="poster-logo-text">TSTH2<br>SCIENCE PARK</div>
                            </div>
                            <span class="poster-badge" style="display: inline-flex; align-items: center; gap: 4px;">
                                @if($stats['videos'] > 0 && $stats['photos'] > 0)
                                    <span class="material-symbols-rounded" style="font-size: 14px;">photo_camera</span> {{ $stats['photos'] }} • <span class="material-symbols-rounded" style="font-size: 14px;">videocam</span> {{ $stats['videos'] }}
                                @elseif($stats['videos'] > 0)
                                    <span class="material-symbols-rounded" style="font-size: 14px;">videocam</span> {{ $stats['videos'] }} Video
                                @elseif($stats['photos'] > 0)
                                    <span class="material-symbols-rounded" style="font-size: 14px;">photo_camera</span> {{ $stats['photos'] }} Foto
                                @else
                                    Album
                                @endif
                            </span>
                        </div>

                        <!-- Inner Frame -->
                        <div class="poster-inner-frame">
                            <!-- Cover Media (Image or Video) -->
                            <div class="poster-image-center" style="position: relative; background: #0b1512; border-radius: 12px; overflow: hidden;">
                                @if($coverPhoto)
                                    @if($isCoverVideo)
                                        <video src="{{ asset('storage/' . $coverPhoto) }}" muted playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
                                        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.35); display: flex; align-items: center; justify-content: center;">
                                            <span style="width: 44px; height: 44px; border-radius: 50%; background: rgba(22,78,63,0.85); backdrop-filter: blur(4px); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">▶</span>
                                        </div>
                                    @else
                                        <img src="{{ asset('storage/' . $coverPhoto) }}" alt="{{ $item->title }}" loading="lazy">
                                    @endif
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
                                <span>{{ __('site.view_gallery') }}</span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </div>
                        </div>
                    </div>

                    @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: white; border-radius: 1.5rem; color: #64748b;">
                        <h3 style="color: #0f172a; margin-bottom: 0.5rem; font-weight: 800;">{{ __('site.empty_gallery') }}</h3>
                        <p>{{ __('site.empty_gallery_desc') }}</p>
                    </div>
                    @endforelse
                </div>

                @if (method_exists($galeri, 'hasPages') && $galeri->hasPages())
                    <div style="margin-top: 3rem; display: flex; justify-content: center;">
                        {{ $galeri->links() }}
                    </div>
                @endif
            </div>

            <!-- ── True Gallery Modal: All Images/Videos at Once, Natural Aspect Ratio, Click to Enlarge ── -->
            <div x-show="open"
                 x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="position: fixed; inset: 0; z-index: 9999; background-color: rgba(5, 20, 16, 0.88); backdrop-filter: blur(12px); overflow-y: auto; padding: 1.5rem 1rem;">

                <div style="max-width: 1200px; margin: 1.5rem auto 3rem auto; background: #ffffff; border-radius: 1.5rem; box-shadow: 0 25px 60px -12px rgba(0,0,0,0.6); overflow: hidden; border: 1px solid rgba(255,255,255,0.2);"
                     @click.away="open = false">

                    <!-- Header Modal -->
                    <div style="padding: 1.5rem 2rem; background: linear-gradient(135deg, #0d342a 0%, #164E3F 100%); color: white; display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <div>
                            <span style="font-size: 0.72rem; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: #a7f3d0; background: rgba(255,255,255,0.12); padding: 0.25rem 0.75rem; border-radius: 999px; display: inline-block; margin-bottom: 0.5rem;">
                                Album Dokumentasi
                            </span>
                            <h2 style="font-size: clamp(1.3rem, 2.5vw, 1.85rem); font-weight: 800; margin: 0 0 0.5rem 0; font-family: var(--font-heading); color: #ffffff;" x-text="activeAlbum.title"></h2>
                            <p style="font-size: 0.92rem; color: rgba(241,245,249,0.85); margin: 0; line-height: 1.6; max-width: 800px;" x-text="activeAlbum.description"></p>
                            <div style="margin-top: 0.75rem; display: flex; align-items: center; gap: 0.75rem; font-size: 0.8rem; color: #fef08a; font-weight: 700;">
                                <span class="material-symbols-rounded" style="font-size: 18px;">photo_library</span>
                                <span x-text="`${activeAlbum.photos ? activeAlbum.photos.length : 0} item media`"></span>
                            </div>
                        </div>
                        <button @click="open = false" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: white; cursor: pointer; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: background 0.2s;">
                            <span class="material-symbols-rounded" style="font-size: 22px;">close</span>
                        </button>
                    </div>

                    <!-- SPOTLIGHT ENLARGED VIEW (Active when an image/video is clicked) -->
                    <template x-if="selectedMedia">
                        <div style="background: #090d16; padding: 2rem 1.5rem; text-align: center; border-bottom: 3px solid #164E3F; position: relative;">
                            <div style="position: absolute; top: 1rem; right: 1.5rem; z-index: 10;">
                                <button type="button" @click="selectedMedia = null" style="background: rgba(255,255,255,0.2); border: none; color: white; padding: 0.45rem 1rem; border-radius: 999px; font-size: 0.8rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; backdrop-filter: blur(8px);">
                                    <span class="material-symbols-rounded" style="font-size: 16px;">close</span>
                                    Tutup Tampilan Pembesaran
                                </button>
                            </div>
                            
                            <div style="display: inline-block; max-width: 100%; position: relative;">
                                <template x-if="isVideo(selectedMedia)">
                                    <video :src="`/storage/${selectedMedia}`" controls autoplay playsinline
                                           style="max-width: 100%; max-height: 72vh; border-radius: 1rem; box-shadow: 0 20px 50px rgba(0,0,0,0.8); outline: none;">
                                    </video>
                                </template>
                                <template x-if="!isVideo(selectedMedia)">
                                    <img :src="`/storage/${selectedMedia}`" alt="Pembesaran Gambar"
                                         style="max-width: 100%; max-height: 72vh; width: auto; height: auto; border-radius: 1rem; box-shadow: 0 20px 50px rgba(0,0,0,0.8); object-fit: contain;">
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- ALL PHOTOS & VIDEOS APPEAR AT ONCE (MASONRY GRID, NATURAL SIZES) -->
                    <div style="padding: 2rem; background: #f8faf9;">
                        <div style="margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                            <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                                <span class="material-symbols-rounded" style="color: #164E3F;">grid_view</span>
                                Semua Foto &amp; Video dalam Album
                            </h3>
                        </div>

                        <!-- Masonry Column Container -->
                        <div class="gallery-masonry">
                            <template x-for="(media, index) in activeAlbum.photos" :key="index">
                                <div class="gallery-masonry-item"
                                     :class="{ 'is-selected': selectedMedia === media }"
                                     @click="selectedMedia = (selectedMedia === media ? null : media)">
                                    
                                    <!-- Video Item -->
                                    <template x-if="isVideo(media)">
                                        <div style="position: relative; overflow: hidden; border-radius: 12px; background: #000;">
                                            <video :src="`/storage/${media}`" muted playsinline preload="metadata"
                                                   style="width: 100%; height: auto; display: block;"></video>
                                            <div class="gallery-video-overlay">
                                                <span class="gallery-play-badge">
                                                    <span class="material-symbols-rounded" style="font-size: 24px;">play_arrow</span>
                                                </span>
                                                <span style="font-size: 0.75rem; font-weight: 700; color: white;">Video</span>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Image Item -->
                                    <template x-if="!isVideo(media)">
                                        <div style="position: relative; overflow: hidden; border-radius: 12px; background: #e2e8f0;">
                                            <img :src="`/storage/${media}`"
                                                 loading="lazy"
                                                 style="width: 100%; height: auto; display: block; transition: transform 0.35s ease;">
                                            <div class="gallery-hover-overlay">
                                                <span class="gallery-zoom-badge">
                                                    <span class="material-symbols-rounded" style="font-size: 18px;">zoom_in</span> Perbesar
                                                </span>
                                            </div>
                                        </div>
                                    </template>

                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Footer Bar -->
                    <div style="padding: 1.25rem 2rem; background: #ffffff; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end;">
                        <button type="button" @click="open = false" class="btn btn-primary" style="background: #164E3F; padding: 0.65rem 1.75rem; border-radius: 0.75rem; font-weight: 700;">
                            Tutup Galeri
                        </button>
                    </div>

                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
