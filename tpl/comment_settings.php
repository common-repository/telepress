<?php
defined( 'ABSPATH' ) || exit;
?>
<table class="form-table">
    <tr>
        <th scope="row">متغییر های موجود :</th>
        <td>
            <p class="">
				<?php foreach ( wptp_comment_variables() as $key => $title ): ?>
                    <span title="<?php echo $title; ?>"><?php echo $key; ?></span> ,
				<?php endforeach; ?>
            </p>
            <p class="description">
                طریقه استقاده:
                NAME<br>
                <i>متغییرها با مقدارها جایگزین میشوند</i>
            </p>
        </td>
    </tr>
    <tr>
        <th scope="row">قالب :</th>
        <td>
            <textarea type="text" rows="12" cols="30" name="comment_tpl"
                      placeholder="قالب ارسالی متن..."><?php echo wptp_comment_tpl(); ?></textarea>
        </td>
    </tr>
    <tr>
        <th scope="row">ایمیل مدیر :</th>
        <td>
            <input type="email" name="admin_email" value="<?php echo get_option( 'wptp_admin_email' ); ?>">
            <p class="description">
                نظرات ارسالی از طرف این ایمیل به ربات ارسال نمی شوند.
            </p>
        </td>
    </tr>
</table>