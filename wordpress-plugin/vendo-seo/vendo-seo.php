<?php
/**
 * Plugin Name:       Vendo SEO
 * Plugin URI:        https://github.com/sahan4115/vendo-digital
 * Description:       Lightweight SEO for Vendo Digital: meta description, canonical, Open Graph / Twitter cards and LocalBusiness JSON-LD — configured under Settings → Vendo SEO. Stands down automatically if Yoast, Rank Math, AIOSEO or SEOPress is active.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Vendo Digital
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       vendo-seo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VENDO_SEO_VERSION', '1.0.0' );
define( 'VENDO_SEO_OPTION', 'vendo_seo_options' );

/* ══════════════════════════════════════════════════════════════
   Options
   ══════════════════════════════════════════════════════════════ */

/**
 * Default option values (Vendo Digital's real details).
 *
 * @return array
 */
function vendo_seo_defaults() {
	return array(
		'enabled'       => 1,
		'seo_title'     => 'Vendo Digital — PPC, SEO & Web Design Agency, Surrey',
		'seo_desc'      => 'Vendo Digital is a PPC, SEO and web design agency in Sutton, Surrey. We took a dental practice from a standing start to £90K/month — no jargon, no waffle, no lock-in contracts.',
		'og_image'      => '',
		'business_name' => 'Vendo Digital',
		'email'         => 'hello@vendodigital.co.uk',
		'phone'         => '+44 20 7101 4967',
		'addr_street'   => '5 Sandiford Road',
		'addr_locality' => 'Sutton',
		'addr_region'   => 'Surrey',
		'addr_postcode' => 'SM3 9RN',
		'addr_country'  => 'GB',
		'price_range'   => '££',
		'founding_year' => '2019',
		'area_served'   => 'United Kingdom',
		'geo_lat'       => '',
		'geo_lng'       => '',
		'social_ig'     => 'https://www.instagram.com/vendo_digital/',
		'social_fb'     => '',
		'social_li'     => 'https://uk.linkedin.com/company/vendo-digital-ltd',
		'social_yt'     => '',
		'knows_about'   => 'Web Design, Google Ads, SEO, Content & Brand, Paid Social, Dental marketing, E-commerce marketing',
	);
}

/**
 * Get one option (merged with defaults).
 *
 * @param string $key Option key.
 * @return mixed
 */
function vendo_seo_get( $key ) {
	$opts = wp_parse_args( (array) get_option( VENDO_SEO_OPTION, array() ), vendo_seo_defaults() );
	return isset( $opts[ $key ] ) ? $opts[ $key ] : '';
}

/* ══════════════════════════════════════════════════════════════
   Guards
   ══════════════════════════════════════════════════════════════ */

/**
 * Is a dedicated SEO plugin already handling meta/schema?
 *
 * @return bool
 */
function vendo_seo_conflict() {
	return defined( 'WPSEO_VERSION' )      // Yoast.
		|| defined( 'RANK_MATH_VERSION' )  // Rank Math.
		|| defined( 'AIOSEO_VERSION' )     // All in One SEO.
		|| defined( 'SEOPRESS_VERSION' );  // SEOPress.
}

/**
 * Should this plugin output anything on the front end?
 *
 * @return bool
 */
function vendo_seo_active() {
	return vendo_seo_get( 'enabled' ) && ! vendo_seo_conflict();
}

/* ══════════════════════════════════════════════════════════════
   Front-end output
   ══════════════════════════════════════════════════════════════ */

/**
 * Canonical URL for the current view.
 *
 * @return string
 */
