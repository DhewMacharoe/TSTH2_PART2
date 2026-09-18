<footer>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <div class="footer-logo">
                    <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo" style="filter: grayscale(1) invert(1) brightness(2); transform: scale(1.6); transform-origin: left center;">
                    <span style="font-size: 1.5rem; font-weight: 700; font-family: var(--font-heading);">{{ $contents['navbar_title']->value ?? '' }}</span>
                </div>
                <p style="color: rgba(255,255,255,0.7); max-width: 300px;">{{ $contents['footer_desc']->value ?? '' }}</p>
            </div>
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('program') }}">Program</a></li>
                    <li><a href="{{ route('research') }}">Research</a></li>
                    <li><a href="{{ route('news') }}">News</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Legal</h4>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact Us</h4>
                <ul style="color: rgba(255,255,255,0.7);">
                    <li>{{ $contents['footer_address']->value ?? '' }}</li>
                    <li>{{ $contents['footer_email']->value ?? '' }}</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} TSTH2. All rights reserved.</p>
        </div>
    </div>
</footer>
<script src="{{ asset('js/lightbox.js') }}"></script>
