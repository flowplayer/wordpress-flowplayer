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