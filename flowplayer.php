<?php
/*
Plugin Name: Flowplayer 5 for Wordpress
Description: A Flowplayer plugin for showing videos in WordPress. Integrates Flowplayer 5. Supports all three default Flowplayer skins, subtitles, tracking with Google Analytics, splash images. You can use your own watermark logo if you own a Commercial Flowplayer license. Without a license this plugin uses the Free version that includes a Flowplayer watermark. Visit the <a href="/wp-admin/options-general.php?page=fp5_options">configuration page</a> and set your Google Analytics ID and Flowplayer license key.
Version: 0.4
Author: Flowplayer ltd. Anssi Piirainen
Author URI: http://flowplayer.org/
Plugin URI: http://flowplayer.org/wordpress
*/

define('FP5_PLUGIN_VERSION', '0.3');
define('FP5_FLOWPLAYER_VERSION', '5.2.1');

$my_plugin_file = __FILE__;

if (isset($plugin)) {
    $my_plugin_file = $plugin;
}
else if (isset($mu_plugin)) {
    $my_plugin_file = $mu_plugin;
}
else if (isset($network_plugin)) {
    $my_plugin_file = $network_plugin;
}

define('FP5_PLUGIN_DIR', WP_PLUGIN_DIR.'/'.basename(dirname($my_plugin_file)) . '/');
define('FP5_PLUGIN_URL', plugin_dir_url( $my_plugin_file));

function fp5_load(){

    if(is_admin()) {
        require_once(FP5_PLUGIN_DIR.'includes/admin.php');
    }

    require_once(FP5_PLUGIN_DIR.'includes/core.php');
}

fp5_load();
register_activation_hook($my_plugin_file, 'fp5_initGlobalOptions');

?>
