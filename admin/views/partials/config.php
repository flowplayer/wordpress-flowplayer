<div class="hidden" id="config">
<p><?php _e( 'Configure the video size, quality and attributes.', $this->plugin_slug ) ?></p>
<table class="form-table">
	<tbody>

		<tr class="fp5-video-attributes" valign="top">
			<th scope="row"><?php _e( 'Video attributes', $this->plugin_slug )?> <a href="https://flowplayer.org/docs/setup.html#video-attributes" target="_blank">?</a></th>
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
					<?php _e( 'Live streaming', $this->plugin_slug ); ?>
				</label>
				|
				<label for="fp5-hls-plugin">
					<input type="checkbox" name="fp5-hls-plugin" id="fp5-hls-plugin" value="true" <?php echo isset( $fp5_stored_meta['fp5-hls-plugin'][0] ) ? checked( $fp5_stored_meta['fp5-hls-plugin'][0], 'true', false ) : 'class="fp5-hls-notset"'; ?> />
					<?php _e( 'Load HLS plugin', $this->plugin_slug ); ?> <a href="https://flowplayer.org/docs/plugins.html#hlsjs">?</a>
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
