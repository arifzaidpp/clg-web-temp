<?php
$footer = Unicamp_Global::instance()->get_footer();

if ( 'none' === $footer ) {
	return;
}
?>
<div id="page-footer-wrapper" class="page-footer-wrapper">
	<?php
	if ( ! function_exists( 'elementor_location_exits' ) || ! elementor_location_exits( 'footer', true ) ) {
		unicamp_load_template( 'footer/simple' );
	} else {
		if ( function_exists( 'elementor_theme_do_location' ) ) :
			unicamp_load_template( 'footer/elementor' );
		endif;
	}
	?>
</div>
