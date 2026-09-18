<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Tanaman - TSTH2</title>
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

    <main style="padding-top: 80px;">
        <div class="coming-soon-container">
            <div class="icon-wip">🌱</div>
            <h1 style="font-size: 3rem; color: var(--color-primary-dark); margin-bottom: 1rem; font-family: var(--font-heading);">Halaman Dalam Pengembangan</h1>
            <p style="font-size: 1.2rem; color: var(--color-text-muted); max-width: 600px; line-height: 1.6;">
                Katalog Tanaman TSTH2 (Ensiklopedia Flora) sedang dalam tahap pembangunan sistem. Nantikan koleksi data plasma nutfah herbal terlengkap segera hadir di sini!
            </p>
            <a href="{{ route('home') }}" class="btn btn-primary" style="margin-top: 2.5rem; padding: 1rem 2rem; font-size: 1.1rem; border-radius: 999px; box-shadow: 0 10px 20px rgba(10, 102, 85, 0.2);">Kembali ke Beranda</a>
        </div>
    </main>
    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
