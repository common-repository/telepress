<?php
defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'wptpTelegram' ) ) {
	require_once WPTP_INC . 'Telegram.php';
	//require_once WPTP_INC . 'TelegramErrorLogger.php'; //uncomment for log errors in telepress/logs
}

$bot = json_decode( get_option( 'wptp_bot' ), 1 );
define( 'WPTP_CHAT_ID', $bot['chat_id'] );
define( 'WPTP_TOKEN', $bot['token'] );
$tel = wptp_bot_obj();

function wptp_send_comment( $comment, $message_id = null, $inline_message_id = null ) {

	$ibt    = wptp_inline_button_types();
	$tel    = wptp_bot_obj();
	$status = 'نامعلوم';
	switch ( $comment->comment_approved ) {
		case 'hold':
		case '0':
			$status = 'در انتظار بررسی';
			break;
		case 'approve':
		case '1':
			$status = 'تائید شده';
			break;
		case 'spam':
			$status = 'جفنگ';
			break;
		case 'trash':
			$status = 'در زباله دان';
			break;
		default:
			$status = 'خطا';
	}

	//text to send and replacement
	$comment_tpl = wptp_comment_tpl();
	$search      = wptp_comment_variables( 1 );
	$replace     = [
		$comment->comment_author,
		$comment->comment_author_email,
		$comment->comment_content,
		get_the_title( $comment->comment_post_ID ),
		$status,
		$comment->comment_agent,
		$comment->comment_date,
		$comment->comment_post_ID,
		get_permalink( $comment->comment_post_ID )
	];
	$text        = str_replace( $search, $replace, $comment_tpl );
	$text        = ! empty( $text ) ? $text : 'empty';
	//buttons
	$approved     = ( $comment->comment_approved == '1' or $comment->comment_approved == 'approve' ) ? 1 : 0;
	$callback_key = ( empty( $approved ) ? $ibt[0] : $ibt[4] );
	$approved     = ( empty( $approved ) ? '✅ پذیرفتن' : '❌ نپذیرفتن' );

	$key   = [];
	$key[] = [
		$tel->buildInlineKeyboardButton( $approved, '', $callback_key . '_' . $comment->comment_ID ),
		$tel->buildInlineKeyboardButton( '↩️ پاسخ', '', $ibt[1] . '_' . $comment->comment_ID )
	];
	$key[] = [
		$tel->buildInlineKeyboardButton( '🚫 جفنگ', '', $ibt[3] . '_' . $comment->comment_ID ),
		$tel->buildInlineKeyboardButton( '🗑 زباله دان', '', $ibt[2] . '_' . $comment->comment_ID ),
	];
	$key   = $tel->buildInlineKeyBoard( $key );
	//send or edit message
	if ( $message_id ) {
		$tel->editMessageText( [
			'chat_id'           => $tel->ChatID(),
			'text'              => $text,
			'reply_markup'      => $key,
			'inline_message_id' => $inline_message_id,
			'message_id'        => $message_id
		] );
	} else {
		$tel->sendMessage( [ 'chat_id' => WPTP_CHAT_ID, 'text' => $text, 'reply_markup' => $key ] );
	}
}

function wptp_bot_obj() {
	global $tel;
	if ( empty( $tel ) ) {
		return new wptpTelegram( WPTP_TOKEN );
	}

	return $tel;
}

function wptp_inline_button_types() {
	return $inline_button_types = [ 'approve', 'reply', 'trash', 'spam', 'hold' ];
}

function wptp_send_message( $msg = 'empty' ) {
	$tel = wptp_bot_obj();
	if ( empty( $msg ) ) {
		$msg = 'empty message';
	}

	$tel->sendMessage( [ 'chat_id' => WPTP_CHAT_ID, 'text' => $msg ] );
}

function wptp_reply_to_comment( $parent, $post_id, $text ) {

	$email  = get_userdata( 1 )->data->user_email;
	$author = get_userdata( 1 )->data->display_name;

	$comment_data = [
		'comment_content'      => $text,
		'comment_parent'       => $parent,
		'user_id'              => 1,
		'comment_author_email' => $email,
		'comment_author'       => $author ? $author : '',
		'comment_post_ID'      => $post_id
	];
	$res          = wp_insert_comment( $comment_data );

	return intval( $res );
}

function wptp_set_web_hook( $token, $return_url = false ) {

	$key = get_option( 'wptp_bot_key' );
	if ( empty( $key ) ) {
		$key = wp_generate_password( 20, 0 );
		update_option( 'wptp_bot_key', $key, false );
	}
	$api_url = get_rest_url( '', '/tp/handler/' . $key );
	$url     = 'https://api.telegram.org/bot' . $token . '/setwebhook?url=' . $api_url;
	if ( $return_url ) {
		return $url;
	}

	$request = wp_remote_get( $url );
	$request = wp_remote_retrieve_body( $request );
	$request = json_decode( $request, 1 );
	if ( $request['ok'] == true && isset( $request['result'] ) && $request['result'] == true ) {
		return true;
	} else {
		return false;
	}
}

//
$callback_query = $tel->Callback_Query();

if ( ! empty( $callback_query ) ) {
	$callback_query_id = $callback_query['id'];
	$data              = explode( '_', sanitize_textarea_field( $callback_query['data'] ) );
	$type              = $data[0];
	$ID                = $data[1];
	if ( $tel->ChatID() == WPTP_CHAT_ID ) {
		if ( empty( get_comment( $ID ) ) ) {
			$text = 'نظر وجود ندارد!';
		} else {
			if ( $type == 'reply' ) {
				$text = '';
				$tel->sendMessage( [
					'chat_id'             => WPTP_CHAT_ID,
					'text'                => 'ID: ' . $ID . "\n" . 'پاسخ را بنویسید:',
					'reply_to_message_id' => $callback_query['message']['message_id'],
					'reply_markup'        => $tel->buildForceReply()
				] );
			} else {
				$set_status = wp_set_comment_status( $ID, $type );
				if ( $set_status == true ) {
					$comment = get_comment( $ID );
					wptp_send_comment( $comment, $callback_query['message']['message_id'], $callback_query_id );
					$text = 'با موفقیت تغییر یافت';
				} else {
					$text = 'خطا!';
				}
			}
		}
	} else {
		$text = '❌ شما مدیر ربات نیستید.';
	}

	$tel->answerCallbackQuery( [ 'callback_query_id' => $callback_query_id, 'text' => $text ] );
}

$text = $tel->getData();
if ( ( isset( $text['message']['text'] ) && ! empty( $text['message']['text'] ) ) &&
     ( isset( $text['message']['reply_to_message']['text'] ) &&
       ! empty( $text['message']['reply_to_message']['text'] ) )
) {

	if ( $tel->ChatID() == WPTP_CHAT_ID ) {
		$text   = $text['message']['reply_to_message']['text'];
		$search = 'پاسخ را بنویسید:';
		if ( strstr( $text, $search ) ) {
			$ID = explode( "\n", $text );
			$ID = str_replace( 'ID: ', '', $ID[0] );
			$ID = intval( $ID );
			wp_set_comment_status( $ID, 'approve' );
			$postID           = get_comment( $ID )->comment_post_ID;
			$reply_to_comment = wptp_reply_to_comment( $ID, $postID, strip_tags( $tel->Text() ) );
			if ( $reply_to_comment ) {
				wptp_send_message( 'نظر مربوطه تائید و پاسخ آن ثبت شد.' );
			} else {
				wptp_send_message( 'خطا!' );
			}
		}
	}
}
