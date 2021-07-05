module.exports = {
  title: "Laravel Pricing",
  description: "Easily manage complex pricing in your Laravel applications.",
  base: "/laravel-pricing/",
  themeConfig: {
    // logo: "/logo.png",
    repo: "moirei/laravel-pricing",
    repoLabel: "Github",
    docsRepo: "moirei/laravel-pricing",
    docsDir: "docs",
    docsBranch: "master",
    sidebar: [
      {
        title: "Concept",
        collapsable: true,
        path: "/concept",
      },
      {
        title: "Get started",
        collapsable: true,
        children: ["/installation", "/prepare-models"],
      },
      {
        title: "Pricing Models",
        path: "/pricing",
        collapsable: false,
      },
      {
        title: "Usage",
        collapsable: false,
        children: [
          "/usage/eloquent",
          "/usage/stand-alone",
          "/usage/complex",
          "/usage/data",
        ],
      },
    ],
    nav: [{ text: "Home", link: "/" }],
  },
  head: [
    // ["link", { rel: "icon", href: "/logo.png" }],
    // ['link', { rel: 'manifest', href: '/manifest.json' }],
    ["meta", { name: "theme-color", content: "#3eaf7c" }],
    ["meta", { name: "apple-mobile-web-app-capable", content: "yes" }],
    [
      "meta",
      { name: "apple-mobile-web-app-status-bar-style", content: "black" },
    ],
    // ["link", { rel: "apple-touch-icon", href: "/icons/apple-touch-icon.png" }],
    // ['link', { rel: 'mask-icon', href: '/icons/safari-pinned-tab.svg', color: '#3eaf7c' }],
    // [
    //   "meta",
    //   {
    //     name: "msapplication-TileImage",
    //     content: "/icons/android-chrome-192x192.png",
    //   },
    // ],
    ["meta", { name: "msapplication-TileColor", content: "#000000" }],
  ],
  plugins: [
    "@vuepress/register-components",
    "@vuepress/active-header-links",
    "@vuepress/pwa",
    [
      "@vuepress/search",
      {
        searchMaxSuggestions: 10,
      },
    ],
    "seo",
  ],
};
