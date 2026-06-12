<?php
/**
 * Uninstall: remove the plugin's stored options when the plugin is
 * deleted from the Plugins screen (not on deactivate).
 *
 * @package Vendo_SEO
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'vendo_seo_options' );
