<?php

// ggc-oauth-setup
function ggc_catch_oauth_response() {
	if( isset( $_REQUEST['step'] ) && $_REQUEST['step'] == 'ggc-oauth-setup' ) :

		die( _pring_r( $_REQUEST ) );


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
			'state'				=> get_current_user_id(),
			'access_type'		=> 'online',
			'approval_prompt'	=> 'force',
		),
		"https://accounts.google.com/o/oauth2/auth"
	);
	return $link;
}
