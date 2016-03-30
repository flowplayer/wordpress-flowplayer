<!-- Flowplayer VAST config -->
<script>
  <?php if( ( !empty( $atts['vast_ads_time'] ) || !empty( $atts['vast_ads_tag'] ) ) && $atts['vast_disable'] != 'true' ) { ?>
    flowplayer_ima.conf({
      ads: [{
        time: "<?php echo esc_attr( $atts['vast_ads_time'] ); ?>",
        adTag: "<?php echo esc_html( $atts['vast_ads_tag'] ); ?>"
      }]
    });
  <?php } else { ?>
    flowplayer_ima.conf({
      ads: null
    });
  <?php } ?>
</script>
