<?php
/**
 * Plugin Name:       Vendo Chat
 * Plugin URI:        https://github.com/sahan4115/vendo-digital
 * Description:       AI enquiry assistant for Vendo Digital. A branded chat widget that answers visitor questions (Claude AI when an API key is set, smart guided mode otherwise) and captures enquiries into the dashboard with email notifications.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Vendo Digital
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       vendo-chat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VENDO_CHAT_VERSION', '1.0.0' );
define( 'VENDO_CHAT_OPTION', 'vendo_chat_options' );

/* ==============================================================
   Options
   ============================================================== */

/**
 * Defaults.
 *
 * @return array
 */
function vchat_defaults() {
	return array(
		'enabled'      => 1,
		'api_key'      => '',
		'model'        => 'claude-opus-4-8',
		'notify_email' => get_option( 'admin_email' ),
		'public_email' => 'hello@vendodigital.co.uk',
		'public_phone' => '0207 101 4967',
		'extra_prompt' => '',
	);
}

/**
 * Read one option merged with defaults.
 *
 * @param string $key Option key.
 * @return mixed
 */
function vchat_get( $key ) {
	$opts = wp_parse_args( (array) get_option( VENDO_CHAT_OPTION, array() ), vchat_defaults() );
	return isset( $opts[ $key ] ) ? $opts[ $key ] : '';
}

/* ==============================================================
   Front-end widget
   ============================================================== */

/**
 * Enqueue the widget and hand it its config.
 */
function vchat_assets() {
	if ( ! vchat_get( 'enabled' ) ) {
		return;
	}
	wp_enqueue_style( 'vendo-chat', plugins_url( 'assets/chat.css', __FILE__ ), array(), VENDO_CHAT_VERSION );
	wp_enqueue_script( 'vendo-chat', plugins_url( 'assets/chat.js', __FILE__ ), array(), VENDO_CHAT_VERSION, true );

	$cfg = array(
		'restUrl' => esc_url_raw( rest_url( 'vendo-chat/v1/' ) ),
		'ai'      => (bool) vchat_get( 'api_key' ),
		'email'   => vchat_get( 'public_email' ),
		'phone'   => vchat_get( 'public_phone' ),
	);
	wp_add_inline_script( 'vendo-chat', 'window.VENDO_CHAT_CFG = ' . wp_json_encode( $cfg ) . ';', 'before' );
}
add_action( 'wp_enqueue_scripts', 'vchat_assets' );

/* ==============================================================
   REST API
   ============================================================== */

/**
 * Routes: POST /message (AI proxy), POST /enquiry (lead capture).
 * Public endpoints — visitors aren't logged in — protected by
 * per-IP rate limits, strict input caps and a honeypot.
 */
