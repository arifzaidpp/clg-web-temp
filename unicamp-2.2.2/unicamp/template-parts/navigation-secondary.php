<?php
if ( ! has_nav_menu( 'secondary' ) ) {
	return;
}
?>
<div id="page-navigation-secondary" class="navigation-secondary">
	<nav class="menu menu--secondary">
		<?php Unicamp::menu_secondary(); ?>
	</nav>
</div>
