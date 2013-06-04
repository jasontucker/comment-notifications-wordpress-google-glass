<?php

/**
 * When a comment is published a notification is sent to Google Glass
 * @param  int $id      [description]
 * @param  string $comment [description]
 */
function ggc_send_notification( $id, $comment ) {

	$datetime = date( DATE_RFC3339 );
	$contact = new stdClass();

	$result = wp_remote_post(
		'http://www.googleapis.com',
		array(
			'body' => json_encode(array(
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
				"inReplyTo"		=> "ggc-{$comment['comment-parent']}",
				"title"			=> apply_filters( 'ggc-card-title', sprintf( __( 'New Comment for "%s" on %s' ), $post_name, get_bloginfo( 'blogname' ) ), $id, $comment ),
				"text"			=> apply_filters( 'ggc-card-text', sprintf( __( '%s said "%s" on "%s"'), get_comment_author( $id ), get_comment_text( $id ), $post_name ), $id, $comment ),
				"html"			=> apply_filters( 'ggc-card-html', sprintf( '<article><section><h1 class="text-normal blue">%s</h1><hr><p class="text-x-small">%s</p></section><footer><p><img class="left icon-small" src="%s">%s</p></footer></article>', __( 'New Comment' ), get_comment_text( $id ), get_avatar( get_comment_author_email( $id ), '100' ), get_comment_author( $id ) ), $id, $comment ),
				"htmlPages"		=> array( '' ),
				"speakableText"	=> apply_filters( 'ggc-card-spoken-text', get_comment_text( $id ), $id, $comment ),
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
		error_log( sprintf( __( 'Google Glasses Comments Notification Error: %s' ), print_r( array( 'id' => $id, 'comment' => $comment, 'result' => $result ) ) ) );
	endif;

}
