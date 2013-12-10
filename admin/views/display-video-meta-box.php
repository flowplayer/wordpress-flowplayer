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
<table class="form-table fp5-video-meta-box">
	<tbody>

		<tr class="fp5-media-files" valign="top">
			<th scope="row"><?php _e( 'Media files', $this->plugin_slug )?></th>
			<td><?php _e( 'It is recommended to add at least two video formats so that the video plays on as many browsers as possible. To be able to calculate the dimensions of the video the video format added needs to be compatible with the browser.', $this->plugin_slug ) ?> <a href="http://flowplayer.org/docs/setup.html#video-formats" target="_blank"><?php _e( 'About video formats.', $this->plugin_slug ) ?></a>
			</td>
		</tr>

		<tr class="flowplayer-drive" valign="top">
			<th scope="row"><?php _e( 'Flowplayer Designer', $this->plugin_slug )?> <a href="http://flowplayer.org/designer/" target="_blank">?</a></th>
			<td>
				<a href="#flowplayer-drive" class="fp5-add-drive button button-primary cboxElement" title="<?php _e( 'Add from Flowplayer Designer', $this->plugin_slug )?>"><?php _e( 'Add from Flowplayer Designer', $this->plugin_slug )?></a>
			</td>
		</tr>

		<tr class="fp5-splash-image" valign="top">
			<th scope="row"><label for="fp5-splash-image"><?php _e( 'Splash Image', $this->plugin_slug )?></label> <a href="http://flowplayer.org/docs/setup.html#splash" target="_blank">?</a></th>
			<td>
				<input class="media-url" type="text" name="fp5-splash-image" id="fp5-splash-image" value="<?php if ( isset ( $fp5_stored_meta['fp5-splash-image'] ) ) echo esc_url( $fp5_stored_meta['fp5-splash-image'][0] ); ?>" />
				<a href="#" class="fp5-add-splash-image button button-primary" title="<?php _e( 'Add splash image', $this->plugin_slug )?>"><?php _e( 'Add splash image', $this->plugin_slug )?></a>
			</td>
		</tr>

		<tr class="fp5-mp4-video" valign="top">
			<th scope="row"><label for="fp5-mp4-video"><?php _e( 'MP4 Video', $this->plugin_slug )?></label></th>
			<td>
				<input class="media-url" type="text" name="fp5-mp4-video" id="fp5-mp4-video" value="<?php if ( isset ( $fp5_stored_meta['fp5-mp4-video'] ) ) echo esc_url( $fp5_stored_meta['fp5-mp4-video'][0] ); ?>" />
				<a href="#" class="fp5-add-mp4 button button-primary" title="<?php _e( 'Add MP4 Video', $this->plugin_slug )?>"><?php _e( 'Add MP4 Video', $this->plugin_slug )?></a>
			</td>
		</tr>

		<tr class="fp5-webm-video" valign="top">
			<th scope="row"><label for="fp5-webm-video"><?php _e( 'WEBM Video', $this->plugin_slug )?></label></th>
			<td>
				<input class="media-url" type="text" name="fp5-webm-video" id="fp5-webm-video" size="70" value="<?php if ( isset ( $fp5_stored_meta['fp5-webm-video'] ) ) echo esc_url( $fp5_stored_meta['fp5-webm-video'][0] ); ?>" />
				<a href="#" class="fp5-add-webm button button-primary" title="<?php _e( 'Add WEBM Video', $this->plugin_slug )?>"><?php _e( 'Add WEBM Video', $this->plugin_slug )?></a>
			</td>
		</tr>

		<tr class="fp5-ogg-video" valign="top">
			<th scope="row"><label for="fp5-ogg-video"><?php _e( 'OGG Video', $this->plugin_slug )?></label></th>
			<td>
				<input class="media-url" type="text" name="fp5-ogg-video" id="fp5-ogg-video" value="<?php if ( isset ( $fp5_stored_meta['fp5-ogg-video'] ) ) echo esc_url( $fp5_stored_meta['fp5-ogg-video'][0] ); ?>" />
				<a href="#" class="fp5-add-ogg button button-primary" title="<?php _e( 'Add OGG Video', $this->plugin_slug )?>"><?php _e( 'Add OGG Video', $this->plugin_slug )?></a>
			</td>
		</tr>

		<tr class="fp5-vtt-subtitles" valign="top">
			<th scope="row"><label for="fp5-vtt-subtitles"><?php _e( 'VVT file (Subtitles)', $this->plugin_slug )?></label> <a href="http://flowplayer.org/docs/subtitles.html" target="_blank">?</a></th>
			<td>
				<input class="media-url" type="text" name="fp5-vtt-subtitles" id="fp5-vtt-subtitles" value="<?php if ( isset ( $fp5_stored_meta['fp5-vtt-subtitles'] ) ) echo esc_url( $fp5_stored_meta['fp5-vtt-subtitles'][0] ); ?>" />
				<a href="#" class="fp5-add-vtt button button-primary" title="<?php _e( 'Add VVT file', $this->plugin_slug )?>"><?php _e( 'Add VVT file', $this->plugin_slug )?></a>
			</td>
		</tr>

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

		<tr class="fp5-video-attributes" valign="top">
			<th scope="row"><?php _e( 'Video attributes', $this->plugin_slug )?> <a href="ttp://flowplayer.org/docs/setup.html#video-attributes" target="_blank">?</a></th>
			<td>
				<label for="fp5-autoplay">
					<?php _e( 'Autoplay', $this->plugin_slug )?>
					<input type="checkbox" name="fp5-autoplay" id="fp5-autoplay" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-autoplay'] ) ) checked( $fp5_stored_meta['fp5-autoplay'][0], 'true' ); ?> />
				</label>
				|
				<label for="fp5-loop">
					<?php _e( 'Loop', $this->plugin_slug )?>
					<input type="checkbox" name="fp5-loop" id="fp5-loop" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-loop'] ) ) checked( $fp5_stored_meta['fp5-loop'][0], 'true' ); ?> />
				</label>
				|
				<label for="fp5-preload" class="fp5-row-title"><?php _e( 'Preload', $this->plugin_slug )?></label>
				<select name="fp5-preload" id="fp5-preload">
					<option value="auto" <?php if ( isset ( $fp5_stored_meta['fp5-preload'] ) ) selected( $fp5_stored_meta['fp5-preload'][0], 'auto' ); ?>>auto</option>
					<option value="metadata" <?php if ( isset ( $fp5_stored_meta['fp5-preload'] ) ) selected( $fp5_stored_meta['fp5-preload'][0], 'metadata' ); ?>>metadata</option>
					<option value="none" <?php if ( isset ( $fp5_stored_meta['fp5-preload'] ) ) selected( $fp5_stored_meta['fp5-preload'][0], 'none' ); ?>>none</option>
				</select>
			</td>
		</tr>

		<tr class="fp5-flowplayer-options'" valign="top">
			<th scope="row"><?php _e( 'Flowplayer options', $this->plugin_slug )?> <a href="http://flowplayer.org/docs/skinning.html#modifier-classes" target="_blank">?</a></th>
			<td>
				<label for="fp5-fixed-controls">
					<?php _e( 'Fixed Controls', $this->plugin_slug )?>
					<input type="checkbox" name="fp5-fixed-controls" id="fp5-fixed-controls" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-fixed-controls'] ) ) checked( $fp5_stored_meta['fp5-fixed-controls'][0], 'true' ); ?> />
				</label>
				|
				<label for="fp5-coloring" class="fp5-row-title"><?php _e( 'Coloring', $this->plugin_slug )?></label>
				<select name="fp5-coloring" id="fp5-coloring">
					<option value="default" <?php if ( isset ( $fp5_stored_meta['fp5-coloring'] ) ) selected( $fp5_stored_meta['fp5-coloring'][0], 'default' ); ?>><?php _e( 'Default', $this->plugin_slug )?></option>
					<option value="color-alt" <?php if ( isset ( $fp5_stored_meta['fp5-coloring'] ) ) selected( $fp5_stored_meta['fp5-coloring'][0], 'color-alt' ); ?>><?php _e( 'Black & White', $this->plugin_slug )?></option>
					<option value="color-alt2" <?php if ( isset ( $fp5_stored_meta['fp5-coloring'] ) ) selected( $fp5_stored_meta['fp5-coloring'][0], 'color-alt2' ); ?>><?php _e( 'Red', $this->plugin_slug )?></option>
					<option value="color-light" <?php if ( isset ( $fp5_stored_meta['fp5-coloring'] ) ) selected( $fp5_stored_meta['fp5-coloring'][0], 'color-light' ); ?>><?php _e( 'Light', $this->plugin_slug )?></option>
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
	</div>
</p>