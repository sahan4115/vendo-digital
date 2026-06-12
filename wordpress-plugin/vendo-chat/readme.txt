=== Vendo Chat ===
Contributors: vendodigital
Tags: chat, ai, claude, leads, enquiries
Requires at least: 6.0
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

AI enquiry assistant for Vendo Digital: a branded chat widget that answers visitor questions and captures leads into the dashboard.

== Description ==

"Venny" appears bottom-right on every page and:

* Answers questions about Vendo's services, pricing approach, dental and
  e-commerce specialisms, location and the free audit.
* Captures enquiries conversationally (name, email, phone, message),
  stores them under the **Enquiries** menu and emails you a notification
  with Reply-To set to the visitor.

Two modes:

* **AI mode** — add an Anthropic API key under Settings → Vendo Chat and
  free-typed questions are answered by Claude through a server-side REST
  proxy (`/wp-json/vendo-chat/v1/message`). The key never reaches the
  browser. Model selectable (Opus 4.8 default, Sonnet 4.6, Haiku 4.5).
* **Guided mode** — no key needed, zero cost: built-in intent matching
  answers the common questions and everything still funnels to enquiry
  capture.

Protection: per-IP rate limits on both endpoints (10 msgs/min, 5
enquiries/10 min), input length caps, honeypot field on the enquiry
endpoint, history capped at 12 turns before reaching the API.

== Installation ==

1. Plugins → Add New → Upload Plugin → vendo-chat.zip → Activate.
2. Settings → Vendo Chat: set the notification email. Optionally paste an
   Anthropic API key (console.anthropic.com) to enable AI answers.
3. The widget appears on the front end immediately. Enquiries arrive
   under the "Enquiries" admin menu and by email.

== Changelog ==

= 1.0.0 =
* Initial release: branded chat widget, guided + AI modes, Anthropic
  Messages API proxy with rate limiting, Enquiries CPT, email
  notifications, settings page.
