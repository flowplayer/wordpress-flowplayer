<?php
/**
 * Helper functions
 *
 * @package   Flowplayer 5 for WordPress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      http://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Output video
 *
 * @since    1.9.0
 *
 * @param array $atts
 */
function fp5_video_output( $atts ) {
	$flowplayer5_output = new Flowplayer5_Output;
	return $flowplayer5_output->video_output( $atts );
}

/**
 * Duplicate WordPress functions for capaability before WP 3.6
 */
if ( ! function_exists( 'has_shortcode' ) ) {
	/**
	 * Whether the passed content contains the specified shortcode
	 *
	 * @since 3.6.0
	 *
	 * @global array $shortcode_tags
	 *
	 * @param string $content Content to search for shortcodes.
	 * @param string $tag     Shortcode tag to check.
	 * @return bool Whether the passed content contains the given shortcode.
	 */
	function has_shortcode( $content, $tag ) {
		if ( false === strpos( $content, '[' ) ) {
			return false;
		}
		if ( shortcode_exists( $tag ) ) {
			preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
			if ( empty( $matches ) )
				return false;

			foreach ( $matches as $shortcode ) {
				if ( $tag === $shortcode[2] ) {
					return true;
				} elseif ( ! empty( $shortcode[5] ) && has_shortcode( $shortcode[5], $tag ) ) {
					return true;
				}
			}
		}
		return false;
	}
}

/**
 * Whether the passed content contains the specified shortcode and return args
 *
 * @since 1.10.0
 *
 * @param string $content Content to search for shortcodes.
 * @param string $tag     Shortcode tag to check.
 * @return false|array
 */
function fp5_has_shortcode_arg( $content, $tag ) {

	if ( false === strpos( $content, '[' ) ) {
		return false;
	}

	if ( shortcode_exists( $tag ) ) {
		preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );

		if ( empty( $matches ) ) {
			return false;
		}
		$shortcode_arg = false;
		foreach ( $matches as $shortcode ) {
			if ( $tag === $shortcode[2] ) {
				$shortcode_arg[] = shortcode_parse_atts( $shortcode[3] );
			} elseif ( ! empty( $shortcode[5] ) && has_shortcode( $shortcode[5], $tag ) ) {
				$shortcode_arg = fp5_has_shortcode_arg( $shortcode[5], $tag );
			}
		}
		return $shortcode_arg;
	}
	return false;
}

/**
 * Wrapper for returning all settings
 *
 * @since 2.0.0
 *
 * @return array
 */
function fp5_get_settings() {
	$flowplayer_settings = new Flowplayer5_Settings;
	return $flowplayer_settings->get_all();
}

/**
 * Display post categories form fields.
 *
 * @since 1.14.0
 *
 * @todo Create taxonomy-agnostic wrapper for this.
 *
 * @param WP_Post $post Post object.
 * @param array   $box {
 *     Categories meta box arguments.
 *
 *     @type string   $id       Meta box ID.
 *     @type string   $title    Meta box title.
 *     @type callable $callback Meta box display callback.
 *     @type array    $args {
 *         Extra meta box arguments.
 *
 *         @type string $taxonomy Taxonomy. Default 'category'.
 *     }
 * }
 */
function fp5_playlist_meta_box( $post, $box ) {
	$defaults = array( 'taxonomy' => 'playlist' );
	if ( ! isset( $box['args'] ) || ! is_array( $box['args'] ) ) {
		$args = array();
	} else {
		$args = $box['args'];
	}
	$r = wp_parse_args( $args, $defaults );
	$tax_name = esc_attr( $r['taxonomy'] );
	$taxonomy = get_taxonomy( $r['taxonomy'] );
	?>
	<div id="taxonomy-<?php echo $tax_name; ?>" class="categorydiv">
	<p><?php _e( 'Select one or more playlists below to add this video to them or create a new playlist', 'flowplayer5' ); ?></p>
		<ul id="<?php echo $tax_name; ?>-tabs" class="category-tabs">
			<li class="tabs"><a href="#<?php echo $tax_name; ?>-all"><?php echo $taxonomy->labels->all_items; ?></a></li>
			<li class="hide-if-no-js"><a href="#<?php echo $tax_name; ?>-pop"><?php _e( 'Most Used' ); ?></a></li>
		</ul>

		<div id="<?php echo $tax_name; ?>-pop" class="tabs-panel" style="display: none;">
			<ul id="<?php echo $tax_name; ?>checklist-pop" class="categorychecklist form-no-clear" >
				<?php $popular_ids = wp_popular_terms_checklist( $tax_name ); ?>
			</ul>
		</div>

		<div id="<?php echo $tax_name; ?>-all" class="tabs-panel">
			<?php
			$name = ( $tax_name == 'category' ) ? 'post_category' : 'tax_input[' . $tax_name . ']';
			echo "<input type='hidden' name='{$name}[]' value='0' />"; // Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.
			?>
			<ul id="<?php echo $tax_name; ?>checklist" data-wp-lists="list:<?php echo $tax_name; ?>" class="categorychecklist form-no-clear">
				<?php wp_terms_checklist( $post->ID, array( 'taxonomy' => $tax_name, 'popular_cats' => $popular_ids ) ); ?>
			</ul>
		</div>
	<?php if ( current_user_can( $taxonomy->cap->edit_terms ) ) : ?>
			<div id="<?php echo $tax_name; ?>-adder" class="wp-hidden-children">
				<a id="<?php echo $tax_name; ?>-add-toggle" href="#<?php echo $tax_name; ?>-add" class="hide-if-no-js taxonomy-add-new">
					<?php
						/* translators: %s: add new taxonomy label */
						printf( __( '+ %s' ), $taxonomy->labels->add_new_item );
					?>
				</a>
				<p id="<?php echo $tax_name; ?>-add" class="category-add wp-hidden-child">
					<label class="screen-reader-text" for="new<?php echo $tax_name; ?>"><?php echo $taxonomy->labels->add_new_item; ?></label>
					<input type="text" name="new<?php echo $tax_name; ?>" id="new<?php echo $tax_name; ?>" class="form-required form-input-tip" value="<?php echo esc_attr( $taxonomy->labels->new_item_name ); ?>" aria-required="true"/>
					<input type="button" id="<?php echo $tax_name; ?>-add-submit" data-wp-lists="add:<?php echo $tax_name; ?>checklist:<?php echo $tax_name; ?>-add" class="button category-add-submit" value="<?php echo esc_attr( $taxonomy->labels->add_new_item ); ?>" />
					<?php wp_nonce_field( 'add-' . $tax_name, '_ajax_nonce-add-' . $tax_name, false ); ?>
					<span id="<?php echo $tax_name; ?>-ajax-response"></span>
				</p>
			</div>
		<?php endif; ?>
	</div>
	<?php
}
