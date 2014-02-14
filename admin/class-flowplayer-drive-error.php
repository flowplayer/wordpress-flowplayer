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
class Flowplayer_Drive_Error {
	public static function show( $type, $content ) {
		echo '<div class="' . sanitize_html_class( $type . '-error' ) . '"><p>' . $content . '</p></div>';
	}

	public static function showAuthenticationApiError() {
		Flowplayer_Drive_Error::show( 'api', __( 'Unable to connect to the Flowplayer Authentication API.', 'flowplayer5' ) );
	}

	public static function showAuthenticationSeedApiError() {
		Flowplayer_Drive_Error::show( 'api', __( 'Unable to connect to the Flowplayer Authentication Seed API.', 'flowplayer5' ) );
	}

	public static function showVideoApiError() {
		Flowplayer_Drive_Error::show( 'api', __( 'Unable to connect to the Flowplayer Video API.', 'flowplayer5' ) );
	}

	public static function showNewUserError() {
		Flowplayer_Drive_Error::show(
			'new-user',
			sprintf(
				__( 'You have not uploaded any videos yet. You can upload the video in <a href="%1$s">Flowplayer Designer</a>.', 'flowplayer5' ),
				esc_url( 'http://flowplayer.org/designer/' )
			)
		);
	}

	public static function showUsernamePasswordError() {
		Flowplayer_Drive_Error::show(
			'login',
			sprintf(
				__( 'You have entered an incorrect combination of username and password. Please check your username and password in the <a href="%1$s">settings</a>.', 'flowplayer5' ),
				esc_url( admin_url( 'edit.php?post_type=flowplayer5&page=flowplayer5_settings' ) )
			)
		);
	}

	public static function showLoginError() {
		Flowplayer_Drive_Error::show(
			'login',
			sprintf(
				__( 'Please <a href="%1$s">login</a> with your <a href="%2$s">Flowplayer.org</a> username and password.', 'flowplayer5' ),
				esc_url( admin_url( 'edit.php?post_type=flowplayer5&page=flowplayer5_settings' ) ),
				esc_url( 'http://flowplayer.org/' )
			)
		);
	}


}
