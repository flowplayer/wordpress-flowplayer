<?php
/**
 * Flowplayer 5 for WordPress
 *
 * @package   Flowplayer_Drive
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
 * Flowplayer Drive Class
 *
 * @package Flowplayer_Drive
 * @author  Ulrich Pogson <ulrich@pogson.ch>
 */
class Flowplayer_Drive {

	/**
	 * Flowplayer Account API URL
	 *
	 * @since    1.2.0
	 *
	 * @var      string
	 */
	protected $account_api_url = 'http://account.api.flowplayer.org/auth?_format=json';

	/**
	 * Flowplayer Video API URL
	 *
	 * @since    1.2.0
	 *
	 * @var      string
	 */
	protected $video_api_url = 'http://videos.api.flowplayer.org/account';

	/**
	 * Initialize Flowplayer Drive
	 *
	 * @since    1.2.0
	 */
	public function run() {
		$plugin = Flowplayer5::get_instance();
		// Call $plugin_version from public plugin class.
		$this->plugin_version = $plugin->get_plugin_version();
		// Add content to footer bottom
		add_action( 'admin_footer', array( $this, 'fp5_drive_content' ) );
	}

	/**
	 * Fetch Flowplayer Drive API authentication seed
	 *
	 * @since    1.2.0
	 */
	protected function make_auth_seed_request() {

		$args = array(
			'user-agent' => 'wp_flowplayer5/version_' . $this->plugin_version,
		);

		$response = wp_remote_get( esc_url_raw( $this->account_api_url ), $args );

		if ( 200 != wp_remote_retrieve_response_code( $response ) ) {
			Flowplayer_Drive_Error::showAuthenticationSeedApiError();
			return;
		}

		$seed = $this->json_decode_body( $response );

		return $seed->result;
	}

	/**
	 * Fetch Flowplayer Drive API authentication code
	 *
	 * @since    1.2.0
	 */
	protected function make_auth_request() {

		// get the login info
		$options   = fp5_get_settings();
		$user_name = ( isset( $options['user_name'] ) ) ? $options['user_name'] : '';
		$password  = ( isset( $options['password'] ) ) ? $options['password'] : '';

		if ( ! $user_name || ! $password ) {
			Flowplayer_Drive_Error::showLoginError();
			return;
		}

		$seed = $this->make_auth_seed_request();

		$auth_api_url = add_query_arg(
			array(
				'callback' => '?',
				'username' => $user_name,
				'hash'     => sha1( $user_name . $seed . $password ),
				'seed'     => $seed,
			),
			$this->account_api_url
		);

		$args = array(
			'user-agent' => 'wp_flowplayer5/version_' . $this->plugin_version,
		);

		$response = wp_remote_get( esc_url_raw( $auth_api_url ), $args );

		if ( 200 != wp_remote_retrieve_response_code( $response ) ) {
			Flowplayer_Drive_Error::showAuthenticationApiError();
			return;
		}

		$auth = $this->json_decode_body( $response );

		if ( ! $auth->success ) {
			Flowplayer_Drive_Error::showUsernamePasswordError();
			return;
		}

		return $auth->result->authcode;
	}

	/**
	 * Fetch Flowplayer Drive Videos
	 *
	 * @since    1.2.0
	 */
	protected function make_video_request() {

		$authcode = $this->make_auth_request();

		$query_args = array(
			'videos'   => 'true',
			'authcode' => $authcode,
		);

		$verified_video_api_url = add_query_arg( $query_args, $this->video_api_url );

		$args = array(
			'user-agent' => 'wp_flowplayer5/version_' . $this->plugin_version,
		);

		$response = wp_remote_get( esc_url_raw( $verified_video_api_url ), $args );

		$json = $this->json_decode_body( $response );

		if ( 200 == wp_remote_retrieve_response_code( $response ) ) {
			return $json->account;
		}

		if ( 'Cannot find account' == $json ) {
			Flowplayer_Drive_Error::showNewUserError();
			return;
		}

		if ( 'authcode missing' != $json ) {
			Flowplayer_Drive_Error::showVideoApiError();
			return;
		}
	}

