<!DOCTYPE html>
<html lang="id" class="bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'Dashboard - TSTH2 Hub')</title>
    
    <!-- PWA Setup -->
    @include('partials.pwa-head')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts & CSS -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800|inter:400,500,600,700" rel="stylesheet" />
    <!-- Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
        .sidebar-link { @apply flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium; }
        .sidebar-link:hover { @apply bg-white/10 text-white translate-x-1; }
        .sidebar-link.active { @apply bg-accent text-white shadow-md font-bold; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        /* Ensure sidebar can scroll on mobile */
        aside#sidebar { -webkit-overflow-scrolling: touch; }
        /* Material Symbols sizing */
        .material-symbols-rounded { font-size: 22px; font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; flex-shrink: 0; }
        .nav-icon { font-size: 20px; font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .mobile-nav-icon { font-size: 22px; font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="text-gray-800 antialiased min-h-screen flex bg-gray-50 overflow-x-hidden">
    <!-- Mobile Backdrop Overlay -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-black/60 z-40 lg:hidden hidden backdrop-blur-sm transition-opacity"></div>

    <!-- Sidebar Navigation -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-gradient-to-b from-primary-900 to-primary-950 text-white flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 shadow-2xl">
        <!-- Logo Area & Close Button for mobile/smartwatch -->
        <div class="h-20 sm:h-24 flex items-center justify-between px-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center overflow-hidden rounded-xl bg-white/10 p-1">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-full w-full object-contain">
                </div>
                <div>
                    <h1 class="text-lg sm:text-xl font-extrabold tracking-tight text-white leading-tight">TSTH2 <span class="text-accent">Hub</span></h1>
                    <p class="text-[10px] text-white/60 font-semibold uppercase tracking-wider">Admin Portal</p>
                </div>
            </div>
            <button id="closeSidebarBtn" class="lg:hidden p-2 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition-colors" aria-label="Tutup Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-4 overflow-y-auto custom-scrollbar">
            @if(auth()->user()->role === 'web_admin')
                <p class="px-4 text-[11px] font-bold text-white/40 uppercase tracking-wider mb-2">Konten Website</p>
                
                <a href="{{ route('web.management.home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('web.management.home') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-rounded">home</span>
                    <span>Beranda (Home)</span>
                </a>

                <a href="{{ route('web.management.penelitian') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('web.management.penelitian*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-rounded">science</span>
                    <span>Fasilitas & Riset</span>
                </a>

                <a href="{{ route('web.management.katalog') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('web.management.katalog*') || request()->routeIs('admin.plants*') || request()->routeIs('admin.categories*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-rounded">eco</span>
                    <span>Katalog Tanaman</span>
                </a>

                <a href="{{ route('web.management.berita') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('web.management.berita*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-rounded">newspaper</span>
                    <span>Berita</span>
                </a>

                <a href="{{ route('web.management.galeri') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('web.management.galeri*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-rounded">photo_library</span>
                    <span>Galeri Foto</span>
                </a>

                <p class="px-4 text-[11px] font-bold text-white/40 uppercase tracking-wider mb-2 mt-6">Pengaturan</p>

                <a href="{{ route('web.management.layout') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('web.management.layout') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-rounded">settings</span>
                    <span>Header & Footer</span>
                </a>

                <!-- Quick Preview Link -->
                <div class="pt-4 px-2">
                    <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-between px-4 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold border border-white/10 transition-all duration-200 group">
                        <div class="flex items-center gap-2.5">
                            <span class="material-symbols-rounded" style="font-size:18px;">open_in_new</span>
                            <span>Pratinjau Web</span>
                        </div>
                        <svg class="w-4 h-4 opacity-70 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                </div>
            @else
                <p class="px-4 text-xs font-bold text-white/40 uppercase tracking-widest mb-2 mt-4">Menu Utama</p>
                <a href="{{ route('dasbor') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('dasbor') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-rounded">dashboard</span>
                    <span>Dashboard</span>
                </a>
                
                @if(in_array(auth()->user()->role, ['pic_gudang', 'agronom', 'pimpinan']))
                <p class="px-4 text-xs font-bold text-white/40 uppercase tracking-widest mb-2 mt-4">Inventory</p>
                <a href="{{ route('gudang') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('gudang') || request()->routeIs('gudang.show') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-rounded">inventory_2</span>
                    <span>Data Barang</span>
                </a>
                @if(auth()->user()->role === 'pic_gudang')
                <a href="{{ route('satuan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('satuan*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-rounded">straighten</span>
                    <span>Master Satuan</span>
                </a>
                @endif
                @php
                    $pendingRequests = 0;
                    if(auth()->user()->role === 'pic_gudang') {
                        $pendingRequests = \App\Models\PermintaanBarang::where('status', 'Menunggu Approval')->count();
                    }
                @endphp
                <a href="{{ route('permintaan.index') }}" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('permintaan*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-rounded">shopping_cart</span>
                        <span>Permintaan Barang</span>
                    </div>
                    @if($pendingRequests > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">{{ $pendingRequests }}</span>
                    @endif
                </a>
                @endif

            @endif

            <p class="px-4 text-[11px] font-bold text-white/40 uppercase tracking-wider mb-2 mt-6">Lompat Aplikasi TSTH2</p>

            <a href="{{ config('app.url_gudang', env('URL_GUDANG', 'http://localhost:8002')) }}" target="_blank" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all font-medium text-sm">
                <div class="flex items-center gap-2.5">
                    <span class="material-symbols-rounded" style="font-size:20px;">inventory_2</span>
                    <span>Sistem Gudang</span>
                </div>
                <span class="text-[10px] bg-white/10 text-white/80 px-1.5 py-0.5 rounded font-mono">:8002</span>
            </a>

            <a href="{{ config('app.url_logbook', env('URL_LOGBOOK', 'http://localhost:8001')) }}" target="_blank" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all font-medium text-sm">
                <div class="flex items-center gap-2.5">
                    <span class="material-symbols-rounded" style="font-size:20px;">assignment</span>
                    <span>Logbook Karyawan</span>
                </div>
                <span class="text-[10px] bg-white/10 text-white/80 px-1.5 py-0.5 rounded font-mono">:8001</span>
            </a>
        </nav>

        <!-- User Profile Bottom -->
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 px-4 py-3 bg-white/5 rounded-xl border border-white/10">
                <a href="{{ route('profil.edit') }}" class="w-10 h-10 bg-gradient-to-br from-accent to-accent-dark rounded-full flex items-center justify-center text-white font-bold shadow-inner">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </a>
                <div class="flex-1 overflow-hidden">
                    <div class="font-bold text-sm truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-accent uppercase tracking-wider font-semibold truncate">{{ str_replace('_', ' ', auth()->user()->role) }}</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Konten Wrapper -->
    <div class="flex-1 flex flex-col lg:ml-72 min-h-screen transition-all duration-300 min-w-0 pb-16 lg:pb-0">
        
        <!-- Topbar -->
        <header class="h-20 bg-white shadow-sm flex items-center justify-between px-4 lg:px-8 z-40 sticky top-0">
            <div class="flex items-center gap-4">
                <button id="mobileMenuBtn" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="hidden md:block">
                    @hasSection('header_title')
                        @yield('header_title')
                    @else
                        @if(auth()->user()->role === 'web_admin')
                            <h2 class="text-xl font-bold text-gray-800">Manajemen Web Publik</h2>
                        @else
                            <h2 class="text-xl font-bold text-gray-800">Dashboard</h2>
                        @endif
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors text-xs sm:text-sm">
                    <span class="material-symbols-rounded" style="font-size:18px;">open_in_new</span>
                    <span class="hidden sm:inline">Lihat Web Publik</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg font-medium transition-colors text-xs sm:text-sm">
                        <span class="material-symbols-rounded" style="font-size:18px;">logout</span>
                        <span class="hidden sm:inline">{{ __('site.nav_logout') }}</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Page Konten -->
        <main class="flex-1 p-4 lg:p-8">
            <div class="max-w-7xl mx-auto">
                @include('partials.dialog-boxes')
                @yield('content')
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="py-6 text-center text-sm text-gray-500 font-medium hidden lg:block">
            &copy; {{ date('Y') }} TSTH2. All rights reserved.
        </footer>
    </div>

    <!-- Mobile Bottom Navigation (Visible only on lg and below) -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] border-t border-gray-100 flex items-center justify-around z-50 h-16 pb-safe">
        @if(auth()->user()->role === 'web_admin')
            <a href="{{ route('web.management.home') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('web.management.home') ? 'text-accent' : 'text-gray-400 hover:text-gray-600' }}">
                <span class="mobile-nav-icon material-symbols-rounded">home</span>
                <span class="text-[10px] font-bold">Beranda</span>
            </a>
            <a href="{{ route('web.management.katalog') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('web.management.katalog*') || request()->routeIs('admin.plants*') ? 'text-accent' : 'text-gray-400 hover:text-gray-600' }}">
                <span class="mobile-nav-icon material-symbols-rounded">eco</span>
                <span class="text-[10px] font-bold">Katalog</span>
            </a>
            <a href="{{ route('web.management.berita') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('web.management.berita*') ? 'text-accent' : 'text-gray-400 hover:text-gray-600' }}">
                <span class="mobile-nav-icon material-symbols-rounded">newspaper</span>
                <span class="text-[10px] font-bold">Berita</span>
            </a>
            <a href="{{ route('web.management.galeri') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('web.management.galeri*') ? 'text-accent' : 'text-gray-400 hover:text-gray-600' }}">
                <span class="mobile-nav-icon material-symbols-rounded">photo_library</span>
                <span class="text-[10px] font-bold">Galeri</span>
            </a>
            <a href="{{ route('profil.edit') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('profil*') ? 'text-accent' : 'text-gray-400 hover:text-gray-600' }}">
                <span class="mobile-nav-icon material-symbols-rounded">person</span>
                <span class="text-[10px] font-bold">Profil</span>
            </a>
        @else
            <a href="{{ route('dasbor') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('dasbor') ? 'text-accent' : 'text-gray-400 hover:text-gray-600' }}">
                <span class="mobile-nav-icon material-symbols-rounded">dashboard</span>
                <span class="text-[10px] font-bold">Dashboard</span>
            </a>
            
            @if(in_array(auth()->user()->role, ['pic_gudang', 'agronom', 'pimpinan']))
            <a href="{{ route('gudang') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('gudang') || request()->routeIs('gudang.show') ? 'text-accent' : 'text-gray-400 hover:text-gray-600' }}">
                <span class="mobile-nav-icon material-symbols-rounded">inventory_2</span>
                <span class="text-[10px] font-bold">Barang</span>
            </a>
            
            <a href="{{ route('permintaan.index') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 relative {{ request()->routeIs('permintaan*') ? 'text-accent' : 'text-gray-400 hover:text-gray-600' }}">
                <div class="relative">
                    <span class="mobile-nav-icon material-symbols-rounded">shopping_cart</span>
                    @php
                        $pendingRequests = 0;
                        if(auth()->user()->role === 'pic_gudang') {
                            $pendingRequests = \App\Models\PermintaanBarang::where('status', 'Menunggu Approval')->count();
                        }
                    @endphp
                    @if($pendingRequests > 0)
                        <span class="absolute -top-1 -right-2 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full shadow-sm animate-pulse">{{ $pendingRequests }}</span>
                    @endif
                </div>
                <span class="text-[10px] font-bold">Request</span>
            </a>
            @endif
            
            <a href="{{ route('profil.edit') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('profil*') ? 'text-accent' : 'text-gray-400 hover:text-gray-600' }}">
                <span class="mobile-nav-icon material-symbols-rounded">person</span>
                <span class="text-[10px] font-bold">Profil</span>
            </a>
        @endif
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('mobileMenuBtn');
            const closeBtn = document.getElementById('closeSidebarBtn');
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            function openSidebar() {
                if (!sidebar) return;
                sidebar.classList.remove('-translate-x-full');
                if (backdrop) backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                if (!sidebar) return;
                sidebar.classList.add('-translate-x-full');
                if (backdrop) backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            if (btn) btn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);
        });
    </script>
    @stack('scripts')
</body>
</html>
