<?php
defined( 'ABSPATH' ) || exit;
$bot = json_decode( get_option( 'wptp_bot' ), 1 );
?>
<table class="form-table">
    <tr>
        <th scope="row">توکن ربات :</th>
        <td>
            <input type="text" name="token" value="<?php echo $bot['token']; ?>" placeholder="توکن...">
            <p class="description">
                توکن رباتی که ساخته اید
                <i>
                    <a href="https://fullkade.com/1395/04/telegram-botfather/" target="_blank">آموزش ساخت ربات تلگرام</a>
                </i>
            </p>
        </td>
    </tr>
    <tr>
        <th scope="row">چت آیدی مدیر ربات :</th>
        <td>
            <input type="text" name="chat_id" value="<?php echo $bot['chat_id']; ?>"
                   placeholder="چت آیدی خود را وارد کنید...">
            <p class="description">
                برای دریافت چت آیدی خود می توانید از ربات @ShowChatIdBot استفاده کنید
            </p>
        </td>
    </tr>
</table>