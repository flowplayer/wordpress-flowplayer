<!-- Flowplayer single video config -->
<script>
jQuery( document ).ready( function( $ ) {
	fpVideo<?php echo absint( $atts['id'] ); ?> = $(".flowplayer-video-<?php echo esc_attr( $atts['id'] ); ?>").flowplayer(
		<?php echo json_encode( $atts['js_config'] ); ?>
	);
});
</script>
