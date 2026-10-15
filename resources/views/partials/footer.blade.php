<footer>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <div class="footer-logo">
                    <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo" style="filter: grayscale(1) invert(1) brightness(2); transform: scale(1.5); transform-origin: left center;">
                    <span style="font-size: 1.4rem; font-weight: 800; font-family: var(--font-heading); color: #ffffff;">TSTH2 Science Park</span>
                </div>
                <p style="color: #cbd5e1; max-width: 320px; font-size: 0.925rem; line-height: 1.65; margin-bottom: 1rem;">
                    {{ $konten['footer_desc']->value ?? 'Pusat riset nasional inovasi herbal & hortikultura di Indonesia di bawah Kementerian Pendidikan Tinggi, Sains, dan Teknologi bekerjasama dengan Institut Teknologi Del.' }}
                </p>
                <div style="font-size: 0.8rem; color: #94a3b8; font-weight: 600;">
                    🇮🇩 Republik Indonesia — Kemendiktisaintek & IT Del
                </div>
            </div>
            <div class="footer-col">
                <h4>Navigasi Utama</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Beranda Utama</a></li>
                    <li><a href="{{ route('profil-public') }}">Profil & Tata Kelola</a></li>
                    <li><a href="{{ route('layanan') }}">Layanan & Lab ISO</a></li>
                    <li><a href="{{ route('penelitian') }}">Fasilitas Riset</a></li>
                    <li><a href="{{ route('berita') }}">Publikasi & Berita</a></li>
                    <li><a href="{{ route('galeri') }}">Arsip Galeri</a></li>
                    <li><a href="{{ route('katalog') }}">Katalog Tanaman</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Sekretariat & Hubungi Kami</h4>
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
            <p>&copy; {{ date('Y') }} TSTH2 (Indonesian Herbal and Horticultural Science and Technology Park). Hak Cipta Dilindungi Undang-Undang.</p>
        </div>
    </div>
</footer>
<script src="{{ asset('js/lightbox.js') }}"></script>
