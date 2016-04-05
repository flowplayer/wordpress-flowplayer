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

	public function register() {
		add_shortcode( 'flowplayer', 'fp5_video_output' );
	}

	public function video_shortcode_atts() {

		$shortcode_atts = false;
		$has_video = apply_filters( 'fp5_filter_has_shortcode', false );
		if ( is_array( $has_video ) ) {
			$shortcode_atts = $has_video;
		} elseif ( true == $has_video ) {
			$shortcode_atts[] = array();
		}

		// flowplayer CPT
		if ( 'flowplayer5' === get_post_type() && is_single() ) {
			//$shortcode_atts[]['id'] = $post->ID;
		} elseif ( is_array( $content_shortcode_atts = $this->content_shortcode_atts() ) ) {
			// Shortcode from content
			if ( is_array( $shortcode_atts ) ) {
				$shortcode_atts = array_merge( $shortcode_atts, $content_shortcode_atts );
			} else {
				$shortcode_atts = $content_shortcode_atts;
			}
		}
		// Video widget
		if ( is_active_widget( false, false, 'flowplayer5-video-widget', true ) ) {
			$widget_data = get_option( 'widget_flowplayer5-video-widget' );
			foreach( $widget_data as $single_widget ) {
				if ( empty( $single_widget ) || ! is_array( $single_widget ) ) {
					continue;
				}
				$shortcode_atts[] = $single_widget;
			}
		}
		if ( is_array( $shortcode_atts ) ) {
			return array_filter( $shortcode_atts );
		} else {
			return $shortcode_atts;
		}

	}

	public function has_flowplayer_shortcode() {
		$shortcode_atts = $this->video_shortcode_atts();
		return is_array( $shortcode_atts );
	}

	public function content_shortcode_atts() {
		if ( is_404() ) {
			return;
		}
		if ( isset( $this->has_flowplayer_video ) ) {
			return $this->has_flowplayer_video;
		}
		$shortcode_atts = array();
		$post = get_queried_object();
		if ( isset( $post->post_content ) ) {
			$shortcode_atts = $this->has_shortcode_arg( $post->post_content, 'flowplayer' );
		} else {
			global $wp_query;
			foreach ( $wp_query->posts as $post ) {
				if ( isset( $post->post_content ) ) {
					$shortcode_atts = $this->has_shortcode_arg( $post->post_content, 'flowplayer' );
					if ( ! $shortcode_atts ) {
						continue;
					}
				}
			}
		}
		$this->shortcode_atts = $shortcode_atts;
		return $this->shortcode_atts;
	}

	/**
	 * Whether the passed content contains the specified shortcode and return args
	 *
	 * @since 1.10.0
	 *
	 * @param string $content Content to search for shortcodes.
	 * @param string $tag     Shortcode tag to check.
	 * @return false|array
	 */
	public function has_shortcode_arg( $content, $tag ) {

		if ( false === strpos( $content, '[' ) ) {
			return false;
		}

		if ( shortcode_exists( $tag ) ) {
			preg_match_all( '/' . get_shortcode_regex() . '/', $content, $matches, PREG_SET_ORDER );

			if ( empty( $matches ) ) {
				return false;
			}
			$shortcode_arg = false;
			foreach ( $matches as $shortcode ) {
				if ( $tag === $shortcode[2] ) {
					$shortcode_arg[] = shortcode_parse_atts( $shortcode[3] );
				} elseif ( ! empty( $shortcode[5] ) && has_shortcode( $shortcode[5], $tag ) ) {
					$shortcode_arg = $this->has_shortcode_arg( $shortcode[5], $tag );
				}
			}
			return $shortcode_arg;
		}
		return false;
	}

	public function get_video_qualities( $atts ) {
		// Use `fp5_filter_has_shortcode` instead and define the video id.
		return apply_filters( 'fp5_filter_video_qualities', $this->get_attr_value( 'qualities', $atts ) );
	}

	public function get_attr_value( $key, $atts ) {
		if ( empty( $atts ) ) {
			return false;
		}
		$video_meta_values = array();
		foreach ( $atts as $id_key => $value ) {
			if ( is_array( $value ) && isset( $value[ $key ] ) ) {
				$video_meta_values[ $id_key ] = $value[ $key ];
			} elseif ( isset( $atts[ $id_key ][ $key ] ) ) {
				$video_meta_values[ $id_key ] = $atts[ $id_key ][ $key ];
			}
		}
		return array_filter( $video_meta_values );
	}

}
