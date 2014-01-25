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
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since    1.0.0
	 */
	private function __construct() {

		$plugin = Flowplayer5::get_instance();
		// Call $plugin_version from public plugin class.
		$this->plugin_version = $plugin->get_plugin_version();
		// Call $player_version from public plugin class.
		$this->player_version = $plugin->get_player_version();
		// Call $plugin_slug from public plugin class.
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Filter video output to video post
		add_filter( 'the_content',  array( $this, 'get_video_output' ) );

		// Load script for Flowplayer global configuration
		add_action( 'wp_head', array( $this, 'global_config_script' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since    1.0.0
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
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$post = get_queried_object();

		// Pull options
		$options = get_option( 'fp5_settings_general' );
		$cdn     = isset( $options['cdn_option'] );
		$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if( $cdn ) {
			$flowplayer5_directory = '//releases.flowplayer.org/' . $this->player_version . '/skin/';
		} else {
			$flowplayer5_directory = plugins_url( '/assets/flowplayer/skin/', __FILE__ );
		}

		// Register stylesheets
		if( function_exists( 'has_shortcode' ) ) {
			$post_content = isset( $post->post_content ) ? $post->post_content : '';
			$has_shortcode = '';
			if( has_shortcode( $post_content, 'flowplayer' ) || 'flowplayer5' == get_post_type() || is_active_widget( false, false, 'flowplayer5-video-widget', true ) || apply_filters( 'fp5_filter_has_shortcode', $has_shortcode ) ) {
				wp_enqueue_style( $this->plugin_slug .'-skins' , trailingslashit( $flowplayer5_directory ) . 'all-skins.css', array(), $this->player_version );
				wp_enqueue_style( $this->plugin_slug .'-logo-origin', plugins_url( '/assets/css/public' . $suffix . '.css', __FILE__ ), array(), $this->plugin_version );
			}
		} else {
			wp_enqueue_style( $this->plugin_slug .'-skins' , trailingslashit( $flowplayer5_directory ) . 'all-skins.css', array(), $this->player_version );
			wp_enqueue_style( $this->plugin_slug .'-logo-origin', plugins_url( '/assets/css/public' . $suffix . '.css', __FILE__ ), array(), $this->plugin_version );
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
		$key     = ( ! empty ( $options['key'] ) ? $options['key'] : '' );
		$cdn     = isset( $options['cdn_option'] );
		$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$flowplayer5_commercial = trailingslashit( WP_CONTENT_DIR ) . 'flowplayer-commercial/flowplayer' . $suffix . '.js';

		if( is_file( $flowplayer5_commercial ) && !$cdn && $key ) {
			$flowplayer5_directory = trailingslashit( WP_CONTENT_URL ) . 'flowplayer-commercial';
		} elseif ( !$cdn && !$key ) {
			$flowplayer5_directory = plugins_url( '/assets/flowplayer', __FILE__  );
		} else {
			$flowplayer5_directory = '//releases.flowplayer.org/' . $this->player_version . '/'. ( $key ? 'commercial' : '' );
		}

		// Register JavaScript
		if( function_exists( 'has_shortcode' ) ) {
			$post_content = isset( $post->post_content ) ? $post->post_content : '';
			$has_shortcode = '';
			if( has_shortcode( $post_content, 'flowplayer' ) || 'flowplayer5' == get_post_type() || is_active_widget( false, false, 'flowplayer5-video-widget', true ) || apply_filters( 'fp5_filter_has_shortcode', $has_shortcode ) ) {
				wp_enqueue_script( $this->plugin_slug . '-script', trailingslashit( $flowplayer5_directory ) . 'flowplayer' . $suffix . '.js', array( 'jquery' ), $this->player_version, false );
			}
		} else {
			wp_enqueue_script( $this->plugin_slug . '-script', trailingslashit( $flowplayer5_directory ) . 'flowplayer' . $suffix . '.js', array( 'jquery' ), $this->player_version, false );
		}

	}

	/**
	 * Add video to post
	 *
	 * Add video html to flowplayer video posts
	 *
	 * @since    1.3.0
	 */
	public function get_video_output( $content ) {
		if( is_singular( 'flowplayer5') || is_post_type_archive( 'flowplayer5') && is_main_query() ) {
			$atts['id'] = get_the_ID();
			$content .= Flowplayer5_Shortcode::create_fp5_video_output( $atts );
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
		$options       = get_option('fp5_settings_general');
		$embed_library = ( ! empty ( $options['library'] ) ? $options['library'] : '' );
		$embed_script  = ( ! empty ( $options['script'] ) ? $options['script'] : '' );
		$embed_skin    = ( ! empty ( $options['skin'] ) ? $options['skin'] : '' );
		$embed_swf     = ( ! empty ( $options['swf'] ) ? $options['swf'] : '' );

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

	}

}