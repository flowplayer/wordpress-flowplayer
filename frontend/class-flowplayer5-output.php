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
 * Flowplayer Output Class
 *
 * @package Flowplayer5_Shortcode
 * @author  Ulrich Pogson <ulrich@pogson.ch>
 *
 * @since 1.3.0
 */
class Flowplayer5_Output {

	/**
	 * Create Flowplayer Video HTML Output
	 *
	 * Retrieves a media files and settings to display a video.
	 *
	 * @since    1.3.0
	 *
	 * @param    array $atts Shortcode attributes
	 */
	public function video_output( $atts ) {

		if ( isset( $atts['playlist'] ) ) {
			$playlist_id = $atts['playlist'];
			$playlist_options = get_option( 'playlist_' . absint( $playlist_id ) );
			// Check if old id is being used in the shortcode
			if ( ! $playlist_options && function_exists( 'wp_get_split_term' ) ) {
				$new_term_id = wp_get_split_term( absint( $playlist_id ), 'playlist' );
				$playlist_options = get_option( 'playlist_' . absint( $new_term_id ) );
				if ( $playlist_options ) {
					$playlist_id = $new_term_id;
				}
			}
			if ( ! $playlist_options ) {
				return;
			}
			ob_start();
			require( 'views/display-playlist.php' );
			$html = ob_get_clean();
			return $html;
		} elseif ( isset( $atts['id'] ) ) {
			return self::single_video_output( $atts );
		}
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
	private static function single_video_output( $atts ) {

		if ( isset( $atts['id'] ) ) {

			$id = $atts['id'];

			/**
			 * New flowplayer shortcode
			 *
			 * @example [flowplayer id="123"]
			 */

			// get the meta from the post type
			$custom_fields  = get_post_custom( $id );
			$loop           = self::get_custom_fields( $custom_fields, 'fp5-loop' );
			$loop           = self::get_custom_fields( $custom_fields, 'fp5-loop' );
			$autoplay       = self::get_custom_fields( $custom_fields, 'fp5-autoplay' );
			$preload        = self::get_custom_fields( $custom_fields, 'fp5-preload' );
			$poster         = '';
			$skin           = self::get_custom_fields( $custom_fields, 'fp5-select-skin' );
			$splash         = self::get_custom_fields( $custom_fields, 'fp5-splash-image' );
			$formats        = array(
				'application/x-mpegurl' => self::get_custom_fields( $custom_fields, 'fp5-hls-video' ),
				'video/webm'            => self::get_custom_fields( $custom_fields, 'fp5-webm-video' ),
				'video/mp4'             => self::get_custom_fields( $custom_fields, 'fp5-mp4-video' ),
				'video/ogg'             => self::get_custom_fields( $custom_fields, 'fp5-ogg-video' ),
				'video/flash'           => self::get_custom_fields( $custom_fields, 'fp5-flash-video' ),
			);
			$subtitles       = self::get_custom_fields( $custom_fields, 'fp5-vtt-subtitles' );
			$max_width       = self::get_custom_fields( $custom_fields, 'fp5-max-width' );
			$width           = self::get_custom_fields( $custom_fields, 'fp5-width' );
			$height          = self::get_custom_fields( $custom_fields, 'fp5-height' );
			$ratio           = self::get_custom_fields( $custom_fields, 'fp5-aspect-ratio' );
			$fixed           = self::get_custom_fields( $custom_fields, 'fp5-fixed-width' );
			$data_rtmp       = self::get_custom_fields( $custom_fields, 'fp5-data-rtmp' );
			$quality         = self::get_custom_fields( $custom_fields, 'fp5-default-quality' );
			$qualities       = self::get_custom_fields( $custom_fields, 'fp5-qualities' );
			$coloring        = self::get_custom_fields( $custom_fields, 'fp5-coloring' );
			$fixed_controls  = self::get_custom_fields( $custom_fields, 'fp5-fixed-controls' );
			$background      = self::get_custom_fields( $custom_fields, 'fp5-no-background' );
			$aside_time      = self::get_custom_fields( $custom_fields, 'fp5-aside-time' );
			$show_title      = self::get_custom_fields( $custom_fields, 'fp5-show-title' );
			$no_hover        = self::get_custom_fields( $custom_fields, 'fp5-no-hover' );
			$no_mute         = self::get_custom_fields( $custom_fields, 'fp5-no-mute' );
			$no_volume       = self::get_custom_fields( $custom_fields, 'fp5-no-volume' );
			$no_embed        = self::get_custom_fields( $custom_fields, 'fp5-no-embed' );
			$live            = self::get_custom_fields( $custom_fields, 'fp5-live' );
			$play_button     = self::get_custom_fields( $custom_fields, 'fp5-play-button' );
			$ads_time        = self::get_custom_fields( $custom_fields, 'fp5-ads-time' );
			$ad_type         = self::get_custom_fields( $custom_fields, 'fp5-ad-type' );
			$description_url = self::get_custom_fields( $custom_fields, 'fp5-description-url', 'location.href' );
			$title           = get_the_title( $id );

		} else {

			/**
			 * Old flowplayer shortcode
			 *
			 * @example [flowplayer splash="trailer_1080p.jpg" webm="trailer_1080p.webm" mp4="trailer_1080p.mp4" ogg="trailer_1080p.ogv" width="1920" height="1080" skin="functional" autoplay="true" loop="true" fixed="false" subtitles="bunny-en.vtt" fixed_controls="true" coloring="default" preload="auto"]
			 */

			// Attributes
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
					'poster'         => '',
				),
				$atts
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

		// Global settings

		// set the options for the shortcode - pulled from the register-settings.php
		$options       = fp5_get_settings();
		$key           = ( isset( $options['key'] ) ) ? $options['key'] : '';
		$ga_account_id = ( isset( $options['ga_account_id'] ) ) ? $options['ga_account_id'] : '';
		$logo          = ( isset( $options['logo'] ) ) ? $options['logo'] : '';
		$logo_origin   = ( isset( $options['logo_origin'] ) ) ? $options['logo_origin'] : '';
		$brand_text    = ( isset( $options['brand_text'] ) ) ? $options['brand_text'] : '';
		$text_origin   = ( isset( $options['text_origin'] ) ) ? $options['text_origin'] : '';
		$asf_test      = ( isset( $options['asf_test'] ) ) ? $options['asf_test'] : '';
		$asf_js        = ( isset( $options['asf_js'] ) ) ? $options['asf_js'] : '';
		$fp_version    = ( isset( $options['fp_version'] ) ) ? $options['fp_version'] : '';

		// Shortcode processing
		$ratio = ( ( $width != 0 && $height != 0 ) ? intval( $height ) / intval( $width ) : '' );
		if ( $fixed == 'true' && $width != '' && $height != '' ) {
			$size = 'width:' . $width . 'px; height:' . $height . 'px; ';
		} elseif ( $max_width != 0 ) {
			$size = 'max-width:' . $max_width . 'px; ';
		}
		$style = array(
			$size,
			'background-image: url(' . esc_url( $splash ) . ');',
		);

		// Prepare div data config
		$data_config = array();
		if ( has_filter( 'fp5_filter_flowplayer_data' ) ) {
			$data_config = array(
				( 0 < strlen( $key ) ? 'data-key="' . esc_attr( $key ) . '"' : '' ),
				( 0 < strlen( $key ) && 0 < strlen( $logo ) ? 'data-logo="' . esc_url( $logo ) . '"' : '' ),
				( 0 < strlen( $ga_account_id ) ? 'data-analytics="' . esc_attr( $ga_account_id ) . '"' : '' ),
				( $ratio != 0 ? 'data-ratio="' . esc_attr( $ratio ) . '"' : '' ),
				( ! empty ( $data_rtmp ) ? 'data-rtmp="' . esc_attr( $data_rtmp ) . '"' : '' ),
				( ! empty ( $quality ) ? 'data-default-quality="' . esc_attr( $quality ) . '"' : '' ),
				( ! empty ( $qualities ) ? 'data-qualities="' . esc_attr( $qualities ) . '"' : '' ),
			);
		}

		// Prepare video tag data config
		$video_data_config = array();
		if ( ! empty ( $title ) && ! empty ( $show_title ) ) {
			$video_data_config['title'] = esc_attr( $title );
		}
		$video_data_config = apply_filters( 'fp5_video_data_config', $video_data_config );

		// Prepare JS config
		$js_config = array();
		if ( 0 == $width && 0 == $height ) {
			$js_config['adaptiveRatio'] = 'true';
		}
		if ( 'true' == $live ) {
			$js_config['live'] = esc_attr( $live );
		}
		if ( 'true' == $no_embed ) {
			$js_config['embed'] = 'false';
		}
		if ( 0 < strlen( $key ) ) {
			$js_config['key'] = esc_attr( $key );
		}
		if ( 0 < strlen( $key ) && 0 < strlen( $logo ) ) {
			$js_config['logo'] = esc_url( $logo );
		}
		if ( 0 < strlen( $ga_account_id ) ) {
			$js_config['analytics'] = esc_attr( $ga_account_id );
		}
		if ( $ratio != 0 ) {
			$js_config['ratio'] = esc_attr( $ratio );
		}
		if ( ! empty ( $data_rtmp ) ) {
			$js_config['rtmp'] = esc_attr( $data_rtmp );
		}
		if ( ! empty ( $quality ) ) {
			$js_config['defaultQuality'] = esc_attr( $quality );
		}
		if ( ! empty ( $qualities ) ) {
			$js_config['qualities'] = esc_attr( $qualities );
		}
		$js_config = apply_filters( 'fp5_js_config', $js_config );

		if ( ! empty ( $brand_text ) ) {
			$js_brand_config['text'] = esc_attr( $brand_text );
		}
		if ( 'true' == $text_origin ) {
			$js_brand_config['showOnOrigin'] = esc_attr( $text_origin );
		}
		$js_brand_config = apply_filters( 'fp5_js_brand_config', $js_brand_config );

		$classes = array(
			'flowplayer-video flowplayer-video-' . $id,
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

		$asf_test = ( ! empty( $asf_test ) ? 'on' : 'off' );
		$ads_time = ( isset( $ads_time ) ? $ads_time : '' );
		$ads_time = ( 0 == $ads_time ? 0.01 : $ads_time );
		$ad_type  = ( ! empty( $ad_type ) ? esc_attr( $ad_type ) : '' );

		$source = array();
		foreach ( $formats as $format => $src ) {
			if ( ! empty( $src ) ) {
				$source[ $format ] = '<source type="' . esc_attr( $format ) . '" src="' . esc_attr( apply_filters( 'fp5_filter_video_src', $src, $format, $id ) ) . '">';
			}
		}

		if ( '' != $subtitles ) {
			$track = '<track src="' . esc_url( $subtitles ) . '"/>';
		} else {
			$track = '';
		}

		// Check if a video has been added before output
		if ( $formats['video/webm'] || $formats['video/mp4'] || $formats['video/ogg'] || $formats['video/flash'] || $formats['application/x-mpegurl'] ) {
			ob_start();
			require( 'views/display-single-video.php' );
			$html = ob_get_clean();
			return $html;
		}

	}

	/**
	 * Check if option exists
	 *
	 * @since    1.9.0
	 */
	private static function get_custom_fields( $custom_fields, $key, $else = '' ) {
		if ( ! empty( $custom_fields[ $key ][0] ) ) {
			return $custom_fields[ $key ][0];
		} else {
			return $else;
		}
	}

	private static function trim_implode( $values ) {
		return trim( implode( ' ', array_filter( $values ) ) );
	}

	private static function process_data_config( $values ) {
		foreach ( $values as $key => $value ) {
			$output[] = 'data-' . esc_html( $key ) . '="' . esc_html( $value ) . '" ';
		}
		return implode( ' ', $output );
	}

	private static function process_js_config( $values ) {
		foreach ( $values as $key => $value ) {
			$output[] = esc_html( $key ) . ':"' . esc_html( $value ) . '",';
		}
		return rtrim( implode( ' ', $output ), ',' );
	}

}
