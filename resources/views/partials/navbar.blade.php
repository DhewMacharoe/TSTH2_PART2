@php
    $headerStyle = 'background: #ffffff; border-bottom: 1px solid rgba(22, 78, 63, 0.1); box-shadow: 0 4px 20px rgba(0,0,0,0.04);';
    $textColor = 'color: #0f172a;';
    $logoColor = 'color: #164E3F;';
@endphp

<style>
    .header-container {
        width: 100%;
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-sizing: border-box;
    }
    .logo-container {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        min-width: 0;
    }
    .logo-container img {
        height: 46px;
        width: auto;
        object-fit: contain;
        flex-shrink: 0;
    }
    .logo-subtext {
        color: #475569;
        font-size: 0.72rem;
        font-weight: 600;
        line-height: 1.2;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        display: block;
    }
    @media (max-width: 640px) {
        .header-container {
            padding: 0 0.85rem;
        }
        .logo-container img {
            height: 38px;
        }
        .logo-text {
            font-size: 1.15rem !important;
        }
        .logo-subtext {
            display: none !important;
        }
        .mobile-menu-toggle {
            display: flex !important;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            font-size: 1.6rem !important;
            padding: 0;
            border-radius: 8px;
        }
    }
    @media (max-width: 320px) {
        .header-container {
            padding: 0 0.5rem;
        }
        .logo-container img {
            height: 30px;
        }
        .logo-text {
            font-size: 1rem !important;
        }
    }
    /* Mobile Menu Drawer Styling */
    .mobile-menu-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        background: rgba(5, 68, 57, 0.4);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 99999;
        display: none;
        justify-content: flex-end;
    }
    .mobile-menu-content {
        background: #ffffff;
        width: 85%;
        max-width: 320px;
        height: 100%;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        box-shadow: -5px 0 25px rgba(0,0,0,0.15);
        animation: slideInNav 0.25s ease-out;
        box-sizing: border-box;
    }
    @keyframes slideInNav {
        from { transform: translateX(100%); }
        to { transform: translateX(0); }
    }
    .mobile-menu-close {
        align-self: flex-end;
        background: #f1f5f9;
        border: none;
        font-size: 1.5rem;
        line-height: 1;
        color: #475569;
        cursor: pointer;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background 0.2s;
    }
    .mobile-menu-close:hover {
        background: #e2e8f0;
    }
    .mobile-nav-links {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        margin-bottom: 2rem;
    }
    .mobile-nav-links a {
        font-family: var(--font-heading, sans-serif);
        font-size: 1.05rem;
        font-weight: 600;
        color: #1e293b;
        text-decoration: none;
        padding: 0.65rem 0.5rem;
        border-radius: 8px;
        transition: background 0.2s, color 0.2s;
    }
    .mobile-nav-links a.active, .mobile-nav-links a:hover {
        background: #f0fdf4;
        color: #054439;
    }
</style>

