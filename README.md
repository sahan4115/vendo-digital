# Vendo Digital

An award-style, highly interactive website for **Vendo Digital** — a PPC, SEO and
web design agency in Sutton, Surrey. Built as a fast static multi-page site with
**GSAP** (scroll story, page transitions, micro-interactions) and **Three.js**
(a custom-shader particle field in the homepage hero), plus an optional AI chat
assistant and matching WordPress packages.

🌐 **Live:** https://sahan4115.github.io/vendo-digital/

---

## Brand

Built to the official Vendo brand sheet.

| Token       | Hex       | Use |
| ----------- | --------- | --- |
| Vendo Green | `#8EFEBB` | Primary accent |
| Vendo Black | `#051412` | Background |
| Vendo Sage  | `#09221F` | Panels / borders |
| Charcoal    | `#2C2C2C` | Muted UI |
| Gray        | `#ABABAB` | Body text |
| White       | `#F4FBF7` | Headings |

Type: **Manrope** (display) + **Instrument Sans** (body, italics).

---

## Pages

A multi-page site sharing one stylesheet and one script. The homepage carries the
full hero experience (preloader + WebGL); inner pages use a lighter
sage-veil page-enter and omit Three.js for weight.

| Page | File | Highlights |
| --- | --- | --- |
| Home | `index.html` | Particle-field hero, marquee, "Why Vendo" + stats, results grid, interactive services showcase, dental/e-commerce fork, sticky word-stack process, rotating V. badge, CTA |
| Results | `results.html` | Full client work grid + headline-proof statement |
| Services | `services.html` | Interactive tab showcase + per-service detail blocks linking to the service pages |
| Service detail ×5 | `service-web-design.html`, `service-google-ads.html`, `service-seo.html`, `service-content-brand.html`, `service-paid-social.html` | Pull-band, bento "what you get" grid, scroll-drawn timeline, FAQ, related work |
| Dental | `dental.html` | Flagship niche: £90K story with counters, differentiator cards, dental cases, FAQ |
| Studio | `studio.html` | Manifesto + stats and the **draggable artwork roster** (team) |
| Contact | `contact.html` | Email/call/chat cards, audit scope, office, ring CTA |

The five `service-*.html` pages are generated from one template —
see [Tooling](#tooling).

---

## Interactivity

- **Preloader → hero** — letter-staggered logo + 0–100 counter, line-masked hero
  reveal (homepage only; inner pages get a fast page-enter under a sage veil).
- **Three.js hero** — custom-shader particle field with mouse parallax; particle
  count scales down on small screens, DPR capped, rendering pauses off-screen.
- **GSAP scroll story** — scroll-velocity marquee, manifesto word highlight,
  animated stat counters, per-element reveals, hero parallax, the sticky
  word-stack process (giant words fill green on scroll), and the timeline rail.
- **Components** — interactive services showcase (hover/tab + cross-fading
  visuals), draggable team roster gallery, bento grids, redesigned FAQ accordion,
  3D-tilt cards, magnetic buttons, custom cursor with contextual labels.
- **Chat assistant ("Venny")** — floating widget; guided mode by default, Claude
  AI mode when served by the WordPress plugin (see below).
- **Accessible & resilient** — `prefers-reduced-motion` disables motion;
  graceful fallback if the GSAP/Three.js CDNs fail.

---

## Run locally

It's a static site — serve the folder with anything:

```bash
npx serve .
# or
python -m http.server 3456
```

Then open `http://localhost:3456`.

> The team artwork uses page-relative paths, so it works from any host or
> subpath (including GitHub Pages). Avoid opening via `file://` — use a server
> so the fonts and images resolve.

---

## Project structure

```
vendo-digital/
├── index.html                 Homepage
├── results.html               \
├── services.html               |  inner pages
├── dental.html                 |
├── studio.html                 |
├── contact.html               /
├── service-*.html             5 generated service pages
├── css/
│   ├── style.css              Brand system + all page styling
│   └── chat.css               Chat widget styles
├── js/
│   ├── main.js                GSAP + Three.js + every interaction
│   └── chat.js                Chat widget (guided + AI modes)
├── images/team/               14 AI-generated roster illustrations (webp)
├── tools/
│   └── gen-service-pages.mjs  Generates the five service-*.html pages
├── wordpress-theme/vendo/     Backend-editable WordPress theme
└── wordpress-plugin/
    ├── vendo-seo/             SEO plugin (meta, OG, JSON-LD)
    └── vendo-chat/            AI chat assistant plugin
```

---

## WordPress packages

All three are optional and independent — the static site needs none of them.

### Theme — `wordpress-theme/vendo/`
A fully **backend-editable** theme using WordPress-native APIs only (no page
builder, no ACF). All homepage copy lives in **Appearance → Customize → Vendo —
Page Content**; the results grid is a **Case Studies** custom post type.
Upload `wordpress-theme/vendo.zip` via Appearance → Themes → Add New.

> Note: the theme currently mirrors the original one-page layout. The static
> multi-page version is the source of truth for the newest sections.

### Plugin — `wordpress-plugin/vendo-seo/`
Lightweight SEO: meta description, canonical, Open Graph / Twitter cards and
`ProfessionalService` (LocalBusiness) JSON-LD, configured under **Settings →
Vendo SEO**. Stands down automatically if Yoast / Rank Math / AIOSEO / SEOPress
(or the theme's built-in SEO) is active. Upload `vendo-seo.zip`.

### Plugin — `wordpress-plugin/vendo-chat/`
The "Venny" chat assistant: a branded widget that answers questions and captures
enquiries into an **Enquiries** post type with email notifications. With an
Anthropic API key (**Settings → Vendo Chat**) it answers via a server-side proxy
to the Claude Messages API (key never exposed to the browser); without one it
runs in guided mode at zero cost. Upload `vendo-chat.zip`.

---

## Tooling

```bash
node tools/gen-service-pages.mjs   # regenerate the five service-*.html pages
```

Edit the per-service data (and shared template) at the top of that file, then
re-run it to keep all five pages consistent.

---

## Deployment

Hosted on **GitHub Pages** from the `main` branch root. Any push to `main`
redeploys within a minute or two — no build step (it's a static site).

```
https://sahan4115.github.io/vendo-digital/
```

---

## Credits

Design & build: bespoke. Team illustrations generated with Higgsfield. Libraries:
[GSAP](https://gsap.com/) (+ ScrollTrigger) and [Three.js](https://threejs.org/),
loaded from CDN.

© 2026 Vendo Digital Ltd.
