<?php
/**
 * Flowplayer 5 for WordPress
 *
 * @package   Flowplayer5_Admin
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

		// Add action & meta links
		$plugin_basename = plugin_basename( plugin_dir_path( FP5_PLUGIN_FILE ) . 'flowplayer.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
		add_filter( 'plugin_row_meta', array( $this, 'add_plugin_row_meta' ), 10, 2 );

		// Edit update messages
		add_filter( 'post_updated_messages', array( $this, 'set_messages' ), 10, 2 );

		// Add column and rows
		add_filter( 'manage_flowplayer5_posts_columns', array( $this, 'shortcode_column' ), 5, 2 );
		add_action( 'manage_flowplayer5_posts_custom_column', array( $this, 'shortcode_row' ), 5, 2 );

		// Add dashboard counts
		add_action( 'dashboard_glance_items', array( $this, 'add_dashboard_counts' ) );
		add_action( 'right_now_content_table_end', array( $this, 'add_dashboard_counts_old' ) );

		// Delete cache on update
		add_action( 'save_post_flowplayer5', array( $this, 'delete_cache_post' ), 10, 3 );
		add_action( 'edited_playlist', array( $this, 'delete_cache_tax' ), 10, 2 );

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
		$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( '/assets/css/admin' . $suffix . '.css', __FILE__ ), $this->plugin_version );

		// Only run in new post and edit screens
		if ( $screen->base == 'post' ) {
			wp_enqueue_style( 'jquery-colorbox', plugins_url( '/assets/jquery-colorbox/colorbox' . $suffix . '.css', __FILE__ ), $this->plugin_version );
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
		$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Only run on flowplayer new and edit post screens
		if ( $screen->post_type === $this->plugin_slug && $screen->base == 'post' ) {

			wp_enqueue_script( 'repeatable-fields', plugins_url( '/assets/js/repeatable-fields' . $suffix . '.js', __FILE__ ), array( 'jquery-ui-sortable' ), $this->plugin_version, false );
			wp_enqueue_script( $this->plugin_slug . '-media', plugins_url( '/assets/js/media' . $suffix . '.js', __FILE__ ), array( 'jquery-ui-tabs' ), $this->plugin_version, false );
			wp_localize_script(
				$this->plugin_slug . '-media',
				'fp5_splash_image',
				array(
					'title'  => __( 'Upload or choose a splash image', $this->plugin_slug ), // This will be used as the default title
					'button' => __( 'Insert Splash Image', $this->plugin_slug )              // This will be used as the default button text
				)
			);
			wp_localize_script(
				$this->plugin_slug . '-media',
				'fp5_mp4_video',
				array(
					'title'  => __( 'Upload or choose a MP4 video file', $this->plugin_slug ), // This will be used as the default title
					'button' => __( 'Insert MP4 Video', $this->plugin_slug )                   // This will be used as the default button text
				)
			);
			wp_localize_script(
				$this->plugin_slug . '-media',
				'fp5_webm_video',
				array(
					'title'  => __( 'Upload or choose a WEBM video file', $this->plugin_slug ), // This will be used as the default title
					'button' => __( 'Insert WEBM Video', $this->plugin_slug )                   // This will be used as the default button text
				)
			);
			wp_localize_script(
				$this->plugin_slug . '-media',
				'fp5_ogg_video',
				array(
					'title'  => __( 'Upload or choose a OGG video file', $this->plugin_slug ), // This will be used as the default title
					'button' => __( 'Insert OGG Video', $this->plugin_slug )                   // This will be used as the default button text
				)
			);
			wp_localize_script(
				$this->plugin_slug . '-media',
				'fp5_flash_video',
				array(
					'title'  => __( 'Upload or choose a flash optimized video file', $this->plugin_slug ), // This will be used as the default title
					'button' => __( 'Insert Flash Video', $this->plugin_slug )                             // This will be used as the default button text
				)
			);
			wp_localize_script(
				$this->plugin_slug . '-media',
				'fp5_hls_video',
				array(
					'title'  => __( 'Upload or choose a HLS video file', $this->plugin_slug ), // This will be used as the default title
					'button' => __( 'Insert HLS Video', $this->plugin_slug )                   // This will be used as the default button text
				)
			);
			wp_localize_script(
				$this->plugin_slug . '-media',
				'fp5_webvtt',
				array(
					'title'  => __( 'Upload or choose a webvtt file', $this->plugin_slug ), // This will be used as the default title
					'button' => __( 'Insert webvtt', $this->plugin_slug )                   // This will be used as the default button text
				)
			);

			wp_enqueue_media();

		}

		// Only run on settings screen
		if ( $screen->post_type === $this->plugin_slug && $screen->id == 'flowplayer5_page_flowplayer5_settings' ) {

			wp_enqueue_script( $this->plugin_slug . '-settings', plugins_url( '/assets/js/settings' . $suffix . '.js', __FILE__ ), array(), $this->plugin_version, false );
			wp_localize_script(
				$this->plugin_slug . '-settings',
				'fp5_logo',
				array(
					'title'  => __( 'Upload or choose a logo', $this->plugin_slug ), // This will be used as the default title
					'button' => __( 'Insert Logo', $this->plugin_slug )              // This will be used as the default button text
				)
			);
			wp_localize_script(
				$this->plugin_slug . '-settings',
				'fp5_asf_js',
				array(
					'title'  => __( 'Upload or choose a JS file', $this->plugin_slug ), // This will be used as the default title
					'button' => __( 'Insert JS file', $this->plugin_slug )              // This will be used as the default button text
				)
			);
			wp_localize_script(
				$this->plugin_slug . '-settings',
				'fp5_vast_js',
				array(
					'title'  => __( 'Upload or choose a JS file', $this->plugin_slug ), // This will be used as the default title
					'button' => __( 'Insert JS file', $this->plugin_slug )              // This will be used as the default button text
				)
			);

			wp_enqueue_media();

		}

		// Only run on new and edit post screens
		if ( $screen->base == 'post' ) {
			wp_enqueue_script( 'jquery-colorbox', plugins_url( '/assets/jquery-colorbox/jquery.colorbox' . $suffix . '.js', __FILE__ ), 'jquery', '1.6.3', false );
		}

		// Only run on new and edit post screens
		if ( 'edit-playlist' === $screen->id ) {
			wp_enqueue_script( 'jquery-ui-sortable' );
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
		include_once dirname( __FILE__ ) . '/views/display-settings.php';
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
	 * Plugin row meta links
	 *
	 * @since 1.6.0
	 */
	public function add_plugin_row_meta( $input, $file ) {

		$plugin_basename = plugin_basename( plugin_dir_path( FP5_PLUGIN_FILE ) . 'flowplayer.php' );

		if ( $plugin_basename == $file ) {
			$input = array_merge(
				$input,
				array(
					'<a href="https://wordpress.org/plugins/flowplayer5/faq/">' . esc_html__( 'FAQ', $this->plugin_slug ) . '</a>',
					'<a href="https://wordpress.org/support/plugin/flowplayer5">' . esc_html__( 'Support', $this->plugin_slug ) . '</a>',
					'<a href="https://wordpress.org/support/view/plugin-reviews/flowplayer5?filter=5">' . esc_html__( 'Rate Plugin', $this->plugin_slug ) . '</a>',
				)
			);
		}

		return $input;

	}

	/**
	 * Edit custom post type messages.
	 *
	 * @since    1.0.0
	 */
	public function set_messages( $messages ) {

		global $post;
		
		/* translators: Publish box date format, see http://php.net/date */
		$scheduled_date = date_i18n( __( 'M j, Y @ H:i', $this->plugin_slug ), strtotime( $post->post_date ) );
		$shortcode_preview = sprintf( __( 'Shortcode: %1$s', $this->plugin_slug ), '[flowplayer id="' . get_the_ID() . '"]' );

		$video_messages = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Video updated.', $this->plugin_slug ) . ' ' . $shortcode_preview,
			2  => __( 'Custom field updated.', $this->plugin_slug ),
			3  => __( 'Custom field deleted.', $this->plugin_slug ),
			4  => __( 'Video updated.', $this->plugin_slug ) . ' ' . $shortcode_preview,
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Video restored to revision from %s', $this->plugin_slug ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Video published.', $this->plugin_slug ) . ' ' . $shortcode_preview,
			7  => __( 'Video saved.', $this->plugin_slug ) . ' ' . $shortcode_preview,
			8  => __( 'Video submitted.', $this->plugin_slug ) . ' ' . $shortcode_preview,
			9  => sprintf( __( 'Video scheduled for: %1$s', $this->plugin_slug ), '<strong>' . $scheduled_date . '</strong>' ) . ' ' . $shortcode_preview,
			10 => __( 'Video draft updated.', $this->plugin_slug ) . ' ' . $shortcode_preview,
		);

		$messages[ $this->plugin_slug ] = apply_filters( 'fp5_filter_set_messages', $video_messages );

		return $messages;

	}

	/**
	 * Add a column for the shortcodes.
	 *
	 * @since    1.1.0
	 */
	public function shortcode_column( $columns ){

		$shortcode = array(
			'shortcode' => __( 'Shortcode', $this->plugin_slug ),
		);

		$columns = array_slice( $columns, 0, 4, true ) + $shortcode + array_slice( $columns, 4, NULL, true );

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
				echo '[flowplayer id="' . absint( $post_id ) . '"]';
				break;

		}

	}

	/**
	 * Add videos to "At a Glance" dashboard widget in WP +3.8
	 *
	 * @since    1.6.0
	 */
	public function add_dashboard_counts() {
		$glancer = new Gamajo_Dashboard_Glancer;
		$glancer->add( 'flowplayer5' );
	}

	/**
	 * Add videos to "Right Now" dashboard widget in WP -3.7
	 *
	 * @since    1.6.0
	 */
	public function add_dashboard_counts_old() {
		$glancer = new Gamajo_Dashboard_RightNow;
		$glancer->add( 'flowplayer5' );
	}

	/**
	 * When the post is saved or updated delete the playlist query cache.
	 *
	 * @param    int     $post_id    Post ID
	 * @param    WP_Post $post       Post object
	 * @param    bool    $update     Whether this is an existing post being updated or not
	 * @since    1.13.0
	 */
	public function delete_cache_post( $post_id, $post, $update ) {
		$playlists = get_the_terms( $post_id, 'playlist' );
		if ( $playlists ){
			foreach ( $playlists as $playlist ) {
				wp_cache_delete( 'playlist_query_' . $playlist->term_id );
			}
		}
	}

	/**
	 * When the taxonomy is saved or updated, delete the playlist query cache.
	 *
	 * @param    int     $term_id   Term ID
	 * @param    int     $tt_id     Term taxonmy ID
	 * @since    1.13.0
	 */
	public function delete_cache_tax( $term_id, $tt_id ) {
		wp_cache_delete( 'playlist_query_' . $term_id );
	}

}
