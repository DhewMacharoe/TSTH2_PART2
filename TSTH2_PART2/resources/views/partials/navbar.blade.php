@php
    $headerStyle = 'background: #ffffff; border-bottom: 1px solid rgba(22, 78, 63, 0.1); box-shadow: 0 4px 20px rgba(0,0,0,0.04);';
    $textColor = 'color: #0f172a;';
    $logoColor = 'color: #164E3F;';
@endphp

<style>
    /* Prevent overflow-x without breaking sticky positioning */
    html, body {
        overflow-x: clip !important;
    }
    /* Material Symbols */
    .material-symbols-rounded { font-family: 'Material Symbols Rounded'; font-style: normal; font-weight: normal; font-size: 20px; line-height: 1; letter-spacing: normal; text-transform: none; display: inline-block; white-space: nowrap; word-wrap: normal; direction: ltr; font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }

    @media (min-width: 641px) {
        .main-header {
            position: sticky !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            z-index: 1000 !important;
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
            transition: all 0.3s ease;
        }
    }
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

    /* ═══════════════════════════════════════════════
       ULTRA-PREMIUM TSTH2 DROPDOWN DESIGN SYSTEM
       ═══════════════════════════════════════════════ */
    @keyframes dropdownEntrance {
        0% {
            opacity: 0;
            transform: translateY(12px) scale(0.96);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    @keyframes pulseLiveDot {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.45);
        }
        50% {
            box-shadow: 0 0 0 5px rgba(16, 185, 129, 0);
        }
    }

    /* ═══════════════════════════════════════════════
       CLEAN, HIGHLY ACCESSIBLE DROPDOWN SYSTEM
       ═══════════════════════════════════════════════ */
    .tsth2-dropdown-menu {
        position: absolute;
        top: calc(100% + 8px);
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        box-shadow: 0 12px 28px -4px rgba(15, 23, 42, 0.15), 0 4px 10px -2px rgba(15, 23, 42, 0.08);
        padding: 6px 0;
        z-index: 1050;
        display: none;
        min-width: 250px;
        box-sizing: border-box;
    }

    /* ONLY show when explicitly active via click, stays open firmly until chosen */
    .tsth2-dropdown-menu.is-active {
        display: block !important;
    }

    /* Rotate trigger arrow when open */
    .dropdown-arrow-icon {
        transition: transform 0.2s ease;
    }
    .login-dropdown.is-open .dropdown-arrow-icon,
    .nav-dropdown.is-open .dropdown-arrow-icon {
        transform: rotate(180deg);
    }

    /* Clean plain text item (NO ICONS, high readability for all users) */
    .dropdown-plain-item {
        display: block;
        padding: 13px 20px;
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        text-decoration: none;
        transition: background-color 0.15s ease, color 0.15s ease;
        line-height: 1.4;
        cursor: pointer;
        background: #ffffff;
    }
    .dropdown-plain-item:not(:last-child) {
        border-bottom: 1px solid #f1f5f9;
    }
    .dropdown-plain-item:hover,
    .dropdown-plain-item:focus {
        background-color: #f1f5f9;
        color: #164E3F;
    }

    /* Clean trigger button for Katalog in navbar - matches .nav-link exactly */
    .nav-dropdown-btn-clean {
        background: none;
        border: none;
        cursor: pointer;
        font-family: var(--font-heading, inherit);
        font-size: 1rem;
        font-weight: 500;
        letter-spacing: inherit;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 0;
        margin: 0;
        line-height: inherit;
        position: relative;
        transition: color var(--transition-fast, 0.2s);
    }
    .nav-dropdown-btn-clean::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 0%;
        height: 2px;
        background-color: var(--color-accent, #164E3F);
        transition: width var(--transition-medium, 0.3s);
    }
    .nav-dropdown-btn-clean:hover::after,
    .nav-dropdown.is-open .nav-dropdown-btn-clean::after {
        width: 100%;
    }
    .nav-dropdown-btn-clean:hover,
    .nav-dropdown.is-open .nav-dropdown-btn-clean {
        color: var(--color-accent, #164E3F) !important;
    }
    .dropdown-arrow-icon {
        transition: transform 0.25s ease;
        display: inline-block;
        vertical-align: middle;
    }
    .nav-dropdown.is-open .dropdown-arrow-icon {
        transform: rotate(180deg);
    }

    /* Portal trigger button for Login */
    .btn-portal-trigger {
        background: #164E3F !important;
        color: #ffffff !important;
        font-size: 0.875rem !important;
        font-weight: 700 !important;
        padding: 0.6rem 1.35rem !important;
        border-radius: 999px !important;
        border: none !important;
        box-shadow: 0 4px 12px rgba(22, 78, 63, 0.25) !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
    }
    .btn-portal-trigger:hover,
    .login-dropdown.is-open .btn-portal-trigger {
        background: #0d382d !important;
        box-shadow: 0 6px 16px rgba(22, 78, 63, 0.35) !important;
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
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" style="{{ $textColor }}">{{ __('site.nav_home') }}</a>
            <a href="{{ route('profil-public') }}" class="nav-link {{ request()->routeIs('profil-public') ? 'active' : '' }}" style="{{ $textColor }}">{{ __('site.nav_profile') }}</a>
            <a href="{{ route('penelitian') }}" class="nav-link {{ request()->routeIs('penelitian') ? 'active' : '' }}" style="{{ $textColor }}">{{ __('site.nav_research') }}</a>
            <a href="{{ route('berita') }}" class="nav-link {{ request()->routeIs('berita') ? 'active' : '' }}" style="{{ $textColor }}">{{ __('site.nav_news') }}</a>
            <a href="{{ route('galeri') }}" class="nav-link {{ request()->routeIs('galeri') ? 'active' : '' }}" style="{{ $textColor }}">{{ __('site.nav_gallery') }}</a>
            
            <div class="nav-dropdown" style="position: relative; display: inline-block;">
                <button type="button" class="nav-dropdown-btn-clean" id="katalogDropdownBtn" style="{{ $textColor }}">
                    <span>{{ __('site.nav_catalog') }}</span>
                    <svg class="dropdown-arrow-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div class="tsth2-dropdown-menu" id="katalogDropdownMenu" style="left: 0; min-width: 220px;">
                    <a href="{{ route('katalog') }}" class="dropdown-plain-item">
                        {{ __('site.nav_greenhouse') }}
                    </a>
                    <a href="{{ route('collection') }}" class="dropdown-plain-item">
                        {{ __('site.nav_outdoor') }}
                    </a>
                </div>
            </div>

        </nav>

        <div class="nav-actions-desktop" style="display: flex; gap: 0.75rem; align-items: center;">
            @php $currentLocale = app()->getLocale(); @endphp
            <!-- Language Switcher Pill -->
            <div class="lang-switcher notranslate" translate="no" style="display: inline-flex; align-items: center; background: #f1f5f9; border-radius: 999px; padding: 3px; border: 1px solid #e2e8f0; font-size: 0.75rem; font-weight: 700;">
                <button type="button" onclick="setSiteLanguage('en')" id="lang-btn-en" title="English" style="display: flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; border: none; cursor: pointer; transition: all 0.2s; {{ $currentLocale === 'en' ? 'background: #164E3F; color: #ffffff; box-shadow: 0 2px 6px rgba(22,78,63,0.3); font-weight: 800;' : 'background: transparent; color: #64748b; font-weight: 600;' }}">
                    <span>EN</span>
                </button>
                <button type="button" onclick="setSiteLanguage('id')" id="lang-btn-id" title="Bahasa Indonesia" style="display: flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; border: none; cursor: pointer; transition: all 0.2s; {{ $currentLocale === 'id' ? 'background: #164E3F; color: #ffffff; box-shadow: 0 2px 6px rgba(22,78,63,0.3); font-weight: 800;' : 'background: transparent; color: #64748b; font-weight: 600;' }}">
                    <span>ID</span>
                </button>
            </div>

            @auth
                <a href="{{ route('dasbor') }}" class="btn btn-primary" style="font-size: 0.875rem; padding: 0.5rem 1.25rem;">{{ __('site.nav_admin_portal') }}</a>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="font-size: 0.875rem; padding: 0.5rem 1.25rem; border-color: #cbd5e1; color: #475569;">{{ __('site.nav_logout') }}</button>
                </form>
            @else
                <div class="login-dropdown" style="position: relative; display: inline-block;">
                    <button type="button" class="btn-portal-trigger" id="loginDropdownBtn">
                        <span>{{ __('site.nav_portal_login') }}</span>
                        <svg class="dropdown-arrow-icon" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div class="tsth2-dropdown-menu" id="loginDropdownMenu" style="right: 0; min-width: 280px;">
                        <a href="{{ route('login') }}" class="dropdown-plain-item">
                            1. Login Web Admin 
                        </a>
                        <a href="{{ config('app.url_gudang', env('URL_GUDANG', 'http://localhost:8002')) }}/login" class="dropdown-plain-item">
                            2. Login Sistem Gudang
                        </a>
                        <a href="{{ config('app.url_logbook', env('URL_LOGBOOK', 'http://localhost:8001')) }}/login" class="dropdown-plain-item">
                            3. Login Logbook Karyawan
                        </a>
                    </div>
                </div>
            @endauth
        </div>

        <!-- Hamburger Button for Mobile & Smartwatch -->
        <button class="mobile-menu-toggle" id="mobileMenuBtn" aria-label="Toggle Menu" style="background: none; border: none; cursor: pointer; display: none; {{ $textColor }}; padding: 4px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
    </div>
</header>

<!-- Mobile Menu Overlay Drawer -->
<div class="mobile-menu-overlay" id="mobileMenu" style="display: none;">
    <div class="mobile-menu-content">
        <button class="mobile-menu-close" id="mobileMenuClose" aria-label="Close Menu">
            <span class="material-symbols-rounded" style="font-size: 20px;">close</span>
        </button>
        
        <div style="margin-bottom: 1.5rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-weight: 800; color: #164E3F; font-size: 1.2rem;">TSTH2 Hub</div>
                <div style="font-size: 0.75rem; color: #64748b;">Taman Sains Teknologi Herbal &amp; Hortikultura</div>
            </div>
        </div>

        <!-- Mobile Language Switcher -->
        <div class="notranslate" translate="no" style="margin-bottom: 1.25rem; padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9;">
            <div style="font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.5px;">Language / Bahasa:</div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="button" onclick="setSiteLanguage('en')" id="m-lang-btn-en" style="flex: 1; text-align: center; padding: 0.45rem 0.75rem; border-radius: 8px; font-size: 0.85rem; font-weight: 700; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; {{ $currentLocale === 'en' ? 'background: #164E3F; color: white;' : 'background: #f1f5f9; color: #475569;' }}">
                    EN · English
                </button>
                <button type="button" onclick="setSiteLanguage('id')" id="m-lang-btn-id" style="flex: 1; text-align: center; padding: 0.45rem 0.75rem; border-radius: 8px; font-size: 0.85rem; font-weight: 700; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; {{ $currentLocale === 'id' ? 'background: #164E3F; color: white;' : 'background: #f1f5f9; color: #475569;' }}">
                    ID · Indonesia
                </button>
            </div>
        </div>

        <nav class="mobile-nav-links">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('site.nav_home') }}</a>
            <a href="{{ route('profil-public') }}" class="{{ request()->routeIs('profil-public') ? 'active' : '' }}">{{ __('site.nav_profile') }}</a>
            <a href="{{ route('penelitian') }}" class="{{ request()->routeIs('penelitian') ? 'active' : '' }}">{{ __('site.nav_research') }}</a>
            <a href="{{ route('berita') }}" class="{{ request()->routeIs('berita') ? 'active' : '' }}">{{ __('site.nav_news') }}</a>
            <a href="{{ route('galeri') }}" class="{{ request()->routeIs('galeri') ? 'active' : '' }}">{{ __('site.nav_gallery') }}</a>
            
            <div style="border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 0.5rem;">
                <button type="button" id="mobileKatalogBtn" style="background: none; border: none; width: 100%; text-align: left; font-family: var(--font-heading); font-size: 1.05rem; font-weight: 600; color: #1e293b; text-transform: uppercase; padding: 0.65rem 0.5rem; cursor: pointer; display: flex; justify-content: space-between; align-items: center; border-radius: 8px;">
                    {{ __('site.nav_catalog') }}
                    <svg id="mobileKatalogArrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.2s;"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div id="mobileKatalogSubmenu" style="display: none; flex-direction: column; gap: 0.5rem; padding-left: 1rem; margin-top: 0.25rem;">
                    <a href="{{ route('katalog') }}" style="font-size: 0.95rem; font-weight: 500; text-decoration: none; color: #64748b;">{{ __('site.nav_greenhouse') }}</a>
                    <a href="{{ route('collection') }}" style="font-size: 0.95rem; font-weight: 500; text-decoration: none; color: #64748b;">{{ __('site.nav_outdoor') }}</a>
                </div>
            </div>
        </nav>
        
        <div class="mobile-nav-actions" style="margin-top: auto; padding-top: 1rem; border-top: 1px solid #f1f5f9;">
            @auth
                <a href="{{ route('dasbor') }}" class="btn btn-primary" style="width: 100%; text-align: center; margin-bottom: 10px; display: block; box-sizing: border-box;">{{ __('site.nav_admin_portal') }}</a>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0; width: 100%;">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="width: 100%; cursor: pointer; display: block; box-sizing: border-box;">{{ __('site.nav_logout') }}</button>
                </form>
            @else
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 2px; letter-spacing: 0.5px;">Pilihan Akses Masuk:</div>
                    <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%; text-align: center; display: block; box-sizing: border-box; font-size: 0.9rem; padding: 0.75rem 1rem; font-weight: 600;">
                        1. Login Web Admin 
                    </a>
                    <a href="{{ config('app.url_gudang', env('URL_GUDANG', 'http://localhost:8002')) }}/login" class="btn btn-outline" style="width: 100%; text-align: center; display: block; box-sizing: border-box; border-color: #164E3F; color: #164E3F; background: #f0fdf4; font-size: 0.9rem; padding: 0.75rem 1rem; font-weight: 600;">
                        2. Login Sistem Gudang
                    </a>
                    <a href="{{ config('app.url_logbook', env('URL_LOGBOOK', 'http://localhost:8001')) }}/login" class="btn btn-outline" style="width: 100%; text-align: center; display: block; box-sizing: border-box; border-color: #3b82f6; color: #2563eb; background: #eff6ff; font-size: 0.9rem; padding: 0.75rem 1rem; font-weight: 600;">
                        3. Login Logbook Karyawan
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu drawer
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

        // Desktop Login Dropdown (STRICT CLICK TO OPEN & STAY OPEN)
        const loginContainer = document.querySelector('.login-dropdown');
        const loginToggle = document.getElementById('loginDropdownBtn');
        const loginMenu = document.getElementById('loginDropdownMenu');
        
        // Desktop Katalog Dropdown (STRICT CLICK TO OPEN & STAY OPEN)
        const dropdownContainer = document.querySelector('.nav-dropdown');
        const dropdownToggle = document.getElementById('katalogDropdownBtn');
        const dropdownMenu = document.getElementById('katalogDropdownMenu');

        if (loginContainer && loginToggle && loginMenu) {
            loginToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const willOpen = !loginMenu.classList.contains('is-active');

                // Close other dropdown
                if (dropdownMenu) dropdownMenu.classList.remove('is-active');
                if (dropdownContainer) dropdownContainer.classList.remove('is-open');

                if (willOpen) {
                    loginMenu.classList.add('is-active');
                    loginContainer.classList.add('is-open');
                } else {
                    loginMenu.classList.remove('is-active');
                    loginContainer.classList.remove('is-open');
                }
            });
        }

        if (dropdownContainer && dropdownToggle && dropdownMenu) {
            dropdownToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const willOpen = !dropdownMenu.classList.contains('is-active');

                // Close other dropdown
                if (loginMenu) loginMenu.classList.remove('is-active');
                if (loginContainer) loginContainer.classList.remove('is-open');

                if (willOpen) {
                    dropdownMenu.classList.add('is-active');
                    dropdownContainer.classList.add('is-open');
                } else {
                    dropdownMenu.classList.remove('is-active');
                    dropdownContainer.classList.remove('is-open');
                }
            });
        }

        // Click outside closes the dropdowns
        document.addEventListener('click', function(e) {
            if (loginContainer && !loginContainer.contains(e.target)) {
                loginMenu.classList.remove('is-active');
                loginContainer.classList.remove('is-open');
            }
            if (dropdownContainer && !dropdownContainer.contains(e.target)) {
                dropdownMenu.classList.remove('is-active');
                dropdownContainer.classList.remove('is-open');
            }
        });

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

