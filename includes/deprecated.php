<?php
/**
 * Deprecated functions & hooks
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
 * Add notice when deprictaed filter is used
 *
 * @since 1.11.0
 */
function fp5_deprecated_hook_admin_notice(){
	if ( has_filter( 'fp5_filter_flowplayer_data' ) && is_admin() || defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		$message = __( 'The filter <code>fp5_filter_flowplayer_data</code> is being used. The filter is now deprecated and will be removed in a future update. Please use <code>fp5_js_brand_config</code> instead.', 'flowplayer5' );
		require_once( plugin_dir_path( FP5_PLUGIN_FILE ) . 'admin/includes/functions.php' );
		echo caldera_warnings_dismissible_notice( $message, false, 'manage_options', 'fp5_filter_flowplayer_data' );
	}

	if ( has_filter( 'fp5_video_config' ) && is_admin() || defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		$message = __( 'The action <code>fp5_video_config</code> is being used. The action has been replaced by the filter <code>fp5_js_config</code>.', 'flowplayer5' );
		require_once( plugin_dir_path( FP5_PLUGIN_FILE ) . 'admin/includes/functions.php' );
		echo caldera_warnings_dismissible_notice( $message, false, 'manage_options', 'fp5_video_config' );
	}
}
add_action( 'admin_notices', 'fp5_deprecated_hook_admin_notice' );

/**
 * Define text domain for dismiss notice
 *
 * @since 1.11.0
 */
function fp5_caldera_wdn_text_domain() {
	return 'flowplayer5';
}
add_filter( 'caldera_wdn_text_domain', 'fp5_caldera_wdn_text_domain' );

/**
 * Wrapper for the filter fp5_filter_flowplayer_data
 *
 * @since 1.11.0
 */
function fp5_deprecated_flowplayer_data( $data_config ) {
	return apply_filters( 'fp5_filter_flowplayer_data', $data_config );
}
