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
	private $account_api_url = 'http://account.api.flowplayer.org/auth?_format=json';

	/**
	 * Flowplayer Video API URL
	 *
	 * @since    1.2.0
	 *
	 * @var      string
	 */
	private $video_api_url = 'http://videos.api.flowplayer.org/account';

	/**
	 * Instance of this class.
	 *
	 * @since    1.2.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize Flowplayer Drive
	 *
	 * @since    1.2.0
	 */
	private function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 *
		 */
		$plugin = Flowplayer5::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Add content to footer bottom
		add_action( 'admin_footer', array( $this, 'fp5_drive_content' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since    1.2.0
	 *
	 * @return   object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fetch Flowplayer Drive API authentication seed
	 *
	 * @since    1.2.0
	 */
	private function make_auth_seed_request() {

		$response_account = wp_remote_get( esc_url_raw( $this->account_api_url ) );

		if ( wp_remote_retrieve_response_code( $response_account ) == 200 ) {

			$body_account = wp_remote_retrieve_body( $response_account );

			$json = json_decode( $body_account );

			return $json->result;

		} else {

			echo '<div class="api-error"><p>' . __( 'Unable to connect to the Flowplayer Authentication Seed API.', 'flowplayer5' ) . '</p></div>';

		}

	}

	/**
	 * Fetch Flowplayer Drive API authentication code
	 *
	 * @since    1.2.0
	 */
	private function make_auth_request() {

		// get the login info
		$options   = get_option('fp5_settings_general');
		$user_name = ( isset( $options['user_name'] ) ) ? $options['user_name'] : '';
		$password  = ( isset( $options['password'] ) ) ? $options['password'] : '';

		if ( $user_name == '' || $password == '' ) {

			$return = '<div class="login-error"><p>';
			$return .= sprintf(
				__( 'Please <a href="%1$s">login</a> with your <a href="%2$s">Flowplayer.org</a> username and password.', $this->plugin_slug ),
				esc_url( admin_url( 'edit.php?post_type=flowplayer5&page=flowplayer5_settings' ) ),
				esc_url( 'http://flowplayer.org/' )
			);
			$return .= '</p></div>';

			echo $return;

		} else {

			$seed = $this->make_auth_seed_request();

			$auth_api_url = esc_url_raw( add_query_arg(
				array(
					'callback' => '?',
					'username' => $user_name,
					'hash'     => sha1( $user_name . $seed . $password ),
					'seed'     => $seed
				),
				esc_url_raw( $this->account_api_url )
			) );

			$response_auth = wp_remote_get( $auth_api_url );

			if ( wp_remote_retrieve_response_code( $response_auth ) == 200 ) {

				$body = wp_remote_retrieve_body( $response_auth );

				$auth = json_decode( $body );

				if ( $auth->success == true ) {

					return $auth->result->authcode;

				} else {

					$return = '<div class="login-error"><p>';
					$return .= sprintf(
						__( 'You have enter a incorrect username and/or password. Please check your username and password in the <a href="%1$s">settings</a>.', $this->plugin_slug ),
						esc_url( admin_url( 'edit.php?post_type=flowplayer5&page=flowplayer5_settings' ) )
					);
					$return .= '</p></div>';

					echo $return;

				}

			} else {

				echo '<div class="api-error"><p>' . __( 'Unable to connect to the Flowplayer Authentication API.', $this->plugin_slug ) . '</p></div>';

			}

		}

	}

	/**
	 * Fetch Flowplayer Drive Videos
	 *
	 * @since    1.2.0
	 */
	private function make_video_request() {

		$authcode = $this->make_auth_request();

		$verified_video_api_url = esc_url_raw( add_query_arg(
			array(
				'videos'   => 'true',
				'authcode' => $authcode
			),
			esc_url_raw( $this->video_api_url )
		) );

		$response_videos = wp_remote_get( $verified_video_api_url );

		$body = wp_remote_retrieve_body( $response_videos );

		$json = json_decode( $body );

		if ( wp_remote_retrieve_response_code( $response_videos ) == 200 ) {

				return $json->videos;

		} else {

			if ( $json == 'Cannot find account' ) {

				$return = '<div class="new-user-error"><p>';
				$return .= sprintf(
					__( 'You have not uploaded any videos yet. You can upload the video in <a href="%1$s">Flowplayer Designer</a>.', $this->plugin_slug ),
					esc_url( 'http://flowplayer.org/designer/' )
				);
				$return .= '</p></div>';

				echo $return;

			} elseif ( $json != 'authcode missing' ) {

				echo '<div class="api-error"><p>' . __( 'Unable to connect to the Flowplayer Video API.', $this->plugin_slug ) . '</p></div>';

			}

		}

	}

	/**
	 * Structure Flowplayer Drive Videos
	 *
	 * @since    1.2.0
	 */
	public function get_videos() {

		$json_videos = $this->make_video_request();

		if ( is_array( $json_videos ) ) {

			foreach ( $json_videos as $video ) {

				foreach ( $video->encodings as $encoding ) {
					if ( $encoding->status === 'done' & $encoding->format === 'webm' ) {
						$webm = $encoding->url;
					}
					if ( $encoding->status === 'done' & $encoding->format === 'mp4' ) {
						$mp4 = $encoding->url;
					}
					if ( $encoding->status === 'done' & $encoding->format=== 'mp4' ) {
						$duration = gmdate( "H:i:s", $encoding->duration );
					} elseif ( $encoding->status === 'done' & $encoding->format === 'webm' ) {
						$duration = gmdate( "H:i:s", $encoding->duration );
					}
				}

				$return = '<div class="video">';
					$return .= '<a href="#" class="choose-video" data-user-id="' . $video->userId .'" data-video-id="' . $video->id .'" data-webm="' . $webm .'" data-mp4="' . $mp4 .'" data-img="' . $video->snapshotUrl . '">';
						$return .= '<h2 class="video-title">' . $video->title . '</h2>';
						$return .= '<div class="thumb" style="background-image: url(' . $video->thumbnailUrl . ');">';
							$return .= '<em class="duration">' . $duration . '</em>';
						$return .= '</div>';
					$return .= '</a>';
				$return .= '</div>';

				echo $return;

			}

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
		if ( $screen->base == 'post' && $screen->post_type == 'flowplayer5' ) {
			?>
			<div style="display: none;">
				<div class="media-frame-router">
					<div class="media-router"><a href="#" class="media-menu-item"><?php __( 'Upload Videos', $this->plugin_slug ) ?></a><a href="#" class="media-menu-item active"><?php __( 'Flowplayer Drive', $this->plugin_slug ) ?></a></div>
				</div>
				<div id="flowplayer-drive">
					<?php $this->get_videos(); ?>
				</div>
			</div>
			<?php
		}
	}

}