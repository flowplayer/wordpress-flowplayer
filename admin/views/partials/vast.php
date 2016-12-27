<script type="text/javascript">
jQuery(function() {
  jQuery('#vast').each(function() {
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

<div class="hidden" id="vast">
<p><?php _e( 'Enable and configure VAST video ads.', $this->plugin_slug ); ?> <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=flowplayer5&page=flowplayer5_settings' ) ); ?>"><?php _e( 'Set up VAST', 'flowplayer5' ); ?></a></p>

<table class="form-table">
	<tbody>

		<tr class="fp5-vast-vpaidmode" valign="top">
			<th scope="row"><label for="fp5-vast-vpaidmode" class="fp5-row-title"><?php _e( 'VpaidMode', $this->plugin_slug )?></label></th>
			<td>
				<select id="fp5-vast-vpaidmode" name="fp5-vast-vpaidmode">
					<option id="fp5-vast-vpaidmode-default" value="" <?php if ( isset ( $fp5_stored_meta['fp5-vast-vpaidmode'] ) ) selected( $fp5_stored_meta['fp5-vast-vpaidmode'][0], '' ); ?>>Use global setting</option>
					<option id="fp5-vast-vpaidmode-disabled" value="DISABLED" <?php if ( isset ( $fp5_stored_meta['fp5-vast-vpaidmode'] ) ) selected( $fp5_stored_meta['fp5-vast-vpaidmode'][0], 'disabled' ); ?>>Disabled</option>
					<option id="fp5-vast-vpaidmode-enabled" value="ENABLED" <?php if ( isset ( $fp5_stored_meta['fp5-vast-vpaidmode'] ) ) selected( $fp5_stored_meta['fp5-vast-vpaidmode'][0], 'enabled' ); ?>>Enabled</option>
					<option id="fp5-vast-vpaidmode-insecure" value="INSECURE" <?php if ( isset ( $fp5_stored_meta['fp5-vast-vpaidmode'] ) ) selected( $fp5_stored_meta['fp5-vast-vpaidmode'][0], 'insecure' ); ?>>Insecure</option>
				</select>

				<?php _e( 'Determines VPAID functionality.', $this->plugin_slug ); ?>
			</td>
		</tr>

		<tr class="fp5-vast-redirects" valign="top">
			<th scope="row"><label for="fp5-vast-redirects" class="fp5-row-title"><?php _e( 'Redirects', $this->plugin_slug )?></label></th>
			<td>
				<input type="text" style="width: 60px;" name="fp5-vast-redirects" id="fp5-vast-redirects" value="<?php if ( isset ( $fp5_stored_meta['fp5-vast-redirects'] ) ) echo $fp5_stored_meta['fp5-vast-redirects'][0]; ?>" /><br>

				<?php _e( 'Maximum number of redirects to try before ad load is aborted. Leave empty to use the global setting.', $this->plugin_slug ); ?>
			</td>
		</tr>

		<tr class="fp5-adrules-xml-url" valign="top">
			<th scope="row"><label for="fp5-adrules-xml-url" class="fp5-row-title"><?php _e( 'AdRules URL', $this->plugin_slug )?></label></th>
			<td>
				<input type="text" style="width: 100%;" name="fp5-adrules-xml-url" id="fp5-adrule-xml-url" value="<?php if ( isset ( $fp5_stored_meta['fp5-adrules-xml-url'] ) ) echo esc_url( $fp5_stored_meta['fp5-adrules-xml-url'][0] ); ?>" /><br>

				<?php _e( 'URL pointing to an XML file containing ad rules for this clip. Has no effect if individual ads are configured below for this clip.', $this->plugin_slug ); ?>
			</td>
		</tr>

	</tbody>
</table>

<table class="form-table">
	<thead>
		<tr>
			<th><?php _e( 'Seconds into the video', $this->plugin_slug )?></th>
			<th style="width: auto; padding-left: 10px;"><?php _e( 'adTag URL', $this->plugin_slug )?></th>
			<th style="width: 2%"></th>
		</tr>
	</thead>

	<tfoot>
		<tr class="fp5-ad-description" valign="top">
			<td>
				<a class="button-secondary fp5_add_repeatable"><?php _e( 'Add new ad', $this->plugin_slug ); ?></a>
			</td>
			<td>
			</td>
		</tr>
	</tfoot>

	<tbody>
		<tr class="template fp5-ad-row" valign="top" style="display:none;">
			<td>
				<input type="text" name="fp5_vast_ads[{{row-count-placeholder}}][fp5-vast-ads-time]" id="fp5-vast-ads-time"/>
			</td>
			<td>
				<input type="text" name="fp5_vast_ads[{{row-count-placeholder}}][fp5-vast-ads-url]" id="fp5-vast-ads-url" style="width: 100%;"/>
			</td>
			<td>
				<button class="fp5_remove_repeatable">x</button>
			</td>
		</tr>

		<?php foreach ( $fp5_vast_ads as $key => $value ) : ?>
		<tr class="fp5-ad-row" valign="top">
			<td>
				<input type="text" name="fp5_vast_ads[<?php echo $key; ?>][fp5-vast-ads-time]" id="fp5-ads-time" value="<?php if ( isset ( $value['fp5-vast-ads-time'] ) ) echo esc_attr( $value['fp5-vast-ads-time'] ); ?>" />
			</td>
			<td>
				<input type="text" name="fp5_vast_ads[<?php echo $key; ?>][fp5-vast-ads-url]" id="fp5-vast-ads-url" value="<?php if ( isset ( $value['fp5-vast-ads-url'] ) ) echo esc_attr( $value['fp5-vast-ads-url'] ); ?>" style="width: 100%;" />
			</td>
			<td>
				<button class="fp5_remove_repeatable">x</button>
			</td>
		</tr>
		<?php endforeach; ?>

	</tbody>
</table>
</div>
