<?php
/**
 * BuddyPress - Groups Members
 *
 * @since   3.0.0
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<h2 class="bp-screen-title">
	<?php esc_html_e( 'Membership List', 'unicamp' ); ?>
</h2>

<div id="members-group-list" class="group_members dir-list" data-bp-list="group_members">
	<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'group-members-loading' ); ?></div>
</div>
