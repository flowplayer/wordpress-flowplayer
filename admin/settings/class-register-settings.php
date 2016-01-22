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

class Flowplayer5_Settings {

	private $options;

	/**
	 * Get things started
	 *
	 * @since 2.0
	 * @return void
	*/
	public function __construct() {

		$this->options  = get_option( 'fp5_settings_general', array() );

		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_filter( 'fp5_register_settings', array( $this, 'hide_options_conditionally' ) );

	}

	/**
	 * Get all settings defaults
	 *
	 * @since 2.0
	 * @return array
	*/
	public function get_defaults() {
		$defaults = array();
		if ( empty( $this->options ) ) {
			$defaults['fp_version'] = 'fp6';
		} else {
			$defaults['fp_version'] = 'fp5';
		}

		return apply_filters( 'fp5_option_defaults', $defaults );
	}

	/**
	 * Get the value of a specific setting
	 *
	 * @since 2.0
	 * @return mixed
	*/
	public function get( $key, $default = false ) {
		if ( false === $default ) {
			$defaults = $this->get_defaults();
			$default = $defaults[ $key ];
		}
		$value = ! empty( $this->options[ $key ] ) ? $this->options[ $key ] : $default;
		return apply_filters( 'fp5_setting_' . $key, $value );
	}

	/**
	 * Get all settings
	 *
	 * @since 2.0
	 * @return array
	*/
	public function get_all() {
		$options = wp_parse_args(
			$this->options,
			$this->get_defaults()
		);
		return apply_filters( 'fp5_settings', $options );
	}

	/**
	 * Add all settings sections and fields
	 *
	 * @since 2.0
	 * @return void
	*/
	function register_settings() {

		foreach ( $this->get_registered_settings() as $tab => $settings ) {

			$title = ! empty( $settings['title'] ) ? $settings['title'] : '__return_null';
			add_settings_section(
				'fp5_settings_' . $tab,
				$title,
				array( $this, 'section_header_callback'),
				'flowplayer5_settings'
			);

			foreach ( $settings as $key => $option ) {

				if( ! is_array( $option ) ) {
					continue;
				}

				$callback = ! empty( $option['callback'] ) ? $option['callback'] : array( $this, $option['type'] . '_callback' );

				add_settings_field(
					'fp5_settings_general[' . $key . ']',
					isset( $option['name'] ) ? $option['name'] : '',
					is_callable( $callback ) ? $callback : array( $this, 'missing_callback' ),
					'flowplayer5_settings',
					'fp5_settings_' . $tab,
					array(
						'id'      => $key,
						'label_for' => '', // WP Core args
						'class'     => '', // WP Core args
						'desc'    => ! empty( $option['desc'] ) ? $option['desc'] : '',
						'name'    => isset( $option['name'] ) ? $option['name'] : null,
						'section' => $tab,
						'size'    => isset( $option['size'] ) ? $option['size'] : null,
						'button'  => isset( $option['button'] ) ? $option['button'] : __( 'Upload', 'flowplayer5' ),
						'options' => isset( $option['options'] ) ? $option['options'] : '',
						'std'     => isset( $option['std'] ) ? $option['std'] : ''
					)
				);
			}
		}

		// Creates our settings in the options table
		register_setting(
			'fp5_settings_group',
			'fp5_settings_general',
			array( $this, 'sanitize_settings' )
		);

	}

	/**
	 * Retrieve the array of plugin settings
	 *
	 * @since 2.0
	 * @return array
	*/
	function sanitize_settings( $input = array() ) {

		if ( empty( $_POST['_wp_http_referer'] ) ) {
			return $input;
		}

		parse_str( $_POST['_wp_http_referer'], $referrer );

		$saved = $this->options;
		if ( ! is_array( $saved ) ) {
			$saved = array();
		}
		$settings = $this->get_registered_settings();
		$tab      = isset( $referrer['tab'] ) ? esc_attr( $referrer['tab'] ) : 'general';

		$input = $input ? $input : array();
		$input = apply_filters( 'fp5_settings_' . $tab . '_sanitize', $input );

		// Ensure a value is always passed for every checkbox
		if ( ! empty( $settings[ $tab ] ) ) {
			foreach ( $settings[ $tab ] as $key => $setting ) {

				// Single checkbox
				if ( isset( $settings[ $tab ][ $key ]['type'] ) && 'checkbox' == $settings[ $tab ][ $key ]['type'] ) {
					$input[ $key ] = ! empty( $input[ $key ] );
				}

			}
		}

		// Loop through each setting being saved and pass it through a sanitization filter
		foreach ( $input as $key => $value ) {

			// Get the setting type (checkbox, select, etc)
			$type = isset( $settings[ $tab ][ $key ][ 'type' ] ) ? $settings[ $tab ][ $key ][ 'type' ] : false;
			$input[ $key ] = $value;

			if ( $type ) {
				// Field type specific filter
				$input[ $key ] = apply_filters( 'fp5_settings_sanitize_' . $type, $input[ $key ], $key );
			}

			// General filter
			$input[ $key ] = apply_filters( 'fp5_settings_sanitize', $input[ $key ], $key );

			if ( empty ( $input[ $key ] ) ) {
				$input[ $key ] = false;
			}
		}

		add_settings_error(
			'fp5-notices',
			'',
			__( 'Settings updated', 'flowplayer5' ),
			'updated'
		);

		return array_merge( $saved, $input );

	}

