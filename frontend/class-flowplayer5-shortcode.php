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
 * Flowplayer Shortcode Class
 *
 * @package Flowplayer5_Shortcode
 * @author  Ulrich Pogson <ulrich@pogson.ch>
 *
 * @since 1.3.0
 */
class Flowplayer5_Shortcode {

	/**
	 * Add shortcode
	 *
	 * @since    1.3.0
	 */
	public function __construct() {

		// Register shortcode.
		add_shortcode( 'flowplayer', 'fp5_video_output' );

	}

	public function flowplayer_shortcode_atts() {
		if ( is_404() || isset( $this->shortcode_atts ) ) {
			return;
		}

		$post           = get_queried_object();
		$shortcode_atts = array();

		if ( 'flowplayer5' == get_post_type() ) {
			if ( is_single() ) {
				$shortcode_atts['id'] = $post->ID;
			}
		} elseif ( isset( $post->post_content ) ) {
			$raw_shortcode_atts = fp5_has_shortcode_arg( $post->post_content, 'flowplayer' );
			if ( is_array( $raw_shortcode_atts ) ) {
				$shortcode_atts = $this->process_shortcode_atts( $raw_shortcode_atts );
			}
		} else {
			global $wp_query;
			foreach ( $wp_query->posts as $post ) {
				$post_content = isset( $post->post_content ) ? $post->post_content : '';
				$raw_shortcode_atts = fp5_has_shortcode_arg( $post_content, 'flowplayer' );
				if ( ! $raw_shortcode_atts ) {
					continue;
				}
				$shortcode_atts = $this->process_shortcode_atts( $raw_shortcode_atts );
			}
		}
		$this->shortcode_atts = array_filter( $shortcode_atts );
		return $this->shortcode_atts;
	}

	public function has_flowplayer_shortcode() {
		if ( empty( $this->flowplayer_shortcode_atts() ) ) {
			return false;
		}
		return true;
	}

	public function has_flowplayer_video() {
		if ( isset( $this->has_flowplayer_video ) ){
			return;
		}

		$has_video = 'flowplayer5' == get_post_type() || is_active_widget( false, false, 'flowplayer5-video-widget', true );
		$has_video = apply_filters( 'fp5_filter_has_shortcode', $has_video );

		if ( ! $has_video ) {
			$has_flowplayer_shortcode = $this->has_flowplayer_shortcode();
			$has_video = ! empty ( $has_flowplayer_shortcode );
		}

		$this->has_flowplayer_video = $has_video;
	}

	public function process_shortcode_atts( $raw_shortcode_args ) {
		$shortcode_args = array();
		foreach ( $raw_shortcode_args as $key => $value ) {
			if ( ! empty( $value['id'] ) ) {
				$shortcode_args = $value;
			} elseif ( isset( $value['playlist'] ) ) {
				$shortcode_args = $value;
			} elseif ( ! empty( $value['mp4'] ) || ! empty( $value['webm'] ) || ! empty( $value['ogg'] ) || ! empty( $value['flash'] ) || ! empty( $value['hls'] ) ) {
				$video_id = substr( md5( serialize( $value ) ), 0, 5 );
				$shortcode_args       = $value;
				$shortcode_args['id'] = $video_id;
			}
		}
		return $shortcode_args;
	}

	public function get_video_qualities( $atts ) {
		return apply_filters( 'fp5_filter_video_qualities', $this->get_video_meta_value( 'qualities', $atts ) );
	}

	public function get_video_meta_value( $key, $atts ) {
		if ( empty( $atts ) ) {
			return false;
		}
		$video_meta_values = array();
		foreach ( $atts as $id_key => $value ) {
			$video_meta_values[ $id_key ] = isset( $atts[ $id_key ][ $key ] ) ? $atts[ $id_key ][ $key ] : '';
		}
		return array_filter( $video_meta_values );
	}

	public function has_feature( $key, $atts ) {
		if ( empty( $atts ) ) {
			return false;
		}
		foreach ( $atts as $id_key => $value ) {
			$video_meta_values[] = empty( $atts[ $id_key ][ $key ] ) ? false : true;
		}
		return in_array( true, $video_meta_values, true );
	}

}
