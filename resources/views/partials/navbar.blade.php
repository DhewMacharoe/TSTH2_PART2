@php
    $urlPengunjung = rtrim(config('app.url_pengunjung', env('URL_PENGUNJUNG', 'http://localhost:8000')), '/');
    $urlKatalog    = rtrim(config('app.url_katalog',    env('URL_KATALOG',    'http://localhost:8003')), '/');
    $headerStyle   = 'background: #ffffff; border-bottom: 1px solid rgba(22, 78, 63, 0.1); box-shadow: 0 4px 20px rgba(0,0,0,0.04);';
    $textColor     = 'color: #0f172a;';
    $logoColor     = 'color: #164E3F;';
    $currentLocale = app()->getLocale();
@endphp

<style>
    /* Prevent overflow-x and guarantee scrollbar stability so navbar never shifts */
    html {
        overflow-y: scroll;
    }
    html, body {
        overflow-x: clip !important;
    }

    /* Material Symbols */
    .material-symbols-rounded { font-family: 'Material Symbols Rounded'; font-style: normal; font-weight: normal; font-size: 20px; line-height: 1; letter-spacing: normal; text-transform: none; display: inline-block; white-space: nowrap; word-wrap: normal; direction: ltr; font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }

    .main-header {
        position: sticky !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        z-index: 1000 !important;
        padding: 0.9rem 0 !important;
        background: rgba(255, 255, 255, 0.98) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
        transition: all 0.3s ease;
        box-sizing: border-box !important;
        font-family: 'Inter', sans-serif !important;
        font-size: 16px !important;
        line-height: 26.4px !important;
    }

    .header-container {
        width: 100% !important;
        max-width: 1280px !important;
        margin: 0 auto !important;
        padding: 0 1.5rem !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        box-sizing: border-box !important;
        height: 46px !important;
    }

    .logo-container {
        display: flex !important;
        align-items: center !important;
        gap: 0.75rem !important;
        text-decoration: none !important;
        min-width: 0 !important;
        height: 46px !important;
    }
    .logo-container img {
        height: 46px !important;
        width: auto !important;
        object-fit: contain !important;
        flex-shrink: 0 !important;
    }
    .logo-text {
        font-family: 'Outfit', sans-serif !important;
        font-size: 1.35rem !important;
        line-height: 1.1 !important;
        font-weight: 800 !important;
        letter-spacing: -0.02em !important;
        color: #164E3F !important;
    }
    .logo-subtext {
        color: #475569 !important;
        font-family: 'Inter', sans-serif !important;
        font-size: 0.72rem !important;
        font-weight: 600 !important;
        line-height: 1.2 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.4px !important;
        display: block !important;
    }

    /* Desktop Navigation Menu (Persis Port 8000) */
    .nav-menu {
        display: flex !important;
        align-items: center !important;
        gap: 0.25rem !important;
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
    }
    .nav-link {
        color: #0f172a !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
        line-height: 23.1px !important;
        position: relative !important;
        padding: 0.5rem 0.85rem !important;
        border-radius: 8px !important;
        transition: all 0.25s ease !important;
        letter-spacing: 0.3px !important;
        text-decoration: none !important;
        text-transform: uppercase !important;
        box-sizing: border-box !important;
        display: inline-flex !important;
        align-items: center !important;
        height: 39.1px !important;
    }
    .nav-link::after {
        content: '' !important;
        position: absolute !important;
        bottom: 4px !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        width: 0% !important;
        height: 2px !important;
        background: #164E3F !important;
        border-radius: 4px !important;
        transition: width 0.3s ease !important;
    }
    .nav-link:hover, .nav-link.active {
        color: #164E3F !important;
        background: rgba(22, 78, 63, 0.06) !important;
    }
    .nav-link:hover::after, .nav-link.active::after {
        width: 60% !important;
    }

    /* Clean Trigger Dropdown Button (Katalog) — Ukuran & Padding Identik Persis Port 8000 */
    .nav-dropdown {
        position: relative !important;
        display: inline-block !important;
    }
    .nav-dropdown-btn-clean {
        background: none !important;
        border: none !important;
        cursor: pointer !important;
        font-family: 'Outfit', sans-serif !important;
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        line-height: 23.1px !important;
        letter-spacing: inherit !important;
        text-transform: uppercase !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        padding: 0 !important;
        margin: 0 !important;
        position: relative !important;
        color: #0f172a !important;
        transition: color 0.2s !important;
        box-sizing: border-box !important;
        height: 23.1px !important;
    }
    .nav-dropdown-btn-clean::after {
        content: '' !important;
        position: absolute !important;
        bottom: -4px !important;
        left: 0 !important;
        width: 0% !important;
        height: 2px !important;
        background-color: #164E3F !important;
        transition: width 0.3s !important;
    }
    .nav-dropdown-btn-clean:hover::after,
    .nav-dropdown.is-open .nav-dropdown-btn-clean::after,
    .nav-dropdown-btn-clean.active::after {
        width: 100% !important;
    }
    .nav-dropdown-btn-clean:hover,
    .nav-dropdown.is-open .nav-dropdown-btn-clean,
    .nav-dropdown-btn-clean.active {
        color: #164E3F !important;
    }
    .dropdown-arrow-icon {
        transition: transform 0.25s ease !important;
        display: inline-block !important;
        vertical-align: middle !important;
    }
    .nav-dropdown.is-open .dropdown-arrow-icon {
        transform: rotate(180deg) !important;
    }

    /* Dropdown Menu Box */
    .tsth2-dropdown-menu {
        position: absolute !important;
        top: calc(100% + 8px) !important;
        left: 0 !important;
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 12px !important;
        box-shadow: 0 12px 28px -4px rgba(15, 23, 42, 0.15), 0 4px 10px -2px rgba(15, 23, 42, 0.08) !important;
        padding: 6px 0 !important;
        z-index: 1050 !important;
        display: none !important;
        min-width: 250px !important;
        box-sizing: border-box !important;
    }
    .tsth2-dropdown-menu.is-active {
        display: block !important;
    }
    .dropdown-plain-item {
        display: block !important;
        padding: 13px 20px !important;
        font-family: 'Outfit', sans-serif !important;
        font-size: 0.95rem !important;
        font-weight: 600 !important;
        color: #1e293b !important;
        text-decoration: none !important;
        transition: background-color 0.15s ease, color 0.15s ease !important;
        line-height: 1.4 !important;
        cursor: pointer !important;
        background: #ffffff !important;
    }
    .dropdown-plain-item:not(:last-child) {
        border-bottom: 1px solid #f1f5f9 !important;
    }
    .dropdown-plain-item:hover,
    .dropdown-plain-item:focus {
        background-color: #f1f5f9 !important;
        color: #164E3F !important;
    }

    /* Actions Desktop Wrapper */
    .nav-actions-desktop {
        display: flex !important;
        gap: 0.75rem !important;
        align-items: center !important;
    }

    .lang-switcher {
        display: inline-flex !important;
        align-items: center !important;
        background: #f1f5f9 !important;
        border-radius: 999px !important;
        padding: 3px !important;
        border: 1px solid #e2e8f0 !important;
        box-sizing: border-box !important;
        width: 82.1px !important;
        height: 35px !important;
    }
    .lang-switcher button {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 4px !important;
        padding: 4px 10px !important;
        border-radius: 999px !important;
        border: none !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
        font-family: Arial, sans-serif !important;
        font-size: 13.3333px !important;
        line-height: normal !important;
        box-sizing: border-box !important;
    }
    #lang-btn-en {
        width: 38.53px !important;
        height: 23px !important;
    }
    #lang-btn-id {
        width: 35.56px !important;
        height: 27px !important;
    }

    /* Portal Login Button — Ukuran, Font, dan Padding Persis Port 8000 */
    .btn-portal-trigger {
        background: #164E3F !important;
        color: #ffffff !important;
        font-family: 'Inter', sans-serif !important;
        font-size: 0.875rem !important;
        font-weight: 700 !important;
        line-height: 23.1px !important;
        letter-spacing: normal !important;
        padding: 0.6rem 1.35rem !important;
        border-radius: 999px !important;
        border: none !important;
        box-shadow: 0 4px 12px rgba(22, 78, 63, 0.25) !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        text-decoration: none !important;
        box-sizing: border-box !important;
        width: 148px !important;
        height: 42.3px !important;
    }
    .btn-portal-trigger:hover {
        background: #0d382d !important;
        box-shadow: 0 6px 16px rgba(22, 78, 63, 0.35) !important;
    }
    .btn-portal-trigger span {
        font-family: 'Inter', sans-serif !important;
        font-size: 0.875rem !important;
        font-weight: 700 !important;
        line-height: 23.1px !important;
        width: 81.8px !important;
    }
    .btn-portal-trigger svg {
        width: 15px !important;
        height: 15px !important;
        display: block !important;
        flex-shrink: 0 !important;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .nav-menu, .nav-actions-desktop {
            display: none !important;
        }
        .mobile-menu-toggle {
            display: flex !important;
        }
        .logo-subtext {
            display: none !important;
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
        box-sizing: border-box;
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
    .mobile-nav-links {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        margin-bottom: 2rem;
    }
    .mobile-nav-links a {
        font-family: 'Outfit', sans-serif;
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
        {{-- Logo TSTH2 --}}
        <a href="{{ $urlPengunjung }}" class="logo-container">
            <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo" onerror="this.src='{{ $urlPengunjung }}/assets/logo.png'">
            <div style="display: flex; flex-direction: column; justify-content: center; min-width: 0;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="logo-text" style="{{ $logoColor }}; font-size: 1.35rem; line-height: 1.1; font-weight: 800; letter-spacing: -0.02em;">TSTH2</span>
                </div>
                <span class="logo-subtext">Taman Sains Teknologi Herbal dan Hortikultura</span>
            </div>
        </a>
        
        {{-- Desktop Navigation (Persis Port 8000) --}}
        <nav class="nav-menu" style="text-transform: uppercase;">
            <a href="{{ $urlPengunjung }}" class="nav-link" style="{{ $textColor }}">{{ __('site.nav_home') }}</a>
            <a href="{{ $urlPengunjung }}/profil-tsth2" class="nav-link" style="{{ $textColor }}">{{ __('site.nav_profile') }}</a>
            <a href="{{ $urlPengunjung }}/penelitian" class="nav-link" style="{{ $textColor }}">{{ __('site.nav_research') }}</a>
            <a href="{{ $urlPengunjung }}/berita" class="nav-link" style="{{ $textColor }}">{{ __('site.nav_news') }}</a>
            <a href="{{ $urlPengunjung }}/galeri" class="nav-link" style="{{ $textColor }}">{{ __('site.nav_gallery') }}</a>
            
            <div class="nav-dropdown" style="position: relative; display: inline-block;">
                <button type="button" class="nav-dropdown-btn-clean active" id="katalogDropdownBtn" style="{{ $textColor }}">
                    <span>{{ __('site.nav_catalog') }}</span>
                    <svg class="dropdown-arrow-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div class="tsth2-dropdown-menu" id="katalogDropdownMenu" style="left: 0; min-width: 220px;">
                    <a href="{{ route('home') }}" class="dropdown-plain-item {{ request()->routeIs('home') ? 'style=color:#164E3F;font-weight:700;' : '' }}">
                        {{ __('site.nav_greenhouse') }}
                    </a>
                    <a href="{{ route('collection') }}" class="dropdown-plain-item {{ request()->routeIs('collection') ? 'style=color:#164E3F;font-weight:700;' : '' }}">
                        {{ __('site.nav_outdoor') }}
                    </a>
                </div>
            </div>
        </nav>

        {{-- Desktop Actions (Persis Port 8000) --}}
        <div class="nav-actions-desktop" style="display: flex; gap: 0.75rem; align-items: center;">
            <!-- Language Switcher Pill -->
            <div class="lang-switcher notranslate" translate="no" style="display: inline-flex; align-items: center; background: #f1f5f9; border-radius: 999px; padding: 3px; border: 1px solid #e2e8f0; font-size: 0.75rem; font-weight: 700;">
                <button type="button" onclick="setSiteLanguage('en')" id="lang-btn-en" title="English" style="display: flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; border: none; cursor: pointer; transition: all 0.2s; font-family: 'Outfit', sans-serif; font-size: 0.75rem; {{ $currentLocale === 'en' ? 'background: #164E3F; color: #ffffff; box-shadow: 0 2px 6px rgba(22,78,63,0.3); font-weight: 800;' : 'background: transparent; color: #64748b; font-weight: 600;' }}">
                    <span>EN</span>
                </button>
                <button type="button" onclick="setSiteLanguage('id')" id="lang-btn-id" title="Bahasa Indonesia" style="display: flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; border: none; cursor: pointer; transition: all 0.2s; font-family: 'Outfit', sans-serif; font-size: 0.75rem; {{ $currentLocale === 'id' ? 'background: #164E3F; color: #ffffff; box-shadow: 0 2px 6px rgba(22,78,63,0.3); font-weight: 800;' : 'background: transparent; color: #64748b; font-weight: 600;' }}">
                    <span>ID</span>
                </button>
            </div>

            {{-- Portal Login (Tombol Hijau Bulat Identik Port 8000) --}}
            <a href="{{ $urlPengunjung }}/login" class="btn-portal-trigger" style="text-decoration: none;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                <span>{{ __('site.nav_portal_login') }}</span>
            </a>
        </div>

        <!-- Hamburger Button for Mobile & Smartwatch -->
        <button class="mobile-menu-toggle" id="mobileMenuBtn" aria-label="Toggle Menu" style="background: none; border: none; cursor: pointer; display: none; {{ $textColor }}; padding: 4px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
    </div>
</header>

<!-- Mobile Menu Overlay Drawer (Identik Port 8000) -->
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
            <a href="{{ $urlPengunjung }}">{{ __('site.nav_home') }}</a>
            <a href="{{ $urlPengunjung }}/profil-tsth2">{{ __('site.nav_profile') }}</a>
            <a href="{{ $urlPengunjung }}/penelitian">{{ __('site.nav_research') }}</a>
            <a href="{{ $urlPengunjung }}/berita">{{ __('site.nav_news') }}</a>
            <a href="{{ $urlPengunjung }}/galeri">{{ __('site.nav_gallery') }}</a>
            
            <div style="border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 0.5rem;">
                <button type="button" id="mobileKatalogBtn" style="background: none; border: none; width: 100%; text-align: left; font-family: 'Outfit', sans-serif; font-size: 1.05rem; font-weight: 600; color: #1e293b; text-transform: uppercase; padding: 0.65rem 0.5rem; cursor: pointer; display: flex; justify-content: space-between; align-items: center; border-radius: 8px;">
                    {{ __('site.nav_catalog') }}
                    <svg id="mobileKatalogArrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.2s;"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div id="mobileKatalogSubmenu" style="display: none; flex-direction: column; gap: 0.5rem; padding-left: 1rem; margin-top: 0.25rem;">
                    <a href="{{ route('home') }}" style="font-size: 0.95rem; font-weight: 500; text-decoration: none; color: #64748b;">{{ __('site.nav_greenhouse') }}</a>
                    <a href="{{ route('collection') }}" style="font-size: 0.95rem; font-weight: 500; text-decoration: none; color: #64748b;">{{ __('site.nav_outdoor') }}</a>
                </div>
            </div>
        </nav>
        
        <div class="mobile-nav-actions" style="margin-top: auto; padding-top: 1rem; border-top: 1px solid #f1f5f9;">
            <a href="{{ $urlPengunjung }}/login" class="btn btn-primary" style="width: 100%; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px; box-sizing: border-box; font-size: 0.95rem; padding: 0.75rem 1rem; font-weight: 700; text-decoration: none; background: #164E3F; color: white; border-radius: 999px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                <span>{{ __('site.nav_portal_login') }}</span>
            </a>
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

        // Desktop Katalog Dropdown (STRICT CLICK TO OPEN & STAY OPEN)
        const dropdownContainer = document.querySelector('.nav-dropdown');
        const dropdownToggle = document.getElementById('katalogDropdownBtn');
        const dropdownMenu = document.getElementById('katalogDropdownMenu');

        if (dropdownContainer && dropdownToggle && dropdownMenu) {
            dropdownToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const willOpen = !dropdownMenu.classList.contains('is-active');

                if (willOpen) {
                    dropdownMenu.classList.add('is-active');
                    dropdownContainer.classList.add('is-open');
                } else {
                    dropdownMenu.classList.remove('is-active');
                    dropdownContainer.classList.remove('is-open');
                }
            });
        }

        // Click outside closes the dropdown
        document.addEventListener('click', function(e) {
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
            document.cookie = "googtrans=/id/en; path=/";
            document.cookie = "googtrans=/id/en; path=/; domain=" + window.location.hostname;
            document.cookie = "site_pref_lang=en; path=/; max-age=31536000";
            localStorage.setItem('site_pref_lang', 'en');
            window.location.href = "{{ route('lang.switch', 'en') }}";
        } else {
            document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=" + window.location.hostname;
            document.cookie = "googtrans=/id/id; path=/";
            document.cookie = "googtrans=/id/id; path=/; domain=" + window.location.hostname;
            document.cookie = "site_pref_lang=id; path=/; max-age=31536000";
            localStorage.setItem('site_pref_lang', 'id');
            window.location.href = "{{ route('lang.switch', 'id') }}";
        }
    }

    (function() {
        var currentLocale = '{{ app()->getLocale() }}';

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
            }
            localStorage.setItem('site_pref_lang', 'id');
        }

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
