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
		}

		if ( isset( $id ) ) {

			/**
			 * New flowplayer shortcode
			 *
			 * @example [flowplayer id="123"]
			 */

			// get the meta from the post type
			$loop           = get_post_meta( $id, 'fp5-loop', true );
			$autoplay       = get_post_meta( $id, 'fp5-autoplay', true );
			$preload        = get_post_meta( $id, 'fp5-preload', true );
			$poster         = '';
			$skin           = get_post_meta( $id, 'fp5-select-skin', true );
			$splash         = get_post_meta( $id, 'fp5-splash-image', true );
			$formats        = array(
				'application/x-mpegurl' => get_post_meta( $id, 'fp5-hls-video', true ),
				'video/webm'            => get_post_meta( $id, 'fp5-webm-video', true ),
				'video/mp4'             => get_post_meta( $id, 'fp5-mp4-video', true ),
				'video/ogg'             => get_post_meta( $id, 'fp5-ogg-video', true),
				'video/flash'           => get_post_meta( $id, 'fp5-flash-video', true),
			);
			$subtitles      = get_post_meta( $id, 'fp5-vtt-subtitles', true );
			$max_width      = get_post_meta( $id, 'fp5-max-width', true );
			$width          = get_post_meta( $id, 'fp5-width', true );
			$height         = get_post_meta( $id, 'fp5-height', true );
			$ratio          = get_post_meta( $id, 'fp5-aspect-ratio', true );
			$fixed          = get_post_meta( $id, 'fp5-fixed-width', true );
			$data_rtmp      = get_post_meta( $id, 'fp5-data-rtmp', true );
			$coloring       = get_post_meta( $id, 'fp5-coloring', true );
			$fixed_controls = get_post_meta( $id, 'fp5-fixed-controls', true );
			$background     = get_post_meta( $id, 'fp5-no-background', true );
			$aside_time     = get_post_meta( $id, 'fp5-aside-time', true );
			$hover          = get_post_meta( $id, 'fp5-no-hover', true );
			$mute           = get_post_meta( $id, 'fp5-no-mute', true );
			$volume         = get_post_meta( $id, 'fp5-no-volume', true );
			$embed          = get_post_meta( $id, 'fp5-no-embed', true );
			$play_button    = get_post_meta( $id, 'fp5-play-button', true );

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

		// Shortcode processing
		$ratio            = ( ( $width != 0 && $height != 0 ) ? intval( $height ) / intval( $width ) : '' );
		$size             = ( $fixed == 'true' && $width != '' && $height != '' ? 'width:' . $width . 'px; height:' . $height . 'px; ' : ( ( $max_width != 0 ) ?  'max-width:' . $max_width . 'px; ' : '' ) );
		$splash_style     = 'background: #777 url(' . esc_url( $splash ) . ') no-repeat;';
		$class            = 'flowplayer ' . $skin . ' ' . ( ! empty ( $splash ) ? 'is-splash ' : '' ) . ( ! empty ( $logo_origin ) ? 'commercial ' : '' );
		$data_key         = ( 0 < strlen ( $key ) ? 'data-key="' . esc_attr( $key ) . '" ' : '');
		$data_logo        = ( 0 < strlen  ( $key ) && 0 < strlen  ( $logo ) ? 'data-logo="' . esc_url( $logo ) . '" ' : '' );
		$data_analytics   = ( 0 < strlen  ( $ga_account_id ) ? 'data-analytics="' . esc_attr( $ga_account_id ) . '" ' : '' );
		$data_ratio       = ( $ratio != 0 ? 'data-ratio="' . esc_attr( $ratio ) . '" ' : '' );
		$data_rtmp        = ( $ratio != 0 ? 'data-rtmp="' . esc_attr( $data_rtmp ) . '" ' : '' );
		$classes = array(
			( 'default' != $coloring ? $coloring : '' ),
			( $fixed_controls ? 'fixed-controls' : '' ),
			( $background ? 'no-background' : '' ),
			( $aside_time ? 'aside-time' : '' ),
			( $hover ? 'no-hover' : '' ),
			( $mute ? 'no-mute' : '' ),
			( $volume ? 'no-volume' : '' ),
			( $play_button ? 'play-button' : '' ),
		);

		$modifier_classes = trim( implode( ' ', $classes ) ); ;
		$flowplayer_data  = $data_key . $data_logo . $data_analytics . $data_ratio . $data_rtmp;
		$attributes       = ( ( $autoplay == 'true' ) ? 'autoplay ' : '' ) . ( ( $loop == 'true' ) ? 'loop ' : '' ) . ( isset ( $preload ) ? 'preload="' . esc_attr( $preload ) . '" ' : '' ) . ( ( $poster == 'true' ) ? 'poster' : '' );

		// Shortcode output
		$return = '<div style="' . esc_attr( $size ) . esc_attr( $splash_style ) . ' background-size: contain;" class="' . esc_attr( $class ) . esc_attr( $modifier_classes ) . '" ' . apply_filters( 'fp5_filter_flowplayer_data', $flowplayer_data ) . '>';
			ob_start();
			$return .= do_action( 'fp5_video_top' );
			$return .= ob_get_contents();
			ob_clean();
			$return .= '<video ' . $attributes . '>';
				foreach( $formats as $format => $src ){
					$src != '' ? $return .= '<source type="' . $format . '" src="' . esc_attr( apply_filters( 'fp5_filter_video_src', $src, $format, $id ) ) . '">' : '';
				}
				$subtitles != '' ? $return .= '<track src="' . esc_url( $subtitles ) . '"/>' : '';
			$return .= '</video>';
			ob_start();
			$return .= do_action( 'fp5_video_bottom' );
			$return .= ob_get_contents();
			ob_clean();
		$return .= '</div>';

		// Extra options
		$return .= '<script>';
			$return .= 'flowplayer.conf = {';
				$width == 0 && esc_attr( $height ) == 0 ? $return .= 'adaptiveRatio: true,' : '';
				$embed == '' ? $return .= 'embed: false,' : '';
			$return .= '};';
		$return .= '</script>';

		// Check if a video has been added before output
		if ( $formats['video/webm'] || $formats['video/mp4'] || $formats['video/ogg'] ) {
			return $return;
		}

	}

}