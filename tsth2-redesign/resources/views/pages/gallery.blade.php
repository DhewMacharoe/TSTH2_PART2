<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri - TSTH2</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <style>
        .coming-soon-container {
            min-height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
            background-color: #f8fafc;
        }
        .icon-wip {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-20px);}
            60% {transform: translateY(-10px);}
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <main style="padding: 120px 5% 80px; max-width: 1200px; margin: 0 auto;">
        @if(!empty($contents['gallery_hero_title']->value))
        <div style="text-align: center; margin-bottom: 3rem;">
            <h1 style="font-size: 3rem; color: var(--color-primary-dark); margin-bottom: 1rem; font-family: var(--font-heading);">{{ $contents['gallery_hero_title']->value ?? '' }}</h1>
            <p style="font-size: 1.2rem; color: var(--color-text-muted);">{{ $contents['gallery_hero_desc']->value ?? '' }}</p>
        </div>
        @endif

        <div style="display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center;">
            @forelse($galleries as $item)
            <div style="width: 320px; background: {{ $loop->iteration % 2 == 1 ? '#164E3F' : '#9C7A26' }}; padding: 15px; border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; box-shadow: 2px 2px 5px rgba(0,0,0,0.1);">
                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="lightbox-img" data-title="{{ $item->title }}" data-desc="{{ $item->description }}" style="width: 100%; height: auto; border-bottom: 2px solid rgba(255,255,255,0.2); border-radius: 8px; margin-bottom: 10px; aspect-ratio: 16/10; object-fit: cover; cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                <h3 style="color: white; font-size: 1.1rem; text-align: center; margin: 0; padding-top: 5px;">{{ $item->title }}</h3>
                <p style="font-size: 0.9rem; color: rgba(255,255,255,0.9); text-align: center; margin-top: 5px;">{{ $item->description }}</p>
            </div>
            @empty
            <div style="width: 100%; text-align: center; padding: 3rem; color: #94a3b8;">Belum ada foto galeri.</div>
            @endforelse
        </div>
    </main>
    
    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
