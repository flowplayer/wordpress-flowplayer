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
