<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Terkini - TSTH2</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
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
        <section style="padding: 5rem 0; background: var(--color-background);">
            <div class="container">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem;">
                    @forelse($news as $item)
                    <div style="background: {{ $loop->iteration % 2 == 1 ? '#164E3F' : '#9C7A26' }}; color: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.3s;">
                        <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : asset('assets/default_hero.jpg') }}" alt="{{ $item->title }}" style="width: 100%; height: 200px; object-fit: cover;">
                        <div style="padding: 1.5rem;">
                            <span style="font-size: 0.8rem; color: #f1c40f; font-weight: 600; text-transform: uppercase;">{{ $item->category }}</span>
                            <h3 style="font-family: var(--font-heading); margin: 0.5rem 0; color: white;">{{ $item->title }}</h3>
                            <p style="color: rgba(255,255,255,0.9); font-size: 0.9rem; margin-bottom: 1rem;">{{ Str::limit($item->description, 100) }}</p>
                            <span style="font-size: 0.8rem; color: rgba(255,255,255,0.7);">{{ $item->date }}</span>
                        </div>
                    </div>
                    @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #94a3b8;">Belum ada berita terbaru.</div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
