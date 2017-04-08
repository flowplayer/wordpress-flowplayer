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
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $plugin_version = '2.2.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'flowplayer5';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization.
	 *
	 * @since    1.0.0
	 */
	private function __construct() {

		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add file support.
		add_filter( 'upload_mimes', array( $this, 'flowplayer_custom_mimes' ) );

	}

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
		if ( isset( $options['fp_version'] ) && 'fp7' === $options['fp_version'] ) {
			return '7.0.2';
		} else {
			return '6.0.5';
		}
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return   Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
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