<header class="main-header" id="header" style="{{ $headerStyle }}">
    <div class="header-container">
        <a href="{{ route('home') }}" class="logo-container">
            <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo">
            <div style="display: flex; flex-direction: column; justify-content: center; min-width: 0;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="logo-text" style="{{ $logoColor }}; font-size: 1.35rem; line-height: 1.1; font-weight: 800; letter-spacing: -0.02em;">TSTH2</span>
                </div>
                <span class="logo-subtext">Taman Sains Teknologi Herbal dan Hortikultura</span>
            </div>
        </a>
        
        <nav class="nav-menu" style="text-transform: uppercase;">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" style="{{ $textColor }}">Beranda</a>
            <a href="{{ route('profil-public') }}" class="nav-link {{ request()->routeIs('profil-public') ? 'active' : '' }}" style="{{ $textColor }}">Profil &amp; Tata Kelola</a>
            <a href="{{ route('penelitian') }}" class="nav-link {{ request()->routeIs('penelitian') ? 'active' : '' }}" style="{{ $textColor }}">Riset</a>
            <a href="{{ route('berita') }}" class="nav-link {{ request()->routeIs('berita') ? 'active' : '' }}" style="{{ $textColor }}">Berita &amp; Artikel</a>
            <a href="{{ route('galeri') }}" class="nav-link {{ request()->routeIs('galeri') ? 'active' : '' }}" style="{{ $textColor }}">Galeri</a>
            
            <div class="nav-dropdown" style="position: relative; display: inline-block;">
                <a href="{{ route('katalog') }}" class="nav-link nav-dropdown-toggle {{ request()->routeIs('katalog') || request()->routeIs('collection') || request()->routeIs('plant.show') ? 'active' : '' }}" style="{{ $textColor }}; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                    Katalog Tanaman
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.2s;"><path d="m6 9 6 6 6-6"/></svg>
                </a>
                <div class="nav-dropdown-menu" style="display: none; position: absolute; top: 100%; left: 0; background: #ffffff; min-width: 230px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.12); border: 1px solid rgba(0,0,0,0.06); padding: 0.5rem 0; z-index: 1000; margin-top: 4px;">
                    <a href="{{ route('katalog') }}" class="dropdown-item-link" style="display: flex; align-items: center; gap: 8px; padding: 0.75rem 1.25rem; font-size: 0.875rem; font-weight: 500; color: #1e293b; text-decoration: none; text-transform: none; transition: background 0.2s, color 0.2s; {{ request()->routeIs('katalog') ? 'background: #f0fdf4; color: var(--color-primary); font-weight: 600;' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg> Greenhouse Collection
                    </a>
                    <a href="{{ route('collection') }}" class="dropdown-item-link" style="display: flex; align-items: center; gap: 8px; padding: 0.75rem 1.25rem; font-size: 0.875rem; font-weight: 500; color: #1e293b; text-decoration: none; text-transform: none; transition: background 0.2s, color 0.2s; {{ request()->routeIs('collection') ? 'background: #f0fdf4; color: var(--color-primary); font-weight: 600;' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22v-9"/><path d="M12 13C7 13 4 8 4 3c5 0 8 3 8 8z"/><path d="M12 13c5 0 8-5 8-10-5 0-8 3-8 8z"/></svg> Outdoor Flora (Koleksi Lahan)
                    </a>
                </div>
            </div>
        </nav>

        <div class="nav-actions-desktop" style="display: flex; gap: 0.5rem; align-items: center;">
            @auth
                <a href="{{ route('dasbor') }}" class="btn btn-primary" style="font-size: 0.875rem; padding: 0.5rem 1.25rem;">Admin Portal</a>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="font-size: 0.875rem; padding: 0.5rem 1.25rem; border-color: #cbd5e1; color: #475569;">Log Out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary" style="font-size: 0.875rem; padding: 0.55rem 1.35rem;">Portal Login</a>
            @endauth
        </div>

        <!-- Hamburger Button for Mobile & Smartwatch -->
        <button class="mobile-menu-toggle" id="mobileMenuBtn" aria-label="Toggle Menu" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; display: none; {{ $textColor }}">
            ☰
        </button>
    </div>
</header>

