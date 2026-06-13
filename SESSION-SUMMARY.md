# Vendo Digital — Build Session Summary & Handoff

A running record of everything built in this session, so work can continue in a
fresh chat without losing context. Newest sections are near the top.

- **Repo:** https://github.com/sahan4115/vendo-digital
- **Live (GitHub Pages):** https://sahan4115.github.io/vendo-digital/
- **Local path:** `C:\IPS\vendo-digital`
- **Latest commit at handoff:** `95dc532`

---

## 1. What this project is

An award-style, highly interactive **multi-page** marketing website for **Vendo
Digital** — a real PPC / SEO / web-design agency in Sutton, Surrey (content
sourced from auditing their live site, vendodigital.co.uk). Static HTML/CSS/JS
+ GSAP + Three.js, hosted on GitHub Pages, plus optional WordPress packages.

**Brand:** Vendo Green `#8EFEBB`, Vendo Black `#051412`, Sage `#09221F`,
Charcoal `#2C2C2C`, Gray `#ABABAB`, White `#F4FBF7`. Fonts: **Manrope**
(display) + **Instrument Sans** (body/italics).

---

## 2. File map

```
vendo-digital/
├── index.html            Homepage (preloader + Three.js particle hero)
├── results.html          Client work grid
├── services.html         Services — hover-reveal list (see §4, latest work)
├── service-web-design.html / -google-ads / -seo / -content-brand / -paid-social
│                         5 service detail pages (generated)
├── dental.html           Flagship niche (counters, FAQ, cases)
├── studio.html           Team — draggable artwork roster
├── contact.html          Form + immersive map + opening hours
├── css/style.css         All site styling (brand system + every component)
├── css/chat.css          Chat widget
├── js/main.js            ALL interactions (GSAP, Three.js, every component)
├── js/chat.js            Chat widget (guided + AI modes)
├── images/team/          14 AI roster illustrations (Higgsfield)
├── images/services/      5 AI service images (Higgsfield) — used by hover-reveal
├── tools/gen-service-pages.mjs   Generates the 5 service-*.html pages
├── wordpress-theme/vendo/        Backend-editable WP theme (+ vendo.zip)
└── wordpress-plugin/
    ├── vendo-seo/  (+ vendo-seo.zip)    SEO plugin
    └── vendo-chat/ (+ vendo-chat.zip)   AI chat assistant plugin
```

---

## 3. Components & interactions built (chronological)

1. **Homepage rebuilt** on the real brand: "Marketing that pays for itself"
   hero, Three.js shader particle field, scroll-velocity marquee, "Why Vendo"
   manifesto with word-highlight + animated stat counters, results grid,
   interactive services showcase, dental/e-commerce fork, **sticky word-stack
   process** (giant words fill green on scroll, redesigned from a flat card row
   then again into a stacked card deck), rotating **V.** badge (uses official
   `VD_ICON_WHITE.svg`).
2. **GitHub + Pages set up** (gh CLI installed, repo `sahan4115/vendo-digital`,
   Pages from `main` root).
3. **Content rewrite** — audited vendodigital.co.uk + Instagram; rewrote
   homepage copy to the real positioning (PPC/SEO/web, dental £90K story, etc.).
4. **WordPress theme** — fully Customizer-editable, Case Studies CPT, no plugins.
5. **Vendo SEO plugin** — meta/OG/canonical/LocalBusiness JSON-LD, settings page,
   conflict-guards against Yoast/RankMath/etc. (functions prefixed `vseo_` after
   a fatal name-clash was fixed).
6. **Vendo Chat plugin + widget ("Venny")** — guided mode by default, Claude API
   mode via server-side proxy when a key is set; captures enquiries to an
   Enquiries CPT + email. Widget also runs on the static site (guided + mailto).
7. **Multi-page conversion** — split the one-pager into the pages above, with a
   CSS sage-veil page-enter transition + word-mask hero reveal on inner pages.
