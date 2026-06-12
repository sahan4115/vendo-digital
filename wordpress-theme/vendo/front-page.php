<?php
/**
 * Front page template — the Vendo Digital landing page.
 *
 * The markup below is intentionally identical to the original static
 * site: the GSAP ScrollTriggers and the Three.js canvas target these
 * exact IDs and classes, so the structure must not change.
 *
 * @package Vendo
 */

get_header();
?>

  <main id="top">

    <!-- ════════ HERO ════════ -->
    <section class="hero" id="hero">
      <canvas id="webgl" aria-hidden="true"></canvas>
      <div class="hero-inner">
        <p class="hero-eyebrow"><span class="pulse-dot"></span>Free site audit — yours in 48 hours</p>
        <h1 class="hero-title" aria-label="Marketing that pays for itself.">
          <span class="line"><span class="word">Marketing</span> <span class="word">that</span></span>
          <span class="line"><span class="word ital">pays</span>&nbsp;<span class="word green">for itself<i class="dot">.</i></span></span>
        </h1>
        <div class="hero-foot">
          <p class="hero-sub">Vendo is a PPC, SEO and web design agency in Surrey. We took a dental practice from a standing start to £90K/month. No jargon, no waffle, no lock-in contracts.</p>
          <div class="hero-ctas">
            <a class="btn btn-hero" href="#contact" data-magnetic data-cursor="Audit">
              <span class="btn-fill"></span><span class="btn-label">Get my free audit</span>
              <svg viewBox="0 0 24 24" fill="none"><path d="M7 17L17 7M17 7H9M17 7v8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <a class="hero-alt" href="#work" data-line>See client results</a>
          </div>
        </div>
      </div>
      <div class="hero-scroll" aria-hidden="true">
        <span>Scroll</span>
        <div class="scroll-track"><span class="scroll-thumb"></span></div>
      </div>
    </section>

    <!-- ════════ MARQUEE ════════ -->
    <div class="marquee" aria-hidden="true">
      <div class="marquee-track" id="marqueeTrack">
        <span>Google Ads <i>✦</i> SEO <i>✦</i> Web Design <i>✦</i> Paid Social <i>✦</i> Content &amp; Brand <i>✦</i>&nbsp;</span>
        <span>Google Ads <i>✦</i> SEO <i>✦</i> Web Design <i>✦</i> Paid Social <i>✦</i> Content &amp; Brand <i>✦</i>&nbsp;</span>
      </div>
    </div>

    <!-- ════════ MANIFESTO ════════ -->
    <section class="manifesto" id="studio">
      <div class="section-head">
        <span class="section-num">01</span>
        <span class="section-tag">Why Vendo</span>
      </div>
      <div class="manifesto-top">
        <p class="manifesto-text" id="manifestoText">Most agencies send a PDF report and hope you don't read it. We think you deserve to know what every pound did. Plans with numbers attached, and reporting humans can actually read.</p>

        <!-- Rotating V. badge: dashed orbit ring spins flat, the mark coin-spins in 3D -->
        <div class="vbadge" aria-hidden="true">
          <svg class="vbadge-ring" viewBox="0 0 200 200" fill="none">
            <circle cx="100" cy="100" r="94" stroke="rgba(142,254,187,0.35)" stroke-width="1.5" stroke-dasharray="3 9" stroke-linecap="round"/>
            <circle cx="100" cy="100" r="78" stroke="rgba(142,254,187,0.12)" stroke-width="1"/>
            <circle class="vbadge-sat" cx="100" cy="6" r="4" fill="#8EFEBB"/>
          </svg>
          <svg class="vbadge-mark" viewBox="0 0 397 384">
            <path d="M359.62,20.46l-121.37,342.18h-104.31L12.58,20.46h88.72l84.82,258.35L271.42,20.46h88.2Z" fill="#8EFEBB"/>
            <path d="M384,339.04c0,13.53-10.97,24.5-24.5,24.5s-24.5-10.97-24.5-24.5,10.97-24.5,24.5-24.5,24.5,10.97,24.5,24.5Z" fill="#8EFEBB"/>
          </svg>
        </div>
      </div>
      <div class="manifesto-meta">
        <div class="meta-card" data-tilt>
          <span class="meta-num" data-count="90">0</span><span class="meta-plus">K</span>
          <span class="meta-label">£ per month — one dental client, 12 months from a standing start</span>
        </div>
        <div class="meta-card" data-tilt>
          <span class="meta-num" data-count="7">0</span><span class="meta-plus">+</span>
          <span class="meta-label">Years growing UK businesses — est. 2019, Sutton, Surrey</span>
        </div>
        <div class="meta-card" data-tilt>
          <span class="meta-num" data-count="14">0</span><span class="meta-plus">+</span>
          <span class="meta-label">Specialists — you talk to the people running your account</span>
        </div>
      </div>
    </section>

    <!-- ════════ SELECTED WORK ════════ -->
    <section class="work" id="work">
      <div class="section-head">
        <span class="section-num">02</span>
        <span class="section-tag">Client results</span>
        <span class="section-hint">Dental · Construction · E-commerce</span>
      </div>

      <div class="work-grid">
        <!-- Case 1 -->
        <article class="case" data-cursor="Open" tabindex="0">
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
        </article>

        <!-- Case 2 -->
        <article class="case" data-cursor="Open" tabindex="0">
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
            <p>SEO and PPC with treatment-level tracking — revenue, not vanity metrics</p>
            <ul class="case-tags"><li>SEO</li><li>Google Ads</li></ul>
          </div>
        </article>

        <!-- Case 3 -->
        <article class="case" data-cursor="Open" tabindex="0">
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
            <p>Personal brand and mobile-first site for a growing dental reputation</p>
            <ul class="case-tags"><li>Brand</li><li>Web design</li></ul>
          </div>
        </article>

        <!-- Case 4 -->
        <article class="case" data-cursor="Open" tabindex="0">
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
        </article>
      </div>

      <a class="btn btn-ghost work-more" href="#contact" data-magnetic><span class="btn-fill"></span><span class="btn-label">Be the next result on this page</span></a>
    </section>

    <!-- ════════ SERVICES (interactive showcase) ════════ -->
    <section class="services" id="services">
      <div class="section-head">
        <span class="section-num">03</span>
        <span class="section-tag">Five things we're genuinely good at</span>
        <span class="section-hint">Hover to explore</span>
      </div>

      <div class="svc" id="svc">
        <!-- LEFT: service index (tabs) -->
        <div class="svc-list" id="svcList" role="tablist" aria-label="Services" aria-orientation="vertical">
          <button class="svc-item is-active" data-svc="0" role="tab" aria-selected="true" aria-controls="svc-panel-0" id="svc-tab-0">
            <span class="svc-idx">01</span>
            <span class="svc-name">Web Design</span>
            <svg class="svc-arrow" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <button class="svc-item" data-svc="1" role="tab" aria-selected="false" aria-controls="svc-panel-1" id="svc-tab-1" tabindex="-1">
            <span class="svc-idx">02</span>
            <span class="svc-name">Google Ads</span>
            <svg class="svc-arrow" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <button class="svc-item" data-svc="2" role="tab" aria-selected="false" aria-controls="svc-panel-2" id="svc-tab-2" tabindex="-1">
            <span class="svc-idx">03</span>
            <span class="svc-name">SEO</span>
            <svg class="svc-arrow" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <button class="svc-item" data-svc="3" role="tab" aria-selected="false" aria-controls="svc-panel-3" id="svc-tab-3" tabindex="-1">
            <span class="svc-idx">04</span>
            <span class="svc-name">Content &amp; Brand</span>
            <svg class="svc-arrow" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <button class="svc-item" data-svc="4" role="tab" aria-selected="false" aria-controls="svc-panel-4" id="svc-tab-4" tabindex="-1">
            <span class="svc-idx">05</span>
            <span class="svc-name">Paid Social</span>
            <svg class="svc-arrow" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <span class="svc-marker" aria-hidden="true"></span>
        </div>

        <!-- RIGHT: live preview stage -->
        <div class="svc-stage" id="svcStage">
          <span class="svc-watermark" id="svcWatermark" aria-hidden="true">01</span>

          <!-- Panel 0 — Web Design -->
          <div class="svc-panel is-active" data-panel="0" id="svc-panel-0" role="tabpanel" aria-labelledby="svc-tab-0">
            <div class="svc-visual">
              <div class="viz viz-product" aria-hidden="true">
                <div class="viz-app">
                  <div class="viz-topbar"><i></i><i></i><i></i></div>
                  <div class="viz-hero"></div>
                  <div class="viz-chips"><span></span><span class="on"></span><span></span></div>
                  <div class="viz-blocks"><b></b><b class="t"></b><b></b><b class="t on"></b></div>
                </div>
                <div class="viz-cursor"></div>
              </div>
            </div>
            <div class="svc-copy">
              <p class="svc-desc">Fast, conversion-focused websites on WordPress and Shopify. Built to turn visitors into enquiries — not just to look good in a portfolio.</p>
              <ul class="svc-tags"><li>WordPress</li><li>Shopify</li><li>Landing pages</li></ul>
            </div>
          </div>

          <!-- Panel 1 — Google Ads -->
          <div class="svc-panel" data-panel="1" id="svc-panel-1" role="tabpanel" aria-labelledby="svc-tab-1" hidden>
            <div class="svc-visual">
              <div class="viz viz-research" aria-hidden="true">
                <svg class="viz-flow" viewBox="0 0 220 150" preserveAspectRatio="xMidYMid meet">
                  <path class="viz-path" d="M24 30 H120 a18 18 0 0 1 18 18 V62 a18 18 0 0 0 18 18 H196" fill="none"/>
                  <g class="viz-node n1"><circle cx="24" cy="30" r="9"/></g>
                  <g class="viz-node n2"><circle cx="120" cy="30" r="9"/></g>
                  <g class="viz-node n3"><circle cx="156" cy="80" r="9"/></g>
                  <g class="viz-node n4"><circle cx="196" cy="80" r="9"/></g>
                </svg>
                <div class="viz-funnel"><i style="--w:100%"></i><i style="--w:72%"></i><i style="--w:48%"></i><i style="--w:31%"></i></div>
              </div>
            </div>
            <div class="svc-copy">
              <p class="svc-desc">Campaigns aimed at buyers, not browsers — led by a Head of Paid Media who used to work at Google. Weekly optimisation, transparent reporting, no minimum term.</p>
              <ul class="svc-tags"><li>Search</li><li>Shopping</li><li>Remarketing</li></ul>
            </div>
          </div>

          <!-- Panel 2 — SEO -->
          <div class="svc-panel" data-panel="2" id="svc-panel-2" role="tabpanel" aria-labelledby="svc-tab-2" hidden>
            <div class="svc-visual">
              <div class="viz viz-seo" aria-hidden="true">
                <svg class="seo-chart" viewBox="0 0 220 120" preserveAspectRatio="xMidYMid meet">
                  <path class="seo-line" d="M10 104 C 45 100, 65 78, 95 70 S 145 46, 170 32 S 200 14, 212 8" fill="none"/>
                </svg>
                <span class="seo-badge">#1</span>
                <div class="seo-pills"><i style="--w:84%"></i><i style="--w:62%"></i><i style="--w:44%"></i></div>
              </div>
            </div>
            <div class="svc-copy">
              <p class="svc-desc">Technical fixes, content and digital PR that compound month over month — reported as revenue and leads, not just rankings.</p>
              <ul class="svc-tags"><li>Technical SEO</li><li>Content</li><li>Digital PR</li></ul>
            </div>
          </div>

          <!-- Panel 3 — Content & Brand -->
          <div class="svc-panel" data-panel="3" id="svc-panel-3" role="tabpanel" aria-labelledby="svc-tab-3" hidden>
            <div class="svc-visual">
              <div class="viz viz-brand" aria-hidden="true">
                <div class="viz-mark">V<span>.</span></div>
                <div class="viz-specimen">Aa</div>
                <div class="viz-palette"><i></i><i></i><i></i><i></i></div>
              </div>
            </div>
            <div class="svc-copy">
              <p class="svc-desc">No jargon, no waffle, no hype. Credible copy and bespoke, hand-crafted logos that give your business instant recognition and trust.</p>
              <ul class="svc-tags"><li>Copywriting</li><li>Logo design</li><li>Brand</li></ul>
            </div>
          </div>

          <!-- Panel 4 — Paid Social -->
          <div class="svc-panel" data-panel="4" id="svc-panel-4" role="tabpanel" aria-labelledby="svc-tab-4" hidden>
            <div class="svc-visual">
              <div class="viz viz-motion" aria-hidden="true">
                <span class="viz-ring r1"></span>
                <span class="viz-ring r2"></span>
                <span class="viz-ring r3"></span>
                <div class="viz-play"></div>
                <div class="viz-wave"><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
              </div>
            </div>
            <div class="svc-copy">
              <p class="svc-desc">Strategic Facebook and Instagram campaigns that build genuinely engaged audiences — and turn them into measurable sales.</p>
              <ul class="svc-tags"><li>Meta ads</li><li>Creative</li><li>Audiences</li></ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ════════ NICHE FORK ════════ -->
    <section class="fork" id="niches">
      <div class="section-head">
        <span class="section-num">04</span>
        <span class="section-tag">Where we have an unfair advantage</span>
      </div>
      <div class="fork-grid">
        <a class="fork-card" href="#contact" data-tilt data-cursor="Let's talk">
          <span class="fork-icon">🦷</span>
          <h3>Run a dental practice?</h3>
          <p>From squat practices to multi-surgery groups — we fill appointment books. One client went from a standing start to £90K/month in 12 months.</p>
          <span class="fork-link">Dental marketing <svg viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </a>
        <a class="fork-card" href="#contact" data-tilt data-cursor="Let's talk">
          <span class="fork-icon">🛒</span>
          <h3>Run an online store?</h3>
          <p>Google Shopping, paid social and Shopify builds measured on the numbers that matter — ROAS, order value and repeat purchase, not vanity clicks.</p>
          <span class="fork-link">E-commerce marketing <svg viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </a>
      </div>
    </section>

    <!-- ════════ PROCESS — sticky word-stack ════════
         Three full-screen panels stack over each other on scroll.
         Each giant word fills with Vendo Green, scrubbed by scroll. -->
    <section class="flow" id="process" aria-label="How it works">
      <div class="flow-head">
        <div class="section-head">
          <span class="section-num">05</span>
          <span class="section-tag">How it works</span>
          <span class="section-hint">Three words. That's the whole process.</span>
        </div>
      </div>

      <div class="fstep" data-flow="0">
        <div class="fpanel">
          <span class="fghost" aria-hidden="true">01</span>
          <div class="fmeta">
            <span class="fkicker">First, we</span>
            <span class="findex">01 — 03</span>
          </div>
          <h3 class="fword">
            <span class="fword-outline" aria-hidden="true">Audit<i>.</i></span>
            <span class="fword-fill">Audit<i>.</i></span>
          </h3>
          <div class="frow">
            <p class="fline">Your site, ads and rankings — human-written, free, in 48 hours. <em>What's leaking, and what it's costing you.</em></p>
            <div class="fviz">
              <div class="viz viz-discover" aria-hidden="true">
                <span class="rd r1"></span><span class="rd r2"></span><span class="rd r3"></span>
                <span class="rd-sweep"></span>
                <span class="rd-dot d1"></span><span class="rd-dot d2"></span>
              </div>
            </div>
            <span class="ftime">Free · 48 hours</span>
          </div>
        </div>
      </div>

      <div class="fstep" data-flow="1">
        <div class="fpanel">
          <span class="fghost" aria-hidden="true">02</span>
          <div class="fmeta">
            <span class="fkicker">Then, we</span>
            <span class="findex">02 — 03</span>
          </div>
          <h3 class="fword">
            <span class="fword-outline" aria-hidden="true">Plan<i>.</i></span>
            <span class="fword-fill">Plan<i>.</i></span>
          </h3>
          <div class="frow">
            <p class="fline">Budgets, forecasts and the order we'd do things in. <em>Numbers attached — you'll know what every pound is for.</em></p>
            <div class="fviz">
              <div class="viz viz-define" aria-hidden="true">
                <span class="wf head"></span>
                <span class="wf"></span>
                <span class="wf short"></span>
                <div class="wf-grid"><i></i><i></i><i></i></div>
              </div>
            </div>
            <span class="ftime">Week 1–2</span>
          </div>
        </div>
      </div>

      <div class="fstep" data-flow="2">
        <div class="fpanel">
          <span class="fghost" aria-hidden="true">03</span>
          <div class="fmeta">
            <span class="fkicker">Every month, we</span>
            <span class="findex">03 — 03</span>
          </div>
          <h3 class="fword">
            <span class="fword-outline" aria-hidden="true">Report<i>.</i></span>
            <span class="fword-fill">Report<i>.</i></span>
          </h3>
          <div class="frow">
            <p class="fline">Reporting you can actually read. <em>Plain English, real revenue — and a team you can phone.</em></p>
            <div class="fviz">
              <div class="viz viz-deliver" aria-hidden="true">
                <div class="bars"><i></i><i></i><i></i><i></i></div>
                <span class="check"></span>
              </div>
            </div>
            <span class="ftime">Every month</span>
          </div>
        </div>
      </div>
    </section>

    <!-- ════════ CTA ════════ -->
    <section class="cta" id="contact">
      <div class="cta-ring" aria-hidden="true">
        <svg viewBox="0 0 200 200">
          <defs><path id="circlePath" d="M100,100 m-78,0 a78,78 0 1,1 156,0 a78,78 0 1,1 -156,0"/></defs>
          <text><textPath href="#circlePath">VENDO DIGITAL — PPC · SEO · WEB — EST. 2019 — SURREY — </textPath></text>
        </svg>
        <span class="cta-ring-dot">V.</span>
      </div>
      <h2 class="cta-title">
        <span class="line">What is your site</span>
        <span class="line"><em>leaving</em> on the table<i class="dot">?</i></span>
      </h2>
      <a class="btn btn-big" href="mailto:hello@vendodigital.co.uk?subject=Free%20site%20audit" data-magnetic data-cursor="Audit">
        <span class="btn-fill"></span><span class="btn-label">Get my free audit</span>
      </a>
      <p class="cta-note">Human-written, yours in 48 hours. We reply within one working day — or call 0207 101 4967.</p>
    </section>

  </main>

<?php
get_footer();
