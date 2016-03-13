<?php
/**
 * Flowplayer 5 for WordPress
 *
 * @package   Flowplayer5
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      https://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Flowplayer5_Plugin {

	public function run() {
		$this->register_common();
		if ( is_admin() ) {
			$this->register_backend();
		} else {
			$this->register_frontend();
		}
	}

	protected function register_common() {
		$post_type = new Flowplayer5_Post_Type();
		add_action( 'init', array( $post_type, 'register' ) );
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_filter( 'upload_mimes', array( $this, 'flowplayer_custom_mimes' ) );
		// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
		register_activation_hook( FP5_PLUGIN_DIR, array( 'Flowplayer5', 'activate' ) );
		register_deactivation_hook( FP5_PLUGIN_DIR, array( 'Flowplayer5', 'deactivate' ) );
	}

	protected function register_backend() {
		$dashboard = new Flowplayer5_Dashboard();
		add_action( 'admin_init', array( $dashboard, 'register' ) );
	}

	protected function register_frontend() {
		$shortcode = new Flowplayer5_Shortcode();
		add_action( 'init', array( $shortcode, 'register' ) );
	}
}

/**
 * Initial Flowplayer5 class
 *
 * @package Flowplayer5
 * @author  Ulrich Pogson <ulrich@pogson.ch>
 *
 * @since 1.0.0
 */
class Flowplayer5 {

	/**
	 * Return the plugin version.
	 *
	 * @since    1.0.0
	 *
	 * @return   Plugin version variable.
	 */
	public function get_plugin_version() {
		return $this->plugin_version;
	}

	/**
	 * Return the player version.
	 *
	 * @since    1.0.0
	 *
	 * @return   Player version variable.
	 */
	public function get_player_version() {
		$options = fp5_get_settings();
		if ( isset( $options['fp_version'] ) && 'fp6' === $options['fp_version'] ) {
			return '6.0.4';
		} else {
			return '5.5.2';
		}
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		require_once( plugin_dir_path( __FILE__ ) . 'update.php' );
		flush_rewrite_rules();
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean $network_wide True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		flush_rewrite_rules();
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, basename( dirname( FP5_PLUGIN_FILE ) ) . '/languages/' );

	}

	/**
	 * Add mime support for webm and vtt.
	 *
	 * @since    1.0.0
	 *
	 * @param    array $mimes    Mime types keyed by the file extension regex corresponding to
	 *                           those types. 'swf' and 'exe' removed from full list. 'htm|html' also
	 *                           removed depending on '$user' capabilities.
	 */
	public function flowplayer_custom_mimes( $mimes ) {

		$mimes['webm'] = 'video/webm';
		$mimes['m3u8'] = 'application/x-mpegurl';
		$mimes['vtt']  = 'text/vtt';

		return $mimes;

	}
}
