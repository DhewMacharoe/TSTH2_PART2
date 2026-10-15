// Service Worker TSTH2 PWA
const CACHE_NAME = 'tsth2-cache-v2';
const STATIC_ASSETS = [
    '/',
    '/manifest.json',
    '/assets/logo.png',
    '/css/index.css',
    '/css/landing.css'
];

// Install Event
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(STATIC_ASSETS).catch(err => {
                    console.warn('Gagal caching sebagian aset statis saat install:', err);
                });
            })
            .then(() => self.skipWaiting())
    );
});

// Activate Event
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.filter(name => name !== CACHE_NAME)
                    .map(name => caches.delete(name))
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch Event
self.addEventListener('fetch', event => {
    // Only handle GET requests
    if (event.request.method !== 'GET') return;

    const acceptHeader = event.request.headers.get('accept') || '';
    const isHtmlOrApi = acceptHeader.includes('text/html') || event.request.url.includes('/api/');

    // Handle HTML & API requests (Network First, Fallback to Cache)
    if (isHtmlOrApi) {
        event.respondWith(
            fetch(event.request)
                .then(response => {
                    if (response && response.status === 200) {
                        const responseClone = response.clone();
                        caches.open(CACHE_NAME).then(cache => {
                            cache.put(event.request, responseClone);
                        });
                    }
                    return response;
                })
                .catch(() => caches.match(event.request).then(cached => cached || caches.match('/')))
        );
    } 
    // Handle Static Assets (Stale-While-Revalidate / Cache First)
    else {
        event.respondWith(
            caches.match(event.request).then(response => {
                return response || fetch(event.request).then(fetchResponse => {
                    if (fetchResponse && fetchResponse.status === 200 && (event.request.url.startsWith(self.location.origin) || event.request.url.includes('fonts.bunny.net'))) {
                        const responseClone = fetchResponse.clone();
                        caches.open(CACHE_NAME).then(cache => {
                            cache.put(event.request, responseClone);
                        });
                    }
                    return fetchResponse;
                }).catch(() => {
                    // Fallback jika offline dan aset tidak ada
                    return null;
                });
            })
        );
    }
});

