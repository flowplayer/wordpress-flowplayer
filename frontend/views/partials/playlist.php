<div id="jsplaylist<?php echo absint( $playlist_id ); ?>" class="flowplayer-playlist flowplayer-playlist-<?php echo absint( $playlist_id ). ' ' . esc_attr( $playlist_options['fp5-select-skin'] ); ?>">
	<?php do_action( 'fp5_playlist_top', $playlist_id, $atts ); ?>
	<a class="fp-prev"><?php _e( '&lt; Prev', 'flowplayer5' ); ?></a>
	<a class="fp-next"><?php _e( 'Next &gt;', 'flowplayer5' ); ?></a>
	<?php do_action( 'fp5_playlist_bottom', $playlist_id, $atts ); ?>
</div>

<?php
$current = current( $atts );
if ( 'fp6' === $current['fp_version'] ) {
	require( 'playlist-script-v6.php' );
} else {
	require( 'playlist-script-v5.php' );
} ?>