function vchat_rest_routes() {
	register_rest_route(
		'vendo-chat/v1',
		'/message',
		array(
			'methods'             => 'POST',
			'callback'            => 'vchat_rest_message',
			'permission_callback' => '__return_true',
		)
	);
	register_rest_route(
		'vendo-chat/v1',
		'/enquiry',
		array(
			'methods'             => 'POST',
			'callback'            => 'vchat_rest_enquiry',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'vchat_rest_routes' );

/**
 * Sliding per-IP rate limit using transients.
 *
 * @param string $bucket Limiter name.
 * @param int    $max    Allowed hits per window.
 * @param int    $window Window in seconds.
 * @return bool True when over the limit.
 */
function vchat_rate_limited( $bucket, $max, $window ) {
	$ip    = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$key   = 'vchat_' . $bucket . '_' . md5( $ip );
	$count = (int) get_transient( $key );
	if ( $count >= $max ) {
		return true;
	}
	set_transient( $key, $count + 1, $window );
	return false;
}

/**
 * The assistant's system prompt — Vendo facts + behavioural rules.
 *
 * @return string
 */
function vchat_system_prompt() {
	$prompt = <<<'PROMPT'
You are Venny, the friendly assistant on the Vendo Digital website (vendodigital.co.uk).

About Vendo Digital:
- A PPC, SEO and web design agency at 5 Sandiford Road, Sutton, Surrey SM3 9RN. Founded 2019. Around 14 specialists.
- Services: Web design (WordPress & Shopify), Google Ads (led by a Head of Paid Media who used to work at Google), SEO (technical, content, digital PR), Paid Social (Facebook/Instagram), Copywriting & logo design.
- Specialisms: dental practice marketing (one client went from a standing start to £90K/month revenue in 12 months) and e-commerce.
- Offer: a FREE site audit — human-written, covers the visitor's site, ads and rankings, delivered within 48 hours.
- Contact: hello@vendodigital.co.uk, 0207 101 4967. Replies within one working day.

Rules:
- Keep every reply under 80 words. Plain text only — no markdown headings, bullets sparingly.
- Be warm, plain-spoken and concrete. No jargon, no hype, no waffle.
- Your goal is to answer the visitor's question briefly, then guide them toward the free audit or leaving contact details.
- NEVER invent prices, statistics, client names or guarantees. If you don't know something, say the team can answer it and suggest leaving an email.
- If the visitor wants to speak to a human, give the phone number and email above.
- Respond only with your final answer — no reasoning, no meta-commentary.
PROMPT;

	$extra = trim( (string) vchat_get( 'extra_prompt' ) );
	if ( $extra ) {
		$prompt .= "\n\nAdditional site-owner notes:\n" . $extra;
	}
	return $prompt;
}

/**
 * POST /message — proxy the conversation to the Anthropic Messages API.
 * The API key never leaves the server.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response
 */
function vchat_rest_message( WP_REST_Request $request ) {
	if ( vchat_rate_limited( 'msg', 10, MINUTE_IN_SECONDS ) ) {
		return new WP_REST_Response( array( 'error' => 'rate_limited' ), 429 );
	}

	$api_key = vchat_get( 'api_key' );
	if ( ! $api_key || ! vchat_get( 'enabled' ) ) {
		return new WP_REST_Response( array( 'error' => 'ai_disabled' ), 503 );
	}

	// Sanitize history: text-only, alternating-ish, hard caps.
	$raw      = (array) $request->get_param( 'messages' );
	$messages = array();
	foreach ( array_slice( $raw, -12 ) as $m ) {
		if ( ! is_array( $m ) || empty( $m['content'] ) || empty( $m['role'] ) ) {
			continue;
		}
		$role = 'user' === $m['role'] ? 'user' : 'assistant';
		$text = mb_substr( sanitize_textarea_field( (string) $m['content'] ), 0, 1000 );
		if ( '' === $text ) {
			continue;
		}
		$messages[] = array( 'role' => $role, 'content' => $text );
	}
	if ( empty( $messages ) || 'user' !== $messages[0]['role'] ) {
		array_unshift( $messages, array( 'role' => 'user', 'content' => 'Hi' ) );
	}
	if ( 'user' !== end( $messages )['role'] ) {
		return new WP_REST_Response( array( 'error' => 'bad_request' ), 400 );
	}

	$body = array(
		'model'      => vchat_get( 'model' ),
		'max_tokens' => 300,
		'system'     => vchat_system_prompt(),
		'messages'   => $messages,
	);

	$response = wp_remote_post(
		'https://api.anthropic.com/v1/messages',
		array(
			'timeout' => 30,
			'headers' => array(
				'x-api-key'         => $api_key,
				'anthropic-version' => '2023-06-01',
				'content-type'      => 'application/json',
			),
			'body'    => wp_json_encode( $body ),
		)
	);

	if ( is_wp_error( $response ) ) {
		return new WP_REST_Response( array( 'error' => 'upstream_unreachable' ), 502 );
	}

	$code = wp_remote_retrieve_response_code( $response );
	$data = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( 200 !== $code || empty( $data['content'] ) ) {
		// Don't leak upstream details to visitors; log for the admin.
		if ( function_exists( 'error_log' ) ) {
			error_log( 'Vendo Chat: Anthropic API error ' . $code . ' — ' . wp_json_encode( isset( $data['error'] ) ? $data['error'] : null ) ); // phpcs:ignore
		}
		return new WP_REST_Response( array( 'error' => 'upstream_error' ), 502 );
	}

	$reply = '';
	foreach ( $data['content'] as $block ) {
		if ( isset( $block['type'] ) && 'text' === $block['type'] ) {
			$reply .= $block['text'];
		}
	}

	return new WP_REST_Response( array( 'reply' => trim( $reply ) ), 200 );
}

/**
 * POST /enquiry — store the lead + notify by email.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response
 */
function vchat_rest_enquiry( WP_REST_Request $request ) {
	if ( vchat_rate_limited( 'enq', 5, 10 * MINUTE_IN_SECONDS ) ) {
		return new WP_REST_Response( array( 'error' => 'rate_limited' ), 429 );
	}

	// Honeypot: the widget always sends website="" — bots fill it.
	if ( '' !== (string) $request->get_param( 'website' ) ) {
		return new WP_REST_Response( array( 'ok' => true ), 200 ); // pretend success
	}

	$name    = mb_substr( sanitize_text_field( (string) $request->get_param( 'name' ) ), 0, 100 );
	$email   = sanitize_email( (string) $request->get_param( 'email' ) );
	$phone   = mb_substr( sanitize_text_field( (string) $request->get_param( 'phone' ) ), 0, 40 );
	$topic   = mb_substr( sanitize_text_field( (string) $request->get_param( 'topic' ) ), 0, 120 );
	$message = mb_substr( sanitize_textarea_field( (string) $request->get_param( 'message' ) ), 0, 2000 );

	if ( ! $name || ! is_email( $email ) || ! $message ) {
		return new WP_REST_Response( array( 'error' => 'invalid' ), 400 );
	}

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'vendo_enquiry',
			'post_status'  => 'private',
			'post_title'   => $name . ' — ' . $topic,
			'post_content' => $message,
		),
		true
	);
	if ( is_wp_error( $post_id ) ) {
		return new WP_REST_Response( array( 'error' => 'save_failed' ), 500 );
	}
	update_post_meta( $post_id, '_vchat_email', $email );
	update_post_meta( $post_id, '_vchat_phone', $phone );
	update_post_meta( $post_id, '_vchat_topic', $topic );

	$to = vchat_get( 'notify_email' );
	if ( is_email( $to ) ) {
		$body = "New website enquiry (via Vendo Chat)\n\n" .
			'Name:  ' . $name . "\n" .
			'Email: ' . $email . "\n" .
			( $phone ? 'Phone: ' . $phone . "\n" : '' ) .
			'Topic: ' . $topic . "\n\n" .
			$message . "\n\n" .
			'View in dashboard: ' . admin_url( 'edit.php?post_type=vendo_enquiry' );
		wp_mail( $to, '[Vendo] New enquiry from ' . $name, $body, array( 'Reply-To: ' . $email ) );
	}

	return new WP_REST_Response( array( 'ok' => true ), 200 );
}

