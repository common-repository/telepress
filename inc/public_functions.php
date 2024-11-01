<?php

defined( 'ABSPATH' ) || exit;

function wptp_comment_variables( $just_keys = false ) {

	$variables = [
		'NAME'       => 'نام',
		'EMAIL'      => 'ایمیل',
		'COMMENT'    => 'نظر',
		'POST_TITLE' => 'عنوان پست',
		'STATUS'     => 'وضعیت نظر',
		'AGENT'      => 'مشخصات',
		'DATE'       => 'تاریخ',
		'POST_ID'    => 'آیدی پست',
		'POST_LINK'  => 'لینک پست',
	];
	if ( $just_keys ) {
		return array_keys( $variables );
	}

	return $variables;
}

function wptp_comment_tpl( $default = false ) {
	$tpl = '🔖 نظر جدیدی ارسال شد:
📄 پست: POST_TITLE
👤 فرستنده: NAME
✉️ ایمیل : EMAIL
📆 تاریخ : DATE
💬 نظر:
COMMENT
📍 وضعیت کنونی: STATUS
🌐 agent: AGENT';
	if ( $default ) {
		return $tpl;
	}

	$db_tpl = get_option( 'wptp_comment_tpl' );
	if ( ! empty( $db_tpl ) ) {
		return $db_tpl;
	}

	return $tpl;
}