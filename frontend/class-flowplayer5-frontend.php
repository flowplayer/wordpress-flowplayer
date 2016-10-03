<?php
/**
 * Flowplayer 5 for WordPress
 *
 * @package   Flowplayer5_Frontend
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
 * Initial Flowplayer Frontend class
 *
 * @package Flowplayer5_Frontend
 * @author  Ulrich Pogson <ulrich@pogson.ch>
 *
 * @since 1.0.0
 */
class Flowplayer5_Frontend {

	/**
	 * Add video to Video post
	 *
	 * Add video html to flowplayer video posts
	 *
	 * @since    1.3.0
	 */
	public function get_video_output( $content ) {

		if ( is_singular( 'flowplayer5' ) || is_post_type_archive( 'flowplayer5' ) || is_tax( 'playlist' ) && is_main_query() ) {
			$atts['id'] = get_the_ID();
			$content .= fp5_video_output( $atts );
		}
		return $content;
	}

	/**
	 * Flowplayer global JavaScript settings.
	 *
	 * @since    1.0.0
	 */
	public function global_config_script() {

		$flowplayer_shortcode = new Flowplayer5_Shortcode();
		if ( ! $flowplayer_shortcode->has_flowplayer_shortcode() ) {
			return;
		}

		$embed_options = array(
			'library' => '',
			'script'  => '',
			'skin'    => '',
			'swf'     => '',
			'swfHls'  => '',
		);
		// set the options for the shortcode - pulled from the display-settings.php
		$options = fp5_get_settings();
		$global_conf['embed'] = array_intersect_key( array_filter( $options ), $embed_options );
		$global_conf = apply_filters( 'fp5_global_config', $global_conf );

		if ( ! empty( $global_conf ) ) {
			echo '<script>flowplayer.conf = ' .json_encode( $global_conf ) . ';</script>' . "\n";
		}

	}

}
