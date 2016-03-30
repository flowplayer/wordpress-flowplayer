<div id="flowplayer-video-<?php echo absint( $atts['id'] ); ?>" style="<?php echo esc_attr( self::trim_implode( $atts['style'] ) ); ?>" class="<?php echo esc_attr( self::trim_implode( $atts['classes'] ) ); ?>" <?php echo fp5_deprecated_flowplayer_data( self::trim_implode( $atts['data_config'] ) ); ?>>

	<?php if ( $atts['asf_js'] ) {
		require( 'ads-script.php' );
	} ?>

	<?php if ( $atts['vast_js'] ) {
		require( 'vast-script.php' );
	} ?>

	<?php do_action( 'fp5_video_top', $atts['id'] ); ?>

	<video <?php echo self::process_data_config( $atts['video_data_config'] ); ?><?php echo self::trim_implode( $atts['attributes'] ); ?>>
		<?php echo self::trim_implode( $atts['source'] ); ?>
		<?php echo esc_textarea( $atts['track'] ); ?>
	</video>

	<?php do_action( 'fp5_video_bottom', $atts['id'] ); ?>

	<?php if ( $atts['lightbox'] ) { ?>
		<button title="<?php _e( 'Close (Esc)', 'flowplayer5' )?>" type="button" class="mfp-close">×</button>
	<?php } ?>

</div>

<?php require( 'video-script.php' ); ?>
