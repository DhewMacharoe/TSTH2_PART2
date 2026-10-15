<!-- PWA Primary Meta Tags -->
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#054439">
<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="TSTH2">

<!-- iOS Meta Tags & Icons -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="TSTH2">
<link rel="apple-touch-icon" href="{{ asset('assets/apple-touch-icon.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/apple-touch-icon.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/apple-touch-icon.png') }}">
<link rel="apple-touch-icon" sizes="167x167" href="{{ asset('assets/apple-touch-icon.png') }}">

<!-- Windows Tile Icons -->
<meta name="msapplication-TileImage" content="{{ asset('assets/icon-192.png') }}">
<meta name="msapplication-TileColor" content="#054439">

<!-- Standard Favicon -->
<link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/icon-192.png') }}">
<link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">

<!-- PWA Service Worker Registration -->
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('{{ asset("sw.js") }}', { scope: '{{ url("/") }}/' })
                .then(function(reg) {
                    console.log('PWA Service Worker registered successfully: ', reg.scope);
                })
                .catch(function(err) {
                    console.warn('PWA Service Worker registration error: ', err);
                });
        });
    }
</script>
