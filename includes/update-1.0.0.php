<?php
/**
 * Update Flowplayer5 to 1.0.0
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

// Get entire array
$plugin_options = get_option( 'fp5_options' );

$new_options = array();

// Update keys
if ( isset( $plugin_options['ga_accountId'] ) )
	$new_options['ga_account_id'] = $plugin_options['ga_accountId'];
if ( isset( $plugin_options['key'] ) )
	$new_options['key'] = $plugin_options['key'];
if ( isset( $plugin_options['logo'] ) )
	$new_options['logo'] = $plugin_options['logo'];
if ( isset( $plugin_options['logoInOrigin'] ) )
	$new_options['logo_origin'] = $plugin_options['logoInOrigin'];

// Update entire array
update_option( 'fp5_settings_general', $new_options );
// Delete old array
delete_option( 'fp5_options' );