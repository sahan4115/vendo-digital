# Vendo Digital — WordPress Theme

A custom one-page WordPress theme for the Vendo Digital landing page. The
animated front-end (GSAP scroll story, Three.js hero, services showcase,
sticky word-stack process, rotating V. badge) is preserved exactly — the
markup the JavaScript depends on is identical to the original static site.

## What's inside

```
vendo/
├── style.css          WordPress theme header (required). Real CSS is in assets/.
├── functions.php      Enqueues fonts, CSS, GSAP, ScrollTrigger, Three.js, main.js
├── header.php         <head> + preloader, cursor, grain, nav, fullscreen menu
├── front-page.php     The landing page (hero → footer)
├── footer.php         Footer markup + wp_footer()
├── index.php          Fallback (re-uses front-page.php)
└── assets/
    ├── css/style.css  Full stylesheet (byte-for-byte from the static site)
    └── js/main.js     All interactions (byte-for-byte from the static site)
```

## Install

1. **Zip the `vendo` folder** (the folder that contains `style.css`), so the
   archive is `vendo.zip` with `style.css` at its root.
2. In WordPress: **Appearance → Themes → Add New → Upload Theme**, choose
   `vendo.zip`, **Install**, then **Activate**.
   *(Or copy the `vendo` folder into `wp-content/themes/` over SFTP.)*
3. The homepage uses `front-page.php` automatically — no page setup needed.
   WordPress always uses `front-page.php` for the site's front page.
4. Set **Settings → General → Site Title** to `Vendo Digital` (used in the
   browser tab via the theme's `title-tag` support; an SEO plugin like
   Yoast/Rank Math will override it if installed).

## Notes

- **Libraries** load from CDN (GSAP 3.12.5, Three.js 0.160.0). To self-host,
  drop the files in `assets/js/` and change the URLs in `functions.php`.
- **The free-audit CTA** currently uses a `mailto:` link. To capture
  submissions in WordPress instead, install Contact Form 7 or WPForms and
  replace the CTA link/button with the form shortcode.
- **Editing copy:** this is the static theme — text lives in `front-page.php`.
  To let non-developers edit the hero, stats, services and case studies from
  the dashboard, the next step is an ACF (Advanced Custom Fields) layer plus a
  "Case Study" custom post type.
- **Requires** WordPress 6.0+ and PHP 7.4+.
