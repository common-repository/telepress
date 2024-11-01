<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="wrap">
    <h2>تنظیمات تله پرس</h2>
	<?php
	$tabs       = [ 'bot' => 'ربات', 'comment' => 'کامنت' ];
	$active_tab = isset( $_GET["tab"] ) ? sanitize_text_field( $_GET["tab"] ) : "bot";

	if ( isset( $_POST['submit'] ) ) {

		switch ( $active_tab ) {
			case 'bot':
				require_once WPTP_INC . 'bot.php';
				$token   = sanitize_text_field( $_POST['token'] );
				$chat_id = sanitize_text_field( $_POST['chat_id'] );
				$bot     = json_decode( get_option( 'wptp_bot' ), 1 );

				if ( ! empty( $token ) && ! empty( $chat_id ) ) {
					$status  = false;
					$options = [ 'token' => $token, 'chat_id' => $chat_id, 'status' => $status ];


					if ( $token != $bot['token'] or $chat_id != $bot['chat_id'] ) {

						if ( $token != $bot['token'] ) {
							if ( update_option( 'wptp_bot', json_encode( $options ), 0 ) ) {
								if ( wptp_set_web_hook( $token ) ) {
									$msg = [ 'updated', 'ربات با موفقیت به سایت متصل شد.' ];
									wptp_send_message( ( isset( $msg[1] ) && ! empty( $msg[1] ) ) ? $msg[1] : 'erm' );
								} else {
									$tag_a = '<a href="' . wptp_set_web_hook( $token, 1 ) . '" target="_blank">اینجا کلیک کنید.</a>';
									$msg   = [
										'error',
										'خطا در برقراری ارتباط با تلگرام یا اینکه توکن اشتباه است. برای تنظیم دستی وب هوک با VPN ' . $tag_a
									];
								}
							}

						} else {
							if ( update_option( 'wptp_bot', json_encode( $options ), 0 ) ) {
								$msg = [ 'updated', 'چت آیدی مدیر ربات با موفقیت تغییر یافت.' ];
								wptp_send_message( 'چت آیدی ربات با موفقیت به شما ' . $chat_id . ' تغییر یافت. ' .
								                   'و از این پس نظرات برای شما ارسال خواهند شد.' );
							}
						}
					}

				} else {
					$msg = [ 'error', 'فیلد توکن یا چت آیدی خالی میباشد.' ];
				}
				wptp_send_message( 'ربات متصل است.' );
				break;
			case 'comment':

				$comment_tpl = isset( $_POST['comment_tpl'] ) ? sanitize_textarea_field( $_POST['comment_tpl'] ) : '';
				$admin_email = sanitize_email( $_POST['admin_email'] );
				$admin_email = update_option( 'wptp_admin_email', $admin_email, false );

				$msg = $admin_email ? [ 'updated', 'تغییرات ذخیره شد.' ] : '';
				if ( ! empty( $comment_tpl ) ) {
					if ( update_option( 'wptp_comment_tpl', $comment_tpl, 0 ) ) {
						$msg = [ 'updated', 'تغییرات ذخیره شد.' ];
					}
				} else {
					$msg = [ 'error', 'فیلد قالب خالی است.' ];
				}
				break;
			default:
				$msg = [ 'error', 'خطای نامعلوم' ];
		}

		if ( isset( $msg ) && ! empty( $msg ) ) {
			echo '<div class="notice is-dismissible ' . ' ' . $msg[0] . '"> 
                <p>' . $msg[1] . '</p>
              <button type="button" class="notice-dismiss"><span class="screen-reader-text">رد کردن این اخطار</span></button></div>';
		}
	}
	?>

    <h2 class="nav-tab-wrapper">
		<?php foreach ( $tabs as $key => $name ):
			$active_tab_this_is = ( $active_tab == $key ? ' nav-tab-active' : '' );
			?>
            <a href="?page=telepress&tab=<?php echo $key ?>"
               class="nav-tab <?php echo $active_tab_this_is; ?>"><?php echo $name; ?></a>
		<?php endforeach; ?>
    </h2>

    <form method="post">
		<?php require_once WPTP_TPL . $active_tab . '_settings.php'; ?>
		<?php submit_button(); ?>
    </form>
</div>