/* ==============================================================
   Enquiries post type (dashboard inbox)
   ============================================================== */

/**
 * Register the Enquiries CPT.
 */
function vchat_register_cpt() {
	register_post_type(
		'vendo_enquiry',
		array(
			'labels'       => array(
				'name'          => __( 'Enquiries', 'vendo-chat' ),
				'singular_name' => __( 'Enquiry', 'vendo-chat' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'menu_icon'    => 'dashicons-format-chat',
			'supports'     => array( 'title', 'editor' ),
			'capabilities' => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap' => true,
		)
	);
}
add_action( 'init', 'vchat_register_cpt' );

/**
 * Admin list columns: email / phone / topic.
 *
 * @param array $columns Columns.
 * @return array
 */
function vchat_admin_columns( $columns ) {
	$columns['vchat_email'] = __( 'Email', 'vendo-chat' );
	$columns['vchat_phone'] = __( 'Phone', 'vendo-chat' );
	$columns['vchat_topic'] = __( 'Topic', 'vendo-chat' );
	return $columns;
}
add_filter( 'manage_vendo_enquiry_posts_columns', 'vchat_admin_columns' );

/**
 * Render the custom columns.
 *
 * @param string $column  Column id.
 * @param int    $post_id Post.
 */
function vchat_admin_column_content( $column, $post_id ) {
	if ( 'vchat_email' === $column ) {
		$email = get_post_meta( $post_id, '_vchat_email', true );
		echo $email ? '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>' : '—';
	} elseif ( 'vchat_phone' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_vchat_phone', true ) ?: '—' );
	} elseif ( 'vchat_topic' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_vchat_topic', true ) ?: '—' );
	}
}
add_action( 'manage_vendo_enquiry_posts_custom_column', 'vchat_admin_column_content', 10, 2 );

/* ==============================================================
   Settings page
   ============================================================== */

/**
 * Register the page.
 */
function vchat_admin_menu() {
	add_options_page( __( 'Vendo Chat', 'vendo-chat' ), __( 'Vendo Chat', 'vendo-chat' ), 'manage_options', 'vendo-chat', 'vchat_render_page' );
}
add_action( 'admin_menu', 'vchat_admin_menu' );

/**
 * Settings link on the Plugins row.
 *
 * @param array $links Links.
 * @return array
 */
function vchat_action_links( $links ) {
	array_unshift( $links, '<a href="' . esc_url( admin_url( 'options-general.php?page=vendo-chat' ) ) . '">' . esc_html__( 'Settings', 'vendo-chat' ) . '</a>' );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'vchat_action_links' );

/**
 * Settings API registration.
 */
function vchat_admin_init() {
	register_setting( 'vendo_chat', VENDO_CHAT_OPTION, array( 'sanitize_callback' => 'vchat_sanitize' ) );
	add_settings_section( 'vchat_main', __( 'Widget', 'vendo-chat' ), '__return_false', 'vendo-chat' );
	add_settings_section( 'vchat_ai', __( 'AI (Claude)', 'vendo-chat' ), 'vchat_ai_section_note', 'vendo-chat' );

	$fields = array(
		'enabled'      => array( __( 'Enable chat widget', 'vendo-chat' ), 'checkbox', 'vchat_main', '' ),
		'notify_email' => array( __( 'Send enquiries to', 'vendo-chat' ), 'text', 'vchat_main', __( 'Email address that receives new enquiry notifications.', 'vendo-chat' ) ),
		'public_email' => array( __( 'Public email (shown to visitors)', 'vendo-chat' ), 'text', 'vchat_main', '' ),
		'public_phone' => array( __( 'Public phone (shown to visitors)', 'vendo-chat' ), 'text', 'vchat_main', '' ),
		'api_key'      => array( __( 'Anthropic API key', 'vendo-chat' ), 'password', 'vchat_ai', __( 'From console.anthropic.com. Leave empty to run the widget in guided mode (no AI, no cost).', 'vendo-chat' ) ),
		'model'        => array( __( 'Model', 'vendo-chat' ), 'model', 'vchat_ai', '' ),
		'extra_prompt' => array( __( 'Extra knowledge for the assistant', 'vendo-chat' ), 'textarea', 'vchat_ai', __( 'Optional. Facts or instructions to add to the assistant, e.g. current offers or opening hours.', 'vendo-chat' ) ),
	);
	foreach ( $fields as $key => $def ) {
		add_settings_field( $key, $def[0], 'vchat_render_field', 'vendo-chat', $def[2], array( 'key' => $key, 'type' => $def[1], 'desc' => $def[3], 'label_for' => 'vchat_' . $key ) );
	}
}
add_action( 'admin_init', 'vchat_admin_init' );

/**
 * Note under the AI section.
 */
function vchat_ai_section_note() {
	echo '<p class="description">' . esc_html__( 'With an API key, visitor questions are answered by Claude through a server-side proxy (the key is never exposed to the browser). Without one, the widget still works in guided mode and still captures enquiries.', 'vendo-chat' ) . '</p>';
}

/**
 * Sanitize options.
 *
 * @param array $input Raw.
 * @return array
 */
function vchat_sanitize( $input ) {
	$clean            = array();
	$clean['enabled'] = empty( $input['enabled'] ) ? 0 : 1;
	$clean['api_key'] = isset( $input['api_key'] ) ? trim( sanitize_text_field( $input['api_key'] ) ) : '';
	$models           = array( 'claude-opus-4-8', 'claude-sonnet-4-6', 'claude-haiku-4-5' );
	$clean['model']   = ( isset( $input['model'] ) && in_array( $input['model'], $models, true ) ) ? $input['model'] : 'claude-opus-4-8';
	$clean['notify_email'] = isset( $input['notify_email'] ) ? sanitize_email( $input['notify_email'] ) : '';
	$clean['public_email'] = isset( $input['public_email'] ) ? sanitize_email( $input['public_email'] ) : '';
	$clean['public_phone'] = isset( $input['public_phone'] ) ? sanitize_text_field( $input['public_phone'] ) : '';
	$clean['extra_prompt'] = isset( $input['extra_prompt'] ) ? sanitize_textarea_field( $input['extra_prompt'] ) : '';
	return $clean;
}

/**
 * Render one field.
 *
 * @param array $args key/type/desc.
 */
function vchat_render_field( $args ) {
	$key   = $args['key'];
	$id    = 'vchat_' . $key;
	$name  = VENDO_CHAT_OPTION . '[' . $key . ']';
	$value = vchat_get( $key );

	switch ( $args['type'] ) {
		case 'checkbox':
			echo '<label><input type="checkbox" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="1" ' . checked( $value, 1, false ) . ' /> ' . esc_html__( 'Enabled', 'vendo-chat' ) . '</label>';
			break;
		case 'password':
			echo '<input type="password" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="regular-text" autocomplete="off" />';
			break;
		case 'textarea':
			echo '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" rows="4" class="large-text">' . esc_textarea( $value ) . '</textarea>';
			break;
		case 'model':
			$models = array(
				'claude-opus-4-8'   => __( 'Claude Opus 4.8 — most capable (default)', 'vendo-chat' ),
				'claude-sonnet-4-6' => __( 'Claude Sonnet 4.6 — fast, balanced cost', 'vendo-chat' ),
				'claude-haiku-4-5'  => __( 'Claude Haiku 4.5 — cheapest, fastest', 'vendo-chat' ),
			);
			echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '">';
			foreach ( $models as $val => $label ) {
				echo '<option value="' . esc_attr( $val ) . '" ' . selected( $value, $val, false ) . '>' . esc_html( $label ) . '</option>';
			}
			echo '</select>';
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
function vchat_render_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Vendo Chat', 'vendo-chat' ); ?></h1>
		<p><?php esc_html_e( 'The chat widget appears bottom-right on every page. Enquiries are stored under the "Enquiries" menu and emailed to you.', 'vendo-chat' ); ?></p>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'vendo_chat' );
			do_settings_sections( 'vendo-chat' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}
