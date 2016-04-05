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

interface Flowplayer5_IPlaylist {
	public function get_videos();
}

class Flowplayer5_Playlist implements Flowplayer5_IPlaylist {
	protected $playlist_id;

	public function __construct( $playlist_id ) {
		$this->playlist_id = $playlist_id;
	}

	public function get_videos() {

		$videos = array();
		$query  = $this->get_video_posts();

		// The Loop
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$video['id']                                       = get_the_ID();
				$videos[ 'id' . $video['id'] ]                     = Flowplayer5_Parse::get_shortcode_attr( $video );
				$videos[ 'id' . $video['id'] ]['content']          = get_the_content();
				$videos[ 'id' . $video['id'] ]['playlist']         = $this->get_id();
				$videos[ 'id' . $video['id'] ]['playlist_options'] = $this->get_term_meta( $this->get_id() );
			}
		}

		wp_reset_postdata();
		return $videos;
	}

	/**
	 * Act as a helper function for one off task of getting videos for a single playlist.
	 *
	 * @param string $playlist_id Playlist ID.
	 *
	 * @return array
	 */
	public static function get_videos_by_id( $playlist_id ) {
		$playlist = new self( $playlist_id );

		return $playlist->get_videos();
	}

	/**
	 * @return string
	 */
	public function get_id() {
		return $this->playlist_id;
	}

	protected function get_video_posts() {
		$videos = wp_cache_get( 'playlist_query_' . $this->get_id() );
		if ( false === $videos ) {
			$videos = new WP_Query( $this->get_video_post_args() );
			if ( ! is_wp_error( $videos ) && $videos->have_posts() ) {
				wp_cache_set( 'playlist_query_' . $this->get_id(), $videos );
			}
		}
		return $videos;
	}

	protected function get_video_post_args() {
		return array(
			'post_type'      => 'flowplayer5',
			'post_status'    => 'publish',
			'posts_per_page' => '100',
			'no_found_rows'  => true,
			'orderby'        => 'meta_value_num',
			'meta_key'       => 'playlist_order_' . absint( $this->get_id() ),
			'tax_query'      => array(
				array(
					'taxonomy' => 'playlist',
					'field'    => 'id',
					'terms'    => absint( $this->get_id() ),
				),
			),
		);
	}

	public static function get_term_meta( $term_id, $key = '' ) {
		$term_meta = '';
		if ( function_exists('get_term_meta') ) {
			$term_meta = get_term_meta( $term_id, $key );
		}
		if ( empty( $term_meta ) ) {
			$term_meta = get_option( 'playlist_' . $term_id );
			if ( $key && isset( $term_meta[ $key ] ) ) {
				$term_meta = $term_meta[ $key ];
			}
		}
		return $term_meta;
	}

	public static function wp_get_split_term( $term_id ) {
		$term_meta = self::get_term_meta( $term_id );
		// Check if old id is being used in the shortcode
		if ( ! $term_meta && function_exists( 'wp_get_split_term' ) ) {
			$term_id = wp_get_split_term( $term_id, 'playlist' );
		}
		return $term_id;
	}
}
