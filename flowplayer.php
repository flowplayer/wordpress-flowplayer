<?php
/**
 *
 * @package   Flowplayer HTML5 for WordPress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      http://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 *
 * @wordpress-plugin
 * Plugin Name: Flowplayer HTML5 for WordPress
 * Plugin URI:  http://wordpress.org/plugins/flowplayer5/
 * Description: A HTML5 responsive video player plugin. From the makers of Flowplayer. Includes player skins, tracking with Google Analytics, splash images and support for subtitles and multi-resolution videos. You can use your own watermark logo if you own a Commercial Flowplayer license.
 * Version:     1.10.1
 * Author:      Flowplayer ltd.
 * Author URI:  http://flowplayer.org/
 * Text Domain: flowplayer5
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $fp5_options;

// Plugin Root File
if ( ! defined( 'FP5_PLUGIN_FILE' ) )
	define( 'FP5_PLUGIN_FILE', __FILE__ );

require_once( plugin_dir_path( __FILE__ ) . 'includes/class-flowplayer5.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-flowplayer5-widget.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-register-post-type.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-register-taxonomy.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php' );
require_once( plugin_dir_path( __FILE__ ) . 'admin/settings/register-settings.php' );
$fp5_options = fp5_get_settings();

if ( is_admin() ) {
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-flowplayer5-admin.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-flowplayer5-meta-box.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-flowplayer5-taxonomy-meta.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'admin/flowplayer-drive/class-flowplayer-drive.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'admin/flowplayer-drive/class-flowplayer-drive-error.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'admin/flowplayer-drive/class-flowplayer-drive-posts.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'admin/insert-video-button.php' );
	if ( ! class_exists( 'Gamajo_Dashboard_Glancer' ) ) {
		require plugin_dir_path( __FILE__ ) . 'admin/includes/class-gamajo-dashboard-glancer.php';
	}
	if ( ! class_exists( 'Gamajo_Dashboard_RightNow' ) ) {
		require plugin_dir_path( __FILE__ ) . 'admin/includes/class-gamajo-dashboard-rightnow.php';
	}
} else {
	require_once( plugin_dir_path( __FILE__ ) . 'frontend/class-flowplayer5-frontend.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'frontend/class-flowplayer5-output.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'frontend/class-flowplayer5-shortcode.php' );
}

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'Flowplayer5', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Flowplayer5', 'deactivate' ) );

Flowplayer5::get_instance();
Flowplayer5_Post_Type::get_instance();
Flowplayer5_Taxonomy::get_instance();
if ( is_admin() ) {
	Flowplayer5_Admin::get_instance();
	Flowplayer5_Video_Meta_Box::get_instance();
	$flowplayer_drive = new Flowplayer_Drive();
	add_action( 'plugins_loaded', array( $flowplayer_drive, 'run' ) );
	new Flowplayer5_Taxonomy_Meta();
} else {
	new Flowplayer5_Frontend();
	new Flowplayer5_Shortcode();
}
