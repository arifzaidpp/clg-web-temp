<?php
/**
 * BuddyPress - Members Home
 *
 * @since   1.0.0
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="item-body" class="tm-sticky-column">
	<?php bp_nouveau_member_hook( 'before', 'home_content' ); ?>
	<?php bp_nouveau_member_template_part(); ?>
	<?php bp_nouveau_member_hook( 'after', 'home_content' ); ?>
</div>
