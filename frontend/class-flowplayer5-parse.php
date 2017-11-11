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
 * Flowplayer Output Class
 *
 * @package Flowplayer5_Shortcode
 * @author  Ulrich Pogson <ulrich@pogson.ch>
 *
 * @since 1.3.0
 */
class Flowplayer5_Parse {

	public static function playlist_single_video_config( $atts ) {
		$new_atts = array();
		foreach( $atts as $key => $value ) {
			$new_atts[ $key ] = self::single_video_config( $value );
		}
		return $new_atts;
	}

	public static function get_shortcode_attr( $atts ) {

		/**
		 * flowplayer shortcode
		 *
		 * @example [flowplayer id="123" splash="trailer_1080p.jpg" webm="trailer_1080p.webm" mp4="trailer_1080p.mp4" width="1920" height="1080" skin="functional" autoplay="true" loop="true" fixed="false" subtitles="bunny-en.vtt" fixed_controls="true" coloring="default" preload="auto"]
		 */
		$shortcode_defaults = array(
			'id'              => '',
			'mp4'             => '',
			'webm'            => '',
			'flash'           => '',
			'hls'             => '',
			'loop'            => '',
			'autoplay'        => '',
			'muted'           => '',
			'volume'          => '',
			'preload'         => '',
			'poster'          => '',
			'skin'            => '',
			'timeline'        => '',
			'splash'          => '',
			'subtitles'       => '',
			'max_width'       => '',
			'width'           => '',
			'height'          => '',
			'ratio'           => '',
			'fixed'           => '',
			'rtmp'            => '',
			'quality'         => '',
			'qualities'       => '',
			'coloring'        => '',
			'fixed_controls'  => '',
			'background'      => '',
			'aside_time'      => '',
			'show_title'      => '',
			'no_hover'        => '',
			'no_mute'         => '',
			'show_mute'       => '',
			'no_volume'       => '',
			'no_embed'        => '',
			'no_share'        => '',
			'no_buffer'       => '',
			'live'            => '',
			'play_button'     => '',
			'ads_time'        => '',
			'ad_type'         => '',
			'description_url' => '',
			'lightbox'        => '',
			'title'           => '',
			'hls_plugin'      => '',
			'icons_edgy'      => '',
			'icons_outlined'  => '',
		);

		$atts = array_filter( shortcode_atts(
			$shortcode_defaults,
			$atts,
			'flowplayer'
		) );

		$custom_fields = array();
		if ( isset( $atts['id'] ) && is_numeric( $atts['id'] ) ) {
			// get the meta from the post type
			$custom_fields             = get_post_custom( $atts['id'] );
			$custom_fields['title'][0] = get_the_title( $atts['id'] );
		} elseif ( empty( $atts['id'] ) ) {
			$atts['id']    = substr( md5( serialize( $atts ) ), 0, 5 );
		}

		$shortcode_attr = array(
			'id'              => $atts['id'],
			'loop'            => self::get_custom_fields( $custom_fields, 'fp5-loop', $atts, 'loop' ),
			'autoplay'        => self::get_custom_fields( $custom_fields, 'fp5-autoplay', $atts, 'autoplay' ),
			'muted'           => self::get_custom_fields( $custom_fields, 'fp5-muted', $atts, 'muted' ),
			'volume'          => self::get_custom_fields( $custom_fields, 'fp5-initial-volume', $atts, 'volume' ),
			'preload'         => self::get_custom_fields( $custom_fields, 'fp5-preload', $atts, 'preload' ),
			'poster'          => self::get_custom_fields( $custom_fields, 'fp5-poster', $atts, 'poster' ),
			'skin'            => self::get_custom_fields( $custom_fields, 'fp5-select-skin', $atts, 'skin', 'minimalist' ),
			'timeline'        => self::get_custom_fields( $custom_fields, 'fp5-timeline-style', $atts, 'timeline', 'timeline-default' ),
			'subtitles'       => self::get_custom_fields( $custom_fields, 'fp5-vtt-subtitles', $atts, 'subtitles' ),
			'max_width'       => self::get_custom_fields( $custom_fields, 'fp5-max-width', $atts, 'max_width' ),
			'ratio'           => self::get_custom_fields( '', '', $atts, 'ratio' ),
			'fixed'           => self::get_custom_fields( $custom_fields, 'fp5-fixed-width', $atts, 'fixed' ),
			'data_rtmp'       => self::get_custom_fields( $custom_fields, 'fp5-data-rtmp', $atts, 'rtmp' ),
			'quality'         => self::get_custom_fields( $custom_fields, 'fp5-default-quality', $atts, 'quality' ),
			'qualities'       => self::get_custom_fields( $custom_fields, 'fp5-qualities', $atts, 'qualities' ),
			'fixed_controls'  => self::get_custom_fields( $custom_fields, 'fp5-fixed-controls', $atts, 'fixed_controls' ),
			'background'      => self::get_custom_fields( $custom_fields, 'fp5-no-background', $atts, 'background' ),
			'aside_time'      => self::get_custom_fields( $custom_fields, 'fp5-aside-time', $atts, 'aside_time' ),
			'show_title'      => self::get_custom_fields( $custom_fields, 'fp5-show-title', $atts, 'show_title' ),
			'no_hover'        => self::get_custom_fields( $custom_fields, 'fp5-no-hover', $atts, 'no_hover' ),
			'no_mute'         => self::get_custom_fields( $custom_fields, 'fp5-no-mute', $atts, 'no_mute' ),
			'show_mute'       => self::get_custom_fields( $custom_fields, 'fp5-show-mute', $atts, 'show_mute' ),
			'no_volume'       => self::get_custom_fields( $custom_fields, 'fp5-no-volume', $atts, 'no_volume' ),
			'no_embed'        => self::get_custom_fields( $custom_fields, 'fp5-no-embed', $atts, 'no_embed' ),
			'no_share'        => self::get_custom_fields( $custom_fields, 'fp5-no-share', $atts, 'no_share' ),
			'no_buffer'       => self::get_custom_fields( $custom_fields, 'fp5-no-buffer', $atts, 'no_buffer' ),
			'live'            => self::get_custom_fields( $custom_fields, 'fp5-live', $atts, 'live' ),
			'play_button'     => self::get_custom_fields( $custom_fields, 'fp5-play-button', $atts, 'play_button' ),
			'icons_edgy'      => self::get_custom_fields( $custom_fields, 'fp5-icons-edgy', $atts, 'icons_edgy' ),
			'icons_outlined'  => self::get_custom_fields( $custom_fields, 'fp5-icons-outlined', $atts, 'icons_outlined' ),
			'ads_time'        => self::get_custom_fields( $custom_fields, 'fp5-ads-time', $atts, 'ads_time' ),
			'ad_type'         => self::get_custom_fields( $custom_fields, 'fp5-ad-type', $atts, 'ad_type' ),
			'fp5_ads'         => maybe_unserialize( self::get_custom_fields( $custom_fields, 'fp5_ads' ) ),
			'vast_adrules'    => self::get_custom_fields( $custom_fields, 'fp5-adrules-xml-url', $atts, 'adrules_xml' ),
			'fp5_vast_ads'    => maybe_unserialize( self::get_custom_fields( $custom_fields, 'fp5_vast_ads' ) ),
			'vast_vpaidmode'  => self::get_custom_fields( $custom_fields, 'fp5-vast-vpaidmode', $atts, 'vast_vpaidmode' ),
			'vast_redirects'  => self::get_custom_fields( $custom_fields, 'fp5-vast-redirects', $atts, 'vast_redirects' ),
			'title'           => self::get_custom_fields( $custom_fields, 'title', $atts, 'title' ),
			'splash'          => self::get_custom_fields( $custom_fields, 'fp5-splash-image', $atts, 'splash' ),
			'width'           => self::get_custom_fields( $custom_fields, 'fp5-width', $atts, 'width' ),
			'height'          => self::get_custom_fields( $custom_fields, 'fp5-height', $atts, 'height' ),
			'description_url' => self::get_custom_fields( $custom_fields, 'fp5-description-url', $atts, 'description_url', get_permalink() ),
			'lightbox'        => self::get_custom_fields( $custom_fields, 'fp5-lightbox', $atts, 'lightbox' ),
			'hls_plugin'      => self::get_custom_fields( $custom_fields, 'fp5-hls-plugin', $atts, 'hls_plugin', true ),
			'formats'         => array(
				'application/x-mpegurl' => self::get_custom_fields( $custom_fields, 'fp5-hls-video', $atts, 'hls' ),
				'video/webm'            => self::get_custom_fields( $custom_fields, 'fp5-webm-video', $atts, 'webm' ),
				'video/mp4'             => self::get_custom_fields( $custom_fields, 'fp5-mp4-video', $atts, 'mp4' ),
				'video/flash'           => self::get_custom_fields( $custom_fields, 'fp5-flash-video', $atts, 'flash' ),
			),
		);

		$global_options = fp5_get_settings();

		return array_merge( $global_options, $shortcode_attr );

	}

