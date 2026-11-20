<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ __('site.hero_news_title') }} - TSTH2</title>
    @include('partials.pwa-head')

    <!-- Styles for distinct editorial news view -->
    <style>
        [x-cloak] { display: none !important; }
        
        .berita-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2.25rem;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ filemtime(public_path('css/landing.css')) }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body style="background: #f8faf9;">
    @include('partials.navbar')

    <main>
        <!-- Executive News Hero Banner -->
        <section style="background: linear-gradient(135deg, #0d342a 0%, #164E3F 50%, #1c5e4d 100%); padding: 5.5rem 1rem; text-align: center; color: white; position: relative; overflow: hidden;">
            <div class="container" style="position: relative; z-index: 2;">
                <h1 style="font-size: clamp(2.4rem, 4.5vw, 3.5rem); margin-bottom: 1rem; color: white; font-family: var(--font-heading, sans-serif); font-weight: 800;">
                    {{ $konten['berita_hero_title']->value ?? 'Berita Terkini' }}
                </h1>
                <p style="font-size: 1.15rem; max-width: 750px; margin: 0 auto; color: rgba(241,245,249,0.92); line-height: 1.65;">
                    {{ $konten['berita_hero_desc']->value ?? 'Informasi terkini seputar kegiatan riset, program inovasi, dan perkembangan Taman Sains Teknologi Herbal dan Hortikultura Indonesia.' }}
                </p>
            </div>
        </section>

        <!-- Konten Area -->
        <section style="padding: 4rem 0;" x-data="{ open: false, activeItem: {} }">
            <div class="container">
                <!-- Search Bar -->
                <div style="max-width: 620px; margin: 0 auto 3.5rem auto;">
                    <form action="{{ route('berita') }}" method="GET" style="display: flex; gap: 0.5rem; background: #ffffff; padding: 0.5rem; border-radius: 999px; box-shadow: 0 10px 30px rgba(14, 52, 42, 0.08); border: 1px solid rgba(22, 78, 63, 0.12);">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('site.search_news_placeholder') }}" style="flex: 1; padding: 0.85rem 1.5rem; border: none; outline: none; background: transparent; font-size: 1rem; color: #0f172a;">
                        <button type="submit" class="btn btn-primary" style="background: #164E3F; border-radius: 999px; display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.85rem 1.5rem;">
                            <span>{{ __('site.search') }}</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21 21-4.3-4.3"/><circle cx="11" cy="11" r="8"/></svg>
                        </button>
                    </form>
                </div>

                <!-- Berita Grid (Original Signature Poster Cards) -->
                <div class="berita-grid">
                    @forelse ($berita as $item)
                        @php
                            $isGreen = $loop->iteration % 2 === 1;
                            $cardBgClass = $isGreen ? 'poster-card-green' : 'poster-card-gold';
                            $badgeLabel = $item->category ?? 'Informasi';
                            $tanggal = $item->date ? \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') : '-';
                            
                            $itemArray = [
                                'id'             => $item->id ?? null,
                                'title'          => $item->title ?? '',
                                'description'    => $item->description ?? '',
                                'category'       => $badgeLabel,
                                'image_path'     => $item->image_path ?? null,
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

                @if (method_exists($berita, 'hasPages') && $berita->hasPages())
                    <div style="margin-top: 3rem; display: flex; justify-content: center;">
                        {{ $berita->links() }}
                    </div>
                @endif
            </div>

            <!-- ── Detail News Modal (Gambarnya ikut terscroll bersama teks artikel secara utuh) ── -->
            <div x-show="open"
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="position: fixed; inset: 0; z-index: 9999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; padding: 1.25rem;">
                
                <!-- Single Unified Scrollable Article Container -->
                <div style="background-color: #ffffff; border-radius: 1.5rem; width: 100%; max-width: 860px; max-height: 92vh; margin: auto; overflow-y: auto; box-shadow: 0 30px 70px -15px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.2);" 
                     class="custom-scrollbar"
                     @click.away="open = false">

                    <!-- Sticky Mini Top Action Bar -->
                    <div style="position: sticky; top: 0; z-index: 20; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); padding: 1rem 1.75rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <span style="font-size: 0.75rem; font-weight: 800; color: #164E3F; background: #e8f5e9; padding: 0.3rem 0.75rem; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.5px;" x-text="activeItem.category"></span>
                            <span style="font-size: 0.85rem; font-weight: 600; color: #64748b;" x-text="activeItem.formatted_date"></span>
                        </div>
                        <button @click="open = false" type="button" aria-label="Tutup Berita" style="background: #f1f5f9; border: 1px solid #e2e8f0; color: #475569; font-size: 1.2rem; font-weight: bold; cursor: pointer; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">&times;</button>
                    </div>

                    <!-- Full Unified Article Body (Everything scrolls together!) -->
                    <div style="padding: 2rem 2.25rem 3rem 2.25rem;">
                        <!-- News Headline -->
                        <h2 style="font-size: clamp(1.6rem, 3.2vw, 2.3rem); font-weight: 800; color: #0f172a; line-height: 1.3; margin-bottom: 1rem; font-family: var(--font-heading, sans-serif);" x-text="activeItem.title"></h2>

                        <!-- Source / Byline -->
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding-bottom: 1.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9; font-size: 0.88rem; color: #64748b;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: #164E3F; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.75rem;">TP</div>
                            <div>
                                <strong style="color: #0f172a;">{{ __('site.editorial_staff') }}</strong>
                                <span style="margin: 0 4px;">•</span>
                                <span>Taman Sains Teknologi Herbal &amp; Hortikultura</span>
                            </div>
                        </div>

                        <!-- Editorial Image (Embedded in scroll flow) -->
                        <template x-if="activeItem.image_path">
                            <figure style="margin: 0 0 2rem 0;">
                                <img :src="`/storage/${activeItem.image_path}`" 
                                     :alt="activeItem.title"
                                     style="width: 100%; border-radius: 1rem; box-shadow: 0 8px 24px rgba(0,0,0,0.08); object-fit: cover; max-height: 480px; display: block;">
                                <figcaption style="margin-top: 0.5rem; font-size: 0.82rem; color: #94a3b8; text-align: center; font-style: italic;" x-text="activeItem.title"></figcaption>
                            </figure>
                        </template>
                        <template x-if="!activeItem.image_path">
                            <figure style="margin: 0 0 2rem 0;">
                                <img src="{{ asset('assets/default_hero.jpg') }}" 
                                     alt="TSTH2 Science Park"
                                     style="width: 100%; border-radius: 1rem; box-shadow: 0 8px 24px rgba(0,0,0,0.08); object-fit: cover; max-height: 380px; display: block;">
                            </figure>
                        </template>

                        <!-- Article Paragraphs -->
                        <div style="color: #334155; line-height: 1.95; white-space: pre-wrap; font-size: 1.1rem; text-align: justify; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;" 
                             x-text="activeItem.description"></div>

                        <!-- Article Footer Actions -->
                        <div style="margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                            <div style="font-size: 0.85rem; color: #64748b;">
                                <span class="inline-flex items-center gap-1.5"><span class="material-symbols-rounded text-sm" style="color: #164E3F;">eco</span><span>TSTH2 Science Park Indonesia</span></span>
                            </div>
                            <button @click="open = false" type="button" class="btn btn-primary" style="background: #164E3F; padding: 0.65rem 1.75rem; border-radius: 999px; color: white; border: none; cursor: pointer; font-weight: 700;">
                                {{ __('site.close') }}
                            </button>
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