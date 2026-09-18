<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Utama - TSTH2</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    @include('partials.navbar')

    <main style="padding-top: 80px;">
        <!-- Page Banner -->
        @if(!empty($contents['program_hero_title']->value))
        <section style="background-image: linear-gradient(rgba(10, 102, 85, 0.8), rgba(4, 42, 35, 0.9)), url(&quot;{{ isset($contents['program_hero_bg']) && $contents['program_hero_bg']->image_path ? asset('storage/' . $contents['program_hero_bg']->image_path) : '' }}&quot;); background-size: cover; background-position: center; padding: 6rem 0; text-align: center; color: white;">
            <div class="container">
                <h1 style="font-size: 3rem; margin-bottom: 1rem;">{{ $contents['program_hero_title']->value ?? '' }}</h1>
                <p style="font-size: 1.1rem; max-width: 600px; margin: 0 auto; opacity: 0.9;">{{ $contents['program_hero_desc']->value ?? '' }}</p>
            </div>
        </section>
        @endif

        <!-- Content Area -->
        <section style="padding: 5rem 0; background: var(--color-background);" x-data="{ open: false, activeItem: {} }">
            <div class="container">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                    @foreach($programs as $item)
                    <div @click="activeItem = {{ json_encode($item) }}; open = true" style="background: {{ $loop->iteration % 2 == 1 ? '#164E3F' : '#9C7A26' }}; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: transform 0.3s ease; cursor: pointer;" class="hover:scale-105">
                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" style="width: 100%; height: 200px; object-fit: cover;">
                        @endif
                        <div style="padding: 2rem;">
                            <h3 style="font-family: var(--font-heading); margin-bottom: 1rem; color: white;">{{ $item->title }}</h3>
                            <p style="color: rgba(255,255,255,0.9); line-height: 1.6;">{{ Str::limit($item->description, 100) }}</p>
                            <div style="margin-top: 1rem; color: #f1c40f; font-size: 0.9rem; font-weight: bold;">Lihat Detail &rarr;</div>
                        </div>
                    </div>
                    @endforeach
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
                            <img :src="`/storage/${activeItem.image_path}`" class="w-full rounded-xl mb-6 shadow-md">
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
