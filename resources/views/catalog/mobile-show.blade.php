@extends('layouts.public')

@section('title', $plant->name . ' - Detail Tanaman')

@section('content')
<div class="container mx-auto px-4 py-6">
    @php
        $catalogRoute = $plant->location_type === 'collection' ? 'collection' : 'katalog';
        $catalogLabel = $plant->location_type === 'collection' ? 'Koleksi Outdoor' : 'Katalog Greenhouse';
    @endphp

    <!-- Breadcrumb -->
    <nav class="mb-3 overflow-x-auto text-[10px] text-[#49645b]" aria-label="Breadcrumb">
        <ol class="flex min-w-max items-center gap-1.5 whitespace-nowrap">
            <li><a href="{{ route($catalogRoute) }}" class="font-semibold text-[#003f32] hover:underline">{{ $catalogLabel }}</a></li>
            <li aria-hidden="true" class="text-[#9aaa9f]">/</li>
            <li>
                @if ($plant->category)
                    <a href="{{ route($catalogRoute, ['category' => $plant->category->slug]) }}" class="font-semibold text-[#003f32] hover:underline">{{ $plant->category->name }}</a>
                @else
                    Tanaman Herbal
                @endif
            </li>
            <li aria-hidden="true" class="text-[#9aaa9f]">/</li>
            <li aria-current="page" class="max-w-[180px] truncate font-bold text-[#111827]">{{ $plant->name }}</li>
        </ol>
    </nav>

    <!-- Header Navigasi Atas -->
    <div class="mb-4 flex items-center justify-between">
        <a href="{{ url()->previous() }}" class="flex items-center gap-1.5 text-xs font-bold text-[#003f32]">
            <span class="text-base">←</span> Kembali
        </a>
        <span class="text-[10px] font-bold tracking-wider text-[#49645b] uppercase">Technical Data Sheet</span>
        <button onclick="navigator.share ? navigator.share({title: '{{ $plant->name }}', url: window.location.href}) : alert('Link disalin!')" class="text-[#003f32]">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
        </button>
    </div>

    @php
        $plantImages = collect([$plant->image])->filter()->merge($plant->image_gallery ?? [])->values();
    @endphp

    <!-- Foto Tanaman -->
    <div class="plant-carousel mb-4 overflow-hidden rounded-2xl bg-white shadow-sm border border-[#e0e9e1] p-3" data-carousel>
        <div class="relative">
            <div class="flex snap-x snap-mandatory gap-2 overflow-x-auto rounded-xl scrollbar-none" data-carousel-track>
            @forelse ($plantImages as $imageIndex => $image)
                <div class="flex w-full shrink-0 snap-center items-center justify-center overflow-hidden rounded-xl bg-[#f0f5f1] {{ $imageIndex === 0 ? 'aspect-square' : 'h-[200px]' }}">
                    <img src="{{ asset('storage/plants/' . $image) }}" alt="{{ $plant->name }} - foto {{ $imageIndex + 1 }}" class="{{ $imageIndex === 0 ? 'h-full w-full object-cover' : 'h-full w-auto max-w-full object-contain' }}">
                </div>
            @empty
                <div class="flex aspect-square w-full items-center justify-center rounded-xl bg-[#f0f5f1] text-4xl">🌿</div>
            @endforelse
            </div>
            @if ($plantImages->count() > 1)
                <button type="button" class="absolute left-2 top-1/2 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-lg font-bold text-[#003f32] shadow" data-carousel-prev aria-label="Foto sebelumnya">‹</button>
                <button type="button" class="absolute right-2 top-1/2 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-lg font-bold text-[#003f32] shadow" data-carousel-next aria-label="Foto berikutnya">›</button>
            @endif
        </div>
        @if ($plantImages->count() > 1)
            <div class="flex items-center justify-center gap-1.5 pt-2" data-carousel-dots>
                @foreach ($plantImages as $imageIndex => $image)
                    <button type="button" class="h-1.5 rounded-full transition-all {{ $imageIndex === 0 ? 'w-4 bg-[#003f32]' : 'w-1.5 bg-[#b8c8bd]' }}" data-carousel-dot="{{ $imageIndex }}" aria-label="Lihat foto {{ $imageIndex + 1 }}"></button>
                @endforeach
            </div>
        @endif
        <div class="pt-3.5 px-1 pb-1">
            <div class="flex items-center justify-between gap-2">
                <h1 class="font-['Manrope'] text-lg font-extrabold text-[#111827]">{{ $plant->name }}</h1>
                @if ($plant->category)
                    <span class="rounded-full bg-[#fef0c7] px-3 py-0.5 text-[10px] font-bold text-[#b54708]">{{ $plant->category->name }}</span>
                @endif
            </div>
            <p class="mt-0.5 text-xs italic text-[#d97706]">{{ $plant->latin_name }}</p>
        </div>
    </div>

    <!-- Tab / Filter Navigasi Interaktif -->
    <nav class="sticky top-2 z-10 mb-6 flex overflow-x-auto gap-2 bg-[#f8faf8] py-2 scrollbar-none" aria-label="Filter bagian">
        <button type="button" onclick="filterSection('semua')" class="tab-btn rounded-full bg-[#003f32] px-4 py-1.5 text-[11px] font-bold text-white whitespace-nowrap transition" data-target="semua">Semua</button>
        <button type="button" onclick="filterSection('taksonomi')" class="tab-btn rounded-full bg-white border border-[#e0e9e1] px-4 py-1.5 text-[11px] font-bold text-[#38584d] whitespace-nowrap transition" data-target="taksonomi">Taksonomi</button>
        <button type="button" onclick="filterSection('deskripsi')" class="tab-btn rounded-full bg-white border border-[#e0e9e1] px-4 py-1.5 text-[11px] font-bold text-[#38584d] whitespace-nowrap transition" data-target="deskripsi">Deskripsi</button>
        <button type="button" onclick="filterSection('manfaat')" class="tab-btn rounded-full bg-white border border-[#e0e9e1] px-4 py-1.5 text-[11px] font-bold text-[#38584d] whitespace-nowrap transition" data-target="manfaat">Manfaat</button>
        <button type="button" onclick="filterSection('asal-daerah')" class="tab-btn rounded-full bg-white border border-[#e0e9e1] px-4 py-1.5 text-[11px] font-bold text-[#38584d] whitespace-nowrap transition" data-target="asal-daerah">Asal Daerah</button>
    </nav>

    <div class="space-y-6">
        <!-- 1. TAKSONOMI BOTANI -->
        <div class="content-section" data-section="taksonomi">
            <section class="rounded-2xl bg-white border border-[#e0e9e1] p-4 shadow-sm">
                <div class="mb-3 flex items-center gap-2.5">
                    <div>
                        <h2 class="font-['Manrope'] text-sm font-extrabold text-[#111827]">TAKSONOMI BOTANI</h2>
                        <p class="text-[10px] text-[#49645b]">Klasifikasi biologis tanaman</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-2.5">
                        <span class="block text-[9px] font-bold tracking-wider text-[#8a9991] uppercase">Kingdom</span>
                        <span class="font-semibold text-[#111827]">Plantae</span>
                    </div>
                    <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-2.5">
                        <span class="block text-[9px] font-bold tracking-wider text-[#8a9991] uppercase">Ordo</span>
                        <span class="font-semibold text-[#111827]">{{ $plant->ordo ?: '-' }}</span>
                    </div>
                    <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-2.5">
                        <span class="block text-[9px] font-bold tracking-wider text-[#8a9991] uppercase">Famili</span>
                        <span class="font-semibold text-[#111827]">{{ $plant->family ?? '-' }}</span>
                    </div>
                    <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-2.5">
                        <span class="block text-[9px] font-bold tracking-wider text-[#8a9991] uppercase">Genus</span>
                        <span class="font-semibold text-[#111827]">{{ $plant->genus ?: '-' }}</span>
                    </div>
                    <div class="col-span-2 rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-2.5">
                        <span class="block text-[9px] font-bold tracking-wider text-[#8a9991] uppercase">Botanist</span>
                        <span class="font-semibold text-[#111827]">{{ $plant->botanist ?: '-' }}</span>
                    </div>
                </div>
            </section>
        </div>

        <!-- 2. DESKRIPSI MORFOLOGI -->
        <div class="content-section" data-section="deskripsi">
            <section class="rounded-2xl bg-white border border-[#e0e9e1] p-4 shadow-sm">
                <div class="mb-3">
                    <h2 class="font-['Manrope'] text-sm font-extrabold text-[#111827]">DESKRIPSI MORFOLOGI</h2>
                    <p class="text-[10px] text-[#49645b]">Karakteristik fisik & rasa</p>
                </div>
                <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-3 text-xs leading-relaxed text-[#4b5563]">
                    {{ $plant->description_full ?: $plant->description_short ?? 'Belum ada deskripsi mendalam untuk tanaman ini.' }}
                </div>
            </section>
        </div>

        <!-- 3. MANFAAT FARMAKOLOGIS -->
        <div class="content-section" data-section="manfaat">
            <section class="rounded-2xl bg-white border border-[#e0e9e1] p-4 shadow-sm">
                <div class="mb-3">
                    <h2 class="font-['Manrope'] text-sm font-extrabold text-[#111827]">MANFAAT FARMAKOLOGIS</h2>
                    <p class="text-[10px] text-[#49645b]">Khasiat terapeutik teruji</p>
                </div>
                <div class="space-y-2">
                    @if($plant->benefits)
                        @php
                            $benefitItems = preg_split('/\r\n|\r|\n|;/', $plant->benefits);
                        @endphp
                        @foreach($benefitItems as $item)
                            @if(trim($item) !== '')
                                <div class="flex items-start gap-2.5 rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-3">
                                    <span class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-[#e8f3ea] text-[#003f32] text-xs font-bold">✓</span>
                                    <p class="text-xs leading-relaxed text-[#4b5563] pt-0.5">{{ trim(ltrim(trim($item), '-•*')) }}</p>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-3 text-xs text-[#4b5563]">
                            Informasi manfaat belum ditambahkan.
                        </div>
                    @endif
                </div>
            </section>
        </div>

        <!-- 4. ASAL DAERAH & HABITAT -->
        <div class="content-section" data-section="asal-daerah">
            <section class="rounded-2xl bg-white border border-[#e0e9e1] p-4 shadow-sm">
                <div class="mb-3">
                    <h2 class="font-['Manrope'] text-sm font-extrabold text-[#111827]">ASAL DAERAH & HABITAT</h2>
                    <p class="text-[10px] text-[#49645b]">Penyebaran agroekosistem</p>
                </div>
                <div class="rounded-xl bg-[#fafafa] border border-[#f0f0f0] p-3 text-xs leading-relaxed text-[#4b5563]">
                    {{ $plant->origin_distribution ?? 'Data asal daerah dan sebaran botani belum ditambahkan.' }}
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('[data-carousel]').forEach((carousel) => {
        const track = carousel.querySelector('[data-carousel-track]');
        if (!track) return;
        const slides = Array.from(track.children);
        const dots = Array.from(carousel.querySelectorAll('[data-carousel-dot]'));
        let currentIndex = 0;
        let autoplay;

        if (slides.length < 2) return;

        const showSlide = (index) => {
            currentIndex = (index + slides.length) % slides.length;
            track.scrollTo({ left: slides[currentIndex].offsetLeft, behavior: 'smooth' });
            dots.forEach((dot, dotIndex) => {
                dot.classList.toggle('w-4', dotIndex === currentIndex);
                dot.classList.toggle('bg-[#003f32]', dotIndex === currentIndex);
                dot.classList.toggle('w-1.5', dotIndex !== currentIndex);
                dot.classList.toggle('bg-[#b8c8bd]', dotIndex !== currentIndex);
            });
        };

        const startAutoplay = () => {
            clearInterval(autoplay);
            autoplay = setInterval(() => showSlide(currentIndex + 1), 4500);
        };

        const prevBtn = carousel.querySelector('[data-carousel-prev]');
        const nextBtn = carousel.querySelector('[data-carousel-next]');
        if (prevBtn) prevBtn.addEventListener('click', () => { showSlide(currentIndex - 1); startAutoplay(); });
        if (nextBtn) nextBtn.addEventListener('click', () => { showSlide(currentIndex + 1); startAutoplay(); });

        dots.forEach((dot) => dot.addEventListener('click', () => {
            showSlide(Number(dot.dataset.carouselDot));
            startAutoplay();
        }));
        carousel.addEventListener('mouseenter', () => clearInterval(autoplay));
        carousel.addEventListener('mouseleave', startAutoplay);
        startAutoplay();
    });

    function filterSection(target) {
        const buttons = document.querySelectorAll('.tab-btn');
        const sections = document.querySelectorAll('.content-section');

        buttons.forEach(btn => {
            if (btn.getAttribute('data-target') === target) {
                btn.classList.remove('bg-white', 'border', 'border-[#e0e9e1]', 'text-[#38584d]');
                btn.classList.add('bg-[#003f32]', 'text-white');
            } else {
                btn.classList.add('bg-white', 'border', 'border-[#e0e9e1]', 'text-[#38584d]');
                btn.classList.remove('bg-[#003f32]', 'text-white');
            }
        });

        sections.forEach(sec => {
            if (target === 'semua' || sec.getAttribute('data-section') === target) {
                sec.style.display = 'block';
            } else {
                sec.style.display = 'none';
            }
        });
    }
</script>
@endsection
