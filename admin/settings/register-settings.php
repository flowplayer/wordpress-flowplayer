<?php
/**
 * Register Settings
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
 * Registers all of the required Flowplayer 5 settings.
 *
 * @since    1.0.0
 */
function fp5_register_settings() {

	$fp5_settings = array(
		'general' => apply_filters( 'fp5_settings_general',
			array(
				'commercial_version' => array(
					'id'   => 'commercial_version',
					'name' => '<strong>' . __('Commercial Version', 'flowplayer5') . '</strong>',
					'desc' => __( 'The commercial version removes the Flowplayer logo and allows you to use your own logo image.', 'flowplayer5' ) . ' <a href="http://flowplayer.org/pricing/">' . __( 'Purchase license', 'flowplayer5' ) . '</a>',
					'type' => 'header'
				),
				'key' => array(
					'id'   => 'key',
					'name' => __( 'License Key', 'flowplayer5' ) . ' <a href="http://flowplayer.org/docs/setup.html#commercial-configuration">?</a>',
					'desc' => __( 'Specify your License Key here.', 'flowplayer5' ),
					'type' => 'text',
					'size' => 'medium'
				),
				'logo' => array(
					'id'   => 'logo',
					'name' => __( 'Logo', 'flowplayer5' ),
					'type' => 'upload',
					'button' => __( 'Add Logo', 'flowplayer5' ),
					'size' => 'regular',
					'desc' => '',
					'preview' => 'true'
				),
				'logo_origin' => array(
					'id'   => 'logo_origin',
					'name' => __( 'Show Logo on this site', 'flowplayer5' ),
					'desc' => __( 'Show logo on this site. Uncheck to show on only externally embedded videos.', 'flowplayer5' ),
					'type' => 'checkbox'
				),
				'flowplayer_drive' => array(
					'id'   => 'flowplayer_drive',
					'name' => '<strong>' . __( 'Flowplayer Drive', 'flowplayer5' ) . '</strong> <a href="https://flowplayer.org/docs/drive.html">?</a>',
					'desc' => __( 'Flowplayer Drive is a new feature that will hosts your video in all of the formats that you need.', 'flowplayer5' ),
					'type' => 'header'
				),
				'user_name' => array(
					'id'   => 'user_name',
					'name' => __( 'Username', 'flowplayer5' ),
					'desc' => __( 'Specify your username here.', 'flowplayer5') ,
					'type' => 'text',
					'size' => 'medium'
				),
				'password' => array(
					'id'   => 'password',
					'name' => __( 'Password', 'flowplayer5' ),
					'desc' => __( 'Specify your password here.', 'flowplayer5' ),
					'type' => 'password',
					'size' => 'medium'
				),
				'video_tracking' => array(
					'id'   => 'video_tracking',
					'name' => '<strong>' . __( 'Video Tracking', 'flowplayer5' ) . '</strong> <a href="http://flowplayer.org/docs/analytics.html">?</a>',
					'desc' => __( 'You can track video traffic using Google Analytics (GA).', 'flowplayer5' ),
					'type' => 'header'
				),
				'ga_account_id' => array(
					'id'   => 'ga_account_id',
					'name' => __( 'Google Analytics account ID', 'flowplayer5' ),
					'desc' => __( 'Specify your GA account ID here.', 'flowplayer5' ),
					'type' => 'text',
					'size' => 'medium'
				),
				'cdn_options' => array(
					'id'   => 'cdn_options',
					'name' => '<strong>' . __( 'CDN Option', 'flowplayer5' ) . '</strong>',
					'desc' => __( 'By default the Flowplayer assets (JS, CSS and SWF) are served from this domain. Use this option to have the assets served from Flowplayer\'s CDN.', 'flowplayer5' ),
					'type' => 'header'
				),
				'cdn_option' => array(
					'id'   => 'cdn_option',
					'name' => __( 'CDN hosted files', 'flowplayer5' ),
					'desc' => __( 'Use Flowplayer\'s CDN', 'flowplayer5' ),
					'type' => 'checkbox'
				),
				'embed_options' => array(
					'id' => 'embed_options',
					'name' => '<strong>' . __( 'Embed Options', 'flowplayer5' ) . '</strong> <a href="http://flowplayer.org/docs/embedding.html#configuration">?</a>',
					'desc' => __( 'By default the embed feature loads the embed script and Flowplayer assets from our CDN. You can use the fields below to change the locations of these assets.', 'flowplayer5' ),
					'type' => 'header'
				),
				'library' => array(
					'id' => 'library',
					'name' => __( 'Library', 'flowplayer5'),
					'desc' => __( 'URL of the Flowplayer API library script', 'flowplayer5' ),
					'type' => 'text',
					'size' => 'regular'
				),
				'script' => array(
					'id'   => 'script',
					'name' => __( 'Script', 'flowplayer5' ),
					'desc' => __( 'URL of the embed script', 'flowplayer5' ),
					'type' => 'text',
					'size' => 'regular'
				),
				'skin' => array(
					'id'   => 'skin',
					'name' => __( 'Skin', 'flowplayer5' ),
					'desc' => __( 'URL of skin for embedding', 'flowplayer5' ),
					'type' => 'text',
					'size' => 'regular'
				),
				'swf' => array(
					'id'   => 'swf',
					'name' => __( 'SWF file', 'flowplayer5'),
					'desc' => __( 'URL of SWF file for embedding', 'flowplayer5' ),
					'type' => 'text',
					'size' => 'regular'
				),
				'asf' => array(
					'id' => 'asf',
					'name' => '<strong>' . __( 'Google AdSense', 'flowplayer5' ) . '</strong> <a href="http://flowplayer.org/asf/">?</a>',
					'desc' => __( 'Sign up for Google AdSense for Flowplayer to be able to monetize your videos ', 'flowplayer5' ). '</strong> <a href="http://flowplayer.org/asf/">' . __( 'Sign up now', 'flowplayer5' ) . '</a>',
					'type' => 'header'
				),
				'asf_js' => array(
					'id'   => 'asf_js',
					'name' => __( 'AdSense plugin js', 'flowplayer5' ),
					'type' => 'upload',
					'button' => __( 'Add js file', 'flowplayer5' ),
					'size' => 'regular',
					'desc' => __( 'Add your custom AdSense plugin javascript file', 'flowplayer5' )
				),
				'asf_test' => array(
					'id'   => 'asf_test',
					'name' => __( 'Test Mode', 'flowplayer5' ),
					'type' => 'checkbox',
					'desc' => __( 'Enable test mode', 'flowplayer5' )
				)
			)
		)
	);

	if ( false == get_option( 'fp5_settings_general' ) ) {
		add_option( 'fp5_settings_general' );
	}

	add_settings_section(
		'fp5_settings_general',
		__( 'General Settings', 'flowplayer5' ),
		'__return_false',
		'flowplayer5_settings'
	);

	foreach ( $fp5_settings['general'] as $option ) {
		add_settings_field(
			'fp5_settings_general[' . $option['id'] . ']',
			$option['name'],
			function_exists( 'fp5_' . $option['type'] . '_callback' ) ? 'fp5_' . $option['type'] . '_callback' : 'fp5_missing_callback',
			'flowplayer5_settings',
			'fp5_settings_general',
			array(
				'id'      => $option['id'],
				'desc'    => $option['desc'],
				'name'    => $option['name'],
				'section' => 'general',
				'preview' => isset( $option['preview'] ) ? $option['preview'] : null,
				'size'    => isset( $option['size'] ) ? $option['size'] : null,
				'button'  => isset( $option['button'] ) ? $option['button'] : __( 'Upload', 'flowplayer5' ),
				'options' => isset( $option['options'] ) ? $option['options'] : '',
				'std'     => isset( $option['std'] ) ? $option['std'] : ''
			)
		);
	}

	// Creates our settings in the options table
	register_setting(
		'fp5_settings_group',
		'fp5_settings_general',
		'fp5_settings_sanitize'
	);

}

