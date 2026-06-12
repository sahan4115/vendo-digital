# Vendo. — UI/UX Design Agency

An award-style, highly interactive landing page for **Vendo**, a UI/UX design agency. Built with vanilla HTML, CSS and JavaScript, enhanced with **GSAP** (scroll story, page transitions, micro-interactions) and **Three.js** (a custom-shader particle field in the hero).

## Brand

Built to the official Vendo brand sheet:

| Token        | Hex       |
| ------------ | --------- |
| Vendo Green  | `#8EFEBB` |
| Vendo Black  | `#051412` |
| Vendo Sage   | `#09221F` |
| Charcoal     | `#2C2C2C` |
| Gray         | `#ABABAB` |

Type: **Manrope** (primary) + **Instrument Sans** (secondary).

## Features

- **Three.js hero** — custom-shader particle field (wave displacement, depth fog, mouse parallax). Particle count scales down on small screens, DPR capped, rendering pauses off-screen / on hidden tab.
- **GSAP choreography** — preloader with counter, line-masked hero reveal, scroll-velocity marquee, manifesto word-by-word highlight, animated stat counters, section reveals, hero parallax.
- **Interactions** — custom cursor with contextual labels, magnetic buttons, 3D-tilt cards, services accordion (mouse + keyboard), CSS case-study mockups that animate on hover, rotating CTA badge.
- **Mobile-first & accessible** — responsive breakpoints, fullscreen menu, `prefers-reduced-motion` support, graceful fallback if CDNs fail.

## Run locally

It's a static site — serve the folder with anything:

```bash
npx serve .
# or
python -m http.server 3456
```

Then open `http://localhost:3456`.

## Structure

```
index.html        # markup
css/style.css     # brand system + all styling
js/main.js        # GSAP + Three.js + interactions
index-v1.html     # earlier design exploration (kept for reference)
```
