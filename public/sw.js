/* KASSCare service worker
 * - Caches the theme, icons and an offline page so the app opens fast.
 * - Never caches patient pages: those always come fresh from the server.
 */
const VERSION = 'kasscare-v2';
const STATIC = ['/offline.html', '/css/kasscare-theme.css', '/icons/kasscare-192.png', '/manifest.webmanifest'];

self.addEventListener('install', (event) => {
  event.waitUntil(caches.open(VERSION).then((c) => c.addAll(STATIC)).then(() => self.skipWaiting()));
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys()
      .then((keys) => Promise.all(keys.filter((k) => k !== VERSION).map((k) => caches.delete(k))))
      .then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  const req = event.request;
  if (req.method !== 'GET') return;
  const url = new URL(req.url);
  if (url.origin !== self.location.origin) return;

  // Pages: always network (fresh, private data). Offline page if no signal.
  if (req.mode === 'navigate') {
    event.respondWith(fetch(req).catch(() => caches.match('/offline.html')));
    return;
  }

  // Static files: cache first, then refresh in the background
  if (/\.(css|js|png|svg|woff2?|webmanifest)$/.test(url.pathname)) {
    event.respondWith(
      caches.match(req).then((hit) => {
        const live = fetch(req).then((res) => {
          if (res.ok) caches.open(VERSION).then((c) => c.put(req, res.clone()));
          return res;
        }).catch(() => hit);
        return hit || live;
      })
    );
  }
});
