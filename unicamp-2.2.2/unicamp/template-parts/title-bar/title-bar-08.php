<div id="page-title-bar" <?php Unicamp_Title_Bar::instance()->the_wrapper_class(); ?>>
	<div class="page-title-bar-inner">
		<div class="page-title-bar-bg"></div>

		<?php unicamp_load_template( 'breadcrumb' ); ?>

		<div class="page-title-bar-heading">
			<div class="container container-small">
				<div class="row row-xs-center">
					<div class="col-md-6">
						<div class="page-title-bar-search-form">
							<h2 class="heading"><?php esc_html_e( 'How may we help you?', 'unicamp' ); ?></h2>
							<?php get_search_form(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
