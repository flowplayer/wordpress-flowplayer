<?php
/**
 * Flowplayer 5 for WordPress
 *
 * @package   Flowplayer5_Admin
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
 * Initial Flowplayer5 Admin class
 *
 * @package Flowplayer5_Admin
 * @author  Ulrich Pogson <ulrich@pogson.ch>
 */
class Flowplayer5_Admin {

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
	 * @since    1.0.0
	 */
	private function __construct() {

		$plugin = Flowplayer5::get_instance();
		// Call $plugin_version from public plugin class.
		$this->plugin_version = $plugin->get_plugin_version();
		// Call $plugin_slug from public plugin class.
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add custom post type
		add_action( 'init', array( $this, 'add_fp5_videos' ) );

		// Add action links
		$plugin_basename = plugin_basename( plugin_dir_path( FP5_PLUGIN_FILE ) . 'flowplayer.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		// Edit update messages
		add_filter( 'post_updated_messages', array( $this, 'set_messages' ) );

		// Add column and rows
		add_filter( 'manage_flowplayer5_posts_columns',  array( $this, 'shortcode_column'), 5, 2 );
		add_action( 'manage_flowplayer5_posts_custom_column',  array( $this, 'shortcode_row'), 5, 2 );

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
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since    1.0.0
	 *
	 * @return   null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		$screen = get_current_screen();

		wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( '/assets/css/admin.css', __FILE__ ), $this->plugin_version );

		// Only run in new post and edit screens
		if ( $screen->base == 'post' ) {
			wp_enqueue_style( 'jquery-colorbox', plugins_url( '/assets/jquery-colorbox/colorbox.css', __FILE__ ), '1.4.27' );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since    1.0.0
	 *
	 * @return   null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		$screen = get_current_screen();

		// Only run on flowplayer new and edit post screens
		if ( $screen->post_type === $this->plugin_slug && $screen->base == 'post' ) {

			wp_enqueue_script( $this->plugin_slug . '-media', plugins_url( '/assets/js/media.js', __FILE__ ), array(), $this->plugin_version, false );
			wp_localize_script( $this->plugin_slug . '-media', 'splash_image',
				array(
					'title'  => __( 'Upload or choose a splash image', $this->plugin_slug ), // This will be used as the default title
					'button' => __( 'Insert Splash Image', $this->plugin_slug )              // This will be used as the default button text
				)
			);
			wp_localize_script( $this->plugin_slug . '-media', 'mp4_video',
				array(
					'title'  => __( 'Upload or choose a mp4 video file', $this->plugin_slug ), // This will be used as the default title
					'button' => __( 'Insert mp4 Video', $this->plugin_slug )                   // This will be used as the default button text
				)
			);
			wp_localize_script( $this->plugin_slug . '-media', 'webm_video',
				array(
					'title'  => __( 'Upload or choose a webm video file', $this->plugin_slug ), // This will be used as the default title
					'button' => __( 'Insert webm Video', $this->plugin_slug )                   // This will be used as the default button text
				)
			);
			wp_localize_script( $this->plugin_slug . '-media', 'ogg_video',
				array(
					'title'  => __( 'Upload or choose a ogg video file', $this->plugin_slug ), // This will be used as the default title
					'button' => __( 'Insert ogg Video', $this->plugin_slug )                   // This will be used as the default button text
				)
			);
			wp_localize_script( $this->plugin_slug . '-media', 'webvtt',
				array(
					'title'  => __( 'Upload or choose a webvtt file', $this->plugin_slug ), // This will be used as the default title
					'button' => __( 'Insert webvtt', $this->plugin_slug )                   // This will be used as the default button text
				)
			);
			wp_localize_script( $this->plugin_slug . '-media', 'logo',
				array(
					'title'  => __( 'Upload or choose a logo', $this->plugin_slug ), // This will be used as the default title
					'button' => __( 'Insert Logo', $this->plugin_slug )              // This will be used as the default button text
				)
			);

			wp_enqueue_media();

		}

		// Only run on settings screen
		if ( $screen->post_type === $this->plugin_slug && $screen->id == 'flowplayer5_page_flowplayer5_settings' ) {

			wp_enqueue_script( $this->plugin_slug . '-settings', plugins_url( '/assets/js/settings.js', __FILE__ ), array(), $this->plugin_version, false );
			wp_localize_script( $this->plugin_slug . '-settings', 'logo',
				array(
					'title'  => __( 'Upload or choose a logo', $this->plugin_slug ), // This will be used as the default title
					'button' => __( 'Insert Logo', $this->plugin_slug )              // This will be used as the default button text
				)
			);

			wp_enqueue_media();

		}

		// Only run on new and edit post screens
		if ( $screen->base == 'post' ) {
			wp_enqueue_script( 'jquery-colorbox', plugins_url( '/assets/jquery-colorbox/jquery.colorbox-min.js', __FILE__ ), 'jquery', '1.4.27', false );
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

		include_once( 'views/display-settings.php' );

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
	public function shortcode_row( $column, $post_id ){

		switch ( $column ) {

			case 'shortcode' :
				echo '[flowplayer id="' . $post_id . '"]'; 
				break;

		}

	}

}