	/**
	 * Structure Flowplayer Drive Videos
	 *
	 * @since    1.2.0
	 */
	public function get_videos() {

		$videos_cache = get_transient( 'flowplayer_drive_videos_cache' );

		if ( false !== $videos_cache ) {
			return $videos_cache;
		}

		$json = $this->make_video_request();

		if ( empty( $json ) ) {
			return;
		}

		$json_videos = $json->videos;

		$rtmp = isset( $json->rtmpUrl ) ? $json->rtmpUrl : '';

		foreach ( $json_videos as $video ) {

			$qualities = array();
			$hls       = '';

			foreach ( $video->encodings as $encoding ) {

				if ( 'done' !== $encoding->status || 'null' === $encoding->url ) {
					continue;
				}

				if ( 'mp4' === $encoding->format && 1 < $video->hlsResolutions ) {
					$qualities[] = $encoding->height . 'p';
				}

				// 'example-video-216p.mp4' - '-216p' / Only fetch default url
				if ( strpos( $encoding->filename, ( '-' . $encoding->height . 'p' ) ) ) {
					continue;
				}

				switch ( $encoding->format ) {
					case 'webm':
						$webm   = str_replace( 'http://', '//', $encoding->url );
						$height = $encoding->height;
						$width  = $encoding->width;
						break;
					case 'mp4':
						$mp4    = str_replace( 'http://', '//', $encoding->url );
						$flash  = $encoding->filename;
						$height = $encoding->height;
						$width  = $encoding->width;
						break;
					case 'hls':
						$hls = str_replace( 'http://', '//', $encoding->url );
						break;
				}

				if ( in_array( $encoding->format, array( 'mp4', 'webm' ) ) ) {
					$duration = gmdate( 'H:i:s', $encoding->duration );
				}
			}

			$videos[] = array(
				'id'             => $video->id,
				'title'          => $video->title,
				'userId'         => $video->userId,
				'rtmp'           => $rtmp,
				'hlsResolutions' => $video->hlsResolutions,
				'webm'           => $webm,
				'mp4'            => $mp4,
				'hls'            => $hls,
				'flash'          => 'mp4:' . $video->userId . '/' . $flash,
				'snapshotUrl'    => str_replace( 'https://', '//', $video->snapshotUrl ),
				'thumbnailUrl'   => str_replace( 'https://', '//', $video->thumbnailUrl ),
				'width'          => $width,
				'height'         => $height,
				'duration'       => $duration,
				'quality'        => $height . 'p',
				'qualities'      => $qualities,
			);

		}

		set_transient( 'flowplayer_drive_videos_cache', $videos, 15 * MINUTE_IN_SECONDS );

		return $videos;

	}

	public function get_video_html() {

		$videos = $this->get_videos();

		if ( ! $videos ) {
			return;
		}

		foreach ( $videos as $video ) {
			$multi_res = '<span class="dashicons"></span>';

			if ( 1 < $video['hlsResolutions'] ) {
				$multi_res = '<span class="dashicons dashicons-desktop"></span><span class="dashicons dashicons-tablet"></span><span class="dashicons dashicons-smartphone"></span>';
			}

			$return = '<div class="video">';
				$return .= '<a href="#" class="choose-video" data-rtmp="' . esc_attr( $video['rtmp'] ) . '" data-user-id="' . esc_attr( $video['userId'] ) . '" data-video-id="' . esc_attr( $video['id'] ) . '" data-video-name="' . esc_html( $video['title'] ) . '" data-webm="' . esc_url( $video['webm'] ) .'" data-mp4="' . esc_url( $video['mp4'] ) . '" data-hls="' . esc_attr( $video['hls'] ) . '" data-flash="' . esc_attr( $video['flash'] ) . '" data-img="' . esc_url( $video['snapshotUrl'] ) . '" data-qualities="' . esc_attr( implode( ',', $video['qualities'] ) ) . '" data-default-quality="' . esc_attr( $video['quality'] ) . '">';
					$return .= '<h2 class="video-title">' . esc_html( $video['title'] ) . '</h2>';
					$return .= '<div class="thumb" style="background-image: url(' . esc_url( $video['thumbnailUrl'] ) . ');">';
						$return .= '<div class="bar">';
							$return .= $multi_res;
							$return .= '<span class="duration">' . esc_attr( $video['duration'] ) . '</span>';
						$return .= '</div>';
					$return .= '</div>';
				$return .= '</a>';
			$return .= '</div>';

			echo $return;
		}

	}

	/**
	 * Content for Flowplayer Drive colorbox modal
	 *
	 * @since    1.2.0
	 */
	public function fp5_drive_content() {

		$screen = get_current_screen();

		// Only run in post/page creation and edit screens
		if ( 'post' != $screen->base || 'flowplayer5' != $screen->post_type  ) {
			return;
		}
		// @todo Use a <button> or two, not links with # hrefs.
		?>
		<div style="display: none;">
			<div id="flowplayer-drive">
				<?php $this->get_video_html(); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Retrieve body from json
	 *
	 * @since    1.7.0
	 */
	public function json_decode_body( $response ) {
		$body = wp_remote_retrieve_body( $response );

		return json_decode( $body );
	}

}
