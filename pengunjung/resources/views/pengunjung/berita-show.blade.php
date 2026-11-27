<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $article['title'] }} - TSTH2</title>
    @include('partials.pwa-head')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ time() }}">
    <style>
        .article-container {
            max-width: 820px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem 5rem 1.5rem;
        }
    </style>
</head>
<body style="background: #f8faf9;">
    @include('partials.navbar')

    <main>
        <article class="article-container">
            <!-- Back navigation -->
            <div style="margin-bottom: 2rem;">
                <a href="{{ route('berita') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #164E3F; font-weight: 700; text-decoration: none; font-size: 0.95rem; background: #e8f5e9; padding: 0.5rem 1.25rem; border-radius: 999px; transition: all 0.2s;">
                    &larr; {{ __('site.back_to_news', ['default' => 'Kembali ke Semua Berita']) }}
                </a>
            </div>

            <!-- Category & Date Meta -->
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                <span style="font-size: 0.78rem; font-weight: 800; color: #164E3F; background: #e8f5e9; padding: 0.35rem 0.85rem; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.5px;">
                    {{ $article['category'] ?? 'Informasi' }}
                </span>
                <span style="color: #64748b; font-size: 0.9rem; font-weight: 600;">
                    {{ $article['date'] ?? '' }}
                </span>
            </div>

            <!-- Headline -->
            <h1 style="font-size: clamp(2rem, 3.8vw, 2.75rem); font-weight: 800; color: #0f172a; line-height: 1.25; margin-bottom: 1.5rem; font-family: var(--font-heading, sans-serif);">
                {{ $article['title'] }}
            </h1>

            <!-- Author & Institution Info -->
            <div style="display: flex; align-items: center; gap: 0.75rem; padding-bottom: 1.75rem; margin-bottom: 2rem; border-bottom: 1px solid #e2e8f0; font-size: 0.9rem; color: #64748b;">
                <div style="width: 38px; height: 38px; border-radius: 50%; background: #164E3F; color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;">
                    TP
                </div>
                <div>
                    <strong style="color: #0f172a; display: block;">{{ __('site.editorial_staff') }}</strong>
                    <span>Taman Sains Teknologi Herbal dan Hortikultura Indonesia</span>
                </div>
            </div>

            <!-- Lead Image (Naturally scrolls with the article!) -->
            @if(!empty($article['image']))
            <figure style="margin: 0 0 2.5rem 0;">
                <img src="{{ asset($article['image']) }}" alt="{{ $article['title'] }}" style="width: 100%; border-radius: 1.25rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); object-fit: cover; max-height: 500px; display: block;">
                <figcaption style="margin-top: 0.75rem; font-size: 0.85rem; color: #94a3b8; text-align: center; font-style: italic;">
                    {{ $article['title'] }}
                </figcaption>
            </figure>
            @endif

            <!-- Body Paragraphs -->
            <div style="color: #334155; font-size: 1.15rem; line-height: 1.95; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
                @if(isset($article['body']) && is_array($article['body']))
                    @foreach($article['body'] as $para)
                        <p style="margin-bottom: 1.5rem; text-align: justify;">{{ $para }}</p>
                    @endforeach
                @else
                    <p style="margin-bottom: 1.5rem; text-align: justify; white-space: pre-wrap;">{{ $article['excerpt'] ?? '' }}</p>
                @endif
            </div>

            <!-- Footer Card -->
            <div style="margin-top: 3.5rem; padding: 2rem; background: #ffffff; border-radius: 1.25rem; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h4 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem 0;">Pusat Publikasi Sains TSTH2</h4>
                    <p style="font-size: 0.88rem; color: #64748b; margin: 0;">Mengabdi untuk kemandirian herbal &amp; hortikultura Indonesia.</p>
                </div>
                <a href="{{ route('berita') }}" class="btn btn-primary" style="background: #164E3F; border-radius: 999px; padding: 0.65rem 1.75rem; text-decoration: none;">
                    &larr; {{ __('site.back_to_news') }}
                </a>
            </div>
        </article>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
