<?php
/**
 * SEO: title, meta description, canonical, Open Graph, Twitter cards and
 * JSON-LD structured data (ProfessionalService + WebSite + WebPage).
 *
 * Everything here defers to a dedicated SEO plugin if one is active
 * (Yoast, Rank Math, AIOSEO, SEOPress) so tags are never duplicated.
 * Values come from the Customizer (Vendo — Page Content → SEO & Sharing,
 * and the Contact & Footer section), so they stay in sync with the site.
 *
 * Note: no FAQPage schema is output — the page has no visible FAQ, and
 * marking up content that isn't on the page breaks Google's guidelines.
 *
 * @package Vendo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Is a dedicated SEO plugin handling meta/schema already?
 *
 * @return bool
 */
function vendo_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' )        // Yoast.
		|| defined( 'RANK_MATH_VERSION' )    // Rank Math.
		|| defined( 'AIOSEO_VERSION' )       // All in One SEO.
		|| defined( 'SEOPRESS_VERSION' )     // SEOPress.
		|| class_exists( 'All_in_One_SEO_Pack' );
}

/**
 * Canonical / og:url for the current view.
 *
 * @return string
 */
function vendo_canonical_url() {
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	if ( is_singular() ) {
		$permalink = get_permalink();
		if ( $permalink ) {
			return $permalink;
		}
	}
	return home_url( '/' );
}

/**
 * The meta description for the current view.
 *
 * @return string
 */
function vendo_meta_description() {
	$desc = vendo_mod( 'seo_desc' );
	if ( ! is_front_page() && is_singular() ) {
		$excerpt = get_the_excerpt();
		if ( $excerpt ) {
			$desc = $excerpt;
		}
	}
	return wp_strip_all_tags( $desc );
}

/**
 * Override the document <title> on the front page with the SEO title.
 *
 * @param string $title Existing title.
 * @return string
 */
function vendo_document_title( $title ) {
	if ( vendo_seo_plugin_active() ) {
		return $title;
	}
	if ( is_front_page() ) {
		$seo_title = vendo_mod( 'seo_title' );
		if ( $seo_title ) {
			return $seo_title;
		}
	}
	return $title;
}
add_filter( 'pre_get_document_title', 'vendo_document_title' );

/**
 * Improve the robots directive (richer snippets/previews).
 *
 * @param array $robots Robots directives.
 * @return array
 */
function vendo_robots( $robots ) {
	if ( vendo_seo_plugin_active() ) {
		return $robots;
	}
	if ( is_front_page() || is_singular() ) {
		$robots['max-image-preview'] = 'large';
		$robots['max-snippet']       = -1;
		$robots['max-video-preview']  = -1;
	}
	return $robots;
}
add_filter( 'wp_robots', 'vendo_robots' );

/**
 * Output meta description, canonical, Open Graph, Twitter and JSON-LD.
 */
