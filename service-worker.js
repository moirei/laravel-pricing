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
    "revision": "c3cf777a58c86f9101b14b5c85df5662"
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
    "url": "assets/js/10.3aaf61f0.js",
    "revision": "bde31c40d37dd284b695f7a7c5747b4e"
  },
  {
    "url": "assets/js/11.902c4c2c.js",
    "revision": "c63bd50972a58b2095cc55f7aecaf509"
  },
  {
    "url": "assets/js/12.58eccf1f.js",
    "revision": "4d7985120350cf373a4dc000fd81759f"
  },
  {
    "url": "assets/js/13.c49f9d28.js",
    "revision": "69aa3de00aa57ec2d9a2afb418eff549"
  },
  {
    "url": "assets/js/14.54fa456f.js",
    "revision": "52b204a8b9ac81212374a6879a4be5ac"
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
    "url": "assets/js/17.0679c40b.js",
    "revision": "94be93f43ea5edb194663c4c8d1e4a91"
  },
  {
    "url": "assets/js/18.fa143d05.js",
    "revision": "c8857a3dd2dde7d43131d26b916f32b5"
  },
  {
    "url": "assets/js/19.01c90a72.js",
    "revision": "b4b5ac031c1fb531c25794e4d7d35d49"
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
    "url": "assets/js/8.dd40b9ef.js",
    "revision": "be428bb149e64986b05d2229ff09e222"
  },
  {
    "url": "assets/js/9.27c057d1.js",
    "revision": "0ad6c54eb6f73b8d1b3f7a6535c2f94f"
  },
  {
    "url": "assets/js/app.603be3b8.js",
    "revision": "aa8fd1bdf65aef3124a682bd68724332"
  },
  {
    "url": "concept.html",
    "revision": "2a3d394bc305c1c03c217e0f05c365b1"
  },
  {
    "url": "index.html",
    "revision": "1ce29b0a7c2ae89203ca8b937f005bd7"
  },
  {
    "url": "installation.html",
    "revision": "3b30fce071cccbb5a01c7e57702a2e32"
  },
  {
    "url": "prepare-models.html",
    "revision": "8eaec6e9d2159b97c47d4631fe5f0e3c"
  },
  {
    "url": "pricing.html",
    "revision": "709b4dba96267d0752790fef3ea5468c"
  },
  {
    "url": "usage/complex.html",
    "revision": "1b3f68e29d6fcc4f463bdd5e7d089038"
  },
  {
    "url": "usage/data.html",
    "revision": "c9ce9485ca65baddd1fa6934632a6c83"
  },
  {
    "url": "usage/eloquent-castmultipricing.html",
    "revision": "06cf43fe54699621bf50c91ee8849f1a"
  },
  {
    "url": "usage/eloquent-castpricing.html",
    "revision": "45e9104c6ac361b61030a1d6062e0947"
  },
  {
    "url": "usage/eloquent-haspricing.html",
    "revision": "c8a5a9f284827e7ab8b4c354135da6b2"
  },
  {
    "url": "usage/stand-alone.html",
    "revision": "7c5237bd74da97699bfea489af87249b"
  },
  {
    "url": "usage/utils.html",
    "revision": "f32efeb35fd50ced4542d0590f908dc7"
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
