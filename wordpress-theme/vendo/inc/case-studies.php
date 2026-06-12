<?php
/**
 * "Case Studies" custom post type — powers the Client Results grid.
 *
 * Each case study: title, excerpt (the one-line blurb), and a meta box
 * for tags, card background and which CSS mock visual to show.
 * Order the grid with the post "Order" attribute (menu_order).
 *
 * @package Vendo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the post type.
 */
function vendo_register_case_cpt() {
	register_post_type(
		'vendo_case',
		array(
			'labels'        => array(
				'name'          => __( 'Case Studies', 'vendo' ),
				'singular_name' => __( 'Case Study', 'vendo' ),
				'add_new_item'  => __( 'Add New Case Study', 'vendo' ),
				'edit_item'     => __( 'Edit Case Study', 'vendo' ),
			),
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'menu_icon'     => 'dashicons-chart-line',
			'menu_position' => 20,
			'supports'      => array( 'title', 'excerpt', 'page-attributes' ),
			'hierarchical'  => false,
		)
	);
}
add_action( 'init', 'vendo_register_case_cpt' );

/**
 * Meta box: tags, background, mock visual.
 */
function vendo_case_meta_box() {
	add_meta_box( 'vendo_case_meta', __( 'Card Settings', 'vendo' ), 'vendo_case_meta_render', 'vendo_case', 'side' );
}
add_action( 'add_meta_boxes', 'vendo_case_meta_box' );

/**
 * Render the meta box.
 *
 * @param WP_Post $post Current post.
 */
function vendo_case_meta_render( $post ) {
	wp_nonce_field( 'vendo_case_meta', 'vendo_case_nonce' );

	$tags = get_post_meta( $post->ID, '_vendo_tags', true );
	$bg   = get_post_meta( $post->ID, '_vendo_bg', true ) ?: 'sage';
	$mock = get_post_meta( $post->ID, '_vendo_mock', true ) ?: 'app';

	$bgs   = array(
		'sage'     => __( 'Sage (dark green)', 'vendo' ),
		'charcoal' => __( 'Charcoal', 'vendo' ),
		'green'    => __( 'Vendo Green', 'vendo' ),
	);
	$mocks = array(
		'app'    => __( 'App window', 'vendo' ),
		'dash'   => __( 'Analytics dashboard', 'vendo' ),
		'mobile' => __( 'Two phones', 'vendo' ),
		'web'    => __( 'Website layout', 'vendo' ),
	);
	?>
	<p>
		<label for="vendo_tags"><strong><?php esc_html_e( 'Tags (comma-separated)', 'vendo' ); ?></strong></label>
		<input type="text" id="vendo_tags" name="vendo_tags" class="widefat" value="<?php echo esc_attr( $tags ); ?>" placeholder="Web design, Google Ads" />
	</p>
	<p>
		<label for="vendo_bg"><strong><?php esc_html_e( 'Card background', 'vendo' ); ?></strong></label>
		<select id="vendo_bg" name="vendo_bg" class="widefat">
			<?php foreach ( $bgs as $val => $label ) : ?>
				<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $bg, $val ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<p>
		<label for="vendo_mock"><strong><?php esc_html_e( 'Visual style', 'vendo' ); ?></strong></label>
		<select id="vendo_mock" name="vendo_mock" class="widefat">
			<?php foreach ( $mocks as $val => $label ) : ?>
				<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $mock, $val ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<p class="description"><?php esc_html_e( 'The one-line blurb under the title comes from the Excerpt field. Use the Order attribute to arrange the grid.', 'vendo' ); ?></p>
	<?php
}

/**
 * Save the meta box.
 *
 * @param int $post_id Post ID.
 */
function vendo_case_meta_save( $post_id ) {
	if (
		! isset( $_POST['vendo_case_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( $_POST['vendo_case_nonce'] ), 'vendo_case_meta' ) ||
		( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
		! current_user_can( 'edit_post', $post_id )
	) {
		return;
	}

	if ( isset( $_POST['vendo_tags'] ) ) {
		update_post_meta( $post_id, '_vendo_tags', sanitize_text_field( wp_unslash( $_POST['vendo_tags'] ) ) );
	}
	if ( isset( $_POST['vendo_bg'] ) ) {
		$bg = sanitize_key( $_POST['vendo_bg'] );
		update_post_meta( $post_id, '_vendo_bg', in_array( $bg, array( 'sage', 'charcoal', 'green' ), true ) ? $bg : 'sage' );
	}
	if ( isset( $_POST['vendo_mock'] ) ) {
		$mock = sanitize_key( $_POST['vendo_mock'] );
		update_post_meta( $post_id, '_vendo_mock', in_array( $mock, array( 'app', 'dash', 'mobile', 'web' ), true ) ? $mock : 'app' );
	}
}
add_action( 'save_post_vendo_case', 'vendo_case_meta_save' );
