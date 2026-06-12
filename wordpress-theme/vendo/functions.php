<?php
/**
 * Vendo Digital — theme setup & asset loading.
 *
 * @package Vendo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

// Backend customisation: Customizer fields, helpers, Case Studies CPT.
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/case-studies.php';

if ( ! function_exists( 'vendo_setup' ) ) {
	/**
	 * Theme supports.
	 */
	function vendo_setup() {
		// Let WordPress / SEO plugins manage the <title> tag.
		add_theme_support( 'title-tag' );

		// Standard niceties.
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

		// A single primary nav location (optional — the homepage uses in-page anchors).
		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'vendo' ),
			)
		);
	}
}
add_action( 'after_setup_theme', 'vendo_setup' );

/**
 * Enqueue fonts, styles and scripts.
 *
 * Script order matters: GSAP → ScrollTrigger → Three.js → main.js.
 * The dependency arrays below guarantee that order, and `true` loads
 * each one in the footer (after the DOM the scripts read), matching the
 * original static site.
 */
function vendo_assets() {
	$ver = wp_get_theme()->get( 'Version' );

	// Brand typography: Manrope + Instrument Sans (Google Fonts).
	wp_enqueue_style(
		'vendo-fonts',
		'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Instrument+Sans:ital,wght@0,400;0,500;0,600;1,400&display=swap',
		array(),
		null
	);

	// Main stylesheet (the real CSS — style.css in the theme root only holds the header).
	wp_enqueue_style(
		'vendo-style',
		get_template_directory_uri() . '/assets/css/style.css',
		array( 'vendo-fonts' ),
		$ver
	);

	// Animation libraries (CDN).
	wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', array(), '3.12.5', true );
	wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', array( 'gsap' ), '3.12.5', true );
	wp_enqueue_script( 'three', 'https://cdnjs.cloudflare.com/ajax/libs/three.js/0.160.0/three.min.js', array(), '0.160.0', true );

	// Site behaviour.
	wp_enqueue_script(
		'vendo-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array( 'gsap', 'gsap-scrolltrigger', 'three' ),
		$ver,
		true
	);

	// Hand the Customizer-defined manifesto accent words to main.js.
	$accents = array_filter( array_map( 'trim', explode( ',', strtolower( vendo_mod( 'manifesto_accents' ) ) ) ) );
	wp_localize_script( 'vendo-main', 'VENDO', array( 'accents' => array_values( $accents ) ) );
}
add_action( 'wp_enqueue_scripts', 'vendo_assets' );

/**
 * Add preconnect hints for the Google Fonts hosts (small perf win).
 */
function vendo_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'vendo_resource_hints', 10, 2 );
