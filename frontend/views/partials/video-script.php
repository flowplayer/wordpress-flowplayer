<!-- Flowplayer single video config -->
<script>
jQuery( document ).ready( function( $ ) {
	fpVideo<?php echo absint( $atts['id'] ); ?> = $(".flowplayer-video-<?php echo esc_attr( $atts['id'] ); ?>").flowplayer(
		<?php echo json_encode( array_merge( $atts['clip_config'], $atts['player_config'] ) ); ?>
	);
});
</script>
