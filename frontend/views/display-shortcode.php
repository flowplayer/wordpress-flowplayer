
<div style="<?php echo esc_attr( trim( implode( ' ', $style ) ) ); ?>" class="<?php echo esc_attr( trim( implode( ' ', $classes ) ) ); ?>" <?php apply_filters( 'fp5_filter_flowplayer_data', esc_attr( trim( implode( ' ', $data ) ) ) ); ?>>

	<script>
		flowplayer_ima.conf({
			adsense: {
				request: {
					adtest: "<?php echo esc_attr( $asf_test ); ?>"
				}
			},
			ads: [{
				time: <?php echo esc_attr( $ads_time ); ?>,
				request: {
					ad_type: "<?php echo esc_attr( $ad_type ); ?>"
				}
			}]
		});
	</script>

	<?php do_action( 'fp5_video_top' ); ?>

	<video <?php echo esc_attr( trim( implode( ' ', $attributes ) ) ); ?>>
		<?php echo implode( '', $source ); ?>
		<?php echo $track; ?>
	</video>

	<?php do_action( 'fp5_video_bottom' ); ?>

</div>

<script>
	flowplayer.conf = {
		<?php echo esc_attr( $adaptive_ratio ); ?>
		<?php echo esc_attr( $embed ); ?>
	};
</script>