function vendo_seo_canonical() {
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
 * Meta description for the current view.
 *
 * @return string
 */
function vendo_seo_description() {
	$desc = vendo_seo_get( 'seo_desc' );
	if ( ! is_front_page() && is_singular() ) {
		$excerpt = get_the_excerpt();
		if ( $excerpt ) {
			$desc = $excerpt;
		}
	}
	return wp_strip_all_tags( (string) $desc );
}

/**
 * Front-page <title> override.
 *
 * @param string $title Current title.
 * @return string
 */
function vendo_seo_title_filter( $title ) {
	if ( vendo_seo_active() && is_front_page() && vendo_seo_get( 'seo_title' ) ) {
		return vendo_seo_get( 'seo_title' );
	}
	return $title;
}
add_filter( 'pre_get_document_title', 'vendo_seo_title_filter' );

/**
 * Richer robots directive.
 *
 * @param array $robots Directives.
 * @return array
 */
function vendo_seo_robots( $robots ) {
	if ( vendo_seo_active() && ( is_front_page() || is_singular() ) ) {
		$robots['max-image-preview'] = 'large';
		$robots['max-snippet']       = -1;
		$robots['max-video-preview'] = -1;
	}
	return $robots;
}
add_filter( 'wp_robots', 'vendo_seo_robots' );

/**
 * Head output: description, canonical, OG/Twitter, JSON-LD.
 */
function vendo_seo_head() {
	if ( ! vendo_seo_active() ) {
		return;
	}

	$site_name = vendo_seo_get( 'business_name' ) ?: get_bloginfo( 'name' );
	$desc      = vendo_seo_description();
	$canonical = vendo_seo_canonical();
	$title     = is_front_page() && vendo_seo_get( 'seo_title' ) ? vendo_seo_get( 'seo_title' ) : wp_get_document_title();
	$og_image  = vendo_seo_get( 'og_image' );

	echo "\n<!-- Vendo SEO v" . esc_html( VENDO_SEO_VERSION ) . " -->\n";
	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( $desc ) . "\" />\n";
	}
	echo '<link rel="canonical" href="' . esc_url( $canonical ) . "\" />\n";

	echo '<meta property="og:type" content="website" />' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . "\" />\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . "\" />\n";
	if ( $desc ) {
		echo '<meta property="og:description" content="' . esc_attr( $desc ) . "\" />\n";
	}
	echo '<meta property="og:url" content="' . esc_url( $canonical ) . "\" />\n";
	echo '<meta property="og:locale" content="en_GB" />' . "\n";
	if ( $og_image ) {
		echo '<meta property="og:image" content="' . esc_url( $og_image ) . "\" />\n";
	}

	echo '<meta name="twitter:card" content="' . ( $og_image ? 'summary_large_image' : 'summary' ) . "\" />\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . "\" />\n";
	if ( $desc ) {
		echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . "\" />\n";
	}
	if ( $og_image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $og_image ) . "\" />\n";
	}

	$json = wp_json_encode( vendo_seo_schema(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	if ( $json ) {
		echo '<script type="application/ld+json">' . $json . "</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- wp_json_encode output.
	}
	echo "<!-- /Vendo SEO -->\n";
}
add_action( 'wp_head', 'vendo_seo_head', 5 );

/**
 * JSON-LD @graph: ProfessionalService + WebSite + WebPage.
 *
 * @return array
 */
function vendo_seo_schema() {
	$home    = home_url( '/' );
	$biz_id  = $home . '#business';
	$site_id = $home . '#website';
	$name    = vendo_seo_get( 'business_name' ) ?: get_bloginfo( 'name' );
	$desc    = vendo_seo_description();
	$image   = vendo_seo_get( 'og_image' );

	$knows = array_values( array_filter( array_map( 'trim', explode( ',', vendo_seo_get( 'knows_about' ) ) ) ) );

	$same_as = array();
	foreach ( array( 'social_ig', 'social_fb', 'social_li', 'social_yt' ) as $net ) {
		$url = trim( vendo_seo_get( $net ) );
		if ( $url && '#' !== $url ) {
			$same_as[] = $url;
		}
	}

	$business = array(
		'@type'        => 'ProfessionalService',
		'@id'          => $biz_id,
		'name'         => $name,
		'url'          => $home,
		'description'  => $desc,
		'email'        => vendo_seo_get( 'email' ),
		'telephone'    => vendo_seo_get( 'phone' ),
		'priceRange'   => vendo_seo_get( 'price_range' ),
		'foundingDate' => vendo_seo_get( 'founding_year' ),
		'areaServed'   => vendo_seo_get( 'area_served' ),
		'address'      => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => vendo_seo_get( 'addr_street' ),
			'addressLocality' => vendo_seo_get( 'addr_locality' ),
			'addressRegion'   => vendo_seo_get( 'addr_region' ),
			'postalCode'      => vendo_seo_get( 'addr_postcode' ),
			'addressCountry'  => vendo_seo_get( 'addr_country' ),
		),
	);

	if ( ! empty( $knows ) ) {
		$business['knowsAbout'] = $knows;
	}
	if ( $image ) {
		$business['image'] = $image;
		$business['logo']  = $image;
	}
	if ( ! empty( $same_as ) ) {
		$business['sameAs'] = $same_as;
	}

	$lat = vendo_seo_get( 'geo_lat' );
	$lng = vendo_seo_get( 'geo_lng' );
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
		'@type'       => 'WebPage',
		'@id'         => vendo_seo_canonical() . '#webpage',
		'url'         => vendo_seo_canonical(),
		'name'        => is_front_page() && vendo_seo_get( 'seo_title' ) ? vendo_seo_get( 'seo_title' ) : wp_get_document_title(),
		'description' => $desc,
		'inLanguage'  => 'en-GB',
		'isPartOf'    => array( '@id' => $site_id ),
		'about'       => array( '@id' => $biz_id ),
	);

	return array(
		'@context' => 'https://schema.org',
		'@graph'   => array( $business, $website, $webpage ),
	);
}

