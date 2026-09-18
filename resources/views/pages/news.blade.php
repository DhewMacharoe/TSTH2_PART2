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
            <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm" style="display: none;">
                <div class="bg-white rounded-2xl w-full max-w-3xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden m-4" @click.away="open = false">
                    <div class="p-4 border-b flex justify-between items-center bg-gray-50">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-primary uppercase" x-text="activeItem.category"></span>
                            <h3 class="text-xl font-bold text-gray-900" x-text="activeItem.title"></h3>
                        </div>
                        <button @click="open = false" class="text-gray-500 hover:text-red-500 font-bold text-xl">&times;</button>
                    </div>
                    <div class="p-6 overflow-y-auto custom-scrollbar">
                        <template x-if="activeItem.image_path">
                            <img :src="`/storage/${activeItem.image_path}`" class="w-full rounded-xl mb-6 shadow-md object-cover" style="max-height: 400px;">
                        </template>
                        <template x-if="!activeItem.image_path">
                            <img src="{{ asset('assets/default_hero.jpg') }}" class="w-full rounded-xl mb-6 shadow-md object-cover" style="max-height: 400px;">
                        </template>
                        <span class="text-gray-500 text-sm mb-4 block" x-text="activeItem.formatted_date"></span>
                        <div class="text-gray-700 leading-relaxed whitespace-pre-wrap text-lg" x-text="activeItem.description"></div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
