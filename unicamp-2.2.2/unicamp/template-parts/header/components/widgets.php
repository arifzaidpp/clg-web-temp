<?php
/**
 * Widgets on header
 *
 * @package Unicamp
 * @since   1.3.1
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="header-widgets">
	<?php Unicamp_Sidebar::instance()->generated_sidebar( 'header_widgets' ); ?>
</div>
