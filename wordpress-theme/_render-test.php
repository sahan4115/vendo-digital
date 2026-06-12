<?php
/**
 * Local render harness — NOT part of the theme.
 * Stubs just enough of WordPress (including a tiny hook system) to run
 * the theme's templates and its wp_head SEO output, so the result can be
 * loaded in a browser and validated.
 *
 * Run:  php _render-test.php > vendo/_preview.html   (cwd = this dir)
 */

define( 'ABSPATH', __DIR__ . '/' );
$GLOBALS['vendo_theme_dir'] = __DIR__ . '/vendo';
$GLOBALS['vendo_hooks']     = array();

// ── tiny hook system ─────────────────────────────────────────
function add_action( $hook, $cb, $prio = 10, $args = 1 ) { add_filter( $hook, $cb, $prio, $args ); }
function add_filter( $hook, $cb, $prio = 10, $args = 1 ) {
	$GLOBALS['vendo_hooks'][ $hook ][] = array( 'cb' => $cb, 'prio' => $prio );
}
function do_action( $hook, ...$a ) {
	if ( empty( $GLOBALS['vendo_hooks'][ $hook ] ) ) { return; }
	$cbs = $GLOBALS['vendo_hooks'][ $hook ];
	usort( $cbs, fn( $x, $y ) => $x['prio'] <=> $y['prio'] );
	foreach ( $cbs as $c ) { call_user_func_array( $c['cb'], $a ); }
}
function apply_filters( $hook, $value, ...$a ) {
	if ( empty( $GLOBALS['vendo_hooks'][ $hook ] ) ) { return $value; }
	$cbs = $GLOBALS['vendo_hooks'][ $hook ];
	usort( $cbs, fn( $x, $y ) => $x['prio'] <=> $y['prio'] );
	foreach ( $cbs as $c ) { $value = call_user_func( $c['cb'], $value, ...$a ); }
	return $value;
}

// ── escaping / i18n / sanitising / json ──────────────────────
function esc_html( $t ) { return htmlspecialchars( (string) $t, ENT_QUOTES, 'UTF-8' ); }
function esc_attr( $t ) { return htmlspecialchars( (string) $t, ENT_QUOTES, 'UTF-8' ); }
function esc_url( $t ) { return htmlspecialchars( (string) $t, ENT_QUOTES, 'UTF-8' ); }
function esc_url_raw( $t ) { return (string) $t; }
function esc_html_e( $t, $d = null ) { echo esc_html( $t ); }
function __( $t, $d = null ) { return $t; }
function sanitize_text_field( $t ) { return trim( (string) $t ); }
function sanitize_textarea_field( $t ) { return trim( (string) $t ); }
function sanitize_key( $t ) { return strtolower( preg_replace( '/[^a-z0-9_\-]/i', '', (string) $t ) ); }
function wp_unslash( $t ) { return $t; }
function wp_strip_all_tags( $t ) { return trim( strip_tags( (string) $t ) ); }
function wp_json_encode( $d, $f = 0 ) { return json_encode( $d, $f ); }

// ── conditional tags / urls ──────────────────────────────────
function is_front_page() { return true; }
function is_singular() { return false; }
function home_url( $path = '' ) { return 'https://vendodigital.co.uk' . ( $path ? $path : '' ); }
function get_permalink( $p = 0 ) { return home_url( '/' ); }
function get_the_excerpt( $p = 0 ) { return ''; }
function get_bloginfo( $k ) { return 'name' === $k ? 'Vendo Digital' : 'UTF-8'; }
function wp_get_document_title() { return apply_filters( 'pre_get_document_title', 'Vendo Digital' ); }

// ── theme / template plumbing ────────────────────────────────
function get_template_directory() { return $GLOBALS['vendo_theme_dir']; }
function get_template_directory_uri() { return '.'; }
function get_theme_mod( $key, $default = '' ) { return $default; }
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
function language_attributes() { echo 'lang="en-GB"'; }
function bloginfo( $k ) { echo 'UTF-8'; }
function body_class() { echo 'class="home"'; }
function wp_body_open() {}
function get_header() { require $GLOBALS['vendo_theme_dir'] . '/header.php'; }
function get_footer() { require $GLOBALS['vendo_theme_dir'] . '/footer.php'; }

function wp_head() {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com" />' . "\n";
	echo '  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />' . "\n";
	echo '  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Instrument+Sans:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet" />' . "\n";
	echo '  <title>' . esc_html( wp_get_document_title() ) . '</title>' . "\n";
	echo '  <link rel="stylesheet" href="assets/css/style.css" />';
	do_action( 'wp_head' ); // fires inc/seo.php output.
}
function wp_footer() {
	echo '<script>var VENDO = {"accents":["pdf","pound","deserve","numbers","read"]};</script>' . "\n";
	echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>' . "\n";
	echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>' . "\n";
	echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/0.160.0/three.min.js"></script>' . "\n";
	echo '<script src="assets/js/main.js"></script>';
}

// ── load the theme exactly as WP would ──────────────────────
require $GLOBALS['vendo_theme_dir'] . '/functions.php';
require $GLOBALS['vendo_theme_dir'] . '/front-page.php';
