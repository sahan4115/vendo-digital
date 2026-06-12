<?php
/**
 * Header: <head>, persistent chrome (preloader, cursor, grain, nav, menu).
 *
 * @package Vendo
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Vendo Digital is a PPC, SEO and web design agency in Sutton, Surrey. We took a dental practice from a standing start to £90K/month — no jargon, no waffle, no lock-in contracts." />
  <meta name="theme-color" content="#051412" />

  <!-- Favicon: the V. icon in Vendo Green on Vendo Black -->
  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='7' fill='%23051412'/%3E%3Ctext x='6' y='24' font-family='Arial Black,sans-serif' font-weight='900' font-size='22' fill='%238EFEBB'%3EV%3C/text%3E%3Ccircle cx='26' cy='23' r='3' fill='%238EFEBB'/%3E%3C/svg%3E" />

  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-loading>
<?php wp_body_open(); ?>

  <!-- ════════ PRELOADER ════════ -->
  <div id="preloader" aria-hidden="true">
    <div class="pre-center">
      <div class="pre-logo" id="preLogo">
        <span>V</span><span>e</span><span>n</span><span>d</span><span>o</span><span class="dot">.</span>
      </div>
      <div class="pre-meta">
        <span class="pre-tag">PPC · SEO · Web Design</span>
        <span class="pre-count" id="preCount">00</span>
      </div>
    </div>
    <div class="pre-veil"></div>
  </div>

  <!-- Custom cursor (fine pointers only) -->
  <div class="cursor" aria-hidden="true"><span class="cursor-text"></span></div>

  <!-- Film grain -->
  <div class="grain" aria-hidden="true"></div>

  <!-- ════════ NAV ════════ -->
  <header class="nav" id="nav">
    <a class="nav-logo" href="#top" aria-label="Vendo home" data-magnetic>Vendo<span class="dot">.</span></a>
    <nav class="nav-links" aria-label="Primary">
      <a href="#work" data-line>Results</a>
      <a href="#services" data-line>Services</a>
      <a href="#niches" data-line>Dental</a>
      <a href="#process" data-line>How it works</a>
    </nav>
    <a class="btn btn-nav" href="#contact" data-magnetic><span class="btn-fill"></span><span class="btn-label">Free audit</span></a>
    <button class="burger" id="burger" aria-label="Open menu" aria-expanded="false" aria-controls="menu">
      <span></span><span></span>
    </button>
  </header>

  <!-- Fullscreen menu (mobile / tablet) -->
  <div class="menu" id="menu" aria-hidden="true">
    <nav class="menu-links" aria-label="Menu">
      <a href="#work"><em>01</em><span>Results</span></a>
      <a href="#services"><em>02</em><span>Services</span></a>
      <a href="#niches"><em>03</em><span>Dental</span></a>
      <a href="#process"><em>04</em><span>How it works</span></a>
      <a href="#contact"><em>05</em><span>Free audit</span></a>
    </nav>
    <div class="menu-foot">
      <a href="mailto:<?php echo esc_attr( vendo_mod( 'email' ) ); ?>"><?php vendo_the( 'email' ); ?></a>
      <span><?php vendo_the( 'city_short' ); ?></span>
    </div>
  </div>
