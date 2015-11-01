<a href="#flowplayer-video-<?php echo absint( $id ); ?>" class="open-popup-link">
<?php
	if ( 'link' == $lightbox ) {
		echo esc_attr( $title );
	} elseif ( 'thumbnail' == $lightbox ) { ?>
		<img src="<?php echo esc_url( $splash ); ?>" alt="<?php echo esc_attr( $title ); ?>" height="<?php echo esc_attr( $height ); ?>" width="<?php echo esc_attr( $width ); ?>">
	<?php } ?>
</a>
<div id="flowplayer-video-<?php echo absint( $id ); ?>" class="mfp-hide">
<?php require( 'video.php' ); ?>
</div>
<?php require( 'lightbox-script.php' ); ?>
