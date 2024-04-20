<div id="page-title-bar" <?php Unicamp_Title_Bar::instance()->the_wrapper_class(); ?>>
	<div class="page-title-bar-inner">
		<div class="page-title-bar-bg"></div>

		<?php unicamp_load_template( 'breadcrumb' ); ?>

		<div class="page-title-bar-heading container">
			<?php Unicamp_THA::instance()->title_bar_heading_before(); ?>

			<?php Unicamp_Title_Bar::instance()->render_title(); ?>

			<?php Unicamp_THA::instance()->title_bar_heading_after(); ?>
		</div>
	</div>
</div>
