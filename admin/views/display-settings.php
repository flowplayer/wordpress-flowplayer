<?php
/**
 * Display Settings
 *
 * @package   Flowplayer 5 for WordPress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      https://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 */
?>
<div class="wrap">

	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<?php settings_errors( 'fp5-notices' ); ?>

	<div>
		<div id="post-body" class="metabox-holder columns-2">

			<div class="post-body-content fp5-main">
				<form method="post" action="options.php">
					<?php
					// This prints out all hidden setting fields
					settings_fields( 'fp5_settings_group' );
					do_settings_sections( 'flowplayer5_settings' );
					submit_button();
					?>
				</form>
			</div>

			<div id="postbox-container-1" class="postbox-container-1 fp5-sidebar">
				<div id="side-sortables" class="ui-sortable metabox-holder">
					<div id="fp5_about" class="postbox">
					<h3 class="hndle"><span><?php _e( 'About Flowplayer HTML5', 'flowplayer5' ); ?></span></h3>
						<div class="inside">
							<p><a target="_blank" title="Flowplayer HTML5 for WordPress FAQ" href="https://wordpress.org/plugins/flowplayer5/faq/"><?php _e( 'Plugin documentation', 'flowplayer5' ); ?></a></p>
							<p><a target="_blank" title="Flowplayer HTML5 for WordPress Support Forum" href="https://wordpress.org/support/plugin/flowplayer5/"><?php _e( 'Support', 'flowplayer5' ); ?></a></p>
							<p><a target="_blank" title="Review Flowplayer HTML5 for WordPress" href="https://wordpress.org/support/view/plugin-reviews/flowplayer5/"><?php _e( 'Leave us a review', 'flowplayer5' ); ?></a></p>
							<p><a target="_blank" title="Flowplayer Website" href="https://flowplayer.org/"><img src="<?php echo plugins_url( '../assets/img/flowplayer.png', __FILE__ ) ?>" style="max-width: 100%;"></a></p>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

</div>