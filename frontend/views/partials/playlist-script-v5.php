<?php
$video_formats = array(
	'webm'  => 'video/webm',
	'mp4'   => 'video/mp4',
	'ogg'   => 'video/ogg',
	'flash' => 'video/flash',
);

// https://flowplayer.org/v5docs/playlist.html#javascript-playlists
foreach ( $atts as $video_id => $video ) {
	$sources = array();
	foreach ( $video_formats as $format => $type ) {
		if ( ! empty( $video['formats'][ $type ] ) ) {
			$sources[] = array(
				esc_attr( $format ) => esc_attr( $video['formats'][ $type ] ),
			);
		}
	}
	$return[] = $sources;
}
?>
<script>
(function($) {
	var Playlist<?php echo esc_attr( $first_video['playlist'] ); ?> = <?php echo json_encode( $return ); ?>;
	var fpPlaylist<?php echo absint( $first_video['playlist'] ); ?> = $("#jsplaylist<?php echo absint( $first_video['playlist'] ); ?>").flowplayer({
		rtmp: "<?php echo esc_attr( $first_video['playlist_options']['fp5-rtmp-url'] ); ?>",
		playlist: Playlist<?php echo absint( $first_video['playlist'] ); ?>
	});
})(jQuery);
</script>