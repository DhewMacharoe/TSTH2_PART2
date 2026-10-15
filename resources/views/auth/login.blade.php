<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Login - TSTH2 Hub</title>
    
    <!-- PWA Setup -->
    @include('partials.pwa-head')
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800|inter:400,500,600,700" rel="stylesheet" />
    
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
        <div class="hidden lg:flex lg:w-1/2 bg-primary relative items-center justify-center overflow-hidden">
            <!-- Decorative Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>
            
            <div class="relative z-10 p-12 text-center text-white max-w-lg">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo TSTH2" class="w-32 h-32 mx-auto mb-8 filter brightness-0 invert drop-shadow-lg">
                <h1 class="text-4xl font-extrabold mb-4 tracking-tight">TSTH2 <span class="text-accent">Hub</span></h1>
                <p class="text-lg text-white/80 font-medium">Sistem Informasi Pengelolaan Inventaris, Logbook, dan Manajemen Operasional Taman Sains Teknologi Herbal dan Hortikultura.</p>
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
                <div class="text-center lg:text-left mb-10">
                    <h2 class="text-3xl font-extrabold text-gray-900">Selamat Datang 👋</h2>
                    <p class="text-gray-500 mt-2 font-medium">Silakan masuk ke akun Anda untuk melanjutkan.</p>
                </div>
                
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
                        {{ session('status') }}
                    </div>
                @endif

                <form novalidate method="POST" action="{{ route('login') }}" class="space-y-6">
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

                    <button type="submit" class="w-full py-3.5 px-4 bg-primary hover:bg-primary-light text-white rounded-xl font-bold text-lg shadow-lg shadow-primary/30 transition-all transform hover:-translate-y-0.5">
                        Masuk
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Halaman Publik
                    </a>
                </div>
            </div>
            
        </div>
    </div>
</body>
</html>
