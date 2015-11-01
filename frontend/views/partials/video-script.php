<!-- Flowplayer single video config -->
<script>
jQuery( document ).ready( function( $ ) {
	$(".flowplayer-video-<?php echo esc_attr( $id ); ?>").flowplayer({
		<?php do_action( 'fp5_video_config', $id ); ?>
		<?php echo self::process_js_config( $js_config ); ?>
	});
});
</script>
