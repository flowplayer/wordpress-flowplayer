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
 * Flowplayer5 Widget Class
 *
 * @package Flowplayer5
 * @author  Ulrich Pogson <ulrich@pogson.ch>
 *
 * @since 1.4.0
 */
class Flowplayer5_Widget extends WP_Widget {

	/**
	 * Initialize the flowplayer5 widget.
	 *
	 * @since    1.4.0
	 */
	public function __construct() {

		$plugin = Flowplayer5::get_instance();
		// Call $plugin_slug from public plugin class.
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Default widget option values.
		$this->defaults = array(
			'id' => '',
		);

		$widget_ops = array(
			'classname'   => 'flowplayer5-video-widget',
			'description' => __( 'Display your Flowplayer Videos in a Widget.', $this->plugin_slug ),
		);

		parent::__construct(
			'flowplayer5-video-widget',
			__( 'Flowplayer Video Widget', $this->plugin_slug ),
			$widget_ops
		);

	}

	/**
	 * Admin side Widget form.
	 *
	 * @since    1.4.0
	 *
	 * @param array $instance
	 */
	public function form( $instance ) {

		// Merge with defaults.
		$instance = wp_parse_args(
			( array ) $instance,
			$this->defaults
		);

		$id = isset( $instance['id'] ) ? absint( $instance['id'] ) : '';

		// WP_Query arguments.
		$args = array(
			'post_type'              => 'flowplayer5',
			'posts_per_page'         => 100,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'no_found_rows'          => true,
		);

		// The Query.
		$query = new WP_Query( $args );
		$posts = $query->posts;

		$html = '<p><label for="' . $this->get_field_id( 'id' ) . '">' . __( 'Choose Video:', $this->plugin_slug ) . '</label>';
		$html .= '<select class="widefat" name="' . $this->get_field_name( 'id' ) . '" id="' . $this->get_field_id( 'id' ) . '">';
		foreach ( $posts as $post ) {
			$html .= '<option value="' . esc_attr( $post->ID ) . '"' . selected( $post->ID, $id, false ) . '>' . esc_attr( $post->post_title ) . '</option>';
		}
		$html .= '</select></p>';

		echo $html;

	}

	/**
	 * Update widget settings.
	 *
	 * @since    1.4.0
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['id'] = absint( $new_instance['id'] );

		return $instance;

	}

	/**
	 * Display widget frontend.
	 *
	 * @since    1.4.0
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		$id    = $instance['id'];
		$title = get_the_title( $id );

		echo $args['before_widget'];
		echo '<h3 class="widget-title">' . esc_attr( $title ) . '</h3>';
		echo fp5_video_output( $instance );
		echo $args['after_widget'];

	}
}

/**
 * Register the Flowplayer5 widget on startup.
 *
 * Calls 'widgets_init' action after the Flowplayer5 widget have been
 * registered.
 *
 * @since 1.4.0
 */
function fp5_widgets_init() {

	register_widget( 'Flowplayer5_Widget' );

}
add_action( 'widgets_init', 'fp5_widgets_init' );
