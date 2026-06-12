/* Generates the five service inner pages (service-*.html) from one
   template + per-service data. Run from the repo root:
     node tools/gen-service-pages.mjs                                */
import { writeFileSync } from "fs";

/* ── reusable case cards ───────────────────── */
const CASES = {
  zen: `<article class="case" data-cursor="Open" tabindex="0">
          <div class="case-media sage">
            <div class="mock mock-app" aria-hidden="true">
              <div class="mock-bar"><i></i><i></i><i></i></div>
              <div class="mock-hero shimmer"></div>
              <div class="mock-row"><div class="mock-chip green"></div><div class="mock-chip"></div><div class="mock-chip"></div></div>
              <div class="mock-cols"><div class="mock-block"></div><div class="mock-block tall green-soft"></div><div class="mock-block"></div></div>
            </div>
          </div>
          <div class="case-info">
            <h3>Zen Dental</h3>
            <p>Practice website and Google Ads built to fill the appointment book</p>
            <ul class="case-tags"><li>Web design</li><li>Google Ads</li></ul>
          </div>
        </article>`,
  tdp: `<article class="case" data-cursor="Open" tabindex="0">
          <div class="case-media charcoal">
            <div class="mock mock-dash" aria-hidden="true">
              <div class="mock-side"><i></i><i></i><i></i><i></i></div>
              <div class="mock-main">
                <div class="mock-kpis"><span></span><span class="green-soft"></span><span></span></div>
                <div class="mock-chart">
                  <svg viewBox="0 0 200 60" preserveAspectRatio="none"><path class="chart-line" d="M0 50 C 25 48, 35 30, 55 32 S 90 44, 110 28 S 150 8, 175 14 S 195 10, 200 6" fill="none"/></svg>
                </div>
                <div class="mock-rows"><i></i><i></i><i></i></div>
              </div>
            </div>
          </div>
          <div class="case-info">
            <h3>The Dental Practice UK</h3>
            <p>SEO and PPC with treatment-level tracking</p>
            <ul class="case-tags"><li>SEO</li><li>Google Ads</li></ul>
          </div>
        </article>`,
  vivek: `<article class="case" data-cursor="Open" tabindex="0">
          <div class="case-media green">
            <div class="mock mock-mobile" aria-hidden="true">
              <div class="phone">
                <div class="phone-notch"></div>
                <div class="phone-hero"></div>
                <div class="phone-rows"><i></i><i></i></div>
                <div class="phone-cta"></div>
              </div>
              <div class="phone phone-back">
                <div class="phone-notch"></div>
                <div class="phone-rows top"><i></i><i></i><i></i></div>
              </div>
            </div>
          </div>
          <div class="case-info">
            <h3>Dr Vivek Shah</h3>
            <p>Personal brand and mobile-first site for a growing reputation</p>
            <ul class="case-tags"><li>Brand</li><li>Web design</li></ul>
          </div>
        </article>`,
  kane: `<article class="case" data-cursor="Open" tabindex="0">
          <div class="case-media sage">
            <div class="mock mock-web" aria-hidden="true">
              <div class="mock-bar"><i></i><i></i><i></i></div>
              <div class="mock-nav"><span class="green-soft"></span><span></span><span></span><span></span></div>
              <div class="mock-headline"><i></i><i class="short"></i></div>
              <div class="mock-cards"><span></span><span class="green-soft"></span><span></span></div>
            </div>
          </div>
          <div class="case-info">
            <h3>Kane Construction</h3>
            <p>Web design and local SEO putting a builder top of the map pack</p>
            <ul class="case-tags"><li>Web design</li><li>Local SEO</li></ul>
          </div>
        </article>`,
  padawan: `<article class="case" data-cursor="Open" tabindex="0">
          <div class="case-media charcoal">
            <div class="mock mock-web" aria-hidden="true">
              <div class="mock-bar"><i></i><i></i><i></i></div>
              <div class="mock-nav"><span class="green-soft"></span><span></span><span></span><span></span></div>
              <div class="mock-headline"><i></i><i class="short"></i></div>
              <div class="mock-cards"><span class="green-soft"></span><span></span><span></span></div>
            </div>
          </div>
          <div class="case-info">
            <h3>Padawan Outpost</h3>
            <p>E-commerce build for a collectibles brand</p>
            <ul class="case-tags"><li>Shopify</li><li>E-commerce</li></ul>
          </div>
        </article>`,
  sherwood: `<article class="case" data-cursor="Open" tabindex="0">
          <div class="case-media sage">
            <div class="mock mock-app" aria-hidden="true">
              <div class="mock-bar"><i></i><i></i><i></i></div>
              <div class="mock-hero shimmer"></div>
              <div class="mock-row"><div class="mock-chip"></div><div class="mock-chip green"></div><div class="mock-chip"></div></div>
              <div class="mock-cols"><div class="mock-block tall green-soft"></div><div class="mock-block"></div><div class="mock-block"></div></div>
            </div>
          </div>
          <div class="case-info">
            <h3>Sherwood Park Dental</h3>
            <p>Practice site and local SEO for a neighbourhood favourite</p>
            <ul class="case-tags"><li>Web design</li><li>Local SEO</li></ul>
          </div>
        </article>`,
};

