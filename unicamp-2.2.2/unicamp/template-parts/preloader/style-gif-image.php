<?php
$image = Unicamp::setting( 'pre_loader_image' );
?>
<div>
	<?php if ( $image !== '' ): ?>
		<img src="<?php echo esc_url( $image ); ?>"
		     alt="<?php esc_attr_e( 'Unicamp Preloader', 'unicamp' ); ?>">
	<?php endif; ?>
</div>
