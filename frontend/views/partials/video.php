<div id="flowplayer-video-<?php echo absint( $atts['id'] ); ?>" style="<?php echo esc_attr( self::process_css_array( $atts['style'] ) ); ?>" class="<?php echo esc_attr( self::trim_implode( $atts['player_classes'] ) ) . ' ' . esc_attr( self::trim_implode( $atts['classes'] ) ); ?>" <?php echo fp5_deprecated_flowplayer_data( self::trim_implode( $atts['data_config'] ) ); ?>>

	<?php do_action( 'fp5_video_top', $atts['id'] ); ?>

	<video <?php echo self::process_data_config( $atts['video_data_config'] ); ?><?php echo self::trim_implode( $atts['attributes'] ); ?>>
		<?php
			foreach ( $atts['formats'] as $format => $src ) {
				$src = apply_filters( 'fp5_filter_video_src', $src, $format, $atts['id'] );
				if ( ! empty( $src ) ) {
					echo '<source type="' . esc_attr( $format ) . '" src="' . esc_attr( $src ) . '">';
				}
			}
			if ( ! empty( $atts['subtitles'] ) ) {
				echo '<track src="' . esc_url( $atts['subtitles'] ) . '"/>';
			}
		?>
	</video>

	<?php do_action( 'fp5_video_bottom', $atts['id'] ); ?>

	<?php if ( $atts['lightbox'] ) { ?>
		<button title="<?php _e( 'Close (Esc)', 'flowplayer5' )?>" type="button" class="mfp-close">×</button>
	<?php } ?>

</div>

<?php require( 'video-script.php' ); ?>