8. **5 service detail pages** — new components: pull-band, bento "what you get"
   grid, scroll-drawn timeline, FAQ, related work. Generated via
   `tools/gen-service-pages.mjs`.
9. **FAQ redesigned** (`.faq2`) — split sticky-intro layout, numbered animated
   accordion (grid-rows height animation). Used on dental + service pages.
10. **Team section** — first a typographic index with a cursor-chasing card
    (fixed a first-hover "fly-in from corner" bug), then **rebuilt as a
    draggable artwork gallery** (grab-and-throw, progress bar, arrows).
11. **Higgsfield imagery** — 14 geometric brand portraits for the team + a
    Winston & Gus card; later 5 abstract service images.
12. **Contact page relayout** — interactive form (floating labels, service
    pills, validation, success state, posts to the chat plugin's `/enquiry`
    endpoint or falls back to mailto) + **immersive ~74vh map** with floating
    glass address/hours panels, pulsing beacon, copy-address button, live
    Open/Closed status.
13. **Services listing → hover-reveal list (latest, commit `95dc532`)** — big
    typographic rows linking to the 5 detail pages; on desktop hover, a preview
    image (the `images/services/*.webp`) **follows the cursor** and other rows
    dim; mobile shows inline thumbs. Replaced the earlier card grid, which had
    itself replaced the original tab "At a glance" showcase (now removed).

---

## 4. Current state of `services.html` (just shipped)

- Section 01 is now `.slist` (id `slist`) — 5 `.srow` anchor rows + a
  `#slistReveal` cursor-following image element + a `.slist-cta` button.
- JS module `serviceList` in `js/main.js` drives the cursor-follow (GSAP
  quickTo), image swap per row (`data-img`), velocity tilt, and show/hide.
- CSS under `/* ── Service list (cursor-reveal, agency style) ── */`.
- **Verified working:** 5 rows are real links, reveal follows cursor and swaps
  the right image, hides on leave, scroll-reveal fires, mobile thumbs show,
  zero horizontal overflow, clean console. (Inspired by wibify.agency/leistungen.)

---

## 5. Conventions & gotchas for the next session

- **Verify in the browser preview** after changes: `preview_start` (launch.json
  name is `vendo`, port 3456), then check console + resize mobile/desktop.
- The **preview tab backgrounds itself**, which throttles CSS transitions and
  can make `getComputedStyle` reads look mid-animation or make screenshots
  intermittently fail — not real bugs. Re-check with a delay or force state.
- **Scroll-reveal uses `once:true`** GSAP ScrollTriggers — once fired they
  self-destroy, so a later "0 triggers" reading is expected, not a failure.
- **Commit style:** message via `.git/COMMIT_MSG.txt` + `git commit -F` (avoids
  PowerShell here-string quoting issues), end with
  `Co-Authored-By: Claude Fable 5 <noreply@anthropic.com>`, then push. The
  red "git : To https://github.com..." stderr line is normal, not an error.
- **GitHub Pages subpath:** the site lives under `/vendo-digital/`, so use
  **page-relative** asset paths (never root-absolute `/images/...`). CSS `url()`
  in custom properties resolve relative to the stylesheet — burned us once on
  the team art; use inline-styled child elements for dynamic bg images.
- **gh CLI** is at `C:\Program Files\GitHub CLI\gh.exe`; PHP (for WP linting) at
  the winget path under `…\PHP.PHP.8.3_…\php.exe`. Node is available.
- **WordPress packages are out of date vs the static site** — the theme still
  mirrors the original one-page layout. If WP parity matters, that's open work.

---

## 6. Open / possible next steps

- Bring the **WordPress theme** up to the multi-page structure (it lags behind).
- Real **case-study detail pages** (results grid currently links to the grid).
- Wire the contact form / chat enquiries to a real backend if not on WordPress
  (currently mailto fallback on the static host).
- Swap any AI roster/service art for real photography if/when available.
- Optional: spare Higgsfield image variants exist in the account library.

🤖 Generated with [Claude Code](https://claude.com/claude-code)
