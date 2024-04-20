<?php
/**
 * BP Nouveau Activity Widget template.
 *
 * @since   3.0.0
 * @version 9.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<?php if ( bp_has_activities( bp_nouveau_activity_widget_query() ) ) : ?>
	<div class="activity-list item-list">
		<?php while ( bp_activities() ) : bp_the_activity(); ?>

			<?php if ( bp_activity_has_content() ) : ?>
				<div class="activity-item">
					<div class="activity-item-avatar">
						<a href="<?php bp_activity_user_link(); ?>" class="bp-tooltip"
						   data-bp-tooltip="<?php echo esc_attr( bp_activity_member_display_name() ); ?>">
							<?php
							bp_activity_avatar(
								array(
									'type'   => 'thumb',
									'width'  => '56',
									'height' => '56',
								)
							);
							?>
						</a>
					</div>
					<div class="activity-item-body">
						<div class="activity-item-action"><?php bp_activity_action(); ?></div>
					</div>
				</div>
			<?php endif; ?>

		<?php endwhile; ?>
	</div>

<?php else : ?>
	<div class="widget-error">
		<?php bp_nouveau_user_feedback( 'activity-loop-none' ); ?>
	</div>
<?php endif; ?>
