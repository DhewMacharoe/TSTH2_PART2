@php
    $urlPengunjung = rtrim(config('app.url_pengunjung', env('URL_PENGUNJUNG', 'http://localhost:8000')), '/');
    $urlKatalog    = rtrim(config('app.url_katalog',    env('URL_KATALOG',    'http://localhost:8003')), '/');
    $currentLocale = app()->getLocale();
@endphp

<style>
    /* Prevent overflow-x and guarantee scrollbar stability */
    html {
        overflow-y: scroll;
    }
    html, body {
        overflow-x: clip !important;
    }

    /* ═══════════════════════════════════════════════
       2-TIER NAVBAR SYSTEM (PERSIS SEPERTI GAMBAR)
       ═══════════════════════════════════════════════ */
    .tsth2-navbar-wrapper {
        position: sticky !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        z-index: 1000 !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }

    /* ── 1. TOP BAR (WARNA ATAS: NAVY BLUE / DEEP BLUE #004c8c) ── */
    .top-header-bar {
        background: #004c8c;
        color: #ffffff;
        font-size: 0.825rem;
        font-weight: 500;
        height: 38px;
        display: flex;
        align-items: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    }
    .top-header-container {
        width: 100%;
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-sizing: border-box;
    }
    .top-header-left {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.775rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .top-header-right {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-left: auto;
    }
    .top-nav-link {
        color: #ffffff;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.8rem;
        transition: opacity 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }
    .top-nav-link:hover {
        opacity: 0.85;
        text-decoration: underline;
    }
    .top-nav-divider {
        color: rgba(255, 255, 255, 0.35);
        font-size: 0.8rem;
    }

    /* Top Language Dropdown with Flag (Seperti di gambar) */
    .lang-top-dropdown {
        position: relative;
        display: inline-block;
    }
    .lang-top-btn {
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        line-height: 1;
    }
    .lang-top-btn:hover,
    .lang-top-dropdown.is-open .lang-top-btn {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.45);
    }
    .lang-top-caret {
        transition: transform 0.2s ease;
        stroke-width: 2.5;
    }
    .lang-top-dropdown.is-open .lang-top-caret {
        transform: rotate(180deg);
    }
    .lang-top-menu {
        position: absolute;
        top: calc(100% + 6px);
        right: 0;
        background: #ffffff;
        color: #1e293b;
        border-radius: 10px;
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.2), 0 4px 8px -2px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        padding: 5px;
        min-width: 175px;
        z-index: 1050;
        display: none;
        box-sizing: border-box;
    }
    .lang-top-dropdown.is-open .lang-top-menu {
        display: block !important;
        animation: dropFadeIn 0.2s ease-out;
    }
    .lang-top-item {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 8px 12px;
        background: none;
        border: none;
        border-radius: 6px;
        font-size: 0.825rem;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
        text-align: left;
        transition: background 0.15s, color 0.15s;
        box-sizing: border-box;
    }
    .lang-top-item:hover {
        background: #f1f5f9;
        color: #004c8c;
    }
    .lang-top-item.active {
        background: #e0f2fe;
        color: #0369a1;
        font-weight: 700;
    }

    /* ── 2. MAIN BAR (WARNA BAWAH: WHITE #ffffff) ── */
    .main-header {
        background: #ffffff;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        padding: 0.65rem 0;
        transition: all 0.3s ease;
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
        gap: 1.5rem;
        flex-wrap: nowrap !important;
    }

    /* Logo & Institutional Title */
    .logo-container {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        flex-shrink: 0;
    }
    .logo-container img {
        height: 44px;
        width: auto;
        object-fit: contain;
        flex-shrink: 0;
    }
    .logo-title-wrap {
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
    }
    .logo-text {
        color: #0f172a;
        font-size: 1.25rem;
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: -0.02em;
        font-family: var(--font-heading, sans-serif);
    }
    .logo-subtext {
        color: #64748b;
        font-size: 0.72rem;
        font-weight: 600;
        line-height: 1.2;
        letter-spacing: 0.2px;
        display: block;
        white-space: nowrap;
    }

    /* ── MENU NAVIGASI: TIDAK SEMUA HURUF KAPITAL & HARUS SEBARIS ── */
    .nav-menu {
        display: flex;
        align-items: center;
        gap: 1.35rem;
        flex-wrap: nowrap !important;
        margin: 0;
        padding: 0;
    }
    .nav-link {
        color: #1e293b;
        font-family: var(--font-heading, sans-serif);
        font-weight: 600;
        font-size: 0.925rem;
        text-transform: none !important;
        white-space: nowrap !important;
        text-decoration: none;
        padding: 0.45rem 0.25rem;
        position: relative;
        transition: color 0.2s ease;
        flex-shrink: 0;
    }
    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0%;
        height: 2.5px;
        background: #004c8c;
        border-radius: 99px;
        transition: width 0.25s ease;
    }
    .nav-link:hover::after,
    .nav-link.active::after {
        width: 100%;
    }
    .nav-link:hover,
    .nav-link.active {
        color: #004c8c !important;
    }

    /* Katalog dropdown */
    .nav-dropdown {
        position: relative;
        display: inline-flex;
        align-items: center;
        flex-shrink: 0;
    }
    .nav-dropdown-btn-clean {
        background: none;
        border: none;
        cursor: pointer;
        font-family: var(--font-heading, sans-serif);
        font-size: 0.925rem;
        font-weight: 600;
        text-transform: none !important;
        white-space: nowrap !important;
        color: #1e293b;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 0.45rem 0.25rem;
        position: relative;
        transition: color 0.2s ease;
        flex-shrink: 0;
    }
    .nav-dropdown-btn-clean::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0%;
        height: 2.5px;
        background: #004c8c;
        border-radius: 99px;
        transition: width 0.25s ease;
    }
    .nav-dropdown-btn-clean:hover::after,
    .nav-dropdown.is-open .nav-dropdown-btn-clean::after {
        width: 100%;
    }
    .nav-dropdown-btn-clean:hover,
    .nav-dropdown.is-open .nav-dropdown-btn-clean {
        color: #004c8c !important;
    }
    .dropdown-arrow-icon {
        transition: transform 0.25s ease;
    }
    .nav-dropdown.is-open .dropdown-arrow-icon {
        transform: rotate(180deg);
    }

    .tsth2-dropdown-menu {
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        box-shadow: 0 12px 28px -4px rgba(15, 23, 42, 0.15), 0 4px 10px -2px rgba(15, 23, 42, 0.08);
        padding: 6px 0;
        z-index: 1050;
        display: none;
        min-width: 220px;
        box-sizing: border-box;
    }
    .tsth2-dropdown-menu.is-active {
        display: block !important;
        animation: dropFadeIn 0.2s ease-out;
    }
    .dropdown-plain-item {
        display: block;
        padding: 11px 18px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #1e293b;
        text-decoration: none;
        transition: background-color 0.15s ease, color 0.15s ease;
        text-transform: none;
        white-space: nowrap;
    }
    .dropdown-plain-item:not(:last-child) {
        border-bottom: 1px solid #f1f5f9;
    }
    .dropdown-plain-item:hover {
        background-color: #f0f9ff;
        color: #004c8c;
    }

    /* Action button di kanan (Pill button seperti tombol Register di gambar ITB) */
    .nav-actions-desktop {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-shrink: 0;
    }
    .btn-register-pill {
        background: #0095da !important;
        color: #ffffff !important;
        font-size: 0.9rem !important;
        font-weight: 700 !important;
        padding: 0.55rem 1.4rem !important;
        border-radius: 9999px !important;
        border: none !important;
        box-shadow: 0 3px 10px rgba(0, 149, 218, 0.3) !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        text-decoration: none !important;
        white-space: nowrap !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
    }
    .btn-register-pill:hover {
        background: #0081be !important;
        box-shadow: 0 5px 14px rgba(0, 149, 218, 0.4) !important;
        transform: translateY(-1px);
    }

    @keyframes dropFadeIn {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .mobile-menu-toggle {
        display: none;
        background: none;
        border: none;
        cursor: pointer;
        color: #1e293b;
        padding: 6px;
    }

    @media (max-width: 1024px) {
        .top-header-left {
            display: none !important;
        }
        .nav-menu {
            display: none !important;
        }
        .nav-actions-desktop {
            display: none !important;
        }
        .mobile-menu-toggle {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }
    }

    @media (max-width: 640px) {
        .top-header-bar {
            height: 34px;
            font-size: 0.75rem;
        }
        .logo-container img {
            height: 36px;
        }
        .logo-text {
            font-size: 1.1rem !important;
        }
        .logo-subtext {
            display: none !important;
        }
    }
</style>

<!-- ═══════════════════════════════════════════════════════════════
     HEADER 2-WARNA (TOP BAR BIRU TUA + MAIN BAR PUTIH)
     ═══════════════════════════════════════════════════════════════ -->
<div class="tsth2-navbar-wrapper">
    <!-- 1. TOP BAR (WARNA ATAS) -->
    <div class="top-header-bar">
        <div class="top-header-container">
            <div class="top-header-left">
                <span>Taman Sains Teknologi Herbal &amp; Hortikultura Indonesia &bull; Kemenristek &amp; IT Del</span>
            </div>
            
            <div class="top-header-right">
                <a href="{{ $urlPengunjung }}#kontak" class="top-nav-link">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>Hubungi Kami</span>
                </a>
                
                <span class="top-nav-divider">|</span>

                <!-- Language Switcher dengan Bendera & Dropdown (Persis seperti gambar ITB) -->
                <div class="lang-top-dropdown notranslate" translate="no" id="langTopDropdown">
                    <button type="button" class="lang-top-btn" id="langTopBtn" aria-label="Pilih Bahasa / Select Language">
                        @if($currentLocale === 'en')
                            <!-- Bendera UK SVG -->
                            <svg class="flag-icon" width="18" height="13" viewBox="0 0 60 30" xmlns="http://www.w3.org/2000/svg" style="border-radius: 2px; box-shadow: 0 0 1px rgba(0,0,0,0.5); flex-shrink: 0;">
                                <clipPath id="uk-flag-top-k"><path d="M0,0 v30 h60 v-30 z"/></clipPath>
                                <g clip-path="url(#uk-flag-top-k)">
                                    <path d="M0,0 v30 h60 v-30 z" fill="#012169"/>
                                    <path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/>
                                    <path d="M0,0 L60,30 M60,0 L0,30" stroke="#C8102E" stroke-width="4"/>
                                    <path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/>
                                    <path d="M30,0 v30 M0,15 h60" stroke="#C8102E" stroke-width="6"/>
                                </g>
                            </svg>
                        @else
                            <!-- Bendera Indonesia SVG -->
                            <svg class="flag-icon" width="18" height="13" viewBox="0 0 20 15" fill="none" xmlns="http://www.w3.org/2000/svg" style="border-radius: 2px; box-shadow: 0 0 1px rgba(0,0,0,0.5); flex-shrink: 0;">
                                <rect width="20" height="7.5" fill="#e11d48"/>
                                <rect y="7.5" width="20" height="7.5" fill="#ffffff"/>
                            </svg>
                        @endif
                        <svg class="lang-top-caret" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m6 9 6 6 6-6"/></svg>
                    </button>

                    <div class="lang-top-menu" id="langTopMenu">
                        <button type="button" onclick="setSiteLanguage('id')" class="lang-top-item {{ $currentLocale !== 'en' ? 'active' : '' }}">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <svg width="18" height="13" viewBox="0 0 20 15" fill="none" xmlns="http://www.w3.org/2000/svg" style="border-radius: 2px; box-shadow: 0 0 1px rgba(0,0,0,0.4);">
                                    <rect width="20" height="7.5" fill="#e11d48"/>
                                    <rect y="7.5" width="20" height="7.5" fill="#ffffff"/>
                                </svg>
                                <span>Bahasa Indonesia</span>
                            </div>
                            @if($currentLocale !== 'en')
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0369a1" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            @endif
                        </button>

                        <button type="button" onclick="setSiteLanguage('en')" class="lang-top-item {{ $currentLocale === 'en' ? 'active' : '' }}">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <svg width="18" height="13" viewBox="0 0 60 30" xmlns="http://www.w3.org/2000/svg" style="border-radius: 2px; box-shadow: 0 0 1px rgba(0,0,0,0.4);">
                                    <clipPath id="uk-flag-menu-k"><path d="M0,0 v30 h60 v-30 z"/></clipPath>
                                    <g clip-path="url(#uk-flag-menu-k)">
                                        <path d="M0,0 v30 h60 v-30 z" fill="#012169"/>
                                        <path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/>
                                        <path d="M0,0 L60,30 M60,0 L0,30" stroke="#C8102E" stroke-width="4"/>
                                        <path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/>
                                        <path d="M30,0 v30 M0,15 h60" stroke="#C8102E" stroke-width="6"/>
                                    </g>
                                </svg>
                                <span>English</span>
                            </div>
                            @if($currentLocale === 'en')
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0369a1" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. MAIN BAR (WARNA BAWAH: PUTIH) -->
    <header class="main-header" id="header">
        <div class="header-container">
            <!-- Brand Logo -->
            <a href="{{ $urlPengunjung }}" class="logo-container">
                <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo" onerror="this.src='{{ $urlPengunjung }}/assets/logo.png'">
                <div class="logo-title-wrap">
                    <span class="logo-text">TSTH2</span>
                    <span class="logo-subtext">Taman Sains Teknologi Herbal dan Hortikultura</span>
                </div>
            </a>
            
            <!-- Menu Navigasi: Sebaris & Bukan Huruf Kapital Semua -->
            <nav class="nav-menu">
                <a href="{{ $urlPengunjung }}" class="nav-link">{{ __('site.nav_home') }}</a>
                <a href="{{ $urlPengunjung }}/profil-tsth2" class="nav-link">{{ __('site.nav_profile') }}</a>
                <a href="{{ $urlPengunjung }}/penelitian" class="nav-link">{{ __('site.nav_research') }}</a>
                <a href="{{ $urlPengunjung }}/berita" class="nav-link">{{ __('site.nav_news') }}</a>
                <a href="{{ $urlPengunjung }}/galeri" class="nav-link">{{ __('site.nav_gallery') }}</a>
                
                <!-- Dropdown Katalog -->
                <div class="nav-dropdown">
                    <button type="button" class="nav-dropdown-btn-clean" id="katalogDropdownBtn">
                        <span>{{ __('site.nav_catalog') }}</span>
                        <svg class="dropdown-arrow-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div class="tsth2-dropdown-menu" id="katalogDropdownMenu">
                        <a href="{{ route('home') }}" class="dropdown-plain-item {{ request()->routeIs('home') ? 'style=color:#004c8c;font-weight:700;' : '' }}">
                            {{ __('site.nav_greenhouse') }}
                        </a>
                        <a href="{{ route('collection') }}" class="dropdown-plain-item {{ request()->routeIs('collection') ? 'style=color:#004c8c;font-weight:700;' : '' }}">
                            {{ __('site.nav_outdoor') }}
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Action Button di Kanan (Pill Button seperti tombol Register di gambar ITB) -->
            <div class="nav-actions-desktop">
                <a href="{{ $urlPengunjung }}/login" class="btn-register-pill">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    <span>{{ __('site.nav_portal_login') }}</span>
                </a>
            </div>

            <!-- Hamburger Button for Mobile -->
            <button class="mobile-menu-toggle" id="mobileMenuBtn" aria-label="Toggle Menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
        </div>
    </header>
</div>

<!-- ═══════════════════════════════════════════════════════════════
     SCRIPT: INTERAKSI DROPDOWN & TRANSLATE ENGINE
     ═══════════════════════════════════════════════════════════════ -->
<script>
    function setSiteLanguage(lang) {
        const expires = new Date(Date.now() + 365 * 864e5).toUTCString();
        const isHttps = window.location.protocol === 'https:' ? '; Secure' : '';
        const host = window.location.hostname;
        
        document.cookie = "site_pref_lang=" + lang + "; expires=" + expires + "; path=/; SameSite=Lax" + isHttps;
        
        if (lang === 'en') {
            document.cookie = "googtrans=/id/en; expires=" + expires + "; path=/; SameSite=Lax" + isHttps;
            if (host.includes('.')) {
                const parts = host.split('.');
                if (parts.length > 2) {
                    const rootDomain = parts.slice(-2).join('.');
                    document.cookie = "googtrans=/id/en; expires=" + expires + "; path=/; domain=." + rootDomain + "; SameSite=Lax" + isHttps;
                }
            }
        } else {
            document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "googtrans=/id/id; expires=" + expires + "; path=/; SameSite=Lax" + isHttps;
        }
        
        localStorage.setItem('site_pref_lang', lang);
        window.location.href = "{{ url('/lang') }}/" + lang;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const langTopDropdown = document.getElementById('langTopDropdown');
        const langTopBtn = document.getElementById('langTopBtn');
        
        if (langTopBtn && langTopDropdown) {
            langTopBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                langTopDropdown.classList.toggle('is-open');
                if (katalogMenu) katalogMenu.classList.remove('is-active');
                if (katalogContainer) katalogContainer.classList.remove('is-open');
            });
        }

        const katalogContainer = document.querySelector('.nav-dropdown');
        const katalogBtn = document.getElementById('katalogDropdownBtn');
        const katalogMenu = document.getElementById('katalogDropdownMenu');

        if (katalogContainer && katalogBtn && katalogMenu) {
            katalogBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const willOpen = !katalogMenu.classList.contains('is-active');
                if (langTopDropdown) langTopDropdown.classList.remove('is-open');

                if (willOpen) {
                    katalogMenu.classList.add('is-active');
                    katalogContainer.classList.add('is-open');
                } else {
                    katalogMenu.classList.remove('is-active');
                    katalogContainer.classList.remove('is-open');
                }
            });
        }

        document.addEventListener('click', function(e) {
            if (langTopDropdown && !langTopDropdown.contains(e.target)) {
                langTopDropdown.classList.remove('is-open');
            }
            if (katalogContainer && !katalogContainer.contains(e.target)) {
                katalogMenu.classList.remove('is-active');
                katalogContainer.classList.remove('is-open');
            }
        });
    });
</script>
