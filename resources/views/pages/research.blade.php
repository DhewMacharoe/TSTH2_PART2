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
            <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm" style="display: none;">
                <div class="bg-white rounded-2xl w-full max-w-3xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden m-4" @click.away="open = false">
                    <div class="p-4 border-b flex justify-between items-center bg-gray-50">
                        <h3 class="text-xl font-bold text-gray-900" x-text="activeItem.title"></h3>
                        <button @click="open = false" class="text-gray-500 hover:text-red-500 font-bold text-xl">&times;</button>
                    </div>
                    <div class="p-6 overflow-y-auto custom-scrollbar">
                        <template x-if="activeItem.image_path">
                            <img :src="`/storage/${activeItem.image_path}`" class="w-full rounded-xl mb-6 shadow-md object-cover" style="max-height: 500px;">
                        </template>
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
