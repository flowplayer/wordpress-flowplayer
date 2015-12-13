<?php
	$video_formats = array(
		'wemb'  => 'video/webm',
		'mp4'   => 'video/mp4',
		'ogg'   => 'video/ogg',
		'flash' => 'video/flash',
		'hls' => 'application/x-mpegurl',
	);
	// https://flowplayer.org/docs/playlist.html#javascript-install
	foreach ( $atts as $video_id => $video ) {
		$sources = array();
		foreach ( $video_formats as $format => $type ) {
			if ( ! empty( $video['src'][ $type ] ) ) {
				$sources[] = array(
					'type' => esc_attr( $type ),
					'src' => esc_attr( $video['src'][ $type ] ),
				);
			}
		}
		$video['js_config']['sources'] = $sources;
		$return[] = $video['js_config'];
	}
?>
<script>
var Playlist<?php echo esc_attr( $playlist_id ); ?> = <?php echo json_encode( $return ); ?>;
var fpPlaylist<?php echo absint( $playlist_id ); ?> = flowplayer('#jsplaylist<?php echo absint( $playlist_id ); ?>', {
	playlist: Playlist<?php echo absint( $playlist_id ); ?>
});
</script>
