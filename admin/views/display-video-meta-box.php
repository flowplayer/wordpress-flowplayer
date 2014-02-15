<?php
/**
 * Display Video Meta Box
 *
 * @package   Flowplayer 5 for WordPress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      http://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 */
?>
<div class="fp5-video-meta-box">
<ul class="nav-tab-wrapper">
	<li class="nav-tab"><a href="#media"><?php _e( 'Media Files', $this->plugin_slug ) ?></a></li>
	<li class="nav-tab"><a href="#skinning"><?php _e( 'Skinning', $this->plugin_slug ) ?></a></li>
	<li class="nav-tab"><a href="#config"><?php _e( 'Configuration', $this->plugin_slug ) ?></a></li>
</ul>
<div id="media">
<h3><?php _e( 'Media Files', $this->plugin_slug )?></h3>
<p><?php _e( 'It is recommended to add at least two video formats so that the video plays on as many browsers as possible. To be able to calculate the dimensions of the video the video format added needs to be compatible with the current browser.', $this->plugin_slug ) ?> <a href="http://flowplayer.org/docs/setup.html#video-formats" target="_blank"><?php _e( 'About video formats.', $this->plugin_slug ) ?></a>
<label class="switch-light switch-android" onclick="">
	<input type="checkbox" name="fp5-toggle" id="fp5-toggle" value="flase"/>
	<span>
		<span><?php _e( 'Basic', $this->plugin_slug ) ?></span> / <span><?php _e( 'Advance', $this->plugin_slug ) ?></span>
	</span>
	<a></a>
