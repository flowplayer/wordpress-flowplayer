<?php
/**
 * Flowplayer 5 for WordPress
 *
 * @package   Flowplayer5_Taxonomy_Meta
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      https://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Custom Taxonomy Meta class.
 *
 * @package Flowplayer5_Taxonomy_Meta
 * @author  Ulrich Pogson <ulrich@pogson.ch>
 */
class Flowplayer5_Taxonomy_Meta {

	/**
	 * Initializes the differnt functions
	 *
	 * @since     1.9.0
	 */
	public function __construct() {

		add_action( 'admin_head-edit-tags.php', array( $this, 'remove_category_fields' ) );
		add_action( 'save_post', array( $this, 'video_save' ), 10, 2 );
		add_action( 'edited_playlist', array( $this, 'save_taxonomy_custom_meta' ), 10, 2 );
		add_action( 'create_playlist', array( $this, 'save_taxonomy_custom_meta' ), 10, 2 );
		add_action( 'playlist_add_form_fields', array( $this, 'taxonomy_add_new_meta_field' ), 10, 2 );
		add_action( 'delete_playlist', array( $this, 'taxonomy_delete_meta_field' ), 10, 2 );
		add_action( 'playlist_edit_form_fields', array( $this, 'taxonomy_edit_meta_field' ), 10, 2 );
		add_filter( 'manage_edit-playlist_columns', array( $this, 'add_playlist_columns' ) );
		add_filter( 'manage_playlist_custom_column', array( $this, 'add_playlist_column_content' ), 10, 3 );
		add_action( 'split_shared_term', array( $this, 'update_playlist_order_for_split_terms' ), 10, 4 );

	}

