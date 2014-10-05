<div style="<?php esc_attr( self::trim_implode( $style ) ); ?>" class="<?php esc_attr( self::trim_implode( $classes ) ); ?>" <?php esc_textarea( apply_filters( 'fp5_filter_flowplayer_data', self::trim_implode( $data ) ) ); ?>>

	<?php if ( $asf_js ) : ?>
		<!-- Flowplayer Ads -->
		<script>
			flowplayer_ima.conf({
				adsense: {
					request: {
						adtest: "<?php echo esc_attr( $asf_test ); ?>"
					}
				},
				ads: [{
					time: "<?php echo esc_attr( $ads_time ); ?>",
					request: {
						ad_type: "<?php echo esc_attr( $ad_type ); ?>"
					}
				}]
			});
		</script>
	<?php endif; ?>

	<?php do_action( 'fp5_video_top' ); ?>

	<video <?php esc_textarea( self::trim_implode( $attributes ) ); ?>>
		<?php esc_textarea( self::trim_implode( $source ) ); ?>
		<?php echo esc_textarea( $track ); ?>
	</video>

	<?php do_action( 'fp5_video_bottom' ); ?>

</div>

<!-- Flowplayer Single Config -->
<script>
jQuery(function($) {
	$(".flowplayer-video-<?php echo esc_attr( $id ); ?>").flowplayer({
		<?php do_action( 'fp5_video_config' ); ?>
		adaptiveRatio: <?php echo esc_attr( $adaptive_ratio ); ?>,
		embed: <?php echo esc_attr( $embed ); ?>
	});
});
</script>
