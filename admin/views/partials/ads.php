<script type="text/javascript">
jQuery(function() {
  jQuery('#asf').each(function() {
    jQuery(this).repeatable_fields({
      wrapper: 'table',
      container: 'tbody',
      row: '.fp5-ad-row',
      add: '.fp5_add_repeatable',
      remove: '.fp5_remove_repeatable',
    });
  });
});
</script>

<div class="hidden" id="asf">
<p><?php _e( 'Enable and configure the video ad.', $this->plugin_slug ); ?> <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=flowplayer5&page=flowplayer5_settings' ) ); ?>"><?php _e( 'Set up Google AdSense', 'flowplayer5' ); ?></a></p>
<table class="form-table">
	<tbody>

		<tr class="fp5-description-url" valign="top">
			<th scope="row"><label for="fp5-description-url" class="fp5-row-title"><?php _e( 'Description URL', $this->plugin_slug )?></label></th>
			<td>
				<input type="text" name="fp5-description-url" id="fp5-description-url" value="<?php if ( isset ( $fp5_stored_meta['fp5-description-url'] ) ) echo esc_url( $fp5_stored_meta['fp5-description-url'][0] ); ?>" />
				<?php _e( 'It is recommended to create metadata pages for each video and supply them as the description URL. (Defaults to current URL where the video is placed.)', $this->plugin_slug ); ?> <a href="https://support.google.com/adsense/answer/1705829?hl=en&ref_topic=1706004"><?php _e( 'Best practices and optimization tips', $this->plugin_slug ); ?></a>
			</td>
		</tr>

	</tbody>
</table>
<table class="form-table">
	<thead>
		<tr>
			<th style="width: 20%"><?php _e( 'Ad Type', $this->plugin_slug )?></th>
			<th><?php _e( 'Seconds into the video', $this->plugin_slug )?></th>
			<th style="width: 2%"></th>
		</tr>
	</thead>

	<tfoot>
		<tr class="fp5-ad-description" valign="top">
			<td>
				<a class="button-secondary fp5_add_repeatable"><?php _e( 'Add new ad', $this->plugin_slug ); ?></a>
			</td>
			<td>
				<?php _e( 'Leave the field blank to disable the ads in the video.', $this->plugin_slug ); ?>
			</td>
		</tr>
	</tfoot>

	<tbody>
		<tr class="template fp5-ad-row" valign="top" style="display:none;">
			<td>
				<select name="fp5_ads[][fp5-ad-type]" id="fp5-ad-type">
					<option value="image_text"><?php _e( 'Image & Text', $this->plugin_slug ); ?></option>
					<option value="video"><?php _e( 'Video', $this->plugin_slug )?></option>
					<option value="skippablevideo"><?php _e( 'Skippable Video', $this->plugin_slug ); ?></option>
				</select>
			</td>
			<td>
				<input type="text" name="fp5_ads[][fp5-ads-time]" id="fp5-ads-time"/>
			</td>
			<td>
				<button class="fp5_remove_repeatable">x</button>
			</td>
		</tr>

		<?php foreach ( $fp5_ads as $key => $value ) : ?>
		<tr class="fp5-ad-row" valign="top">
			<td>
				<select name="fp5_ads[<?php echo $key; ?>][fp5-ad-type]" id="fp5-ad-type">
					<option value="image_text" <?php if ( isset ( $value['fp5-ad-type'] ) ) selected( $value['fp5-ad-type'], 'image_text' ); ?>><?php _e( 'Image & Text', $this->plugin_slug ); ?></option>
					<option value="video" <?php if ( isset ( $value['fp5-ad-type'] ) ) selected( $value['fp5-ad-type'], 'video' ); ?>><?php _e( 'Video', $this->plugin_slug )?></option>
					<option value="skippablevideo" <?php if ( isset ( $value['fp5-ad-type'] ) ) selected( $value['fp5-ad-type'], 'skippablevideo' ); ?>><?php _e( 'Skippable Video', $this->plugin_slug ); ?></option>
				</select>
			</td>
			<td>
				<input type="text" name="fp5_ads[<?php echo $key; ?>][fp5-ads-time]" id="fp5-ads-time" value="<?php if ( isset ( $value['fp5-ads-time'] ) ) echo esc_attr( $value['fp5-ads-time'] ); ?>" />
			</td>
			<td>
				<button class="fp5_remove_repeatable">x</button>
			</td>
		</tr>
		<?php endforeach; ?>

	</tbody>
</table>
</div>
