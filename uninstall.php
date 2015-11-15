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

// If uninstall not called from WordPress exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

/**
 * Delete all plugin data
 *
 * @since    1.0.0
 */
function flowplayer5_delete_data() {
	// Delete All the Custom Post Types.
	$fp5_post_types = array( 'flowplayer5' );
	foreach ( $fp5_post_types as $post_type ) {

		$items = get_posts(
			array(
				'post_type'   => $post_type,
				'numberposts' => -1,
				'fields'      => 'ids',
			)
		);

		if ( $items ) {
			foreach ( $items as $item ) {
				wp_delete_post( $item, true );
			}
		}
	}

	// Delete all the Plugin Options.
	delete_option( 'fp5_settings_general' );
}

if ( is_multisite() ) {

	global $wpdb;
	$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );

	if ( $blogs ) {
		foreach ( $blogs as $blog ) {
			switch_to_blog( $blog['blog_id'] );
			flowplayer5_delete_data();
		}
		restore_current_blog();
	}
} else {

	flowplayer5_delete_data();

}
