<?php Unicamp_Top_Bar::instance()->render(); ?>

<header id="page-header" <?php Unicamp_Header::instance()->get_wrapper_class(); ?>>
	<div class="page-header-place-holder"></div>
	<div id="page-header-inner" class="page-header-inner" data-sticky="1">
		<div class="container">
			<div class="header-wrap">
				<?php Unicamp_THA::instance()->header_wrap_top(); ?>

				<div class="header-left">
					<div class="header-content-inner">
						<?php unicamp_load_template( 'branding' ); ?>
					</div>
				</div>

				<?php Unicamp_Header::instance()->print_category_menu(); ?>

				<div class="header-right">
					<div class="header-content-inner">
						<div id="header-right-inner" class="header-right-inner">
							<div class="header-right-inner-content">
								<?php Unicamp_THA::instance()->header_right_top(); ?>

								<?php unicamp_load_template( 'navigation' ); ?>

								<?php Unicamp_Header::instance()->print_language_switcher(); ?>

								<?php Unicamp_Header::instance()->print_social_networks(); ?>

								<?php Unicamp_Header::instance()->print_notification(); ?>

								<?php Unicamp_Woo::instance()->render_mini_cart(); ?>

								<?php Unicamp_Header::instance()->print_search(); ?>

								<?php Unicamp_Header::instance()->print_user_buttons(); ?>

								<?php Unicamp_Header::instance()->print_button( array( 'size' => 'sm' ) ); ?>

								<?php Unicamp_THA::instance()->header_right_bottom(); ?>
							</div>
						</div>

						<?php Unicamp_Header::instance()->print_open_mobile_menu_button(); ?>

						<?php Unicamp_Header::instance()->print_more_tools_button(); ?>
					</div>
				</div>

				<?php Unicamp_THA::instance()->header_wrap_bottom(); ?>
			</div>
		</div>
	</div>
</header>
