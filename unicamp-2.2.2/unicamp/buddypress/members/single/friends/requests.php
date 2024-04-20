<?php
/**
 * BuddyPress - Members Friends Requests
 *
 * @since   3.0.0
 * @version 5.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<?php bp_nouveau_member_hook( 'before', 'friend_requests_content' ); ?>

	<div class="friendship-requests" data-bp-list="friendship_requests">
		<?php bp_get_template_part( 'members/single/friends/requests-loop' ); ?>
	</div>

<?php bp_nouveau_member_hook( 'after', 'friend_requests_content' );
