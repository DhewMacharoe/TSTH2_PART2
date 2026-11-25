<footer id="kontak">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <div class="footer-logo">
                    <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo" style="filter: grayscale(1) invert(1) brightness(2); transform: scale(1.5); transform-origin: left center;">
                    <span style="font-size: 1.4rem; font-weight: 800; font-family: var(--font-heading); color: #ffffff;">TSTH2 Indonesia</span>
                </div>
                <p style="color: #cbd5e1; max-width: 320px; font-size: 0.925rem; line-height: 1.65; margin-bottom: 1rem;">
                    {{ $konten['footer_desc']->value ?? (app()->getLocale() === 'en' ? 'National research center for herbal and horticultural innovation in Indonesia under the Ministry of Higher Education, Science, and Technology in collaboration with Institut Teknologi Del.' : 'Pusat riset nasional inovasi herbal & hortikultura di Indonesia di bawah Kementerian Pendidikan Tinggi, Sains, dan Teknologi bekerjasama dengan Institut Teknologi Del.') }}
                </p>
                <div style="font-size: 0.8rem; color: #94a3b8; font-weight: 600;">
                    {{ __('site.footer_institution_sub') }}
                </div>
            </div>
            <div class="footer-col">
                <h4>{{ __('site.footer_nav_main') }}</h4>
                <ul>
                    <li><a href="{{ route('home') }}">{{ __('site.nav_home') }}</a></li>
                    <li><a href="{{ route('profil-public') }}">{{ __('site.nav_profile') }}</a></li>
                    <li><a href="{{ route('layanan') }}">{{ __('site.nav_services') }}</a></li>
                    <li><a href="{{ route('penelitian') }}">{{ __('site.nav_research') }}</a></li>
                    <li><a href="{{ route('berita') }}">{{ __('site.nav_news') }}</a></li>
                    <li><a href="{{ route('galeri') }}">{{ __('site.nav_gallery') }}</a></li>
                    <li><a href="https://katalog.tsth2.id">{{ __('site.nav_catalog') }}</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>{{ __('site.footer_secretariat') }}</h4>
                <ul style="color: #cbd5e1; font-size: 0.9rem;">
                    <li style="margin-bottom: 0.5rem; display: flex; gap: 0.5rem;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #9C7A26; flex-shrink: 0; margin-top: 0.1rem;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>{{ $konten['footer_address']->value ?? 'Pollung, Humbang Hasundutan, Sumatera Utara, Indonesia' }}</span>
                    </li>
                    <li style="display: flex; gap: 0.5rem;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #9C7A26; flex-shrink: 0;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <span>{{ $konten['footer_email']->value ?? 'info@tsth2.id' }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} TSTH2 ({{ app()->getLocale() === 'en' ? 'Indonesian Herbal & Horticulture Science and Technology Park' : 'Taman Sains Teknologi Herbal dan Hortikultura Indonesia' }}). {{ __('site.footer_rights_reserved') }}</p>
        </div>
    </div>
</footer>
<script src="{{ asset('js/lightbox.js') }}"></script>
