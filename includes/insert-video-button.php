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
 * @since 1.1.0
 * @return string "Insert Video" Button
 */
function fp5_media_button() {
	global $pagenow, $typenow, $wp_version;
	$output = '';

	/** Only run in post/page creation and edit screens */
	if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) && $typenow != 'flowplayer5' ) {
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
 * @since 1.1.0
 * @global $pagenow
 * @global $typenow
 */
function fp5_modal_content() {
	global $pagenow, $typenow;

	// Only run in post/page creation and edit screens
	if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) && $typenow != 'flowplayer5' ) {
		?>
		<script type="text/javascript">
            jQuery(document).ready(function () {
                // Open modal
                jQuery(".add-video").colorbox({
                    inline: true,
                    width: false,
                    transition: "none"
                });
            });
            function insertVideo() {
                var id = jQuery('#flowplayer5_videos').val();

                // Return early if no download is selected
                if ('' === id) {
                    alert('<?php _e( "You must choose a Video", "flowplayer5" ); ?>');
                    return;
                }

                // Send the shortcode to the editor
                window.send_to_editor('[flowplayer id="' + id + '"]');
                // Close modal
                jQuery.colorbox.close();
            }
		</script>

	<div style="display: none;">
		<div id="choose-video" class="wrap">
			<p><?php echo __( 'Use the dropdown below to chose a video to insert as a shortcode', 'flowplayer5' ); ?></p>
			<div>
				<select name="flowplayer5_videos" id="flowplayer5_videos">
					<?php
					global $post;
					$args = array( 
						'posts_per_page' => -1,
						'post_type'      => 'flowplayer5'
					);
					$posts = get_posts( $args );
					foreach( $posts as $post ) : setup_postdata( $post ); ?>
					<option value="<? echo $post->ID; ?>"><?php the_title(); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<p class="submit">
				<input type="button" id="flowplayer5-insert-video" class="button-primary" value="<?php echo __( 'Insert Video', 'flowplayer5' ); ?>" onclick="insertVideo();" />
				<a id="fp5-cancel-video-insert" class="button-secondary" onclick="jQuery.colorbox.close();" title="<?php _e( 'Cancel', 'flowplayer5' ); ?>"><?php _e( 'Cancel', 'flowplayer5' ); ?></a>
			</p>
		</div>
	</div>
	<?php
	}
}
add_action( 'admin_footer', 'fp5_modal_content' );