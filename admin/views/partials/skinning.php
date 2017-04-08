<div class="hidden" id="skinning">
<p><?php _e( 'Customize the looks and behavoir of the video player.', $this->plugin_slug ) ?> <a href="https://flowplayer.org/docs/skinning.html" target="_blank"><?php _e( 'Learn more about skinning the player.', $this->plugin_slug ) ?> </a></p>
<table class="form-table">
	<tbody>

		<tr class="fp5-select-skin" valign="top">
			<th scope="row"><?php _e( 'Select skin', $this->plugin_slug )?></th>
			<td>
				<select id="fp5-select-skin" name="fp5-select-skin">
					<?php
						if( version_compare( $this->player_version, '7.0.0', '>=' ) ) {
							$skins = array(
								'fp-default' => 'Default',
								'fp-minimal' => 'Minimal',
								'fp-playful' => 'Playful',
							);
						} else {
							$skins = array(
								'minimalist' => 'Minimalist',
								'functional' => 'Functional',
								'playful' => 'Playful',
							);
						}
						foreach( $skins as $name => $label ):
					?>
						<option id="fp5-<?php echo $name; ?>" value="<?php echo $name; ?>" <?php if ( isset ( $fp5_stored_meta['fp5-select-skin'] ) ) selected( $fp5_stored_meta['fp5-select-skin'][0], $name ); ?>><?php echo $label; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
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
		<?php if ( version_compare( $this->player_version, '7.0.0', '>=' ) ) { ?>
		<tr class="fp5-timeline-style" valign="top">
			<th scope="row"><?php _e( 'Timeline style', $this->plugin_slug )?></th>
			<td>
				<select id="fp5-timeline-style" name="fp5-timeline-style">
					<?php
						$timelines = array(
							'timeline-default' => 'Default',
							'fp-slim' => 'Slim',
							'fp-full' => 'Full',
							'fp-fat' => 'Fat',
						);
						foreach( $timelines as $name => $label ):
					?>
						<option id="fp5-<?php echo $name; ?>" value="<?php echo $name; ?>" <?php if ( isset ( $fp5_stored_meta['fp5-timeline-style'] ) ) selected( $fp5_stored_meta['fp5-timeline-style'][0], $name ); ?>><?php echo $label; ?></option>
					<?php endforeach; ?>
				</select>
				<div style="margin-top: 1em">
					<label for="fp5-no-buffer">
						<input type="checkbox" name="fp5-no-buffer" id="fp5-no-buffer" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-no-buffer'] ) ) checked( $fp5_stored_meta['fp5-no-buffer'][0], 'true' ); ?> />
						<?php _e( 'Hide buffer indicator', $this->plugin_slug )?>
					</label>
				</div>
			</td>
		</tr>
		<?php } ?>
		<tr class="fp5-flowplayer-style'" valign="top">
			<th scope="row"><?php _e( 'Player style', $this->plugin_slug )?> <a href="https://flowplayer.org/docs/skinning.html" target="_blank">?</a></th>
			<td>
				<?php
					if ( version_compare( $this->player_version, '7.0.0', '>=' ) ) {
						$style_checkboxes = array(
							'icons-edgy' 		 	=> __( 'Edgy icons', $this->plugin_slug ),
							'icons-outlined' 	=> __( 'Outlined icons', $this->plugin_slug ),
							'fixed-controls' 	=> __( 'Fixed Controls', $this->plugin_slug ),
							'show-mute' 			=> __( 'Show the mute control', $this->plugin_slug ),
							'no-volume' 			=> __( 'Hide the volume control', $this->plugin_slug ),
							'show-title' 			=> __( 'Show the title for this clip. Displayed in a top bar when hovering over the player', $this->plugin_slug ),
							'no-share' 				=> __( 'Hide the sharing button', $this->plugin_slug ),
						);
					} else {
						$style_checkboxes = array(
							'no-background'		=> __( 'Remove background color', $this->plugin_slug ),
							'fixed-controls' 	=> __( 'Fixed Controls', $this->plugin_slug ),
							'play-button' 		=> __( 'Display play button on the controlbar', $this->plugin_slug ),
							'aside-time' 			=> __( 'Time in top left corner', $this->plugin_slug ),
							'no-time' 				=> __( 'Hide the time display', $this->plugin_slug ),
							'no-mute' 				=> __( 'Hide the mute control', $this->plugin_slug ),
							'no-volume' 			=> __( 'Hide the volume control', $this->plugin_slug ),
							'no-embed' 				=> __( 'Hide the embed button', $this->plugin_slug ),
							'show-title' 			=> __( 'Show the title for this clip. Displayed in a top bar when hovering over the player', $this->plugin_slug ),
 						);
					}

					foreach ( $style_checkboxes as $name => $label ) {
				?>
				<label for="fp5-<?php echo $name; ?>">
					<input type="checkbox" name="fp5-<?php echo $name; ?>" id="fp5-<?php echo $name; ?>" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-' . $name] ) ) checked( $fp5_stored_meta['fp5-' . $name][0], 'true' ); ?> />
					<?php echo $label; ?>
				</label><br>
				<?php } ?>
			</td>
		</tr>
	</tbody>
</table>
</div>
