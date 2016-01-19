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
