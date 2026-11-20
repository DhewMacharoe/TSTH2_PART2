@extends('layouts.app')

@section('title', $plant->name . ' | Katalog TSTH2')

@section('content')
<style>
    /* 1. Efek khusus kotak foto utama carousel (hanya foto yang zoom, kotak tetap diam agar panah tidak berkedip) */
    .plant-hero-frame img.plant-zoom-img {
        transition: transform 0.3s ease, opacity 0.3s ease;
        will-change: transform;
    }
    .plant-hero-frame:hover img.plant-zoom-img {
        transform: scale(1.05);
    }

    /* 2. Efek Hover & Klik pada Card Tanaman Serupa */
    .plant-interactive-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .plant-interactive-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
    }
    .plant-interactive-card:active {
        transform: scale(0.98);
    }
    .plant-interactive-card img.plant-zoom-img {
        transition: transform 0.3s ease, opacity 0.3s ease;
        will-change: transform;
    }
    .plant-interactive-card:hover img.plant-zoom-img {
        transform: scale(1.05);
    }

    /* 3. Mengunci tombol panah Next & Prev agar stabil dan tidak berkedip */
    .carousel-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%) translateZ(0);
        z-index: 30;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
        transition: background-color 0.2s ease;
    }
    .carousel-nav-btn:hover {
        background-color: #ffffff;
    }
    .carousel-nav-btn:active {
        transform: translateY(-50%) scale(0.95);
    }
</style>

