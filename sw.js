const cacheName = 'tanzamart-v2'; // Nimebadilisha jina kuwa v2 ili kufuta cache yote ya zamani
const assetsToCache = [
  'index.php',
  'products.php',
  'track.php',
  'includes/db.php'
];

// 1. Inasakinisha Service Worker mpya
self.addEventListener('install', event => {
  self.skipWaiting(); // Inalazimisha Service Worker mpya ianze kazi mara moja
  event.waitUntil(
    caches.open(cacheName).then(cache => {
      return cache.addAll(assetsToCache);
    })
  );
});

// 2. Inasafisha kumbukumbu (Cache) za zamani wakati Service Worker mpya inapoanza
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cache => {
          if (cache !== cacheName) {
            console.log('TanzaMart: Inasafisha cache ya zamani:', cache);
            return caches.delete(cache);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// 3. NETWORK-FIRST STRATEGY (Inachukua data mpya kabisa kutoka server/database kwanza)
self.addEventListener('fetch', event => {
  // Tunahakikisha ombi ni la HTTP/HTTPS pekee
  if (!event.request.url.startsWith('http')) return;

  event.respondWith(
    fetch(event.request)
      .then(networkResponse => {
        // Kama mtandao upo, inachukua data mpya kutoka server na ku-update cache
        if (networkResponse && networkResponse.status === 200) {
          const responseToCache = networkResponse.clone();
          caches.open(cacheName).then(cache => {
            cache.put(event.request, responseToCache);
          });
        }
        return networkResponse;
      })
      .catch(() => {
        // Kama hakuna mtandao kabisa (Offline), ndipo inapotumia nakala iliyohifadhiwa
        return caches.match(event.request);
      })
  );
});