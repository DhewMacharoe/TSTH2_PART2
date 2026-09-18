<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penelitian - TSTH2</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
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
        <section style="padding: 5rem 0; background: var(--color-background);">
            <div class="container">
                <div style="display: flex; flex-direction: column; gap: 4rem;">
                    @forelse($researches as $item)
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center; background: {{ $loop->iteration % 2 == 1 ? '#164E3F' : '#9C7A26' }}; padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-lg);">
                        <div style="border-radius: 20px; overflow: hidden; order: {{ $loop->iteration % 2 == 0 ? 2 : 1 }};">
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="lightbox-img" data-title="{{ $item->title }}" data-desc="{{ $item->description }}" style="width: 100%; height: 300px; object-fit: cover;">
                        </div>
                        <div style="order: {{ $loop->iteration % 2 == 0 ? 1 : 2 }};">
                            <h2 style="font-family: var(--font-heading); color: white; margin-bottom: 1rem;">{{ $item->title }}</h2>
                            <p style="color: rgba(255,255,255,0.9); line-height: 1.8; margin-bottom: 1.5rem;">{{ $item->description }}</p>
                            <a href="#" style="color: #f1c40f; font-weight: 600; text-decoration: none;">Pelajari Publikasi Kami →</a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center" style="padding: 3rem; color: #94a3b8; text-align: center;">Belum ada data fasilitas/riset.</div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
