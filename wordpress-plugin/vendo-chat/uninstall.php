<?php
/**
 * Uninstall: remove stored options. Enquiry posts are intentionally
 * kept — they're business data; delete them from the dashboard if wanted.
 *
 * @package Vendo_Chat
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'vendo_chat_options' );
