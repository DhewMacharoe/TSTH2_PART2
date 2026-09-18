<!DOCTYPE html>
<html lang="id" class="bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - TSTH2 Hub')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800|inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
        .sidebar-link { @apply flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium; }
        .sidebar-link:hover { @apply bg-white/10 text-white translate-x-1; }
        .sidebar-link.active { @apply bg-accent text-white shadow-md font-bold; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="text-gray-800 antialiased min-h-screen flex custom-scrollbar overflow-x-hidden">

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 w-72 bg-primary text-white shadow-2xl flex flex-col z-50 transform transition-transform duration-300 -translate-x-full lg:translate-x-0" id="sidebar">
        <!-- Logo Area -->
        <div class="h-24 flex items-center gap-4 px-6 border-b border-white/10 pt-4 pb-4">
            <div class="w-16 h-16 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-16 w-16 object-contain" style="filter: grayscale(1) invert(1) brightness(2); transform: scale(1.3);">
            </div>
            <div>
                <h1 class="text-xl font-extrabold tracking-tight">TSTH2 <span class="text-accent">Hub</span></h1>
                <p class="text-xs text-white/60 font-semibold uppercase tracking-wider">Admin Portal</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-4 overflow-y-auto custom-scrollbar">
            <p class="px-4 text-xs font-bold text-white/40 uppercase tracking-widest mb-2 mt-4">Menu Utama</p>
            
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('dashboard') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <span class="text-xl">📊</span>
                <span>Dashboard</span>
            </a>
            
            @if(in_array(auth()->user()->role, ['pic_warehouse', 'warehouse', 'agronom']))
            <p class="px-4 text-xs font-bold text-white/40 uppercase tracking-widest mb-2 mt-6">Inventory</p>
            <a href="{{ route('warehouse') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('warehouse*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <span class="text-xl">📦</span>
                <span>Data Barang</span>
            </a>
            @php
                $pendingRequests = 0;
                if(auth()->user()->role === 'pic_warehouse') {
                    $pendingRequests = \App\Models\PermintaanBarang::where('status', 'Waiting')->count();
                } elseif(auth()->user()->role === 'warehouse') {
                    $pendingRequests = \App\Models\PermintaanBarang::where('status', 'On Progress')->count();
                }
            @endphp
            <a href="{{ route('request.index') }}" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('request*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <span class="text-xl">🛒</span>
                    <span>Permintaan Barang</span>
                </div>
                @if($pendingRequests > 0)
                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm">{{ $pendingRequests }}</span>
                @endif
            </a>
            @endif

            @if(in_array(auth()->user()->role, ['pic_warehouse', 'warehouse', 'agronom', 'pimpinan']))
            <p class="px-4 text-xs font-bold text-white/40 uppercase tracking-widest mb-2 mt-6">Aktivitas</p>
            <a href="{{ route('logbook') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('logbook*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <span class="text-xl">📖</span>
                <span>Logbook Karyawan</span>
            </a>
            @endif



            @if(auth()->user()->role === 'web_admin')
            <p class="px-4 text-xs font-bold text-white/40 uppercase tracking-widest mb-2 mt-6">Pengaturan</p>
            <div x-data="{ open: {{ request()->routeIs('web.management*') ? 'true' : 'false' }} }">
                <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('web.management*') ? 'bg-accent text-white shadow-md font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">🌐</span>
                        <span>Manajemen Web Publik</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                    <a href="{{ route('web.management.home') }}" class="block text-sm font-medium {{ request()->routeIs('web.management.home') ? 'text-white font-bold translate-x-1' : 'text-white/70 hover:text-white hover:translate-x-1' }} transition-all duration-300">Halaman Utama</a>
                    <a href="{{ route('web.management.program') }}" class="block text-sm font-medium {{ request()->routeIs('web.management.program') ? 'text-white font-bold translate-x-1' : 'text-white/70 hover:text-white hover:translate-x-1' }} transition-all duration-300">Program Unggulan</a>
                    <a href="{{ route('web.management.research') }}" class="block text-sm font-medium {{ request()->routeIs('web.management.research') ? 'text-white font-bold translate-x-1' : 'text-white/70 hover:text-white hover:translate-x-1' }} transition-all duration-300">Fasilitas & Penelitian</a>
                    <a href="{{ route('web.management.news') }}" class="block text-sm font-medium {{ request()->routeIs('web.management.news') ? 'text-white font-bold translate-x-1' : 'text-white/70 hover:text-white hover:translate-x-1' }} transition-all duration-300">Berita & Artikel</a>
                    <a href="{{ route('web.management.gallery') }}" class="block text-sm font-medium {{ request()->routeIs('web.management.gallery') ? 'text-white font-bold translate-x-1' : 'text-white/70 hover:text-white hover:translate-x-1' }} transition-all duration-300">Galeri</a>
                    <a href="{{ route('web.management.layout') }}" class="block text-sm font-medium {{ request()->routeIs('web.management.layout') ? 'text-white font-bold translate-x-1' : 'text-white/70 hover:text-white hover:translate-x-1' }} transition-all duration-300">Header & Footer</a>
                    <a href="{{ route('web.management.katalog') }}" class="block text-sm font-medium {{ request()->routeIs('web.management.katalog') ? 'text-white font-bold translate-x-1' : 'text-white/70 hover:text-white hover:translate-x-1' }} transition-all duration-300">Katalog</a>
                </div>
            </div>
            @endif
        </nav>

        <!-- User Profile Bottom -->
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 px-4 py-3 bg-white/5 rounded-xl border border-white/10">
                <a href="{{ route('profile.edit') }}" class="w-10 h-10 bg-gradient-to-br from-accent to-accent-dark rounded-full flex items-center justify-center text-white font-bold shadow-inner">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </a>
                <div class="flex-1 overflow-hidden">
                    <div class="font-bold text-sm truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-accent uppercase tracking-wider font-semibold truncate">{{ str_replace('_', ' ', auth()->user()->role) }}</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col lg:ml-72 min-h-screen transition-all duration-300 min-w-0">
        
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
                        <h2 class="text-xl font-bold text-gray-800">Dashboard</h2>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors text-sm">
                    <span>🏠</span> <span class="hidden sm:inline">Web Publik</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg font-medium transition-colors text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 lg:p-8">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="py-6 text-center text-sm text-gray-500 font-medium">
            &copy; {{ date('Y') }} TSTH2. All rights reserved.
        </footer>
    </div>

    <script>
        // Mobile Sidebar Toggle
        const btn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        
        if(btn && sidebar) {
            btn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }
    </script>
    @stack('scripts')
    <!-- Division Chat Widget -->
    @if(in_array(auth()->user()->role, ['pic_warehouse', 'warehouse']))
    <div id="divisionChatWidget" class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
        <!-- Chat Window -->
        <div id="divisionChatWindow" class="hidden w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col mb-4 max-h-[500px] transition-all transform origin-bottom-right">
            <!-- Header -->
            <div class="bg-primary text-white p-4 flex items-center justify-between">
                <div>
                    <h3 class="font-bold flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg> Chat Divisi</h3>
                    <p class="text-xs text-primary-light font-medium opacity-90 mt-0.5">PIC & Warehouse Staff</p>
                </div>
                <button type="button" onclick="toggleDivisionChat()" class="text-white/80 hover:text-white transition-colors bg-white/10 hover:bg-white/20 p-1.5 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Messages -->
            <div id="divisionChatMessages" class="flex-1 p-4 overflow-y-auto space-y-4 bg-gray-50/50 h-[350px] custom-scrollbar">
                <div class="text-center text-gray-400 text-sm mt-10">Memuat diskusi...</div>
            </div>

            <!-- Input -->
            <div class="p-3 border-t border-gray-100 bg-white">
                <form id="divisionChatForm" onsubmit="submitDivisionChat(event)" class="flex gap-2">
                    <input type="text" id="divisionChatInput" class="flex-1 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl focus:ring-primary focus:border-primary px-4 py-2.5 text-sm shadow-sm font-medium transition-all" placeholder="Ketik pesan..." required autocomplete="off">
                    <button type="submit" class="p-2.5 bg-primary text-white rounded-xl hover:bg-primary-light transition-all duration-300 shadow-md shadow-primary/20 hover:shadow-primary/40 flex items-center justify-center hover:-translate-y-0.5">
                        <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Floating Button -->
        <button type="button" onclick="toggleDivisionChat()" class="relative w-14 h-14 bg-gradient-to-r from-primary to-primary-light text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-300 group">
            <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            <span id="divisionChatBadge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-black min-w-[22px] h-[22px] flex items-center justify-center rounded-full border-2 border-white shadow-sm px-1">0</span>
        </button>
    </div>

    <script>
        let isDivisionChatOpen = false;
        let divisionChatInterval = null;
        const currentDivisionUserId = {{ auth()->id() }};

        function toggleDivisionChat() {
            const window = document.getElementById('divisionChatWindow');
            isDivisionChatOpen = !isDivisionChatOpen;
            
            if(isDivisionChatOpen) {
                window.classList.remove('hidden');
                document.getElementById('divisionChatBadge').classList.add('hidden');
                
                // Mark as read
                fetch('{{ route("division-chat.read") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                fetchDivisionChats();
                if(!divisionChatInterval) {
                    divisionChatInterval = setInterval(fetchDivisionChats, 5000);
                }
            } else {
                window.classList.add('hidden');
                if(divisionChatInterval) {
                    clearInterval(divisionChatInterval);
                    divisionChatInterval = null;
                }
            }
        }

        function fetchDivisionChats() {
            fetch('{{ route("division-chat.index") }}')
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('divisionChatMessages');
                    if(data.length === 0) {
                        container.innerHTML = '<div class="text-center text-gray-400 text-sm mt-10 font-medium">Belum ada diskusi divisi.<br>Mulai obrolan sekarang!</div>';
                        return;
                    }

                    let html = '';
                    data.forEach(chat => {
                        const isMe = chat.user_id === currentDivisionUserId;
                        const time = new Date(chat.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                        
                        if (isMe) {
                            html += `
                                <div class="flex flex-col items-end animate-fade-in-up">
                                    <div class="bg-primary text-white rounded-2xl rounded-tr-none px-4 py-2.5 max-w-[85%] shadow-sm">
                                        <p class="text-sm leading-relaxed">${chat.pesan}</p>
                                    </div>
                                    <span class="text-[10px] text-gray-400 mt-1.5 font-medium">${time}</span>
                                </div>
                            `;
                        } else {
                            html += `
                                <div class="flex flex-col items-start animate-fade-in-up">
                                    <div class="flex items-end gap-2 max-w-[85%]">
                                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex-shrink-0 flex items-center justify-center text-xs font-bold text-gray-600 shadow-inner">${chat.user.name.charAt(0).toUpperCase()}</div>
                                        <div class="bg-white text-gray-800 rounded-2xl rounded-tl-none px-4 py-2.5 border border-gray-100 shadow-sm">
                                            <div class="text-[10px] font-extrabold text-primary mb-1 tracking-wide">${chat.user.name}</div>
                                            <p class="text-sm leading-relaxed">${chat.pesan}</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] text-gray-400 mt-1.5 ml-9 font-medium">${time}</span>
                                </div>
                            `;
                        }
                    });

                    const isScrolledToBottom = container.scrollHeight - container.clientHeight <= container.scrollTop + 10;
                    container.innerHTML = html;
                    if (isScrolledToBottom) {
                        container.scrollTop = container.scrollHeight;
                    }
                });
        }

        function submitDivisionChat(e) {
            e.preventDefault();
            const input = document.getElementById('divisionChatInput');
            const pesan = input.value;
            if(!pesan.trim()) return;

            input.value = '';

            fetch('{{ route("division-chat.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ pesan })
            })
            .then(res => res.json())
            .then(() => {
                fetchDivisionChats();
                setTimeout(() => {
                    const container = document.getElementById('divisionChatMessages');
                    container.scrollTop = container.scrollHeight;
                }, 100);
            });
        }

        // Check unread count periodically if chat is closed
        setInterval(() => {
            if(!isDivisionChatOpen) {
                fetch('{{ route("division-chat.unread") }}')
                    .then(res => res.json())
                    .then(data => {
                        const badge = document.getElementById('divisionChatBadge');
                        if(data.count > 0) {
                            badge.textContent = data.count > 99 ? '99+' : data.count;
                            badge.classList.remove('hidden');
                        } else {
                            badge.classList.add('hidden');
                        }
                    });
            }
        }, 10000); // Check every 10 seconds

        // Initial check
        document.addEventListener('DOMContentLoaded', () => {
            fetch('{{ route("division-chat.unread") }}')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('divisionChatBadge');
                    if(data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.classList.remove('hidden');
                    }
                });
        });
    </script>
    @endif
</body>
</html>
