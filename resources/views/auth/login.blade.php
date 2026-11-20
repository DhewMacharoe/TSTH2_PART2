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

    <!-- Tailwind & App CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex min-h-screen">

    <!-- Split Layout Container -->
    <div class="flex w-full min-h-screen">

        <!-- Left Side: Branding (Hidden on mobile) -->
        <div class="hidden lg:flex lg:w-1/2 relative items-center justify-center overflow-hidden" style="background: linear-gradient(135deg, #054439 0%, #02231d 100%);">
            <!-- Ambient glows -->
            <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full blur-3xl pointer-events-none" style="background: rgba(212,175,55,0.12);"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full blur-3xl pointer-events-none" style="background: rgba(255,255,255,0.08);"></div>

            <div class="relative z-10 p-12 text-center text-white max-w-lg">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo TSTH2" class="w-28 h-28 mx-auto mb-8 filter brightness-0 invert drop-shadow-xl">
                <h1 class="text-4xl font-extrabold mb-4 tracking-tight">TSTH2 <span style="color: #D4AF37;">Hub</span></h1>
                <p class="text-lg text-white/80 font-medium leading-relaxed mb-8">
                    Sistem Informasi Terpadu — Web Manajemen, Inventaris Pergudangan, Logbook Karyawan, dan Katalog Tanaman TSTH2.
                </p>
                <!-- Portal badges -->
                <div class="grid grid-cols-2 gap-3 text-left">
                    <div class="bg-white/10 backdrop-blur border border-white/20 rounded-xl p-3">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="material-symbols-rounded text-sm" style="color: #D4AF37; font-size:16px;">public</span>
                            <span class="text-xs font-bold text-white/90">Web Pengunjung</span>
                        </div>
                        <p class="text-xs text-white/60">Halaman publik & katalog tanaman</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur border border-white/20 rounded-xl p-3">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="material-symbols-rounded text-sm" style="color: #D4AF37; font-size:16px;">inventory_2</span>
                            <span class="text-xs font-bold text-white/90">Sistem Gudang</span>
                        </div>
                        <p class="text-xs text-white/60">Inventaris & permintaan barang</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur border border-white/20 rounded-xl p-3">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="material-symbols-rounded text-sm" style="color: #D4AF37; font-size:16px;">badge</span>
                            <span class="text-xs font-bold text-white/90">Logbook Karyawan</span>
                        </div>
                        <p class="text-xs text-white/60">Target, monitoring & aktivitas</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur border border-white/20 rounded-xl p-3">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="material-symbols-rounded text-sm" style="color: #D4AF37; font-size:16px;">admin_panel_settings</span>
                            <span class="text-xs font-bold text-white/90">Web Management</span>
                        </div>
                        <p class="text-xs text-white/60">Kelola konten & pengguna</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Unified Login Form -->
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-4 sm:p-8 lg:p-12 bg-white relative">

            <!-- Mobile Logo -->
            <div class="w-full flex justify-center lg:hidden mb-8">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="w-10 h-10">
                    <span class="text-xl font-bold text-gray-900">TSTH2 <span style="color: #054439;">Hub</span></span>
                </div>
            </div>

            <div class="w-full max-w-md">

                <!-- Header -->
                <div class="text-center lg:text-left mb-8">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Masuk ke Sistem</h2>
                    <p class="text-gray-500 mt-1.5 text-sm font-medium">
                        Satu akun untuk semua portal — sistem akan otomatis mengarahkan Anda sesuai peran.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
                        {{ session('status') }}
                    </div>
                @endif

                <form novalidate method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none transition-all font-medium bg-gray-50 focus:bg-white @error('email') border-red-500 @enderror"
                            style="focus:border-color: #054439;"
                            placeholder="nama@tsth2.id">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required
                                class="w-full px-4 py-3 pr-12 rounded-xl border border-gray-300 focus:outline-none transition-all font-medium bg-gray-50 focus:bg-white @error('password') border-red-500 @enderror"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700" tabindex="-1">
                                <span class="material-symbols-rounded" id="pwd-eye" style="font-size:20px;">visibility</span>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember + Forgot -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded" style="accent-color: #054439;">
                            <span class="text-sm font-medium text-gray-600">Ingat Saya</span>
                        </label>
                        @if (Route::has('password.permintaan'))
                            <a href="{{ route('password.permintaan') }}" class="text-sm font-bold transition-colors" style="color: #054439;">
                                Lupa Sandi?
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full py-3.5 px-4 text-white rounded-xl font-bold text-base shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2" style="background: linear-gradient(135deg, #054439, #02231d); box-shadow: 0 4px 15px rgba(5,68,57,0.3);">
                        <span class="material-symbols-rounded" style="font-size: 20px;">login</span>
                        <span>Masuk ke TSTH2 Hub</span>
                    </button>
                </form>

                <!-- Info box -->
                <div class="mt-6 p-4 rounded-xl border text-xs text-gray-600 space-y-2" style="background:#f8fffe; border-color:#d1fae5;">
                    <div class="flex items-start gap-2">
                        <span class="material-symbols-rounded shrink-0" style="font-size:16px; color:#054439; margin-top:1px;">info</span>
                        <span>
                            <strong class="text-gray-700">Satu Login untuk Semua Portal.</strong>
                            Setelah masuk, sistem akan otomatis mengarahkan ke portal yang sesuai dengan peran Anda (Pimpinan, Kepala Departemen, Karyawan, PIC Gudang, Admin Web, dll).
                        </span>
                    </div>
                </div>

                <!-- Back to public -->
                <div class="mt-6 text-center">
                    <a href="{{ url('/') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-900 transition-colors inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Halaman Publik
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            const eye = document.getElementById('pwd-eye');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                eye.textContent = 'visibility_off';
            } else {
                pwd.type = 'password';
                eye.textContent = 'visibility';
            }
        }
    </script>
</body>
</html>
