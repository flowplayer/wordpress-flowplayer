<!-- Flowplayer Ads config -->
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
				ad_type: "<?php echo esc_attr( $ad_type ); ?>",
				description_url: "<?php echo esc_html( $description_url ); ?>"
			}
		}]
	});
</script>
