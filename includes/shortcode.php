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
 * Flowplayer video Shortcode
 *
 * Retrieves a media files and settings to display a video.
 *
 * @since 1.0.0
 * @param array $atts Shortcode attributes
 * @example [flowplayer id='39']
 */
function add_fp5_shortcode( $atts ) {

	global $post;


	if ( isset( $atts['id'] ) ) {

		// get post id
		$id = $atts['id'];

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
		$data_ratio       = ( $ratio != 0 ? 'data-ratio="' . $ratio . '"' : '' );
		$attributes       = ( ( $autoplay == 'true' ) ? 'autoplay ' : '' ) . ( ( $loop == 'true' ) ? 'loop ' : '' ) . ( isset ( $preload ) ? 'preload="' . $preload . '" ' : '' ) . ( ( $poster == 'true' ) ? 'poster' : '' );
		$modifier_classes = ( ( $fixed_controls == 'true' ) ? 'fixed-controls ' : '' ) . ( $coloring != 'default' ? $coloring : '' );

		// Shortcode output
		$return = '<div style="' . $size . $splash_style . ' background-size: contain;" class="' . $class . $modifier_classes . '" ' . $data_key . $data_logo . $data_analytics . $data_ratio . '>';
			$return .= '<video ' . $attributes . '>';
				$webm      != '' ? $return .= '<source type="video/webm" src="' . $webm . '">' : '';
				$mp4       != '' ? $return .= '<source type="video/mp4" src="' . $mp4 . '">' : '';
				$ogg       != '' ? $return .= '<source type="video/ogg" src="' . $ogg . '">' : '';
				// $subtitles != '' ? $return .= '<track src="' . $subtitles . '"/>' : '';
			$return .= '</video>';
		$return .= '</div>';

		// Extra options
		$return .= '<script>';
			$width == 0 && $height == 0 ? $return .= 'flowplayer.conf.adaptiveRatio = true;' : '';
		$return .= '</script>';

		// Check if a video has been added before output
		if ( $webm || $mp4 || $ogg ) {
			return $return;
		}

	} else {

		//[flowplayer splash="http://flowplayer.grappler.tk/files/2013/02/trailer_1080p.jpg" webm="http://flowplayer.grappler.tk/files/2013/02/trailer_1080p.webm" mp4="http://flowplayer.grappler.tk/files/2013/02/trailer_1080p.mp4" ogg="http://flowplayer.grappler.tk/files/2013/02/trailer_1080p.ogv" width="1920" height="1080" skin="functional" autoplay="true" loop="true" fixed="true" subtitles="http://www.grappler.tk/flowplayer/wp-content/uploads/2013/08/bunny-en.vtt"]

		// Attributes
		extract( shortcode_atts(
			array(
				'mp4'       => '',
				'webm'      => '',
				'ogg'       => '',
				'skin'      => 'minimalist',
				'splash'    => '',
				'autoplay'  => 'false',
				'loop'      => 'false',
				'subtitles' => '',
				'width'     => '',
				'height'    => '',
				'fixed'     => 'false'
			), $atts )
		);

		// set the options for the shortcode - pulled from the register-settings.php
		$options       = get_option('fp5_settings_general');
		$key           = $options['key'];
		$logo          = $options['logo'];
		$ga_account_id = $options['ga_account_id'];

		// Shortcode processing
		$ratio            = ( ( $width != 0 && $height != 0 ) ? intval( $height ) / intval( $width ) : '' );
		$size             = ( $fixed == 'true' && $width != '' && $height != '' ? 'width:' . $width . 'px; height:' . $height . 'px; ' : ( ( $width != 0 ) ?  'max-width:' . $width . 'px; ' : '' ) );
		$splash_style     = 'background: #777 url(' . $splash . ') no-repeat;';
		$class            = 'flowplayer ' . $skin . ' ' . ( ! empty ( $splash ) ? 'is-splash ' : '' );
		$data_key         = ( 0 < strlen ( $key ) ? 'data-key="' . $key . '" ' : '');
		$data_logo        = ( 0 < strlen  ( $key ) && 0 < strlen  ( $logo ) ? 'data-logo="' . $logo . '" ' : '' );
		$data_analytics   = ( 0 < strlen  ( $ga_account_id ) ? 'data-analytics="' . $ga_account_id . '" ' : '' );
		$data_ratio       = ( $ratio != 0 ? 'data-ratio="' . $ratio . '"' : '' );
		$attributes       = ( ( $autoplay == 'true' ) ? 'autoplay ' : '' ) . ( ( $loop == 'true' ) ? 'loop ' : '' );

		// Shortcode output
		$return = '<div style="' . $size . $splash_style . ' background-size: contain;" class="' . $class . '" ' . $data_key . $data_logo . $data_analytics . $data_ratio . '>';
			$return .= '<video ' . $attributes . '>';
				$webm      != '' ? $return .= '<source type="video/webm" src="' . $webm . '">' : '';
				$mp4       != '' ? $return .= '<source type="video/mp4" src="' . $mp4 . '">' : '';
				$ogg       != '' ? $return .= '<source type="video/ogg" src="' . $ogg . '">' : '';
				// $subtitles != '' ? $return .= '<track src="' . $subtitles . '"/>' : '';
			$return .= '</video>';
		$return .= '</div>';

		return $return;

	}

}

// Register shortcode
add_shortcode( 'flowplayer', 'add_fp5_shortcode' );