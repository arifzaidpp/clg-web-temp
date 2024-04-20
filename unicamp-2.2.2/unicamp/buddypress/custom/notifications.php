<?php if ( bp_has_notifications( bp_ajax_querystring( 'notifications' ) . '&user_id=' . get_current_user_id() . '&is_new=0' ) ) : ?>
	<?php while ( bp_the_notifications() ) : bp_the_notification(); ?>
		<li class="read-item <?php echo isset( buddypress()->notifications->query_loop->notification->is_new ) && buddypress()->notifications->query_loop->notification->is_new ? 'unread' : ''; ?>">
			<span class="unicamp-bp-full-link">
				<?php bp_the_notification_description(); ?>
			</span>
			<div class="notification-avatar">
				<?php unicamp_bp_notification_avatar(); ?>
			</div>
			<div class="notification-content">
				<span class="unicamp-bp-full-link">
					<?php bp_the_notification_description(); ?>
				</span>
				<span><?php bp_the_notification_description(); ?></span>
				<span class="posted"><?php bp_the_notification_time_since(); ?></span>
			</div>
			<div class="notification-status"></div>
		</li>
	<?php endwhile; ?>
<?php endif; ?>
