/**
 * Welcome to your Workbox-powered service worker!
 *
 * You'll need to register this file in your web app and you should
 * disable HTTP caching for this file too.
 * See https://goo.gl/nhQhGp
 *
 * The rest of the code is auto-generated. Please don't update this file
 * directly; instead, make changes to your Workbox build configuration
 * and re-run your build process.
 * See https://goo.gl/2aRDsh
 */

importScripts("https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js");

self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

/**
 * The workboxSW.precacheAndRoute() method efficiently caches and responds to
 * requests for URLs in the manifest.
 * See https://goo.gl/S9QRab
 */
self.__precacheManifest = [
  {
    "url": "404.html",
    "revision": "95b18174217d32e0566d25aa7de7bd6d"
  },
  {
    "url": "assets/css/0.styles.0ff02e0b.css",
    "revision": "bf49294762161ac47ff7954ddecd019d"
  },
  {
    "url": "assets/img/search.83621669.svg",
    "revision": "83621669651b9a3d4bf64d1a670ad856"
  },
  {
    "url": "assets/js/10.0926590c.js",
    "revision": "ad8559a95c5f7c45973e2f3002df3ef2"
  },
  {
    "url": "assets/js/11.42b3934e.js",
    "revision": "01c7f0b98f3eb4854089556bbf386604"
  },
  {
    "url": "assets/js/12.7a423e23.js",
    "revision": "f02ac29b7fb197d9d2beaf5f9b3a018a"
  },
  {
    "url": "assets/js/13.ba3a6172.js",
    "revision": "069bb447bb537ec249d4cea2c5e87e22"
  },
  {
    "url": "assets/js/14.d8d64f5a.js",
    "revision": "af15bf949f49ce2903b79a413ff275a9"
  },
  {
    "url": "assets/js/15.331b4bcb.js",
    "revision": "e7c5e394d4554d30c87ea95a866d468e"
  },
  {
    "url": "assets/js/16.29db673a.js",
    "revision": "3eecd6e2e3f1ddd4560a57d1f010743c"
  },
  {
    "url": "assets/js/17.6f6394ed.js",
    "revision": "381d01e19d00b402b3d1f1ce6e4a8907"
  },
  {
    "url": "assets/js/18.28bd023a.js",
    "revision": "52b2d6bcf12f4af98bacce94c20c4a9b"
  },
  {
    "url": "assets/js/19.f34eecf3.js",
    "revision": "a8da9046bd5abe070041396d15457414"
  },
  {
    "url": "assets/js/2.6ddd8aba.js",
    "revision": "cbed2ed15481b149e47d4faab59abf1b"
  },
  {
    "url": "assets/js/20.71d2a223.js",
    "revision": "58262419c141e5fbe5fd7a46a292254c"
  },
  {
    "url": "assets/js/3.4a818af6.js",
    "revision": "b6833f4fae7cc9ab7d1b131d69b9fe77"
  },
  {
    "url": "assets/js/4.7bd0e979.js",
    "revision": "ba5eb7cc188fb486b6b77c3d28af20c2"
  },
  {
    "url": "assets/js/5.b8254b2c.js",
    "revision": "b2f83d4a323b1cccabcf6b780abb804c"
  },
  {
    "url": "assets/js/6.10d24408.js",
    "revision": "3486daf8876e68f8f2c47eb0cd611478"
  },
  {
    "url": "assets/js/7.2143f453.js",
    "revision": "7d95a487a4ada9e2e4abc1be2498cf32"
  },
  {
    "url": "assets/js/8.681796f1.js",
    "revision": "877bf0e9e52c1b700a5ba5aa96804fd9"
  },
  {
    "url": "assets/js/9.13215a3c.js",
    "revision": "497d53f65459537902cb03f2f465e4e6"
  },
  {
    "url": "assets/js/app.6cbbe6d4.js",
    "revision": "06be748ef609bf7afe80c3baae62a42e"
  },
  {
    "url": "concept.html",
    "revision": "0a1b0743593fd0d9994780fcd1278b2c"
  },
  {
    "url": "index.html",
    "revision": "c8b21866e2afa4f4820bac667324786c"
  },
  {
    "url": "installation.html",
    "revision": "503a7d8cdfd8c3c29f28b38be3fc64d7"
  },
  {
    "url": "prepare-models.html",
    "revision": "3c56d3ce4faa2a7ecdadb64c513fb6e4"
  },
  {
    "url": "pricing.html",
    "revision": "d5486ca1b1efc87898e57b75c36f6062"
  },
  {
    "url": "usage/complex.html",
    "revision": "a9e2f850da0359b03542fb280b62e7d3"
  },
  {
    "url": "usage/data.html",
    "revision": "e9baca2186f53c44f58a9ed75b643d44"
  },
  {
    "url": "usage/eloquent-castmultipricing.html",
    "revision": "f7c580a91d951b1050594deeaa781d3f"
  },
  {
    "url": "usage/eloquent-castpricing.html",
    "revision": "fc7581ebf70756a982f5b051ab240bca"
  },
  {
    "url": "usage/eloquent-haspricing.html",
    "revision": "b7a3eaf7c08089336270677af050e0de"
  },
  {
    "url": "usage/stand-alone.html",
    "revision": "329e02c7d0809eb1b1bfc4fc57f30d61"
  },
  {
    "url": "usage/utils.html",
    "revision": "ab1eab89e998bc3c44be26cd7a33df1b"
  }
].concat(self.__precacheManifest || []);
workbox.precaching.precacheAndRoute(self.__precacheManifest, {});
addEventListener('message', event => {
  const replyPort = event.ports[0]
  const message = event.data
  if (replyPort && message && message.type === 'skip-waiting') {
    event.waitUntil(
      self.skipWaiting().then(
        () => replyPort.postMessage({ error: null }),
        error => replyPort.postMessage({ error })
      )
    )
  }
})
