<div id="jsplaylist" class="<?php echo esc_attr( $playlist_options['fp5-select-skin'] ); ?>">
	<a class="fp-prev"><?php _e( '&lt; Prev', 'flowplayer5' ); ?></a>
	<a class="fp-next"><?php _e( 'Next &gt;', 'flowplayer5' ); ?></a>
</div>

<script>

	jQuery(function() {

		var allVideos = [
			<?php
			// WP_Query arguments
			$args = array(
				'post_type'   => 'flowplayer5',
				'post_status' => 'publish',
				'orderby'     => 'meta_value_num',
				'meta_key'    => 'playlist_order_' . esc_attr( $playlist ),
				'tax_query'   => array(
					array(
						'taxonomy' => 'playlist',
						'field'    => 'id',
						'terms'    => esc_attr( $playlist ),
					),
				),
				'cache_results'          => true,
				'update_post_meta_cache' => true,
				'update_post_term_cache' => true,
			);

			// The Query
			$query = new WP_Query( $args );

			// The Loop
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					?>
					[
						{webm: "<?php echo esc_url( get_post_meta( get_the_ID(), 'fp5-webm-video', true ) ); ?>"},
						{mp4: "<?php echo esc_url( get_post_meta( get_the_ID(), 'fp5-mp4-video', true ) ); ?>"},
						{ogg: "<?php echo esc_url( get_post_meta( get_the_ID(), 'fp5-ogg-video', true ) ); ?>"},
						{flash: "<?php echo esc_url( get_post_meta( get_the_ID(), 'fp5-flash-video', true ) ); ?>"}
					],
					<?php
				}
			}

			// Restore original Post Data
			wp_reset_postdata();
			?>
		];

		jQuery("#jsplaylist").flowplayer({
			rtmp: "<?php echo esc_attr( $playlist_options['fp5-rtmp-url'] ); ?>",
			//ratio: 9/16,
			playlist: allVideos,
		});
	});
</script>
