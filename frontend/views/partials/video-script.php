<!-- Flowplayer single video config -->
<?php
$options = fp5_get_settings();
?>

<script>
jQuery( document ).ready( function( $ ) {
	var fpVideo<?php echo absint( $atts['id'] ); ?> = $(".flowplayer-video-<?php echo esc_attr( $atts['id'] ); ?>").flowplayer(
		<?php do_action( 'fp5_video_config', $atts['id'] ); ?>
		<?php echo json_encode( $atts['js_config'] ); ?>
	);
});
</script>
