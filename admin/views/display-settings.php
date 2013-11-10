<?php
/**
 * Display Settings
 *
 * @package   Flowplayer 5 for WordPress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      http://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 */
?>
<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<div class="fp5-main">
		<?php settings_errors( 'fp5-notices' ); ?>
		<form method="post" action="options.php">
			<?php
			// This prints out all hidden setting fields
			settings_fields( 'fp5_settings_group' );
			do_settings_sections( 'flowplayer5_settings' );
			submit_button();
			?>
		</form>
	</div>

	<div class="fp5-sidebar">
		<div id="side-sortables" class="ui-sortable metabox-holder">
			<div id="fp5_about" class="postbox">
			<h3 class="hndle"><span><?php _e( 'About Flowplayer 5', 'flowplayer5' ); ?></span></h3>
				<div class="inside">
					<p><a target="_blank" title="Flowplayer 5 for WordPress FAQ" href="http://wordpress.org/plugins/flowplayer5/faq/"><?php _e( 'FAQ', 'flowplayer5' ); ?></a></p>
					<p><a target="_blank" title="Flowplayer 5 for WordPress Support Forum" href="http://wordpress.org/support/plugin/flowplayer5/"><?php _e( 'Support Forum', 'flowplayer5' ); ?></a></p>
					<p><a target="_blank" title="Review Flowplayer 5 for WordPress" href="http://wordpress.org/support/view/plugin-reviews/flowplayer5/"><?php _e( 'Rate us on WordPress.org', 'flowplayer5' ); ?></a></p>
					<p><a target="_blank" title="Flowplayer 5 for WordPress Changelog" href="http://wordpress.org/plugins/flowplayer5/changelog/"><?php _e( 'Changelog', 'flowplayer5' ); ?></a></p>
					<p><a target="_blank" title="Follow Flowplayer on Twitter" href="http://twitter.com/flowplayer/"><?php _e( 'Follow us on Twitter', 'flowplayer5' ); ?></a></p>
					<p><a target="_blank" title="Like Flowplayer on Facebook" href="https://www.facebook.com/flowplayer"><?php _e( 'Like us on Facebook', 'flowplayer5' ); ?></a></p>
					<p><a target="_blank" title="Flowplayer Website" href="http://flowplayer.org/"><?php _e( 'Flowplayer Website', 'flowplayer5' ); ?></a></p>
				</div>
			</div>
		</div>
	</div>

</div>