	/**
	 * Remove redudant fields.
	 *
	 * Remove the description field and parent selectbox
	 *
	 * @since     1.9.0
	 */
	public function remove_category_fields() {

		$screen = get_current_screen();

		if ( 'playlist' != $screen->taxonomy ) {
			return;
		}

		// New Category
		$parent = 'parent()';

		// Edit Category
		if ( isset( $_GET['action'] ) ) {
			$parent = 'parent().parent()';
		}

		?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('label[for=parent]').<?php echo $parent; ?>.remove();
					$('label[for=tag-description]').<?php echo $parent; ?>.remove();
					$('textarea[id=description]').<?php echo $parent; ?>.remove();
					jQuery('.fp5-product-order').sortable();
				});
			</script>
	<?php
	}

	/**
	 * Add Shortcode column to taxonomy
	 *
	 * @since     1.9.0
	 */
	function add_playlist_columns( $columns ){
		unset($columns['description']);
		$columns['shortcode'] = __( 'Shortcode', 'flowplayer5' );
		return $columns;
	}

	/**
	 * Add content to shortcode column
	 *
	 * @since     1.9.0
	 */
	function add_playlist_column_content( $content, $column_name, $term_id ){
		switch ( $column_name ) {

			case 'shortcode' :
				$content = '[flowplayer playlist="' . $term_id . '"]';
				break;

		}
		return $content;
	}

	/**
	 * Add meta fields to add taxonomy page
	 *
	 * @since     1.9.0
	 */
	public function taxonomy_add_new_meta_field() {
		// this will add the custom meta field to the add new term page
		?>
		<div class="form-field fp5-select-skin">
			<label for="term_meta[fp5-select-skin]"><?php _e( 'Select skin', 'flowplayer5' ); ?></label>
			<select id="term_meta[fp5-select-skin]" name="term_meta[fp5-select-skin]">
				<option id="fp5-minimalist" value="minimalist" <?php if ( isset ( $term_meta['fp5-select-skin'] ) ) selected( $term_meta['fp5-select-skin'], 'minimalist' ); ?>>Minimalist</option>
				<option id="fp5-functional" value="functional" <?php if ( isset ( $term_meta['fp5-select-skin'] ) ) selected( $term_meta['fp5-select-skin'] , 'functional' ); ?>>Functional</option>
				<option id="fp5-playful" value="playful" <?php if ( isset ( $term_meta['fp5-select-skin'] ) ) selected( $term_meta['fp5-select-skin'] , 'playful' ); ?>>Playful</option>
			</select>
			<p class="description"><?php _e( 'Select a skin for the playlist', 'flowplayer5' ); ?></p>
		</div>
		<div class="form-field fp5-rtmp-url">
			<label for="term_meta[fp5-rtmp-url]"><?php _e( 'Add RTMP net connection URL', 'flowplayer5' ); ?></label>
			<input class="media-url" type="text" name="term_meta[fp5-rtmp-url]" id="term_meta[fp5-rtmp-url]" value="<?php if ( isset ( $term_meta['fp5-rtmp-url'] ) ) echo esc_attr( $term_meta['fp5-rtmp-url'] ); ?>" />
			<p class="description"><?php _e( 'Optional RTMP net connection URL for all of the videos in the playlist', 'flowplayer5' ); ?></p>
		</div>
	<?php
	}

	/**
	 * Add meta fields to edit taxonomy page
	 *
	 * @since     1.9.0
	 */
	public function taxonomy_edit_meta_field( $term, $taxonomy ) {

		// put the term ID into a variable
		$t_id = $term->term_id;

		// retrieve the existing value(s) for this meta field. This returns an array
		$term_meta = get_option( "playlist_$t_id" ); ?>
		<tr class="form-field fp5-select-skin">
			<th scope="row" valign="top"><label for="term_meta[fp5-select-skin]"><?php _e( 'Select skin', 'flowplayer5' ); ?></label></th>
			<td>
				<select id="term_meta[fp5-select-skin]" name="term_meta[fp5-select-skin]">
					<option id="fp5-minimalist" value="minimalist" <?php if ( isset ( $term_meta['fp5-select-skin'] ) ) selected( $term_meta['fp5-select-skin'], 'minimalist' ); ?>>Minimalist</option>
					<option id="fp5-functional" value="functional" <?php if ( isset ( $term_meta['fp5-select-skin'] ) ) selected( $term_meta['fp5-select-skin'] , 'functional' ); ?>>Functional</option>
					<option id="fp5-playful" value="playful" <?php if ( isset ( $term_meta['fp5-select-skin'] ) ) selected( $term_meta['fp5-select-skin'] , 'playful' ); ?>>Playful</option>
				</select>
				<p class="description"><?php _e( 'Select a skin for the playlist', 'flowplayer5' ); ?></p>
			</td>
		</tr>
		<tr class="form-field fp5-rtmp-url">
			<th scope="row" valign="top"><label for="term_meta[fp5-rtmp-url]"><?php _e( 'Add RTMP net connection URL', 'flowplayer5' ); ?></label></th>
			<td>
				<input class="media-url" type="text" name="term_meta[fp5-rtmp-url]" id="term_meta[fp5-rtmp-url]" value="<?php if ( isset ( $term_meta['fp5-rtmp-url'] ) ) echo esc_attr( $term_meta['fp5-rtmp-url'] ); ?>" />
				<p class="description"><?php _e( 'Optional RTMP net connection URL for all of the videos in the playlist', 'flowplayer5' ); ?></p>
			</td>

		</tr>
	<?php
		// WP_Query arguments
		$args = array(
			'post_type'      => 'flowplayer5',
			'posts_per_page' => '100',
			'no_found_rows'  => true,
			'orderby'        => 'meta_value_num',
			'meta_key'       => $taxonomy . '_order_' . $t_id,
			'tax_query'      => array(
				array(
					'taxonomy' => 'playlist',
					'field'    => 'id',
					'terms'    => $t_id,
				),
			),
		);

		// The Query
		$products = new WP_Query( $args );

		if ( $products ) { ?>
			<tr class="form-field">
			<th scope="row" valign="top"><label id="<?php echo $taxonomy . '_order_' . $t_id; ?>"><?php _e( 'Order Videos', 'flowplayer5' ); ?></label></th>
				<td>
					<ul class="fp5-product-order">
						<?php // The Loop
							if ( $products->have_posts() ) {
								while ( $products->have_posts() ) {
									$products->the_post();
									?>
									<li>
										<span><?php echo get_the_title(); ?></span>
										<input type="hidden" name="post_order[]" value="<?php echo get_the_ID(); ?>" />
									</li>
									<?php
								}
							} else {
								// no posts found
							}

							// Restore original Post Data
							wp_reset_postdata();?>
					</ul>
				</td>
			</tr>
		<?php }
	}


	/**
	 * Add a default playlist order when saving a video
	 *
	 * @since     1.9.0
	 */
	public function video_save( $post_ID, $post ) {
		if ( 'flowplayer5' == $post->post_type ) {
			$meta = get_post_custom( $post_ID );
			$terms = wp_get_object_terms( $post_ID, 'playlist' );
			foreach ( $terms as $term ) {
				if ( ! array_key_exists( 'playlist_order_' . $term->term_id, $meta ) ) {
					update_post_meta(
						$post_ID,
						'playlist_order_'. $term->term_id,
						absint( $post_ID )
					);
				}
			}
		}
	}

	/**
	 * Save extra taxonomy fields callback function.
	 *
	 * @since     1.9.0
	 */
	public function save_taxonomy_custom_meta( $term_id, $tt_id ) {
		if ( isset( $_POST['term_meta'] ) ) {

			$term_meta = get_option( 'playlist_' . $term_id );
			$cat_keys = array_keys( $_POST['term_meta'] );

			foreach ( $cat_keys as $key ) {
				if ( isset ( $_POST['term_meta'][ $key ] ) ) {
					$term_meta[ $key ] = sanitize_text_field( $_POST['term_meta'][ $key ] );
				}
			}
			// Save the option array.
			update_option(
				'playlist_' . $term_id,
				$term_meta
			);
		}
		if ( is_array( $_POST['post_order'] ) ) {
			foreach ( $_POST['post_order'] as $key => $id ) {
				update_post_meta(
					absint( $id ),
					'playlist_order_' . absint( $term_id ),
					absint( count( $_POST['post_order'] ) - $key )
				);
			}
		}
	}

	/**
	 * Delete the options when deleting a playlist
	 *
	 * @since     1.9.0
	 */
	public function taxonomy_delete_meta_field( $term, $tt_id, $deleted_term ) {
		delete_option( 'playlist_' . $term );
	}

	/**
	 * Update options after a previously shared taxonomy term is split into two separate terms.
	 *
	 * @since     1.10.7
	 */
	public function update_playlist_order_for_split_terms( $old_term_id, $new_term_id, $term_taxonomy_id, $taxonomy ) {
		$playlist_order = get_option( 'playlist_' . $old_term_id );

		// Check to see whether the stored tag ID is the one that's just been split.
		if ( isset( $playlist_order ) && $old_term_id !== $new_term_id && 'playlist' == $taxonomy ) {
			// We have a match, so we save a new option with the new id.
			update_option( 'playlist_' . $new_term_id, $playlist_order );
			delete_option( 'playlist_' . $old_term_id );
		}
	}

}