	public static function single_video_config( $atts ) {

		// Prepare div data config - deprecated
		$return['data_config'] = array();
		if ( has_filter( 'fp5_filter_flowplayer_data' ) ) {
			$return['data_config'] = array(
				( 0 < strlen( $atts['key'] ) ? 'data-key="' . esc_attr( $atts['key'] ) . '"' : '' ),
				( 0 < strlen( $atts['key'] ) && 0 < strlen( $atts['logo'] ) ? 'data-logo="' . esc_url( $atts['logo'] ) . '"' : '' ),
				( 0 < strlen( $atts['ga_account_id'] ) ? 'data-analytics="' . esc_attr( $atts['ga_account_id'] ) . '"' : '' ),
				( ( $atts['width'] > 0 && $atts['height'] > 0 ) ? 'data-ratio="' . $atts['ratio'] . '"' : '' ),
				( ! empty ( $atts['data_rtmp'] ) ? 'data-rtmp="' . esc_attr( $atts['data_rtmp'] ) . '"' : '' ),
				( ! empty ( $atts['quality'] ) && ! empty ( $atts['qualities'] ) ? 'data-default-quality="' . esc_attr( $atts['quality'] ) . '"' : '' ),
				( ! empty ( $atts['qualities'] ) ? 'data-qualities="' . esc_attr( $atts['qualities'] ) . '"' : '' ),
			);
		}

		// Prepare styles
		$return['style'] = array();
		if ( $atts['fixed'] == 'true' && ! empty( $atts['width'] ) && ! empty( $atts['height'] ) ) {
			$return['style']['width'] = $atts['width'] . 'px';
			$return['style']['height'] = $atts['height'] . 'px';
		} elseif ( $atts['max_width'] != 0 ) {
			$return['style']['max-width'] = $atts['max_width'] . 'px';
		}
		if( ! empty( $atts['splash'] ) ) {
			$return['style']['background-image'] = 'url(' . esc_url( $atts['splash'] ) . ')';
		}

		// Prepare player classes
		$return['player_classes'] = array(
			'flowplayer-video',
			'flowplayer-video-' . $atts['id'],
			'flowplayer-' . $atts['id'],
			$atts['skin'],
			$atts['timeline'],
		);

		// Prepare video classes
		$return['classes'] = array(
			( ! empty ( $atts['splash'] ) ? 'is-splash' : '' ),
			( ! empty ( $atts['logo_origin'] ) ? 'commercial' : '' ),
			( $atts['fixed_controls'] ? 'fixed-controls no-toggle' : '' ),
			( $atts['background'] ? 'no-background' : '' ),
			( $atts['aside_time'] ? 'aside-time' : '' ),
			( $atts['show_mute'] ? 'fp-mute' : '' ),
			( $atts['no_mute'] ? 'no-mute' : '' ),
			( $atts['no_buffer'] ? 'no-buffer' : '' ),
			( $atts['no_volume'] ? 'no-volume' : '' ),
			( $atts['play_button'] ? 'play-button' : '' ),
			( $atts['icons_edgy'] ? 'fp-edgy' : '' ),
			( $atts['icons_outlined'] ? 'fp-outlined' : '' ),
		);

		$return['attributes'] = array(
			( ( $atts['autoplay'] == 'true' ) ? 'autoplay' : '' ),
			( ( $atts['loop'] == 'true' ) ? 'loop' : '' ),
			( ( ! empty ( $atts['preload'] ) && 'true' !== $atts['live'] ) ? 'preload="' . esc_attr( $atts['preload'] ) . '"' : '' ),
			( ( $atts['poster'] == 'true' ) ? 'poster' : '' ),
		);

		// Prepare video tag data config
		$video_data_config = array();
		if ( ! empty ( $atts['title'] ) && ! empty ( $atts['show_title'] ) ) {
			$video_data_config['title'] = esc_attr( $atts['title'] );
		}
		$return['video_data_config'] = apply_filters( 'fp5_video_data_config', $video_data_config, $atts['id'] );

		// Prepare JS config
		$js_config = array();

		// ASF ads
		if ( ! empty( $atts['ad_type'] ) && ! empty( $atts['ads_time'] ) || '0' === $atts['ads_time'] ) {
			$asf_ads[] = array(
				'time'    => $atts['ads_time'],
				'ad_type' => $atts['ad_type'],
			);
		} elseif ( is_array( $atts['fp5_ads'] ) ) {
			foreach( $atts['fp5_ads'] as $fp5_ad ) {
				$asf_ads[] = array(
					'time'    => $fp5_ad['fp5-ads-time'],
					'ad_type' => $fp5_ad['fp5-ad-type'],
				);
			}
		}
		if ( $atts['asf_js'] && ! empty( $atts['fp5_ads'] ) ) {
			$js_config['ima'] = array(
				'adtest' => ! empty( $atts['asf_test'] ) ? 'on' : 'off',
				'description_url' => $atts['description_url'],
				'ads' => $asf_ads,
			);
		}

		// VAST
		if ( is_array( $atts['fp5_vast_ads'] ) ) {
			foreach( $atts['fp5_vast_ads'] as $fp5_vast_ad ) {
				$vast_ads[] = array(
					'time'    => $fp5_vast_ad['fp5-vast-ads-time'],
					'adTag'   => $fp5_vast_ad['fp5-vast-ads-url'],
				);
			}
		}
		if ( $atts['vast_js'] ) {
  		if ( ! empty( $atts['fp5_vast_ads'] ) ) {
  			$js_config['ima'] = array(
  				'ads' => $vast_ads,
  			);
			} elseif ( ! empty( $atts['vast_adrules'] ) ) {
  			$js_config['ima'] = array(
  				'adRules' => $atts['vast_adrules'],
  			);
			}

			if ( array_key_exists( 'ima', $js_config ) ) {
				// Vpaidmode
				if ( $atts['vast_vpaidmode'] !== '' ) {
					$js_config['ima']['VpaidMode'] = strtoupper( $atts['vast_vpaidmode'] );
				} elseif ( $atts['vast_vpaidmode_global'] !== '' ) {
					$js_config['ima']['VpaidMode'] = strtoupper( $atts['vast_vpaidmode_global'] );
				}

				// Redirects
				if ( $atts['vast_redirects'] !== '' ) {
					$js_config['ima']['redirects'] = intval( $atts['vast_redirects'] );
				} elseif ( $atts['vast_redirects_global'] !== '' ) {
					$js_config['ima']['redirects'] = $atts['vast_redirects_global'];
				}
			}

		}

		if ( 'true' == $atts['muted'] ) {
			$js_config['muted'] = (bool) $atts['muted'];
		} elseif ( "100" !== $atts['volume'] ) {
			$js_config['volume'] = floatval( $atts['volume'] ) / 100;
		}
		if ( 'true' == $atts['live'] ) {
			$js_config['live'] = (bool) $atts['live'];
		}
		if ( 0 < $atts['ratio'] ) {
			$js_config['ratio'] = esc_attr( $atts['ratio'] );
		} elseif ( 0 < $atts['width'] && 0 < $atts['height'] ) {
			$js_config['ratio'] = intval( $atts['height'] ) / intval( $atts['width'] );
		}
		if ( ! empty ( $atts['data_rtmp'] ) ) {
			$js_config['rtmp'] = esc_attr( $atts['data_rtmp'] );
		}
		if ( ! empty ( $atts['quality'] ) && ! empty ( $atts['qualities'] ) ) {
			$js_config['defaultQuality'] = esc_attr( $atts['quality'] );
		}
		if ( ! empty ( $atts['qualities'] ) ) {
			$js_config['qualities'] = explode( ',', esc_attr( $atts['qualities'] ) );
		}
		if ( 0 == $atts['width'] && 0 == $atts['height'] ) {
			$js_config['adaptiveRatio'] = true;
		}
		if ( 'true' == $atts['no_embed'] ) {
			$js_config['embed'] = false;
		}
		if ( 'true' == $atts['no_share'] ) {
			$js_config['share'] = false;
		}
		if ( 0 < strlen( $atts['key'] ) ) {
			$js_config['key'] = esc_attr( $atts['key'] );
		}
		if ( 0 < strlen( $atts['key'] ) && 0 < strlen( $atts['logo'] ) ) {
			$js_config['logo'] = esc_url( $atts['logo'] );
		}
		if ( 0 < strlen( $atts['ga_account_id'] ) ) {
			$js_config['analytics'] = esc_attr( $atts['ga_account_id'] );
		}
		if ( 0 < strlen( $atts['key'] ) ) {
			if ( ! empty ( $atts['brand_text'] ) ) {
				$js_config['brand']['text'] = esc_attr( $atts['brand_text'] );
			}
			if ( 1 == $atts['text_origin'] ) {
				$js_config['brand']['showOnOrigin'] = true;
			}
		}
		$js_config = apply_filters( 'fp5_js_config', $js_config, $atts['id'] );

		$clip_config = array(
			'flashls',
			'loop',
			'live',
			'rtmp',
			'sources',
			'title',
			'cuepoints',
			'dashjs',
			'defaultQuality',
			'qualities',
			'hlsjs',
			'ima',
			'subtitles',
			'thumbnails',
		);
		$return['clip_config']   = array_intersect_key( $js_config, $clip_config );
		$return['player_config'] = array_diff_key( $js_config, $clip_config );

		// Check if a video has been added before output
		if ( $atts['formats']['video/webm'] || $atts['formats']['video/mp4'] || $atts['formats']['video/flash'] || $atts['formats']['application/x-mpegurl'] ) {
			return array_merge( $atts, $return );
		}

	}

	/**
	 * Check if option exists
	 *
	 * @since    1.9.0
	 */
	private static function get_custom_fields( $custom_fields, $key, $override = array(), $override_key = '', $else = '' ) {
		if ( isset( $override[ $override_key ] ) ) {
			return $override[ $override_key ];
		} elseif ( isset( $custom_fields[ $key ][0] ) && ( ! empty( $custom_fields[ $key ][0] ) || is_numeric( $custom_fields[ $key ][0] ) ) ) {
			return $custom_fields[ $key ][0];
		} else {
			return $else;
		}
	}

}
