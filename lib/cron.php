<?php

function gcc_renew_oauth_hook() {
	$oauth = get_option( 'gcc-oauth-settings' );
	if( ! isset( $oauth['oauth-code'] ) )
		return;

	ggc_get_access_token( $oauth['oauth-code'] );
}
