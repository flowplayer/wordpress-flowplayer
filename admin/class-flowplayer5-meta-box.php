<?php
/**
 * Flowplayer 5 for WordPress
 *
 * @package   Flowplayer5_Video_Meta_Box
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      https://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Video Meta box class.
 *
 * @package Flowplayer5_Video_Meta_Box
 * @author  Ulrich Pogson <ulrich@pogson.ch>
 */
class Flowplayer5_Video_Meta_Box {

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

		$flowplayer5          = Flowplayer5::get_instance();
		$this->plugin_slug    = $flowplayer5->get_plugin_slug();
		$this->player_version = $flowplayer5->get_player_version();

		// Setup the meta boxes for the video and shortcode
		add_action( 'add_meta_boxes', array( $this, 'add_shortcode_meta_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_video_meta_box' ) );

		// Setup the function responsible for saving
		add_action( 'save_post', array( $this, 'save_fp5_video_details' ) );

		add_filter( 'default_hidden_meta_boxes', array( $this, 'default_hidden_meta_boxes' ), 10, 2 );

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
		$html .= '<p>' . __( 'Copy this shortcode to a post or page to show the video.', $this->plugin_slug ) . '</p>';

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
			'high'
		);

	}

	/**
	 * Displays the meta box for displaying the 'Flowplayer Video'
	 *
	 * @since      1.0.0
	 */
	public function display_video_meta_box( $post ) {
    
		wp_nonce_field( plugin_basename( __FILE__ ), 'fp5-nonce' );
		$fp5_stored_meta = wp_parse_args(
			get_post_meta( $post->ID ),
			apply_filters( 'fp5_post_meta_defaults', array(
				'fp5-preload' => array( 'metadata' )
			) )
		);
		// https://core.trac.wordpress.org/ticket/15030
		$fp5_ads = isset( $fp5_stored_meta['fp5_ads'][0] ) ? maybe_unserialize( $fp5_stored_meta['fp5_ads'][0] ) : array();
		if ( isset( $fp5_stored_meta['fp5-ad-type'][0] ) ) {
			$fp5_ads[0]['fp5-ad-type'] = $fp5_stored_meta['fp5-ad-type'][0];
		}
		if ( isset( $fp5_stored_meta['fp5-ads-time'][0] ) ) {
			$fp5_ads[0]['fp5-ads-time'] = $fp5_stored_meta['fp5-ads-time'][0];
		}
		
		$fp5_vast_ads = isset( $fp5_stored_meta['fp5_vast_ads'][0] ) ? maybe_unserialize( $fp5_stored_meta['fp5_vast_ads'][0] ) : array();
		if ( isset( $fp5_stored_meta['fp5-vast-ads-time'][0] ) ) {
			$fp5_vast_ads[0]['fp5-vast-ads-time'] = $fp5_stored_meta['fp5-vast-ads-time'][0];
		}
		if ( isset( $fp5_stored_meta['fp5-vast-ads-url'][0] ) ) {
			$fp5_vast_ads[0]['fp5-vast-ads-url'] = $fp5_stored_meta['fp5-vast-ads-url'][0];
		}


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

			// Check, validate and save checkboxes
			$checkboxes = array(
				'fp5-autoplay',
				'fp5-loop',
				'fp5-fixed-controls',
				'fp5-aspect-ratio',
				'fp5-fixed-width',
				'fp5-no-background',
				'fp5-aside-time',
				'fp5-no-hover',
				'fp5-no-mute',
				'fp5-no-time',
				'fp5-no-volume',
				'fp5-no-embed',
				'fp5-live',
				'fp5-play-button',
				'fp5-show-title',
				'fp5-hls-plugin',
			);

			foreach ( $checkboxes as $checkbox ) {
				if ( isset( $_POST[ $checkbox ] ) ) {
					$value = 'true';
				} else {
					$value = '';
				}
				update_post_meta(
					$post_id,
					$checkbox,
					$value
				);
			}

			// Check, validate and save keys
			$choices = $this->allowed_dropdown_options();
			$keys = array(
				'fp5-select-skin',
				'fp5-preload',
				'fp5-coloring',
				'fp5-lightbox',
				'fp5-vast-vpaidmode',
			);

			foreach ( $keys as $key ) {
				if ( isset( $_POST[ $key ] ) && in_array( $_POST[ $key ] , $choices[ $key ] ) ) {
					update_post_meta(
						$post_id,
						$key,
						sanitize_key( $_POST[ $key ] )
					);
				}
			}

			// Check, validate and save urls
			$urls = array(
				'fp5-splash-image',
				'fp5-mp4-video',
				'fp5-webm-video',
				'fp5-ogg-video',
				'fp5-hls-video',
				'fp5-vtt-subtitles',
				'fp5-description-url',
				'fp5-adrules-xml-url',
			);

			foreach ( $urls as $url ) {
				if ( isset( $_POST[ $url ] ) ) {
					update_post_meta(
						$post_id,
						$url,
						esc_url_raw( $_POST[ $url ] )
					);
				}
			}

			// Check, validate and save numbers
			$numbers = array(
				'fp5-max-width',
				'fp5-width',
				'fp5-height',
				'fp5-user-id',
				'fp5-video-id',
				'fp5-duration',
				'fp5-vast-redirects',
			);

			foreach ( $numbers as $number ) {
				if ( isset( $_POST[ $number ] ) ) {
					update_post_meta(
						$post_id,
						$number,
						$this->sanitize_postive_number_including_zero( $_POST[ $number ] )
					);
				}
			}

			// Check, validate and save text fields
			$text_fields = array(
				'fp5-flash-video',
				'fp5-video-name',
				'fp5-data-rtmp',
				'fp5-qualities',
				'fp5-default-quality',
			);

			foreach ( $text_fields as $text_field ) {
				if ( isset( $_POST[ $text_field ] ) ) {
					update_post_meta(
						$post_id,
						$text_field,
						sanitize_text_field( $_POST[ $text_field ] )
					);
				}
			}

			if ( array_key_exists( 'fp5_ads', $_POST ) && is_array( $_POST[ 'fp5_ads' ] ) ) {
				foreach ( $_POST[ 'fp5_ads' ] as $key => $value ) {
					$new_value[ $key ] = array(
						'fp5-ad-type' => in_array( $value['fp5-ad-type'], $choices['fp5-ad-type'] ) ? $value['fp5-ad-type'] : '',
						'fp5-ads-time' => $this->sanitize_postive_number_including_zero( $value['fp5-ads-time'] ),
					);
				}
				update_post_meta(
					$post_id,
					'fp5_ads',
					$new_value
				);
			} else {
				delete_post_meta(
					$post_id,
					'fp5_ads'
				);
			}
			
			
      if ( array_key_exists( 'fp5_vast_ads', $_POST ) && is_array( $_POST[ 'fp5_vast_ads' ] ) ) {
				foreach ( $_POST[ 'fp5_vast_ads' ] as $key => $value ) {
					$new_value[ $key ] = array(
						'fp5-vast-ads-time' => $this->sanitize_postive_number_including_zero( $value['fp5-vast-ads-time'] ),
						'fp5-vast-ads-url' => esc_url_raw( $value['fp5-vast-ads-url'] ),
					);
				}
				update_post_meta(
					$post_id,
					'fp5_vast_ads',
					$new_value
				);
			} else {
				delete_post_meta(
					$post_id,
					'fp5_vast_ads'
				);
			}

		}

	}

	public function sanitize_postive_number_including_zero( $number ) {
		if ( '' !== $number ) {
			return absint( $number );
		} else {
			return '';
		}
	}

	public function allowed_dropdown_options() {
		$options = array(
			'fp5-ad-type' => array(
				'image_text',
				'video',
				'skippablevideo',
			),
			'fp5-select-skin' => array(
				'minimalist',
				'functional',
				'playful',
			),
			'fp5-preload' => array(
				'none',
				'metadata',
				'auto',
			),
			'fp5-coloring' => array(
				'default',
				'color-alt',
				'color-alt2',
				'color-light',
			),
			'fp5-lightbox' => array(
				'',
				'link',
				'thumbnail',
			),
			'fp5-vast-vpaidmode' => array(
				'',
				'DISABLED',
				'ENABLED',
				'INSECURE',
			),
		);
		return $options;
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
		return ! $is_autosave && ! $is_revision && $is_valid_nonce;

	}

	public function default_hidden_meta_boxes( $hidden, $screen ) {
		if ( 'post' == $screen->base && 'flowplayer5' == $screen->post_type ) {
			$hidden[] = 'authordiv';
		}
		return $hidden;
	}

}
