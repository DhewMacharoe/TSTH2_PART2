<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Logbook Karyawan - TSTH2 Hub') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800|inter:400,500,600,700" rel="stylesheet" />
    <!-- Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
        
        /* Sidebar layout styling */
        aside#sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 50;
            width: 18rem !important; /* 288px */
            background: linear-gradient(180deg, #054439 0%, #02231d 100%) !important;
            color: #ffffff !important;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transition: transform 0.3s ease-in-out;
            -webkit-overflow-scrolling: touch;
        }

        .main-wrapper {
            transition: margin-left 0.3s ease-in-out;
            min-width: 0;
            flex: 1 1 0%;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        @media (min-width: 1024px) {
            aside#sidebar {
                transform: translateX(0) !important;
            }
            .main-wrapper {
                margin-left: 18rem !important;
            }
        }

        @media (max-width: 1023px) {
            aside#sidebar {
                transform: translateX(-100%);
            }
            aside#sidebar.mobile-open {
                transform: translateX(0) !important;
            }
            .main-wrapper {
                margin-left: 0 !important;
            }
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            transition: all 0.2s ease-in-out;
            font-weight: 500;
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
        }
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            transform: translateX(2px);
        }
        .sidebar-link.active {
            background-color: #c09e5c !important;
            color: #ffffff !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            font-weight: 700;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.4); }
        .material-symbols-rounded { font-size: 22px; font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; flex-shrink: 0; }
    </style>
</head>
<body class="text-gray-800 antialiased min-h-screen flex bg-gray-50 overflow-x-hidden">
    <!-- Mobile Backdrop Overlay -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-black/60 z-40 lg:hidden hidden backdrop-blur-sm transition-opacity"></div>

    <!-- Sidebar Navigation -->
    <aside id="sidebar" class="text-white flex flex-col shadow-2xl">
        <!-- Logo Area & Close Button -->
        <div class="h-20 sm:h-24 flex items-center justify-between px-6 border-b border-white/10 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center overflow-hidden rounded-xl bg-white/10 p-1">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-full w-full object-contain">
                </div>
                <div>
                    <h1 class="text-lg sm:text-xl font-extrabold tracking-tight text-white leading-tight">TSTH2 <span class="text-[#c09e5c]">Hub</span></h1>
                    <p class="text-[10px] text-white/60 font-semibold uppercase tracking-wider">
                        @if(Auth::user()->role === 'pimpinan')
                            Pimpinan / Direktur
                        @elseif(Auth::user()->role === 'kepala_departemen')
                            Kepala Departemen
                        @elseif(Auth::user()->role === 'kepala_divisi')
                            Kepala Divisi
                        @elseif(in_array(Auth::user()->role, ['employee', 'karyawan', 'agronom']))
                            Karyawan Operasional
                        @elseif(in_array(Auth::user()->role, ['admin', 'web_admin']))
                            Administrator
                        @elseif(in_array(Auth::user()->role, ['pic_gudang', 'pic_lab']))
                            Warehouse Team
                        @else
                            Portal TSTH2
                        @endif
                    </p>
                </div>
            </div>
            <button id="closeSidebarBtn" class="lg:hidden p-2 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition-colors" aria-label="Tutup Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        @php
            $urlGudang = rtrim(config('app.url_gudang', env('URL_GUDANG', 'http://localhost:8002')), '/');
            $urlPengunjung = rtrim(config('app.url_pengunjung', env('URL_PENGUNJUNG', 'http://localhost:8000')), '/');
            $pendingPermintaan = 0;
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('permintaan_barangs')) {
                    if (in_array(Auth::user()->role, ['pimpinan', 'admin', 'web_admin'])) {
                        $pendingPermintaan = \Illuminate\Support\Facades\DB::table('permintaan_barangs')->where('status', 'Menunggu Approval')->count();
                    } else {
                        $pendingPermintaan = \Illuminate\Support\Facades\DB::table('permintaan_barangs')
                            ->where('user_id', Auth::id())
                            ->whereIn('status', ['Menunggu Pengambilan', 'Pending (Jadwal Ulang)'])
                            ->count();
                    }
                }
            } catch (\Exception $e) {}
        @endphp

        <!-- Navigation Links (Logbook Prioritas Utama untuk Pimpinan & Karyawan) -->
        <nav class="flex-1 px-4 py-6 space-y-3 overflow-y-auto custom-scrollbar">
            @if(Auth::user()->role === 'pimpinan')
                {{-- PIMPINAN --}}
                <p class="px-4 text-xs font-bold text-white/40 uppercase tracking-widest mb-1 mt-2">Menu Utama</p>
                <a href="{{ route('pimpinan.dashboard', ['section' => 'dashboard']) }}" class="sidebar-link {{ request()->routeIs('pimpinan.dashboard') && request('section', 'dashboard') === 'dashboard' ? 'active' : '' }}">
                    <span class="material-symbols-rounded">dashboard</span>
                    <span>Dashboard Pimpinan</span>
                </a>
                <a href="{{ route('pimpinan.dashboard', ['section' => 'monitoring']) }}" class="sidebar-link {{ request()->routeIs('pimpinan.dashboard') && request('section') === 'monitoring' ? 'active' : '' }}">
                    <span class="material-symbols-rounded">monitoring</span>
                    <span>Monitoring Kinerja</span>
                </a>
                <a href="{{ route('pimpinan.employees.index') }}" class="sidebar-link {{ request()->routeIs('pimpinan.employees*') ? 'active' : '' }}">
                    <span class="material-symbols-rounded">group</span>
                    <span>Data Karyawan</span>
                </a>

                <p class="px-4 text-[11px] font-bold text-white/40 uppercase tracking-wider mb-1 mt-6">Gudang & Inventaris</p>
                <a href="{{ $urlGudang }}/permintaan" class="sidebar-link justify-between">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-rounded">shopping_cart</span>
                        <span>Permintaan Barang</span>
                    </div>
                    @if($pendingPermintaan > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">{{ $pendingPermintaan }}</span>
                    @endif
                </a>
                <a href="{{ $urlGudang }}/gudang" class="sidebar-link">
                    <span class="material-symbols-rounded">inventory_2</span>
                    <span>Data Barang</span>
                </a>

            @elseif(Auth::user()->role === 'kepala_departemen')
                {{-- KEPALA DEPARTEMEN --}}
                <p class="px-4 text-xs font-bold text-white/40 uppercase tracking-widest mb-1 mt-2">Menu Utama</p>
                <a href="{{ route('kepala_departemen.dashboard') }}" class="sidebar-link {{ request()->routeIs('kepala_departemen.dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-rounded">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('kepala_departemen.projects.index') }}" class="sidebar-link {{ request()->routeIs('kepala_departemen.projects*') ? 'active' : '' }}">
                    <span class="material-symbols-rounded">folder_special</span>
                    <span>Manajemen Project</span>
                </a>
                <a href="{{ route('admin.targets.index') }}" class="sidebar-link {{ request()->routeIs('admin.targets*') ? 'active' : '' }}">
                    <span class="material-symbols-rounded">flag</span>
                    <span>Target & Sasaran</span>
                </a>
                <a href="{{ route('kepala_departemen.logbooks.index') }}" class="sidebar-link {{ request()->routeIs('kepala_departemen.logbooks*') ? 'active' : '' }}">
                    <span class="material-symbols-rounded">rate_review</span>
                    <span>Review Logbook</span>
                </a>

                <p class="px-4 text-[11px] font-bold text-white/40 uppercase tracking-wider mb-1 mt-6">Gudang & Inventaris</p>
                <a href="{{ $urlGudang }}/permintaan" class="sidebar-link justify-between">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-rounded">shopping_cart</span>
                        <span>Permintaan Barang</span>
                    </div>
                    @if($pendingPermintaan > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">{{ $pendingPermintaan }}</span>
                    @endif
                </a>
                <a href="{{ $urlGudang }}/gudang" class="sidebar-link">
                    <span class="material-symbols-rounded">inventory_2</span>
                    <span>Data Barang</span>
                </a>

            @elseif(Auth::user()->role === 'kepala_divisi')
                {{-- KEPALA DIVISI --}}
                <p class="px-4 text-xs font-bold text-white/40 uppercase tracking-widest mb-1 mt-2">Menu Utama</p>
                <a href="{{ route('kepala_divisi.dashboard') }}" class="sidebar-link {{ request()->routeIs('kepala_divisi.dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-rounded">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('kepala_divisi.my_logbooks.create') }}" class="sidebar-link {{ request()->routeIs('kepala_divisi.my_logbooks.create') ? 'active' : '' }}">
                    <span class="material-symbols-rounded">edit_note</span>
                    <span>Isi Logbook</span>
                </a>
                <a href="{{ route('kepala_divisi.my_logbooks.index') }}" class="sidebar-link {{ request()->routeIs('kepala_divisi.my_logbooks.index') || request()->routeIs('kepala_divisi.my_logbooks.show') || request()->routeIs('kepala_divisi.my_logbooks.edit') ? 'active' : '' }}">
                    <span class="material-symbols-rounded">assignment</span>
                    <span>Logbook Saya</span>
                </a>
                <a href="{{ route('kepala_divisi.tasks.index') }}" class="sidebar-link {{ request()->routeIs('kepala_divisi.tasks*') ? 'active' : '' }}">
                    <span class="material-symbols-rounded">task_alt</span>
                    <span>Pekerjaan & Tugas</span>
                </a>
                <a href="{{ route('kepala_divisi.logbooks.index') }}" class="sidebar-link {{ request()->routeIs('kepala_divisi.logbooks*') ? 'active' : '' }}">
                    <span class="material-symbols-rounded">rate_review</span>
                    <span>Review Logbook</span>
                </a>

                <p class="px-4 text-[11px] font-bold text-white/40 uppercase tracking-wider mb-1 mt-6">Gudang & Inventaris</p>
                <a href="{{ $urlGudang }}/permintaan" class="sidebar-link justify-between">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-rounded">shopping_cart</span>
                        <span>Permintaan Barang</span>
                    </div>
                    @if($pendingPermintaan > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">{{ $pendingPermintaan }}</span>
                    @endif
                </a>
                <a href="{{ $urlGudang }}/gudang" class="sidebar-link">
                    <span class="material-symbols-rounded">inventory_2</span>
                    <span>Data Barang</span>
                </a>

            @elseif(in_array(Auth::user()->role, ['employee', 'karyawan', 'agronom']))
                {{-- KARYAWAN OPERASIONAL --}}
                <p class="px-4 text-xs font-bold text-white/40 uppercase tracking-widest mb-1 mt-2">Menu Utama</p>
                <a href="{{ route('employee.dashboard') }}" class="sidebar-link {{ request()->routeIs('employee.dashboard') || request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-rounded">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('employee.logbooks.create') }}" class="sidebar-link {{ request()->routeIs('employee.logbooks.create') ? 'active' : '' }}">
                    <span class="material-symbols-rounded">edit_note</span>
                    <span>Isi Logbook</span>
                </a>
                <a href="{{ route('employee.logbooks.index') }}" class="sidebar-link {{ request()->routeIs('employee.logbooks.index') || request()->routeIs('employee.logbooks.show') || request()->routeIs('employee.logbooks.edit') ? 'active' : '' }}">
                    <span class="material-symbols-rounded">assignment</span>
                    <span>Logbook Saya</span>
                </a>

                <p class="px-4 text-[11px] font-bold text-white/40 uppercase tracking-wider mb-1 mt-6">Gudang & Inventaris</p>
                <a href="{{ $urlGudang }}/permintaan" class="sidebar-link justify-between">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-rounded">shopping_cart</span>
                        <span>Permintaan Barang</span>
                    </div>
                    @if($pendingPermintaan > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">{{ $pendingPermintaan }}</span>
                    @endif
                </a>

            @elseif(in_array(Auth::user()->role, ['admin', 'web_admin']))
                {{-- ADMINISTRATOR --}}
                <p class="px-4 text-[11px] font-bold text-white/40 uppercase tracking-wider mb-1">Manajemen Logbook</p>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-rounded">dashboard</span>
                    <span>Dashboard Admin</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <span class="material-symbols-rounded">group</span>
                    <span>Manajemen User</span>
                </a>
                <a href="{{ route('admin.departments.index') }}" class="sidebar-link {{ request()->routeIs('admin.departments*') ? 'active' : '' }}">
                    <span class="material-symbols-rounded">corporate_fare</span>
                    <span>Departemen & Divisi</span>
                </a>
                <a href="{{ route('admin.targets.index') }}" class="sidebar-link {{ request()->routeIs('admin.targets*') ? 'active' : '' }}">
                    <span class="material-symbols-rounded">flag</span>
                    <span>Target & Sasaran</span>
                </a>
                <a href="{{ route('kepala_departemen.logbooks.index') }}" class="sidebar-link {{ request()->routeIs('kepala_departemen.logbooks*') ? 'active' : '' }}">
                    <span class="material-symbols-rounded">rate_review</span>
                    <span>Review Logbook</span>
                </a>

                <p class="px-4 text-[11px] font-bold text-white/40 uppercase tracking-wider mb-1 mt-6">Sistem Lain</p>
                <a href="{{ route('sso.jump', ['portal' => 'pengunjung', 'target' => '/web-management/home']) }}" target="_blank" class="sidebar-link justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-rounded">web</span>
                        <span>Manajemen Web</span>
                    </div>
                    <span class="text-[9px] bg-white/10 text-white/60 px-1.5 py-0.5 rounded font-mono">↗</span>
                </a>
                <a href="{{ route('sso.jump', ['portal' => 'katalog', 'target' => '/admin/plants']) }}" target="_blank" class="sidebar-link justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-rounded">eco</span>
                        <span>Katalog Tanaman</span>
                    </div>
                    <span class="text-[9px] bg-white/10 text-white/60 px-1.5 py-0.5 rounded font-mono">↗</span>
                </a>
                <a href="{{ route('sso.jump', ['portal' => 'warehouse', 'target' => '/dasbor']) }}" target="_blank" class="sidebar-link justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-rounded">warehouse</span>
                        <span>Sistem Gudang</span>
                    </div>
                    <span class="text-[9px] bg-white/10 text-white/60 px-1.5 py-0.5 rounded font-mono">↗</span>
                </a>

            @elseif(in_array(Auth::user()->role, ['pic_gudang', 'pic_lab']))
                {{-- PIC GUDANG / LAB --}}
                <p class="px-4 text-xs font-bold text-white/40 uppercase tracking-widest mb-1 mt-2">Warehouse & Inventaris</p>
                <a href="{{ $urlGudang }}/dasbor" class="sidebar-link">
                    <span class="material-symbols-rounded">dashboard</span>
                    <span>Dashboard Gudang</span>
                </a>
                <a href="{{ $urlGudang }}/gudang" class="sidebar-link">
                    <span class="material-symbols-rounded">inventory_2</span>
                    <span>Data Barang</span>
                </a>
                <a href="{{ $urlGudang }}/permintaan" class="sidebar-link justify-between">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-rounded">shopping_cart</span>
                        <span>Permintaan Barang</span>
                    </div>
                    @if($pendingPermintaan > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">{{ $pendingPermintaan }}</span>
                    @endif
                </a>
            @endif
        </nav>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <!-- Topbar (Identik dengan TSTH2 Hub) -->
        <header class="h-20 bg-white shadow-sm flex items-center justify-between px-4 lg:px-8 z-40 sticky top-0 border-b border-gray-100 shrink-0">
            <div class="flex items-center gap-4">
                <button id="mobileMenuBtn" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="hidden md:block">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">TSTH2 Hub</span>
                    <h2 class="text-lg font-bold text-gray-800 leading-tight">
                        @if(Auth::user()->role === 'pimpinan')
                            Monitoring Kinerja & Logbook
                        @elseif(Auth::user()->role === 'kepala_departemen')
                            Departemen & Project
                        @elseif(Auth::user()->role === 'kepala_divisi')
                            Divisi & Pekerjaan
                        @elseif(in_array(Auth::user()->role, ['admin', 'web_admin']))
                            Manajemen Sistem
                        @else
                            Logbook Karyawan
                        @endif
                    </h2>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                {{-- Tombol Aksi Cepat: Isi Logbook Langsung --}}
                @if(Auth::user()->role === 'kepala_divisi')
                    <a
                        href="{{ route('kepala_divisi.my_logbooks.create') }}"
                        class="inline-flex items-center gap-1.5 bg-[#054439] hover:bg-[#032e26] text-white px-3.5 py-2 rounded-xl font-semibold text-xs shadow-sm transition"
                        title="Isi Logbook Harian"
                    >
                        <span class="material-symbols-rounded" style="font-size:18px;">edit_note</span>
                        <span>Isi Logbook</span>
                    </a>
                @elseif(in_array(Auth::user()->role, ['employee', 'karyawan', 'agronom', 'leader']))
                    <a
                        href="{{ route('employee.logbooks.create') }}"
                        class="inline-flex items-center gap-1.5 bg-[#054439] hover:bg-[#032e26] text-white px-3.5 py-2 rounded-xl font-semibold text-xs shadow-sm transition"
                        title="Isi Logbook Harian"
                    >
                        <span class="material-symbols-rounded" style="font-size:18px;">edit_note</span>
                        <span>Isi Logbook</span>
                    </a>
                @endif

                {{-- Notification Dropdown Popover (Tidak Redirect Langsung) --}}
                @include('partials.notification-dropdown')

                {{-- Profile Dropdown --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center gap-2 px-2 sm:px-3 py-1.5 rounded-xl hover:bg-gray-100 transition-colors group">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm shrink-0" style="background: linear-gradient(135deg, #054439 0%, #02231d 100%);">
                            {{ Auth::user()->display_initial }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <div class="text-sm font-bold text-gray-800 leading-none">{{ Auth::user()->display_name }}</div>
                            <div class="text-[10px] text-gray-500 uppercase tracking-wide font-semibold leading-none mt-0.5">{{ str_replace('_', ' ', Auth::user()->role) }}</div>
                        </div>
                        <span class="material-symbols-rounded text-gray-400 hidden sm:inline" style="font-size:16px;">expand_more</span>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50" style="display:none;">
                        <div class="px-4 py-2.5 border-b border-gray-100">
                            <div class="font-bold text-sm text-gray-900 truncate">{{ Auth::user()->display_name }}</div>
                            <div class="text-[11px] text-gray-500 uppercase tracking-wide mt-0.5">{{ str_replace('_', ' ', Auth::user()->role) }}</div>
                        </div>
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <span class="material-symbols-rounded" style="font-size:17px;">manage_accounts</span>
                            <span>Profil Saya</span>
                        </a>
                        <div class="border-t border-gray-100 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <span class="material-symbols-rounded" style="font-size:17px;">logout</span>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        @if(isset($header))
            <div class="bg-white border-b border-gray-100 py-4 px-4 lg:px-8">
                {{ $header }}
            </div>
        @endif

        <!-- Page Content -->
        <main class="flex-1 min-w-0">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="py-6 text-center text-sm text-gray-500 font-medium hidden lg:block border-t border-gray-100 bg-white mt-auto">
            &copy; {{ date('Y') }} TSTH2 Hub. All rights reserved.
        </footer>
    </div>

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('mobileMenuBtn');
            const closeBtn = document.getElementById('closeSidebarBtn');
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            function openSidebar() {
                if (!sidebar) return;
                sidebar.classList.add('mobile-open');
                if (backdrop) backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                if (!sidebar) return;
                sidebar.classList.remove('mobile-open');
                if (backdrop) backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            if (btn) btn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);
        });
    </script>
</body>
</html>