	/**
	 * Retrieve the array of plugin settings
	 *
	 * @since 2.0
	 * @return array
	*/
	function get_registered_settings() {
		$defaults = $this->get_defaults();
		$settings = array(
			'general' => array(
				'title'      => __( 'General Settings', 'flowplayer5' ),
				'fp_version' => array(
					'name'    => '<strong>' . __( 'Flowplayer Version', 'flowplayer5' ) . '</strong>',
					'desc'    => __( 'Choose Flowplayer script version', 'flowplayer5' ),
					'type'    => 'select',
					'options' => array(
						'fp5' => __( 'Version 5', 'flowplayer5' ),
						'fp6' => __( 'Version 6', 'flowplayer5' )
					),
					'std'     => $defaults['fp_version']
				),
				'cdn_option' => array(
					'name' => __( 'CDN hosted files', 'flowplayer5' ),
					'desc' => __( 'Use Flowplayer\'s CDN to host the CSS and JavaScript assets', 'flowplayer5' ),
					'type' => 'checkbox',
				),
			),
			'branding' => array(
				'title'      => __( 'Branding Settings', 'flowplayer5' ),
				'key' => array(
					'name' => __( 'License Key', 'flowplayer5' ) . ' <a href="https://flowplayer.org/docs/setup.html#commercial-configuration">?</a>',
					'desc' => __( 'Enter your license key.', 'flowplayer5' ),
					'type' => 'text',
					'size' => 'medium',
				),
				'directory' => array(
					'name' => __( 'Commercial Assests Directory', 'flowplayer5' ),
					'type' => 'text',
					'size' => 'regular',
					'desc' => __( 'Add the link to the directory with the Flowplayer Commercial assests', 'flowplayer5' ) . ' e.g. example.com/flowplayer5/'
				),
				'logo' => array(
					'name' => __( 'Logo', 'flowplayer5' ),
					'type' => 'upload',
					'button' => __( 'Add Logo', 'flowplayer5' ),
					'size' => 'regular',
					'preview' => 'true',
				),
				'logo_origin' => array(
					'desc' => __( 'Check to show the logo in videos on this site and in externally embedded videos.', 'flowplayer5' ),
					'type' => 'checkbox',
				),
				'brand_text' => array(
					'name' => __( 'Brand Text', 'flowplayer5' ),
					'type' => 'text',
					'button' => __( 'Add brand name', 'flowplayer5' ) . ' <a href="http://flowplayer.org/news/releases/html5/v.6.0.0.html">' . __( 'Flowplayer 6 feature', 'flowplayer5' ) . '</a>',
					'size' => 'regular',
					'desc' => __( 'If set, the brand name will appear in the controlbar.' ),
				),
				'text_origin' => array(
					'desc' => __( 'Check to show the title in videos on this site and in externally embedded videos.', 'flowplayer5' ) . ' <a href="http://flowplayer.org/news/releases/html5/v.6.0.0.html">' . __( 'Flowplayer 6 feature', 'flowplayer5' ) . '</a>',
					'type' => 'checkbox',
				),
			),
			'flowplayer_drive' => array(
				'title'      => __( 'Flowplayer Drive Settings', 'flowplayer5' ) . ' <a href="https://flowplayer.org/docs/drive.html">?</a>',
				'user_name' => array(
					'name' => __( 'Username', 'flowplayer5' ),
					'desc' => __( 'Enter your flowplayer.org username.', 'flowplayer5' ) ,
					'type' => 'text',
					'size' => 'medium',
				),
				'password' => array(
					'name' => __( 'Password', 'flowplayer5' ),
					'desc' => __( 'Enter your flowplayer.org password.', 'flowplayer5' ),
					'type' => 'password',
					'size' => 'medium',
				),
			),
			'video_tracking' => array(
				'title'      => __( 'Video tracking Settings', 'flowplayer5' ) . ' <a href="http://flowplayer.org/docs/analytics.html">?</a>',
				'ga_account_id' => array(
					'name' => __( 'Google Analytics account ID', 'flowplayer5' ),
					'desc' => __( 'Enter your GA account ID.', 'flowplayer5' ),
					'type' => 'text',
					'size' => 'medium',
				),
			),
			'asf' => array(
				'title'      => __( 'AdSense for Flowplayer Setting', 'flowplayer5' ) . ' <a href="http://flowplayer.org/asf/">?</a>',
				'asf_css' => array(
					'name' => __( 'AdSense plugin CSS', 'flowplayer5' ),
					'type' => 'upload',
					'button' => __( 'Add CSS file', 'flowplayer5' ),
					'size' => 'regular',
					'desc' => __( 'Add your custom AdSense plugin CSS file', 'flowplayer5' ),
				),
				'asf_js' => array(
					'name' => __( 'AdSense plugin js', 'flowplayer5' ),
					'type' => 'upload',
					'button' => __( 'Add js file', 'flowplayer5' ),
					'size' => 'regular',
					'desc' => __( 'Add your custom AdSense plugin javascript file', 'flowplayer5' ),
				),
				'asf_test' => array(
					'name' => __( 'Test Mode', 'flowplayer5' ),
					'type' => 'checkbox',
					'desc' => __( 'Enable test mode', 'flowplayer5' ),
				)
			),
			'embed_options' => array(
				'title'      => __( 'Embed asset Setting', 'flowplayer5' ) . ' <a href="https://flowplayer.org/docs/embedding.html#configuration">?</a>',
				'library' => array(
					'name' => __( 'Library', 'flowplayer5' ),
					'desc' => __( 'URL of the Flowplayer API library script', 'flowplayer5' ),
					'type' => 'text',
					'size' => 'regular',
				),
				'script' => array(
					'name' => __( 'Script', 'flowplayer5' ),
					'desc' => __( 'URL of the embed script', 'flowplayer5' ),
					'type' => 'text',
					'size' => 'regular',
				),
				'skin' => array(
					'name' => __( 'Skin', 'flowplayer5' ),
					'desc' => __( 'URL of skin for embedding', 'flowplayer5' ),
					'type' => 'text',
					'size' => 'regular',
				),
				'swf' => array(
					'name' => __( 'SWF file', 'flowplayer5' ),
					'desc' => __( 'URL of SWF file for embedding', 'flowplayer5' ),
					'type' => 'text',
					'size' => 'regular',
				),
			),
		);

		$settings['general'] = apply_filters( 'fp5_settings_general', $settings['general'] );
		return apply_filters( 'fp5_register_settings', $settings );
	}

