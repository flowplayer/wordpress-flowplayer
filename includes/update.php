<?php
/**
 * Update helper
 *
 * @package   Flowplayer 5 for WordPress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      https://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$fp5_options = get_option( 'fp5_options' );
$fp5_version = get_option( 'fp5_version' );
$fp5_player_version = get_option( 'fp5_player_version' );

if ( $fp5_options & ! $fp5_version ) {

	// 1.0.0 is the first version to use this option so we must add it
	$fp5_version = '1.0.0';
	$fp5_player_version = '5.4.3';

	add_option( 'fp5_version', $fp5_version );
	add_option( 'fp5_player_version', $fp5_player_version );

	include( 'update-1.0.0.php' );

}
