<?php
defined( 'ABSPATH' ) || exit;

/**
 * Plugin Name:  TelePress
 * Description: Send and manage wordpress comments in telegram bot
 * Version: 1.0
 * Author: siamak dal
 * Author URI: https://t.me/s_n_m_d
 * Requires at least: 4
 * Tested up to: 5.6
 */

//Plugin prefix = wptp = Wordpress TelePress

define( 'WPTP_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'WPTP_INC', trailingslashit( WPTP_DIR . 'inc' ) );
define( 'WPTP_TPL', trailingslashit( WPTP_DIR . 'tpl' ) );

register_activation_hook( __FILE__, function () {
	$admin_email = get_option( 'wptp_admin_email' );
	if ( empty( $admin_email ) ) {
		update_option( 'wptp_admin_email', get_userdata( 1 )->user_email );
	}
} );

require_once WPTP_INC . 'public_functions.php';

if ( is_admin() ) {
	require_once WPTP_INC . 'backend.php';
} else {
	require_once WPTP_INC . 'frontend.php';
}