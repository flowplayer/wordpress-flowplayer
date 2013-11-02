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

//if uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();

/**
 * Fired when the plugin is uninstalled.
 *
 * @since    1.0.0
 */

/**
 * Delete All the Custom Post Types
 *
 * @since    1.0.0
 */
$fp5_post_types = array( 'flowplayer5' );
foreach ( $fp5_post_types as $post_type ) {

	$items = get_posts(
		array(
			'post_type'   => $post_type,
			'numberposts' => -1,
			'fields'      => 'ids'
		)
	);

	if ( $items ) {
		foreach ( $items as $item ) {
			wp_delete_post( $item, true);
		}
	}
}

/**
 * Delete all the Plugin Options
 *
 * @since    1.0.0
 */
delete_option( 'fp5_settings_general' );