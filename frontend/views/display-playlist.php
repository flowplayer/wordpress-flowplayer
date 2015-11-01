<div id="jsplaylist<?php echo absint( $playlist_id ); ?>" class="flowplayer-playlist flowplayer-playlist-<?php echo absint( $playlist_id . ' ' . $playlist_options['fp5-select-skin'] ); ?>">
	<a class="fp-prev"><?php _e( '&lt; Prev', 'flowplayer5' ); ?></a><a class="fp-next"><?php _e( 'Next &gt;', 'flowplayer5' ); ?></a>
</div>
<script>
(function($) {
	var Playlist<?php echo esc_attr( $playlist_id ); ?> = [
		<?php
		// WP_Query arguments
		$args = array(
			'post_type'      => 'flowplayer5',
			'post_status'    => 'publish',
			'orderby'        => 'meta_value_num',
			'posts_per_page' => '-1',
			'meta_key'       => 'playlist_order_' . absint( $playlist_id ),
			'tax_query'      => array(
				array(
					'taxonomy' => 'playlist',
					'field'    => 'id',
					'terms'    => absint( $playlist_id ),
				),
			),
			'cache_results'          => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => true,
		);

		// The Query
		$query = new WP_Query( $args );
		$formats = array(
			'wemb',
			'mp4',
			'ogg',
			'flash'
		);
		// The Loop
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post(); ?>
				[
					<?php foreach ( $formats as $format ) {
						$format_url = get_post_meta( get_the_ID(), 'fp5-' . $format . '-video', true );
						if ( ! empty( $format_url ) ) {
							$return[] = '{' . $format . ': "' . esc_url( get_post_meta( get_the_ID(), 'fp5-' . $format . '-video', true ) ) . '"},';
						}
					}
					echo implode( '', $return );
					?>
				],
		<?php }
		}

		// Restore original Post Data
		wp_reset_postdata();
		?>
	];
	$("#jsplaylist<?php echo absint( $playlist_id ); ?>").flowplayer({
		rtmp: "<?php echo esc_attr( $playlist_options['fp5-rtmp-url'] ); ?>",
		playlist: Playlist<?php echo absint( $playlist_id ); ?>
	});
})(jQuery);
</script>
