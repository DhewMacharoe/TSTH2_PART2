<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Fasilitas & Penelitian - TSTH2</title>
    @include('partials.pwa-head')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <!-- Fitur Cache Busting sudah Anda buat dengan baik di sini -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ time() }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body style="background: #f8faf9;" x-data="{ open: false, activeItem: {} }">
    @include('partials.navbar')

    <main style="padding-top: 80px;">
        <!-- Executive Hero Banner -->
        <section style="background: linear-gradient(135deg, #0d342a 0%, #164E3F 50%, #1c5e4d 100%); padding: 5.5rem 1rem; text-align: center; color: white; position: relative; overflow: hidden;">
            <div class="container" style="position: relative; z-index: 2;">
                <span class="hero-badge">Aktivitas &amp; Program Riset</span>
                <h1 style="font-size: clamp(2.4rem, 4.5vw, 3.5rem); margin-bottom: 1rem; color: white; font-family: var(--font-heading, sans-serif); font-weight: 800;">
                    {{ $konten['penelitian_hero_title']->value ?? 'Riset TSTH2' }}
                </h1>
                <p style="font-size: 1.15rem; max-width: 750px; margin: 0 auto; color: rgba(241,245,249,0.92); line-height: 1.65;">
                    {{ $konten['penelitian_hero_desc']->value ?? 'Infrastruktur dan fasilitas riset terpadu untuk mengembangkan potensi tanaman herbal dan hortikultura Indonesia.' }}
                </p>
            </div>
        </section>

        <!-- Search Bar & Grid Area -->
        <section style="padding: 4rem 0;">
            <div class="container">
                <!-- Search Bar -->
                <div style="max-width: 620px; margin: 0 auto 4rem auto;">
                    <form action="{{ route('penelitian') }}" method="GET" style="display: flex; gap: 0.5rem; background: #ffffff; padding: 0.5rem; border-radius: 999px; box-shadow: 0 10px 30px rgba(14, 52, 42, 0.08); border: 1px solid rgba(22, 78, 63, 0.12);">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari fasilitas laboratorium atau kata kunci riset..." style="flex: 1; padding: 0.85rem 1.5rem; border: none; outline: none; background: transparent; font-size: 1rem; color: #0f172a;">
                        <button type="submit" class="btn btn-primary" style="background: #164E3F; border-radius: 999px; display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.85rem 1.5rem;">
                            <span>Cari</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21 21-4.3-4.3"/><circle cx="11" cy="11" r="8"/></svg>
                        </button>
                    </form>
                </div>

                <!-- Grid Data -->
                <div class="penelitian-grid">
                    @forelse ($penelitianes as $item)
                        @php
                            $isGreen =$loop->iteration % 2 === 1;
                            $cardBgClass =$isGreen ? 'poster-card-green' : 'poster-card-gold';
                            
                            // Map data aman untuk Alpine
                            $itemArray = [
                                'id' => $item->id ?? null,
                                'title' => $item->title ?? '',
                                'description' => $item->description ?? '',
                                'image_path' => $item->image_path ?? null,
                            ];
                        @endphp
                        
                        <div class="poster-card {{ $cardBgClass }} animate-on-scroll" 
                             style="cursor: pointer;" 
                             @click="activeItem = @js($itemArray); open = true">
                            
                            <!-- Header Logo TSTH2 & Badge -->
                            <div class="poster-header">
                                <div class="poster-logo">
                                    <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo">
                                    <div class="poster-logo-text">
                                        TSTH2 SCIENCE PARK
                                    </div>
                                </div>
                                <span class="poster-badge">{{ $isGreen ? 'Laboratorium Riset' : 'Bioteknologi' }}</span>
                            </div>

                            <!-- Inner Frame Content -->
                            <div class="poster-inner-frame">
                                <div>
                                    <h3 style="font-size: 1.35rem; font-weight: 800; color: white; line-height: 1.25; margin-bottom: 0.6rem; font-family: var(--font-heading, sans-serif);">
                                        {{ Str::limit($item->title, 65) }}
                                    </h3>
                                    <p style="font-size: 0.88rem; color: rgba(255,255,255,0.85); line-height: 1.5; margin: 0;">
                                        {{ Str::limit($item->description, 90) }}
                                    </p>
                                </div>

                                <div class="poster-image-center">
                                    <img src="{{ !empty($item->image_path) ? asset('storage/' . $item->image_path) : asset('assets/default_hero.jpg') }}" alt="{{ $item->title }}" loading="lazy">
                                </div>

                                <div class="poster-pill-tag">
                                    <span>Spesifikasi Riset &rarr;</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: white; border-radius: 1.5rem; color: #64748b;">
                            <h3 style="color: #0f172a; margin-bottom: 0.5rem; font-weight: 800;">Data Riset Tidak Ditemukan</h3>
                            <p>Silakan coba kata kunci pencarian lainnya.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Detail Modal Terpusat -->
        <div x-show="open" 
             style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 10000; background: rgba(14, 52, 42, 0.85); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; padding: 1.5rem;" 
             x-cloak>
            
            <div style="background: #ffffff; border-radius: 1.5rem; max-width: 720px; width: 100%; max-height: 90vh; margin: auto; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35);" 
                 @click.away="open = false">
                
                <div style="position: relative;">
                    <template x-if="activeItem.image_path">
                        <img :src="`/storage/${activeItem.image_path}`" style="width: 100%; height: 300px; object-fit: cover;">
                    </template>
                    <button type="button" @click="open = false" style="position: absolute; top: 1rem; right: 1rem; width: 36px; height: 36px; border-radius: 50%; background: rgba(0,0,0,0.6); color: white; border: none; font-size: 1.2rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
                </div>
                
                <div style="padding: 2.25rem; overflow-y: auto;">
                    <h3 style="font-size: 1.75rem; color: #0f172a; margin-bottom: 1rem; font-weight: 800; font-family: var(--font-heading, sans-serif);" x-text="activeItem.title"></h3>
                    <p style="font-size: 1.05rem; line-height: 1.7; color: #475569; white-space: pre-wrap;" x-text="activeItem.description"></p>
                    
                    <div style="margin-top: 2rem; text-align: right;">
                        <button type="button" @click="open = false" class="btn btn-primary" style="background: #164E3F; padding: 0.6rem 1.5rem; border-radius: 999px; color: white; border: none; font-weight: bold; cursor: pointer;">Tutup Spesifikasi</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>