const tile = (t) =>
  `<div class="bento-tile${t.wide ? " is-wide" : ""}${t.accent ? " is-accent" : ""}"${t.href ? ` onclick="location.href='${t.href}'" style="cursor:pointer"` : ""}>
          ${t.ico ? `<span class="b-ico">${t.ico}</span>` : ""}
          <h3>${t.h}</h3>
          <p>${t.p}</p>
        </div>`;

const tstep = (s, i) =>
  `<div class="tstep">
          <span class="t-kick">Step 0${i + 1} — ${s.kick}</span>
          <h3>${s.h}</h3>
          <p>${s.p}</p>
        </div>`;

const faqItem = (f, i) =>
  `<div class="faq2-item">
            <button class="faq2-q" aria-expanded="false">
              <span class="faq2-idx">0${i + 1}</span>
              <strong>${f.q}</strong>
              <span class="faq2-ico" aria-hidden="true"></span>
            </button>
            <div class="faq2-a"><div><p>${f.a}</p></div></div>
          </div>`;

const page = (d) => `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>${d.title} — Vendo Digital</title>
  <meta name="description" content="${d.desc}" />
  <meta name="theme-color" content="#051412" />
  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='7' fill='%23051412'/%3E%3Ctext x='6' y='24' font-family='Arial Black,sans-serif' font-weight='900' font-size='22' fill='%238EFEBB'%3EV%3C/text%3E%3Ccircle cx='26' cy='23' r='3' fill='%238EFEBB'/%3E%3C/svg%3E" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Instrument+Sans:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/chat.css" />
</head>
<body>

  <div class="page-veil" aria-hidden="true"></div>
  <div class="cursor" aria-hidden="true"><span class="cursor-text"></span></div>
  <div class="grain" aria-hidden="true"></div>

  <header class="nav" id="nav">
    <a class="nav-logo" href="index.html" aria-label="Vendo home" data-magnetic>Vendo<span class="dot">.</span></a>
    <nav class="nav-links" aria-label="Primary">
      <a href="results.html" data-line>Results</a>
      <a href="services.html" class="is-active" data-line>Services</a>
      <a href="dental.html" data-line>Dental</a>
      <a href="studio.html" data-line>Studio</a>
    </nav>
    <a class="btn btn-nav" href="contact.html" data-magnetic><span class="btn-fill"></span><span class="btn-label">Free audit</span></a>
    <button class="burger" id="burger" aria-label="Open menu" aria-expanded="false" aria-controls="menu">
      <span></span><span></span>
    </button>
  </header>

  <div class="menu" id="menu" aria-hidden="true">
    <nav class="menu-links" aria-label="Menu">
      <a href="results.html"><em>01</em><span>Results</span></a>
      <a href="services.html"><em>02</em><span>Services</span></a>
      <a href="dental.html"><em>03</em><span>Dental</span></a>
      <a href="studio.html"><em>04</em><span>Studio</span></a>
      <a href="contact.html"><em>05</em><span>Free audit</span></a>
    </nav>
    <div class="menu-foot">
      <a href="mailto:hello@vendodigital.co.uk">hello@vendodigital.co.uk</a>
      <span>Sutton, Surrey</span>
    </div>
  </div>

  <main id="top">

    <section class="page-hero">
      <div class="hero-inner">
        <p class="hero-eyebrow"><span class="pulse-dot"></span>${d.eyebrow}</p>
        <h1 class="hero-title" aria-label="${d.ariaTitle}">
          <span class="line">${d.line1.map((w) => `<span class="word">${w}</span>`).join(" ")}</span>
          <span class="line"><span class="word ital">${d.ital}</span>&nbsp;<span class="word green">${d.green}<i class="dot">.</i></span></span>
        </h1>
        <p class="hero-sub">${d.sub}</p>
      </div>
    </section>

    <div class="pull-band">
      <p>${d.pull}</p>
    </div>

    <section class="manifesto">
      <div class="section-head">
        <span class="section-num">01</span>
        <span class="section-tag">What you get</span>
      </div>
      <div class="bento">
        ${d.bento.map(tile).join("\n        ")}
      </div>
    </section>

    <section class="manifesto">
      <div class="section-head">
        <span class="section-num">02</span>
        <span class="section-tag">How it runs</span>
      </div>
      <div class="tline">
        <div class="tline-rail"><div class="tline-fill"></div></div>
        ${d.steps.map(tstep).join("\n        ")}
      </div>
    </section>

    <section class="manifesto">
      <div class="faq2">
        <div class="faq2-intro">
          <div class="section-head" style="margin-bottom: 18px;">
            <span class="section-num">03</span>
            <span class="section-tag">FAQ</span>
          </div>
          <h2>Asked <em>before</em> you have to.</h2>
          <p>Straight answers — and anything else, ask Venny in the corner or just call.</p>
          <a class="btn btn-ghost" href="tel:02071014967" data-magnetic><span class="btn-fill"></span><span class="btn-label">0207 101 4967</span></a>
        </div>
        <div class="faq2-list">
          ${d.faq.map(faqItem).join("\n          ")}
        </div>
      </div>
    </section>

    <section class="work">
      <div class="section-head">
        <span class="section-num">04</span>
        <span class="section-tag">Related work</span>
        <span class="section-hint"><a href="results.html" data-line>All results →</a></span>
      </div>
      <div class="work-grid">
        ${d.cases.map((c) => CASES[c]).join("\n        ")}
      </div>
    </section>

    <section class="cta">
      <h2 class="cta-title">
        <span class="line">${d.ctaL1}</span>
        <span class="line">${d.ctaL2}</span>
      </h2>
      <a class="btn btn-big" href="contact.html" data-magnetic data-cursor="Audit">
        <span class="btn-fill"></span><span class="btn-label">${d.ctaBtn}</span>
      </a>
      <p class="cta-note">Free, human-written, yours in 48 hours. We reply within one working day.</p>
    </section>

  </main>

  <footer class="footer">
    <div class="footer-top">
      <span class="footer-logo" aria-hidden="true">Vendo<span class="dot">.</span></span>
    </div>
    <div class="footer-grid">
      <div class="f-col">
        <span class="f-head">Sitemap</span>
        <a href="results.html" data-line>Results</a>
        <a href="services.html" data-line>Services</a>
        <a href="dental.html" data-line>Dental</a>
        <a href="studio.html" data-line>Studio</a>
        <a href="contact.html" data-line>Free audit</a>
      </div>
      <div class="f-col">
        <span class="f-head">Social</span>
        <a href="https://www.instagram.com/vendo_digital/" data-line>Instagram</a>
        <a href="#" data-line>Facebook</a>
        <a href="https://uk.linkedin.com/company/vendo-digital-ltd" data-line>LinkedIn</a>
        <a href="#" data-line>YouTube</a>
      </div>
      <div class="f-col">
        <span class="f-head">Office</span>
        <span class="f-text">5 Sandiford Road<br/>Sutton, Surrey SM3 9RN</span>
        <a href="mailto:hello@vendodigital.co.uk" data-line>hello@vendodigital.co.uk</a>
        <a href="tel:02071014967" data-line>0207 101 4967</a>
      </div>
    </div>
    <div class="footer-bar">
      <span>© 2026 Vendo Digital Ltd.</span>
      <span>www.vendodigital.co.uk</span>
      <button class="to-top" id="toTop" data-magnetic>Back to top ↑</button>
    </div>
  </footer>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>
  <script src="js/main.js" defer></script>
  <script src="js/chat.js" defer></script>
</body>
</html>
`;

