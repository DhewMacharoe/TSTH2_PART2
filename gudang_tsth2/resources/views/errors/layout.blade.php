<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title') - TSTH2</title>
    @include('partials.pwa-head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:500,600,700,800|inter:400,500,600,700" rel="stylesheet" />
    <!-- Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>.material-symbols-rounded{font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;}</style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#054439',
                            dark: '#02231d',
                            light: '#0a6655',
                        },
                        accent: {
                            DEFAULT: '#c09e5c',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4 antialiased text-gray-800">
    <div class="max-w-lg w-full bg-white rounded-3xl p-8 sm:p-10 shadow-xl border border-gray-100 text-center relative overflow-hidden">
        <!-- Ambient Glow -->
        <div class="absolute -top-20 -right-20 w-44 h-44 bg-emerald-100/50 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 w-44 h-44 bg-amber-100/40 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <!-- Icon Badge -->
            <div class="w-20 h-20 mx-auto rounded-3xl bg-gray-50 border border-gray-100 flex items-center justify-center shadow-inner mb-6">
                @yield('icon', '<span class="material-symbols-rounded" style="font-size:2.5rem;color:#94a3b8;">warning</span>')
            </div>

            <!-- Error Code & Title -->
            <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-widest bg-gray-100 text-gray-600 mb-3">
                Error @yield('code')
            </span>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight mb-3 font-heading">
                @yield('heading')
            </h1>

            <p class="text-gray-500 text-sm leading-relaxed mb-8 max-w-sm mx-auto">
                @yield('message')
            </p>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ url('/') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary-light text-white font-bold py-3 px-6 rounded-xl transition text-sm shadow-md">
                    <span class="material-symbols-rounded" style="font-size:18px;">home</span> Kembali ke Beranda
                </a>
                <button onclick="window.history.back()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 px-6 rounded-xl transition text-sm">
                    <span class="material-symbols-rounded" style="font-size:18px;">arrow_back</span> Halaman Sebelumnya
                </button>
            </div>
        </div>
    </div>
</body>
</html>
