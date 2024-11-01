<?php
defined( 'ABSPATH' ) || exit;

function wptp_bot_handler( $data = null ) {

	$key    = ( isset( $data['key'] ) && ! empty( $data['key'] ) ) ? $data['key'] : '';
	$db_key = get_option( 'wptp_bot_key' );

	if ( $key == $db_key ) {
		require_once WPTP_INC . 'bot.php';
	}
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'tp', 'handler/', [
		'methods'  => [ 'GET', 'POST' ],
		'callback' => 'wptp_bot_handler',
	] );
	register_rest_route( 'tp', 'handler/(?P<key>\w+)', [
		'methods'  => [ 'GET', 'POST' ],
		'callback' => 'wptp_bot_handler',
	] );
} );

add_action( 'wp_insert_comment', function ( $id, $comment ) {

	if ( $comment->comment_type != 'comment' or get_option( 'wptp_admin_email' ) == $comment->comment_author_email ) {
		return;
	}

	require_once WPTP_INC . 'bot.php';
	wptp_send_comment( $comment );

}, 20, 2 );
