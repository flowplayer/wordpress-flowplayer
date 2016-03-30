<!-- Flowplayer single video config -->
<?php
$options = fp5_get_settings();
$vast_js = ( ! empty ( $options['vast_js'] ) ? $options['vast_js'] : '' );
echo $vast_js;
?>

<script>
jQuery( document ).ready( function( $ ) {
	var fpVideo<?php echo absint( $atts['id'] ); ?> = $(".flowplayer-video-<?php echo esc_attr( $atts['id'] ); ?>").flowplayer(
		<?php do_action( 'fp5_video_config', $atts['id'] ); ?>
		<?php echo json_encode( $atts['js_config'] ); ?>
	);
});
</script>
