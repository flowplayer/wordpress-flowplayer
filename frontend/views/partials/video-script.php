<!-- Flowplayer single video config -->
<script>
jQuery( document ).ready( function( $ ) {
	$(".flowplayer-video-<?php echo esc_attr( $atts['id'] ); ?>").flowplayer({
		<?php do_action( 'fp5_video_config', $atts['id'] ); ?>
		<?php echo self::process_js_config( $atts['js_config'] ); ?>
	});
});
</script>
