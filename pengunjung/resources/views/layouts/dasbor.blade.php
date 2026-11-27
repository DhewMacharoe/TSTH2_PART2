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
                    <p class="text-[10px] text-white/60 font-semibold uppercase tracking-wider">
                        @if(auth()->user()->isPicLab())
                            Warehouse Lab (RdI)
                        @elseif(auth()->user()->isPicGudang())
                            Warehouse Gudang (GA)
                        @elseif(auth()->user()->isWebAdmin())
                            Web Administrator
                        @elseif(auth()->user()->role === 'pimpinan')
                            Pimpinan / Direktur
                        @elseif(auth()->user()->role === 'kepala_departemen')
                            Kepala Departemen
                        @elseif(auth()->user()->role === 'kepala_divisi')
                            Kepala Divisi
                        @elseif(in_array(auth()->user()->role, ['employee', 'karyawan', 'agronom']))
                            Karyawan Operasional
                        @else
                            Admin Portal
                        @endif
                    </p>
                </div>
            </div>
            <button id="closeSidebarBtn" class="lg:hidden p-2 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition-colors" aria-label="Tutup Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        @php
            $urlLogbook = rtrim(config('app.url_logbook', env('URL_LOGBOOK', 'http://localhost:8001')), '/');
            $urlGudang = rtrim(config('app.url_gudang', env('URL_GUDANG', 'http://localhost:8002')), '/');
            $pendingRequests = 0;
            $user = auth()->user();
            if ($user->isPicGudang()) {
                $pendingRequests = \App\Models\PermintaanBarang::where('jenis_barang', 'lapangan')
                    ->whereIn('status', ['Menunggu Approval', 'Menunggu Pengambilan', 'Menunggu Verifikasi Pengembalian', 'Pending (Jadwal Ulang)'])->count();
            } elseif ($user->isPicLab()) {
                $pendingRequests = \App\Models\PermintaanBarang::where('jenis_barang', 'lab')
                    ->whereIn('status', ['Menunggu Approval', 'Menunggu Pengambilan', 'Menunggu Verifikasi Pengembalian', 'Pending (Jadwal Ulang)'])->count();
            } elseif ($user->isWebAdmin() || $user->role === 'pimpinan') {
                $pendingRequests = \App\Models\PermintaanBarang::where('status', 'Menunggu Approval')->count();
            } else {
                $pendingRequests = \App\Models\PermintaanBarang::where('user_id', $user->id)
                    ->whereIn('status', ['Menunggu Pengambilan', 'Pending (Jadwal Ulang)'])->count();
            }
        @endphp

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

                <a href="{{ route('web.management.katalog') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium text-white/70 hover:bg-white/10 hover:text-white">
                    <span class="material-symbols-rounded">eco</span>
                    <span>Katalog Tanaman</span>
                    <span class="ml-auto text-[9px] bg-white/10 text-white/60 px-1.5 py-0.5 rounded font-mono">↗</span>
                </a>

                <a href="{{ route('web.management.berita') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('web.management.berita*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-rounded">newspaper</span>
                    <span>Berita</span>
                </a>

                <a href="{{ route('web.management.galeri') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('web.management.galeri*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-rounded">photo_library</span>
                    <span>Galeri Foto</span>
                </a>

                <a href="{{ route('web.management.layout') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('web.management.layout') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-rounded">settings</span>
                    <span>Header & Footer</span>
                </a>

                <div class="pt-2 px-2">
                    <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-between px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold border border-white/10 transition-all duration-200 group">
                        <div class="flex items-center gap-2.5">
                            <span class="material-symbols-rounded" style="font-size:18px;">open_in_new</span>
                            <span>Pratinjau Web</span>
                        </div>
                        <span class="material-symbols-rounded text-xs opacity-70 group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                    </a>
                </div>

                <p class="px-4 text-[11px] font-bold text-white/40 uppercase tracking-wider mb-2 mt-6">Manajemen Sistem & Logbook</p>
                <a href="{{ route('sso.jump', ['portal' => 'logbook', 'target' => '/admin/users']) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-300 font-medium">
                    <span class="material-symbols-rounded">group</span>
                    <span>Manajemen User</span>
                </a>
                <a href="{{ route('sso.jump', ['portal' => 'logbook', 'target' => '/admin/departments']) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-300 font-medium">
                    <span class="material-symbols-rounded">corporate_fare</span>
                    <span>Departemen & Divisi</span>
                </a>
                <a href="{{ route('sso.jump', ['portal' => 'logbook', 'target' => '/admin/targets']) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-300 font-medium">
                    <span class="material-symbols-rounded">flag</span>
                    <span>Target & Sasaran</span>
                </a>

            @elseif(auth()->user()->role === 'pimpinan')
                {{-- MENU PIMPINAN (Logbook Prioritas Utama) --}}
                <p class="px-4 text-xs font-bold text-white/40 uppercase tracking-widest mb-2 mt-4">Menu Utama</p>
                <a href="{{ $urlLogbook }}/pimpinan/dashboard?section=dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-300 font-medium">
                    <span class="material-symbols-rounded">dashboard</span>
                    <span>Dashboard Pimpinan</span>
                </a>
                <a href="{{ $urlLogbook }}/pimpinan/dashboard?section=monitoring" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-300 font-medium">
                    <span class="material-symbols-rounded">monitoring</span>
                    <span>Monitoring Kinerja</span>
                </a>
                <a href="{{ $urlLogbook }}/pimpinan/employees" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-300 font-medium">
                    <span class="material-symbols-rounded">group</span>
                    <span>Data Karyawan</span>
                </a>

                {{-- WAREHOUSE (Low Priority untuk Pimpinan) --}}
                <p class="px-4 text-[11px] font-bold text-white/40 uppercase tracking-wider mb-2 mt-6">Gudang & Inventaris</p>
                <a href="{{ route('permintaan.index') }}" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('permintaan*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-rounded">shopping_cart</span>
                        <span>Permintaan Barang</span>
                    </div>
                    @if($pendingRequests > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">{{ $pendingRequests }}</span>
                    @endif
                </a>
                <a href="{{ route('gudang') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('gudang*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-rounded">inventory_2</span>
                    <span>Data Barang</span>
                </a>

            @elseif(auth()->user()->role === 'kepala_departemen')
                {{-- MENU KEPALA DEPARTEMEN --}}
                <p class="px-4 text-xs font-bold text-white/40 uppercase tracking-widest mb-2 mt-4">Menu Utama</p>
                <a href="{{ $urlLogbook }}/kepala-departemen/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-300 font-medium">
                    <span class="material-symbols-rounded">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ $urlLogbook }}/kepala-departemen/projects" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-300 font-medium">
                    <span class="material-symbols-rounded">folder_special</span>
                    <span>Manajemen Project</span>
                </a>
                <a href="{{ $urlLogbook }}/admin/targets" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-300 font-medium">
                    <span class="material-symbols-rounded">flag</span>
                    <span>Target & Sasaran</span>
                </a>

                {{-- WAREHOUSE (Low Priority) --}}
                <p class="px-4 text-[11px] font-bold text-white/40 uppercase tracking-wider mb-2 mt-6">Gudang & Inventaris</p>
                <a href="{{ route('permintaan.index') }}" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('permintaan*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-rounded">shopping_cart</span>
                        <span>Permintaan Barang</span>
                    </div>
                    @if($pendingRequests > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">{{ $pendingRequests }}</span>
                    @endif
                </a>
                <a href="{{ route('gudang') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('gudang*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-rounded">inventory_2</span>
                    <span>Data Barang</span>
                </a>

            @elseif(auth()->user()->role === 'kepala_divisi')
                {{-- MENU KEPALA DIVISI --}}
                <p class="px-4 text-xs font-bold text-white/40 uppercase tracking-widest mb-2 mt-4">Menu Utama</p>
                <a href="{{ $urlLogbook }}/kepala-divisi/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-300 font-medium">
                    <span class="material-symbols-rounded">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ $urlLogbook }}/kepala-divisi/tasks" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-300 font-medium">
                    <span class="material-symbols-rounded">task_alt</span>
                    <span>Pekerjaan & Tugas</span>
                </a>
                <a href="{{ $urlLogbook }}/kepala-divisi/logbooks" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-300 font-medium">
                    <span class="material-symbols-rounded">rate_review</span>
                    <span>Review Logbook</span>
                </a>

                {{-- WAREHOUSE (Low Priority) --}}
                <p class="px-4 text-[11px] font-bold text-white/40 uppercase tracking-wider mb-2 mt-6">Gudang & Inventaris</p>
                <a href="{{ route('permintaan.index') }}" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('permintaan*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-rounded">shopping_cart</span>
                        <span>Permintaan Barang</span>
                    </div>
                    @if($pendingRequests > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">{{ $pendingRequests }}</span>
                    @endif
                </a>
                <a href="{{ route('gudang') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('gudang*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-rounded">inventory_2</span>
                    <span>Data Barang</span>
                </a>

            @elseif(in_array(auth()->user()->role, ['employee', 'karyawan', 'agronom']))
                {{-- MENU KARYAWAN / EMPLOYEE / AGRONOM --}}
                <p class="px-4 text-xs font-bold text-white/40 uppercase tracking-widest mb-2 mt-4">Menu Utama</p>
                <a href="{{ $urlLogbook }}/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-300 font-medium">
                    <span class="material-symbols-rounded">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ $urlLogbook }}/employee/logbooks" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-300 font-medium">
                    <span class="material-symbols-rounded">assignment</span>
                    <span>Logbook Saya</span>
                </a>


                {{-- WAREHOUSE (Low Priority) --}}
                <p class="px-4 text-[11px] font-bold text-white/40 uppercase tracking-wider mb-2 mt-6">Gudang & Inventaris</p>
                <a href="{{ route('permintaan.index') }}" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('permintaan*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-rounded">shopping_cart</span>
                        <span>Permintaan Barang</span>
                    </div>
                    @if($pendingRequests > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">{{ $pendingRequests }}</span>
                    @endif
                </a>

            @elseif(auth()->user()->isPicLab() || auth()->user()->isPicGudang())
                {{-- MENU PIC GUDANG / PIC LAB (Warehouse Prioritas Utama) --}}
                <p class="px-4 text-xs font-bold text-white/40 uppercase tracking-widest mb-2 mt-4">
                    {{ auth()->user()->isPicLab() ? 'Warehouse Lab (RdI)' : 'Warehouse Gudang (GA)' }}
                </p>
                <a href="{{ route('dasbor') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('dasbor') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-rounded">dashboard</span>
                    <span>Dashboard Gudang</span>
                </a>
                <a href="{{ route('gudang') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('gudang') || request()->routeIs('gudang.show') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-rounded">inventory_2</span>
                    <span>Data Barang</span>
                </a>
                <a href="{{ route('satuan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('satuan*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-rounded">straighten</span>
                    <span>Master Satuan</span>
                </a>
                <a href="{{ route('permintaan.index') }}" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('permintaan*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-rounded">shopping_cart</span>
                        <span>Permintaan Barang</span>
                    </div>
                    @if($pendingRequests > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">{{ $pendingRequests }}</span>
                    @endif
                </a>

                {{-- PIC Manajerial: tidak mengisi logbook --}}
            @endif
        </nav>

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
                <div class="hidden md:flex items-center gap-3">
                    @hasSection('header_title')
                        @yield('header_title')
                    @else
                        @if(auth()->user()->role === 'web_admin')
                            <h2 class="text-xl font-bold text-gray-800">Manajemen Web Publik</h2>
                        @else
                            <h2 class="text-xl font-bold text-gray-800">Dashboard</h2>
                        @endif
                    @endif

                    {{-- Portal Badge Indikator Pembatas Jelas --}}
                    @if(auth()->user()->role === 'web_admin')
                    @elseif(auth()->user()->isPicGudang())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200 shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            GUDANG (GA)
                        </span>
                    @elseif(auth()->user()->isPicLab())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-cyan-50 text-cyan-800 border border-cyan-200 shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-cyan-500"></span>
                            LAB (RdI)
                        </span>
                    @elseif(auth()->user()->role === 'pimpinan')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-purple-50 text-purple-800 border border-purple-200 shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                            PORTAL PIMPINAN
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                {{-- Notification Dropdown Popover (Tidak Redirect Langsung) --}}
                @include('partials.notification-dropdown')

                {{-- Profile Dropdown --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center gap-2 px-2 sm:px-3 py-1.5 rounded-xl hover:bg-gray-100 transition-colors group">
                        <div class="w-8 h-8 bg-gradient-to-br from-primary-800 to-primary-950 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm flex-shrink-0">
                            {{ auth()->user()->display_initial }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <div class="text-sm font-bold text-gray-800 leading-none">{{ auth()->user()->display_name }}</div>
                            <div class="text-[10px] text-gray-500 uppercase tracking-wide font-semibold leading-none mt-0.5">{{ auth()->user()->role_label }}</div>
                        </div>
                        <span class="material-symbols-rounded text-gray-400 hidden sm:inline" style="font-size:16px;">expand_more</span>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50" style="display:none;">
                        <div class="px-4 py-2.5 border-b border-gray-100">
                            <div class="font-bold text-sm text-gray-900 truncate">{{ auth()->user()->display_name }}</div>
                            <div class="text-[11px] text-gray-500 uppercase tracking-wide mt-0.5">{{ auth()->user()->role_label }}</div>
                        </div>
                        <a href="{{ route('profil.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <span class="material-symbols-rounded" style="font-size:17px;">manage_accounts</span>
                            <span>Edit Profil</span>
                        </a>
                        <div class="border-t border-gray-100 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <span class="material-symbols-rounded" style="font-size:17px;">logout</span>
                                    <span>{{ __('site.nav_logout') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
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
            <a href="{{ config('app.url_katalog', env('URL_KATALOG', 'http://localhost:8003')) }}/admin/plants" class="flex flex-col items-center justify-center w-full h-full space-y-1 text-gray-400 hover:text-gray-600">
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
            
            @if(in_array(auth()->user()->role, ['pic_gudang', 'pic_lab', 'agronom', 'pimpinan', 'kepala_divisi', 'kepala_departemen', 'web_admin', 'employee', 'karyawan', 'leader']))
            <a href="{{ route('gudang') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('gudang') || request()->routeIs('gudang.show') ? 'text-accent' : 'text-gray-400 hover:text-gray-600' }}">
                <span class="mobile-nav-icon material-symbols-rounded">inventory_2</span>
                <span class="text-[10px] font-bold">Barang</span>
            </a>
            
            <a href="{{ route('permintaan.index') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 relative {{ request()->routeIs('permintaan*') ? 'text-accent' : 'text-gray-400 hover:text-gray-600' }}">
                <div class="relative">
                    <span class="mobile-nav-icon material-symbols-rounded">shopping_cart</span>
                    @php
                        $mobilePending = 0;
                        $mUser = auth()->user();
                        if ($mUser->isPicGudang()) {
                            $mobilePending = \App\Models\PermintaanBarang::where('jenis_barang', 'lapangan')->whereIn('status', ['Menunggu Approval', 'Menunggu Pengambilan', 'Menunggu Verifikasi Pengembalian', 'Pending (Jadwal Ulang)'])->count();
                        } elseif ($mUser->isPicLab()) {
                            $mobilePending = \App\Models\PermintaanBarang::where('jenis_barang', 'lab')->whereIn('status', ['Menunggu Approval', 'Menunggu Pengambilan', 'Menunggu Verifikasi Pengembalian', 'Pending (Jadwal Ulang)'])->count();
                        } elseif ($mUser->isWebAdmin() || $mUser->role === 'pimpinan') {
                            $mobilePending = \App\Models\PermintaanBarang::where('status', 'Menunggu Approval')->count();
                        } else {
                            $mobilePending = \App\Models\PermintaanBarang::where('user_id', $mUser->id)->whereIn('status', ['Menunggu Pengambilan', 'Pending (Jadwal Ulang)'])->count();
                        }
                    @endphp
                    @if($mobilePending > 0)
                        <span class="absolute -top-1 -right-2 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full shadow-sm animate-pulse">{{ $mobilePending }}</span>
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
