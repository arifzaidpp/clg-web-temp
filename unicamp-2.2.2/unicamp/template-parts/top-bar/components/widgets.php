<?php
/**
 * Widgets on top bar
 *
 * @package Unicamp
 * @since   1.3.1
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="top-bar-widgets">
	<?php Unicamp_Sidebar::instance()->generated_sidebar( 'top_bar_widgets' ); ?>
</div>
