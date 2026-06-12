<?php
/**
 * Coexistence test: load the PLUGIN and the THEME's inc/seo.php in the
 * same process — exactly the combination that caused the fatal
 * "Cannot redeclare" error. Then verify the theme stands down and only
 * the plugin outputs.
 */
define( 'ABSPATH', __DIR__ . '/' );
$GLOBALS['hooks'] = array();
function add_action( $h, $cb, $p = 10, $a = 1 ) { add_filter( $h, $cb, $p, $a ); }
function add_filter( $h, $cb, $p = 10, $a = 1 ) { $GLOBALS['hooks'][ $h ][] = array( 'cb' => $cb, 'p' => $p ); }
function do_action( $h, ...$a ) { if ( empty( $GLOBALS['hooks'][ $h ] ) ) { return; } $c = $GLOBALS['hooks'][ $h ]; usort( $c, fn( $x, $y ) => $x['p'] <=> $y['p'] ); foreach ( $c as $f ) { call_user_func_array( $f['cb'], $a ); } }
function apply_filters( $h, $v, ...$a ) { if ( empty( $GLOBALS['hooks'][ $h ] ) ) { return $v; } $c = $GLOBALS['hooks'][ $h ]; usort( $c, fn( $x, $y ) => $x['p'] <=> $y['p'] ); foreach ( $c as $f ) { $v = call_user_func( $f['cb'], $v, ...$a ); } return $v; }
function esc_html( $t ) { return htmlspecialchars( (string) $t, ENT_QUOTES, 'UTF-8' ); }
function esc_attr( $t ) { return htmlspecialchars( (string) $t, ENT_QUOTES, 'UTF-8' ); }
function esc_url( $t ) { return htmlspecialchars( (string) $t, ENT_QUOTES, 'UTF-8' ); }
function esc_url_raw( $t ) { return (string) $t; }
function esc_textarea( $t ) { return htmlspecialchars( (string) $t, ENT_QUOTES, 'UTF-8' ); }
function esc_html__( $t, $d = null ) { return $t; }
function esc_html_e( $t, $d = null ) { echo $t; }
function __( $t, $d = null ) { return $t; }
function sanitize_text_field( $t ) { return trim( (string) $t ); }
function sanitize_textarea_field( $t ) { return trim( (string) $t ); }
function wp_strip_all_tags( $t ) { return trim( strip_tags( (string) $t ) ); }
function wp_json_encode( $d, $f = 0 ) { return json_encode( $d, $f ); }
function wp_parse_args( $a, $d ) { return array_merge( $d, (array) $a ); }
function get_option( $k, $d = false ) { return $d; }
function is_front_page() { return true; }
function is_singular() { return false; }
function home_url( $p = '' ) { return 'https://vendodigital.co.uk' . $p; }
function get_permalink( $x = 0 ) { return home_url( '/' ); }
function get_the_excerpt( $x = 0 ) { return ''; }
function get_bloginfo( $k ) { return 'Vendo Digital'; }
function wp_get_document_title() { return apply_filters( 'pre_get_document_title', 'Fallback' ); }
function plugin_basename( $f ) { return basename( dirname( $f ) ) . '/' . basename( $f ); }
function admin_url( $p = '' ) { return '/wp-admin/' . $p; }
function register_setting( ...$a ) {}
function add_settings_section( ...$a ) {}
function add_settings_field( ...$a ) {}
function add_options_page( ...$a ) {}
function current_user_can( ...$a ) { return true; }
function checked( ...$a ) { return ''; }
function wp_enqueue_media() {}
function wp_add_inline_script( ...$a ) {}
// Theme-side helpers the theme's seo.php depends on:
function get_theme_mod( $k, $d = '' ) { return $d; }
function vendo_mod( $k ) { return ''; }

// 1. Plugin loads first (as WordPress does).
require __DIR__ . '/vendo-seo/vendo-seo.php';
echo "Plugin loaded OK.\n";

// 2. Then the active theme's functions load its seo module.
require dirname( __DIR__ ) . '/wordpress-theme/vendo/inc/seo.php';
echo "Theme seo.php loaded OK alongside plugin - NO redeclare fatal.\n";

// 3. Theme must stand down because VENDO_SEO_VERSION is defined.
echo 'Theme stands down: ' . ( vendo_seo_plugin_active() ? "YES\n" : "NO (BUG)\n" );

// 4. wp_head fires both hooks; only the plugin block should appear once.
ob_start();
do_action( 'wp_head' );
$head = ob_get_clean();
$plugin_blocks = substr_count( $head, '<!-- Vendo SEO v' );
$theme_blocks  = substr_count( $head, '<!-- Vendo SEO -->' );
$descs         = substr_count( $head, 'meta name="description"' );
echo "Plugin SEO blocks: $plugin_blocks (want 1)\n";
echo "Theme SEO blocks: $theme_blocks (want 0)\n";
echo "Meta descriptions: $descs (want 1)\n";
$ld = json_decode( ( preg_match( '/<script type="application\/ld\+json">(.*?)<\/script>/s', $head, $m ) ? $m[1] : '' ), true );
echo 'JSON-LD: ' . ( $ld ? 'valid (' . implode( '+', array_map( fn( $n ) => $n['@type'], $ld['@graph'] ) ) . ")\n" : "INVALID\n" );
// Encoding sanity: the pound sign and em dash must be real UTF-8.
echo 'Encoding OK: ' . ( ( strpos( $head, '£90K/month' ) !== false && strpos( $head, '—' ) !== false ) ? "YES\n" : "NO (mojibake!)\n" );
