<?php
/**
 * Flowplayer 5 for WordPress
 *
 * @package   Flowplayer5
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
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
 * @since 1.9.0
 */
class Flowplayer5_Post_Type {

	/**
	 * Instance of this class.
	 *
	 * @since    1.9.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization.
	 *
	 * @since    1.9.0
	 */
	private function __construct() {

		// Add custom post type.
		add_action( 'init', array( $this, 'add_fp5_videos' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since    1.9.0
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
	 * Add video Custom Post Type for flowplayer5
	 *
	 * @since    1.9.0
	 */
	public function add_fp5_videos() {

		$labels = array(
			'name'                => _x( 'Videos', 'Post Type General Name', 'flowplayer5' ),
			'singular_name'       => _x( 'Video', 'Post Type Singular Name', 'flowplayer5' ),
			'menu_name'           => __( 'Videos', 'flowplayer5' ),
			'parent_item_colon'   => __( 'Parent Video', 'flowplayer5' ),
			'all_items'           => __( 'All Videos', 'flowplayer5' ),
			'view_item'           => __( 'View Video', 'flowplayer5' ),
			'add_new_item'        => __( 'Add New Video', 'flowplayer5' ),
			'add_new'             => __( 'New Video', 'flowplayer5' ),
			'edit_item'           => __( 'Edit Video', 'flowplayer5' ),
			'update_item'         => __( 'Update Video', 'flowplayer5' ),
			'search_items'        => __( 'Search Videos', 'flowplayer5' ),
			'not_found'           => __( 'No videos found', 'flowplayer5' ),
			'not_found_in_trash'  => __( 'No videos found in Trash', 'flowplayer5' ),
		);

		$supports = array(
			'title',
			'author',
		);

		$rewrite = array(
			'slug'                => __( 'video', 'flowplayer5' ),
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => true,
		);

		$args = array(
			'label'               => __( 'flowplayer5', 'flowplayer5' ),
			'description'         => __( 'Flowplayer Videos', 'flowplayer5' ),
			'labels'              => apply_filters( 'fp5_post_type_labels', $labels ),
			'supports'            => apply_filters( 'fp5_post_type_supports', $supports ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'show_admin_column'   => true,
			'menu_position'       => 15,
			'menu_icon'           => ( version_compare( $GLOBALS['wp_version'], '3.8-alpha', '>' ) ) ? 'dashicons-format-video' : '',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => apply_filters( 'fp5_post_type_rewrite', $rewrite ),
			'query_var'           => 'video',
			'capability_type'     => 'page',
		);

		$args = apply_filters( 'fp5_post_type_args', $args );

		register_post_type( 'flowplayer5', $args );

	}
}
