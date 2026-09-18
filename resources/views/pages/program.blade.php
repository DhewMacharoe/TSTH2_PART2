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
                <!-- Search Bar -->
                <div style="text-align: center; margin-bottom: 3rem;">
                    <form action="{{ route('program') }}" method="GET" style="display: flex; justify-content: center; max-width: 500px; margin: 0 auto;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari program..." style="flex-grow: 1; padding: 1rem 1.5rem; border-radius: 30px 0 0 30px; border: 1px solid #ccc; font-size: 1rem; outline: none;">
                        <button type="submit" style="padding: 1rem 2rem; background: var(--color-primary); color: white; border: none; border-radius: 0 30px 30px 0; font-weight: bold; cursor: pointer; font-size: 1rem;">Cari</button>
                    </form>
                </div>

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
            <div x-show="open" style="position: fixed; inset: 0; z-index: 9999; background-color: rgba(0,0,0,0.7); backdrop-filter: blur(4px); display: none;">
                <div style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; padding: 1rem;">
                    <div style="background-color: white; border-radius: 1rem; width: 100%; max-width: 800px; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden;" @click.away="open = false">
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
