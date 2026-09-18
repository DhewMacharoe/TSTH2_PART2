<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Utama - TSTH2</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
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
        <section style="padding: 5rem 0; background: var(--color-background);">
            <div class="container">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                    @foreach($programs as $item)
                    <div style="background: {{ $loop->iteration % 2 == 1 ? '#164E3F' : '#9C7A26' }}; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: transform 0.3s ease;">
                        
                        <div style="padding: 2rem;">
                            <h3 style="font-family: var(--font-heading); margin-bottom: 1rem; color: white;">{{ $item->title }}</h3>
                            <p style="color: rgba(255,255,255,0.9); line-height: 1.6;">{{ $item->description }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
