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

/**
 * Duplicate WordPress functions for capaability before WP 3.6
 */
if ( ! function_exists( 'has_shortcode' ) ) {
	/**
	 * Whether the passed content contains the specified shortcode
	 *
	 * @since 3.6.0
	 *
	 * @global array $shortcode_tags
	 * @param string $tag
	 * @return boolean
	 */
	function has_shortcode( $content, $tag ) {
		if ( false === strpos( $content, '[' ) ) {
			return false;
		}

		if ( shortcode_exists( $tag ) ) {
			preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
			if ( empty( $matches ) )
				return false;

			foreach ( $matches as $shortcode ) {
				if ( $tag === $shortcode[2] ) {
					return true;
				} elseif ( ! empty( $shortcode[5] ) && has_shortcode( $shortcode[5], $tag ) ) {
					return true;
				}
			}
		}
		return false;
	}
}

/**
 * Whether the passed content contains the specified shortcode and return args
 *
 * @since 1.10.0
 *
 * @param string $tag
 * @return false|array
 */
function fp5_has_shortcode_arg( $content, $tag ) {

	if ( false === strpos( $content, '[' ) ) {
		return false;
	}

	if ( shortcode_exists( $tag ) ) {
		preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );

		if ( empty( $matches ) ) {
			return false;
		}

		foreach ( $matches as $shortcode ) {
			if ( $tag === $shortcode[2] ) {
				return shortcode_parse_atts( $shortcode[3] );
			} elseif ( ! empty( $shortcode[5] ) && has_shortcode( $shortcode[5], $tag ) ) {
				return $shortcode;
			}
		}

	}
	return false;
}
