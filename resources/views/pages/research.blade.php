<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penelitian - TSTH2</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    @include('partials.navbar')

    <main style="padding-top: 80px;">
        <!-- Page Banner -->
        @if(!empty($contents['research_hero_title']->value))
        <section style="background-image: linear-gradient(rgba(10, 102, 85, 0.8), rgba(4, 42, 35, 0.9)), url(&quot;{{ isset($contents['research_hero_bg']) && $contents['research_hero_bg']->image_path ? asset('storage/' . $contents['research_hero_bg']->image_path) : '' }}&quot;); background-size: cover; background-position: center; padding: 6rem 0; text-align: center; color: white;">
            <div class="container">
                <h1 style="font-size: 3rem; margin-bottom: 1rem;">{{ $contents['research_hero_title']->value ?? '' }}</h1>
                <p style="font-size: 1.1rem; max-width: 600px; margin: 0 auto; opacity: 0.9;">{{ $contents['research_hero_desc']->value ?? '' }}</p>
            </div>
        </section>
        @endif

        <!-- Content Area -->
        <section style="padding: 5rem 0; background: var(--color-background);" x-data="{ open: false, activeItem: {} }">
            <div class="container">
                <!-- Search Bar -->
                <div style="text-align: center; margin-bottom: 3rem;">
                    <form action="{{ route('research') }}" method="GET" style="display: flex; justify-content: center; max-width: 500px; margin: 0 auto;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari fasilitas/riset..." style="flex-grow: 1; padding: 1rem 1.5rem; border-radius: 30px 0 0 30px; border: 1px solid #ccc; font-size: 1rem; outline: none;">
                        <button type="submit" style="padding: 1rem 2rem; background: var(--color-primary); color: white; border: none; border-radius: 0 30px 30px 0; font-weight: bold; cursor: pointer; font-size: 1rem;">Cari</button>
                    </form>
                </div>

                <div style="display: flex; flex-direction: column; gap: 4rem;">
                    @forelse($researches as $item)
                    <div @click="activeItem = {{ json_encode($item) }}; open = true" style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center; background: {{ $loop->iteration % 2 == 1 ? '#164E3F' : '#9C7A26' }}; padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-lg); cursor: pointer; transition: transform 0.3s;" class="hover:scale-105 md:grid-cols-2 grid-cols-1">
                        <div style="border-radius: 20px; overflow: hidden; order: {{ $loop->iteration % 2 == 0 ? 2 : 1 }};">
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" style="width: 100%; height: 300px; object-fit: cover;">
                        </div>
                        <div style="order: {{ $loop->iteration % 2 == 0 ? 1 : 2 }};">
                            <h2 style="font-family: var(--font-heading); color: white; margin-bottom: 1rem;">{{ $item->title }}</h2>
                            <p style="color: rgba(255,255,255,0.9); line-height: 1.8; margin-bottom: 1.5rem;">{{ Str::limit($item->description, 150) }}</p>
                            <span style="color: #f1c40f; font-weight: 600; text-decoration: none;">Lihat Selengkapnya →</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center" style="padding: 3rem; color: #94a3b8; text-align: center;">Belum ada data fasilitas/riset.</div>
                    @endforelse
                </div>
            </div>

            <!-- Detail Modal -->
            <div x-show="open" style="position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; background-color: rgba(0,0,0,0.7); backdrop-filter: blur(4px); display: none;">
                <div style="background-color: white; border-radius: 1rem; width: 100%; max-width: 800px; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden; margin: 1rem;" @click.away="open = false">
                    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; background-color: #f9fafb;">
                        <h3 style="font-size: 1.25rem; font-weight: bold; color: #111827; margin: 0; font-family: var(--font-heading);" x-text="activeItem.title"></h3>
                        <button @click="open = false" style="background: none; border: none; color: #6b7280; font-size: 1.5rem; font-weight: bold; cursor: pointer; line-height: 1;">&times;</button>
                    </div>
                    <div style="padding: 1.5rem; overflow-y: auto; flex-grow: 1;">
                        <template x-if="activeItem.image_path">
                            <img :src="`/storage/${activeItem.image_path}`" style="width: 100%; border-radius: 0.75rem; margin-bottom: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); object-fit: contain; max-height: 400px; display: block; margin-left: auto; margin-right: auto;">
                        </template>
                        <div style="color: #374151; line-height: 1.8; white-space: pre-wrap; font-size: 1.1rem; text-align: left;" x-text="activeItem.description"></div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
