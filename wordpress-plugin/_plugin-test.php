<?php
/**
 * Dev harness — NOT part of the plugin. Stubs enough of WordPress to
 * load vendo-seo.php and exercise its head output + schema, including
 * the conflict guard and the theme-coexistence path.
 *
 * Run: php _plugin-test.php
 */

define( 'ABSPATH', __DIR__ . '/' );
$GLOBALS['hooks'] = array();

function add_action( $h, $cb, $p = 10, $a = 1 ) { add_filter( $h, $cb, $p, $a ); }
function add_filter( $h, $cb, $p = 10, $a = 1 ) { $GLOBALS['hooks'][ $h ][] = array( 'cb' => $cb, 'p' => $p ); }
function do_action( $h, ...$a ) {
	if ( empty( $GLOBALS['hooks'][ $h ] ) ) { return; }
	$c = $GLOBALS['hooks'][ $h ]; usort( $c, fn( $x, $y ) => $x['p'] <=> $y['p'] );
	foreach ( $c as $f ) { call_user_func_array( $f['cb'], $a ); }
}
function apply_filters( $h, $v, ...$a ) {
	if ( empty( $GLOBALS['hooks'][ $h ] ) ) { return $v; }
	$c = $GLOBALS['hooks'][ $h ]; usort( $c, fn( $x, $y ) => $x['p'] <=> $y['p'] );
	foreach ( $c as $f ) { $v = call_user_func( $f['cb'], $v, ...$a ); }
	return $v;
}

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
function wp_parse_args( $args, $defaults ) { return array_merge( $defaults, (array) $args ); }
function get_option( $k, $d = false ) { return $d; }
function is_front_page() { return true; }
function is_singular() { return false; }
function home_url( $p = '' ) { return 'https://vendodigital.co.uk' . $p; }
function get_permalink( $x = 0 ) { return home_url( '/' ); }
function get_the_excerpt( $x = 0 ) { return ''; }
function get_bloginfo( $k ) { return 'Vendo Digital'; }
function wp_get_document_title() { return apply_filters( 'pre_get_document_title', 'Fallback Title' ); }
function plugin_basename( $f ) { return basename( dirname( $f ) ) . '/' . basename( $f ); }
function admin_url( $p = '' ) { return 'https://vendodigital.co.uk/wp-admin/' . $p; }
function register_setting( ...$a ) {}
function add_settings_section( ...$a ) {}
function add_settings_field( ...$a ) {}
function add_options_page( ...$a ) {}
function current_user_can( ...$a ) { return true; }
function checked( ...$a ) { return ''; }
function wp_enqueue_media() {}
function wp_add_inline_script( ...$a ) {}

require __DIR__ . '/vendo-seo/vendo-seo.php';

echo "=== TEST 1: head output (no conflicts) ===\n";
ob_start();
do_action( 'wp_head' );
$head = ob_get_clean();
echo $head;

echo "\n=== TEST 2: title filter ===\n";
echo wp_get_document_title() . "\n";

echo "\n=== TEST 3: robots filter ===\n";
echo json_encode( apply_filters( 'wp_robots', array() ) ) . "\n";

echo "\n=== TEST 4: JSON-LD validity ===\n";
preg_match( '/<script type="application\/ld\+json">(.*?)<\/script>/s', $head, $m );
$ld = json_decode( $m[1] ?? '', true );
echo null === $ld ? "PARSE FAILED\n" : 'Parsed OK. Types: ' . implode( ', ', array_map( fn( $n ) => $n['@type'], $ld['@graph'] ) ) . "\n";
echo 'sameAs: ' . json_encode( $ld['@graph'][0]['sameAs'] ?? null ) . "\n";
echo 'geo present (should be false): ' . var_export( isset( $ld['@graph'][0]['geo'] ) , true ) . "\n";
echo 'knowsAbout count: ' . count( $ld['@graph'][0]['knowsAbout'] ?? array() ) . "\n";

echo "\n=== TEST 5: conflict guard (simulate Yoast) ===\n";
define( 'WPSEO_VERSION', '22.0' );
ob_start();
do_action( 'wp_head' );
$head2 = ob_get_clean();
echo '' === trim( $head2 ) ? "Correct: no output when Yoast active.\n" : "FAIL: output despite Yoast!\n";
echo 'Title reverts to WP: ' . wp_get_document_title() . "\n";
