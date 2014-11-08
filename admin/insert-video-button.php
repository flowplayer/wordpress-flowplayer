<?php
/**
 * Add media button for shortcode
 *
 * @package   Flowplayer 5 for WordPress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      http://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Adds an "Insert Video" button above the TinyMCE Editor on add/edit screens.
 *
 * @since    1.1.0
 * @return   string "Insert Video" Button
 */
function fp5_media_button() {
	$screen = get_current_screen();
	$output = '';

	/** Only run in post/page creation and edit screens */
	if ( $screen->base == 'post' && $screen->post_type != 'flowplayer5' ) {
		$img = '<span class="wp-media-buttons-icon" ></span>';
		$output = '<a href="#choose-video" class="button add-video" title="' . __( 'Insert Video', 'flowplayer5' ) . '" style="padding-left: .4em;">' . $img . __( 'Insert Video', 'flowplayer5' ) . '</a>';
	}
	echo $output;
}
add_action( 'media_buttons', 'fp5_media_button', 11 );

/**
 * Content for colorbox modal
 *
 * Prints the footer code needed for the Insert Video button.
 *
 * @since    1.1.0
 */
function fp5_modal_content() {
	$screen = get_current_screen();

	// Only run in post/page creation and edit screens
	if ( $screen->base == 'post' && $screen->post_type != 'flowplayer5' ) {
		?>
		<script type="text/javascript">
			jQuery(document).ready( function ($) {
				// Open modal
				$( '.add-video' ).colorbox({
					inline: true,
					width: false,
					transition: "none"
				});
			});

			function insertVideo() {
				var id = jQuery( '#flowplayer5_videos' ).val();

				// Return early if no download is selected
				if ( '' === id ) {
					alert('<?php _e( 'You must choose a Video', 'flowplayer5' ); ?>');
					return;
				}

				// Send the shortcode to the editor
				window.send_to_editor( '[flowplayer id="' + id + '"]' );
				// Close modal
				jQuery.colorbox.close();
			};

			function insertPlaylist() {
				var id = jQuery( '#flowplayer5_playlist' ).val();

				// Return early if no download is selected
				if ( '' === id ) {
					alert('<?php _e( 'You must choose a Playlist', 'flowplayer5' ); ?>');
					return;
				}

				// Send the shortcode to the editor
				window.send_to_editor( '[flowplayer playlist="' + id + '"]' );
				// Close modal
				jQuery.colorbox.close();
			};

		</script>

	<div style="display: none;">
		<div id="choose-video">
			<p><?php echo __( 'Choose a video from the dropdown to insert as a shortcode', 'flowplayer5' ); ?></p>
			<div>
				<select name="flowplayer5_videos" id="flowplayer5_videos">
					<?php
					// WP_Query arguments
					$args = array(
						'post_type' => 'flowplayer5',
					);

					// The Query
					$query = new WP_Query( $args );
					$posts = $query->posts;

					foreach ( $posts as $post ) { ?>
						<option value="<?php echo esc_attr( $post->ID ) ?>"><?php echo esc_attr( $post->post_title ) ?></option>
					<?php } ?>
				</select>
			</div>
			<p class="submit">
				<input type="button" id="flowplayer5-insert-video" class="button-primary" value="<?php echo __( 'Insert Video', 'flowplayer5' ); ?>" onclick="insertVideo();" />
				<a id="fp5-cancel-video-insert" class="button-secondary" onclick="jQuery.colorbox.close();" title="<?php _e( 'Cancel', 'flowplayer5' ); ?>"><?php _e( 'Cancel', 'flowplayer5' ); ?></a>
			</p>
			<p><?php echo __( 'Choose a playlist from the dropdown to insert as a shortcode', 'flowplayer5' ); ?></p>
			<div>
				<?php _e( 'Playlist', 'flowplayer5' )?>
				<select name="flowplayer5_playlist" id="flowplayer5_playlist">
					<?php

					$args = array(
						'post'     => 'flowplayer5',
						'taxonomy' => 'playlist',
					);

					$categories = get_categories( $args );

					foreach ( $categories as $category ) { ?>
						<option value="<?php echo esc_attr( $category->term_id ) ?>"><?php echo esc_attr( $category->name ) ?></option>
					<?php } ?>
				</select>
			</div>
			<p class="submit">
				<input type="button" id="flowplayer5-insert-playlist" class="button-primary" value="<?php echo __( 'Insert Playlist', 'flowplayer5' ); ?>" onclick="insertPlaylist();" />
				<a id="fp5-cancel-video-insert" class="button-secondary" onclick="jQuery.colorbox.close();" title="<?php _e( 'Cancel', 'flowplayer5' ); ?>"><?php _e( 'Cancel', 'flowplayer5' ); ?></a>
			</p>
		</div>
	</div>
	<?php
	}
}
add_action( 'admin_footer', 'fp5_modal_content' );
