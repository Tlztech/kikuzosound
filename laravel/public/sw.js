//var CACHE_NAME = 'aus-cacheV4';
var urlsToCache = [
  '/',
  //css
  'js/bxslider/jquery.bxslider.css',
  'css/jquery-ui.css',
  'css/jquery-ui.structure.min.css',
  'css/jquery-ui.theme.min.css',
  'css/full-screen-helper.css',
  'css/jquery-ui-slider-pips.css',

  //js
  'js/jquery.min.js',
  'js/bxslider/jquery.bxslider.js',
  'js/full-screen-helper.min.js',
  'js/jquery-ui.js',
  'js/jquery.ui.touch-punch.min.js',
  'js/jquery-ui-slider-pips.js',
  'js/jquery.imagebox.js?v=1.1.6.20170323',
  'js/bootstrap-switch-button.js?v=1.1.6.20210421',


  //img
  'img/heart_icon.png',
  'img/pulse_icon.png',
  'img/lung_icon.png',
  'favicon.ico',
  'img/bg.png',
  'img/heart_grey_icon.png',
  'img/pulse_grey_icon.png',
  'img/lung_grey_icon.png',
  'img/switch_body.png',
  'img/stethoscope.png',
  'img/reload_icon.png',
  'img/reload_icon_big.png',

  'manifest.json'
];

self.addEventListener('install', function(event) {
  const CACHE_NAME= new URL(location).searchParams.get('cache_version');
  // Perform install steps
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', function (event) {
  event.respondWith(
      fetch(event.request).catch(function() {
          return caches.match(event.request)
      })
  )
})

self.addEventListener('activate', function(event) {
  const CACHE_NAME= new URL(location).searchParams.get('cache_version');
  var cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(function(cacheNames) {
      return Promise.all(
        cacheNames.map(function(cacheName) {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});