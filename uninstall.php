<?php
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

function wptp_uninstall() {
	$option_keys = [
		'wptp_admin_email',
		'wptp_bot_key',
		'wptp_comment_tpl',
		'wptp_bot'
	];
	foreach ( $option_keys as $key ) {
		delete_option( $key );
	}
}

wptp_uninstall();