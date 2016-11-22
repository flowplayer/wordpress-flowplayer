<?php
/**
 * Display Video Meta Box
 *
 * @package   Flowplayer 5 for WordPress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      https://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 */
?>
<div class="fp5-video-meta-box">
<ul class="nav-tab-wrapper nav-tab-small">
	<li class="nav-tab"><a href="#media"><?php _e( 'Media Files', $this->plugin_slug ) ?></a></li>
	<li class="nav-tab"><a href="#skinning"><?php _e( 'Skinning', $this->plugin_slug ) ?></a></li>
	<li class="nav-tab"><a href="#config"><?php _e( 'Configuration', $this->plugin_slug ) ?></a></li>
	<li class="nav-tab"><a href="#asf"><?php _e( 'Google AdSense', $this->plugin_slug ) ?></a></li>
	<li class="nav-tab"><a href="#vast"><?php _e( 'VAST', $this->plugin_slug ) ?></a></li>
</ul>
<?php
	require( 'partials/media.php' );
	require( 'partials/skinning.php' );
	require( 'partials/config.php' );
	require( 'partials/ads.php' );
	require( 'partials/vast.php' );
?>
</div>

<div id="fp5-preview" class="preview hidden">
	<div id="video"></div>
</div>
<div class="fp5-row-content hidden">
	<label for="fp5-user-id" class="fp5-row-title">User ID</label>
	<input type="text" name="fp5-user-id" id="fp5-user-id" value="<?php if ( isset ( $fp5_stored_meta['fp5-user-id'] ) ) echo esc_attr( $fp5_stored_meta['fp5-user-id'][0] ); ?>" />
	<label for="fp5-video-id" class="fp5-row-title">Video ID</label>
	<input type="text" name="fp5-video-id" id="fp5-video-id" value="<?php if ( isset ( $fp5_stored_meta['fp5-video-id'] ) ) echo esc_attr( $fp5_stored_meta['fp5-video-id'][0] ); ?>" />
	<label for="fp5-video-name" class="fp5-row-title">Video Name</label>
	<input type="text" name="fp5-video-name" id="fp5-video-name" value="<?php if ( isset ( $fp5_stored_meta['fp5-video-name'] ) ) echo esc_attr( $fp5_stored_meta['fp5-video-name'][0] ); ?>" />
	<label for="fp5-duration" class="fp5-row-title">Duration</label>
	<input type="text" name="fp5-duration" id="fp5-duration" value="<?php if ( isset ( $fp5_stored_meta['fp5-duration'] ) ) echo esc_attr( $fp5_stored_meta['fp5-duration'][0] ); ?>" />
</div>
