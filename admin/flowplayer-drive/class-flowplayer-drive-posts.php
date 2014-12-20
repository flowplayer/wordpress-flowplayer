<?php
/**
 * A function used to programmatically create a post in WordPress. The slug, author ID, and title
 * are defined within the context of the function.
 *
 * @returns -1 if the post was never created, -2 if a post with the same title exists, or the ID
 *          of the post if successful.
 */
function programmatically_create_video( $video ) {

	// Initialize the page ID to -1. This indicates no action has been taken.
	$post_id = -1;
	// WP_Query arguments
	$args = array (
		'post_type'      => 'flowplayer5',
		'posts_per_page' => -1
	);

	$existing_videos = get_posts( $args );

	foreach( $existing_videos as $existing_video ) {
		$post_id = $existing_video->ID;
		$video_id[] = get_post_meta( $post_id, 'fp5-video-id', true );
	}

	// If the page doesn't already exist, then create it
	if( in_array( $video['id'], $video_id ) ) {

		// Set the post ID so that we know the post was created successfully
		$post_id = wp_insert_post(
			array(
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
				'post_title'     => $video['title'],
				'post_status'    => 'publish',
				'post_type'      => 'flowplayer5'
			)
		);

		// Check, validate and save urls
		$urls = array(
			'fp5-splash-image' => $video['snapshotUrl'],
			'fp5-mp4-video'    => $video['mp4'],
			'fp5-webm-video'   => $video['webm']
		);

		foreach ( $urls as $key => $value ) {
			if( isset( $value ) ) {
				update_post_meta(
					$post_id,
					$key,
					esc_url_raw( $value )
				);
			}
		}

		// Check, validate and save text fields
		$text_fields = array(
			'fp5-flash-video' => $video['flash'],
			'fp5-video-name'  => $video['title'],
			'fp5-data-rtmp'   => $video['rtmp']
		);

		foreach ( $text_fields as $key => $value ) {
			if( isset( $value ) ) {
				update_post_meta(
					$post_id,
					$key,
					sanitize_text_field( $value )
				);
			}
		}

		// Check, validate and save numbers
		$numbers = array(
			'fp5-width'    => $video['width'],
			'fp5-height'   => $video['height'],
			'fp5-user-id'  => $video['userId'],
			'fp5-video-id' => $video['id'],
			'fp5-duration' => $video['duration']
		);

		foreach ( $numbers as $key => $value ) {
			if( isset( $value ) ) {
				update_post_meta(
					$post_id,
					$key,
					$value //@TODO Sanitize
				);
			}
		}

	// Otherwise, we'll stop
	} else {

		// Arbitrarily use -2 to indicate that the page with the title already exists
		$post_id = -2;

	} // end if

	return $post_id . ' | ';

} // end programmatically_create_post

function generate_videos() {
	$flowplayer_drive = new Flowplayer_Drive();
	$videos = $flowplayer_drive->get_videos();
	foreach ( $videos as $video ) {
		echo programmatically_create_video( $video );
	}
}
