<?php
/**
 * BuddyPress - Activity Stream (Single Item)
 *
 * This template is used by activity-loop.php and AJAX functions to show
 * each activity.
 *
 * @since   3.0.0
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;

bp_nouveau_activity_hook( 'before', 'entry' );
?>
	<li class="<?php bp_activity_css_class(); ?>" id="activity-<?php bp_activity_id(); ?>"
	    data-bp-activity-id="<?php bp_activity_id(); ?>" data-bp-timestamp="<?php bp_nouveau_activity_timestamp(); ?>">
		<div class="activity-content">
			<?php if ( bp_nouveau_activity_has_content() ) : ?>
				<div class="activity-inner">
					<?php bp_nouveau_activity_content(); ?>
				</div>
			<?php endif; ?>

			<div class="activity-header">
				<div class="activity-avatar item-avatar">
					<a href="<?php bp_activity_user_link(); ?>">
						<?php bp_activity_avatar( array( 'type' => 'full' ) ); ?>
					</a>
				</div>
				<div class="activity-action">
					<?php echo unicamp_bp_get_activity_action(); ?>
				</div>
			</div>

			<?php bp_nouveau_activity_entry_buttons(); ?>
		</div>
	</li>
<?php
bp_nouveau_activity_hook( 'after', 'entry' );
