<?php
$type   = Unicamp_Global::instance()->get_top_bar_type();
$layout = Unicamp::setting( "top_bar_style_{$type}_layout" );
?>
<div <?php Unicamp_Top_Bar::instance()->get_wrapper_class(); ?>>
	<div class="container">
		<div class="row row-eq-height">
			<?php unicamp_load_template( 'top-bar/content-column', $layout ); ?>
		</div>
	</div>
</div>
