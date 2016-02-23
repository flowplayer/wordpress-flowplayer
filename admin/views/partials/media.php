<div id="media">
	<p style="width:70%;float:left;">
		<?php _e( 'Add at least two video formats so that the video plays on as many browsers as possible. To be able to calculate the dimensions of the video the video format added needs to be compatible with the current browser.', $this->plugin_slug ) ?> <a href="https://flowplayer.org/docs/setup.html#video-formats" target="_blank"><?php _e( 'About video formats.', $this->plugin_slug ) ?></a>
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
			<th scope="row"><?php _e( 'Flowplayer Drive', $this->plugin_slug )?> <a href="https://flowplayer.org/" target="_blank">?</a></th>
			<td>
				<a href="#flowplayer-drive" class="fp5-add-drive button button-primary cboxElement" title="<?php _e( 'Add from Flowplayer Drive', $this->plugin_slug )?>"><?php _e( 'Add from Flowplayer Drive', $this->plugin_slug )?></a>
			</td>
		</tr>

		<tr class="fp5-splash-image" valign="top">
			<th scope="row"><label for="fp5-splash-image"><?php _e( 'Splash Image', $this->plugin_slug )?></label> <a href="https://flowplayer.org/docs/setup.html#splash" target="_blank">?</a></th>
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
			<th scope="row"><label for="fp5-flash-video"><?php _e( 'Flash Video', $this->plugin_slug )?></label> <a href="https://flowplayer.org/docs/setup.html#flash-video" target="_blank">?</a></th>
			<td>
				<input class="media-url" type="text" name="fp5-flash-video" id="fp5-flash-video" placeholder="<?php _e( 'Add Flash video url', $this->plugin_slug )?>" value="<?php if ( isset ( $fp5_stored_meta['fp5-flash-video'] ) ) echo esc_attr( $fp5_stored_meta['fp5-flash-video'][0] ); ?>" />
				<a href="#" class="fp5-add-flash button button-primary" title="<?php _e( 'Add Flash video', $this->plugin_slug )?>"><?php _e( 'Add Flash video', $this->plugin_slug )?></a>
			</td>
		</tr>

		<tr class="fp5-hls-video hidden advance" valign="top">
			<th scope="row"><label for="fp5-hls-video"><?php _e( 'HLS Video', $this->plugin_slug )?></label> <a href="https://flowplayer.org/docs/setup.html#video-formats" target="_blank">?</a></th>
			<td>
				<input class="media-url" type="text" name="fp5-hls-video" id="fp5-hls-video" onblur="switchHLSCheckbox(this.value)" placeholder="<?php _e( 'Add HLS video url', $this->plugin_slug )?>" value="<?php if ( isset ( $fp5_stored_meta['fp5-hls-video'] ) ) echo esc_attr( $fp5_stored_meta['fp5-hls-video'][0] ); ?>" />
				<a href="#" class="fp5-add-hls button button-primary" title="<?php _e( 'Add HLS video', $this->plugin_slug )?>"><?php _e( 'Add HLS video', $this->plugin_slug )?></a>
			</td>
		</tr>

		<tr class="fp5-vtt-subtitles" valign="top">
			<th scope="row"><label for="fp5-vtt-subtitles"><?php _e( 'VVT file (Subtitles)', $this->plugin_slug )?></label> <a href="https://flowplayer.org/docs/subtitles.html" target="_blank">?</a></th>
			<td>
				<input class="media-url" type="text" name="fp5-vtt-subtitles" id="fp5-vtt-subtitles" placeholder="<?php _e( 'Add VVT file url', $this->plugin_slug )?>" value="<?php if ( isset ( $fp5_stored_meta['fp5-vtt-subtitles'] ) ) echo esc_url( $fp5_stored_meta['fp5-vtt-subtitles'][0] ); ?>" />
				<a href="#" class="fp5-add-vtt button button-primary" title="<?php _e( 'Add VVT file', $this->plugin_slug )?>"><?php _e( 'Add VVT file', $this->plugin_slug )?></a>
			</td>

		<tr class="fp5-data-rtmp hidden advance" valign="top">
			<th scope="row"><label for="fp5-data-rtmp"><?php _e( 'RTMP URL', $this->plugin_slug )?></label> <a href="https://flowplayer.org/docs/setup.html#server-side" target="_blank">?</a></th>
			<td>
				<input class="media-url" type="text" name="fp5-data-rtmp" id="fp5-data-rtmp" placeholder="<?php _e( 'Add rtmp value', $this->plugin_slug )?>" value="<?php if ( isset ( $fp5_stored_meta['fp5-data-rtmp'] ) ) echo esc_attr( $fp5_stored_meta['fp5-data-rtmp'][0] ); ?>" />
			</td>
		</tr>

		</tr>
	</tbody>
</table>
</div>