</label></p>
<table class="form-table">
	<tbody>

		<tr class="flowplayer-drive" valign="top">
			<th scope="row"><?php _e( 'Flowplayer Designer', $this->plugin_slug )?> <a href="http://flowplayer.org/designer/" target="_blank">?</a></th>
			<td>
				<a href="#flowplayer-drive" class="fp5-add-drive button button-primary cboxElement" title="<?php _e( 'Add from Flowplayer Designer', $this->plugin_slug )?>"><?php _e( 'Add from Flowplayer Designer', $this->plugin_slug )?></a>
			</td>
		</tr>

		<tr class="fp5-splash-image" valign="top">
			<th scope="row"><label for="fp5-splash-image"><?php _e( 'Splash Image', $this->plugin_slug )?></label> <a href="http://flowplayer.org/docs/setup.html#splash" target="_blank">?</a></th>
			<td>
				<input class="media-url" type="text" name="fp5-splash-image" id="fp5-splash-image" placeholder="<?php _e( 'Add splash image url', $this->plugin_slug )?>" value="<?php if ( isset ( $fp5_stored_meta['fp5-splash-image'] ) ) echo esc_url( $fp5_stored_meta['fp5-splash-image'][0] ); ?>" />
				<a href="#" class="fp5-add-splash-image button button-primary" title="<?php _e( 'Add splash image', $this->plugin_slug )?>"><?php _e( 'Add splash image', $this->plugin_slug )?></a>
			</td>
		</tr>

		<tr class="fp5-mp4-video" valign="top">
			<th scope="row"><label for="fp5-mp4-video"><?php _e( 'MP4 Video', $this->plugin_slug )?></label></th>
			<td>
				<input class="media-url" type="text" name="fp5-mp4-video" id="fp5-mp4-video" placeholder="<?php _e( 'Add MP4 Video url', $this->plugin_slug )?>" value="<?php if ( isset ( $fp5_stored_meta['fp5-mp4-video'] ) ) echo esc_url( $fp5_stored_meta['fp5-mp4-video'][0] ); ?>" />
				<a href="#" class="fp5-add-mp4 button button-primary" title="<?php _e( 'Add MP4 Video', $this->plugin_slug )?>"><?php _e( 'Add MP4 Video', $this->plugin_slug )?></a>
			</td>
		</tr>

		<tr class="fp5-webm-video" valign="top">
			<th scope="row"><label for="fp5-webm-video"><?php _e( 'WEBM Video', $this->plugin_slug )?></label></th>
			<td>
				<input class="media-url" type="text" name="fp5-webm-video" id="fp5-webm-video" size="70" placeholder="<?php _e( 'Add WEBM Video url', $this->plugin_slug )?>" value="<?php if ( isset ( $fp5_stored_meta['fp5-webm-video'] ) ) echo esc_url( $fp5_stored_meta['fp5-webm-video'][0] ); ?>" />
				<a href="#" class="fp5-add-webm button button-primary" title="<?php _e( 'Add WEBM Video', $this->plugin_slug )?>"><?php _e( 'Add WEBM Video', $this->plugin_slug )?></a>
			</td>
		</tr>

		<tr class="fp5-ogg-video" valign="top">
			<th scope="row"><label for="fp5-ogg-video"><?php _e( 'OGG Video', $this->plugin_slug )?></label></th>
			<td>
				<input class="media-url" type="text" name="fp5-ogg-video" id="fp5-ogg-video" placeholder="<?php _e( 'Add OGG Video url', $this->plugin_slug )?>" value="<?php if ( isset ( $fp5_stored_meta['fp5-ogg-video'] ) ) echo esc_url( $fp5_stored_meta['fp5-ogg-video'][0] ); ?>" />
				<a href="#" class="fp5-add-ogg button button-primary" title="<?php _e( 'Add OGG Video', $this->plugin_slug )?>"><?php _e( 'Add OGG Video', $this->plugin_slug )?></a>
			</td>
		</tr>

		<tr class="fp5-flash-video hidden advance" valign="top">
			<th scope="row"><label for="fp5-flash-video"><?php _e( 'Flash Video', $this->plugin_slug )?></label> <a href="http://flowplayer.org/docs/setup.html#flash-video" target="_blank">?</a></th>
			<td>
				<input class="media-url" type="text" name="fp5-flash-video" id="fp5-flash-video" placeholder="<?php _e( 'Add Flash video url', $this->plugin_slug )?>" value="<?php if ( isset ( $fp5_stored_meta['fp5-flash-video'] ) ) echo esc_attr( $fp5_stored_meta['fp5-flash-video'][0] ); ?>" />
				<a href="#" class="fp5-add-flash button button-primary" title="<?php _e( 'Add Flash video', $this->plugin_slug )?>"><?php _e( 'Add Flash video', $this->plugin_slug )?></a>
			</td>
		</tr>

		<tr class="fp5-hls-video hidden advance" valign="top">
			<th scope="row"><label for="fp5-hls-video"><?php _e( 'HLS Video', $this->plugin_slug )?></label> <a href="http://flowplayer.org/docs/setup.html#video-formats" target="_blank">?</a></th>
			<td>
				<input class="media-url" type="text" name="fp5-hls-video" id="fp5-hls-video" placeholder="<?php _e( 'Add HLS video url', $this->plugin_slug )?>" value="<?php if ( isset ( $fp5_stored_meta['fp5-hls-video'] ) ) echo esc_attr( $fp5_stored_meta['fp5-hls-video'][0] ); ?>" />
				<a href="#" class="fp5-add-hls button button-primary" title="<?php _e( 'Add HLS video', $this->plugin_slug )?>"><?php _e( 'Add HLS video', $this->plugin_slug )?></a>
			</td>
		</tr>

		<tr class="fp5-vtt-subtitles" valign="top">
			<th scope="row"><label for="fp5-vtt-subtitles"><?php _e( 'VVT file (Subtitles)', $this->plugin_slug )?></label> <a href="http://flowplayer.org/docs/subtitles.html" target="_blank">?</a></th>
			<td>
				<input class="media-url" type="text" name="fp5-vtt-subtitles" id="fp5-vtt-subtitles" placeholder="<?php _e( 'Add VVT file url', $this->plugin_slug )?>" value="<?php if ( isset ( $fp5_stored_meta['fp5-vtt-subtitles'] ) ) echo esc_url( $fp5_stored_meta['fp5-vtt-subtitles'][0] ); ?>" />
				<a href="#" class="fp5-add-vtt button button-primary" title="<?php _e( 'Add VVT file', $this->plugin_slug )?>"><?php _e( 'Add VVT file', $this->plugin_slug )?></a>
			</td>

		<tr class="fp5-data-rtmp hidden advance" valign="top">
			<th scope="row"><label for="fp5-data-rtmp"><?php _e( 'RTMP URL', $this->plugin_slug )?></label> <a href="http://flowplayer.org/docs/setup.html#server-side" target="_blank">?</a></th>
			<td>
				<input class="media-url" type="text" name="fp5-data-rtmp" id="fp5-data-rtmp" placeholder="<?php _e( 'Add rtmp value', $this->plugin_slug )?>" value="<?php if ( isset ( $fp5_stored_meta['fp5-data-rtmp'] ) ) echo esc_attr( $fp5_stored_meta['fp5-data-rtmp'][0] ); ?>" />
			</td>
		</tr>

		</tr>
	</tbody>
