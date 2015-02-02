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
		$options   = get_option( 'fp5_settings_general' );
		$cdn       = isset( $options['cdn_option'] );
		$key       = ( ! empty ( $options['key'] ) ? $options['key'] : '' );
		$directory = ( ! empty ( $options['directory'] ) ? $options['directory'] : '' );

		$flowplayer5_commercial = trailingslashit( WP_CONTENT_DIR ) . 'flowplayer-commercial';

		if ( is_file( $flowplayer5_commercial ) && ! $cdn && $key ) {
			$this->flowplayer5_directory = $flowplayer5_commercial;
		} elseif ( $directory ) {
			$this->flowplayer5_directory = $directory;
		} elseif ( ! $cdn && ! $key ) {
			$this->flowplayer5_directory = plugins_url( '/assets/flowplayer', __FILE__  );
		} else {
			$this->flowplayer5_directory = '//releases.flowplayer.org/' . $this->player_version . '/'. ( $key ? 'commercial' : '' );
		}

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

		$post = get_queried_object();

		// Pull options
		$options = get_option( 'fp5_settings_general' );
		$asf_css  = ( ! empty ( $options['asf_css'] ) ? $options['asf_css'] : false );
		$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_style( $this->plugin_slug . '-skins', trailingslashit( $this->flowplayer5_directory ) . 'skin/all-skins.css', array(), $this->player_version );
		wp_register_style( $this->plugin_slug . '-logo-origin', plugins_url( '/assets/css/public-concat' . $suffix . '.css', __FILE__ ), array(), $this->plugin_version );
		wp_register_style( $this->plugin_slug . '-asf', esc_url( $asf_css ), array(), null );

		// Register stylesheets
		$post_content = isset( $post->post_content ) ? $post->post_content : '';
		$has_shortcode = has_shortcode( $post_content, 'flowplayer' ) || 'flowplayer5' == get_post_type() || is_active_widget( false, false, 'flowplayer5-video-widget', true );
		if ( apply_filters( 'fp5_filter_has_shortcode', $has_shortcode ) ) {
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

		$post = get_queried_object();

		// Pull options
		$options = get_option( 'fp5_settings_general' );
		$asf_js  = ( ! empty ( $options['asf_js'] ) ? $options['asf_js'] : false );

		$post_content = isset( $post->post_content ) ? $post->post_content : '';
		$has_shortcode = has_shortcode( $post_content, 'flowplayer' ) || 'flowplayer5' == get_post_type() || is_active_widget( false, false, 'flowplayer5-video-widget', true );
		$shortcode_atts = fp5_has_shortcode_arg( $post_content, 'flowplayer' );
		$post_id = '';
		if ( isset( $shortcode_atts['id'] ) ) {
			$post_id = $shortcode_atts['id'];
		} elseif ( isset ( $post->ID ) ) {
			$post_id = $post->ID;
		}
		$qualities = get_post_meta( $post_id, 'fp5-qualities', true );

		$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script( $this->plugin_slug . '-script', trailingslashit( $this->flowplayer5_directory ) . 'flowplayer' . $suffix . '.js', array( 'jquery' ), $this->player_version, false );
		wp_register_script( $this->plugin_slug . '-ima3', '//s0.2mdn.net/instream/html5/ima3.js', array(), null, false );
		wp_register_script( $this->plugin_slug . '-asf', esc_url( $asf_js ), array( $this->plugin_slug . '-ima3' ), null, false );
		wp_register_script( $this->plugin_slug . '-quality-selector', plugins_url( '/assets/drive/quality-selector' . $suffix . '.js', __FILE__ ), array( $this->plugin_slug . '-script' ), $this->player_version, false );

		// Register JavaScript
		if ( apply_filters( 'fp5_filter_has_shortcode', $has_shortcode ) ) {
			wp_enqueue_script( $this->plugin_slug . '-script' );
			if ( $asf_js ) {
				wp_enqueue_script( $this->plugin_slug . '-asf' );
			}
			if ( $qualities ) {
				wp_enqueue_script( $this->plugin_slug . '-quality-selector' );
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
		$options       = get_option( 'fp5_settings_general' );
		$embed_library = ( ! empty ( $options['library'] ) ? $options['library'] : '' );
		$embed_script  = ( ! empty ( $options['script'] ) ? $options['script'] : '' );
		$embed_skin    = ( ! empty ( $options['skin'] ) ? $options['skin'] : '' );
		$embed_swf     = ( ! empty ( $options['swf'] ) ? $options['swf'] : '' );
		$asf_js        = ( ! empty ( $options['asf_js'] ) ? $options['asf_js'] : '' );

		if ( $embed_library || $embed_script || $embed_skin || $embed_swf ) {

			$return = '<!-- flowplayer global options -->';
			$return .= '<script>';
			$return .= 'flowplayer.conf = {';
				$return .= 'embed: {';
					$return .= ( ! empty ( $embed_library ) ? 'library: "' . $embed_library . '",' : '' );
					$return .= ( ! empty ( $embed_script ) ? 'script: "' . $embed_script . '",' : '' );
					$return .= ( ! empty ( $embed_skin ) ? 'skin: "' . $embed_skin . '",' : '' );
					$return .= ( ! empty ( $embed_swf ) ? 'swf: "' . $embed_swf . '"' : '' );
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
