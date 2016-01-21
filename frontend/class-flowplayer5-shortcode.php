<?php
/**
 * Flowplayer 5 for WordPress
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
		add_shortcode( 'flowplayer', array( $this, 'shortcode_video_output' ) );

	}

	/**
	 * Flowplayer Video HTML Output
	 *
	 * Retrieves a media files and settings to display a video.
	 *
	 * @since    1.3.0
	 *
	 * @param    array $atts Shortcode attributes.
	 */
	public function shortcode_video_output( $atts ) {
		return fp5_video_output( $atts );
	}

	public function has_flowplayer_shortcode() {
		if ( is_404() || isset( $this->has_flowplayer_shortcode ) ) {
			return;
		}

		$post           = get_queried_object();
		$has_shortcode  = array();
		$shortcode_args = array();


		if ( isset( $post->post_content ) ) {
			$shortcode_args = fp5_has_shortcode_arg( $post->post_content, 'flowplayer' );
			if ( is_array( $shortcode_args ) ) {
				foreach ( $shortcode_args as $key => $value ) {
					if ( isset( $value['id'] ) ) {
						$has_shortcode[ 'id' . $value['id'] ] = $value['id'];
					} elseif ( isset( $value['playlist'] ) ) {
						$has_shortcode[ 'playlist' . $value['playlist'] ] = $value['playlist'];
					} elseif ( ! empty( $value['mp4'] ) || ! empty( $value['webm'] ) || ! empty( $value['ogg'] ) || ! empty( $value['flash'] ) || ! empty( $value['hls'] ) ) {
						$video_id = substr( md5( serialize( $value ) ), 0, 5 );
						$has_shortcode[ 'video' . $video_id ] = $video_id;
					}
				}
			}
		} else {
			global $wp_query;
			foreach ( $wp_query->posts as $post ) {
				$post_content = isset( $post->post_content ) ? $post->post_content : '';
				$shortcode_args = fp5_has_shortcode_arg( $post_content, 'flowplayer' );
				if ( ! $shortcode_args ) {
					continue;
				}
				foreach ( $shortcode_args as $key => $value ) {
					if ( isset( $value['id'] ) ) {
						$has_shortcode[ 'id' . $value['id'] ] = $value['id'];
					} elseif ( isset( $value['playlist'] ) ) {
						$has_shortcode[ 'playlist' . $value['playlist'] ] = $value['playlist'];
					} elseif ( ! empty( $value['mp4'] ) || ! empty( $value['webm'] ) || ! empty( $value['ogg'] ) || ! empty( $value['flash'] ) || ! empty( $value['hls'] ) ) {
						$video_id = substr( md5( serialize( $value ) ), 0, 5 );
						$has_shortcode[ 'video' . $video_id ] = $video_id;
					}
				}
			}
		}

		$this->has_flowplayer_shortcode = array_filter( $has_shortcode );
		return $this->has_flowplayer_shortcode;
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

	public function video_qualities() {

		if ( isset( $this->video_qualities ) ){
			return;
		}

		$post_id   = '';
		$qualities = array();

		// Check if the post is a flowplayer video
		if ( 'flowplayer5' == get_post_type() && isset ( $post->ID ) ) {
			$post_id = $post->ID;
			$qualities[] = get_post_meta( $post_id, 'fp5-qualities', true );
			$this->video_qualities = $qualities;
			return;
		}

		$has_flowplayer_shortcode = $this->has_flowplayer_shortcode();
		if ( empty( $has_flowplayer_shortcode) ) {
			return;
		}

		$this->video_qualities = apply_filters( 'fp5_filter_video_qualities', $this->get_video_meta_values( 'fp5-qualities', $has_flowplayer_shortcode ) );
	}

	public function get_video_meta( $video_ids ) {
		if ( isset( $this->video_post_meta ) ){
			return;
		}
		if ( empty( $video_ids ) ) {
			return;
		}
		$video_meta = array();
		foreach ( $video_ids as $key => $value ) {
			// Check that it is a single video and not a playlist
			if ( 'id' . $value === $key ) {
				$video_meta[ $key ] = get_post_meta( $value );
				$video_meta[ $key ]['id'] = $value;
			}
			if ( 'playlist' . $value === $key ) {
				$video_meta = array_merge(
					$video_meta,
					Flowplayer5_Playlist::get_videos_meta_by_id( $value )
				);
			}
		}
		$this->video_post_meta = $video_meta;
	}

	public function get_video_meta_values( $key, $video_ids ) {
		if ( empty( $video_ids ) ) {
			return false;
		}
		$video_meta_values = array();
		$this->get_video_meta( $video_ids );
		foreach ( $this->video_post_meta as $id_key => $value ) {
			// Check that it is a single video and not a playlist
			if ( 'id' . $value['id'] === $id_key ) {
				$video_meta_values[ $id_key ] = isset( $this->video_post_meta[ $id_key ][ $key ][0] ) ? $this->video_post_meta[ $id_key ][ $key ][0] : '';
			}
		}
		return array_filter( $video_meta_values );
	}

}
