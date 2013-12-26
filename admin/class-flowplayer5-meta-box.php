<?php
/**
 * Flowplayer 5 for WordPress
 *
 * @package   Video_Meta_Box
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
 * Video Meta box class.
 *
 * @package Video_Meta_Box
 * @author  Ulrich Pogson <ulrich@pogson.ch>
 */
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
	 * Initializes the meta boxes
	 *
	 * @since     1.0.0
	 */
	public function __construct() {

		$flowplayer5 = Flowplayer5::get_instance();
		$this->plugin_slug = $flowplayer5->get_plugin_slug();

		// Setup the meta boxes for the video and shortcode
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
	 * @since      1.0.0
	 */
	public function display_shortcode_meta_box() {

		$html = '[flowplayer id="' . get_the_ID() . '"]';
		$html .= '<p>' . __( 'Copy this shortcode to a post, page or widget to show the video.', $this->plugin_slug ) . '</p>'; 

		echo $html;

	}

	/**
	 * Registers the meta box for displaying the 'Flowplayer Video' in the post editor.
	 *
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

		include_once( plugin_dir_path( __FILE__ ) . 'views/display-video-meta-box.php' );

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
			if( isset( $_POST['fp5-select-skin'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-select-skin',
					sanitize_key( $_POST['fp5-select-skin'] )
				);
			}

			// Checks for input and saves
			if( isset( $_POST['fp5-autoplay'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-autoplay',
					'true'
				);
			} else {
				update_post_meta(
					$post_id,
					'fp5-autoplay',
					''
				);
			}

			// Checks for input and saves
			if( isset( $_POST['fp5-loop'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-loop',
					'true'
				);
			} else {
				update_post_meta(
					$post_id,
					'fp5-loop',
					''
				);
			}

			// Checks for input and saves
			if( isset( $_POST['fp5-preload'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-preload',
					sanitize_key( $_POST['fp5-preload'] )
				);
			}

			// Checks for input and saves
			if( isset( $_POST['fp5-fixed-controls'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-fixed-controls',
					'true'
				);
			} else {
				update_post_meta(
					$post_id,
					'fp5-fixed-controls',
					''
				);
			}

			// Checks for input and saves
			if( isset( $_POST['fp5-coloring'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-coloring',
					sanitize_key( $_POST['fp5-coloring'] )
				);
			}

			// Checks for input and saves if needed
			if( isset( $_POST['fp5-splash-image'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-splash-image',
					esc_url_raw( $_POST['fp5-splash-image'] )
				);
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-mp4-video'] ) ) {
				update_post_meta(
					$post_id, 'fp5-mp4-video',
					esc_url_raw( $_POST['fp5-mp4-video'] )
				);
			}

			// Checks for input and saves if needed
			if( isset( $_POST['fp5-webm-video'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-webm-video',
					esc_url_raw( $_POST['fp5-webm-video'] )
				);
			}

			// Checks for input and saves if needed
			if( isset( $_POST['fp5-ogg-video'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-ogg-video',
					esc_url_raw( $_POST['fp5-ogg-video'] )
				);
			}

			// Checks for input and saves if needed
			if( isset( $_POST['fp5-flash-video'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-flash-video',
					sanitize_text_field( $_POST['fp5-flash-video'] )
				);
			}

			// Checks for input and saves if needed
			if( isset( $_POST['fp5-vtt-subtitles'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-vtt-subtitles',
					esc_url_raw( $_POST['fp5-vtt-subtitles'] )
				);
			}

			// Checks for input and saves if needed
			if( isset( $_POST['fp5-max-width'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-max-width',
					absint( $_POST['fp5-max-width'] )
				);
			}

			// Checks for input and saves if needed
			if( isset( $_POST['fp5-width'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-width',
					absint( $_POST['fp5-width'] )
				);
			}

			// Checks for input and saves if needed
			if( isset( $_POST['fp5-height'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-height',
					absint( $_POST['fp5-height'] )
				);
			}

			// Checks for input and saves
			if( isset( $_POST['fp5-aspect-ratio'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-aspect-ratio',
					'true'
				);
			} else {
				update_post_meta(
					$post_id,
					'fp5-aspect-ratio',
					''
				);
			}

			// Checks for input and saves
			if( isset( $_POST['fp5-fixed-width'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-fixed-width',
					'true'
				);
			} else {
				update_post_meta(
					$post_id,
					'fp5-fixed-width',
					''
				);
			}

			// Checks for input and saves if needed
			if( isset( $_POST['fp5-user-id'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-user-id',
					absint( $_POST['fp5-user-id'] )
				);
			}

			// Checks for input and saves if needed
			if( isset( $_POST['fp5-video-id'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-video-id',
					absint( $_POST['fp5-video-id'] )
				);
			}

			// Checks for input and saves if needed
			if( isset( $_POST['fp5-video-name'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-video-name',
					sanitize_text_field( $_POST['fp5-video-name'] )
				);
			}

			// Checks for input and saves if needed
			if( isset( $_POST['fp5-data-rtmp'] ) ) {
				update_post_meta(
					$post_id,
					'fp5-data-rtmp',
					sanitize_text_field( $_POST['fp5-data-rtmp'] )
				);
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