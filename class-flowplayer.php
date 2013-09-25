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
 * Initial Flowplayer class
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
	protected $version = '1.0.0';

	public function get_version() {
		return $this->version;
	}


	/**
	 * Player version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $player_version = '5.4.3';

	public function get_player_version() {
		return $this->player_version;
	}


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

	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_settings_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Load script for global configuration
		add_action( 'wp_head', array( $this, 'global_config_script' ) );

		// Add custom post type
		add_action( 'init', array( $this, 'add_fp5_videos' ) );

		// Add action links
		$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . 'flowplayer.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		// Edit update messages
		add_filter( 'post_updated_messages', array( $this, 'set_messages' ) );

		// Add column and rows
		add_filter( 'manage_flowplayer5_posts_columns',  array( $this, 'shortcode_column'), 5, 2 );
		add_action( 'manage_flowplayer5_posts_custom_column',  array( $this, 'shortcode_row'), 5, 2 );

		// Add file support
		add_filter( 'upload_mimes', array( $this, 'flowplayer_custom_mimes' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
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
		require_once( plugin_dir_path( __FILE__ ) . 'includes/update.php' );
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
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		$screen = get_current_screen();

		wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( '/assets/css/admin.css', __FILE__ ), $this->version );

		// Only run in new post and edit screens
		if ( $screen->base == 'post' ) {
			wp_enqueue_style( 'jquery-colorbox', plugins_url( '/assets/jquery-colorbox/colorbox.css', __FILE__ ), '1.4.27' );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		$screen = get_current_screen();

		if ( $screen->post_type === $this->plugin_slug && $screen->base == 'post' ) {

			wp_enqueue_script( $this->plugin_slug . '-media', plugins_url( '/assets/js/media.js', __FILE__ ), array(), $this->version, false );
			wp_localize_script( $this->plugin_slug . '-media', 'splash_image',
				array(
					'title'  => __( 'Upload or choose a splash image', 'flowplayer5' ), // This will be used as the default title
					'button' => __( 'Insert Splash Image', 'flowplayer5' )              // This will be used as the default button text
				)
			);
			wp_localize_script( $this->plugin_slug . '-media', 'mp4_video',
				array(
					'title'  => __( 'Upload or choose a mp4 video file', 'flowplayer5' ), // This will be used as the default title
					'button' => __( 'Insert mp4 Video', 'flowplayer5' )                   // This will be used as the default button text
				)
			);
			wp_localize_script( $this->plugin_slug . '-media', 'webm_video',
				array(
					'title'  => __( 'Upload or choose a webm video file', 'flowplayer5' ), // This will be used as the default title
					'button' => __( 'Insert webm Video', 'flowplayer5' )                   // This will be used as the default button text
				)
			);
			wp_localize_script( $this->plugin_slug . '-media', 'ogg_video',
				array(
					'title'  => __( 'Upload or choose a ogg video file', 'flowplayer5' ), // This will be used as the default title
					'button' => __( 'Insert ogg Video', 'flowplayer5' )                   // This will be used as the default button text
				)
			);
			wp_localize_script( $this->plugin_slug . '-media', 'webvtt',
				array(
					'title'  => __( 'Upload or choose a webvtt file', 'flowplayer5' ), // This will be used as the default title
					'button' => __( 'Insert webvtt', 'flowplayer5' )                   // This will be used as the default button text
				)
			);
			wp_localize_script( $this->plugin_slug . '-media', 'logo',
				array(
					'title'  => __( 'Upload or choose a logo', 'flowplayer5' ), // This will be used as the default title
					'button' => __( 'Insert Logo', 'flowplayer5' )              // This will be used as the default button text
				)
			);

			wp_enqueue_media();

		}

		// Only run in new post and edit screens
		if ( $screen->base == 'post' ) {
			wp_enqueue_script( 'jquery-colorbox', plugins_url( '/assets/jquery-colorbox/jquery.colorbox-min.js', __FILE__ ), 'jquery', '1.4.27', false );
		}

	}


	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		// set the options for the shortcode - pulled from the register-settings.php
		$options = get_option('fp5_settings_general');
		$cdn     = isset( $options['cdn_option'] );

		global $post;

		// Register shortcode stylesheets and JavaScript
		if( function_exists( 'has_shortcode' ) ) {
			if( has_shortcode( $post->post_content, 'flowplayer' ) ) {
				if( $cdn ) {
					wp_enqueue_style( $this->plugin_slug .'-skins' , '//releases.flowplayer.org/' . $this->player_version . '/skin/all-skins.css' );
				} else {
					wp_enqueue_style( $this->plugin_slug .'-skins', plugins_url( '/assets/flowplayer/skin/all-skins.css', __FILE__ ), $this->player_version );
				}
				wp_enqueue_style( $this->plugin_slug .'-logo-origin', plugins_url( '/assets/css/public.css', __FILE__ ), $this->player_version );
			}
		} else {
			if( $cdn ) {
				wp_enqueue_style( $this->plugin_slug .'-skins' , '//releases.flowplayer.org/' . $this->player_version . '/skin/all-skins.css' );
			} else {
				wp_enqueue_style( $this->plugin_slug .'-skins', plugins_url( '/assets/flowplayer/skin/all-skins.css', __FILE__ ), $this->player_version );
			}
			wp_enqueue_style( $this->plugin_slug .'-logo-origin', plugins_url( '/assets/css/public.css', __FILE__ ), $this->player_version );
		}

	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// set the options for the shortcode - pulled from the register-settings.php
		$options = get_option('fp5_settings_general');
		$key     = ( ! empty ( $options['key'] ) ? $options['key'] : '' );
		$cdn     = isset( $options['cdn_option'] );

		global $post;

		// Register shortcode stylesheets and JavaScript
		if( function_exists( 'has_shortcode' ) ) {
			if( has_shortcode( $post->post_content, 'flowplayer' ) ) {
				if( $cdn ) {
					wp_enqueue_script( $this->plugin_slug . '-script', '//releases.flowplayer.org/' . $this->player_version . '/'. ( $key != '' ? 'commercial/' : '' ) . 'flowplayer.min.js', array( 'jquery' ), $this->player_version, false );
				} else {
					wp_enqueue_script( $this->plugin_slug . '-script', plugins_url( '/assets/flowplayer/' . ( $key != '' ? "commercial/" : "" ) . 'flowplayer.min.js', __FILE__  ), array( 'jquery' ), $this->player_version, false );
				}
			}
		} else {
			if( $cdn ) {
				wp_enqueue_script( $this->plugin_slug . '-script', '//releases.flowplayer.org/' . $this->player_version . '/'. ( $key != '' ? 'commercial/' : '' ) . 'flowplayer.min.js', array( 'jquery' ), $this->player_version, false );
			} else {
				wp_enqueue_script( $this->plugin_slug . '-script', plugins_url( '/assets/flowplayer/' . ( $key != '' ? "commercial/" : "" ) . 'flowplayer.min.js', __FILE__  ), array( 'jquery' ), $this->player_version, false );
			}
		}

	}

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

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		$this->plugin_settings_screen_hook_suffix = add_submenu_page(
			'edit.php?post_type=flowplayer5',
			__( 'Flowplayer Settings', $this->plugin_slug ),
			__( 'Settings', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug . '_settings',
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {

		include_once( 'includes/display-settings.php' );

	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'edit.php?post_type=flowplayer5&page=flowplayer5_settings' ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 * @since    1.0.0
	 */
	public function add_fp5_videos() {

		$labels = apply_filters( 'fp5_post_type_labels', array(
			'name'                => _x( 'Videos', 'Post Type General Name', $this->plugin_slug ),
			'singular_name'       => _x( 'Video', 'Post Type Singular Name', $this->plugin_slug ),
			'menu_name'           => __( 'Video', $this->plugin_slug ),
			'parent_item_colon'   => __( 'Parent Video', $this->plugin_slug ),
			'all_items'           => __( 'All Videos', $this->plugin_slug ),
			'view_item'           => __( 'View Video', $this->plugin_slug ),
			'add_new_item'        => __( 'Add New Video', $this->plugin_slug ),
			'add_new'             => __( 'New Video', $this->plugin_slug ),
			'edit_item'           => __( 'Edit Video', $this->plugin_slug ),
			'update_item'         => __( 'Update Video', $this->plugin_slug ),
			'search_items'        => __( 'Search videos', $this->plugin_slug ),
			'not_found'           => __( 'No videos found', $this->plugin_slug ),
			'not_found_in_trash'  => __( 'No videos found in Trash', $this->plugin_slug ),
		) );

		$args = apply_filters( 'fp5_post_type_args', array(
			'label'               => __( 'Video', $this->plugin_slug ),
			'description'         => __( 'Flowplayer videos', $this->plugin_slug ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 15,
			'menu_icon'           => '',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'query_var'           => 'video',
			'rewrite'             => false,
			'capability_type'     => 'page',
		) );

		register_post_type( 'flowplayer5', $args );

	}

	/**
	 * Edit custom post type messages.
	 *
	 * @since    1.0.0
	 */
	public function set_messages($messages) {

		global $post;

		$messages[$this->plugin_slug] = array(

			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Video updated.', $this->plugin_slug ) . ' ' . sprintf( __( 'Shortcode: %1$s', $this->plugin_slug ), '[flowplayer id="' . get_the_ID() . '"]' ),
			2  => __( 'Custom field updated.', $this->plugin_slug ),
			3  => __( 'Custom field deleted.', $this->plugin_slug ),
			4  => __( 'Video updated.', $this->plugin_slug ) . ' ' . sprintf( __( 'Shortcode: %1$s', $this->plugin_slug ), '[flowplayer id="' . get_the_ID() . '"]' ),
			5  => isset( $_GET['revision'] ) ? sprintf( __( $singular . ' restored to revision from %s', $this->plugin_slug ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Video published.', $this->plugin_slug ) . ' ' . sprintf( __( 'Shortcode: %1$s', $this->plugin_slug ), '[flowplayer id="' . get_the_ID() . '"]' ),
			7  => __( 'Video saved.', $this->plugin_slug ) . ' ' . sprintf( __( 'Shortcode: %1$s', $this->plugin_slug ), '[flowplayer id="' . get_the_ID() . '"]' ),
			8  => __( 'Video submitted.', $this->plugin_slug ) . ' ' . sprintf( __( 'Shortcode: %1$s', $this->plugin_slug ), '[flowplayer id="' . get_the_ID() . '"]' ),
			9  => sprintf( __( 'Video scheduled for: %1$s', $this->plugin_slug ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
			10 => __( 'Video draft updated.', $this->plugin_slug ),

		);

		return $messages;

	}

	/**
	 * Add a column for the shortcodes.
	 *
	 * @since    1.1.0
	 */
	public function shortcode_column( $columns ){

		$columns = array(

			'cb'        => '<input type="checkbox" />',
			'title'     => __( 'Title' ),
			'author'    => __( 'Author' ),
			'shortcode' => __( 'Shortcode', $this->plugin_slug ),
			'date'      => __( 'Date' )

		);

		return $columns;

	}

	/**
	 * Add row with shortcodes
	 *
	 * @since    1.1.0
	 */
	public function shortcode_row( $column, $post_id  ){

		switch ( $column ) {

			case 'shortcode' :
				echo '[flowplayer id="' . $post_id . '"]'; 
				break;

		}

	}

	/**
	 * Add mime support for webm and vtt.
	 *
	 * @since    1.0.0
	 */
	public function flowplayer_custom_mimes( $mimes ){

		$mimes['webm'] = 'video/webm';
		$mimes['vtt'] = 'text/vtt';

		return $mimes;

	}

}