	/**
	 * Hides options conditionally
	 *
	 * Renders the header.
	 *
	 * @since 2.0
	 * @param array $args Arguments passed by the setting
	 * @return void
	 */
	function hide_options_conditionally( $settings ) {
		$setting_values = $this->get_all();

		if ( isset( $settings['general']['fp_version'] ) && 'fp6' !== $setting_values['fp_version'] ) {
			unset( $settings['branding']['brand_text'] );
			unset( $settings['branding']['text_origin'] );
			unset( $settings['asf'] );
		}

		if ( empty( $setting_values['key'] ) ) {
			unset( $settings['branding']['directory'] );
			unset( $settings['branding']['logo'] );
			unset( $settings['branding']['logo_origin'] );
		}

		if ( isset( $setting_values['key'] ) ) {
			unset( $settings['cdn_options'] );
		}

		return $settings;
	}

	/**
	 * Header Callback
	 *
	 * Renders the header.
	 *
	 * @since 2.0
	 * @param array $args Arguments passed by the setting
	 * @return void
	 */
	function header_callback( $args ) {
		echo '<p class="description">' . $args['desc'] . '</p>';
	}

	/**
	 * Header Callback
	 *
	 * Renders the header.
	 *
	 * @since 2.0
	 * @param array $args Arguments passed by the setting
	 * @return void
	 */
	function section_header_callback( $args ) {
		$html = '';
		switch ( $args['id'] ) {
			case 'fp5_settings_branding':
				$html = '<p class="description">' . __( 'The commercial version removes the Flowplayer logo and allows you to use your own logo image.', 'flowplayer5' ) . ' <a href="http://flowplayer.org/pricing/">' . __( 'Purchase license', 'flowplayer5' ) . '</a>' . '</p>';
				break;
			case 'fp5_settings_flowplayer_drive':
				$html = '<p class="description">' . __( 'Flowplayer Drive is a new feature that will hosts your video in all of the formats that you need.', 'flowplayer5' ) . '</p>';
				break;
			case 'fp5_settings_video_tracking':
				$html = '<p class="description">' . __( 'You can track video traffic using Google Analytics (GA).', 'flowplayer5' ) . '</p>';
				break;
			case 'fp5_settings_asf':
				$html = '<p class="description">' . __( 'Sign up for Google AdSense for Flowplayer to be able to monetize your videos ', 'flowplayer5' ). '</strong> <a href="http://flowplayer.org/asf/">' . __( 'Sign up now', 'flowplayer5' ) . '</a>' . '</p>';
				break;
			case 'fp5_settings_embed_options':
				$html = '<p class="description">' . __( 'By default the embed feature loads the embed script and Flowplayer assets from our CDN. You can use the fields below to change the locations of these assets.', 'flowplayer5' ) . '</p>';
				break;
		}
		echo apply_filters( 'fp5_settings_section_callback', $html, $args );
	}

