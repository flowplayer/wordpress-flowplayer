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
	<li class="nav-tab"><a href="#asf"><?php _e( 'Google AdSense', $this->plugin_slug ) ?></a></li>
	<li class="nav-tab"><a href="#vast"><?php _e( 'VAST', $this->plugin_slug ) ?></a></li>
</ul>
<div id="media">
	<p style="width:70%;float:left;">
		<?php _e( 'Add at least two video formats so that the video plays on as many browsers as possible. To be able to calculate the dimensions of the video the video format added needs to be compatible with the current browser.', $this->plugin_slug ) ?> <a href="http://flowplayer.org/docs/setup.html#video-formats" target="_blank"><?php _e( 'About video formats.', $this->plugin_slug ) ?></a>
	</p>
	<p style="width:30%;float:right;">
		<label class="switch-light switch-android" onclick="">
			<input type="checkbox" name="fp5-toggle" id="fp5-toggle" value="flase"/>
			<span>
				<span><?php _e( 'Basic', $this->plugin_slug ) ?></span> / <span><?php _e( 'Advanced', $this->plugin_slug ) ?></span>
			</span>
			<a></a>
		</label>
	</p>
<table class="form-table">
	<tbody>

		<tr class="flowplayer-drive" valign="top">
			<th scope="row"><?php _e( 'Flowplayer Drive', $this->plugin_slug )?> <a href="http://flowplayer.org/" target="_blank">?</a></th>
			<td>
				<a href="#flowplayer-drive" class="fp5-add-drive button button-primary cboxElement" title="<?php _e( 'Add from Flowplayer Drive', $this->plugin_slug )?>"><?php _e( 'Add from Flowplayer Drive', $this->plugin_slug )?></a>
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
<p><?php _e( 'Customize the looks and behavoir of the video player.', $this->plugin_slug ) ?></p>
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
		<?php if ( version_compare( $this->player_version, '6.0.0', '<' ) ) : ?>
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
		<?php endif; ?>
		<tr class="fp5-lightbox" valign="top">
			<th scope="row"><?php _e( 'Lightbox', $this->plugin_slug )?></th>
			<td>
				<select name="fp5-lightbox" id="fp5-lightbox">
					<option value=""><?php _e( 'None', $this->plugin_slug )?></option>
					<option value="link" <?php if ( isset ( $fp5_stored_meta['fp5-lightbox'] ) ) selected( $fp5_stored_meta['fp5-lightbox'][0], 'link' ); ?>><?php _e( 'Link', $this->plugin_slug )?></option>
					<option value="thumbnail" <?php if ( isset ( $fp5_stored_meta['fp5-lightbox'] ) ) selected( $fp5_stored_meta['fp5-lightbox'][0], 'thumbnail' ); ?>><?php _e( 'Thumbnail', $this->plugin_slug )?></option>
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
				<?php if ( version_compare( $this->player_version, '6.0.0', '>=' ) ) { ?>
				<br>
				<label for="fp5-show-title">
					<input type="checkbox" name="fp5-show-title" id="fp5-show-title" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-show-title'] ) ) checked( $fp5_stored_meta['fp5-show-title'][0], 'true' ); ?> />
					<?php _e( 'Show the title for this clip. Displayed in a top bar when hovering over the player', $this->plugin_slug );?>
				</label>
				<?php } ?>
			</td>
		</tr>
	</tbody>
</table>
</div>
<div class="hidden" id="config">
<p><?php _e( 'Configure the video size, quality and attributes.', $this->plugin_slug ) ?></p>
<table class="form-table">
	<tbody>

		<tr class="fp5-video-attributes" valign="top">
			<th scope="row"><?php _e( 'Video attributes', $this->plugin_slug )?> <a href="http://flowplayer.org/docs/setup.html#video-attributes" target="_blank">?</a></th>
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
				|
				<label for="fp5-live">
					<input type="checkbox" name="fp5-live" id="fp5-live" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-live'] ) ) checked( $fp5_stored_meta['fp5-live'][0], 'true' ); ?> />
					<?php _e( 'Live streaming', $this->plugin_slug )?>
				</label>
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

		<tr class="fp5-quality" valign="top">
			<th scope="row"><?php _e( 'Video quality', $this->plugin_slug )?> <a href="https://flowplayer.org/demos/qsel/" target="_blank">?</a></th>
			<td>
				<label for="fp5-default-quality" class="fp5-row-title"><?php _e( 'Default quality', $this->plugin_slug )?></label>
				<input type="text" name="fp5-default-quality" id="fp5-default-quality" placeholder="<?php _e( '360p', $this->plugin_slug )?>" value="<?php if ( isset ( $fp5_stored_meta['fp5-default-quality'] ) ) echo esc_attr( $fp5_stored_meta['fp5-default-quality'][0] ); ?>" />
				|
				<label for="fp5-qualities" class="fp5-row-title"><?php _e( 'Video qualities', $this->plugin_slug )?></label>
				<input type="text" name="fp5-qualities" id="fp5-qualities" placeholder="<?php _e( '216p,360p,720p,1080p', $this->plugin_slug )?>" value="<?php if ( isset ( $fp5_stored_meta['fp5-qualities'] ) ) echo esc_attr( $fp5_stored_meta['fp5-qualities'][0] ); ?>" />
			</td>
		</tr>

	</tbody>
