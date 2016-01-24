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
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		// Filter video output to video post
		add_filter( 'the_content',  array( $this, 'get_video_output' ) );

		// Load script for Flowplayer global configuration
		add_action( 'wp_head', array( $this, 'global_config_script' ) );

	}

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

		// set the options for the shortcode - pulled from the display-settings.php
		$options       = fp5_get_settings();
		$embed_library = ( ! empty ( $options['library'] ) ? $options['library'] : '' );
		$embed_script  = ( ! empty ( $options['script'] ) ? $options['script'] : '' );
		$embed_skin    = ( ! empty ( $options['skin'] ) ? $options['skin'] : '' );
		$embed_swf     = ( ! empty ( $options['swf'] ) ? $options['swf'] : '' );
		$asf_js        = ( ! empty ( $options['asf_js'] ) ? $options['asf_js'] : '' );

		if ( ( $embed_library || $embed_script || $embed_skin || $embed_swf ) && $this->has_flowplayer_video ) {

			$return = '<!-- flowplayer global options -->';
			$return .= '<script>';
			$return .= 'flowplayer.conf = {';
				$return .= 'embed: {';
					$return .= ( ! empty ( $embed_library ) ? 'library: "' . esc_js( $embed_library ) . '",' : '' );
					$return .= ( ! empty ( $embed_script ) ? 'script: "' . esc_js( $embed_script ) . '",' : '' );
					$return .= ( ! empty ( $embed_skin ) ? 'skin: "' . esc_js( $embed_skin ) . '",' : '' );
					$return .= ( ! empty ( $embed_swf ) ? 'swf: "' . esc_js( $embed_swf ) . '"' : '' );
				$return .= '}';
			$return .= '};';
			$return .= '</script>';

			echo $return;

		}

		if ( $asf_js ) { ?>
			<script>
			flowplayer(function(api, root) {
				flowplayer_ima.create(api, root);
			});
			</script>
		<?php }

	}

}
