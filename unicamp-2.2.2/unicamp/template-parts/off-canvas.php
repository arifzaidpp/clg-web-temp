<?php
if ( ! has_nav_menu( 'off_canvas' ) ) {
	return;
}
?>
<div id="popup-canvas-menu" class="page-popup popup-canvas-menu">
	<div id="page-close-main-menu" class="page-close-main-menu popup-close-button">
		<span class="fal fa-times"></span>
	</div>

	<div class="page-popup-content">
		<div class="popup-canvas-menu-left-content">
			<div class="popup-canvas-menu-image">
				<img src="<?php echo UNICAMP_THEME_ASSETS_URI . '/images/canvas-menu-image.png'; ?>"
				     alt="<?php esc_attr_e( 'Menu Image', 'unicamp' ); ?>">
			</div>
		</div>
		<div class="popup-canvas-menu-right-content">
			<?php Unicamp::off_canvas_menu_primary(); ?>

			<div class="popup-canvas-menu-search-form">
				<?php echo get_search_form(); ?>
			</div>
		</div>
	</div>
</div>
