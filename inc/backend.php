<?php

defined( 'ABSPATH' ) || exit;

function wptp_admin_setting() {
	require_once WPTP_TPL . 'settings.php';
}

add_action( 'admin_menu', function () {
	add_menu_page(
		'تله پرس',
		'تله پرس',
		'manage_options',
		'telepress',
		'wptp_admin_setting',
		'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pjxzdmcgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzIgMzI7IiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAzMiAzMiIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+PHN0eWxlIHR5cGU9InRleHQvY3NzIj4KCS5zdDB7ZmlsbDojMjIyQTMwO30KCS5zdDF7ZmlsbDpub25lO3N0cm9rZTojMjIyQTMwO3N0cm9rZS13aWR0aDoxLjg3NjE7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEwO30KCS5zdDJ7ZmlsbDojMTcxNzE1O30KCS5zdDN7ZmlsbC1ydWxlOmV2ZW5vZGQ7Y2xpcC1ydWxlOmV2ZW5vZGQ7ZmlsbDojMjIyQTMwO30KPC9zdHlsZT48ZyBpZD0iTGF5ZXJfMSIvPjxnIGlkPSJpY29ucyI+PGcgaWQ9InRlbGVncmFtIj48cGF0aCBjbGFzcz0ic3QwIiBkPSJNMjEsMTIuMUwxMy4yLDE5Yy0wLjEsMC4xLTAuMSwwLjItMC4xLDAuM2wtMC4zLDIuN2MwLDAuMS0wLjEsMC4xLTAuMiwwbC0xLjItNGMtMC4xLTAuMiwwLTAuNCwwLjItMC41ICAgIGw5LjEtNS43QzIxLDExLjcsMjEuMiwxMiwyMSwxMi4xeiIvPjxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0xNiwyLjJjLTMuOCwwLTcuMywxLjUtOS44LDRjLTIuNSwyLjUtNC4xLDYtNC4xLDkuOGMwLDMuOCwxLjUsNy4zLDQuMSw5LjhjMi41LDIuNSw2LDQsOS44LDQgICAgYzMuOCwwLDcuMy0xLjUsOS44LTRjMi41LTIuNSw0LjEtNiw0LjEtOS44QzI5LjgsOC40LDIzLjYsMi4yLDE2LDIuMnogTTI0LjIsMTAuMWwtMi44LDEzLjZjLTAuMSwwLjYtMC45LDAuOS0xLjQsMC41bC00LjMtMy4yICAgIGwtMi4yLDIuMmMtMC40LDAuNC0xLDAuMi0xLjItMC4zbC0xLjYtNWwtNC4yLTEuM2MtMC42LTAuMi0wLjYtMC45LDAtMS4xbDE2LjctNi41QzIzLjcsOC45LDI0LjQsOS40LDI0LjIsMTAuMXoiLz48L2c+PC9nPjwvc3ZnPg==',
		50 );
} );