<!-- Google Translate Element & Automatic Translation Logic -->
<div id="google_translate_element" style="display:none;" aria-hidden="true"></div>
<style>
    /* Complete suppression of Google Translate top banner, toolbar, and iframes */
    html {
        top: 0 !important;
        margin-top: 0 !important;
    }
    body {
        top: 0px !important;
        position: static !important;
        margin-top: 0px !important;
    }
    .goog-te-banner-frame,
    .goog-te-banner-frame.skiptranslate,
    iframe.goog-te-banner-frame,
    .VIpgJd-ZVi9od-ORHb-OEVmcb,
    .VIpgJd-ZVi9od-ORHb-OEVmcb-ti6hGc,
    .VIpgJd-ZVi9od-aZ2wEe-wOHMyf,
    .VIpgJd-ZVi9od-l4eHX-hSRGPd,
    iframe.VIpgJd-ZVi9od-ORHb-OEVmcb,
    body > .skiptranslate,
    body > iframe[src*="translate"],
    body > iframe[id*="goog"],
    #goog-gt-tt,
    .goog-tooltip,
    .goog-tooltip:hover,
    .goog-te-balloon-frame {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        width: 0 !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }
    .goog-text-highlight {
        background-color: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }
    #google_translate_element {
        display: none !important;
    }
    font > font {
        font-family: inherit !important;
        color: inherit !important;
    }
