import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        // ★ PWA plugin – otomatis menghasilkan manifest & service‑worker ★
        VitePWA({
            registerType: 'autoUpdate',
            injectRegister: 'script',
            manifest: {
                name: 'TSTH2 Hub',
                short_name: 'TSTH2',
                description: 'Portal Admin dan Gudang TSTH2',
                start_url: '/',
                scope: '/',
                display: 'standalone',
                background_color: '#ffffff',
                theme_color: '#059669',
                icons: [
                    { src: '/assets/logo.png', sizes: '48x48', type: 'image/png' },
                    { src: '/assets/logo.png', sizes: '72x72', type: 'image/png' },
                    { src: '/assets/logo.png', sizes: '96x96', type: 'image/png' },
                    { src: '/assets/logo.png', sizes: '144x144', type: 'image/png' },
                    { src: '/assets/logo.png', sizes: '192x192', type: 'image/png' },
                    { src: '/assets/logo.png', sizes: '256x256', type: 'image/png' },
                    { src: '/assets/logo.png', sizes: '384x384', type: 'image/png' },
                    { src: '/assets/logo.png', sizes: '512x512', type: 'image/png' },
                ],
            },
            workbox: {
                // cache‑strategi untuk offline mode
                runtimeCaching: [
                    {
                        urlPattern: ({ request }) => request.destination === 'document',
                        handler: 'NetworkFirst',
                        options: { cacheName: 'html-cache' },
                    },
                    {
                        urlPattern: ({ request }) => ['script', 'style', 'image'].includes(request.destination),
                        handler: 'StaleWhileRevalidate',
                        options: { cacheName: 'asset-cache' },
                    },
                ],
            },
        }),
    ],
});
