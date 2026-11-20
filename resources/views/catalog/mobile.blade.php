@extends('layouts.public')

@section('title', ($konten['katalog_hero_title']->value ?? $title ?? 'Katalog Tanaman Herbal') . ' - TSTH2')

@section('hero_title', $konten['katalog_hero_title']->value ?? $title ?? 'Katalog Tanaman Herbal')
@section('hero_desc', $konten['katalog_hero_desc']->value ?? 'Pindai & Jelajahi Koleksi Plasma Nutfah Tanaman Obat Indonesia.')

@section('content')
<div class="container mx-auto px-4">
    @php
        $currentRoute = request()->routeIs('collection') ? 'collection' : 'katalog';
    @endphp

    <!-- Catalog Location Tab (Mobile) -->
    <div class="mb-4 flex items-center gap-2">
        <a href="{{ route('katalog') }}" class="flex-1 text-center rounded-full px-3 py-1.5 text-[10px] font-bold transition {{ request()->routeIs('katalog') ? 'bg-[#003f32] text-white' : 'bg-white text-[#38584d] border border-[#e0e9e1]' }}">
            <span class="inline-flex items-center justify-center gap-1">
                <span class="material-symbols-rounded text-sm">home</span>
                <span>Greenhouse</span>
            </span>
        </a>
        <a href="{{ route('collection') }}" class="flex-1 text-center rounded-full px-3 py-1.5 text-[10px] font-bold transition {{ request()->routeIs('collection') ? 'bg-[#003f32] text-white' : 'bg-white text-[#38584d] border border-[#e0e9e1]' }}">
            <span class="inline-flex items-center justify-center gap-1">
                <span class="material-symbols-rounded text-sm">park</span>
                <span>Outdoor</span>
            </span>
        </a>
    </div>

    <section class="mb-4 flex flex-col gap-2.5">
        <form method="GET" action="{{ route($currentRoute) }}" class="flex w-full">
            @if(request('category') && request('category') !== 'semua')
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <div class="relative flex flex-1 items-center">
                <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[#164f43]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                <input type="search" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('site.catalog_search_placeholder') }}"
                    class="h-10 w-full rounded-l-full border border-r-0 border-[#e0e9e1] bg-white pl-10 pr-3 text-xs text-[#123b32] shadow-sm focus:border-[#00634e] focus:outline-none">
            </div>
            <button type="submit" class="h-10 shrink-0 rounded-r-full bg-[#003f32] px-4 text-xs font-bold text-white transition hover:bg-[#002e24] focus:outline-none">
                {{ __('site.catalog_search_btn') }}
            </button>
        </form>
        
        <nav class="flex flex-nowrap items-center gap-1.5 overflow-x-auto pb-1.5 scrollbar-none" aria-label="{{ __('site.category') }}">
            @php($activeCategory = request('category', 'semua'))
            <a href="{{ route($currentRoute, array_filter(array_merge(request()->query(), ['category' => 'semua']))) }}"
               style="display: inline-block; white-space: nowrap;"
               class="flex-shrink-0 rounded-full px-3 py-1 text-[10px] font-semibold transition {{ $activeCategory === 'semua' ? 'bg-[#003f32] text-white' : 'bg-white text-[#38584d] border border-[#e0e9e1]' }}">
               {{ __('site.catalog_all_collection') }}
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route($currentRoute, array_filter(array_merge(request()->query(), ['category' => $cat->slug]))) }}"
                   style="display: inline-block; white-space: nowrap;"
                   class="flex-shrink-0 rounded-full px-3 py-1 text-[10px] font-semibold transition {{ $activeCategory === $cat->slug ? 'bg-[#003f32] text-white' : 'bg-white text-[#38584d] border border-[#e0e9e1]' }}">
                   {{ $cat->name }}
                </a>
            @endforeach
        </nav>
    </section>

    <p class="mb-2.5 text-[11px] text-[#557067]">
        {{ __('site.catalog_showing_species', ['count' => $plants->total() ?? count($plants)]) }}
    </p>

    <section class="space-y-4">
        @forelse ($plants as $plant)
            <a href="{{ route('plant.show', $plant) }}" class="flex items-center gap-3 rounded-2xl bg-white border border-[#e0e9e1] p-2.5 shadow-sm transition hover:bg-[#fafafa]">
                <div style="width: 56px; height: 56px; min-width: 56px; min-height: 56px;" class="overflow-hidden rounded-xl bg-[#f0f5f1]">
                    @if ($plant->image)
                        <img src="{{ asset('storage/plants/' . $plant->image) }}" alt="{{ $plant->name }}" style="width: 56px; height: 56px;" class="object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-[#477568]/40"><span class="material-symbols-rounded text-2xl">eco</span></div>
                    @endif
                </div>
                
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-2 mb-0.5">
                        <h2 class="truncate font-['Outfit'] text-xs font-extrabold text-[#111827]">{{ $plant->name }}</h2>
                        <span class="flex-shrink-0 rounded bg-[#fef0c7] px-2 py-0.5 text-[9px] font-bold text-[#b54708]">
                            {{ $plant->category?->name ?? __('site.catalog_herbal_default') }}
                        </span>
                    </div>
                    <p class="truncate text-[10px] italic text-[#d97706] mb-0.5">{{ $plant->latin_name }}</p>
                    <p class="line-clamp-1 text-[10px] text-[#4b5563]">{{ $plant->description_short }}</p>
                </div>

                <div class="flex-shrink-0 text-[#8a9991] pl-1">
                    <span class="text-xs font-bold">›</span>
                </div>
            </a>
        @empty
            <div class="py-10 text-center text-xs text-[#557067]">{{ __('site.catalog_no_match') }}</div>
        @endforelse
    </section>

    @if ($plants->hasPages())
        <div class="mt-5 flex justify-center text-xs">{{ $plants->links() }}</div>
    @endif
</div>
@endsection
