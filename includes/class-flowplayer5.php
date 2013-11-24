<?php
/**
 * Flowplayer 5 for WordPress
 *
 * @package   Flowplayer5
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
 * Initial Flowplayer5 class
 *
 * @package Flowplayer5
 * @author  Ulrich Pogson <ulrich@pogson.ch>
 */
class Flowplayer5 {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $plugin_version = '1.3.0';

	/**
	 * Player version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $player_version = '5.4.4';

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

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add custom post type
		add_action( 'init', array( $this, 'add_fp5_videos' ) );

		// Add file support
		add_filter( 'upload_mimes', array( $this, 'flowplayer_custom_mimes' ) );

	}

	/**
	 * Return the plugin version.
	 *
	 * @since    1.0.0
	 *
	 *@return    Plugin version variable.
	 */
	public function get_plugin_version() {
		return $this->plugin_version;
	}

	/**
	 * Return the player version.
	 *
	 * @since    1.0.0
	 *
	 *@return    Player version variable.
	 */
	public function get_player_version() {
		return $this->player_version;
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 *@return    Plugin slug variable.
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
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		require_once( plugin_dir_path( __FILE__ ) . 'update.php' );
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		// TODO: Define deactivation functionality here
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
		load_plugin_textdomain( $domain, FALSE, basename( dirname( FP5_PLUGIN_FILE ) ) . '/languages/' );

	}

	/**
	 * Add video Custom Post Type for flowplayer5
	 *
	 * @since    1.0.0
	 */
	public function add_fp5_videos() {

		$labels = apply_filters( 'fp5_post_type_labels', array(
			'name'                => _x( 'Videos', 'Post Type General Name', $this->plugin_slug ),
			'singular_name'       => _x( 'Video', 'Post Type Singular Name', $this->plugin_slug ),
			'menu_name'           => __( 'Videos', $this->plugin_slug ),
			'parent_item_colon'   => __( 'Parent Video', $this->plugin_slug ),
			'all_items'           => __( 'All Videos', $this->plugin_slug ),
			'view_item'           => __( 'View Video', $this->plugin_slug ),
			'add_new_item'        => __( 'Add New Video', $this->plugin_slug ),
			'add_new'             => __( 'New Video', $this->plugin_slug ),
			'edit_item'           => __( 'Edit Video', $this->plugin_slug ),
			'update_item'         => __( 'Update Video', $this->plugin_slug ),
			'search_items'        => __( 'Search Videos', $this->plugin_slug ),
			'not_found'           => __( 'No Videos found', $this->plugin_slug ),
			'not_found_in_trash'  => __( 'No Videos found in Trash', $this->plugin_slug ),
		) );

		$supports = apply_filters( 'fp5_post_type_supports', array(
			'title',
		) );

		$rewrite = apply_filters( 'fp5_post_type_rewrite', array(
			'slug'                => __( 'video', $this->plugin_slug ),
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => true,
		) );

		$args = apply_filters( 'fp5_post_type_args', array(
			'label'               => __( 'flowplayer5', $this->plugin_slug ),
			'description'         => __( 'Flowplayer Videos', $this->plugin_slug ),
			'labels'              => $labels,
			'supports'            => $supports,
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'menu_position'       => 15,
			'menu_icon'           => '',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'query_var'           => 'video',
			'capability_type'     => 'page',
		) );

		register_post_type( 'flowplayer5', $args );

	}

	/**
	 * Add mime support for webm and vtt.
	 *
	 * @since    1.0.0
	 */
	public function flowplayer_custom_mimes( $mimes ){

		$mimes['webm'] = 'video/webm';
		$mimes['vtt']  = 'text/vtt';

		return $mimes;

	}

}