</table>
</div>
<div class="hidden" id="skinning">
<h3><?php _e( 'Skinning', $this->plugin_slug )?></h3>
<p><?php _e( 'Alter the looks and/or behaviour of the skin.', $this->plugin_slug ) ?></p>
<table class="form-table">
	<tbody>

		<tr class="fp5-select-skin" valign="top">
			<th scope="row"><?php _e( 'Select skin', $this->plugin_slug )?></th>
			<td>
				<select id="fp5-select-skin" name="fp5-select-skin">
					<option id="fp5-minimalist" value="minimalist" <?php if ( isset ( $fp5_stored_meta['fp5-select-skin'] ) ) selected( $fp5_stored_meta['fp5-select-skin'][0], 'minimalist' ); ?>>Minimalist</option>
					<option id="fp5-functional" value="functional" <?php if ( isset ( $fp5_stored_meta['fp5-select-skin'] ) ) selected( $fp5_stored_meta['fp5-select-skin'][0], 'functional' ); ?>>Functional</option>
					<option id="fp5-playful" value="playful" <?php if ( isset ( $fp5_stored_meta['fp5-select-skin'] ) ) selected( $fp5_stored_meta['fp5-select-skin'][0], 'playful' ); ?>>Playful</option>
				</select>
				<div class="player-previews">
					<img id="fp5_minimalist" class="minimalist player-preview" src="<?php echo plugins_url( '../assets/img/minimalist.png', __FILE__ ) ?>" />
					<img id="fp5_functional" class="functional player-preview" src="<?php echo plugins_url( '../assets/img/functional.png', __FILE__ ) ?>" />
					<img id="fp5_playful" class="playful player-preview" src="<?php echo plugins_url( '../assets/img/playful.png', __FILE__ ) ?>" />
				</div>
			</td>
		</tr>

		<tr class="fp5-coloring" valign="top">
			<th scope="row"><?php _e( 'Coloring', $this->plugin_slug )?></th>
			<td>
				<select name="fp5-coloring" id="fp5-coloring">
					<option value="default" <?php if ( isset ( $fp5_stored_meta['fp5-coloring'] ) ) selected( $fp5_stored_meta['fp5-coloring'][0], 'default' ); ?>><?php _e( 'Default', $this->plugin_slug )?></option>
					<option value="color-alt" <?php if ( isset ( $fp5_stored_meta['fp5-coloring'] ) ) selected( $fp5_stored_meta['fp5-coloring'][0], 'color-alt' ); ?>><?php _e( 'Black & White', $this->plugin_slug )?></option>
					<option value="color-alt2" <?php if ( isset ( $fp5_stored_meta['fp5-coloring'] ) ) selected( $fp5_stored_meta['fp5-coloring'][0], 'color-alt2' ); ?>><?php _e( 'Red', $this->plugin_slug )?></option>
					<option value="color-light" <?php if ( isset ( $fp5_stored_meta['fp5-coloring'] ) ) selected( $fp5_stored_meta['fp5-coloring'][0], 'color-light' ); ?>><?php _e( 'Light', $this->plugin_slug )?></option>
				</select>
			</td>
		</tr>

		<tr class="fp5-flowplayer-style'" valign="top">
			<th scope="row"><?php _e( 'Player style', $this->plugin_slug )?> <a href="http://flowplayer.org/docs/skinning.html#modifier-classes" target="_blank">?</a></th>
			<td>
				<label for="fp5-no-background">
					<input type="checkbox" name="fp5-no-background" id="fp5-no-background" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-no-background'] ) ) checked( $fp5_stored_meta['fp5-no-background'][0], 'true' ); ?> />
					<?php _e( 'Remove background color', $this->plugin_slug )?>
				</label>
				<br>
				<label for="fp5-fixed-controls">
					<input type="checkbox" name="fp5-fixed-controls" id="fp5-fixed-controls" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-fixed-controls'] ) ) checked( $fp5_stored_meta['fp5-fixed-controls'][0], 'true' ); ?> />
					<?php _e( 'Fixed Controls', $this->plugin_slug )?>
				</label>
				<br>
				<label for="fp5-play-button">
					<input type="checkbox" name="fp5-play-button" id="fp5-play-button" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-play-button'] ) ) checked( $fp5_stored_meta['fp5-play-button'][0], 'true' ); ?> />
					<?php _e( 'Display play button on the controlbar', $this->plugin_slug )?>
				</label>
				<br>
				<label for="fp5-no-hover">
					<input type="checkbox" name="fp5-no-hover" id="fp5-no-hover" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-no-hover'] ) ) checked( $fp5_stored_meta['fp5-no-hover'][0], 'true' ); ?> />
					<?php _e( 'All UI elements are always visible ignoring the mouse hover', $this->plugin_slug )?>
				</label>
				<br>
				<label for="fp5-aside-time">
					<input type="checkbox" name="fp5-aside-time" id="fp5-aside-time" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-aside-time'] ) ) checked( $fp5_stored_meta['fp5-aside-time'][0], 'true' ); ?> />
					<?php _e( 'Time in top left corner', $this->plugin_slug )?>
				</label>
				<br>
				<label for="fp5-no-time">
					<input type="checkbox" name="fp5-no-time" id="fp5-no-time" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-no-time'] ) ) checked( $fp5_stored_meta['fp5-no-time'][0], 'true' ); ?> />
					<?php _e( 'Hide the time display', $this->plugin_slug )?>
				</label>
				<br>
				<label for="fp5-no-mute">
					<input type="checkbox" name="fp5-no-mute" id="fp5-no-mute" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-no-mute'] ) ) checked( $fp5_stored_meta['fp5-no-mute'][0], 'true' ); ?> />
					<?php _e( 'Hide the mute control', $this->plugin_slug )?>
				</label>
				<br>
				<label for="fp5-no-volume">
					<input type="checkbox" name="fp5-no-volume" id="fp5-no-volume" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-no-volume'] ) ) checked( $fp5_stored_meta['fp5-no-volume'][0], 'true' ); ?> />
					<?php _e( 'Hide the volume control', $this->plugin_slug )?>
				</label>
				<br>
				<label for="fp5-no-embed">
					<input type="checkbox" name="fp5-no-embed" id="fp5-no-embed" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-no-embed'] ) ) checked( $fp5_stored_meta['fp5-no-embed'][0], 'true' ); ?> />
					<?php _e( 'Hide the embed button', $this->plugin_slug )?>
				</label>
			</td>
		</tr>
	</tbody>
