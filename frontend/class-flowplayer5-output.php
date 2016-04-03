<?php
/**
 * Flowplayer 5 for WordPress
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

/**
 * Flowplayer Output Class
 *
 * @package Flowplayer5_Shortcode
 * @author  Ulrich Pogson <ulrich@pogson.ch>
 *
 * @since 1.3.0
 */
class Flowplayer5_Render {

	/**
	 * Create Flowplayer Video HTML Output
	 *
	 * Retrieves a media files and settings to display a video.
	 *
	 * @since    1.3.0
	 *
	 * @param    array $atts Shortcode attributes.
	 *
	 * @return array string Player markup.
	 */
	public function player_render( $atts ) {
		if ( isset( $atts['playlist'] ) ) {
			$playlist_id = Flowplayer5_Playlist::wp_get_split_term( $atts['playlist'] );
			$atts = Flowplayer5_Playlist::get_videos_by_id( $playlist_id );
			$atts = Flowplayer5_Parse::playlist_single_video_config( $atts );
			$player = self::playlist_render( $atts );
		} else {
			$atts = Flowplayer5_Parse::get_shortcode_attr( $atts );
			$new_atts[ 'id' . $atts['id'] ] = Flowplayer5_Parse::single_video_config( $atts );
			$player = self::single_video_render( reset( $new_atts ) );
		}
		return $player;
	}

	/**
	 * Create Flowplayer Video HTML Output
	 *
	 * Retrieves a media files and settings to display a video.
	 *
	 * @since    1.3.0
	 *
	 * @param    array $atts Shortcode attributes
	 */
	private static function single_video_render( $atts ) {
		if ( ! $atts ) {
			return;
		}
		ob_start();
		if ( $atts['lightbox'] ) {
			require( 'views/partials/lightbox.php' );
		} else {
			require( 'views/partials/video.php' );
		}
		return ob_get_clean();
	}

	/**
	 * Create Flowplayer Playlist HTML Output
	 *
	 * Retrieves a media files and settings to display a video.
	 *
	 * @since    1.3.0
	 *
	 * @param    array $atts Shortcode attributes
	 */
	private static function playlist_render( $atts ) {
		if ( ! is_array( $atts ) ) {
			return;
		}
		$first_video = reset( $atts );
		ob_start();
		require( 'views/partials/playlist.php' );
		return ob_get_clean();
	}

	private static function trim_implode( $values ) {
		return trim( implode( ' ', array_filter( $values ) ) );
	}

	private static function process_data_config( $values ) {
		if ( empty( $values ) ) {
			return;
		}
		foreach ( $values as $key => $value ) {
			$output[] = 'data-' . esc_html( $key ) . '="' . esc_html( $value ) . '" ';
		}
		return implode( '', $output );
	}

	private static function process_css_array( $values ) {
		if ( empty( $values ) ) {
			return;
		}
		foreach ( $values as $property => $value ) {
			$output[] = esc_html( $property ) . ':' . esc_html( $value ) . ';';
		}
		return implode( '', $output );
	}
	
}