/* ══════════════════════════════════════════════════════════════
   Admin: Settings → Vendo SEO
   ══════════════════════════════════════════════════════════════ */

/**
 * Register the settings page.
 */
function vendo_seo_admin_menu() {
	add_options_page(
		__( 'Vendo SEO', 'vendo-seo' ),
		__( 'Vendo SEO', 'vendo-seo' ),
		'manage_options',
		'vendo-seo',
		'vendo_seo_render_page'
	);
}
add_action( 'admin_menu', 'vendo_seo_admin_menu' );

/**
 * "Settings" link on the Plugins screen row.
 *
 * @param array $links Action links.
 * @return array
 */
function vendo_seo_action_links( $links ) {
	array_unshift( $links, '<a href="' . esc_url( admin_url( 'options-general.php?page=vendo-seo' ) ) . '">' . esc_html__( 'Settings', 'vendo-seo' ) . '</a>' );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'vendo_seo_action_links' );

/**
 * Field registry: key => [label, type, section, description].
 *
 * @return array
 */
function vendo_seo_fields() {
	return array(
		'enabled'       => array( __( 'Enable SEO output', 'vendo-seo' ), 'checkbox', 'general', __( 'Untick to silence all front-end output without deactivating the plugin.', 'vendo-seo' ) ),
		'seo_title'     => array( __( 'Homepage title', 'vendo-seo' ), 'text', 'general', __( 'The browser-tab / search-result title for the front page.', 'vendo-seo' ) ),
		'seo_desc'      => array( __( 'Meta description', 'vendo-seo' ), 'textarea', 'general', __( 'Aim for ~155 characters.', 'vendo-seo' ) ),
		'og_image'      => array( __( 'Share image', 'vendo-seo' ), 'image', 'general', __( '1200×630 PNG/JPG shown when links are shared on social media.', 'vendo-seo' ) ),

		'business_name' => array( __( 'Business name', 'vendo-seo' ), 'text', 'business', '' ),
		'email'         => array( __( 'Email', 'vendo-seo' ), 'text', 'business', '' ),
		'phone'         => array( __( 'Phone (international)', 'vendo-seo' ), 'text', 'business', __( 'E.g. +44 20 7101 4967', 'vendo-seo' ) ),
		'addr_street'   => array( __( 'Street address', 'vendo-seo' ), 'text', 'business', '' ),
		'addr_locality' => array( __( 'Town / city', 'vendo-seo' ), 'text', 'business', '' ),
		'addr_region'   => array( __( 'County / region', 'vendo-seo' ), 'text', 'business', '' ),
		'addr_postcode' => array( __( 'Postcode', 'vendo-seo' ), 'text', 'business', '' ),
		'addr_country'  => array( __( 'Country code', 'vendo-seo' ), 'text', 'business', __( 'Two letters, e.g. GB', 'vendo-seo' ) ),

		'price_range'   => array( __( 'Price range', 'vendo-seo' ), 'text', 'schema', __( '£ to ££££', 'vendo-seo' ) ),
		'founding_year' => array( __( 'Founding year', 'vendo-seo' ), 'text', 'schema', '' ),
		'area_served'   => array( __( 'Area served', 'vendo-seo' ), 'text', 'schema', '' ),
		'geo_lat'       => array( __( 'Office latitude', 'vendo-seo' ), 'text', 'schema', __( 'Optional. Right-click your pin in Google Maps to copy coordinates.', 'vendo-seo' ) ),
		'geo_lng'       => array( __( 'Office longitude', 'vendo-seo' ), 'text', 'schema', '' ),
		'knows_about'   => array( __( 'Areas of expertise', 'vendo-seo' ), 'textarea', 'schema', __( 'Comma-separated, e.g. SEO, Google Ads, Dental marketing.', 'vendo-seo' ) ),

		'social_ig'     => array( __( 'Instagram URL', 'vendo-seo' ), 'text', 'social', '' ),
		'social_fb'     => array( __( 'Facebook URL', 'vendo-seo' ), 'text', 'social', '' ),
		'social_li'     => array( __( 'LinkedIn URL', 'vendo-seo' ), 'text', 'social', '' ),
		'social_yt'     => array( __( 'YouTube URL', 'vendo-seo' ), 'text', 'social', '' ),
	);
}

/**
 * Register setting, sections and fields.
 */
