<?php
/**
 *
 * @package   Flowplayer HTML5 for WordPress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      https://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 *
 * @wordpress-plugin
 * Plugin Name: Flowplayer HTML5 for WordPress
 * Plugin URI:  http://wordpress.org/plugins/flowplayer5/
 * Description: A HTML5 responsive video player plugin. From the makers of Flowplayer. Includes player skins, tracking with Google Analytics, splash images and support for subtitles and multi-resolution videos. You can use your own watermark logo if you own a Commercial Flowplayer license.
 * Version:     2.1.0
 * Author:      Flowplayer ltd.
 * Author URI:  https://flowplayer.org/
 * Text Domain: flowplayer5
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Plugin Root File
if ( ! defined( 'FP5_PLUGIN_FILE' ) )
	define( 'FP5_PLUGIN_FILE', __FILE__ );

$dir = dirname( __FILE__ );

require_once( $dir . '/includes/class-flowplayer5.php' );
require_once( $dir . '/includes/class-flowplayer5-plugin.php' );
require_once( $dir . '/includes/class-flowplayer5-widget.php' );
require_once( $dir . '/includes/class-register-post-type.php' );
require_once( $dir . '/includes/class-register-taxonomy.php' );
require_once( $dir . '/admin/settings/class-register-settings.php' );
require_once( $dir . '/admin/settings/class-sanitize-settings.php' );
require_once( $dir . '/includes/functions.php' );
require_once( $dir . '/includes/deprecated.php' );

if ( is_admin() ) {
	if( ! defined( 'DOING_AJAX ') || ! DOING_AJAX ) {
		require_once( $dir . '/admin/class-flowplayer5-admin.php' );
		require_once( $dir . '/admin/class-flowplayer5-meta-box.php' );
		require_once( $dir . '/admin/class-flowplayer5-taxonomy-meta.php' );
		require_once( $dir . '/admin/flowplayer-drive/class-flowplayer-drive.php' );
		require_once( $dir . '/admin/flowplayer-drive/class-flowplayer-drive-error.php' );
		require_once( $dir . '/admin/insert-video-button.php' );

		if ( ! class_exists( 'Gamajo_Dashboard_Glancer' ) ) {
			require $dir . '/admin/includes/class-gamajo-dashboard-glancer.php';
		}

		if ( ! class_exists( 'Gamajo_Dashboard_RightNow' ) ) {
			require $dir . '/admin/includes/class-gamajo-dashboard-rightnow.php';
		}
	}

} else {
	require_once( $dir . '/frontend/class-flowplayer5-frontend.php' );
	require_once( $dir . '/frontend/class-flowplayer5-styles-scripts.php' );
	require_once( $dir . '/frontend/class-flowplayer5-playlist.php' );
	require_once( $dir . '/frontend/class-flowplayer5-output.php' );
	require_once( $dir . '/frontend/class-flowplayer5-shortcode.php' );
	require_once( $dir . '/frontend/class-flowplayer5-parse.php' );
}

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'Flowplayer5', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Flowplayer5', 'deactivate' ) );

Flowplayer5::get_instance();

// Don't use $plugins as it is not supported before WP 4.0 See: #28102
$fp5_plugin = new Flowplayer5_Plugin();
$fp5_plugin->run();

Flowplayer5_Post_Type::get_instance();
Flowplayer5_Taxonomy::get_instance();

if ( is_admin() ) {
	if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
		Flowplayer5_Admin::get_instance();
		Flowplayer5_Video_Meta_Box::get_instance();
	}
}
