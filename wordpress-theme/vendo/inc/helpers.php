<?php
/**
 * Template helpers: theme-mod access, word splitting for the GSAP
 * hero animation, tag lists, case-study mock visuals and defaults.
 *
 * @package Vendo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shorthand for get_theme_mod with the theme's defaults registry.
 *
 * @param string $key Setting key (without the 'vendo_' prefix).
 * @return string
 */
function vendo_mod( $key ) {
	$defaults = vendo_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	return get_theme_mod( 'vendo_' . $key, $default );
}

/**
 * Echo a theme mod, escaped for HTML.
 *
 * @param string $key Setting key.
 */
function vendo_the( $key ) {
	echo esc_html( vendo_mod( $key ) );
}

/**
 * Wrap each word of a string in <span class="word"> for the hero's
 * line-mask reveal. The animation translates each .word from below
 * an overflow-hidden line, so the spans are structural, not cosmetic.
 *
 * @param string $text  Plain text.
 * @param string $class Extra class for every word span.
 * @return string Safe HTML.
 */
function vendo_words( $text, $class = '' ) {
	$words = preg_split( '/\s+/', trim( $text ) );
	$class = $class ? 'word ' . $class : 'word';
	$out   = array();
	foreach ( $words as $w ) {
		$out[] = '<span class="' . esc_attr( $class ) . '">' . esc_html( $w ) . '</span>';
	}
	return implode( ' ', $out );
}

/**
 * Render a comma-separated mod as <li> items.
 *
 * @param string $key Setting key holding "a, b, c".
 */
function vendo_tag_list( $key ) {
	$tags = array_filter( array_map( 'trim', explode( ',', vendo_mod( $key ) ) ) );
	foreach ( $tags as $tag ) {
		echo '<li>' . esc_html( $tag ) . '</li>';
	}
}

/**
 * Render the marquee string: "A, B, C" → A <i>✦</i> B <i>✦</i> C <i>✦</i>
 */
function vendo_marquee_html() {
	$items = array_filter( array_map( 'trim', explode( ',', vendo_mod( 'marquee' ) ) ) );
	$out   = '';
	foreach ( $items as $item ) {
		$out .= esc_html( $item ) . ' <i>✦</i> ';
	}
	return $out . '&nbsp;';
}

/**
 * The four CSS-built case-study mock visuals. Selected per case in the
 * Case Study editor; markup must match assets/css/style.css selectors.
 *
 * @param string $type app|dash|mobile|web.
 */
function vendo_render_mock( $type ) {
	switch ( $type ) {
		case 'dash':
			?>
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
			<?php
			break;

		case 'mobile':
			?>
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
			<?php
			break;

		case 'web':
			?>
			<div class="mock mock-web" aria-hidden="true">
				<div class="mock-bar"><i></i><i></i><i></i></div>
				<div class="mock-nav"><span class="green-soft"></span><span></span><span></span><span></span></div>
				<div class="mock-headline"><i></i><i class="short"></i></div>
				<div class="mock-cards"><span></span><span class="green-soft"></span><span></span></div>
			</div>
			<?php
			break;

		case 'app':
		default:
			?>
			<div class="mock mock-app" aria-hidden="true">
				<div class="mock-bar"><i></i><i></i><i></i></div>
				<div class="mock-hero shimmer"></div>
				<div class="mock-row"><div class="mock-chip green"></div><div class="mock-chip"></div><div class="mock-chip"></div></div>
				<div class="mock-cols"><div class="mock-block"></div><div class="mock-block tall green-soft"></div><div class="mock-block"></div></div>
			</div>
			<?php
			break;
	}
}

/**
 * Fallback case studies shown until real Case Study posts are published,
 * so a fresh install never renders an empty results grid.
 *
 * @return array[]
 */
function vendo_default_cases() {
	return array(
		array(
			'title' => 'Zen Dental',
			'blurb' => 'Practice website and Google Ads built to fill the appointment book',
			'tags'  => 'Web design, Google Ads',
			'bg'    => 'sage',
			'mock'  => 'app',
		),
		array(
			'title' => 'The Dental Practice UK',
			'blurb' => 'SEO and PPC with treatment-level tracking — revenue, not vanity metrics',
			'tags'  => 'SEO, Google Ads',
			'bg'    => 'charcoal',
			'mock'  => 'dash',
		),
		array(
			'title' => 'Dr Vivek Shah',
			'blurb' => 'Personal brand and mobile-first site for a growing dental reputation',
			'tags'  => 'Brand, Web design',
			'bg'    => 'green',
			'mock'  => 'mobile',
		),
		array(
			'title' => 'Kane Construction',
			'blurb' => 'Web design and local SEO putting a builder top of the map pack',
			'tags'  => 'Web design, Local SEO',
			'bg'    => 'sage',
			'mock'  => 'web',
		),
	);
}

/**
 * Case studies for the front page: published Case Study posts, or the
 * defaults above when none exist yet.
 *
 * @return array[] Each: title, blurb, tags, bg, mock.
 */
function vendo_get_cases() {
	$posts = get_posts(
		array(
			'post_type'      => 'vendo_case',
			'posts_per_page' => 8,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		)
	);

	if ( empty( $posts ) ) {
		return vendo_default_cases();
	}

	$cases = array();
	foreach ( $posts as $p ) {
		$cases[] = array(
			'title' => get_the_title( $p ),
			'blurb' => $p->post_excerpt,
			'tags'  => get_post_meta( $p->ID, '_vendo_tags', true ),
			'bg'    => get_post_meta( $p->ID, '_vendo_bg', true ) ?: 'sage',
			'mock'  => get_post_meta( $p->ID, '_vendo_mock', true ) ?: 'app',
		);
	}
	return $cases;
}

/**
 * Render one case card (shared by real posts and defaults).
 *
 * @param array $case title|blurb|tags|bg|mock.
 */
function vendo_render_case( $case ) {
	$bg = in_array( $case['bg'], array( 'sage', 'charcoal', 'green' ), true ) ? $case['bg'] : 'sage';
	?>
	<article class="case" data-cursor="Open" tabindex="0">
		<div class="case-media <?php echo esc_attr( $bg ); ?>">
			<?php vendo_render_mock( $case['mock'] ); ?>
		</div>
		<div class="case-info">
			<h3><?php echo esc_html( $case['title'] ); ?></h3>
			<p><?php echo esc_html( $case['blurb'] ); ?></p>
			<?php if ( ! empty( $case['tags'] ) ) : ?>
			<ul class="case-tags">
				<?php
				foreach ( array_filter( array_map( 'trim', explode( ',', $case['tags'] ) ) ) as $tag ) {
					echo '<li>' . esc_html( $tag ) . '</li>';
				}
				?>
			</ul>
			<?php endif; ?>
		</div>
	</article>
	<?php
}