</table>
</div>
<div class="hidden" id="asf">
<p><?php _e( 'Enable and configure the video ad.', $this->plugin_slug ); ?> <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=flowplayer5&page=flowplayer5_settings' ) ); ?>"><?php _e( 'Set up Google AdSense', 'flowplayer5' ); ?></a></p>
<table class="form-table">
	<tbody>

		<tr class="fp5-ad-type" valign="top">
			<th scope="row"><label for="fp5-ad-type" class="fp5-row-title"><?php _e( 'Ad Type', $this->plugin_slug )?></label></th>
			<td>
				<select name="fp5-ad-type" id="fp5-ad-type">
					<option value="image_text" <?php if ( isset ( $fp5_stored_meta['fp5-ad-type'] ) ) selected( $fp5_stored_meta['fp5-ad-type'][0], 'image_text' ); ?>><?php _e( 'Image & Text', $this->plugin_slug ); ?></option>
					<option value="video" <?php if ( isset ( $fp5_stored_meta['fp5-ad-type'] ) ) selected( $fp5_stored_meta['fp5-ad-type'][0], 'video' ); ?>><?php _e( 'Video', $this->plugin_slug )?></option>
					<option value="skippablevideo" <?php if ( isset ( $fp5_stored_meta['fp5-ad-type'] ) ) selected( $fp5_stored_meta['fp5-ad-type'][0], 'skippablevideo' ); ?>><?php _e( 'Skippable Video', $this->plugin_slug ); ?></option>
				</select>
				<?php _e( 'Type of ads', $this->plugin_slug ); ?>
			</td>
		</tr>

		<tr class="fp5-ads-time" valign="top">
			<th scope="row"><label for="fp5-ads-time" class="fp5-row-title"><?php _e( 'Ads Time', $this->plugin_slug )?></label></th>
			<td>
				<input type="text" name="fp5-ads-time" id="fp5-ads-time" value="<?php if ( isset ( $fp5_stored_meta['fp5-ads-time'] ) ) echo esc_attr( $fp5_stored_meta['fp5-ads-time'][0] ); ?>" />
				<?php _e( 'Time in seconds into the video. Leave the field blank to disable the ads in the video.', $this->plugin_slug ); ?>
			</td>
		</tr>

		<tr class="fp5-description-url" valign="top">
			<th scope="row"><label for="fp5-description-url" class="fp5-row-title"><?php _e( 'Description URL', $this->plugin_slug )?></label></th>
			<td>
				<input type="text" name="fp5-description-url" id="fp5-description-url" value="<?php if ( isset ( $fp5_stored_meta['fp5-description-url'] ) ) echo esc_url( $fp5_stored_meta['fp5-description-url'][0] ); ?>" />
				<?php _e( 'It is recommended to create metadata pages for each video and supply them as the description URL. (Defaults to current URL where the video is placed.)', $this->plugin_slug ); ?> <a href="https://support.google.com/adsense/answer/1705829?hl=en&ref_topic=1706004"><?php _e( 'Best practices and optimization tips', $this->plugin_slug ); ?></a>
			</td>
		</tr>

	</tbody>
</table>
</div>

<div class="hidden" id="vast">
<p><?php _e( 'Enable and configure VAST.', $this->plugin_slug ); ?> <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=flowplayer5&page=flowplayer5_settings' ) ); ?>"><?php _e( 'Set up VAST', 'flowplayer5' ); ?></a></p>
<table class="form-table">
	<tbody>

		<tr class="fp5-vast-ads-time" valign="top">
			<th scope="row"><label for="fp5-vast-ads-time" class="fp5-row-title"><?php _e( 'Ads Time', $this->plugin_slug )?></label></th>
			<td>
				<input type="text" name="fp5-vast-ads-time" id="fp5-vast-ads-time" value="<?php if ( isset ( $fp5_stored_meta['fp5-vast-ads-time'] ) ) echo esc_attr( $fp5_stored_meta['fp5-vast-ads-time'][0] ); ?>" />
				<?php _e( 'Time in seconds into the video. 0 for pre-roll. -1 for post-roll. Default is pre-roll.', $this->plugin_slug ); ?>
			</td>
		</tr>

		<tr class="fp5-vast-ads-tag" valign="top">
			<th scope="row"><label for="fp5-vast-ads-tag" class="fp5-row-title"><?php _e( 'Ad Tag', $this->plugin_slug )?></label></th>
			<td>
				<input type="text" name="fp5-vast-ads-tag" id="fp5-vast-ads-tag" value="<?php if ( isset ( $fp5_stored_meta['fp5-vast-ads-tag'] ) ) echo esc_url( $fp5_stored_meta['fp5-vast-ads-tag'][0] ); ?>" />
				<?php _e( 'Ad Tag for VAST ad. Leave blank for global default ad tag.', $this->plugin_slug ); ?>
			</td>
		</tr>

		<tr class="fp5-vast-disable" valign="top">
			<th scope="row"><label for="fp5-vast-disable" class="fp5-row-title"><?php _e( 'Disable', $this->plugin_slug )?></label></th>
			<td>
				<input type="checkbox" name="fp5-vast-disable" id="fp5-vast-disable" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-vast-disable'] ) ) checked( $fp5_stored_meta['fp5-vast-disable'][0], 'true' ); ?> />
				<?php _e( 'Disable ads for this video?', $this->plugin_slug ); ?>
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
		<label for="fp5-duration" class="fp5-row-title">Duration</label>
		<input type="text" name="fp5-duration" id="fp5-duration" value="<?php if ( isset ( $fp5_stored_meta['fp5-duration'] ) ) echo esc_attr( $fp5_stored_meta['fp5-duration'][0] ); ?>" />
	</div>
</p>
