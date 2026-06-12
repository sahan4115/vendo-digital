<?php
/**
 * Front page template — the Vendo Digital landing page.
 *
 * All copy comes from the Customizer (Appearance → Customize → Vendo —
 * Page Content); the Client Results grid comes from the Case Studies
 * post type. The DOM structure is identical to the original static
 * site — the GSAP ScrollTriggers and Three.js canvas target these
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
        <p class="hero-eyebrow"><span class="pulse-dot"></span><?php vendo_the( 'hero_eyebrow' ); ?></p>
        <h1 class="hero-title" aria-label="<?php echo esc_attr( vendo_mod( 'hero_l1' ) . ' ' . vendo_mod( 'hero_l2_ital' ) . ' ' . vendo_mod( 'hero_l2_green' ) . '.' ); ?>">
          <span class="line"><?php echo vendo_words( vendo_mod( 'hero_l1' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- helper escapes. ?></span>
          <span class="line"><span class="word ital"><?php vendo_the( 'hero_l2_ital' ); ?></span>&nbsp;<span class="word green"><?php vendo_the( 'hero_l2_green' ); ?><i class="dot">.</i></span></span>
        </h1>
        <div class="hero-foot">
          <p class="hero-sub"><?php vendo_the( 'hero_sub' ); ?></p>
          <div class="hero-ctas">
            <a class="btn btn-hero" href="#contact" data-magnetic data-cursor="Audit">
              <span class="btn-fill"></span><span class="btn-label"><?php vendo_the( 'hero_cta' ); ?></span>
              <svg viewBox="0 0 24 24" fill="none"><path d="M7 17L17 7M17 7H9M17 7v8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <a class="hero-alt" href="#work" data-line><?php vendo_the( 'hero_alt' ); ?></a>
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
        <span><?php echo vendo_marquee_html(); // phpcs:ignore WordPress.Security.EscapeOutput -- helper escapes. ?></span>
        <span><?php echo vendo_marquee_html(); // phpcs:ignore WordPress.Security.EscapeOutput -- helper escapes. ?></span>
      </div>
    </div>

    <!-- ════════ MANIFESTO ════════ -->
    <section class="manifesto" id="studio">
      <div class="section-head">
        <span class="section-num">01</span>
        <span class="section-tag"><?php vendo_the( 'manifesto_tag' ); ?></span>
      </div>
      <div class="manifesto-top">
        <p class="manifesto-text" id="manifestoText"><?php vendo_the( 'manifesto_text' ); ?></p>

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
        <?php for ( $s = 1; $s <= 3; $s++ ) : ?>
        <div class="meta-card" data-tilt>
          <span class="meta-num" data-count="<?php echo esc_attr( vendo_mod( 'stat' . $s . '_num' ) ); ?>">0</span><span class="meta-plus"><?php vendo_the( 'stat' . $s . '_suffix' ); ?></span>
          <span class="meta-label"><?php vendo_the( 'stat' . $s . '_label' ); ?></span>
        </div>
        <?php endfor; ?>
      </div>
    </section>

    <!-- ════════ CLIENT RESULTS ════════ -->
    <section class="work" id="work">
      <div class="section-head">
        <span class="section-num">02</span>
        <span class="section-tag"><?php vendo_the( 'work_tag' ); ?></span>
        <span class="section-hint"><?php vendo_the( 'work_hint' ); ?></span>
      </div>

      <div class="work-grid">
        <?php
        foreach ( vendo_get_cases() as $vendo_case ) {
          vendo_render_case( $vendo_case );
        }
        ?>
      </div>

      <a class="btn btn-ghost work-more" href="#contact" data-magnetic><span class="btn-fill"></span><span class="btn-label"><?php vendo_the( 'work_more' ); ?></span></a>
    </section>

    <!-- ════════ SERVICES (interactive showcase) ════════ -->
    <section class="services" id="services">
      <div class="section-head">
        <span class="section-num">03</span>
        <span class="section-tag"><?php vendo_the( 'services_tag' ); ?></span>
        <span class="section-hint">Hover to explore</span>
      </div>

      <div class="svc" id="svc">
        <!-- LEFT: service index (tabs) -->
        <div class="svc-list" id="svcList" role="tablist" aria-label="Services" aria-orientation="vertical">
          <?php for ( $s = 1; $s <= 5; $s++ ) : $idx = $s - 1; ?>
          <button class="svc-item<?php echo 0 === $idx ? ' is-active' : ''; ?>" data-svc="<?php echo esc_attr( $idx ); ?>" role="tab" aria-selected="<?php echo 0 === $idx ? 'true' : 'false'; ?>" aria-controls="svc-panel-<?php echo esc_attr( $idx ); ?>" id="svc-tab-<?php echo esc_attr( $idx ); ?>"<?php echo 0 === $idx ? '' : ' tabindex="-1"'; ?>>
            <span class="svc-idx"><?php echo esc_html( str_pad( $s, 2, '0', STR_PAD_LEFT ) ); ?></span>
            <span class="svc-name"><?php vendo_the( 'svc' . $s . '_name' ); ?></span>
            <svg class="svc-arrow" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <?php endfor; ?>
          <span class="svc-marker" aria-hidden="true"></span>
        </div>

        <!-- RIGHT: live preview stage (visuals fixed per panel; copy editable) -->
        <div class="svc-stage" id="svcStage">
          <span class="svc-watermark" id="svcWatermark" aria-hidden="true">01</span>

          <!-- Panel 0 -->
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
              <p class="svc-desc"><?php vendo_the( 'svc1_desc' ); ?></p>
              <ul class="svc-tags"><?php vendo_tag_list( 'svc1_tags' ); ?></ul>
            </div>
          </div>

          <!-- Panel 1 -->
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
              <p class="svc-desc"><?php vendo_the( 'svc2_desc' ); ?></p>
              <ul class="svc-tags"><?php vendo_tag_list( 'svc2_tags' ); ?></ul>
            </div>
          </div>

          <!-- Panel 2 -->
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
              <p class="svc-desc"><?php vendo_the( 'svc3_desc' ); ?></p>
              <ul class="svc-tags"><?php vendo_tag_list( 'svc3_tags' ); ?></ul>
            </div>
          </div>

          <!-- Panel 3 -->
          <div class="svc-panel" data-panel="3" id="svc-panel-3" role="tabpanel" aria-labelledby="svc-tab-3" hidden>
            <div class="svc-visual">
              <div class="viz viz-brand" aria-hidden="true">
                <div class="viz-mark">V<span>.</span></div>
                <div class="viz-specimen">Aa</div>
                <div class="viz-palette"><i></i><i></i><i></i><i></i></div>
              </div>
            </div>
            <div class="svc-copy">
              <p class="svc-desc"><?php vendo_the( 'svc4_desc' ); ?></p>
              <ul class="svc-tags"><?php vendo_tag_list( 'svc4_tags' ); ?></ul>
            </div>
          </div>

          <!-- Panel 4 -->
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
              <p class="svc-desc"><?php vendo_the( 'svc5_desc' ); ?></p>
              <ul class="svc-tags"><?php vendo_tag_list( 'svc5_tags' ); ?></ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ════════ NICHE FORK ════════ -->
    <section class="fork" id="niches">
      <div class="section-head">
        <span class="section-num">04</span>
        <span class="section-tag"><?php vendo_the( 'fork_tag' ); ?></span>
      </div>
      <div class="fork-grid">
        <?php for ( $s = 1; $s <= 2; $s++ ) : ?>
        <a class="fork-card" href="<?php echo esc_url( vendo_mod( 'fork' . $s . '_url' ) ); ?>" data-tilt data-cursor="Let's talk">
          <span class="fork-icon"><?php vendo_the( 'fork' . $s . '_icon' ); ?></span>
          <h3><?php vendo_the( 'fork' . $s . '_title' ); ?></h3>
          <p><?php vendo_the( 'fork' . $s . '_text' ); ?></p>
          <span class="fork-link"><?php vendo_the( 'fork' . $s . '_link' ); ?> <svg viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </a>
        <?php endfor; ?>
      </div>
    </section>

    <!-- ════════ PROCESS — sticky word-stack ════════ -->
    <section class="flow" id="process" aria-label="How it works">
      <div class="flow-head">
        <div class="section-head">
          <span class="section-num">05</span>
          <span class="section-tag">How it works</span>
          <span class="section-hint"><?php vendo_the( 'flow_hint' ); ?></span>
        </div>
      </div>

      <?php
      $vendo_flow_viz = array(
        1 => '<div class="viz viz-discover" aria-hidden="true"><span class="rd r1"></span><span class="rd r2"></span><span class="rd r3"></span><span class="rd-sweep"></span><span class="rd-dot d1"></span><span class="rd-dot d2"></span></div>',
        2 => '<div class="viz viz-define" aria-hidden="true"><span class="wf head"></span><span class="wf"></span><span class="wf short"></span><div class="wf-grid"><i></i><i></i><i></i></div></div>',
        3 => '<div class="viz viz-deliver" aria-hidden="true"><div class="bars"><i></i><i></i><i></i><i></i></div><span class="check"></span></div>',
      );
      for ( $s = 1; $s <= 3; $s++ ) :
        $vendo_pad = str_pad( $s, 2, '0', STR_PAD_LEFT );
      ?>
      <div class="fstep" data-flow="<?php echo esc_attr( $s - 1 ); ?>">
        <div class="fpanel">
          <span class="fghost" aria-hidden="true"><?php echo esc_html( $vendo_pad ); ?></span>
          <div class="fmeta">
            <span class="fkicker"><?php vendo_the( 'flow' . $s . '_kicker' ); ?></span>
            <span class="findex"><?php echo esc_html( $vendo_pad ); ?> — 03</span>
          </div>
          <h3 class="fword">
            <span class="fword-outline" aria-hidden="true"><?php vendo_the( 'flow' . $s . '_word' ); ?><i>.</i></span>
            <span class="fword-fill"><?php vendo_the( 'flow' . $s . '_word' ); ?><i>.</i></span>
          </h3>
          <div class="frow">
            <p class="fline"><?php vendo_the( 'flow' . $s . '_line' ); ?> <em><?php vendo_the( 'flow' . $s . '_em' ); ?></em></p>
            <div class="fviz">
              <?php echo $vendo_flow_viz[ $s ]; // phpcs:ignore WordPress.Security.EscapeOutput -- static markup above. ?>
            </div>
            <span class="ftime"><?php vendo_the( 'flow' . $s . '_time' ); ?></span>
          </div>
        </div>
      </div>
      <?php endfor; ?>
    </section>

    <!-- ════════ CTA ════════ -->
    <section class="cta" id="contact">
      <div class="cta-ring" aria-hidden="true">
        <svg viewBox="0 0 200 200">
          <defs><path id="circlePath" d="M100,100 m-78,0 a78,78 0 1,1 156,0 a78,78 0 1,1 -156,0"/></defs>
          <text><textPath href="#circlePath"><?php vendo_the( 'cta_ring' ); ?></textPath></text>
        </svg>
        <span class="cta-ring-dot">V.</span>
      </div>
      <h2 class="cta-title">
        <span class="line"><?php vendo_the( 'cta_l1' ); ?></span>
        <span class="line"><em><?php vendo_the( 'cta_em' ); ?></em> <?php vendo_the( 'cta_l2' ); ?><i class="dot">?</i></span>
      </h2>
      <a class="btn btn-big" href="mailto:<?php echo esc_attr( vendo_mod( 'email' ) ); ?>?subject=Free%20site%20audit" data-magnetic data-cursor="Audit">
        <span class="btn-fill"></span><span class="btn-label"><?php vendo_the( 'cta_btn' ); ?></span>
      </a>
      <p class="cta-note"><?php vendo_the( 'cta_note' ); ?></p>
    </section>

  </main>

<?php
get_footer();