</style>

<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'id',
            includedLanguages: 'en,id',
            autoDisplay: false
        }, 'google_translate_element');
    }

    function setSiteLanguage(lang) {
        if (lang === 'en') {
            // Aktifkan terjemahan Inggris
            document.cookie = "googtrans=/id/en; path=/";
            document.cookie = "googtrans=/id/en; path=/; domain=" + window.location.hostname;
            document.cookie = "site_pref_lang=en; path=/; max-age=31536000";
            localStorage.setItem('site_pref_lang', 'en');
            window.location.href = "{{ url('/lang/en') }}";
        } else {
            // Kembali ke Bahasa Indonesia
            document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=" + window.location.hostname;
            document.cookie = "googtrans=/id/id; path=/";
            document.cookie = "googtrans=/id/id; path=/; domain=" + window.location.hostname;
            document.cookie = "site_pref_lang=id; path=/; max-age=31536000";
            localStorage.setItem('site_pref_lang', 'id');
            window.location.href = "{{ url('/lang/id') }}";
        }
    }

    (function() {
        var currentLocale = '{{ app()->getLocale() }}';

        // Sinkronkan cookie Google Translate dengan locale Laravel yang aktif
        if (currentLocale === 'en') {
            if (!document.cookie.includes('googtrans=/id/en')) {
                document.cookie = "googtrans=/id/en; path=/";
                document.cookie = "googtrans=/id/en; path=/; domain=" + window.location.hostname;
            }
            localStorage.setItem('site_pref_lang', 'en');
        } else {
            if (document.cookie.includes('googtrans=/id/en')) {
                document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=" + window.location.hostname;
                document.cookie = "googtrans=/id/id; path=/";
                document.cookie = "googtrans=/id/id; path=/; domain=" + window.location.hostname;
            }
            localStorage.setItem('site_pref_lang', 'id');
        }

        // Hilangkan toolbar/banner Google Translate yang mengganggu tampilan
        function killTranslateBanner() {
            if (document.body && document.body.style.top && document.body.style.top !== '0px') {
                document.body.style.top = '0px';
            }
            if (document.documentElement && document.documentElement.style.top && document.documentElement.style.top !== '0px') {
                document.documentElement.style.top = '0px';
            }
            const banners = document.querySelectorAll('.goog-te-banner-frame, .VIpgJd-ZVi9od-ORHb-OEVmcb, body > .skiptranslate, body > iframe');
            banners.forEach(function(el) {
                if (el.tagName === 'IFRAME' || el.classList.contains('skiptranslate') || el.classList.contains('VIpgJd-ZVi9od-ORHb-OEVmcb')) {
                    el.style.display = 'none';
                    el.style.visibility = 'hidden';
                    el.style.height = '0';
                    el.style.width = '0';
                    el.style.position = 'absolute';
                    el.style.top = '-9999px';
                }
            });
        }
        setInterval(killTranslateBanner, 100);
        window.addEventListener('load', killTranslateBanner);
    })();
</script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" async defer></script>
