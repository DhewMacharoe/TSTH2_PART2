@extends('layouts.public')

@section('title', ($konten['katalog_hero_title']->value ?? $title ?? 'Katalog Tanaman Herbal') . ' - TSTH2')

@section('hero_title', $konten['katalog_hero_title']->value ?? $title ?? 'Katalog Tanaman Herbal')
@section('hero_desc', $konten['katalog_hero_desc']->value ?? 'Jelajahi Koleksi Plasma Nutfah Tanaman Obat Indonesia di Taman Sains Teknologi Herbal dan Hortikultura.')

@section('content')
<div class="container mx-auto px-4 max-w-7xl">
    @php
        $currentRoute = request()->routeIs('collection') ? 'collection' : 'katalog';
    @endphp

    <!-- Filter & Search -->
    <section class="mb-10 flex flex-col items-center justify-center gap-6">
        <form method="GET" action="{{ route($currentRoute) }}" class="flex w-full max-w-[500px] mx-auto">
            @if(request('category') && request('category') !== 'semua')
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <div class="relative flex flex-1 items-center">
                <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[#164f43]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                <input type="search" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('site.catalog_search_placeholder') }}"
                    class="h-11 w-full rounded-l-full border border-r-0 border-[#e0e9e1] bg-white pl-10 pr-4 text-xs text-[#123b32] shadow-sm placeholder:text-[#8a9991] focus:border-[#00634e] focus:outline-none focus:ring-1 focus:ring-[#00634e]">
            </div>
            <button type="submit" class="h-11 shrink-0 rounded-r-full bg-[#003f32] px-6 text-xs font-bold text-white transition hover:bg-[#002e24] focus:outline-none">
                {{ __('site.catalog_search_btn') }}
            </button>
        </form>
        
        <nav class="flex flex-wrap justify-center gap-2" aria-label="{{ __('site.category') }}">
            @php($activeCategory = request('category', 'semua'))
            <a href="{{ route($currentRoute, array_filter(array_merge(request()->query(), ['category' => 'semua']))) }}"
               class="rounded-full px-4 py-2 text-[11px] font-bold transition {{ $activeCategory === 'semua' ? 'bg-[#003f32] text-white' : 'bg-white text-[#38584d] border border-[#e0e9e1] hover:bg-[#e8f3ea]' }}">
               {{ __('site.catalog_all_collection') }}
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route($currentRoute, array_filter(array_merge(request()->query(), ['category' => $cat->slug]))) }}"
                   class="rounded-full bg-white px-4 py-2 text-[11px] font-bold text-[#38584d] border border-[#e0e9e1] transition hover:bg-[#e8f3ea] {{ $activeCategory === $cat->slug ? '!bg-[#003f32] !text-white' : '' }}">
                   {{ $cat->name }}
                </a>
            @endforeach
        </nav>
    </section>

    <!-- Plant Grid -->
    <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse ($plants as $plant)
            <a href="{{ route('plant.show', $plant) }}" class="group flex flex-col overflow-hidden rounded-2xl bg-white border border-[#e0e9e1] transition duration-300 hover:shadow-md">
                <div class="relative h-[200px] w-full overflow-hidden bg-white">
                    @if ($plant->image)
                        <img src="{{ asset('storage/plants/' . $plant->image) }}" alt="{{ $plant->name }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full items-center justify-center text-[#477568]"><span class="material-symbols-rounded text-5xl opacity-40">eco</span></div>
                    @endif
                    <span class="absolute right-3 top-3 rounded bg-[#fef0c7] px-2.5 py-1 text-[9px] font-bold uppercase tracking-wider text-[#b54708] shadow-sm">
                        {{ $plant->category?->name ?? __('site.catalog_herbal_default') }}
                    </span>
                </div>
                
                <div class="flex flex-1 flex-col p-5">
                    <h2 class="font-['Outfit'] text-lg font-extrabold text-[#111827]">{{ $plant->name }}</h2>
                    <p class="text-[11px] italic text-[#d97706]">{{ $plant->latin_name }}</p>
                    <p class="mt-1.5 text-[9px] font-bold uppercase tracking-wider text-[#6b7280]">{{ __('site.catalog_family_label') }}: {{ $plant->family }}</p>
                    <p class="mt-3 line-clamp-3 flex-1 text-[11px] leading-relaxed text-[#4b5563]">{{ $plant->description_short }}</p>
                    
                    <span class="mt-5 flex h-10 w-full items-center justify-center rounded-lg bg-[#003f32] text-[11px] font-bold text-white transition group-hover:bg-[#002e24]">
                        {{ __('site.catalog_view_detail') }} <span class="ml-1.5">→</span>
                    </span>
                </div>
            </a>
        @empty
            <div class="col-span-full py-20 text-center text-sm text-[#557067]">
                {{ __('site.catalog_no_match') }}
                <a href="{{ route($currentRoute) }}" class="font-bold text-[#003f32] underline">{{ __('site.catalog_reset_filter') }}</a>
            </div>
        @endforelse
    </section>

    @if ($plants->hasPages())
        <div class="mt-10 flex justify-center">{{ $plants->links() }}</div>
    @endif
</div>
@endsection
