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

	$result = wp_remote_post(
		'https://www.googleapis.com/upload/mirror/v1/timeline',
		array(
			'body' => json_encode(array(
				"key"			=> $options['api-simple-key'],
				"kind"			=> "mirror#timelineItem",
				"id"			=> "ggc-{$id}",
				"sourceItemId"	=> $id,
				"canonicalUrl"	=> get_comment_link( $comment ),
				"bundleId"		=> sprintf( "ggc-%s-items", sanitize_title( get_bloginfo( 'blogname' ) ) ),
				"isBundleCover"	=> false,
				"selfLink"		=> get_comment_link( $comment ),
				"created"		=> $datetime,
				"updated"		=> $datetime,
				"displayTime"	=> $datetime,
				"isPinned"		=> false,
				"isDeleted"		=> false,
				"inReplyTo"		=> "ggc-{$comment->comment_parent}",
				"title"			=> apply_filters( 'ggc-card-title', sprintf( __( 'New Comment for "%s" on %s' ), get_the_title( $comment->comment_post_ID ), get_bloginfo( 'blogname' ) ), $id, $comment ),
				"text"			=> apply_filters( 'ggc-card-text', sprintf( __( '%s said "%s" on "%s"'), get_comment_author( $id ), get_comment_text( $id ), get_the_title( $comment->comment_post_ID ) ), $id, $comment ),
				"html"			=> apply_filters( 'ggc-card-html', sprintf( '<article><section><h1 class="text-normal blue">%s</h1><hr><p class="text-x-small">%s</p></section><footer><p><img class="left icon-small" src="%s">%s</p></footer></article>', __( 'New Comment' ), get_comment_text( $id ), get_avatar( get_comment_author_email( $id ), '100' ), get_comment_author( $id ) ), $id, $comment ),
				"htmlPages"		=> array( '' ),
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
					)
				),
				"notification"=> array(
					"level" => 'DEFAULT',
				)
			)), // json_encode
			'headers' => array(
				'Content-Type'	=> 'application/json'
			)
		)
	); // wp_remote_post

	if( is_wp_error( $result ) ) :
		error_log( sprintf( __( 'Google Glasses Comments Notification Error: %s' ), print_r( array( 'id' => $id, 'comment' => $comment, 'result' => $result ), true ) ) );
	endif;

	error_log( sprintf( __( 'Google Glasses Result: %s' ), print_r( array( 'id' => $id, 'comment' => $comment, 'result' => $result ), true ) ) );

}