/* ── per-service data ──────────────────────── */
const SERVICES = {
  "service-web-design.html": {
    title: "Web Design",
    desc: "Conversion-focused WordPress and Shopify websites from Vendo Digital — fast, mobile-first and built to turn visitors into enquiries.",
    eyebrow: "Service 01 · Web design",
    ariaTitle: "Websites built to sell.",
    line1: ["Websites", "built"],
    ital: "to",
    green: "sell",
    sub: "Fast, mobile-first sites on WordPress and Shopify, designed to turn visitors into enquiries — with analytics wired in from day one so you can prove it.",
    pull: "A website that doesn't convert is just <b>expensive brochure-ware</b>. Ours are built to earn their keep.",
    bento: [
      { ico: "🧭", h: "Design & build", p: "Bespoke design on WordPress or Shopify — no off-the-shelf templates wearing a trench coat." },
      { ico: "📱", h: "Mobile-first", p: "Most of your visitors are on a phone. Every layout starts there, not as an afterthought." },
      { ico: "⚡", h: "Built for speed", p: "Fast pages keep visitors and please Google. We treat load time as a feature." },
      { ico: "📊", h: "Tracking wired in", p: "Analytics and conversion tracking configured at launch — you'll see exactly what the site does for you." },
      { ico: "🔎", h: "SEO-ready foundations", p: "Clean structure, fast pages and proper markup, so your rankings start on the front foot." },
      { accent: true, h: "Built to convert.", p: "Get the free audit and see what your current site is leaving on the table.", href: "contact.html" },
    ],
    steps: [
      { kick: "Scope", h: "Sitemap & goals", p: "We map what the site needs to do — the pages, the journeys, and the one action each page exists to drive." },
      { kick: "Design", h: "Look, feel, flow", p: "Design that fits your brand and your buyers, reviewed with you before a line of code is written." },
      { kick: "Build", h: "WordPress or Shopify", p: "Responsive build, fast load times, and the boring-but-vital things: forms that work, tracking that fires." },
      { kick: "Launch", h: "Measure & improve", p: "We launch with analytics in place and report what visitors actually do — then improve it." },
    ],
    faq: [
      { q: "Which platforms do you build on?", a: "WordPress for most business sites, Shopify for stores. Both leave you with a site your team can edit without calling a developer for every comma." },
      { q: "Can you redesign our existing site?", a: "Yes — the free audit looks at what's worth keeping, what's hurting you, and whether a refresh or rebuild is the better investment." },
      { q: "Who writes the content?", a: "Our in-house content writers, with you. No jargon, no waffle — words that get straight to the point." },
      { q: "Will we be able to update it ourselves?", a: "That's the point of building on WordPress and Shopify. You get a clean editing experience and a handover walkthrough." },
    ],
    cases: ["kane", "zen"],
    ctaL1: "Your site should be",
    ctaL2: 'your best <em>salesperson</em><i class="dot">.</i>',
    ctaBtn: "Audit my website",
  },

  "service-google-ads.html": {
    title: "Google Ads",
    desc: "Google Ads management from Vendo Digital — Search, Shopping and remarketing campaigns led by a Head of Paid Media who used to work at Google.",
    eyebrow: "Service 02 · Google Ads",
    ariaTitle: "Ads that earn it back.",
    line1: ["Ads", "that"],
    ital: "earn",
    green: "it back",
    sub: "Search, Shopping and remarketing campaigns that connect you with people actively looking for what you sell — led by a Head of Paid Media who used to work at Google.",
    pull: "Clicks are easy to buy. <b>Customers</b> are what we're paid to find.",
    bento: [
      { ico: "🔍", h: "Search campaigns", p: "Your ads in front of people typing exactly what you sell — structured around buyer intent." },
      { ico: "🛒", h: "Shopping campaigns", p: "Product feeds tuned so your items show up looking right, priced right, at the right moment." },
      { ico: "🔁", h: "Remarketing", p: "Most visitors don't buy first time. We bring the warm ones back without stalking the cold ones." },
      { ico: "🗓️", h: "Weekly optimisation", p: "Bids, budgets, negatives and copy reviewed every week — not set-and-forget." },
      { ico: "📖", h: "Reporting you can read", p: "Plain-English monthly reports tied to enquiries and revenue, not impressions." },
      { accent: true, h: "Ex-Google leadership.", p: "Your campaigns are led by a Head of Paid Media who used to work at Google.", href: "contact.html" },
    ],
    steps: [
      { kick: "Audit", h: "Find the leaks", p: "We audit your account (or your market, if you're starting fresh): wasted spend, missed searches, weak landing pages." },
      { kick: "Restructure", h: "Build it right", p: "Campaigns structured around your treatments, products or services — with tracking that ties spend to outcomes." },
      { kick: "Launch", h: "Go live, watched", p: "Launch with conservative budgets and tight negatives, then loosen as the data earns it." },
      { kick: "Optimise", h: "Every week, forever", p: "Weekly optimisation and honest reporting. If something isn't working, you'll hear it from us first." },
    ],
    faq: [
      { q: "What budget do we need?", a: "It depends on your market and competition, so we scope it after the free audit rather than guessing. We'd rather tell you a number we believe than one you want to hear." },
      { q: "Is there a minimum term?", a: "No minimum term and no lock-in contracts. We keep clients by performing." },
      { q: "How fast will we see results?", a: "Ads can be live within days; meaningful optimisation takes a few weeks of data. The audit sets expectations for your specific market." },
      { q: "Who actually manages the account?", a: "The specialists themselves — including a dedicated dental PPC account manager for practices. No account-manager layer." },
    ],
    cases: ["zen", "tdp"],
    ctaL1: "Stop paying for",
    ctaL2: 'the wrong <em>clicks</em><i class="dot">.</i>',
    ctaBtn: "Audit my ads",
  },

  "service-seo.html": {
    title: "SEO",
    desc: "SEO from Vendo Digital — technical fixes, content and digital PR that compound month over month, reported as revenue and leads, not just rankings.",
    eyebrow: "Service 03 · SEO",
    ariaTitle: "Be found first.",
    line1: ["Be", "found"],
    ital: "first,",
    green: "not last",
    sub: "Technical fixes, content and digital PR that build your organic presence month over month — reported as revenue and leads, not just rankings.",
    pull: "Position three for the <b>right phrase</b> beats position one for the wrong one.",
    bento: [
      { ico: "🔧", h: "Technical SEO", p: "Speed, structure, indexing and markup — the foundations Google rewards and visitors never notice." },
      { ico: "✍️", h: "Content that ranks", p: "Pages and articles written for the searches your buyers actually make, by our in-house writers." },
      { ico: "📰", h: "Digital PR & outreach", p: "Links earned from real publications — the authority signals that move competitive rankings." },
      { ico: "📍", h: "Local SEO", p: "Map-pack visibility for 'near me' searches — the highest-intent traffic a local business can get." },
      { ico: "🧠", h: "Keyword strategy", p: "We target phrases by revenue potential, not vanity volume." },
      { accent: true, h: "Reported as revenue.", p: "Rankings are the means. Leads and revenue are the report.", href: "contact.html" },
    ],
    steps: [
      { kick: "Audit", h: "Technical deep-dive", p: "Everything that's holding you back: crawl issues, speed, structure, content gaps, and what competitors rank for that you don't." },
      { kick: "Fix", h: "Foundations first", p: "We fix the technical issues before chasing new rankings — otherwise you're pouring water into a leaky bucket." },
      { kick: "Create", h: "The content engine", p: "A steady rhythm of pages and articles targeting the searches that drive enquiries." },
      { kick: "Earn", h: "Authority building", p: "Digital PR and outreach earn the links that turn good content into ranking content. Compounds every month." },
    ],
    faq: [
      { q: "How long until we see results?", a: "SEO compounds: early technical wins can move quickly, competitive rankings take months. The audit gives you an honest timeline for your market — anyone promising page one in a week is selling something else." },
      { q: "Do you guarantee rankings?", a: "No — and you should run from anyone who does. We guarantee the work, full transparency, and reporting you can hold us to." },
      { q: "What does the reporting look like?", a: "Plain-English monthly reports tied to traffic, leads and revenue. Rankings are in there too — as context, not the headline." },
      { q: "Do you do local SEO?", a: "Yes — map-pack and 'near me' visibility is core to what we do for practices, trades and local businesses." },
    ],
    cases: ["kane", "sherwood"],
    ctaL1: "Own the searches",
    ctaL2: 'that <em>matter</em><i class="dot">.</i>',
    ctaBtn: "Audit my rankings",
  },

  "service-content-brand.html": {
    title: "Content & Brand",
    desc: "Copywriting, blog writing and logo design from Vendo Digital — no jargon, no waffle, no hype. Credible words and bespoke identities.",
    eyebrow: "Service 04 · Content & brand",
    ariaTitle: "Words that work.",
    line1: ["Words", "that"],
    ital: "actually",
    green: "work",
    sub: "No jargon, no waffle, no hype — credible, powerful words that get straight to the point, plus bespoke hand-crafted logos that make you instantly recognisable.",
    pull: "If you sound like <b>everyone else</b> in your industry, price is all that's left to compete on.",
    bento: [
      { ico: "🖋️", h: "Copywriting", p: "Website copy, landing pages and campaigns — written to persuade, not to fill space." },
      { ico: "📚", h: "Blog & content writing", p: "Articles that answer real customer questions and feed your SEO at the same time." },
      { ico: "⭐", h: "Logo design", p: "Bespoke, hand-crafted logos with a clear sense of credibility — never clip-art." },
      { ico: "🎨", h: "Brand guidelines", p: "Colours, type and usage rules so everything you put out looks like it came from the same company." },
      { ico: "🗣️", h: "Tone of voice", p: "How you sound, written down — so every email, ad and page speaks the same language." },
      { accent: true, h: "No jargon. No waffle. No hype.", p: "That's the house style. It's also the pitch.", href: "contact.html" },
    ],
    steps: [
      { kick: "Discover", h: "Find your voice", p: "Who you are, who you're for, and what makes you worth choosing — said plainly." },
      { kick: "Create", h: "Write & design", p: "Copy and identity work drafted by our in-house writers and designers, grounded in the discovery." },
      { kick: "Refine", h: "Together", p: "We refine with you — collaborative rounds until it sounds like you on your best day." },
      { kick: "Roll out", h: "Everywhere it matters", p: "Website, ads, social, print — delivered with guidelines so it stays consistent without us." },
    ],
    faq: [
      { q: "Do you only do logos?", a: "No — logo design sits inside full identity work: colours, type, tone of voice and guidelines. A logo alone is a hat without an outfit." },
      { q: "Who writes the content?", a: "Our in-house content writers — the same team behind our clients' websites, blogs and digital PR campaigns." },
      { q: "How do revisions work?", a: "Collaboratively. We'd rather refine with you in rounds than disappear for a month and unveil something you didn't ask for." },
      { q: "Can you match our existing brand?", a: "Yes — if your identity works, we write and design within it. The audit will tell you honestly if it's working against you." },
    ],
    cases: ["vivek", "padawan"],
    ctaL1: "Say it better than",
    ctaL2: 'your <em>competitors</em><i class="dot">.</i>',
    ctaBtn: "Start with the free audit",
  },

  "service-paid-social.html": {
    title: "Paid Social",
    desc: "Paid social from Vendo Digital — strategic Facebook and Instagram campaigns that build engaged audiences and turn attention into measurable sales.",
    eyebrow: "Service 05 · Paid social",
    ariaTitle: "Ads worth stopping for.",
    line1: ["Ads", "worth"],
    ital: "stopping",
    green: "for",
    sub: "Strategic Facebook and Instagram campaigns that build genuinely engaged audiences — and turn that attention into measurable sales, not just likes.",
    pull: "Likes don't pay invoices. <b>Measurable sales</b> do.",
    bento: [
      { ico: "📣", h: "Meta ads", p: "Facebook and Instagram campaigns built around your goals — reach, leads or direct sales." },
      { ico: "🎬", h: "Creative that converts", p: "Scroll-stopping visuals and copy, tested against each other instead of argued about." },
      { ico: "👥", h: "Audience building", p: "Finding the people most likely to buy — and the lookalikes of your best customers." },
      { ico: "🔁", h: "Retargeting", p: "Warm audiences brought back at the right moment with the right message." },
      { ico: "🧪", h: "Test & learn", p: "Structured testing of creative, audiences and offers — the budget follows the winners." },
      { accent: true, h: "Measured on sales.", p: "Engagement is a means. Revenue is the measure.", href: "contact.html" },
    ],
    steps: [
      { kick: "Research", h: "Know the audience", p: "Who buys, why they buy, and what they need to see before they will — mapped before any spend." },
      { kick: "Create", h: "Creative & launch", p: "Ad creative and copy produced in-house, launched across structured test campaigns." },
      { kick: "Test", h: "Let the data argue", p: "Creative and audiences tested head-to-head; opinions lose, results win." },
      { kick: "Scale", h: "Back the winners", p: "Budget shifts to what's converting, with plain-English reporting on what every pound returned." },
    ],
    faq: [
      { q: "Which platforms do you run?", a: "Primarily Meta — Facebook and Instagram — where targeting and intent data are strongest for our clients' markets." },
      { q: "Do you make the ad creative?", a: "Yes — visuals and copy in-house, tested in structured experiments rather than chosen by committee." },
      { q: "How is success measured?", a: "Leads and sales tracked end to end, reported monthly in plain English. Engagement metrics are context, not the headline." },
      { q: "Does this work alongside Google Ads?", a: "Very well — social builds demand and retargets browsers, search captures people ready to buy. The audit shows the right mix for you." },
    ],
    cases: ["padawan", "tdp"],
    ctaL1: "Turn attention",
    ctaL2: 'into <em>revenue</em><i class="dot">.</i>',
    ctaBtn: "Audit my social ads",
  },
};

for (const [file, data] of Object.entries(SERVICES)) {
  writeFileSync(new URL("../" + file, import.meta.url), page(data));
  console.log("wrote", file);
}
