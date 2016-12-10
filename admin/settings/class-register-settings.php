<?php
/**
 * Register Settings
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

class Flowplayer5_Settings {

	/**
	 * Get all settings defaults
	 *
	 * @since 1.11.0
	 * @return array
	*/
	private function get_defaults( $options ) {
		$defaults = array();
		if ( empty( $options ) ) {
			$defaults['fp_version'] = 'fp6';
		} else {
			$defaults['fp_version'] = 'fp5';
		}
		
		$defaults['vast_vpaidmode_global'] = 'ENABLED';
		$defaults['vast_redirects_global'] = 4;		
		
		$settings_array = $this->get_registered_settings();
		foreach( $settings_array as $section => $setting ) {
			if ( isset( $setting['title'] ) ) {
				unset( $setting['title'] );
			}
			foreach ( $setting as $setting_name => $setting_config ) {
				$setting_names[ $setting_name ] = '';
			}
			$defaults = array_merge( $setting_names, $defaults ) ;
		}
		return apply_filters( 'fp5_option_defaults', $defaults );
	}

	/**
	 * Get the value of a specific setting
	 *
	 * @since 1.11.0
	 * @return mixed
	*/
	public function get( $key, $default = false ) {
		$settings = $this->get_all();
		$value = ! empty( $settings[ $key ] ) ? $settings[ $key ] : $default;
		return apply_filters( 'fp5_setting_' . $key, $value );
	}

	/**
	 * Get all settings
	 *
	 * @since 1.11.0
	 * @return array
	*/
	public function get_all() {
		$settings = get_option( 'fp5_settings_general', array() );
		$options = wp_parse_args(
			$settings,
			$this->get_defaults( $settings )
		);
		return apply_filters( 'fp5_settings', $options );
	}

	/**
	 * Add all settings sections and fields
	 *
	 * @since 1.11.0
	 * @return void
	*/
	public function register_settings() {
		$saved_settings = $this->get_all();
		$param_arr[] = $saved_settings;
		foreach ( $this->get_registered_settings() as $section => $settings ) {

			if ( isset( $settings['condition'] ) && ! call_user_func_array( $settings['condition'], $param_arr ) ) {
				continue;
			}

			$title = ! empty( $settings['title'] ) ? $settings['title'] : '__return_null';
			add_settings_section(
				'fp5_settings_' . $section,
				$title,
				array( $this, 'section_header_callback'),
				'flowplayer5_settings'
			);

			foreach ( $settings as $setting_id => $setting_config ) {

				if( ! is_array( $setting_config ) ||
				isset( $setting_config['condition'] ) && ! call_user_func_array( $setting_config['condition'], $param_arr ) ||
				'condition' === $setting_id ) {
					continue;
				}

				$callback = ! empty( $setting_config['callback'] ) ? $setting_config['callback'] : array( $this, $setting_config['type'] . '_callback' );

				add_settings_field(
					'fp5_settings_general[' . $setting_id . ']',
					isset( $setting_config['name'] ) ? $setting_config['name'] : '',
					is_callable( $callback ) ? $callback : array( $this, 'missing_callback' ),
					'flowplayer5_settings',
					'fp5_settings_' . $section,
					array(
						'id'        => $setting_id,
						'label_for' => '', // WP Core args
						'class'     => '', // WP Core args
						'desc'      => ! empty( $setting_config['desc'] ) ? $setting_config['desc'] : '',
						'name'      => isset( $setting_config['name'] ) ? $setting_config['name'] : null,
						'section'   => $section,
						'size'      => isset( $setting_config['size'] ) ? $setting_config['size'] : null,
						'button'    => isset( $setting_config['button'] ) ? $setting_config['button'] : __( 'Upload', 'flowplayer5' ),
						'options'   => isset( $setting_config['options'] ) ? $setting_config['options'] : '',
						'value'     => $saved_settings[ $setting_id ],
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
	 * @since 1.11.0
	 * @return array
	*/
	public function sanitize_settings( $input = array() ) {

		if ( empty( $_POST['_wp_http_referer'] ) ) {
			return $input;
		}

		$saved = $this->get_all();
		$settings = $this->get_registered_settings();
		if ( ! is_array( $saved ) ) {
			$saved = array();
		}
		$input = $input ? $input : array();
		foreach ( $settings  as $section => $setting ) {
			$input = apply_filters( 'fp5_settings_' . $section . '_sanitize', $input );

			// Ensure a value is always passed for every checkbox
			if ( ! empty( $settings[ $section ] ) ) {
				foreach ( $settings[ $section ] as $key => $setting ) {

					// Single checkbox
					if ( isset( $settings[ $section ][ $key ]['type'] ) && 'checkbox' == $settings[ $section ][ $key ]['type'] ) {
						$input[ $key ] = ! empty( $input[ $key ] );
					}

				}
			}

			// Loop through each setting being saved and pass it through a sanitization filter
			foreach ( $input as $key => $value ) {

				// Get the setting type (checkbox, select, etc)
				$type = isset( $settings[ $section ][ $key ][ 'type' ] ) ? $settings[ $section ][ $key ][ 'type' ] : false;
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
	 * @since 1.11.0
	 * @return array
	*/
	private function get_registered_settings() {
		$settings = array(
			'general' => array(
				'title' => __( 'General Settings', 'flowplayer5' ),
				'fp_version' => array(
					'name'    => '<strong>' . __( 'Flowplayer Version', 'flowplayer5' ) . '</strong>',
					'desc'    => __( 'Choose Flowplayer script version', 'flowplayer5' ),
					'type'    => 'select',
					'options' => array(
						'fp5' => __( 'Version 5', 'flowplayer5' ),
						'fp6' => __( 'Version 6', 'flowplayer5' )
					),
				),
				'cdn_option' => array(
					'name'      => __( 'CDN hosted files', 'flowplayer5' ),
					'desc'      => __( 'Use Flowplayer\'s CDN to host the CSS and JavaScript assets', 'flowplayer5' ),
					'type'      => 'checkbox',
					'condition' => array( $this, 'has_no_licence' ),
				),
			),
			'branding' => array(
				'title' => __( 'Branding', 'flowplayer5' ),
				'key' => array(
					'name' => __( 'License Key', 'flowplayer5' ) . ' <a href="https://flowplayer.org/docs/setup.html#commercial-configuration">?</a>',
					'desc' => __( 'Enter your license key.', 'flowplayer5' ),
					'type' => 'text',
					'size' => 'medium',
				),
				'directory' => array(
					'name'     => __( 'Commercial Assests Directory', 'flowplayer5' ),
					'type'     => 'text',
					'size'     => 'regular',
					'desc'     => __( 'Add the link to the directory with the Flowplayer Commercial assests', 'flowplayer5' ) . ' e.g. example.com/flowplayer5/',
					'condition' => array( $this, 'has_licence' ),
				),
				'logo' => array(
					'name'     => __( 'Logo', 'flowplayer5' ),
					'type'     => 'upload',
					'button'   => __( 'Add Logo', 'flowplayer5' ),
					'size'     => 'regular',
					'preview'  => 'true',
					'condition' => array( $this, 'has_licence' ),
				),
				'logo_origin' => array(
					'desc'     => __( 'Check to show the logo in videos on this site and in externally embedded videos.', 'flowplayer5' ),
					'type'     => 'checkbox',
					'condition' => array( $this, 'has_licence' ),
				),
				'brand_text' => array(
					'name'      => __( 'Brand Text', 'flowplayer5' ),
					'type'      => 'text',
					'button'    => __( 'Add brand name', 'flowplayer5' ) . ' <a href="https://flowplayer.org/news/releases/html5/v.6.0.0.html">' . __( 'Flowplayer 6 feature', 'flowplayer5' ) . '</a>',
					'size'      => 'regular',
					'desc'      => __( 'If set, the brand name will appear in the controlbar.' ),
					'condition' => array( $this, 'is_fp6' ),
				),
				'text_origin' => array(
					'desc'     => __( 'Check to show the title in videos on this site and in externally embedded videos.', 'flowplayer5' ) . ' <a href="https://flowplayer.org/news/releases/html5/v.6.0.0.html">' . __( 'Flowplayer 6 feature', 'flowplayer5' ) . '</a>',
					'type'      => 'checkbox',
					'condition' => array( $this, 'is_fp6' ),
				),
			),
			'flowplayer_drive' => array(
				'title' => __( 'Flowplayer Drive', 'flowplayer5' ) . ' <a href="https://flowplayer.org/docs/drive.html">?</a>',
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
				'drive_analytics' => array(
					'name' => __( 'Drive Analytics', 'flowplayer5' ) . ' <a href="https://flowplayer.org/blog/improved-analytics/">?</a>',
					'type' => 'checkbox',
					'desc' => __( 'Enable the Drive Analytics script', 'flowplayer5' ),
					'condition' => array( $this, 'is_fp6' ),
				)
			),
			'video_tracking' => array(
				'title' => __( 'Video tracking', 'flowplayer5' ) . ' <a href="https://flowplayer.org/docs/analytics.html">?</a>',
				'ga_account_id' => array(
					'name' => __( 'Google Analytics account ID', 'flowplayer5' ),
					'desc' => __( 'Enter your GA account ID.', 'flowplayer5' ),
					'type' => 'text',
					'size' => 'medium',
				),
			),
			'asf' => array(
				'title'     => __( 'AdSense for Flowplayer', 'flowplayer5' ) . ' <a href="https://flowplayer.org/asf/">?</a>',
				'condition' => array( $this, 'is_fp6' ),
				'asf_js' => array(
					'name'   => __( 'AdSense plugin JS', 'flowplayer5' ),
					'type'   => 'upload',
					'button' => __( 'Add JS file', 'flowplayer5' ),
					'size'  => 'regular',
					'desc'   => __( 'Add your custom AdSense plugin javascript file', 'flowplayer5' ),
				),
				'asf_test' => array(
					'name' => __( 'Test Mode', 'flowplayer5' ),
					'type' => 'checkbox',
					'desc' => __( 'Enable test mode', 'flowplayer5' ),
				)
			),
			'vast' => array(
				'title'     => __( 'VAST Ads', 'flowplayer5' ) . ' <a href="https://flowplayer.org/docs/vast.html">?</a>',
				'condition' => array( $this, 'is_fp6' ),
				'vast_js' => array(
					'name'   => __( 'VAST plugin URL', 'flowplayer5' ),
					'type'   => 'upload',
					'button' => __( 'Add JS file', 'flowplayer5' ),
					'size'  => 'regular',
					'desc'   => __( 'Add your custom VAST plugin file', 'flowplayer5' ),
				),
				'vast_vpaidmode_global' => array(
					'name'    => __( 'VpaidMode default', 'flowplayer5' ),
					'desc'    => __( 'Global default which can be overridden in individual videos', 'flowplayer5' ),
					'type'    => 'select',
					'options' => array(
						'DISABLED' => __( 'Disabled', 'flowplayer5' ),
						'ENABLED' => __( 'Enabled', 'flowplayer5' ),
						'INSECURE' => __( 'Insecure', 'flowplayer5' )
					),
				),
				'vast_redirects_global' => array(
					'name'   => __( 'Redirects', 'flowplayer5' ),
					'desc'    => __( 'Maximum number of redirects to try before ad load is aborted. Global default which can be overridden in individual videos', 'flowplayer5' ),
					'type'   => 'text',
					'size'  => 'small',
				),
			),
			'embed_options' => array(
				'title' => __( 'Embed assets', 'flowplayer5' ) . ' <a href="https://flowplayer.org/docs/embedding.html#configuration">?</a>',
				'library' => array(
					'name' => __( 'Library', 'flowplayer5' ),
					'desc' => __( 'URL of the Flowplayer API library script', 'flowplayer5' ),
					'type' => 'text',
					'size' => 'regular',
				),
				'script' => array(
					'name' => __( 'Embed script', 'flowplayer5' ),
					'desc' => __( 'URL of the embed script', 'flowplayer5' ),
					'type' => 'text',
					'size' => 'regular',
				),
				'skin' => array(
					'name' => __( 'Skin CSS', 'flowplayer5' ),
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
				'swfHls' => array(
					'name' => __( 'Flash HLS SWF file', 'flowplayer5' ),
					'desc' => __( 'URL of Flash HLS SWF file for embedding.', 'flowplayer5' ),
					'type' => 'text',
					'size' => 'regular',
				),
			),
		);
		$settings['general'] = apply_filters( 'fp5_settings_general', $settings['general'] );
		return apply_filters( 'fp5_register_settings', $settings );
	}

	private function is_fp6( $setting_values ) {
		return 'fp6' === $setting_values['fp_version'];
	}

	private function has_licence( $setting_values ) {
		return ! empty( $setting_values['key'] );
	}

	private function has_no_licence( $setting_values ) {
		return ! $this->has_licence( $setting_values );
	}

	/**
	 * Header Callback
	 *
	 * Renders the header.
	 *
	 * @since 1.11.0
	 * @param array $args Arguments passed by the setting
	 * @return void
	 */
	public function header_callback( $args ) {
		echo '<p class="description">' . $args['desc'] . '</p>';
	}

	/**
	 * Header Callback
	 *
	 * Renders the header.
	 *
	 * @since 1.11.0
	 * @param array $args Arguments passed by the setting
	 * @return void
	 */
	public function section_header_callback( $args ) {
		$html = '';
		switch ( $args['id'] ) {
			case 'fp5_settings_branding':
				$html = '<p class="description">' . __( 'The commercial version removes the Flowplayer logo and allows you to use your own logo image.', 'flowplayer5' ) . ' <a href="https://flowplayer.org/pricing/">' . __( 'Purchase license', 'flowplayer5' ) . '</a>' . '</p>';
				break;
			case 'fp5_settings_flowplayer_drive':
				$html = '<p class="description">' . __( 'Flowplayer Drive is a new feature that will hosts your video in all of the formats that you need.', 'flowplayer5' ) . '</p>';
				break;
			case 'fp5_settings_video_tracking':
				$html = '<p class="description">' . __( 'You can track video traffic using Google Analytics (GA).', 'flowplayer5' ) . '</p>';
				break;
			case 'fp5_settings_asf':
				$html = '<p class="description">' . __( 'Sign up for Google AdSense for Flowplayer to be able to monetize your videos ', 'flowplayer5' ). '</strong> <a href="https://flowplayer.org/asf/">' . __( 'Sign up now', 'flowplayer5' ) . '</a>' . '</p>';
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
	 * @since 1.11.0
	 * @param array $args Arguments passed by the setting
	 * @return void
	 */
	function checkbox_callback( $args ) {

		$checked = isset( $args['value'] ) ? checked( 1, $args['value'], false ) : '';
		$html = '<input type="checkbox" id="fp5_settings_general[' . $args['id'] . ']" name="fp5_settings_general[' . $args['id'] . ']" value="1" ' . $checked . '/>';
		$html .= '<label for="fp5_settings_general[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

		echo $html;
	}

	/**
	 * Text Callback
	 *
	 * Renders text fields.
	 *
	 * @since 1.11.0
	 * @param array $args Arguments passed by the setting
	 *
	 * @return void
	 */
	public function text_callback( $args ) {

		$value = $args['value'];

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
	 * @since 1.11.0
	 * @param array $args Arguments passed by the setting
	 *
	 * @return void
	 */
	function password_callback( $args ) {

		$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
		$desc = empty( $args['value'] ) ? $args['desc'] : __( 'Your password is saved. For security reasons it will not be displayed here.', 'flowplayer5' );
		$html = '<input type="password" class="' . $size . '-text" id="fp5_settings_general[' . $args['id'] . ']" name="fp5_settings_general[' . $args['id'] . ']"/>';
		$html .= '<label for="fp5_settings_general[' . $args['id'] . ']"> '  . '<br>' . $desc . '</label>';

		echo $html;
	}

	/**
	 * Select Callback
	 *
	 * Renders select fields.
	 *
	 * @since 1.11.0
	 * @param array $args Arguments passed by the setting
	 *
	 * @return void
	 */
	function select_callback( $args ) {

		$value = $args['value'];

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
	 *
	 * @return void
	 */
	public function upload_callback( $args ) {

		$value = $args['value'];

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
	 * @since 1.11.0
	 * @param array $args Arguments passed by the setting
	 * @return void
	 */
	public function missing_callback( $args ) {
		printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'flowplayer5' ), $args['id'] );
	}

}
