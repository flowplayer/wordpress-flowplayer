<a href="#flowplayer-video-<?php echo absint( $atts['id'] ); ?>" class="open-popup-link">
<?php
	if ( 'link' == $atts['lightbox'] && isset( $atts['video_data_config']['title'] ) ) {
		echo esc_attr( $atts['video_data_config']['title'] );
	} elseif ( 'thumbnail' == $atts['lightbox'] && isset( $atts['video_data_config']['title'] ) ) { ?>
		<img src="<?php echo esc_url( $splash ); ?>" alt="<?php echo esc_attr( $atts['video_data_config']['title'] ); ?>" height="<?php echo esc_attr( $atts['height'] ); ?>" width="<?php echo esc_attr( $atts['width'] ); ?>">
	<?php } else {
		_e( 'Click to open video', 'flowplayer5' );
	} ?>
</a>
<div id="flowplayer-video-<?php echo absint( $atts['id'] ); ?>" class="mfp-hide">
<?php require( 'video.php' ); ?>
</div>
<?php require( 'lightbox-script.php' ); ?>