function vendo_seo_head() {
	if ( vendo_seo_plugin_active() ) {
		return; // Let the plugin own it.
	}

	$site_name = get_bloginfo( 'name' ) ? get_bloginfo( 'name' ) : 'Vendo Digital';
	$desc      = vendo_meta_description();
	$canonical = vendo_canonical_url();
	$title     = is_front_page() ? vendo_mod( 'seo_title' ) : wp_get_document_title();
	$og_image  = get_theme_mod( 'vendo_og_image', '' );
	$locale    = 'en_GB';

	echo "\n  <!-- Vendo SEO -->\n";
	if ( $desc ) {
		echo '  <meta name="description" content="' . esc_attr( $desc ) . "\" />\n";
	}
	echo '  <link rel="canonical" href="' . esc_url( $canonical ) . "\" />\n";

	// Open Graph.
	echo '  <meta property="og:type" content="website" />' . "\n";
	echo '  <meta property="og:site_name" content="' . esc_attr( $site_name ) . "\" />\n";
	echo '  <meta property="og:title" content="' . esc_attr( $title ) . "\" />\n";
	if ( $desc ) {
		echo '  <meta property="og:description" content="' . esc_attr( $desc ) . "\" />\n";
	}
	echo '  <meta property="og:url" content="' . esc_url( $canonical ) . "\" />\n";
	echo '  <meta property="og:locale" content="' . esc_attr( $locale ) . "\" />\n";
	if ( $og_image ) {
		echo '  <meta property="og:image" content="' . esc_url( $og_image ) . "\" />\n";
	}

	// Twitter.
	echo '  <meta name="twitter:card" content="' . ( $og_image ? 'summary_large_image' : 'summary' ) . "\" />\n";
	echo '  <meta name="twitter:title" content="' . esc_attr( $title ) . "\" />\n";
	if ( $desc ) {
		echo '  <meta name="twitter:description" content="' . esc_attr( $desc ) . "\" />\n";
	}
	if ( $og_image ) {
		echo '  <meta name="twitter:image" content="' . esc_url( $og_image ) . "\" />\n";
	}

	// JSON-LD structured data.
	$json = wp_json_encode( vendo_schema_graph(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	if ( $json ) {
		echo '  <script type="application/ld+json">' . $json . "</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- wp_json_encode output.
	}
	echo "  <!-- /Vendo SEO -->\n";
}
add_action( 'wp_head', 'vendo_seo_head', 5 );

/**
 * Build the schema.org @graph: the agency as a ProfessionalService
 * (a LocalBusiness subtype), the WebSite, and the current WebPage.
 *
 * @return array
 */
function vendo_schema_graph() {
	$home     = home_url( '/' );
	$biz_id   = $home . '#business';
	$site_id  = $home . '#website';
	$name     = get_bloginfo( 'name' ) ? get_bloginfo( 'name' ) : 'Vendo Digital';
	$desc     = vendo_meta_description();
	$og_image = get_theme_mod( 'vendo_og_image', '' );

	// Service areas of expertise, kept in sync with the editable service names.
	$knows = array();
	for ( $s = 1; $s <= 5; $s++ ) {
		$svc = trim( vendo_mod( 'svc' . $s . '_name' ) );
		if ( $svc ) {
			$knows[] = $svc;
		}
	}
	$knows[] = 'Dental marketing';
	$knows[] = 'E-commerce marketing';

	// Social profiles (only real ones — skip placeholder "#").
	$same_as = array();
	foreach ( array( 'ig', 'fb', 'li', 'yt' ) as $net ) {
		$url = vendo_mod( $net );
		if ( $url && '#' !== $url ) {
			$same_as[] = $url;
		}
	}

	$business = array(
		'@type'       => 'ProfessionalService',
		'@id'         => $biz_id,
		'name'        => $name,
		'url'         => $home,
		'description' => $desc,
		'email'       => vendo_mod( 'email' ),
		'telephone'   => vendo_mod( 'phone_intl' ),
		'priceRange'  => vendo_mod( 'price_range' ),
		'foundingDate' => vendo_mod( 'founding_year' ),
		'areaServed'  => vendo_mod( 'area_served' ),
		'address'     => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => vendo_mod( 'addr1' ),
			'addressLocality' => 'Sutton',
			'addressRegion'   => 'Surrey',
			'postalCode'      => 'SM3 9RN',
			'addressCountry'  => 'GB',
		),
		'knowsAbout'  => $knows,
	);

	if ( $og_image ) {
		$business['image'] = $og_image;
		$business['logo']  = $og_image;
	}
	if ( ! empty( $same_as ) ) {
		$business['sameAs'] = $same_as;
	}

	// Geo coordinates — only if both are filled in (no fabricated values).
	$lat = vendo_mod( 'geo_lat' );
	$lng = vendo_mod( 'geo_lng' );
	if ( is_numeric( $lat ) && is_numeric( $lng ) ) {
		$business['geo'] = array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => (float) $lat,
			'longitude' => (float) $lng,
		);
	}

	$website = array(
		'@type'      => 'WebSite',
		'@id'        => $site_id,
		'url'        => $home,
		'name'       => $name,
		'inLanguage' => 'en-GB',
		'publisher'  => array( '@id' => $biz_id ),
	);

	$webpage = array(
		'@type'      => 'WebPage',
		'@id'        => vendo_canonical_url() . '#webpage',
		'url'        => vendo_canonical_url(),
		'name'       => is_front_page() ? vendo_mod( 'seo_title' ) : wp_get_document_title(),
		'description' => $desc,
		'inLanguage' => 'en-GB',
		'isPartOf'   => array( '@id' => $site_id ),
		'about'      => array( '@id' => $biz_id ),
	);

	return array(
		'@context' => 'https://schema.org',
		'@graph'   => array( $business, $website, $webpage ),
	);
}
