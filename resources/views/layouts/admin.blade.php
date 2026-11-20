@php
    $urlPengunjung = rtrim(config('app.url_pengunjung', env('URL_PENGUNJUNG', 'http://localhost:8000')), '/');
    $urlLogbook = rtrim(config('app.url_logbook', env('URL_LOGBOOK', 'http://localhost:8001')), '/');
    $urlGudang = rtrim(config('app.url_gudang', env('URL_GUDANG', 'http://localhost:8002')), '/');
@endphp
<!DOCTYPE html>
<html lang="id" class="bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'Katalog Tanaman - TSTH2 Hub')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/tsth2-logo.png') }}">

    <!-- Fonts & Icons (Seragam dengan Port 8000) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800|inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; background: #f9fafb; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
        .material-symbols-rounded { font-size: 20px; font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; flex-shrink: 0; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
    </style>
</head>
<body class="text-gray-800 antialiased min-h-screen flex bg-gray-50 overflow-x-hidden">

    <!-- Mobile Backdrop Overlay -->
    <div id="sidebarBackdrop" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:40;" onclick="toggleMobileSidebar()"></div>

    <!-- ═══ SIDEBAR ADMIN TSTH2 HUB (IDENTIK DENGAN PORT 8000) ═══ -->
    <aside id="sidebar" style="position: fixed; inset-block: 0; left: 0; z-index: 50; width: 18rem; background: linear-gradient(180deg, #054439 0%, #02231d 100%); color: #ffffff; display: flex; flex-direction: column; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); transition: transform 0.3s ease;">
        <!-- Logo Area -->
        <div style="height: 5.5rem; display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 2.75rem; height: 2.75rem; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 0.75rem; background: rgba(255,255,255,0.1); padding: 0.25rem;">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo" style="height: 100%; width: 100%; object-fit: contain;" onerror="this.src='{{ $urlPengunjung }}/assets/logo.png'">
                </div>
                <div>
                    <h1 style="font-size: 1.25rem; font-weight: 800; letter-spacing: -0.025em; color: #ffffff; line-height: 1.2; margin: 0;">TSTH2 <span style="color: #c29453;">Hub</span></h1>
                    <p style="font-size: 10px; color: rgba(255,255,255,0.6); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">ADMINISTRATOR KATALOG</p>
                </div>
            </div>
            <button onclick="toggleMobileSidebar()" style="display: none; background: transparent; border: none; color: rgba(255,255,255,0.7); cursor: pointer;" id="closeSidebarBtn">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>

        <!-- Navigation Menus -->
        <nav class="custom-scrollbar" style="flex: 1; padding: 1.5rem 1rem; overflow-y: auto; display: flex; flex-direction: column; gap: 0.35rem;">
            <p style="padding: 0 1rem; font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 0.5rem 0;">Konten Website</p>
            
            <a href="{{ route('sso.jump', ['portal' => 'pengunjung', 'target' => '/web-management/home']) }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-weight: 500; font-size: 0.88rem; color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.7)';">
                <span class="material-symbols-rounded">home</span>
                <span>Beranda (Home)</span>
            </a>

            <a href="{{ route('sso.jump', ['portal' => 'pengunjung', 'target' => '/web-management/penelitian']) }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-weight: 500; font-size: 0.88rem; color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.7)';">
                <span class="material-symbols-rounded">science</span>
                <span>Fasilitas & Riset</span>
            </a>

            {{-- 🌟 KATALOG TANAMAN (MENU AKTIF EMAS IDENTIK DENGAN SCREENSHOT 8000) 🌟 --}}
            <a href="{{ route('admin.plants.index') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-weight: 700; font-size: 0.88rem; background: #c29453; color: #ffffff; text-decoration: none; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                <span class="material-symbols-rounded">eco</span>
                <span>Katalog Tanaman</span>
            </a>
            {{-- Sub-menu Katalog --}}
            <div style="padding-left: 2rem; display: flex; flex-direction: column; gap: 0.25rem; margin-top: 2px; margin-bottom: 6px;">
                <a href="{{ route('admin.plants.index') }}" style="font-size: 0.8rem; font-weight: 600; padding: 6px 12px; border-radius: 6px; text-decoration: none; {{ request()->routeIs('admin.plants*') ? 'color: #ffffff; background: rgba(255,255,255,0.15);' : 'color: rgba(255,255,255,0.6);' }}">
                    • Kelola Tanaman
                </a>
                <a href="{{ route('admin.categories.index') }}" style="font-size: 0.8rem; font-weight: 600; padding: 6px 12px; border-radius: 6px; text-decoration: none; {{ request()->routeIs('admin.categories*') ? 'color: #ffffff; background: rgba(255,255,255,0.15);' : 'color: rgba(255,255,255,0.6);' }}">
                    • Kelola Kategori
                </a>
            </div>

            <a href="{{ route('sso.jump', ['portal' => 'pengunjung', 'target' => '/web-management/berita']) }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-weight: 500; font-size: 0.88rem; color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.7)';">
                <span class="material-symbols-rounded">newspaper</span>
                <span>Berita</span>
            </a>

            <a href="{{ route('sso.jump', ['portal' => 'pengunjung', 'target' => '/web-management/galeri']) }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-weight: 500; font-size: 0.88rem; color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.7)';">
                <span class="material-symbols-rounded">photo_library</span>
                <span>Galeri Foto</span>
            </a>

            <a href="{{ route('sso.jump', ['portal' => 'pengunjung', 'target' => '/web-management/layout']) }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-weight: 500; font-size: 0.88rem; color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.7)';">
                <span class="material-symbols-rounded">settings</span>
                <span>Header & Footer</span>
            </a>

            <div style="padding: 0.5rem 0.5rem 0;">
                <a href="{{ route('home') }}" target="_blank" style="display: flex; align-items: center; justify-content: space-between; padding: 0.65rem 1rem; border-radius: 0.75rem; background: rgba(255,255,255,0.1); color: #ffffff; font-size: 0.75rem; font-weight: 600; border: 1px solid rgba(255,255,255,0.1); text-decoration: none;">
                    <div style="display: flex; align-items: center; gap: 0.6rem;">
                        <span class="material-symbols-rounded" style="font-size:18px;">open_in_new</span>
                        <span>Pratinjau Web</span>
                    </div>
                    <span class="material-symbols-rounded" style="font-size:16px; opacity:0.7;">arrow_forward</span>
                </a>
            </div>

            <p style="padding: 0 1rem; font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.05em; margin: 1.5rem 0 0.5rem 0;">Manajemen Sistem & Logbook</p>
            
            <a href="{{ route('sso.jump', ['portal' => 'logbook', 'target' => '/admin/users']) }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-weight: 500; font-size: 0.88rem; color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.7)';">
                <span class="material-symbols-rounded">group</span>
                <span>Manajemen User</span>
            </a>
            <a href="{{ route('sso.jump', ['portal' => 'logbook', 'target' => '/admin/departments']) }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-weight: 500; font-size: 0.88rem; color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.7)';">
                <span class="material-symbols-rounded">corporate_fare</span>
                <span>Departemen & Divisi</span>
            </a>
            <a href="{{ route('sso.jump', ['portal' => 'logbook', 'target' => '/admin/targets']) }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-weight: 500; font-size: 0.88rem; color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.7)';">
                <span class="material-symbols-rounded">flag</span>
                <span>Target & Sasaran</span>
            </a>

            <p style="padding: 0 1rem; font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.05em; margin: 1.5rem 0 0.5rem 0;">Warehouse & Inventaris</p>
            <a href="{{ route('sso.jump', ['portal' => 'warehouse', 'target' => '/gudang']) }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-weight: 500; font-size: 0.88rem; color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.7)';">
                <span class="material-symbols-rounded">inventory_2</span>
                <span>Data Barang</span>
            </a>
        </nav>
    </aside>

    <!-- ═══ MAIN CONTENT WRAPPER (IDENTIK DENGAN PORT 8000) ═══ -->
    <div style="flex: 1; display: flex; flex-direction: column; margin-left: 18rem; min-height: 100vh; min-width: 0;" id="mainWrapper">
        
        <!-- Topbar Admin -->
        <header style="height: 5rem; background: #ffffff; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); display: flex; align-items: center; justify-content: space-between; padding: 0 2rem; position: sticky; top: 0; z-index: 40; border-bottom: 1px solid #e2e8f0;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button onclick="toggleMobileSidebar()" style="display: none; width: 2.5rem; height: 2.5rem; align-items: center; justify-content: center; border-radius: 0.5rem; background: #f3f4f6; border: none; cursor: pointer;" id="mobileMenuBtn">
                    <span class="material-symbols-rounded" style="color: #4b5563;">menu</span>
                </button>
                <div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <h2 style="font-size: 1.25rem; font-weight: 700; color: #1f2937; margin: 0; line-height: 1.2;">Katalog Tanaman</h2>
                        <span style="display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; background: #e6f4ea; color: #164E3F; border: 1px solid #b7e1cd;">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981;"></span>
                            ADMIN KATALOG FLORA
                        </span>
                    </div>
                    <p style="font-size: 0.78rem; color: #6b7280; margin: 2px 0 0 0;">Kelola data koleksi tanaman herbal & hortikultura TSTH2.</p>
                </div>
            </div>

            <!-- Profile & Actions Right -->
            <div style="display: flex; align-items: center; gap: 1rem;">
                {{-- Bell Notification --}}
                <div style="width: 2.25rem; height: 2.25rem; border-radius: 0.75rem; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #4b5563; cursor: pointer;">
                    <span class="material-symbols-rounded">notifications</span>
                </div>

                {{-- User Profile (Tanpa Nama Orang Sesuai Instruksi) --}}
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.35rem 0.75rem; border-radius: 0.75rem; background: #f9fafb; border: 1px solid #e5e7eb;">
                    <div style="width: 2.25rem; height: 2.25rem; border-radius: 50%; background: linear-gradient(135deg, #164E3F 0%, #0d382d 100%); display: flex; align-items: center; justify-content: center; color: #ffffff; font-weight: 800; font-size: 0.85rem; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                        W
                    </div>
                    <div style="text-align: left;">
                        <div style="font-size: 0.875rem; font-weight: 700; color: #1f2937; line-height: 1;">Web Administrator</div>
                        <div style="font-size: 10px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 3px;">WEB ADMIN</div>
                    </div>
                </div>

                {{-- Logout Button --}}
                <form method="POST" action="{{ route('admin.logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" style="display: inline-flex; align-items: center; gap: 4px; padding: 0.45rem 0.85rem; border-radius: 0.5rem; font-size: 0.78rem; font-weight: 700; color: #dc2626; background: #fef2f2; border: 1px solid #fee2e2; cursor: pointer; transition: background 0.2s;" title="Keluar">
                        <span class="material-symbols-rounded" style="font-size: 16px;">logout</span>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Content Area -->
        <main style="flex: 1; padding: 1.5rem 2rem;">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer style="padding: 1.5rem 2rem; text-align: center; font-size: 0.82rem; color: #6b7280; font-weight: 500; border-top: 1px solid #e5e7eb; background: #ffffff;">
            &copy; {{ date('Y') }} TSTH2 Hub - Taman Sains Teknologi Herbal dan Hortikultura. All rights reserved.
        </footer>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success')),
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#003f32',
                    timer: 3200,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'error',
                    title: 'Periksa kembali form',
                    text: @json(implode("\n", $errors->all())),
                    confirmButtonColor: '#003f32',
                });
            });
        </script>
    @endif

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar.style.transform === 'translateX(0px)') {
                sidebar.style.transform = 'translateX(-100%)';
                backdrop.style.display = 'none';
            } else {
                sidebar.style.transform = 'translateX(0px)';
                backdrop.style.display = 'block';
            }
        }
        function handleResize() {
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.getElementById('mainWrapper');
            const mobileBtn = document.getElementById('mobileMenuBtn');
            const closeBtn = document.getElementById('closeSidebarBtn');
            if (window.innerWidth < 1024) {
                sidebar.style.transform = 'translateX(-100%)';
                mainWrapper.style.marginLeft = '0';
                if (mobileBtn) mobileBtn.style.display = 'flex';
                if (closeBtn) closeBtn.style.display = 'block';
            } else {
                sidebar.style.transform = 'translateX(0)';
                mainWrapper.style.marginLeft = '18rem';
                if (mobileBtn) mobileBtn.style.display = 'none';
                if (closeBtn) closeBtn.style.display = 'none';
            }
        }
        window.addEventListener('resize', handleResize);
        document.addEventListener('DOMContentLoaded', handleResize);

        // SweetAlert Delete confirmation
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('form[data-delete-form]').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    const title = form.dataset.deleteTitle || 'Hapus data?';
                    const text = form.dataset.deleteText || 'Data yang sudah dihapus tidak dapat dikembalikan.';
                    Swal.fire({
                        icon: 'warning',
                        title,
                        text,
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#b04d3e',
                        cancelButtonColor: '#718178',
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>
</body>
</html>