function vendo_seo_admin_init() {
	register_setting( 'vendo_seo', VENDO_SEO_OPTION, array( 'sanitize_callback' => 'vendo_seo_sanitize' ) );

	$sections = array(
		'general'  => __( 'Titles & sharing', 'vendo-seo' ),
		'business' => __( 'Business details (LocalBusiness schema)', 'vendo-seo' ),
		'schema'   => __( 'Schema extras', 'vendo-seo' ),
		'social'   => __( 'Social profiles (sameAs)', 'vendo-seo' ),
	);
	foreach ( $sections as $id => $title ) {
		add_settings_section( 'vendo_seo_' . $id, $title, '__return_false', 'vendo-seo' );
	}

	foreach ( vendo_seo_fields() as $key => $def ) {
		add_settings_field(
			$key,
			$def[0],
			'vendo_seo_render_field',
			'vendo-seo',
			'vendo_seo_' . $def[2],
			array( 'key' => $key, 'type' => $def[1], 'desc' => $def[3], 'label_for' => 'vendo_seo_' . $key )
		);
	}
}
add_action( 'admin_init', 'vendo_seo_admin_init' );

/**
 * Sanitize all options on save.
 *
 * @param array $input Raw input.
 * @return array
 */
function vendo_seo_sanitize( $input ) {
	$clean = array();
	foreach ( vendo_seo_fields() as $key => $def ) {
		$value = isset( $input[ $key ] ) ? $input[ $key ] : '';
		switch ( $def[1] ) {
			case 'checkbox':
				$clean[ $key ] = $value ? 1 : 0;
				break;
			case 'textarea':
				$clean[ $key ] = sanitize_textarea_field( $value );
				break;
			case 'image':
				$clean[ $key ] = esc_url_raw( $value );
				break;
			default:
				$clean[ $key ] = 0 === strpos( $key, 'social_' ) ? esc_url_raw( $value ) : sanitize_text_field( $value );
		}
	}
	return $clean;
}

/**
 * Render one field.
 *
 * @param array $args key/type/desc.
 */
function vendo_seo_render_field( $args ) {
	$key   = $args['key'];
	$type  = $args['type'];
	$id    = 'vendo_seo_' . $key;
	$name  = VENDO_SEO_OPTION . '[' . $key . ']';
	$value = vendo_seo_get( $key );

	switch ( $type ) {
		case 'checkbox':
			echo '<label><input type="checkbox" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="1" ' . checked( $value, 1, false ) . ' /> ' . esc_html__( 'Enabled', 'vendo-seo' ) . '</label>';
			break;
		case 'textarea':
			echo '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" rows="3" class="large-text">' . esc_textarea( $value ) . '</textarea>';
			break;
		case 'image':
			echo '<input type="url" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="regular-text" placeholder="https://…" /> ';
			echo '<button type="button" class="button vendo-seo-media" data-target="' . esc_attr( $id ) . '">' . esc_html__( 'Choose image', 'vendo-seo' ) . '</button>';
			if ( $value ) {
				echo '<p><img src="' . esc_url( $value ) . '" alt="" style="max-width:240px;height:auto;border:1px solid #ccd0d4;border-radius:4px;" /></p>';
			}
			break;
		default:
			echo '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="regular-text" />';
	}

	if ( ! empty( $args['desc'] ) ) {
		echo '<p class="description">' . esc_html( $args['desc'] ) . '</p>';
	}
}

/**
 * Render the settings page.
 */
function vendo_seo_render_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Vendo SEO', 'vendo-seo' ); ?></h1>

		<?php if ( vendo_seo_conflict() ) : ?>
			<div class="notice notice-warning"><p>
				<?php esc_html_e( 'Another SEO plugin (Yoast / Rank Math / AIOSEO / SEOPress) is active, so Vendo SEO is standing down — nothing is output on the front end to avoid duplicate tags. Your settings here are kept.', 'vendo-seo' ); ?>
			</p></div>
		<?php endif; ?>

		<p><?php esc_html_e( 'Outputs meta description, canonical URL, Open Graph / Twitter cards and LocalBusiness structured data. Validate with Google\'s Rich Results Test after saving.', 'vendo-seo' ); ?></p>

		<form action="options.php" method="post">
			<?php
			settings_fields( 'vendo_seo' );
			do_settings_sections( 'vendo-seo' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Media uploader for the share-image field (settings page only).
 *
 * @param string $hook Current admin page.
 */
function vendo_seo_admin_assets( $hook ) {
	if ( 'settings_page_vendo-seo' !== $hook ) {
		return;
	}
	wp_enqueue_media();
	$js = '
	jQuery(function($){
		$(".vendo-seo-media").on("click", function(e){
			e.preventDefault();
			var target = $("#" + $(this).data("target"));
			var frame = wp.media({ title: "Choose share image", multiple: false, library: { type: "image" } });
			frame.on("select", function(){
				var att = frame.state().get("selection").first().toJSON();
				target.val(att.url);
			});
			frame.open();
		});
	});';
	wp_add_inline_script( 'jquery', $js );
}
add_action( 'admin_enqueue_scripts', 'vendo_seo_admin_assets' );
