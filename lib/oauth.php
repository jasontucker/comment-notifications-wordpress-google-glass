<?php

function ggc_catch_oauth_response() {

	$screen = get_current_screen();
	if( $screen->id != 'comments_page_google-glass-comments' )
		return;

	if( isset( $_REQUEST['state'] ) && $_REQUEST['state'] == 'ggc-oauth-setup' ) :

		// Crap
		if( isset( $_REQUEST['error'] ) ) :
			add_settings_error( 'ggc-settings', 'google-nocode', apply_filters( 'ggc-nocode-message', sprintf( __( 'There was an error when trying to authenticate with the almighty Google. The error code was %s.' ), esc_html( $_REQUEST['error'] ) ), $_REQUEST['error'] ), 'error' );

		// SA-WEET-AH!
		elseif( isset( $_REQUEST['code'] ) ) :
			ggc_get_access_token( $_REQUEST['code'] );

		endif;

	endif;
}

/**
 * Generates a google oAuth link
 * @see  https://developers.google.com/accounts/docs/OAuth2WebServer
 * @return string full url to the Google oAuth endpoint
 */
function gcc_get_oauth_link() {
	$options = ggc_get_options();

	$link = add_query_arg(
		array(
			'response_type'		=> 'code',
			'client_id'			=> $options['api-client-id'],
			'redirect_uri'		=> add_query_arg( array( 'page' => 'google-glass-comments' ), admin_url( 'edit-comments.php' ) ),
			'scope'				=> 'https://www.googleapis.com/auth/glass.timeline',
			'state'				=> 'ggc-oauth-setup',
			'access_type'		=> 'online',
			'approval_prompt'	=> 'force'
		),
		"https://accounts.google.com/o/oauth2/auth"
	);
	return $link;
}

function ggc_get_access_token( $code = '' ) {
	$options = ggc_get_options();

	$result = wp_remote_post(
		"https://accounts.google.com/o/oauth2/token",
		array(
			'body' => http_build_query(array(
				'code'			=> $code,
				'client_id'		=> $options['api-client-id'],
				'client_secret'	=> $options['api-client-secret'],
				'redirect_uri'	=> add_query_arg( array( 'page' => 'google-glass-comments' ), admin_url( 'edit-comments.php' ) ),
				'grant_type'	=> 'authorization_code'
			)),
			'headers' => array(
				'Content-Type'	=> 'application/x-www-form-urlencoded'
			)
		)
	);

	$result_body = json_decode( wp_remote_retrieve_body( $result ) );

	if( is_wp_error( $result ) || isset( $result_body->error ) ) :
		// add_settings_error( 'ggc-settings', 'google-noauth', apply_filters( 'ggc-noauth-message', sprintf( __( 'There was an error when trying to get an access token from the almighty Google. The error code was %s.' ), esc_html( $result_body->error ) ), $result ), 'error' );
		return false;
	endif;

	$oauth = array();
	$oauth['oauth-code']			= $code;
	$oauth['oauth-access-token']	= $result_body->access_token;
	$oauth['oauth-token-type']		= $result_body->token_type;
	$oauth['oauth-token-epiration']	= $result_body->expires_in;

	update_option( 'ggc-oauth-settings', $oauth );

	if ( ! wp_next_scheduled( 'gcc_renew_oauth' ) ) {
		wp_schedule_event( time(), 'hourly', 'gcc_renew_oauth' );
	}
}
