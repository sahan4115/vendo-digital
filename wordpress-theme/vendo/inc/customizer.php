<?php
/**
 * Customizer: every piece of front-page copy is editable under
 * Appearance → Customize, grouped into sections that mirror the page.
 * All settings default to the original copy, so a fresh activation
 * renders the site exactly as designed.
 *
 * @package Vendo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Single source of truth for setting keys, defaults and labels.
 * Key => array( default, label, section, control-type ).
 *
 * @return array
 */
function vendo_fields() {
	return array(
		// ── Hero ─────────────────────────────────────────────
		'hero_eyebrow'   => array( 'Free site audit — yours in 48 hours', 'Eyebrow pill', 'hero' ),
		'hero_l1'        => array( 'Marketing that', 'Heading line 1', 'hero' ),
		'hero_l2_ital'   => array( 'pays', 'Heading line 2 — italic word', 'hero' ),
		'hero_l2_green'  => array( 'for itself', 'Heading line 2 — green words', 'hero' ),
		'hero_sub'       => array( 'Vendo is a PPC, SEO and web design agency in Surrey. We took a dental practice from a standing start to £90K/month. No jargon, no waffle, no lock-in contracts.', 'Sub-heading', 'hero', 'textarea' ),
		'hero_cta'       => array( 'Get my free audit', 'Primary button label', 'hero' ),
		'hero_alt'       => array( 'See client results', 'Secondary link label', 'hero' ),

		// ── Marquee ──────────────────────────────────────────
		'marquee'        => array( 'Google Ads, SEO, Web Design, Paid Social, Content & Brand', 'Marquee items (comma-separated)', 'marquee' ),

		// ── Why Vendo (manifesto + stats) ───────────────────
		'manifesto_tag'  => array( 'Why Vendo', 'Section label', 'manifesto' ),
		'manifesto_text' => array( "Most agencies send a PDF report and hope you don't read it. We think you deserve to know what every pound did. Plans with numbers attached, and reporting humans can actually read.", 'Manifesto text', 'manifesto', 'textarea' ),
		'manifesto_accents' => array( 'pdf, pound, deserve, numbers, read', 'Green accent words (comma-separated word starts)', 'manifesto' ),
		'stat1_num'      => array( '90', 'Stat 1 — number', 'manifesto' ),
		'stat1_suffix'   => array( 'K', 'Stat 1 — suffix', 'manifesto' ),
		'stat1_label'    => array( '£ per month — one dental client, 12 months from a standing start', 'Stat 1 — label', 'manifesto' ),
		'stat2_num'      => array( '7', 'Stat 2 — number', 'manifesto' ),
		'stat2_suffix'   => array( '+', 'Stat 2 — suffix', 'manifesto' ),
		'stat2_label'    => array( 'Years growing UK businesses — est. 2019, Sutton, Surrey', 'Stat 2 — label', 'manifesto' ),
		'stat3_num'      => array( '14', 'Stat 3 — number', 'manifesto' ),
		'stat3_suffix'   => array( '+', 'Stat 3 — suffix', 'manifesto' ),
		'stat3_label'    => array( 'Specialists — you talk to the people running your account', 'Stat 3 — label', 'manifesto' ),

		// ── Client results ───────────────────────────────────
		'work_tag'       => array( 'Client results', 'Section label', 'work' ),
		'work_hint'      => array( 'Dental · Construction · E-commerce', 'Right-hand hint', 'work' ),
		'work_more'      => array( 'Be the next result on this page', 'Bottom button label', 'work' ),

		// ── Services (5 fixed panels, each with its own visual) ─
		'services_tag'   => array( "Five things we're genuinely good at", 'Section label', 'services' ),
		'svc1_name'      => array( 'Web Design', 'Service 1 — name', 'services' ),
		'svc1_desc'      => array( 'Fast, conversion-focused websites on WordPress and Shopify. Built to turn visitors into enquiries — not just to look good in a portfolio.', 'Service 1 — description', 'services', 'textarea' ),
		'svc1_tags'      => array( 'WordPress, Shopify, Landing pages', 'Service 1 — tags', 'services' ),
		'svc2_name'      => array( 'Google Ads', 'Service 2 — name', 'services' ),
		'svc2_desc'      => array( 'Campaigns aimed at buyers, not browsers — led by a Head of Paid Media who used to work at Google. Weekly optimisation, transparent reporting, no minimum term.', 'Service 2 — description', 'services', 'textarea' ),
		'svc2_tags'      => array( 'Search, Shopping, Remarketing', 'Service 2 — tags', 'services' ),
		'svc3_name'      => array( 'SEO', 'Service 3 — name', 'services' ),
		'svc3_desc'      => array( 'Technical fixes, content and digital PR that compound month over month — reported as revenue and leads, not just rankings.', 'Service 3 — description', 'services', 'textarea' ),
		'svc3_tags'      => array( 'Technical SEO, Content, Digital PR', 'Service 3 — tags', 'services' ),
		'svc4_name'      => array( 'Content & Brand', 'Service 4 — name', 'services' ),
		'svc4_desc'      => array( 'No jargon, no waffle, no hype. Credible copy and bespoke, hand-crafted logos that give your business instant recognition and trust.', 'Service 4 — description', 'services', 'textarea' ),
		'svc4_tags'      => array( 'Copywriting, Logo design, Brand', 'Service 4 — tags', 'services' ),
		'svc5_name'      => array( 'Paid Social', 'Service 5 — name', 'services' ),
		'svc5_desc'      => array( 'Strategic Facebook and Instagram campaigns that build genuinely engaged audiences — and turn them into measurable sales.', 'Service 5 — description', 'services', 'textarea' ),
		'svc5_tags'      => array( 'Meta ads, Creative, Audiences', 'Service 5 — tags', 'services' ),

		// ── Niche fork ───────────────────────────────────────
		'fork_tag'        => array( 'Where we have an unfair advantage', 'Section label', 'fork' ),
		'fork1_icon'      => array( '🦷', 'Card 1 — emoji', 'fork' ),
		'fork1_title'     => array( 'Run a dental practice?', 'Card 1 — heading', 'fork' ),
		'fork1_text'      => array( 'From squat practices to multi-surgery groups — we fill appointment books. One client went from a standing start to £90K/month in 12 months.', 'Card 1 — body', 'fork', 'textarea' ),
		'fork1_link'      => array( 'Dental marketing', 'Card 1 — link label', 'fork' ),
		'fork1_url'       => array( '#contact', 'Card 1 — link URL', 'fork' ),
		'fork2_icon'      => array( '🛒', 'Card 2 — emoji', 'fork' ),
		'fork2_title'     => array( 'Run an online store?', 'Card 2 — heading', 'fork' ),
		'fork2_text'      => array( 'Google Shopping, paid social and Shopify builds measured on the numbers that matter — ROAS, order value and repeat purchase, not vanity clicks.', 'Card 2 — body', 'fork', 'textarea' ),
		'fork2_link'      => array( 'E-commerce marketing', 'Card 2 — link label', 'fork' ),
		'fork2_url'       => array( '#contact', 'Card 2 — link URL', 'fork' ),

		// ── How it works (3 stacked cards) ──────────────────
		'flow_hint'      => array( "Three words. That's the whole process.", 'Section hint', 'flow' ),
		'flow1_kicker'   => array( 'First, we', 'Step 1 — kicker', 'flow' ),
		'flow1_word'     => array( 'Audit', 'Step 1 — giant word', 'flow' ),
		'flow1_line'     => array( 'Your site, ads and rankings — human-written, free, in 48 hours.', 'Step 1 — line', 'flow', 'textarea' ),
		'flow1_em'       => array( "What's leaking, and what it's costing you.", 'Step 1 — italic payoff', 'flow' ),
		'flow1_time'     => array( 'Free · 48 hours', 'Step 1 — time chip', 'flow' ),
		'flow2_kicker'   => array( 'Then, we', 'Step 2 — kicker', 'flow' ),
		'flow2_word'     => array( 'Plan', 'Step 2 — giant word', 'flow' ),
		'flow2_line'     => array( "Budgets, forecasts and the order we'd do things in.", 'Step 2 — line', 'flow', 'textarea' ),
		'flow2_em'       => array( "Numbers attached — you'll know what every pound is for.", 'Step 2 — italic payoff', 'flow' ),
		'flow2_time'     => array( 'Week 1–2', 'Step 2 — time chip', 'flow' ),
		'flow3_kicker'   => array( 'Every month, we', 'Step 3 — kicker', 'flow' ),
		'flow3_word'     => array( 'Report', 'Step 3 — giant word', 'flow' ),
		'flow3_line'     => array( 'Reporting you can actually read.', 'Step 3 — line', 'flow', 'textarea' ),
		'flow3_em'       => array( 'Plain English, real revenue — and a team you can phone.', 'Step 3 — italic payoff', 'flow' ),
		'flow3_time'     => array( 'Every month', 'Step 3 — time chip', 'flow' ),

		// ── CTA ──────────────────────────────────────────────
		'cta_ring'       => array( 'VENDO DIGITAL — PPC · SEO · WEB — EST. 2019 — SURREY — ', 'Rotating ring text', 'cta' ),
		'cta_l1'         => array( 'What is your site', 'Heading line 1', 'cta' ),
		'cta_em'         => array( 'leaving', 'Heading line 2 — italic word', 'cta' ),
		'cta_l2'         => array( 'on the table', 'Heading line 2 — rest', 'cta' ),
		'cta_btn'        => array( 'Get my free audit', 'Button label', 'cta' ),
		'cta_note'       => array( 'Human-written, yours in 48 hours. We reply within one working day — or call 0207 101 4967.', 'Note under button', 'cta', 'textarea' ),

		// ── Contact & footer ─────────────────────────────────
		'email'          => array( 'hello@vendodigital.co.uk', 'Email address', 'contact' ),
		'phone'          => array( '0207 101 4967', 'Phone (display)', 'contact' ),
		'addr1'          => array( '5 Sandiford Road', 'Address line 1', 'contact' ),
		'addr2'          => array( 'Sutton, Surrey SM3 9RN', 'Address line 2', 'contact' ),
		'city_short'     => array( 'Sutton, Surrey', 'Short location (menu footer)', 'contact' ),
		'ig'             => array( 'https://www.instagram.com/vendo_digital/', 'Instagram URL', 'contact' ),
		'fb'             => array( '#', 'Facebook URL', 'contact' ),
		'li'             => array( 'https://uk.linkedin.com/company/vendo-digital-ltd', 'LinkedIn URL', 'contact' ),
		'yt'             => array( '#', 'YouTube URL', 'contact' ),
		'copyright'      => array( 'Vendo Digital Ltd.', 'Copyright name', 'contact' ),
		'site_url_label' => array( 'www.vendodigital.co.uk', 'Footer site label', 'contact' ),
	);
}

