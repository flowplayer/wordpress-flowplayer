<?php
/**
 * Flowplayer 5 for WordPress
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
 * Flowplayer Shortcode Class
 *
 * @package Flowplayer5_Shortcode
 * @author  Ulrich Pogson <ulrich@pogson.ch>
 *
 * @since 1.3.0
 */
class Flowplayer5_Shortcode {

	/**
	 * Instance of this class.
	 *
	 * @since    1.3.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Add shortcode
	 *
	 * @since    1.3.0
	 */
	private function __construct() {

		// Register shortcode
		add_shortcode( 'flowplayer', array( $this, 'create_fp5_video_output' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since    1.3.0
	 *
	 * @return   object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	/**
	 * Create Flowplayer Video HTML Output
	 *
	 * Retrieves a media files and settings to display a video.
	 *
	 * @since    1.3.0
	 *
	 * @param    array $atts Shortcode attributes
	 */
	public function create_fp5_video_output( $atts ) {

		if ( isset( $atts['id'] ) ) {

			$id = $atts['id'];

			/**
			 * New flowplayer shortcode
			 *
			 * @example [flowplayer id="123"]
			 */

			// get the meta from the post type
			$custom_fields  = get_post_custom( $id );
			$loop           = $this->get_custom_fields( $custom_fields, 'fp5-loop' );
			$loop           = $this->get_custom_fields( $custom_fields, 'fp5-loop' );
			$autoplay       = $this->get_custom_fields( $custom_fields, 'fp5-autoplay' );
			$preload        = $this->get_custom_fields( $custom_fields, 'fp5-preload' );
			$poster         = '';
			$skin           = $this->get_custom_fields( $custom_fields, 'fp5-select-skin' );
			$splash         = $this->get_custom_fields( $custom_fields, 'fp5-splash-image' );
			$formats        = array(
				'application/x-mpegurl' => $this->get_custom_fields( $custom_fields, 'fp5-hls-video' ),
				'video/webm'            => $this->get_custom_fields( $custom_fields, 'fp5-webm-video' ),
				'video/mp4'             => $this->get_custom_fields( $custom_fields, 'fp5-mp4-video' ),
				'video/ogg'             => $this->get_custom_fields( $custom_fields, 'fp5-ogg-video' ),
				'video/flash'           => $this->get_custom_fields( $custom_fields, 'fp5-flash-video' ),
			);
			$subtitles      = $this->get_custom_fields( $custom_fields, 'fp5-vtt-subtitles' );
			$max_width      = $this->get_custom_fields( $custom_fields, 'fp5-max-width' );
			$width          = $this->get_custom_fields( $custom_fields, 'fp5-width' );
			$height         = $this->get_custom_fields( $custom_fields, 'fp5-height' );
			$ratio          = $this->get_custom_fields( $custom_fields, 'fp5-aspect-ratio' );
			$fixed          = $this->get_custom_fields( $custom_fields, 'fp5-fixed-width' );
			$data_rtmp      = $this->get_custom_fields( $custom_fields, 'fp5-data-rtmp' );
			$coloring       = $this->get_custom_fields( $custom_fields, 'fp5-coloring' );
			$fixed_controls = $this->get_custom_fields( $custom_fields, 'fp5-fixed-controls' );
			$background     = $this->get_custom_fields( $custom_fields, 'fp5-no-background' );
			$aside_time     = $this->get_custom_fields( $custom_fields, 'fp5-aside-time' );
			$no_hover       = $this->get_custom_fields( $custom_fields, 'fp5-no-hover' );
			$no_mute        = $this->get_custom_fields( $custom_fields, 'fp5-no-mute' );
			$no_volume      = $this->get_custom_fields( $custom_fields, 'fp5-no-volume' );
			$no_embed       = $this->get_custom_fields( $custom_fields, 'fp5-no-embed' );
			$play_button    = $this->get_custom_fields( $custom_fields, 'fp5-play-button' );
			$ads_time       = $this->get_custom_fields( $custom_fields, 'fp5-ads-time' );
			$ad_type        = $this->get_custom_fields( $custom_fields, 'fp5-ad-type' );

		} else {

			/**
			 * Old flowplayer shortcode
			 *
			 * @example [flowplayer splash="trailer_1080p.jpg" webm="trailer_1080p.webm" mp4="trailer_1080p.mp4" ogg="trailer_1080p.ogv" width="1920" height="1080" skin="functional" autoplay="true" loop="true" fixed="false" subtitles="bunny-en.vtt" fixed_controls="true" coloring="default" preload="auto"]
			 */

			// Attributes
			extract(
				shortcode_atts(
					array(
						'mp4'            => '',
						'webm'           => '',
						'ogg'            => '',
						'flash'          => '',
						'skin'           => 'minimalist',
						'splash'         => '',
						'autoplay'       => 'false',
						'loop'           => 'false',
						'subtitles'      => '',
						'width'          => '',
						'height'         => '',
						'fixed'          => 'false',
						'fixed_controls' => '',
						'coloring'       => 'default',
						'preload'        => 'auto',
						'poster'         => ''
					),
					$atts
				)
			);

			$max_width = $width;

			$formats = array(
				'application/x-mpegurl' => $hls,
				'video/webm'            => $webm,
				'video/mp4'             => $mp4,
				'video/ogg'             => $ogg,
				'video/flash'           => $flash,
			);

		}

		// set the options for the shortcode - pulled from the register-settings.php
		$options       = get_option('fp5_settings_general');
		$key           = ( isset( $options['key'] ) ) ? $options['key'] : '';
		$logo          = ( isset( $options['logo'] ) ) ? $options['logo'] : '';
		$ga_account_id = ( isset( $options['ga_account_id'] ) ) ? $options['ga_account_id'] : '';
		$logo_origin   = ( isset( $options['logo_origin'] ) ) ? $options['logo_origin'] : '';
		$asf_test      = ( isset( $options['asf_test'] ) ) ? $options['asf_test'] : '';
		$asf_js        = ( isset( $options['asf_js'] ) ) ? $options['asf_js'] : '';

		// Shortcode processing
		$ratio            = ( ( $width != 0 && $height != 0 ) ? intval( $height ) / intval( $width ) : '' );

		$style = array(
			( $fixed == 'true' && $width != '' && $height != '' ? 'width:' . $width . 'px; height:' . $height . 'px; ' : ( ( $max_width != 0 ) ?  'max-width:' . $max_width . 'px;' : '' ) ),
			'background: #777 url(' . esc_url( $splash ) . ') no-repeat; background-size: contain;',
		);

		$data = array(
			( 0 < strlen ( $key ) ? 'data-key="' . esc_attr( $key ) . '"' : ''),
			( 0 < strlen  ( $key ) && 0 < strlen  ( $logo ) ? 'data-logo="' . esc_url( $logo ) . '"' : '' ),
			( 0 < strlen  ( $ga_account_id ) ? 'data-analytics="' . esc_attr( $ga_account_id ) . '"' : '' ),
			( $ratio != 0 ? 'data-ratio="' . esc_attr( $ratio ) . '"' : '' ),
			( $ratio != 0 ? 'data-rtmp="' . esc_attr( $data_rtmp ) . '"' : '' ),
		);

		$classes = array(
			'flowplayer',
			$skin,
			( ! empty ( $splash ) ? 'is-splash' : '' ),
			( ! empty ( $logo_origin ) ? 'commercial' : '' ),
			( isset( $id ) ? 'flowplayer-' . $id : '' ),
			( 'default' != $coloring ? $coloring : '' ),
			( $fixed_controls ? 'fixed-controls' : '' ),
			( $background ? 'no-background' : '' ),
			( $aside_time ? 'aside-time' : '' ),
			( $no_hover ? 'no-hover' : '' ),
			( $no_mute ? 'no-mute' : '' ),
			( $no_volume ? 'no-volume' : '' ),
			( $play_button ? 'play-button' : '' ),
		);

		$attributes = array(
			( ( $autoplay == 'true' ) ? 'autoplay' : '' ),
			( ( $loop == 'true' ) ? 'loop' : '' ),
			( isset ( $preload ) ? 'preload="' . esc_attr( $preload ) . '"' : '' ),
			( ( $poster == 'true' ) ? 'poster' : '' ),
		);

		$asf_test = ( isset ( $ads_time ) ? 'on' : '' );
		$ads_time = ( isset ( $ads_time ) ? intval( $ads_time ) : '' );
		$ad_type  = ( isset ( $ad_type ) ? esc_attr( $ad_type ) : '' );

		$source = array();
		foreach( $formats as $format => $src ){
			if ( !empty( $src ) ) {
				$source[ $format ] = '<source type="' . $format . '" src="' . esc_attr( apply_filters( 'fp5_filter_video_src', $src, $format, $id ) ) . '">';
			}
		}

		if ( '' != $subtitles ) {
			$track = '<track src="' . esc_url( $subtitles ) . '"/>';
		} else {
			$track = '';
		}

		if ( 0 == $width && 0 == $height ) {
			$adaptive_ratio = 'adaptiveRatio: true,';
		} else {
			$adaptive_ratio = '';
		}

		if ( '' != $no_embed ) {
			$embed = 'embed: false,';
		} else {
			$embed = '';
		}

		// Check if a video has been added before output
		if ( $formats['video/webm'] || $formats['video/mp4'] || $formats['video/ogg'] || $formats['video/flash'] || $formats['application/x-mpegurl'] ) {
			include_once( 'views/display-shortcode.php' );
		}

	}

	/**
	 * Check if option exists
	 *
	 * @since    1.9.0
	 */
	public function get_custom_fields( $custom_fields, $key ) {
		if ( isset( $custom_fields[ $key ][0] ) ) {
			return $custom_fields[ $key ][0];
		} else {
			return '';
		}
	}

}