add_action( 'admin_init', 'fp5_register_settings' );

/**
 * Header Callback
 *
 * Renders the header.
 *
 * @since    1.0.0
 * @param    array $args Arguments passed by the setting
 */
function fp5_header_callback( $args ) {
	echo '<p class="description">' . $args['desc'] . '</p>';
}

/**
 * Text Callback
 *
 * Renders text fields.
 *
 * @since    1.0.0
 * @param    array $args Arguments passed by the setting
 * @global   $fp5_options Array of all the fp5 Options
 */
function fp5_text_callback( $args ) {
	global $fp5_options;

	if ( isset( $fp5_options[ $args['id'] ] ) )
		$value = $fp5_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = isset( $args['size'] ) && !is_null($args['size']) ? $args['size'] : 'regular';
	$html = '<input type="text" class="' . $args['size'] . '-text" id="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" name="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';
	$html .= '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"> ' . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Password Callback
 *
 * Renders text fields.
 *
 * @since    1.2.0
 * @param    array $args Arguments passed by the setting
 * @global   $fp5_options Array of all the fp5 Options
 */
function fp5_password_callback( $args ) {
	global $fp5_options;

	// get the settings
	$options = get_option('fp5_settings_general');

	if ( isset( $fp5_options[ $args['id'] ] ) )
		$value = $fp5_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = isset( $args['size'] ) && !is_null($args['size']) ? $args['size'] : 'regular';
	$desc = empty( $options['password'] ) ? $args['desc'] : __( 'Your passord is saved. For security reasons it will not be displayed here.', 'flowplayer5' );

	$html = '<input type="password" class="' . $args['size'] . '-text" id="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" name="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"/>';
	$html .= '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"> ' . $desc . '</label>';

	echo $html;
}

/**
 * Upload Callback
 *
 * Renders upload fields.
 *
 * @since    1.0.0
 * @param    array $args Arguments passed by the setting
 * @global   $fp5_options Array of all the fp5 Options
 */
function fp5_upload_callback($args) {
	global $fp5_options;

	if ( isset( $fp5_options[ $args['id'] ] ) )
		$value = $fp5_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

	$html = '<input type="text" class="' . $args['size'] . '-text fp5_upload_field" id="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" name="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';
	$html .= '<a href="#" type="button" class="fp5_settings_upload_button button-secondary" title="' . $args['button'] . '"/>' . $args['button'] . '</a>';
	$html .= '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"> ' . $args['desc'] . '</label>';
	$html .= isset( $args['preview'] ) && !is_null( $args['preview'] ) ? '<img style="max-width: 300px; display:block" src="' . esc_attr( $value ) . '" class="fp5_settings_upload_preview"/>' : '';

	echo $html;
}

/**
 * Checkbox Callback
 *
 * Renders checkboxes.
 *
 * @since    1.0.0
 * @param    array $args Arguments passed by the setting
 * @global   $fp5_options Array of all the fp5 Options
 */
function fp5_checkbox_callback( $args ) {
	global $fp5_options;

	$checked = isset($fp5_options[$args['id']]) ? checked(1, $fp5_options[$args['id']], false) : '';
	$html = '<input type="checkbox" id="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" name="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']" value="1" ' . $checked . '/>';
	$html .= '<label for="fp5_settings_' . $args['section'] . '[' . $args['id'] . ']"> ' . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Missing Callback
 *
 * If a function is missing for settings callbacks alert the user.
 *
 * @since    1.0.0
 * @param    array $args Arguments passed by the setting
 */
function fp5_missing_callback($args) {
	printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'flowplayer5' ), $args['id'] );
}

/**
 * Settings Sanitization
 *
 * Adds a settings error (for the updated message)
 * At some point this will validate input
 *
 * @since    1.0.0
 * @param    array $input The value inputted in the field
 * @return   string $input Sanitised value
 */
function fp5_settings_sanitize( $input ) {

	// get the settings
	$options   = get_option('fp5_settings_general');
	// protect password by hashing it immediately
	if ( ! empty( $input['password'] ) ) {
		$input['password'] = sha1( $input['password'] );
	} elseif ( ! empty( $options['password'] ) ) {
		$input['password'] = $options['password'];
	}

	add_settings_error(
		'fp5-notices',
		'',
		__('Settings Updated', 'flowplayer5'),
		'updated'
	);

	return $input;
}

/**
 * Get Settings
 *
 * Retrieves all plugin settings and returns them as a combined array.
 *
 * @since    1.0.0
 * @return   array Merged array of all the EDD settings
 */
function fp5_get_settings() {
	$general_settings = is_array( get_option( 'fp5_settings_general' ) ) ? get_option( 'fp5_settings_general' ) : array();

	return array_merge( $general_settings );
}
