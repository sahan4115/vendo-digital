<?php
/**
 * Local render harness — NOT part of the theme.
 * Stubs the WordPress functions the theme calls, then renders
 * front-page.php (which pulls in header.php / footer.php) so the
 * output can be loaded in a browser and verified.
 *
 * Run:  php _render-test.php > vendo/_preview.html   (cwd = this dir)
 */

define( 'ABSPATH', __DIR__ . '/' );
$GLOBALS['vendo_theme_dir'] = __DIR__ . '/vendo';

// ── escaping / i18n / sanitising ─────────────────────────────
function esc_html( $t ) { return htmlspecialchars( (string) $t, ENT_QUOTES, 'UTF-8' ); }
function esc_attr( $t ) { return htmlspecialchars( (string) $t, ENT_QUOTES, 'UTF-8' ); }
function esc_url( $t ) { return htmlspecialchars( (string) $t, ENT_QUOTES, 'UTF-8' ); }
function esc_html_e( $t, $d = null ) { echo esc_html( $t ); }
function __( $t, $d = null ) { return $t; }
function sanitize_text_field( $t ) { return trim( (string) $t ); }
function sanitize_textarea_field( $t ) { return trim( (string) $t ); }
function sanitize_key( $t ) { return strtolower( preg_replace( '/[^a-z0-9_\-]/i', '', (string) $t ) ); }
function wp_unslash( $t ) { return $t; }

// ── theme / template plumbing ────────────────────────────────
function get_template_directory() { return $GLOBALS['vendo_theme_dir']; }
function get_template_directory_uri() { return '.'; }
function get_theme_mod( $key, $default = '' ) { return $default; }
function add_action( ...$a ) {}
function add_filter( ...$a ) {}
function add_theme_support( ...$a ) {}
function register_nav_menus( ...$a ) {}
function register_post_type( ...$a ) {}
function add_meta_box( ...$a ) {}
function wp_enqueue_style( ...$a ) {}
function wp_enqueue_script( ...$a ) {}
function wp_localize_script( ...$a ) {}
function wp_get_theme() { return new class() { public function get( $k ) { return '1.0.0'; } }; }
function get_posts( $args = array() ) { return array(); }
function get_post_meta( $id, $key, $single = false ) { return ''; }
function get_the_title( $p = 0 ) { return ''; }
function current_user_can( ...$a ) { return true; }
function language_attributes() { echo 'lang="en"'; }
function bloginfo( $k ) { echo 'UTF-8'; }
function body_class() { echo 'class="home"'; }
function wp_body_open() {}
function get_header() { require $GLOBALS['vendo_theme_dir'] . '/header.php'; }
function get_footer() { require $GLOBALS['vendo_theme_dir'] . '/footer.php'; }

function wp_head() {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com" />' . "\n";
	echo '  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />' . "\n";
	echo '  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Instrument+Sans:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet" />' . "\n";
	echo '  <title>Vendo Digital — render test</title>' . "\n";
	echo '  <link rel="stylesheet" href="assets/css/style.css" />';
}
function wp_footer() {
	// Mirrors functions.php: localized VENDO object + scripts in dependency order.
	echo '<script>var VENDO = {"accents":["pdf","pound","deserve","numbers","read"]};</script>' . "\n";
	echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>' . "\n";
	echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>' . "\n";
	echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/0.160.0/three.min.js"></script>' . "\n";
	echo '<script src="assets/js/main.js"></script>';
}

// ── load the theme exactly as WP would ──────────────────────
require $GLOBALS['vendo_theme_dir'] . '/functions.php';
require $GLOBALS['vendo_theme_dir'] . '/front-page.php';
