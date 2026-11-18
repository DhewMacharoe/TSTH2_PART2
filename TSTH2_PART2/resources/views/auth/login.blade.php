<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Login - TSTH2 Hub</title>
    
    <!-- PWA Setup -->
    @include('partials.pwa-head')
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800|inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <!-- Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex min-h-screen">

    <!-- Split Layout Container -->
    <div class="flex w-full min-h-screen">
        
        <!-- Left Side: Branding/Image (Hidden on mobile) -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary-900 via-primary to-primary-950 relative items-center justify-center overflow-hidden">
            <!-- Subtle Ambient Soft Glows (Figma Style) -->
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-accent/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-primary-light/25 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 p-12 text-center text-white max-w-lg">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo TSTH2" class="w-32 h-32 mx-auto mb-8 filter brightness-0 invert drop-shadow-xl">
                <h1 class="text-4xl font-extrabold mb-4 tracking-tight">TSTH2 <span class="text-accent">Hub</span></h1>
                <p class="text-lg text-white/80 font-medium leading-relaxed">Sistem Informasi Web Manajemen, Inventaris Pergudangan, dan Operasional Riset Taman Sains Teknologi Herbal dan Hortikultura.</p>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-4 sm:p-8 lg:p-12 bg-white relative">
            
            <!-- Mobile Logo (Visible only on small screens) -->
            <div class="w-full flex justify-center lg:hidden mb-8">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="w-10 h-10">
                    <span class="text-xl font-bold text-gray-900">TSTH2 <span class="text-primary">Hub</span></span>
                </div>
            </div>

            <div class="w-full max-w-md">
                <!-- Dual Portal Selector Switcher -->
                <div class="grid grid-cols-2 gap-2 p-1.5 bg-gray-100 rounded-2xl mb-8 border border-gray-200/80">
                    <button type="button" class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl text-xs sm:text-sm font-bold bg-white text-emerald-800 shadow-sm border border-emerald-200">
                        <span class="material-symbols-rounded" style="font-size: 18px; color: #059669;">admin_panel_settings</span>
                        <span>1. Web &amp; Gudang</span>
                    </button>
                    <a href="{{ config('app.url_logbook', env('URL_LOGBOOK', 'http://localhost:8001')) }}/login" class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl text-xs sm:text-sm font-semibold text-gray-600 hover:text-blue-600 hover:bg-white/70 transition">
                        <span class="material-symbols-rounded" style="font-size: 18px; color: #3b82f6;">badge</span>
                        <span>2. Karyawan &rarr;</span>
                    </a>
                </div>

                <div class="text-center lg:text-left mb-8">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold mb-3 border border-emerald-100">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Portal Web Manajemen &amp; Pergudangan
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Masuk ke Sistem</h2>
                    <p class="text-gray-500 mt-1.5 text-sm font-medium">Akses khusus pengelola portal web, inventory logistik gudang, agronom, dan pimpinan.</p>
                </div>
                
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
                        {{ session('status') }}
                    </div>
                @endif

                <form novalidate method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-medium bg-gray-50 focus:bg-white @error('email') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" 
                            placeholder="nama@email.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi</label>
                        <input id="password" type="password" name="password" required 
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-medium bg-gray-50 focus:bg-white @error('password') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" 
                            placeholder="••••••••">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <span class="text-sm font-medium text-gray-600">Ingat Saya</span>
                        </label>
                        
                        @if (Route::has('password.permintaan'))
                            <a href="{{ route('password.permintaan') }}" class="text-sm font-bold text-primary hover:text-primary-light transition-colors">
                                Lupa Sandi?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 bg-primary hover:bg-primary-light text-white rounded-xl font-bold text-base shadow-lg shadow-primary/30 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <span class="material-symbols-rounded" style="font-size: 20px;">login</span>
                        <span>Masuk ke Web Manajemen &amp; Katalog</span>
                    </button>
                </form>

                <div class="mt-6 p-3 rounded-xl bg-gray-50 border border-gray-200/70 text-[11px] text-gray-500 flex items-start gap-2.5">
                    <span class="material-symbols-rounded text-emerald-600 shrink-0" style="font-size: 16px; margin-top: 1px;">verified_user</span>
                    <span><strong>Portal Web Admin &amp; Katalog Tanaman.</strong> Untuk login Sistem Gudang, buka <a href="{{ config('app.url_gudang', env('URL_GUDANG', 'http://localhost:8002')) }}/login" class="underline text-primary">portal gudang</a>. Untuk Logbook, buka <a href="{{ config('app.url_logbook', env('URL_LOGBOOK', 'http://localhost:8001')) }}/login" class="underline text-primary">portal logbook</a>.</span>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ url('/') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-900 transition-colors inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Halaman Publik
                    </a>
                </div>
            </div>
            
        </div>
    </div>
</body>
</html>
