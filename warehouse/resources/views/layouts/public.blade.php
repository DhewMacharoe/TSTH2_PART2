<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'Katalog & Sains - TSTH2')</title>
    @include('partials.pwa-head')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ time() }}">
    <!-- Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script>tailwind.config = { corePlugins: { preflight: false } }</script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body style="background: var(--color-bg-light);">
    @include('partials.navbar')

    <main style="padding-top: 80px;">
        <!-- Page Banner Konsisten -->
        @hasSection('hero_title')
        <section style="background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%); padding: 5rem 1rem; text-align: center; color: white; position: relative; overflow: hidden; border-bottom: 1px solid rgba(255,255,255,0.1);">
            <div style="position: absolute; top: -50%; left: 50%; transform: translateX(-50%); width: 600px; height: 300px; background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%); pointer-events: none;"></div>
            <div class="container" style="position: relative; z-index: 2;">
                <h1 style="font-size: clamp(2.2rem, 4vw, 3.2rem); margin-bottom: 1rem; color: white; font-family: var(--font-heading); font-weight: 800;">@yield('hero_title')</h1>
                <p style="font-size: 1.15rem; max-width: 680px; margin: 0 auto; color: rgba(255,255,255,0.9); line-height: 1.6;">@yield('hero_desc')</p>
            </div>
        </section>
        @endif

        <div style="padding: 3rem 0; min-height: 75vh;">
            <div class="container">
                @include('partials.dialog-boxes')
            </div>
            @yield('content')
        </div>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
