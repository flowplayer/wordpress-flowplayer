<?php
/**
 * Flowplayer 5 for WordPress
 *
 * @package   Flowplayer5_Frontend
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
		global $flowplayer5_shortcode;

		$plugin = Flowplayer5::get_instance();
		// Call $plugin_version from public plugin class.
		$this->plugin_version = $plugin->get_plugin_version();
		// Call $player_version from public plugin class.
		$this->player_version = $plugin->get_player_version();
		// Call $plugin_slug from public plugin class.
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Pull options
		$options    = fp5_get_settings();
		$cdn        = isset( $options['cdn_option'] );
		$key        = ( ! empty ( $options['key'] ) ? $options['key'] : '' );
		$directory  = ( ! empty ( $options['directory'] ) ? $options['directory'] : '' );
		$fp_version = ( isset( $options['fp_version'] ) && 'fp6' === $options['fp_version'] ) ? '-6' : '';

		$flowplayer5_commercial = trailingslashit( WP_CONTENT_DIR ) . 'flowplayer-commercial';

		if ( is_file( $flowplayer5_commercial ) && ! $cdn && $key ) {
			$this->flowplayer5_directory = $flowplayer5_commercial;
		} elseif ( $directory ) {
			$this->flowplayer5_directory = $directory;
		} elseif ( $cdn && ! $key ) {
			$this->flowplayer5_directory = plugins_url( '/assets/flowplayer' . $fp_version, __FILE__  );
		} else {
			$this->flowplayer5_directory = '//releases.flowplayer.org/' . $this->player_version . '/'. ( $key ? 'commercial' : '' );
		}

		// Start check if posts have videos
		add_action( 'wp_enqueue_scripts', array( $this, 'has_flowplayer_video' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Filter video output to video post
		add_filter( 'the_content',  array( $this, 'get_video_output' ) );

		// Load script for Flowplayer global configuration
		add_action( 'wp_head', array( $this, 'global_config_script' ) );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		// Pull options
		$options = fp5_get_settings();
		$asf_css = ( ! empty ( $options['asf_css'] ) ? $options['asf_css'] : false );
		$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_style( $this->plugin_slug . '-skins', trailingslashit( $this->flowplayer5_directory ) . 'skin/all-skins.css', array(), $this->player_version );
		wp_register_style( $this->plugin_slug . '-logo-origin', plugins_url( '/assets/css/public-concat' . $suffix . '.css', __FILE__ ), array(), $this->plugin_version );
		wp_register_style( $this->plugin_slug . '-asf', esc_url( $asf_css ), array(), null );

		// Register stylesheets
		if ( $this->has_flowplayer_video ) {
			wp_enqueue_style( $this->plugin_slug . '-skins' );
			wp_enqueue_style( $this->plugin_slug . '-logo-origin' );
			if ( $asf_css ) {
				wp_enqueue_style( $this->plugin_slug . '-asf' );
			}
		}

	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$options = fp5_get_settings();
		$asf_js  = ( ! empty ( $options['asf_js'] ) ? $options['asf_js'] : false );
		$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$fp_version = ( isset( $options['fp_version'] ) && 'fp6' === $options['fp_version'] ) ? '-v6' : '';

		wp_register_script( $this->plugin_slug . '-script', trailingslashit( $this->flowplayer5_directory ) . 'flowplayer' . $suffix . '.js', array( 'jquery' ), $this->player_version, false );
		wp_register_script( $this->plugin_slug . '-ima3', '//s0.2mdn.net/instream/html5/ima3.js', array(), null, false );
		wp_register_script( $this->plugin_slug . '-asf', esc_url( $asf_js ), array( $this->plugin_slug . '-ima3' ), null, false );
		wp_register_script( $this->plugin_slug . '-quality-selector', plugins_url( '/assets/drive/quality-selector' . $fp_version . $suffix . '.js', __FILE__ ), array( $this->plugin_slug . '-script' ), $this->player_version, false );
		wp_register_script( 'magnific-popup', plugins_url( '/frontend/assets/magnific-popup/magnific-popup' . $suffix . '.js', FP5_PLUGIN_FILE ), array( 'jquery' ), '1.0.0', false );

		// Register JavaScript
		if ( $this->has_flowplayer_video ) {
			wp_enqueue_script( $this->plugin_slug . '-script' );
			if ( $asf_js ) {
				wp_enqueue_script( $this->plugin_slug . '-asf' );
			}
			$this->video_qualities();
			if ( isset( $this->video_qualities ) ) {
				wp_enqueue_script( $this->plugin_slug . '-quality-selector' );
			}
			if ( get_post_custom_values( 'fp5-lightbox', current( $this->has_flowplayer_shortcode ) ) ) {
				wp_enqueue_script( 'magnific-popup' );
			}
		}
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

	public function has_flowplayer_shortcode() {
		if ( is_404() || isset( $this->has_flowplayer_shortcode ) ) {
			return;
		}

		$post           = get_queried_object();
		$has_shortcode  = array();
		$shortcode_args = array();

		if ( isset( $post->post_content ) ) {
			$shortcode_args = fp5_has_shortcode_arg( $post->post_content, 'flowplayer' );
			if ( is_array( $shortcode_args ) ) {
				foreach ( $shortcode_args as $key => $value ) {
					if ( isset( $value['id'] ) ) {
						$has_shortcode[ 'id' . $value['id'] ] = $value['id'];
					} elseif ( isset( $value['playlist'] ) ) {
						$has_shortcode[ 'playlist' . $value['playlist'] ] = $value['playlist'];
					} elseif ( ! empty( $value['mp4'] ) || ! empty( $value['webm'] ) || ! empty( $value['ogg'] ) || ! empty( $value['flash'] ) || ! empty( $value['hls'] ) ) {
						$video_id = substr( md5( serialize( $value ) ), 0, 5 );
						$has_shortcode[ 'video' . $video_id ] = $video_id;
					}
				}
			}
		} else {
			global $wp_query;
			foreach ( $wp_query->posts as $post ) {
				$post_content = isset( $post->post_content ) ? $post->post_content : '';
				$shortcode_args = fp5_has_shortcode_arg( $post_content, 'flowplayer' );
				if ( ! $shortcode_args ) {
					continue;
				}
				foreach ( $shortcode_args as $key => $value ) {
					if ( isset( $value['id'] ) ) {
						$has_shortcode[ 'id' . $value['id'] ] = $value['id'];
					} elseif ( isset( $value['playlist'] ) ) {
						$has_shortcode[ 'playlist' . $value['playlist'] ] = $value['playlist'];
					} elseif ( ! empty( $value['mp4'] ) || ! empty( $value['webm'] ) || ! empty( $value['ogg'] ) || ! empty( $value['flash'] ) || ! empty( $value['hls'] ) ) {
						$video_id = substr( md5( serialize( $value ) ), 0, 5 );
						$has_shortcode[ 'video' . $video_id ] = $video_id;
					}
				}
			}
		}

		$this->has_flowplayer_shortcode = array_filter( $has_shortcode );
	}

	public function has_flowplayer_video() {
		if ( isset( $this->has_flowplayer_video ) ){
			return;
		}

		$has_video = 'flowplayer5' == get_post_type() || is_active_widget( false, false, 'flowplayer5-video-widget', true );
		$has_video = apply_filters( 'fp5_filter_has_shortcode', $has_video );

		if ( ! $has_video ) {
			$this->has_flowplayer_shortcode();
			$has_video = ! empty ( $this->has_flowplayer_shortcode );
		}

		$this->has_flowplayer_video = $has_video;
	}

	public function video_qualities() {

		if ( isset( $this->video_qualities ) ){
			return;
		}

		$post_id   = '';
		$qualities = array();

		// Check if the post is a flowplayer video
		if ( 'flowplayer5' == get_post_type() && isset ( $post->ID ) ) {
			$post_id = $post->ID;
			$qualities[] = get_post_meta( $post_id, 'fp5-qualities', true );
			$this->video_qualities = $qualities;
			return;
		}

		$this->has_flowplayer_shortcode();
		if ( empty( $this->has_flowplayer_shortcode ) ) {
			return;
		}
		foreach ( $this->has_flowplayer_shortcode as $key => $value ) {
			if ( 'id' . $value === $key ) {
				$qualities[] = get_post_meta( $post_id, 'fp5-qualities', true );
			}
		}

		$this->video_qualities = $qualities;
	}

}
