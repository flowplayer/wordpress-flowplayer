<?php
/**
 * Helper functions
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
 * Output video
 *
 * @since    1.9.0
 */
function fp5_video_output( $atts ) {
	$flowplayer5_output = new Flowplayer5_Output;
	return $flowplayer5_output->video_output( $atts );
}
