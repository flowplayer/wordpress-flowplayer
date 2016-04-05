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
class Flowplayer5_Taxonomy {

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

		// Register Custom Taxonomy.
		add_action( 'init', array( $this, 'register_playlist' ) );

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
	 * Register Custom Taxonomy
	 *
	 * @since    1.9.0
	 */
	public function register_playlist() {

		$labels = array(
			'name'                       => _x( 'Playlists', 'Taxonomy General Name', 'flowplayer5' ),
			'singular_name'              => _x( 'Playlist', 'Taxonomy Singular Name', 'flowplayer5' ),
			'menu_name'                  => __( 'Playlists', 'flowplayer5' ),
			'all_items'                  => __( 'All Playlists', 'flowplayer5' ),
			'parent_item'                => __( 'Parent Playlist', 'flowplayer5' ),
			'parent_item_colon'          => __( 'Parent Playlist:', 'flowplayer5' ),
			'new_item_name'              => __( 'New Playlist Name', 'flowplayer5' ),
			'add_new_item'               => __( 'Add New Playlist', 'flowplayer5' ),
			'edit_item'                  => __( 'Edit Playlist', 'flowplayer5' ),
			'update_item'                => __( 'Update Playlist', 'flowplayer5' ),
			'separate_items_with_commas' => __( 'Separate playlists with commas', 'flowplayer5' ),
			'search_items'               => __( 'Search Playlists', 'flowplayer5' ),
			'add_or_remove_items'        => __( 'Add or remove playlists', 'flowplayer5' ),
			'choose_from_most_used'      => __( 'Choose from the most used playlists', 'flowplayer5' ),
			'not_found'                  => __( 'Not Found', 'flowplayer5' ),
		);

		$rewrite = array(
			'slug'                       => 'playlist',
			'with_front'                 => true,
			'hierarchical'               => false,
		);

		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => false,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => false,
			'query_var'                  => 'playlist',
			'meta_box_cb'                => 'fp5_playlist_meta_box',
			'rewrite'                    => $rewrite,
		);

		$args = apply_filters( 'fp5_taxonomy_args', $args );

		register_taxonomy( 'playlist', array( 'flowplayer5' ), $args );

	}
}
