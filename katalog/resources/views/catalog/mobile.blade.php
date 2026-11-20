@extends('layouts.app')

@section('title', $title ?? 'Katalog Tanaman Herbal TSTH2')

@section('content')
    <header class="mb-5 text-left">
        <h1 class="font-['Manrope'] text-2xl font-extrabold tracking-tight text-[#003f32]">{{ $title ?? 'Koleksi Tanaman Herbal' }}</h1>
        <p class="mt-1 text-xs leading-relaxed text-[#49645b]">Pindai & Jelajahi Koleksi Plasma Nutfah Tanaman Obat Indonesia.</p>
    </header>

    @php
        $currentRoute = request()->routeIs('collection') ? 'collection' : 'home';
    @endphp

    <nav class="mb-5 flex rounded-2xl border border-[#e0e9e1] bg-white p-1 shadow-sm" aria-label="Pilih lokasi utama">
        <a href="{{ route('home', request()->except('location')) }}" class="flex-1 rounded-xl px-3 py-2.5 text-center text-xs font-bold transition {{ $currentRoute === 'home' ? 'bg-[#003f32] text-white shadow-sm' : 'text-[#38584d]' }}">Greenhouse</a>
        <a href="{{ route('collection', request()->except('location')) }}" class="flex-1 rounded-xl px-3 py-2.5 text-center text-xs font-bold transition {{ $currentRoute === 'collection' ? 'bg-[#003f32] text-white shadow-sm' : 'text-[#38584d]' }}">Outdoor</a>
    </nav>

    <section class="mb-5 rounded-2xl border border-[#e0e9e1] bg-white p-3 shadow-sm">
        <form method="GET" action="{{ route($currentRoute) }}" class="flex flex-col gap-3">
            <div class="flex items-center gap-2">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama tanaman atau khasiat..." class="h-10 min-w-0 flex-1 rounded-xl border border-[#e0e9e1] bg-[#f8fcf8] px-3.5 text-xs text-[#123b32] focus:border-[#00634e] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#00634e]">
                <button type="submit" class="h-10 rounded-xl bg-[#003f32] px-4 text-xs font-bold text-white shadow-sm transition hover:bg-[#002e24]">Cari</button>
            </div>

        <nav class="flex flex-nowrap items-center gap-2 overflow-x-auto pb-1 scrollbar-none" aria-label="Filter kategori">
            @foreach ($categories as $cat)
                <label class="flex-shrink-0 cursor-pointer">
                    <input type="checkbox" name="category[]" value="{{ $cat->slug }}" class="peer sr-only" onchange="this.form.submit()" @checked(in_array($cat->slug, $selectedCategories, true))>
                    <span class="inline-flex whitespace-nowrap rounded-full border border-[#e0e9e1] bg-white px-3 py-1.5 text-[10px] font-semibold text-[#38584d] transition peer-checked:border-[#003f32] peer-checked:bg-[#003f32] peer-checked:text-white">
                        {{ $cat->name }}
                    </span>
                </label>
            @endforeach
            <a href="{{ route($currentRoute, request()->except('category')) }}" class="flex-shrink-0 rounded-full border border-red-200 px-3 py-1.5 text-[10px] font-semibold text-red-700">Reset Filter</a>
        </nav>
        </form>
    </section>

    <!-- Info Jumlah Tanaman -->
    <p class="mb-2.5 text-[11px] text-[#557067]">
        Menampilkan <span class="font-bold text-[#003f32]">{{ $plants->count() }}</span> dari
        <span class="font-bold text-[#003f32]">{{ $plants->total() }}</span> tanaman
    </p>

    <!-- Tampilan List Mobile -->
    <!-- PERUBAHAN DI SINI: space-y-2.5 diubah menjadi space-y-4 agar jarak antar kartu lebih lega -->
    <section class="space-y-3">
        @forelse ($plants as $plant)
            <a href="{{ route('plant.show', $plant) }}" class="flex items-center gap-3 rounded-2xl border border-[#e0e9e1] bg-white p-3 shadow-sm transition hover:bg-[#fafafa]">
                <!-- Gambar Mini (Terkunci kecil 56x56 px) -->
                                {{-- Gambar Mini dengan Fallback --}}
                <div class="h-16 w-16 shrink-0 overflow-hidden rounded-xl bg-[#f0f5f1] relative">
                    @if ($plant->image)
                        <img src="{{ route('filebase.show', ['path' => 'plants/' . $plant->image]) }}" alt="{{ $plant->name }}" class="h-full w-full object-cover transition group-hover:scale-105" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div style="display:none;" class="absolute inset-0 flex items-center justify-center text-2xl">🌿</div>
                    @else
                        <div class="flex h-full w-full items-center justify-center text-2xl">🌿</div>
                    @endif
                </div>
                
                <!-- Informasi Teks -->
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-2 mb-0.5">
                        <h2 class="truncate font-['Manrope'] text-xs font-extrabold text-[#111827]">{{ $plant->name }}</h2>
                        <!-- Nama Kategori di Kartu -->
                        <span class="flex-shrink-0 rounded bg-[#fef0c7] px-2 py-0.5 text-[9px] font-bold text-[#b54708]">{{ $plant->category?->name ?? 'Herbal' }}</span>
                    </div>
                    <p class="truncate text-[10px] italic text-[#d97706] mb-0.5">{{ $plant->latin_name }}</p>
                    <p class="line-clamp-1 text-[10px] text-[#4b5563]">{{ $plant->description_short }}</p>
                </div>

                <!-- Panah Kanan -->
                <div class="flex-shrink-0 text-[#8a9991] pl-1">
                    <span class="text-xs font-bold">›</span>
                </div>
            </a>
        @empty
            <div class="py-10 text-center text-xs text-[#557067]">Tidak ada tanaman yang cocok.</div>
        @endforelse
    </section>

    @if ($plants->hasPages())
        <div class="mt-5 flex justify-center text-xs">{{ $plants->links() }}</div>
    @endif
@endsection