	/**
	 * Checkbox Callback
	 *
	 * Renders checkboxes.
	 *
	 * @since 2.0
	 * @param array $args Arguments passed by the setting
	 * @global $this->options Array of all the Flowplayer5 Options
	 * @return void
	 */
	function checkbox_callback( $args ) {

		$checked = isset( $this->options[ $args['id'] ] ) ? checked( 1, $this->options[ $args['id'] ], false ) : '';
		$html = '<input type="checkbox" id="fp5_settings_general[' . $args['id'] . ']" name="fp5_settings_general[' . $args['id'] . ']" value="1" ' . $checked . '/>';
		$html .= '<label for="fp5_settings_general[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

		echo $html;
	}

	/**
	 * Text Callback
	 *
	 * Renders text fields.
	 *
	 * @since 2.0
	 * @param array $args Arguments passed by the setting
	 * @global $this->options Array of all the Flowplayer5 Options
	 * @return void
	 */
	function text_callback( $args ) {

		if ( isset( $this->options[ $args['id'] ] ) ) {
			$value = $this->options[ $args['id'] ];
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}

		$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
		$html = '<input type="text" class="' . $size . '-text" id="fp5_settings_general[' . $args['id'] . ']" name="fp5_settings_general[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
		$html .= '<label for="fp5_settings_general[' . $args['id'] . ']"> '  . '<br>' . $args['desc'] . '</label>';

		echo $html;
	}

	/**
	 * Password Callback
	 *
	 * Renders password fields.
	 *
	 * @since 2.0
	 * @param array $args Arguments passed by the setting
	 * @global $this->options Array of all the Flowplayer5 Options
	 * @return void
	 */
	function password_callback( $args ) {

		$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
		$desc = empty( $this->options['password'] ) ? $args['desc'] : __( 'Your password is saved. For security reasons it will not be displayed here.', 'flowplayer5' );
		$html = '<input type="password" class="' . $size . '-text" id="fp5_settings_general[' . $args['id'] . ']" name="fp5_settings_general[' . $args['id'] . ']"/>';
		$html .= '<label for="fp5_settings_general[' . $args['id'] . ']"> '  . '<br>' . $desc . '</label>';

		echo $html;
	}

	/**
	 * Select Callback
	 *
	 * Renders select fields.
	 *
	 * @since 2.0
	 * @param array $args Arguments passed by the setting
	 * @global $this->options Array of all the Flowplayer5 Options
	 * @return void
	 */
	function select_callback( $args ) {

		if ( isset( $this->options[ $args['id'] ] ) ) {
			$value = $this->options[ $args['id'] ];
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}

		$html = '<select id="fp5_settings_general[' . $args['id'] . ']" name="fp5_settings_general[' . $args['id'] . ']"/>';

		foreach ( $args['options'] as $option => $name ) :
			$selected = selected( $option, $value, false );
			$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
		endforeach;

		$html .= '</select>';
		$html .= '<label for="fp5_settings_general[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

		echo $html;
	}

	/**
	 * Upload Callback
	 *
	 * Renders file upload fields.
	 *
	 * @since 2.0
	 * @param array $args Arguements passed by the setting
	 * @global $this->options Array of all the Flowplayer5 Options
	 * @return void
	 */
	function upload_callback( $args ) {

		if ( isset( $this->options[ $args['id'] ] ) ) {
			$value = $this->options[ $args['id'] ];
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}

		$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';

		$html = '<input type="text" class="' . $size . '-text fp5_' . $args['id'] . '_upload_field" id="fp5_settings_general[' . $args['id'] . ']" name="fp5_settings_general[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
		$html .= '<span>&nbsp;<input type="button" class="fp5_settings_' . $args['id'] . '_upload_button button-secondary" value="' . $args['button'] . '"/></span>';
		$html .= '<label for="fp5_settings_general[' . $args['id'] . ']"> '  . '<br>' . $args['desc'] . '</label>';

		echo $html;
	}

	/**
	 * Missing Callback
	 *
	 * If a function is missing for settings callbacks alert the user.
	 *
	 * @since 2.0
	 * @param array $args Arguments passed by the setting
	 * @return void
	 */
	function missing_callback( $args ) {
		printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'flowplayer5' ), $args['id'] );
	}

}
