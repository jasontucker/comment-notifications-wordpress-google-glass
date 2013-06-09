<?php

// ggc-oauth-setup
add_action( 'admin_init', 'ggc_catch_oauth_response' );
function ggc_catch_oauth_response() {

	$screen = get_current_screen();
	if( $screen->id != 'comments_page_google-glass-comments' )
		return;

	if( isset( $_REQUEST['state'] ) && $_REQUEST['state'] == 'ggc-oauth-setup' ) :

		// Crap
		if( isset( $_REQUEST['error'] ) ) :
			wp_die( __( 'OH NOES!' ) );

		// SA-WEET-AH!
		elseif( isset( $_REQUEST['code'] ) ) :
			ggc_get_access_token( $_REQUEST['code'] );

		endif;

	endif;
}

// https://developers.google.com/accounts/docs/OAuth2WebServer

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
			'redirect_uri'		=> add_query_arg( array( 'page' => 'google-glass-comments', 'step' => 'ggc-oauth-setup' ), admin_url( 'edit-comments.php' ) ),
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
				'redirect_uri'	=> add_query_arg( array( 'page' => 'google-glass-comments', 'step' => 'ggc-oauth-setup' ), admin_url( 'edit-comments.php' ) ),
				'grant_type'	=> 'authorization_code'
			)),
			'headers' => array(
				'Content-Type'	=> 'application/x-www-form-urlencoded'
			)
		)
	);

	if( is_wp_error( $result ) ) :
		error_log( sprintf( __( 'Google Authentication Error: %s' ), print_r( array( 'result' => $result ), true ) ) );
	endif;

	wp_mail( 'brandon@pixeljar.net', 'google authentication', sprintf( __( 'Google Glasses Result: %s' ), print_r( array( 'result' => $result ), true ) ) );
	error_log( sprintf( __( 'Google Authentication Result: %s' ), print_r( array( 'result' => $result ), true ) ) );

	$result_body = wp_remote_retrieve_body( $result );
	echo '<pre>'.print_r( json_decode( $result_body ), true ).'</pre>';

}
