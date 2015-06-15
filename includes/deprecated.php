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

function fp5_deprecated_hook_admin_notice() {
	$user_ID = get_current_user_id();
	$user_meta = get_user_meta( $user_ID, 'fp5_notices' );
	if ( has_filter( 'fp5_filter_flowplayer_data' ) && ! isset( $user_meta['fp5_filter_flowplayer_data'] ) ) { ?>
		<div id="message" class="updated notice is-dismissible">
			<p><?php _e( 'The filter <code>fp5_filter_flowplayer_data</code> is being used. The filter is now deprecated and will be removed in a future update. Please use <code>fp5_js_brand_config</code> instead.', 'my-text-domain' ); ?></p>
		</div>
	<?php }

}
add_action( 'admin_notices', 'fp5_deprecated_hook_admin_notice' );

function fp5_deprecated_flowplayer_data( $data_config ) {
	return apply_filters( 'fp5_filter_flowplayer_data', $data_config );
}