<!-- Mobile Menu Overlay Drawer -->
<div class="mobile-menu-overlay" id="mobileMenu" style="display: none;">
    <div class="mobile-menu-content">
        <button class="mobile-menu-close" id="mobileMenuClose" aria-label="Close Menu">&times;</button>
        
        <div style="margin-bottom: 1.5rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem;">
            <div style="font-weight: 800; color: #164E3F; font-size: 1.2rem;">TSTH2 Hub</div>
            <div style="font-size: 0.75rem; color: #64748b;">Taman Sains Teknologi Herbal & Hortikultura</div>
        </div>

        <nav class="mobile-nav-links">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('profil-public') }}" class="{{ request()->routeIs('profil-public') ? 'active' : '' }}">Profil &amp; Tata Kelola</a>
            <a href="{{ route('penelitian') }}" class="{{ request()->routeIs('penelitian') ? 'active' : '' }}">Riset</a>
            <a href="{{ route('berita') }}" class="{{ request()->routeIs('berita') ? 'active' : '' }}">Berita &amp; Artikel</a>
            <a href="{{ route('galeri') }}" class="{{ request()->routeIs('galeri') ? 'active' : '' }}">Galeri</a>
            
            <div style="border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 0.5rem;">
                <button type="button" id="mobileKatalogBtn" style="background: none; border: none; width: 100%; text-align: left; font-family: var(--font-heading); font-size: 1.05rem; font-weight: 600; color: #1e293b; text-transform: uppercase; padding: 0.65rem 0.5rem; cursor: pointer; display: flex; justify-content: space-between; align-items: center; border-radius: 8px;">
                    Katalog Tanaman
                    <svg id="mobileKatalogArrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.2s;"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div id="mobileKatalogSubmenu" style="display: none; flex-direction: column; gap: 0.5rem; padding-left: 1rem; margin-top: 0.25rem;">
                    <a href="{{ route('katalog') }}" style="font-size: 0.95rem; font-weight: 500; text-decoration: none; color: {{ request()->routeIs('katalog') ? 'var(--color-primary)' : '#64748b' }};">Greenhouse Collection</a>
                    <a href="{{ route('collection') }}" style="font-size: 0.95rem; font-weight: 500; text-decoration: none; color: {{ request()->routeIs('collection') ? 'var(--color-primary)' : '#64748b' }};">Outdoor Flora</a>
                </div>
            </div>
        </nav>
        
        <div class="mobile-nav-actions" style="margin-top: auto; pt-4">
            @auth
                <a href="{{ route('dasbor') }}" class="btn btn-primary" style="width: 100%; text-align: center; margin-bottom: 10px; display: block; box-sizing: border-box;">Admin Portal</a>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0; width: 100%;">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="width: 100%; cursor: pointer; display: block; box-sizing: border-box;">Log Out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%; text-align: center; display: block; box-sizing: border-box;">Portal Login</a>
            @endauth
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenuClose = document.getElementById('mobileMenuClose');
        const mobileMenu = document.getElementById('mobileMenu');
        
        if(mobileMenuBtn && mobileMenu && mobileMenuClose) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });
            
            mobileMenuClose.addEventListener('click', function() {
                mobileMenu.style.display = 'none';
                document.body.style.overflow = '';
            });
            
            mobileMenu.addEventListener('click', function(e) {
                if(e.target === mobileMenu) {
                    mobileMenu.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });
        }

        // Desktop Katalog Dropdown Toggle / Hover
        const dropdownContainer = document.querySelector('.nav-dropdown');
        const dropdownToggle = document.querySelector('.nav-dropdown-toggle');
        const dropdownMenu = document.querySelector('.nav-dropdown-menu');
        
        if (dropdownContainer && dropdownToggle && dropdownMenu) {
            dropdownToggle.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                }
                e.stopPropagation();
                const isVisible = dropdownMenu.style.display === 'block';
                dropdownMenu.style.display = isVisible ? 'none' : 'block';
            });

            document.addEventListener('click', function(e) {
                if (!dropdownContainer.contains(e.target)) {
                    dropdownMenu.style.display = 'none';
                }
            });

            dropdownContainer.addEventListener('mouseenter', function() {
                dropdownMenu.style.display = 'block';
            });
            dropdownContainer.addEventListener('mouseleave', function() {
                dropdownMenu.style.display = 'none';
            });
        }

        // Mobile Katalog Submenu Accordion
        const mobileKatalogBtn = document.getElementById('mobileKatalogBtn');
        const mobileKatalogSubmenu = document.getElementById('mobileKatalogSubmenu');
        const mobileKatalogArrow = document.getElementById('mobileKatalogArrow');

        if (mobileKatalogBtn && mobileKatalogSubmenu) {
            mobileKatalogBtn.addEventListener('click', function() {
                const isOpen = mobileKatalogSubmenu.style.display === 'flex';
                mobileKatalogSubmenu.style.display = isOpen ? 'none' : 'flex';
                if (mobileKatalogArrow) {
                    mobileKatalogArrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
                }
            });
        }
    });
</script>
