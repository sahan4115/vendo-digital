# Vendo Digital — WordPress Theme

A custom one-page WordPress theme for the Vendo Digital landing page,
**fully editable from the WordPress admin** with zero plugin dependencies.
The animated front-end (GSAP scroll story, Three.js hero, services
showcase, sticky word-stack process, rotating V. badge) is preserved
exactly — the markup the JavaScript depends on is identical to the
original static site.

## Editing content (no code needed)

- **All page copy** → **Appearance → Customize → Vendo — Page Content**,
  grouped by section (Hero, Marquee, Why Vendo + Stats, Client Results,
  Services, Niche Cards, How It Works, CTA, Contact & Footer), with live
  preview. Everything defaults to the designed copy, so a fresh install
  looks exactly like the original.
- **Client results grid** → the **Case Studies** menu in the admin
  sidebar. Each case study: title, one-line blurb (the Excerpt field),
  and a Card Settings box for tags, card background (sage / charcoal /
  Vendo Green) and visual style (app window / dashboard / phones /
  website). Reorder with the Order attribute. Until you publish your
  first case study, the four designed defaults are shown.
- **Manifesto accent words** (the green words that light up on scroll)
  are a Customizer field too — they're passed from PHP into the GSAP
  script via `wp_localize_script`.

Built on WordPress-native APIs only (Customizer + custom post type +
meta boxes) — no ACF or other plugins required.

## SEO (built in)

The theme ships its own SEO layer (`inc/seo.php`), so you don't need an
SEO plugin — but it **automatically stands down** if Yoast, Rank Math,
All in One SEO or SEOPress is active, so nothing is ever duplicated.

What it outputs on the homepage:

- A search/browser **title** and **meta description** (editable under
  Customize → SEO & Sharing).
- **Canonical** URL, and a richer robots directive (`max-image-preview:large`).
- **Open Graph** + **Twitter Card** tags for clean link previews. Upload a
  1200×630 **Share image** in the SEO section to get large image cards.
- **JSON-LD structured data** (`@graph`): a `ProfessionalService`
  (LocalBusiness) node with your name, address, phone, price range,
  founding year, social profiles and areas of expertise (auto-built from
  your service names), plus `WebSite` and `WebPage` nodes.

To finish the local-SEO setup:

1. **Customize → SEO & Sharing** → upload a share image; optionally paste
   your office **latitude/longitude** (from Google Maps → right-click your
   pin → copy the numbers) so the business shows a precise map location.
2. Set **Settings → General → Site Title** to `Vendo Digital`.
3. Submit `https://yoursite.com/wp-sitemap.xml` (WordPress builds this
   automatically) to **Google Search Console**.
4. Validate the markup at **search.google.com/test/rich-results**.

No FAQ schema is included on purpose: the page has no visible FAQ
section, and Google's guidelines prohibit marking up content that isn't
on the page. (If you add a real FAQ section later, FAQ schema can go with it.)

## What's inside

```
vendo/
├── style.css            WordPress theme header (required). Real CSS is in assets/.
├── functions.php        Enqueues assets; loads inc/; localizes JS settings
├── header.php           <head> + preloader, cursor, grain, nav, fullscreen menu
├── front-page.php       The landing page, templated from Customizer settings
├── footer.php           Footer (contact/socials from Customizer) + wp_footer()
├── index.php            Fallback (re-uses front-page.php)
├── inc/
│   ├── customizer.php   ~70 settings with the designed copy as defaults
│   ├── helpers.php      vendo_mod/vendo_words/mock renderer/case defaults
│   └── case-studies.php Case Study post type + Card Settings meta box
└── assets/
    ├── css/style.css    Full stylesheet (byte-for-byte from the static site)
    └── js/main.js       All interactions (accent words overridable from WP)
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
- **Requires** WordPress 6.0+ and PHP 7.4+.
- `wordpress-theme/_render-test.php` (one level up, outside this folder) is a
  development harness that renders the theme without WordPress for testing —
  don't include it in the theme zip.
