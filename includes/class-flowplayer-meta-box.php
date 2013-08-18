<?php
/**
 * Meta boxes for custom post type
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

class Video_Meta_Box {

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug;

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	public function __construct() {

		$flowplayer5 = Flowplayer5::get_instance();
		$this->plugin_slug = $flowplayer5->get_plugin_slug();

		// Setup the meta boxs for the video and shortcode
		add_action( 'add_meta_boxes', array( $this, 'add_shortcode_meta_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_video_meta_box' ) );

		// Setup the function responsible for saving
		add_action( 'save_post', array( $this, 'save_fp5_video_details' ) );

	}

	/**
	 * Registers the meta box for displaying the 'Shortcode' in the post editor.
	 *
	 * @since      1.0.0
	 */
	public function add_shortcode_meta_box() {

		add_meta_box(
			'fp5_shortcode',
			__( 'Shortcode', $this->plugin_slug ),
			array( $this, 'display_shortcode_meta_box' ),
			'flowplayer5',
			'side',
			'default'
		);

	}

	/**
	 * Displays the meta box for displaying the 'Shortcode'
	 *
	 * @since      0.1.0
	 */
	public function display_shortcode_meta_box() {

			$html = '[flowplayer id="' . get_the_ID() . '"]';
			$html .= '<p>' . __( 'Copy this shortcode to a post, page or widget to show the video.', $this->plugin_slug ) . '</p>'; 

		echo $html;

	}

	/**
	 * Registers the meta box for displaying the 'Flowplayer Video' in the post editor.
	 *
	 * @version    1.0.0
	 * @since      1.0.0
	 */
	public function add_video_meta_box() {

		add_meta_box(
			'fp5_video_details',
			__( 'Flowplayer Video', $this->plugin_slug ),
			array( $this, 'display_video_meta_box' ),
			'flowplayer5',
			'normal',
			'default'
		);

	}

	/**
	 * Displays the meta box for displaying the 'Flowplayer Video'
	 *
	 * @since      1.0.0
	 */
	public function display_video_meta_box( $post ) {

		wp_nonce_field( plugin_basename( __FILE__ ), 'fp5-nonce' );
		$fp5_stored_meta = get_post_meta( $post->ID );
		$fp5_select_skin = get_post_meta( $post->ID,'_fp5_select_skin',true );
		?>

		<table class="form-table">
			<tbody>

				<tr valign="top">
					<th scope="row"><span class="fp5-row-title"><strong><?php _e( 'Media files', $this->plugin_slug )?></strong></span></th>
					<td><?php _e( 'It is recommended to add at least two video formats so that the video plays on as many browsers as possible. To be able to calculate the dimensions of the video the video format added needs to be comptible with the browser.', $this->plugin_slug ) ?> <a href="http://flowplayer.org/docs/#video-formats" target="_blank"><?php _e( 'About video formats.', $this->plugin_slug ) ?></a>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="fp5-splash-image"><?php _e( 'Splash Image', $this->plugin_slug )?></label> <a href="http://flowplayer.org/docs/index.html#splash" target="_blank">?</a></th>
					<td>
						<input class="media-url" type="text" name="fp5-splash-image" id="fp5-splash-image" size="70" value="<?php if ( isset ( $fp5_stored_meta['fp5-splash-image'] ) ) echo $fp5_stored_meta['fp5-splash-image'][0]?>" />
						<a href="#" class="fp5-add-splash-image button button-primary" title="<?php _e( 'Add splash image', $this->plugin_slug )?>"><?php _e( 'Add splash image', $this->plugin_slug )?></a>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="fp5-mp4-video"><?php _e( 'mp4 Video', $this->plugin_slug )?></label></th>
					<td>
						<input class="media-url" type="text" name="fp5-mp4-video" id="fp5-mp4-video" size="70" value="<?php if ( isset ( $fp5_stored_meta['fp5-mp4-video'] ) ) echo $fp5_stored_meta['fp5-mp4-video'][0]; ?>" />
						<a href="#" class="fp5-add-mp4 button button-primary" title="<?php _e( 'Add mp4 Video', $this->plugin_slug )?>"><?php _e( 'Add mp4 Video', $this->plugin_slug )?></a>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="fp5-webm-video"><?php _e( 'webm Video', $this->plugin_slug )?></label></th>
					<td>
						<input class="media-url" type="text" name="fp5-webm-video" id="fp5-webm-video" size="70" value="<?php if ( isset ( $fp5_stored_meta['fp5-webm-video'] ) ) echo $fp5_stored_meta['fp5-webm-video'][0]; ?>" />
						<a href="#" class="fp5-add-webm button button-primary" title="<?php _e( 'Add webm Video', $this->plugin_slug )?>"><?php _e( 'Add webm Video', $this->plugin_slug )?></a>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="fp5-ogg-video"><?php _e( 'ogg Video', $this->plugin_slug )?></label></th>
					<td>	
						<input class="media-url" type="text" name="fp5-ogg-video" id="fp5-ogg-video" size="70" value="<?php if ( isset ( $fp5_stored_meta['fp5-ogg-video'] ) ) echo $fp5_stored_meta['fp5-ogg-video'][0]; ?>" />
						<a href="#" class="fp5-add-ogg button button-primary" title="<?php _e( 'Add ogg Video', $this->plugin_slug )?>"><?php _e( 'Add ogg Video', $this->plugin_slug )?></a>
					</td>
				</tr>

				<!-- <tr valign="top">
					<th scope="row"><label for="fp5-vtt-subtitles"><?php _e( 'vtt file (Subtitles)', $this->plugin_slug )?></label> <a href="http://flowplayer.org/docs/subtitles.html" target="_blank">?</a></th>
					<td>
						<input class="media-url" type="text" name="fp5-vtt-subtitles" id="fp5-vtt-subtitles" size="70" value="<?php if ( isset ( $fp5_stored_meta['fp5-vtt-subtitles'] ) ) echo $fp5_stored_meta['fp5-vtt-subtitles'][0]; ?>" />
						<a href="#" class="fp5-add-vtt button button-primary" title="<?php _e( 'Add vtt file', $this->plugin_slug )?>"><?php _e( 'Add vtt file', $this->plugin_slug )?></a>
					</td>
				</tr> -->

			</tbody>
		</table>

		<p>
			<div class="fp5-row-content">
				<span class="fp5-row-title"><strong><?php _e( 'Select skin', $this->plugin_slug )?></strong></span>
			</div>
			<div class="fp5-row-content">
				<select id="fp5-select-skin" name="fp5-select-skin">
					<option id="fp5-minimalist" value="minimalist" <?php if ( isset ( $fp5_stored_meta['fp5-select-skin'] ) ) selected( $fp5_stored_meta['fp5-select-skin'][0], 'minimalist' ); ?>>Minimalist</option>
					<option id="fp5-functional" value="functional" <?php if ( isset ( $fp5_stored_meta['fp5-select-skin'] ) ) selected( $fp5_stored_meta['fp5-select-skin'][0], 'functional' ); ?>>Functional</option>
					<option id="fp5-playful" value="playful" <?php if ( isset ( $fp5_stored_meta['fp5-select-skin'] ) ) selected( $fp5_stored_meta['fp5-select-skin'][0], 'playful' ); ?>>Playful</option>
				</select>
				<div class="player-previews">
					<img id="fp5_minimalist" class="minimalist player-preview" src="<?php echo plugins_url( '/assets/img/minimalist.png', dirname(__FILE__) ) ?>" />
					<img id="fp5_functional" class="functional player-preview" src="<?php echo plugins_url( '/assets/img/functional.png', dirname(__FILE__) ) ?>" />
					<img id="fp5_playful" class="playful player-preview" src="<?php echo plugins_url( '/assets/img/playful.png', dirname(__FILE__) ) ?>" />
				</div>
			</div>
		</p>

		<p>
			<div class="fp5-row-content">
				<span class="fp5-row-title"><strong><?php _e( 'Video attributes', $this->plugin_slug )?></strong> <a href="http://flowplayer.org/docs/index.html#video-attributes" target="_blank">?</a></span>
			</div>
			<div class="fp5-row-content">
				<label for="fp5-autoplay">
					<?php _e( 'Autoplay', $this->plugin_slug )?>
					<input type="checkbox" name="fp5-autoplay" id="fp5-autoplay" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-autoplay'] ) ) checked( $fp5_stored_meta['fp5-autoplay'][0], 'true' ); ?> />
				</label>
				|
				<label for="fp5-loop">
					<?php _e( 'Loop', $this->plugin_slug )?>
					<input type="checkbox" name="fp5-loop" id="fp5-loop" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-loop'] ) ) checked( $fp5_stored_meta['fp5-loop'][0], 'true' ); ?> />
				</label>
				|
				<label for="fp5-preload" class="fp5-row-title"><?php _e( 'Preload', $this->plugin_slug )?></label>
				<select name="fp5-preload" id="fp5-preload">
					<option value="auto" <?php if ( isset ( $fp5_stored_meta['fp5-preload'] ) ) selected( $fp5_stored_meta['fp5-preload'][0], 'auto' ); ?>>auto</option>';
					<option value="metadata" <?php if ( isset ( $fp5_stored_meta['fp5-preload'] ) ) selected( $fp5_stored_meta['fp5-preload'][0], 'metadata' ); ?>>metadata</option>';
					<option value="none" <?php if ( isset ( $fp5_stored_meta['fp5-preload'] ) ) selected( $fp5_stored_meta['fp5-preload'][0], 'none' ); ?>>none</option>';
				</select>
			</div>
		</p>

		<p>
			<div class="fp5-row-content">
				<span class="fp5-row-title"><strong><?php _e( 'Flowplayer options', $this->plugin_slug )?></strong> <a href="http://flowplayer.org/docs/skinning.html#modifier-classes" target="_blank">?</a></span>
			</div>
			<div class="fp5-row-content">
				<label for="fp5-fixed-controls">
					<?php _e( 'Fixed Controls', $this->plugin_slug )?>
					<input type="checkbox" name="fp5-fixed-controls" id="fp5-fixed-controls" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-fixed-controls'] ) ) checked( $fp5_stored_meta['fp5-fixed-controls'][0], 'true' ); ?> />
				</label>
				|
				<label for="fp5-coloring" class="fp5-row-title"><?php _e( 'Coloring', $this->plugin_slug )?></label>
				<select name="fp5-coloring" id="fp5-coloring">
					<option value="default" <?php if ( isset ( $fp5_stored_meta['fp5-coloring'] ) ) selected( $fp5_stored_meta['fp5-coloring'][0], 'default' ); ?>><?php _e( 'Default', $this->plugin_slug )?></option>';
					<option value="color-alt" <?php if ( isset ( $fp5_stored_meta['fp5-coloring'] ) ) selected( $fp5_stored_meta['fp5-coloring'][0], 'color-alt' ); ?>><?php _e( 'Black & White', $this->plugin_slug )?></option>';
					<option value="color-alt2" <?php if ( isset ( $fp5_stored_meta['fp5-coloring'] ) ) selected( $fp5_stored_meta['fp5-coloring'][0], 'color-alt2' ); ?>><?php _e( 'Red', $this->plugin_slug )?></option>';
					<option value="color-light" <?php if ( isset ( $fp5_stored_meta['fp5-coloring'] ) ) selected( $fp5_stored_meta['fp5-coloring'][0], 'color-light' ); ?>><?php _e( 'Light', $this->plugin_slug )?></option>';
				</select>
			</div>
		</p>

		<p>
			<div id="fp5-preview" class="preview">
				<div id="video"></div>
			</div>
		</p>

		<p>
			<div class="fp5-row-content">
				<span class="fp5-row-title"><strong><?php _e( 'Size options', $this->plugin_slug )?></strong></span>
			</div>
			<div class="fp5-row-content">
				<label for="fp5-max-width" class="fp5-row-title"><?php _e( 'Max Width', $this->plugin_slug )?></label>
				<input type="text" name="fp5-max-width" id="fp5-max-width" value="<?php if ( isset ( $fp5_stored_meta['fp5-max-width'] ) ) echo $fp5_stored_meta['fp5-max-width'][0]; ?>" />
				<label for="fp5-width" class="fp5-row-title"><?php _e( 'Width', $this->plugin_slug )?></label>
				<input type="text" name="fp5-width" id="fp5-width" value="<?php if ( isset ( $fp5_stored_meta['fp5-width'] ) ) echo $fp5_stored_meta['fp5-width'][0]; ?>" />
				<label for="fp5-height" class="fp5-row-title"><?php _e( 'Height', $this->plugin_slug )?></label>
				<input type="text" name="fp5-height" id="fp5-height" value="<?php if ( isset ( $fp5_stored_meta['fp5-height'] ) ) echo $fp5_stored_meta['fp5-height'][0]; ?>" />
			</div>
		</p>

		<p>
			<div class="fp5-row-content">
				<label for="fp5-aspect-ratio">
					<input type="checkbox" name="fp5-aspect-ratio" id="fp5-aspect-ratio" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-aspect-ratio'] ) ) checked( $fp5_stored_meta['fp5-aspect-ratio'][0], 'true' ); ?> />
					<?php _e( 'Change video dimensions', $this->plugin_slug )?>
				</label>
				|
				<label for="fp5-fixed-width">
					<input type="checkbox" name="fp5-fixed-width" id="fp5-fixed-width" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-fixed-width'] ) ) checked( $fp5_stored_meta['fp5-fixed-width'][0], 'true' ); ?> />
					<?php _e( 'Use fixed player size', $this->plugin_slug ) ?>
				</label>
			</div>
		</p>

	<?php
	}

	/**
	 * When the post is saved or updated, generates a short URL to the existing post.
	 *
	 * @param    int     $post_id    The ID of the post being save
	 * @since    1.0.0
	 */
	public function save_fp5_video_details( $post_id ) {

		if ( $this->user_can_save( $post_id, 'fp5-nonce' ) ) {

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-select-skin' ] ) ) {
				update_post_meta( $post_id, 'fp5-select-skin', $_POST[ 'fp5-select-skin' ] );
			}

			// Checks for input and saves
			if( isset( $_POST[ 'fp5-autoplay' ] ) ) {
				update_post_meta( $post_id, 'fp5-autoplay', 'true' );
			} else {
				update_post_meta( $post_id, 'fp5-autoplay', '' );
			}

			// Checks for input and saves
			if( isset( $_POST[ 'fp5-loop' ] ) ) {
				update_post_meta( $post_id, 'fp5-loop', 'true' );
			} else {
				update_post_meta( $post_id, 'fp5-loop', '' );
			}

			// Checks for input and saves
			if( isset( $_POST[ 'fp5-preload' ] ) ) {
				update_post_meta( $post_id, 'fp5-preload', $_POST[ 'fp5-preload' ] );
			}

			// Checks for input and saves
			if( isset( $_POST[ 'fp5-fixed-controls' ] ) ) {
				update_post_meta( $post_id, 'fp5-fixed-controls', 'true' );
			} else {
				update_post_meta( $post_id, 'fp5-fixed-controls', '' );
			}

			// Checks for input and saves
			if( isset( $_POST[ 'fp5-coloring' ] ) ) {
				update_post_meta( $post_id, 'fp5-coloring', $_POST[ 'fp5-coloring' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-splash-image' ] ) ) {
				update_post_meta( $post_id, 'fp5-splash-image', $_POST[ 'fp5-splash-image' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-mp4-video' ] ) ) {
				update_post_meta( $post_id, 'fp5-mp4-video', $_POST[ 'fp5-mp4-video' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-webm-video' ] ) ) {
				update_post_meta( $post_id, 'fp5-webm-video', $_POST[ 'fp5-webm-video' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-ogg-video' ] ) ) {
				update_post_meta( $post_id, 'fp5-ogg-video', $_POST[ 'fp5-ogg-video' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-vtt-subtitles' ] ) ) {
				update_post_meta( $post_id, 'fp5-vtt-subtitles', $_POST[ 'fp5-vtt-subtitles' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-max-width' ] ) ) {
				update_post_meta( $post_id, 'fp5-max-width', $_POST[ 'fp5-max-width' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-width' ] ) ) {
				update_post_meta( $post_id, 'fp5-width', $_POST[ 'fp5-width' ] );
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-height' ] ) ) {
				update_post_meta( $post_id, 'fp5-height', $_POST[ 'fp5-height' ] );
			}

			// Checks for input and saves
			if( isset( $_POST[ 'fp5-aspect-ratio' ] ) ) {
				update_post_meta( $post_id, 'fp5-aspect-ratio', 'true' );
			} else {
				update_post_meta( $post_id, 'fp5-aspect-ratio', '' );
			}

			// Checks for input and saves
			if( isset( $_POST[ 'fp5-fixed-width' ] ) ) {
				update_post_meta( $post_id, 'fp5-fixed-width', 'true' );
			} else {
				update_post_meta( $post_id, 'fp5-fixed-width', '' );
			}

		}

	}


	/**
	 * Determines whether or not the current user has the ability to save meta data associated with this post.
	 *
	 * @param    int     $post_id    The ID of the post being save
	 * @param    string  $nonce      The nonce identifier associated with the value being saved
	 * @return   bool                Whether or not the user has the ability to save this post.
	 * @since    1.0.0
	 */
	private function user_can_save( $post_id, $nonce ) {

		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ $nonce ] ) && wp_verify_nonce( $_POST[ $nonce ], plugin_basename( __FILE__ ) ) ) ? true : false;

		// Return true if the user is able to save; otherwise, false.
		return ! ( $is_autosave || $is_revision) && $is_valid_nonce;

	}

}