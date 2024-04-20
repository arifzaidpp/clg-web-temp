<?php
/**
 * The template for displaying columns of top bar.
 * one right column
 *
 * This template can be overridden by copying it to unicamp-child/template-parts/top-bar/content-column-1r.php
 *
 * @author ThemeMove
 * @since  1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="col-md-12 top-bar-right">
	<div class="top-bar-wrap">
		<?php Unicamp_Top_Bar::instance()->print_components( 'right' ); ?>
	</div>
</div>
