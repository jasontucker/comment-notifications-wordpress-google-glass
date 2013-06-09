<?php

/**
 * When a comment is published a notification is sent to Google Glass
 * @param  integer $id      the id of the comment
 * @param  array   $comment the entire comment object
 * @since  1.0
 */
function ggc_send_notification( $id = 0, $comment = array() ) {

	$datetime = date( DATE_RFC3339 );
	$options = ggc_get_options();
	$oauth = get_option( 'ggc-oauth-settings' );

	$result = wp_remote_post(
		add_query_arg( array( 'access_token' => $oauth['oauth-access-token'] ), 'https://www.googleapis.com/mirror/v1/timeline' ),
		array(
			'body' => json_encode(array(
				"bundleId"		=> sprintf( "ggc-%s-items", sanitize_title( get_bloginfo( 'blogname' ) ) ),
				"sourceItemId"	=> $id,
				"canonicalUrl"	=> get_comment_link( $comment ),
				"html"			=> apply_filters( 'ggc-card-html', sprintf( '<article><section><h1 class="text-normal blue">%s</h1><hr><p class="text-x-small">%s</p></section><footer><p>%s%s</p></footer></article>', __( 'New Comment' ), get_comment_text( $id ), str_replace( "class='", "class='left icon-small ", get_avatar( get_comment_author_email( $id ), '100' ) ), get_comment_author( $id ) ), $id, $comment ),
				"speakableText"	=> apply_filters( 'ggc-card-spoken-text', get_comment_text( $id ), $id, $comment ),
				"menuItems"		=> array(
					array(
						"action"	=> "CUSTOM",
						"id"		=> "spam",
						"values"	=> array(array(
							"displayName"	=> "Spam",
							"iconUrl"		=> GGC_IMG."spam.png" // http://thenounproject.com/noun/disapprove/#icon-No339 - Disapprove from The Noun Project
						))
					),
					array(
						"action"	=> "CUSTOM",
						"id"		=> "approve",
						"values"	=> array(array(
							"displayName"	=> "Approve",
							"iconUrl"		=> GGC_IMG."approve.png" // http://thenounproject.com/noun/approve/#icon-No330 - Approve from The Noun Project
						))
					),
					array(
						"action"	=> "CUSTOM",
						"id"		=> "delete",
						"values"	=> array(array(
							"displayName"	=> "Delete",
							"iconUrl"		=> GGC_IMG."delete.png" // http://thenounproject.com/noun/trash/#icon-No16505 Trash, designed by filip (http://thenounproject.com/fmal) from The Noun Project
						))
					),
					array(
						"action"	=> "DELETE",
						"id"		=> "delete_card",
						"values"	=> array(array(
							"displayName"	=> "Delete Card",
							"iconUrl"		=> GGC_IMG."delete.png" // http://thenounproject.com/noun/trash/#icon-No16505 Trash, designed by filip (http://thenounproject.com/fmal) from The Noun Project
						))
					),
					array(
						"action"	=> "READ_ALOUD",
						"id"		=> "read_card",
						"values"	=> array(array(
							"displayName"	=> "Read Card",
							"iconUrl"		=> GGC_IMG."delete.png" // http://thenounproject.com/noun/trash/#icon-No16505 Trash, designed by filip (http://thenounproject.com/fmal) from The Noun Project
						))
					)
				),
				"notification"=> array(
					"level" => 'DEFAULT',
				)
			)), // json_encode
			'headers' => array(
				'Content-Type'	=> 'application/json',
				'Authorization'	=> 'Bearer '.$oauth['oauth-access-token']
			)
		)
	); // wp_remote_post

	if( is_wp_error( $result ) ) :
		error_log( sprintf( __( 'Google Glasses Comments Notification Error: %s' ), print_r( array( 'id' => $id, 'comment' => $comment, 'result' => $result ), true ) ) );
		return false;
	endif;

	add_comment_meta( $id, 'ggc-object', json_decode( wp_remote_retrieve_body( $result ) ), true );
}
