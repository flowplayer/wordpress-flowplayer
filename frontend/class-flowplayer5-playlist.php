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
				$video[ 'id' ]                                  = get_the_ID();
				$videos[ 'id' . $video[ 'id' ] ]                = Flowplayer5_Output::single_video_processing( $video );
				$videos[ 'id' . $video[ 'id' ] ][ 'content' ]   = get_the_content();
			}
		}

		wp_reset_postdata();

		return $videos;
	}

	public function get_videos_meta() {

		$videos = array();
		$query  = $this->get_video_posts();

		// The Loop
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$video_id                         = get_the_ID();
				$videos[ 'id' . $video_id ]       = get_post_custom();
				$videos[ 'id' . $video_id ]['id'] = $video_id;
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
	 * Act as a helper function for one off task of getting videos for a single playlist.
	 *
	 * @param string $playlist_id Playlist ID.
	 *
	 * @return array
	 */
	public static function get_videos_meta_by_id( $playlist_id ) {
		$playlist = new self( $playlist_id );

		return $playlist->get_videos_meta();
	}

	/**
	 * @return string
	 */
	public function get_id() {
		return $this->playlist_id;
	}

	protected function get_video_posts() {
		$videos = wp_cache_get( 'playlist_query_' . $this->get_id() );
		if ( $videos ) {
			return $videos;
		}
		$videos = new WP_Query( $this->get_video_post_args() );
		wp_cache_set( 'playlist_query_' . $this->get_id(), $videos );
		return $videos;
	}

	protected function get_video_post_args() {
		return array(
			'post_type'      => 'flowplayer5',
			'post_status'    => 'publish',
			'orderby'        => 'meta_value_num',
			'posts_per_page' => '-1',
			'meta_key'       => 'playlist_order_' . absint( $this->playlist_id ),
			'tax_query'      => array(
				array(
					'taxonomy' => 'playlist',
					'field'    => 'id',
					'terms'    => absint( $this->playlist_id ),
				),
			),
			'cache_results'          => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => true,
		);
	}
}
