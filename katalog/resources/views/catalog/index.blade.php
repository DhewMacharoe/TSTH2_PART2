@extends('layouts.app')

@section('title', $title ?? 'Katalog Tanaman Herbal TSTH2')

@section('content')

    @php
        $currentRoute = request()->routeIs('collection') ? 'collection' : 'home';
        $totalPlants = $plants instanceof \Illuminate\Pagination\LengthAwarePaginator ? $plants->total() : $plants->count();
        $displayedPlants = $plants->count();
    @endphp

    {{-- 1. BAGIAN HEADER --}}
    <header class="mb-8 text-center" style="margin-bottom: 2rem; text-align: center;">
        <h1 class="text-4xl font-extrabold tracking-tight text-emerald-900 sm:text-5xl" style="font-family: 'Montserrat', sans-serif; color: #064e3b; font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem;">
            Katalog Tanaman
        </h1>
        <h2 class="text-2xl font-bold italic text-yellow-600 sm:text-3xl" style="font-family: serif; color: #d97706; font-size: 1.5rem; font-weight: 700; font-style: italic;">
            Koleksi Greenhouse & Outdoor TSTH2
        </h2>
    </header>

    {{-- 2. LOKASI, PENCARIAN & FILTER --}}
    <!-- Lokasi Utama -->
    <nav class="mb-5 flex flex-wrap justify-center gap-3" aria-label="Pilih lokasi utama" style="display: flex; justify-content: center; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
        <a href="{{ route('home', request()->except('location')) }}" 
           class="inline-flex items-center gap-2 rounded-full border px-6 py-2.5 text-sm font-bold transition shadow-sm"
           style="border-radius: 9999px; padding: 0.65rem 1.5rem; font-size: 0.875rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s; {{ $currentRoute === 'home' ? 'background: #003f32; color: #ffffff; border: 1px solid #003f32;' : 'background: #ffffff; color: #374151; border: 1px solid #e5e7eb;' }}">
            <span>Greenhouse <span style="font-weight: 400; opacity: 0.85;">({{ $locationCounts->get('greenhouse', 0) }} Tanaman)</span></span>
        </a>
        <a href="{{ route('collection', request()->except('location')) }}" 
           class="inline-flex items-center gap-2 rounded-full border px-6 py-2.5 text-sm font-bold transition shadow-sm"
           style="border-radius: 9999px; padding: 0.65rem 1.5rem; font-size: 0.875rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s; {{ $currentRoute === 'collection' ? 'background: #003f32; color: #ffffff; border: 1px solid #003f32;' : 'background: #ffffff; color: #374151; border: 1px solid #e5e7eb;' }}">
            <span>Outdoor <span style="font-weight: 400; opacity: 0.85;">({{ $locationCounts->get('collection', 0) }} Tanaman)</span></span>
        </a>
    </nav>
            
    <section class="mb-8 rounded-[2rem] border border-gray-200 bg-white p-5 shadow-sm sm:p-7" style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 1.5rem; padding: 1.75rem; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <form method="GET" action="{{ route($currentRoute) }}">
            <input type="hidden" name="sort" value="{{ request('sort', 'default') }}">

            <!-- Search Box (Di bawah Lokasi) -->
            <div class="relative mb-5 mx-auto max-w-3xl" style="position: relative; max-width: 48rem; margin: 0 auto 1.25rem auto;">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-5" style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); pointer-events: none; display: flex; align-items: center;">
                    <svg style="width: 20px; height: 20px; color: #6b7280;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.35-4.35"/></svg>
                </div>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama tanaman (contoh: Monstera, Calathea...)" 
                       class="block w-full rounded-full border border-gray-200 bg-gray-50 py-3.5 pl-14 pr-6 text-sm text-gray-900 placeholder-gray-500 focus:border-emerald-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-emerald-900 shadow-sm transition-all"
                       style="width: 100%; border-radius: 9999px; border: 1px solid #e5e7eb; background: #f9fafb; padding: 0.85rem 1.5rem 0.85rem 3.5rem; font-size: 0.875rem; color: #111827; box-sizing: border-box; outline: none;">
            </div>

            <!-- Tombol Filter Kategori (Di tengah & wrap) -->
            <div class="flex flex-wrap items-center justify-center gap-2" style="display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 0.5rem;">
                <a href="{{ route($currentRoute, request()->except('category')) }}" 
                   class="inline-flex items-center rounded-full border border-gray-200 bg-white px-4 py-2 text-[11px] font-bold text-gray-700 transition hover:bg-gray-50"
                   style="border-radius: 9999px; border: 1px solid #e5e7eb; background: #ffffff; padding: 0.45rem 1rem; font-size: 0.75rem; font-weight: 700; color: #374151; text-decoration: none;">
                    Semua
                </a>
                @foreach ($categories as $cat)
                    <label class="cursor-pointer" style="cursor: pointer; margin: 0;">
                        <input type="checkbox" name="category[]" value="{{ $cat->slug }}" class="peer sr-only" onchange="this.form.submit()" @checked(in_array($cat->slug, $selectedCategories, true)) style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0;">
                        <span class="inline-flex items-center rounded-full border border-gray-200 bg-white px-4 py-2 text-[11px] font-bold text-gray-700 transition hover:bg-gray-50 peer-checked:border-yellow-600 peer-checked:bg-yellow-600 peer-checked:text-white"
                              style="border-radius: 9999px; border: 1px solid {{ in_array($cat->slug, $selectedCategories, true) ? '#d97706' : '#e5e7eb' }}; background: {{ in_array($cat->slug, $selectedCategories, true) ? '#d97706' : '#ffffff' }}; color: {{ in_array($cat->slug, $selectedCategories, true) ? '#ffffff' : '#374151' }}; padding: 0.45rem 1rem; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center;">
                            {{ $cat->name }}
                        </span>
                    </label>
                @endforeach
                
                <!-- Tombol Reset Filter -->
                <a href="{{ route($currentRoute, request()->except('category')) }}" 
                   class="inline-flex items-center rounded-full border border-red-200 bg-red-50 px-4 py-2 text-[11px] font-bold text-red-600 transition hover:bg-red-100"
                   style="border-radius: 9999px; border: 1px solid #fecaca; background: #fef2f2; color: #dc2626; padding: 0.45rem 1rem; font-size: 0.75rem; font-weight: 700; text-decoration: none;">
                    Reset Filter
                </a>
            </div>
        </form>
    </section>

    {{-- 3. JUMLAH DAN GRID DAFTAR TANAMAN --}}
    <p class="mb-4 text-sm text-[#557067]" style="color: #557067; font-size: 0.875rem; margin-bottom: 1rem;">
        Menampilkan <span class="font-bold text-[#003f32]" style="font-weight: 700; color: #003f32;">{{ $displayedPlants }}</span> dari
        <span class="font-bold text-[#003f32]" style="font-weight: 700; color: #003f32;">{{ $totalPlants }}</span> tanaman
    </p>

    <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.5rem; width: 100%;">
        @forelse ($plants as $plant)
            <a href="{{ route('plant.show', $plant) }}" class="group flex flex-col overflow-hidden rounded-2xl border border-[#f0f0f0] bg-[#fafafa] transition duration-300 hover:-translate-y-1 hover:shadow-md" style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 1rem; overflow: hidden; display: flex; flex-direction: column; text-decoration: none; transition: transform 0.25s, box-shadow 0.25s;">
                <div class="relative h-[200px] w-full overflow-hidden bg-gray-100" style="position: relative; height: 200px; width: 100%; overflow: hidden; background: #f3f4f6;">
                    @if ($plant->image)
                        {{-- Skeleton Loading --}}
                        <div class="skeleton-loader absolute inset-0 flex flex-col items-center justify-center bg-gray-200" style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: #e5e7eb;">
                            <span style="font-size: 2.5rem; opacity: 0.4;">🌿</span>
                        </div>

                        {{-- Gambar Tanaman --}}
                        <img 
                            src="{{ route('filebase.show', ['path' => 'plants/' . $plant->image]) }}" 
                            alt="{{ $plant->name }}" 
                            loading="lazy"
                            onload="this.classList.remove('opacity-0'); if(this.previousElementSibling) this.previousElementSibling.remove();"
                            onerror="this.style.display='none'; if(this.previousElementSibling) { this.previousElementSibling.innerHTML='<span style=\"font-size: 2.5rem; opacity: 0.4;\">🌿</span>'; }"
                            class="relative z-10 h-full w-full object-cover opacity-0 transition-all duration-300 group-hover:scale-105"
                            style="position: relative; z-index: 10; height: 100%; width: 100%; object-fit: cover;"
                        >
                    @else
                        <div class="flex h-full items-center justify-center text-[#477568] transition duration-300 group-hover:scale-105" style="height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%);">
                            <span style="font-size: 2.5rem; opacity: 0.5;">🌿</span>
                        </div>
                    @endif

                    {{-- Badge Kategori --}}
                    <span class="absolute right-3 top-3 z-20 rounded bg-[#fef0c7] px-2.5 py-1 text-[9px] font-bold uppercase tracking-wider text-[#b54708] shadow-sm" style="position: absolute; top: 0.75rem; right: 0.75rem; z-index: 20; background: #fef0c7; color: #b54708; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; padding: 0.25rem 0.6rem; border-radius: 0.375rem; letter-spacing: 0.05em;">
                        {{ $plant->category?->name ?? 'Herbal' }}
                    </span>
                </div>

                <div class="flex flex-1 flex-col p-5" style="padding: 1.25rem; display: flex; flex-direction: column; flex: 1;">
                    <h2 class="font-['Manrope'] text-lg font-extrabold text-[#111827]" style="font-family: 'Outfit', 'Manrope', sans-serif; font-size: 1.15rem; font-weight: 800; color: #111827; margin: 0 0 0.25rem 0;">{{ $plant->name }}</h2>
                    <p class="text-[11px] italic text-[#d97706]" style="font-size: 0.8rem; font-style: italic; color: #d97706; margin: 0 0 0.25rem 0;">{{ $plant->latin_name }}</p>
                    <p class="mt-1 text-[9px] font-bold uppercase tracking-wider text-[#6b7280]" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; color: #6b7280; letter-spacing: 0.05em; margin: 0 0 0.5rem 0;">FAMILI: {{ $plant->family }}</p>
                    <p class="mt-2 line-clamp-3 flex-1 text-[11px] leading-relaxed text-[#4b5563]" style="font-size: 0.8rem; line-height: 1.5; color: #4b5563; margin: 0 0 1rem 0; flex: 1; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">{{ $plant->description_short }}</p>

                    <span class="mt-auto flex h-10 w-full items-center justify-center rounded-lg bg-[#003f32] text-[11px] font-bold text-white transition hover:bg-[#002e24]" style="margin-top: auto; display: flex; height: 2.5rem; width: 100%; align-items: center; justify-content: center; border-radius: 0.5rem; background: #003f32; color: #ffffff; font-size: 0.75rem; font-weight: 700; text-decoration: none;">
                        Lihat Detail Tanaman <span style="margin-left: 0.35rem;">&rarr;</span>
                    </span>
                </div>
            </a>
        @empty
            <div class="col-span-full py-20 text-center text-sm text-[#557067]" style="grid-column: 1 / -1; padding: 4rem 1rem; text-align: center; color: #557067;">
                Tidak ada tanaman yang cocok. <a href="{{ route($currentRoute) }}" class="font-bold text-[#003f32] underline" style="color: #003f32; font-weight: 700; text-decoration: underline;">Reset filter</a>
            </div>
        @endforelse
    </section>

    {{-- PAGINASI --}}
    @if ($plants->hasPages())
        <div class="mt-10 flex justify-center" style="margin-top: 2.5rem; display: flex; justify-content: center;">
            {{ $plants->links() }}
        </div>
    @endif
@endsection
