<?php
/**
 * BuddyPress - Groups Activity
 *
 * @since   3.0.0
 * @version 8.0.0
 */
?>

	<h2 class="bp-screen-title<?php echo ( ! bp_is_group_home() ) ? ' bp-screen-reader-text' : ''; ?>">
		<?php esc_html_e( 'Group Activities', 'unicamp' ); ?>
	</h2>

<?php bp_nouveau_groups_activity_post_form(); ?>

<?php bp_nouveau_group_hook( 'before', 'activity_content' ); ?>

	<div id="activity-stream" class="activity single-group" data-bp-list="activity">

		<div id="bp-activity-ajax-loader"><?php bp_nouveau_user_feedback( 'group-activity-loading' ); ?></div>

	</div><!-- .activity -->

<?php
bp_nouveau_group_hook( 'after', 'activity_content' );
