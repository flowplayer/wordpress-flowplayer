<!-- Flowplayer Ads config -->
<script>
	flowplayer_ima.conf({
		adsense: {
			request: {
				adtest: "<?php echo esc_attr( $atts['asf_test'] ); ?>"
			}
		},
		ads: [{
			time: "<?php echo esc_attr( $atts['ads_time'] ); ?>",
			request: {
				ad_type: "<?php echo esc_attr( $atts['ad_type'] ); ?>",
				description_url: "<?php echo esc_html( $atts['description_url'] ); ?>"
			}
		}]
	});
</script>