</table>
</div>
<div class="hidden" id="config">
<h3><?php _e( 'Configuration', $this->plugin_slug )?></h3>
<p><?php _e( 'Configure the video attributes and player size', $this->plugin_slug ) ?></p>
<table class="form-table">
	<tbody>

		<tr class="fp5-video-attributes" valign="top">
			<th scope="row"><?php _e( 'Video attributes', $this->plugin_slug )?> <a href="ttp://flowplayer.org/docs/setup.html#video-attributes" target="_blank">?</a></th>
			<td>
				<label for="fp5-autoplay">
					<input type="checkbox" name="fp5-autoplay" id="fp5-autoplay" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-autoplay'] ) ) checked( $fp5_stored_meta['fp5-autoplay'][0], 'true' ); ?> />
					<?php _e( 'Autoplay', $this->plugin_slug )?>
				</label>
				|
				<label for="fp5-loop">
					<input type="checkbox" name="fp5-loop" id="fp5-loop" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-loop'] ) ) checked( $fp5_stored_meta['fp5-loop'][0], 'true' ); ?> />
					<?php _e( 'Loop', $this->plugin_slug )?>
				</label>
				|
				<label for="fp5-preload" class="fp5-row-title"><?php _e( 'Preload', $this->plugin_slug )?></label>
				<select name="fp5-preload" id="fp5-preload">
					<option value="none" <?php if ( isset ( $fp5_stored_meta['fp5-preload'] ) ) selected( $fp5_stored_meta['fp5-preload'][0], 'none' ); ?>>none</option>
					<option value="metadata" <?php if ( isset ( $fp5_stored_meta['fp5-preload'] ) ) selected( $fp5_stored_meta['fp5-preload'][0], 'metadata' ); ?>>metadata</option>
					<option value="auto" <?php if ( isset ( $fp5_stored_meta['fp5-preload'] ) ) selected( $fp5_stored_meta['fp5-preload'][0], 'auto' ); ?>>auto</option>
				</select>
			</td>
		</tr>

		<tr class="fp5-size-options" valign="top">
			<th scope="row" rowspan="2"><?php _e( 'Size options', $this->plugin_slug )?></th>
			<td>
				<label for="fp5-max-width" class="fp5-row-title"><?php _e( 'Max Width', $this->plugin_slug )?></label>
				<input type="text" name="fp5-max-width" id="fp5-max-width" value="<?php if ( isset ( $fp5_stored_meta['fp5-max-width'] ) ) echo esc_attr( $fp5_stored_meta['fp5-max-width'][0] ); ?>" />
				<label for="fp5-width" class="fp5-row-title"><?php _e( 'Width', $this->plugin_slug )?></label>
				<input type="text" name="fp5-width" id="fp5-width" value="<?php if ( isset ( $fp5_stored_meta['fp5-width'] ) ) echo esc_attr( $fp5_stored_meta['fp5-width'][0] ); ?>" />
				<label for="fp5-height" class="fp5-row-title"><?php _e( 'Height', $this->plugin_slug )?></label>
				<input type="text" name="fp5-height" id="fp5-height" value="<?php if ( isset ( $fp5_stored_meta['fp5-height'] ) ) echo esc_attr( $fp5_stored_meta['fp5-height'][0] ); ?>" />
			</td>
		</tr>
		<tr class="fp5-size-options" valign="top">
			<td>
				<label for="fp5-aspect-ratio">
					<input type="checkbox" name="fp5-aspect-ratio" id="fp5-aspect-ratio" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-aspect-ratio'] ) ) checked( $fp5_stored_meta['fp5-aspect-ratio'][0], 'true' ); ?> />
					<?php _e( 'Change video dimensions', $this->plugin_slug )?>
				</label>
				|
				<label for="fp5-fixed-width">
					<input type="checkbox" name="fp5-fixed-width" id="fp5-fixed-width" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-fixed-width'] ) ) checked( $fp5_stored_meta['fp5-fixed-width'][0], 'true' ); ?> />
					<?php _e( 'Use fixed player size', $this->plugin_slug ) ?>
				</label>
			</td>
		</tr>

	</tbody>
</table>
</div>
</div>

<p>
	<div id="fp5-preview" class="preview hidden">
		<div id="video"></div>
	</div>
</p>

<p>
	<div class="fp5-row-content hidden">
		<label for="fp5-user-id" class="fp5-row-title">User ID</label>
		<input type="text" name="fp5-user-id" id="fp5-user-id" value="<?php if ( isset ( $fp5_stored_meta['fp5-user-id'] ) ) echo esc_attr( $fp5_stored_meta['fp5-user-id'][0] ); ?>" />
		<label for="fp5-video-id" class="fp5-row-title">Video ID</label>
		<input type="text" name="fp5-video-id" id="fp5-video-id" value="<?php if ( isset ( $fp5_stored_meta['fp5-video-id'] ) ) echo esc_attr( $fp5_stored_meta['fp5-video-id'][0] ); ?>" />
		<label for="fp5-video-name" class="fp5-row-title">Video Name</label>
		<input type="text" name="fp5-video-name" id="fp5-video-name" value="<?php if ( isset ( $fp5_stored_meta['fp5-video-name'] ) ) echo esc_attr( $fp5_stored_meta['fp5-video-name'][0] ); ?>" />
	</div>
</p>
