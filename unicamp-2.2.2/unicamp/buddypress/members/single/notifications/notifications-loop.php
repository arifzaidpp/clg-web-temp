<?php
/**
 * BuddyPress - Members Notifications Loop
 *
 * @since   3.0.0
 * @version 3.1.0
 */

defined( 'ABSPATH' ) || exit;

if ( bp_has_notifications( bp_ajax_querystring( 'notifications' ) ) ) :

	bp_nouveau_pagination( 'top' ); ?>

	<form action="" method="post" id="notifications-bulk-management" class="standard-form">
		<div class="bp-tables-wrapper">
			<table class="notifications bp-tables-user">
				<thead>
				<tr>
					<th class="icon"></th>
					<th class="bulk-select-all"><input id="select-all-notifications" type="checkbox"><label
							class="bp-screen-reader-text"
							for="select-all-notifications"><?php esc_html_e( 'Select all', 'unicamp' ); ?></label>
					</th>
					<th class="title"><?php esc_html_e( 'Notification', 'unicamp' ); ?></th>
					<th class="date">
						<?php esc_html_e( 'Date Received', 'unicamp' ); ?>
						<?php bp_nouveau_notifications_sort_order_links(); ?>
					</th>
					<th class="actions"><?php esc_html_e( 'Actions', 'unicamp' ); ?></th>
				</tr>
				</thead>

				<tbody>

				<?php
				while ( bp_the_notifications() ) :
					bp_the_notification();
					?>
					<tr>
						<td></td>
						<td class="bulk-select-check"><label for="<?php bp_the_notification_id(); ?>"><input
									id="<?php bp_the_notification_id(); ?>" type="checkbox" name="notifications[]"
									value="<?php bp_the_notification_id(); ?>" class="notification-check"><span
									class="bp-screen-reader-text"><?php esc_html_e( 'Select this notification', 'unicamp' ); ?></span></label>
						</td>
						<td class="notification-description"><?php bp_the_notification_description(); ?></td>
						<td class="notification-since"><?php bp_the_notification_time_since(); ?></td>
						<td class="notification-actions"><?php bp_the_notification_action_links(); ?></td>
					</tr>

				<?php endwhile; ?>

				</tbody>
			</table>
		</div>
		<div class="notifications-options-nav">
			<?php unicamp_bp_nouveau_notifications_bulk_management_dropdown(); ?>
		</div><!-- .notifications-options-nav -->

		<?php wp_nonce_field( 'notifications_bulk_nonce', 'notifications_bulk_nonce' ); ?>
	</form>

<?php else : ?>
	<?php bp_nouveau_user_feedback( 'member-notifications-none' ); ?>
<?php endif;