<div class="w-full">
    @php
        $catalogRoute = $plant->location_type === 'collection' || $plant->location_type === 'outdoor' ? 'collection' : 'home';
        $catalogLabel = $plant->location_type === 'collection' || $plant->location_type === 'outdoor' ? 'Koleksi Outdoor' : 'Katalog Greenhouse';
    @endphp
    
    <!-- Breadcrumb -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <nav class="min-w-0 overflow-x-auto text-xs font-medium text-gray-500" aria-label="Breadcrumb">
            <ol class="flex min-w-max items-center gap-2 whitespace-nowrap">
                <li><a href="{{ route($catalogRoute) }}" class="text-[#003f32] transition hover:underline">{{ $catalogLabel }}</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li>
                    @if ($plant->category)
                        <a href="{{ route($catalogRoute, ['category' => $plant->category->slug]) }}" class="text-[#003f32] transition hover:underline">{{ $plant->category->name }}</a>
                    @else
                        Tanaman Herbal
                    @endif
                </li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li aria-current="page" class="max-w-[280px] truncate font-bold text-gray-900">{{ $plant->name }}</li>
            </ol>
        </nav>
        <button type="button" class="flex items-center gap-1.5 transition hover:text-gray-900" data-share-button data-share-title="{{ $plant->name }}" aria-label="Bagikan halaman tanaman {{ $plant->name }}">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
            </svg>
            Bagikan
        </button>
    </div>

    <!-- Main Card -->
    <article class="overflow-hidden rounded-xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8 md:p-10">
        
        <!-- Bagian Atas: Gambar & Info Utama (Layout Desktop) -->
        <div class="mb-8 grid gap-8 md:grid-cols-[340px_minmax(0,1fr)]">
            
            @php
                $plantImages = collect([$plant->image])->filter()->merge($plant->image_gallery ?? [])->values();
            @endphp
            <div class="plant-carousel order-1 w-full shrink-0 md:col-start-1 md:row-start-1 md:w-[340px]" data-carousel>
                <div class="relative">
                    {{-- Kotak Foto Utama menggunakan plant-hero-frame agar tidak ikut naik-turun --}}
                    <div class="plant-hero-frame overflow-hidden rounded-2xl border border-gray-100 bg-gray-100 shadow-sm" data-carousel-track>
                    @forelse ($plantImages as $imageIndex => $image)
                        <div class="{{ $imageIndex === 0 ? '' : 'hidden' }} relative aspect-square w-full bg-gray-100" data-carousel-slide="{{ $imageIndex }}">
                            {{-- Skeleton Loading Foto Utama --}}
                            <div class="skeleton-loader absolute inset-0 flex flex-col items-center justify-center bg-gray-200 animate-pulse">
                                <svg class="h-12 w-12 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                </svg>
                            </div>

                            {{-- Foto Utama Tanaman --}}
                            <img 
                                src="{{ route('filebase.show', ['path' => 'plants/' . $image]) }}" 
                                alt="{{ $plant->name }} - foto {{ $imageIndex + 1 }}" 
                                {{ $imageIndex === 0 ? '' : 'loading=lazy' }}
                                onload="this.classList.remove('opacity-0'); if(this.previousElementSibling) this.previousElementSibling.remove();"
                                onerror="if(this.previousElementSibling) this.previousElementSibling.classList.remove('animate-pulse');"
                                class="plant-zoom-img relative z-10 h-full w-full object-cover opacity-0"
                            >
                        </div>
                    @empty
                        <div class="flex aspect-square w-full flex-col items-center justify-center text-gray-400">
                            <span class="mb-2 text-6xl">🌿</span>
                        </div>
                    @endforelse
                    </div>
                    @if ($plantImages->count() > 1)
                        <button type="button" class="carousel-nav-btn left-2 flex h-8 w-8 items-center justify-center rounded-full bg-white/90 text-lg font-bold text-[#003f32] shadow" data-carousel-prev aria-label="Foto sebelumnya">&lsaquo;</button>
                        <button type="button" class="carousel-nav-btn right-2 flex h-8 w-8 items-center justify-center rounded-full bg-white/90 text-lg font-bold text-[#003f32] shadow" data-carousel-next aria-label="Foto berikutnya">&rsaquo;</button>
                    @endif
                </div>
                @if ($plantImages->count() > 1)
                    <div class="mt-3 flex gap-2 overflow-x-auto pb-1 scrollbar-none" data-carousel-thumbnails>
                        @foreach ($plantImages as $imageIndex => $image)
                            <button type="button" class="relative aspect-square w-16 shrink-0 overflow-hidden rounded-lg border-2 {{ $imageIndex === 0 ? 'border-[#16805f]' : 'border-transparent' }} bg-gray-100 transition hover:border-[#16805f]" data-carousel-thumbnail="{{ $imageIndex }}" aria-label="Lihat foto {{ $imageIndex + 1 }}">
                                {{-- Skeleton Loading Thumbnail --}}
                                <div class="skeleton-loader absolute inset-0 bg-gray-200 animate-pulse"></div>
                                
                                {{-- Foto Thumbnail --}}
                                <img 
                                    src="{{ route('filebase.show', ['path' => 'plants/' . $image]) }}" 
                                    alt="" 
                                    loading="lazy"
                                    onload="this.classList.remove('opacity-0'); if(this.previousElementSibling) this.previousElementSibling.remove();"
                                    onerror="if(this.previousElementSibling) this.previousElementSibling.classList.remove('animate-pulse');"
                                    class="relative z-10 h-full w-full object-cover opacity-0 transition-opacity duration-300"
                                >
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- FUNGSI SCROLL: Hanya diterapkan di layar MD ke atas -->
            <div class="order-2 min-h-0 pr-2 md:col-start-2 md:row-start-1 md:max-h-[calc(100vh-16rem)] md:overflow-y-auto md:pr-4">
                <div>
                <span class="mb-3 inline-block rounded-sm bg-[#f0fdf4] px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-[#16a34a]">
                    {{ $plant->category?->name ?? 'Tanaman Herbal' }}
                </span>
                
                <h1 class="font-['Montserrat'] text-3xl font-extrabold tracking-tight text-[#000000]">{{ $plant->name }}</h1>
                <p class="mb-6 mt-1 text-base italic text-gray-500">{{ $plant->latin_name }}</p>
                
                @auth
                    <div class="mb-5 grid grid-cols-2 gap-3 rounded-lg bg-[#f6fbf6] p-3 text-xs text-[#557067]">
                        <div><p class="font-bold text-[#718178]">Populasi Greenhouse</p><p class="mt-1 text-lg font-extrabold text-[#003f32]">{{ number_format((int) ($plant->greenhouse_population ?? 0), 0, ',', '.') }}</p></div>
                        <div><p class="font-bold text-[#718178]">Populasi Outdoor</p><p class="mt-1 text-lg font-extrabold text-[#003f32]">{{ number_format((int) ($plant->outdoor_population ?? 0), 0, ',', '.') }}</p></div>
                    </div>
                @endauth
                
                <!-- 1. TAKSONOMI BOTANI -->
                <div class="content-section" data-section="taksonomi">
                    <section class="rounded-2xl border border-[#e0e9e1] bg-white p-4 shadow-sm">
                        <div class="mb-3">
                            <h2 class="font-['Manrope'] text-sm font-extrabold text-[#111827]">TAKSONOMI BOTANI</h2>
                            <p class="text-[10px] text-[#49645b]">Klasifikasi biologis tanaman</p>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="rounded-xl border border-[#f0f0f0] bg-[#fafafa] p-2.5">
                                <span class="block text-[9px] font-bold uppercase tracking-wider text-[#8a9991]">Kingdom</span>
                                <span class="font-semibold text-[#111827]">{{ $plant->kingdom ?? 'Plantae' }}</span>
                            </div>
                            <div class="rounded-xl border border-[#f0f0f0] bg-[#fafafa] p-2.5">
                                <span class="block text-[9px] font-bold uppercase tracking-wider text-[#8a9991]">Ordo</span>
                                <span class="font-semibold text-[#111827]">{{ $plant->order ?? $plant->ordo ?? '-' }}</span>
                            </div>
                            <div class="rounded-xl border border-[#f0f0f0] bg-[#fafafa] p-2.5">
                                <span class="block text-[9px] font-bold uppercase tracking-wider text-[#8a9991]">Famili</span>
                                <span class="font-semibold text-[#111827]">{{ $plant->family ?? '-' }}</span>
                            </div>
                            <div class="rounded-xl border border-[#f0f0f0] bg-[#fafafa] p-2.5">
                                <span class="block text-[9px] font-bold uppercase tracking-wider text-[#8a9991]">Genus</span>
                                <span class="font-semibold text-[#111827]">{{ $plant->genus ?? '-' }}</span>
                            </div>
                            <div class="col-span-2 rounded-xl border border-[#f0f0f0] bg-[#fafafa] p-2.5">
                                <span class="block text-[9px] font-bold uppercase tracking-wider text-[#8a9991]">Botanist</span>
                                <span class="font-semibold text-[#111827]">{{ $plant->botanist ?? $plant->author ?? '-' }}</span>
                            </div>
                        </div>
                    </section>
                </div>
                </div>
                
                <!-- Bagian Bawah: Deskripsi, Manfaat, Asal Daerah -->
                <div class="mt-6 space-y-4">
                    <!-- Deskripsi -->
                    <div class="content-section" data-section="deskripsi">
                        <section class="rounded-2xl border border-[#e0e9e1] bg-white p-4 shadow-sm">
                            <div class="mb-3">
                                <h2 class="font-['Manrope'] text-sm font-extrabold text-[#111827]">DESKRIPSI MORFOLOGI</h2>
                                <p class="text-[10px] text-[#49645b]">Karakteristik fisik & rasa</p>
                            </div>

                            <div class="rounded-xl border border-[#f0f0f0] bg-[#fafafa] p-3 text-xs leading-relaxed text-[#4b5563]">
                                {{ $plant->description_full ?: $plant->description_short ?? 'Belum ada deskripsi mendalam untuk tanaman ini.' }}
                            </div>
                        </section>
                    </div>
                    
                    <!-- Manfaat -->
                    <div class="content-section" data-section="manfaat">
                        <section class="rounded-2xl border border-[#e0e9e1] bg-white p-4 shadow-sm">
                            <div class="mb-3">
                                <h2 class="font-['Manrope'] text-sm font-extrabold text-[#111827]">MANFAAT FARMAKOLOGIS</h2>
                                <p class="text-[10px] text-[#49645b]">Khasiat terapeutik teruji</p>
                            </div>

                            <div class="space-y-2">
                                @if($plant->benefits)
                                    @foreach($plant->benefit_items ?? [] as $item)
                                        @if(filled($item['description']))
                                            <details class="group overflow-hidden rounded-xl border border-[#cfe2d3] bg-white shadow-sm transition-all duration-200 hover:border-[#00634e] hover:shadow-md open:border-[#00634e] open:bg-[#f8fcf8]">
                                                <summary class="flex cursor-pointer list-none items-center gap-3 p-3 outline-none transition-colors hover:bg-[#f6fbf6] focus-visible:bg-[#f6fbf6] focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-[#00634e]">
                                                    <span class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-[#e8f3ea] text-[10px] font-bold text-[#003f32]">✓</span>
                                                    <span class="flex-1 text-xs font-semibold leading-relaxed text-[#344e45]">{{ $item['title'] }}</span>
                                                    <span class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full border border-[#b9d5bf] text-[#00634e] transition-transform duration-200 group-open:rotate-180" aria-hidden="true">
                                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m6 9 6 6 6-6"/></svg>
                                                    </span>
                                                </summary>
                                                <div class="border-t border-[#dbe9dd] px-10 pb-3 pt-2">
                                                    <p class="text-xs leading-relaxed text-[#4b5563]">{{ $item['description'] }}</p>
                                                </div>
                                            </details>
                                        @else
                                            <div class="flex items-start gap-2 rounded-xl border border-[#f0f0f0] bg-[#fafafa] p-3">
                                                <span class="mt-0.5 flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-[#e8f3ea] text-[10px] font-bold text-[#003f32]">✓</span>
                                                <p class="text-xs leading-relaxed text-[#4b5563]">{{ $item['title'] }}</p>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="rounded-xl border border-[#f0f0f0] bg-[#fafafa] p-3 text-xs text-[#4b5563]">
                                        Informasi manfaat belum ditambahkan.
                                    </div>
                                @endif
                            </div>
                        </section>
                    </div>

                    <!-- Asal Daerah -->
                    <div class="content-section" data-section="asal-daerah">
                        <section class="rounded-2xl border border-[#e0e9e1] bg-white p-4 shadow-sm">
                            <div class="mb-3">
                                <h2 class="font-['Manrope'] text-sm font-extrabold text-[#111827]">ASAL DAERAH & HABITAT</h2>
                                <p class="text-[10px] text-[#49645b]">Kawasan asal dan sebaran botani</p>
                            </div>
                            <div class="rounded-xl border border-green-100 bg-[#f0f8f4] p-3 shadow-sm">
                                <div class="text-[13px] leading-relaxed text-gray-700">
                                    <p class="mb-1 font-bold text-[#003f32]">
                                        Kawasan Asal: {{ $plant->origin ?? 'Data belum tersedia' }}
                                    </p>
                                    <p>
                                        {{ $plant->origin_distribution ?? 'Sebaran botani belum ditambahkan.' }}
                                    </p>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </article>

    @if ($relatedPlants->isNotEmpty())
        <section class="mt-12" aria-labelledby="related-plants-heading">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-[#16805f]">{{ $relatedSectionLabel }}</p>
                    <h2 id="related-plants-heading" class="mt-1 font-serif text-2xl font-bold text-[#003f32]">{{ $relatedSectionTitle }}</h2>
                </div>
                <a href="{{ route($catalogRoute) }}" class="shrink-0 text-sm font-bold text-[#16805f] transition hover:text-[#003f32]">Lihat katalog <span aria-hidden="true">&rarr;</span></a>
            </div>
            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($relatedPlants as $relatedPlant)
                    <a href="{{ route('plant.show', $relatedPlant) }}" class="plant-interactive-card group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
                            @if ($relatedPlant->image)
                                {{-- Skeleton Loading Card Tanaman Serupa --}}
                                <div class="skeleton-loader absolute inset-0 flex flex-col items-center justify-center bg-gray-200 animate-pulse">
                                    <svg class="h-8 w-8 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                    </svg>
                                </div>

                                {{-- Foto Tanaman Serupa --}}
                                <img 
                                    src="{{ route('filebase.show', ['path' => 'plants/' . $relatedPlant->image]) }}" 
                                    alt="{{ $relatedPlant->name }}" 
                                    loading="lazy"
                                    onload="this.classList.remove('opacity-0'); if(this.previousElementSibling) this.previousElementSibling.remove();"
                                    onerror="if(this.previousElementSibling) this.previousElementSibling.classList.remove('animate-pulse');"
                                    class="plant-zoom-img relative z-10 h-full w-full object-cover opacity-0 transition duration-300 group-hover:scale-105"
                                >
                            @else
                                <div class="flex h-full items-center justify-center text-4xl text-[#477568] opacity-50 transition duration-300 group-hover:scale-105">🌿</div>
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="text-sm font-semibold text-[#111827]">{{ $relatedPlant->name }}</h3>
                            <p class="mt-0.5 text-xs italic text-gray-500">{{ $relatedPlant->latin_name ?: 'Nama latin belum tersedia' }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>

<script>
    document.querySelectorAll('[data-carousel]').forEach((carousel) => {
        const slides = Array.from(carousel.querySelectorAll('[data-carousel-slide]'));
        const thumbnails = Array.from(carousel.querySelectorAll('[data-carousel-thumbnail]'));
        let currentIndex = 0;
        let autoplay;

        if (slides.length < 2) return;

        const showSlide = (index) => {
            currentIndex = (index + slides.length) % slides.length;
            slides.forEach((slide, slideIndex) => {
                slide.classList.toggle('hidden', slideIndex !== currentIndex);
            });
            thumbnails.forEach((thumbnail, thumbnailIndex) => {
                thumbnail.classList.toggle('border-[#16805f]', thumbnailIndex === currentIndex);
                thumbnail.classList.toggle('border-transparent', thumbnailIndex !== currentIndex);
            });
        };

        const startAutoplay = () => {
            clearInterval(autoplay);
            autoplay = setInterval(() => showSlide(currentIndex + 1), 4500);
        };

        carousel.querySelector('[data-carousel-prev]').addEventListener('click', () => {
            showSlide(currentIndex - 1);
            startAutoplay();
        });
        carousel.querySelector('[data-carousel-next]').addEventListener('click', () => {
            showSlide(currentIndex + 1);
            startAutoplay();
        });
        thumbnails.forEach((thumbnail) => thumbnail.addEventListener('click', () => {
            showSlide(Number(thumbnail.dataset.carouselThumbnail));
            startAutoplay();
        }));
        carousel.addEventListener('mouseenter', () => clearInterval(autoplay));
        carousel.addEventListener('mouseleave', startAutoplay);
        startAutoplay();
    });
</script>
@endsection