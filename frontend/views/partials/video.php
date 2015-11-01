<div id="flowplayer-video-<?php echo absint( $id ); ?>" style="<?php echo esc_attr( self::trim_implode( $style ) ); ?>" class="<?php echo esc_attr( self::trim_implode( $classes ) ); ?>" <?php echo fp5_deprecated_flowplayer_data( self::trim_implode( $data_config ) ); ?>>

	<?php if ( $asf_js ) {
		require( 'ads-script.php' );
	} ?>

	<?php do_action( 'fp5_video_top', $id ); ?>

	<video <?php echo self::process_data_config( $video_data_config ); ?><?php echo self::trim_implode( $attributes ); ?>>
		<?php echo self::trim_implode( $source ); ?>
		<?php echo esc_textarea( $track ); ?>
	</video>

	<?php do_action( 'fp5_video_bottom', $id ); ?>

	<?php if ( $lightbox ) { ?>
		<button title="<?php _e( 'Close (Esc)', 'flowplayer5' )?>" type="button" class="mfp-close">×</button>
	<?php } ?>

</div>

<?php require( 'video-script.php' ); ?>
