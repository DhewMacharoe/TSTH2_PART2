<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Terkini - TSTH2</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    @include('partials.navbar')

    <main style="padding-top: 80px;">
        <!-- Page Banner -->
        @if(!empty($contents['news_hero_title']->value))
        <section style="background-color: var(--color-primary-dark); padding: 6rem 0; text-align: center; color: white;">
            <div class="container">
                <h1 style="font-size: 3rem; margin-bottom: 1rem;">{{ $contents['news_hero_title']->value ?? '' }}</h1>
                <p style="font-size: 1.1rem; max-width: 600px; margin: 0 auto; opacity: 0.9;">{{ $contents['news_hero_desc']->value ?? '' }}</p>
            </div>
        </section>
        @endif

        <!-- Content Area -->
        <section style="padding: 5rem 0; background: var(--color-background);" x-data="{ open: false, activeItem: {} }">
            <div class="container">
                <!-- Search Bar -->
                <div style="text-align: center; margin-bottom: 3rem;">
                    <form action="{{ route('news') }}" method="GET" style="display: flex; justify-content: center; max-width: 500px; margin: 0 auto;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita..." style="flex-grow: 1; padding: 1rem 1.5rem; border-radius: 30px 0 0 30px; border: 1px solid #ccc; font-size: 1rem; outline: none;">
                        <button type="submit" style="padding: 1rem 2rem; background: var(--color-primary); color: white; border: none; border-radius: 0 30px 30px 0; font-weight: bold; cursor: pointer; font-size: 1rem;">Cari</button>
                    </form>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem;">
                    @forelse($news as $item)
                    <div @click="activeItem = {{ json_encode($item) }}; activeItem.formatted_date = '{{ $item->date ? \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') : '-' }}'; open = true" style="background: {{ $loop->iteration % 2 == 1 ? '#164E3F' : '#9C7A26' }}; color: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.3s; cursor: pointer;" class="hover:scale-105">
                        <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : asset('assets/default_hero.jpg') }}" alt="{{ $item->title }}" style="width: 100%; height: 200px; object-fit: cover;">
                        <div style="padding: 1.5rem;">
                            <span style="font-size: 0.8rem; color: #f1c40f; font-weight: 600; text-transform: uppercase;">{{ $item->category }}</span>
                            <h3 style="font-family: var(--font-heading); margin: 0.5rem 0; color: white;">{{ $item->title }}</h3>
                            <p style="color: rgba(255,255,255,0.9); font-size: 0.9rem; margin-bottom: 1rem;">{{ Str::limit($item->description, 100) }}</p>
                            <span style="font-size: 0.8rem; color: rgba(255,255,255,0.7);">{{ $item->date ? \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') : '-' }}</span>
                            <div style="margin-top: 1rem; color: #f1c40f; font-size: 0.9rem; font-weight: bold;">Baca Selengkapnya &rarr;</div>
                        </div>
                    </div>
                    @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #94a3b8;">Belum ada berita terbaru.</div>
                    @endforelse
                </div>
            </div>

            <!-- Detail Modal -->
            <div x-show="open" style="position: fixed; inset: 0; z-index: 9999; background-color: rgba(0,0,0,0.7); backdrop-filter: blur(4px); display: none;">
                <div style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; padding: 1rem;">
                    <div style="background-color: white; border-radius: 1rem; width: 100%; max-width: 800px; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden;" @click.away="open = false">
                    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: flex-start; background-color: #f9fafb;">
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-size: 0.85rem; font-weight: bold; color: var(--color-primary); text-transform: uppercase; margin-bottom: 0.25rem;" x-text="activeItem.category"></span>
                            <h3 style="font-size: 1.25rem; font-weight: bold; color: #111827; margin: 0; font-family: var(--font-heading);" x-text="activeItem.title"></h3>
                        </div>
                        <button @click="open = false" style="background: none; border: none; color: #6b7280; font-size: 1.5rem; font-weight: bold; cursor: pointer; line-height: 1;">&times;</button>
                    </div>
                    <div style="padding: 1.5rem; overflow-y: auto; flex-grow: 1;">
                        <template x-if="activeItem.image_path">
                            <img :src="`/storage/${activeItem.image_path}`" style="width: 100%; border-radius: 0.75rem; margin-bottom: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); object-fit: contain; max-height: 400px; display: block; margin-left: auto; margin-right: auto;">
                        </template>
                        <template x-if="!activeItem.image_path">
                            <img src="{{ asset('assets/default_hero.jpg') }}" style="width: 100%; border-radius: 0.75rem; margin-bottom: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); object-fit: contain; max-height: 400px; display: block; margin-left: auto; margin-right: auto;">
                        </template>
                        <span style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem; display: block;" x-text="activeItem.formatted_date"></span>
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
