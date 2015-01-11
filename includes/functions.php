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
				return fp5_has_shortcode_arg( $shortcode[5], $tag );
			}
		}
	}
	return false;
}

/**
 * Enqueue public-facing stylesheets & JavaScript files.
 *
 * @since    1.0.0
 */
function fp5_enqueue_scripts_styles( $video_id = '', $playlist_id = '' ) {

	// Pull options
	$options   = get_option( 'fp5_settings_general' );
	$asf_js    = ( ! empty ( $options['asf_js'] ) ? $options['asf_js'] : false );
	$asf_css   = ( ! empty ( $options['asf_css'] ) ? $options['asf_css'] : false );
	$qualities = isset( $video_id ) ? get_post_meta( $video_id, 'fp5-qualities', true ) : false;

	// Enqueue stylesheets
	wp_enqueue_style( 'flowplayer5-skins' );
	wp_enqueue_style( 'flowplayer5-logo-origin' );
	if ( $asf_css ) {
		wp_enqueue_style( 'flowplayer5-asf' );
	}

	// Enqueue scripts
	wp_enqueue_script( 'flowplayer5-script' );
	if ( $asf_js ) {
		wp_enqueue_script( 'flowplayer5-asf' );
	}
	if ( $qualities ) {
		wp_enqueue_script( 'flowplayer5-quality-selector' );
	}
}
