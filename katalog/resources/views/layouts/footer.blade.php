@php
    $urlPengunjung = rtrim(config('app.url_pengunjung', env('URL_PENGUNJUNG', 'http://localhost:8000')), '/');
    $urlKatalog    = rtrim(config('app.url_katalog',    env('URL_KATALOG',    'http://localhost:8003')), '/');
@endphp

<style>
    /* ═══════════════════════════════════════════════
       TSTH2 FOOTER — Identik Persis Port 8000
       ═══════════════════════════════════════════════ */
    footer.tsth2-public-footer {
        background: linear-gradient(135deg, #0b2820 0%, #0d342a 60%, #0a2218 100%) !important;
        color: #ffffff !important;
        padding: 5rem 0 2.5rem !important;
        border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
        position: relative !important;
        overflow: hidden !important;
        font-family: 'Inter', sans-serif !important;
        line-height: 1.65 !important;
        box-sizing: border-box !important;
    }
    footer.tsth2-public-footer *,
    footer.tsth2-public-footer *::before,
    footer.tsth2-public-footer *::after {
        box-sizing: border-box !important;
    }
    footer.tsth2-public-footer::before {
        content: '' !important;
        position: absolute !important;
        top: -80px !important;
        right: -80px !important;
        width: 350px !important;
        height: 350px !important;
        border-radius: 50% !important;
        background: radial-gradient(circle, rgba(156, 122, 38, 0.08) 0%, transparent 70%) !important;
        pointer-events: none !important;
    }
    footer.tsth2-public-footer .footer-inner {
        max-width: 1280px !important;
        margin: 0 auto !important;
        padding: 0 1.5rem !important;
        position: relative !important;
        z-index: 1 !important;
    }
    footer.tsth2-public-footer .footer-grid {
        display: grid !important;
        grid-template-columns: 2fr 1fr 1fr !important;
        gap: 3.5rem !important;
        margin-bottom: 3.5rem !important;
    }
    footer.tsth2-public-footer .footer-logo {
        display: flex !important;
        align-items: center !important;
        gap: 1rem !important;
        margin-bottom: 1.35rem !important;
    }
    footer.tsth2-public-footer .footer-logo img {
        height: 48px !important;
        width: auto !important;
        filter: brightness(0) invert(1) !important;
        opacity: 0.9 !important;
    }
    footer.tsth2-public-footer .footer-brand-title {
        font-size: 1.4rem !important;
        font-weight: 800 !important;
        font-family: 'Outfit', sans-serif !important;
        color: #ffffff !important;
    }
    footer.tsth2-public-footer .footer-desc {
        color: #cbd5e1 !important;
        max-width: 340px !important;
        font-size: 0.925rem !important;
        line-height: 1.65 !important;
        margin-bottom: 1rem !important;
        font-family: 'Inter', 'DM Sans', sans-serif !important;
    }
    footer.tsth2-public-footer .footer-institution {
        font-size: 0.8rem !important;
        color: #94a3b8 !important;
        font-weight: 600 !important;
    }
    footer.tsth2-public-footer .footer-col h4 {
        font-size: 1rem !important;
        margin-bottom: 1.35rem !important;
        color: #9C7A26 !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        position: relative !important;
        padding-bottom: 0.75rem !important;
    }
    footer.tsth2-public-footer .footer-col h4::after {
        content: '' !important;
        position: absolute !important;
        bottom: 0 !important;
        left: 0 !important;
        width: 32px !important;
        height: 2px !important;
        background: linear-gradient(90deg, #9C7A26, transparent) !important;
        border-radius: 2px !important;
    }
    footer.tsth2-public-footer .footer-col ul {
        list-style: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    footer.tsth2-public-footer .footer-col ul li {
        margin-bottom: 0.85rem !important;
    }
    footer.tsth2-public-footer .footer-col ul li a {
        color: rgba(255, 255, 255, 0.65) !important;
        transition: all 0.2s ease !important;
        font-size: 0.9rem !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.4rem !important;
        text-decoration: none !important;
        font-family: 'Outfit', sans-serif !important;
    }
    footer.tsth2-public-footer .footer-col ul li a::before {
        display: none !important;
        content: none !important;
    }
    footer.tsth2-public-footer .footer-col ul li a:hover {
        color: #ffffff !important;
        transform: translateX(4px) !important;
    }
    footer.tsth2-public-footer .footer-bottom {
        text-align: center !important;
        padding-top: 2.5rem !important;
        border-top: 1px solid rgba(255, 255, 255, 0.07) !important;
        color: rgba(255, 255, 255, 0.4) !important;
        font-size: 0.85rem !important;
        font-family: 'Inter', sans-serif !important;
    }

    @media (max-width: 900px) {
        footer.tsth2-public-footer .footer-grid {
            grid-template-columns: 1fr !important;
            gap: 2rem !important;
        }
    }
</style>

<footer class="tsth2-public-footer" id="kontak">
    <div class="footer-inner">
        <div class="footer-grid">
            <div class="footer-col">
                <div class="footer-logo">
                    <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo" onerror="this.src='{{ $urlPengunjung }}/assets/logo.png'">
                    <span class="footer-brand-title">TSTH2 Indonesia</span>
                </div>
                <p class="footer-desc">
                    {{ app()->getLocale() === 'en' ? 'National research center for herbal and horticultural innovation in Indonesia under the Ministry of Higher Education, Science, and Technology in collaboration with Institut Teknologi Del.' : 'Pusat riset nasional inovasi herbal & hortikultura di Indonesia di bawah Kementerian Pendidikan Tinggi, Sains, dan Teknologi bekerjasama dengan Institut Teknologi Del.' }}
                </p>
                <div class="footer-institution">
                    {{ __('site.footer_institution_sub') }}
                </div>
            </div>

            <div class="footer-col">
                <h4>{{ __('site.footer_nav_main') }}</h4>
                <ul>
                    <li><a href="{{ $urlPengunjung }}">{{ __('site.nav_home') }}</a></li>
                    <li><a href="{{ $urlPengunjung }}/profil-tsth2">{{ __('site.nav_profile') }}</a></li>
                    <li><a href="{{ $urlPengunjung }}/layanan">{{ __('site.nav_services') }}</a></li>
                    <li><a href="{{ $urlPengunjung }}/penelitian">{{ __('site.nav_research') }}</a></li>
                    <li><a href="{{ $urlPengunjung }}/berita">{{ __('site.nav_news') }}</a></li>
                    <li><a href="{{ $urlPengunjung }}/galeri">{{ __('site.nav_gallery') }}</a></li>
                    <li><a href="{{ route('collection') }}">{{ __('site.nav_catalog') }}</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>{{ __('site.footer_secretariat') }}</h4>
                <ul style="color: #cbd5e1; font-size: 0.9rem;">
                    <li style="margin-bottom: 0.65rem; display: flex; gap: 0.5rem; align-items: flex-start;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #9C7A26; flex-shrink: 0; margin-top: 0.15rem;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>Pollung, Humbang Hasundutan, Sumatera Utara, Indonesia</span>
                    </li>
                    <li style="display: flex; gap: 0.5rem; align-items: center;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #9C7A26; flex-shrink: 0;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <span>info@tsth2.id</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} TSTH2 ({{ app()->getLocale() === 'en' ? 'Indonesian Herbal & Horticulture Science and Technology Park' : 'Taman Sains Teknologi Herbal dan Hortikultura Indonesia' }}). {{ __('site.footer_rights_reserved') }}</p>
        </div>
    </div>
</footer>
