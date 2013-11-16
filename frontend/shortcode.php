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
 * Create Flowplayer Video HTML Output
 *
 * Retrieves a media files and settings to display a video.
 *
 * @since    1.0.0
 *
 * @param    array $atts Shortcode attributes
 */
function create_fp5_video_output( $atts ) {

	global $post;
	
	if ( isset( $atts['id'] ) ) {
		$id = $atts['id'];
	} elseif ( 'flowplayer5' == get_post_type() ) {
		$id = get_the_ID();
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
		$fixed_controls = get_post_meta( $id, 'fp5-fixed-controls', true );
		$coloring       = get_post_meta( $id, 'fp5-coloring', true );
		$skin           = get_post_meta( $id, 'fp5-select-skin', true );
		$splash         = get_post_meta( $id, 'fp5-splash-image', true );
		$mp4            = get_post_meta( $id, 'fp5-mp4-video', true );
		$webm           = get_post_meta( $id, 'fp5-webm-video', true );
		$ogg            = get_post_meta( $id, 'fp5-ogg-video', true) ;
		$subtitles      = get_post_meta( $id, 'fp5-vtt-subtitles', true );
		$max_width      = get_post_meta( $id, 'fp5-max-width', true );
		$width          = get_post_meta( $id, 'fp5-width', true );
		$height         = get_post_meta( $id, 'fp5-height', true );
		$ratio          = get_post_meta( $id, 'fp5-aspect-ratio', true );
		$fixed          = get_post_meta( $id, 'fp5-fixed-width', true );

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
	$splash_style     = 'background: #777 url(' . $splash . ') no-repeat;';
	$class            = 'flowplayer ' . $skin . ' ' . ( ! empty ( $splash ) ? 'is-splash ' : '' ) . ( ! empty ( $logo_origin ) ? 'commercial ' : '' );
	$data_key         = ( 0 < strlen ( $key ) ? 'data-key="' . $key . '" ' : '');
	$data_logo        = ( 0 < strlen  ( $key ) && 0 < strlen  ( $logo ) ? 'data-logo="' . $logo . '" ' : '' );
	$data_analytics   = ( 0 < strlen  ( $ga_account_id ) ? 'data-analytics="' . $ga_account_id . '" ' : '' );
	$data_ratio       = ( $ratio != 0 ? 'data-ratio="' . $ratio . '" ' : '' );

	$modifier_classes = ( ( $fixed_controls == 'true' ) ? 'fixed-controls ' : '' ) . ( $coloring != 'default' ? $coloring : '' );
	$flowplayer_data  = $data_key . $data_logo . $data_analytics . $data_ratio;
	$attributes       = ( ( $autoplay == 'true' ) ? 'autoplay ' : '' ) . ( ( $loop == 'true' ) ? 'loop ' : '' ) . ( isset ( $preload ) ? 'preload="' . $preload . '" ' : '' ) . ( ( $poster == 'true' ) ? 'poster' : '' );

	// Shortcode output
	$return = '<div style="' . $size . $splash_style . ' background-size: contain;" class="' . $class . $modifier_classes . '" ' . apply_filters( 'fp5_filter_flowplayer_data', $flowplayer_data ) . '>';
		ob_start();
		$return .= do_action( 'fp5_video_top' );
		$return .= ob_get_contents();
		ob_clean();
		$return .= '<video ' . $attributes . '>';
			$webm      != '' ? $return .= '<source type="video/webm" src="' . $webm . '">' : '';
			$mp4       != '' ? $return .= '<source type="video/mp4" src="' . $mp4 . '">' : '';
			$ogg       != '' ? $return .= '<source type="video/ogg" src="' . $ogg . '">' : '';
			$subtitles != '' ? $return .= '<track src="' . $subtitles . '"/>' : '';
		$return .= '</video>';
		ob_start();
		$return .= do_action( 'fp5_video_bottom' );
		$return .= ob_get_contents();
		ob_clean();
	$return .= '</div>';

	// Extra options
	$return .= '<script>';
		$width == 0 && $height == 0 ? $return .= 'flowplayer.conf.adaptiveRatio = true;' : '';
	$return .= '</script>';

	// Check if a video has been added before output
	if ( $webm || $mp4 || $ogg ) {
		return $return;
	}

}

// Register shortcode
add_shortcode( 'flowplayer', 'create_fp5_video_output' );