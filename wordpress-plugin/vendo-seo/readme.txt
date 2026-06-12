=== Vendo SEO ===
Contributors: vendodigital
Tags: seo, schema, open graph, local business, json-ld
Requires at least: 6.0
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lightweight SEO for Vendo Digital: meta tags, Open Graph cards and LocalBusiness structured data, configured from Settings → Vendo SEO.

== Description ==

Outputs on the front end:

* Homepage title and meta description
* Canonical URL and an improved robots directive (large image previews)
* Open Graph and Twitter Card tags for social link previews
* JSON-LD structured data: ProfessionalService (LocalBusiness) with
  name/address/phone, price range, founding year, areas of expertise and
  social profiles, plus WebSite and WebPage nodes

All values are editable under **Settings → Vendo SEO** and ship with
Vendo Digital's real details as defaults.

The plugin is theme-independent and **stands down automatically** when
Yoast SEO, Rank Math, All in One SEO or SEOPress is active, so tags are
never duplicated. The Vendo theme's built-in SEO layer likewise stands
down when this plugin is active.

== Installation ==

1. Plugins → Add New → Upload Plugin → choose vendo-seo.zip → Install Now → Activate.
2. Go to Settings → Vendo SEO, review the values, upload a 1200×630 share image, Save.
3. Validate the homepage at https://search.google.com/test/rich-results

== Changelog ==

= 1.0.0 =
* Initial release: meta description, canonical, robots, Open Graph/Twitter, LocalBusiness JSON-LD, settings page with media uploader, SEO-plugin conflict guard, uninstall cleanup.
