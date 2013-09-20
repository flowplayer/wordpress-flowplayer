<?php
/**
 *
 * @package   Flowplayer 5 for WordPress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      http://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 *
 * @wordpress-plugin
 * Plugin Name: Flowplayer 5 for WordPress
 * Plugin URI:  http://wordpress.org/plugins/flowplayer5/
 * Description: A HTML5 responsive video player plugin. From the makers of Flowplayer. Supports all three default Flowplayer skins, subtitles, tracking with Google Analytics, splash images. You can use your own watermark logo if you own a Commercial Flowplayer license.
 * Version:     1.1.0
 * Author:      Flowplayer ltd. Anssi Piirainen, Ulrich Pogson
 * Author URI:  http://flowplayer.org/
 * Text Domain: flowplayer5
 * Domain Path: /lang
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $fp5_options;

require_once( plugin_dir_path( __FILE__ ) . 'class-flowplayer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/register-settings.php' );
$fp5_options = fp5_get_settings();

if( is_admin() ) {
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-flowplayer-meta-box.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'includes/insert-video-button.php' );
} else {
	require_once( plugin_dir_path( __FILE__ ) . 'includes/shortcode.php' );
}

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'Flowplayer5', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Flowplayer5', 'deactivate' ) );

Flowplayer5::get_instance();
if( is_admin() ) {
	Video_Meta_Box::get_instance();
}