/**
 * Key → default map (used by vendo_mod() in helpers.php).
 *
 * @return array
 */
function vendo_defaults() {
	static $defaults = null;
	if ( null === $defaults ) {
		$defaults = array();
		foreach ( vendo_fields() as $key => $def ) {
			$defaults[ $key ] = $def[0];
		}
	}
	return $defaults;
}

/**
 * Register sections, settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function vendo_customize_register( $wp_customize ) {
	$panel = 'vendo_content';
	$wp_customize->add_panel(
		$panel,
		array(
			'title'    => __( 'Vendo — Page Content', 'vendo' ),
			'priority' => 10,
		)
	);

	$sections = array(
		'hero'      => __( 'Hero', 'vendo' ),
		'marquee'   => __( 'Marquee', 'vendo' ),
		'manifesto' => __( 'Why Vendo + Stats', 'vendo' ),
		'work'      => __( 'Client Results (labels)', 'vendo' ),
		'services'  => __( 'Services', 'vendo' ),
		'fork'      => __( 'Niche Cards', 'vendo' ),
		'flow'      => __( 'How It Works', 'vendo' ),
		'cta'       => __( 'Call To Action', 'vendo' ),
		'contact'   => __( 'Contact & Footer', 'vendo' ),
	);

	$i = 0;
	foreach ( $sections as $id => $title ) {
		$wp_customize->add_section(
			'vendo_' . $id,
			array(
				'title'    => $title,
				'panel'    => $panel,
				'priority' => ++$i,
			)
		);
	}

	foreach ( vendo_fields() as $key => $def ) {
		list( $default, $label, $section ) = $def;
		$type = isset( $def[3] ) ? $def[3] : 'text';

		$wp_customize->add_setting(
			'vendo_' . $key,
			array(
				'default'           => $default,
				'sanitize_callback' => 'textarea' === $type ? 'sanitize_textarea_field' : 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'vendo_' . $key,
			array(
				'label'   => $label,
				'section' => 'vendo_' . $section,
				'type'    => $type,
			)
		);
	}
}
add_action( 'customize_register', 'vendo_customize_register' );
