<?php
defined( 'ABSPATH' ) || exit;
/**
 * Rewrite some functions of BP for better.
 */

/**
 * Unicamp change input to button
 *
 * @see   bp_nouveau_submit_button()
 *
 * Output a submit button and the nonce for the requested action.
 *
 * @since 3.0.0
 *
 * @param string $action The action to get the submit button for. Required.
 */
function unicamp_bp_nouveau_submit_button( $action ) {
	$submit_data = bp_nouveau_get_submit_button( $action );
	if ( empty( $submit_data['attributes'] ) || empty( $submit_data['nonce'] ) ) {
		return;
	}

	if ( ! empty( $submit_data['before'] ) ) {

		/**
		 * Fires before display of the submit button.
		 *
		 * This is a dynamic filter that is dependent on the "before" value provided by bp_nouveau_get_submit_button().
		 *
		 * @since 3.0.0
		 */
		do_action( $submit_data['before'] );
	}

	$submit_input = sprintf( '<button type="submit" %s>%s</button>',
		bp_get_form_field_attributes( 'submit', $submit_data['attributes'] ),  // Safe.
		esc_html__( 'Save Changes', 'unicamp' )
	);

	// Output the submit button.
	if ( isset( $submit_data['wrapper'] ) && false === $submit_data['wrapper'] ) {
		echo '' . $submit_input;

		// Output the submit button into a wrapper.
	} else {
		printf( '<div class="submit">%s</div>', $submit_input );
	}

	if ( empty( $submit_data['nonce_key'] ) ) {
		wp_nonce_field( $submit_data['nonce'] );
	} else {
		wp_nonce_field( $submit_data['nonce'], $submit_data['nonce_key'] );
	}

	if ( ! empty( $submit_data['after'] ) ) {

		/**
		 * Fires before display of the submit button.
		 *
		 * This is a dynamic filter that is dependent on the "after" value provided by bp_nouveau_get_submit_button().
		 *
		 * @since 3.0.0
		 */
		do_action( $submit_data['after'] );
	}
}


/**
 * Unicamp change input to button
 *
 * @see   bp_nouveau_notifications_bulk_management_dropdown()
 *                                  \
 * Output the dropdown for bulk management of notifications.
 *
 * @since 3.0.0
 */
function unicamp_bp_nouveau_notifications_bulk_management_dropdown() {
	?>

	<div class="select-wrap">
		<label class="bp-screen-reader-text"
		       for="notification-select"><?php esc_html_e( 'Select Bulk Action', 'unicamp' ); ?></label>
		<select name="notification_bulk_action" id="notification-select">
			<option value="" selected="selected"><?php esc_html_e( 'Bulk Actions', 'unicamp' ); ?></option>
			<?php if ( bp_is_current_action( 'unread' ) ) : ?>
				<option value="read"><?php echo esc_html_x( 'Mark read', 'button', 'unicamp' ); ?></option>
			<?php elseif ( bp_is_current_action( 'read' ) ) : ?>
				<option value="unread"><?php echo esc_html_x( 'Mark unread', 'button', 'unicamp' ); ?></option>
			<?php endif; ?>
			<option value="delete"><?php echo esc_html_x( 'Delete', 'button', 'unicamp' ); ?></option>
		</select>
		<span class="select-arrow"></span>
	</div>

	<button type="submit" id="notification-bulk-manage"
	        class="button action"><?php echo esc_html_x( 'Apply', 'button', 'unicamp' ) ?></button>
	<?php
}

/**
 * Unicamp remove template notice.
 *
 * @see   bp_nouveau_group_header_template_part()
 *
 * Use the appropriate Group header and enjoy a template hierarchy
 *
 * @since 3.0.0
 */
function unicamp_bp_nouveau_group_header_template_part() {
	$template = 'group-header';

	if ( bp_group_use_cover_image_header() ) {
		$template = 'cover-image-header';
	}

	/**
	 * Fires before the display of a group's header.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_group_header' );

	// Get the template part for the header
	bp_nouveau_group_get_template_part( $template );

	/**
	 * Fires after the display of a group's header.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_group_header' );

	//bp_nouveau_template_notices();
}

/**
 * Unicamp remove template notice.
 *
 * @see   bp_nouveau_member_header_template_part()
 *
 * Use the appropriate Member header and enjoy a template hierarchy
 *
 * @since 3.0.0
 *
 * @return string HTML Output
 */
function unicamp_bp_nouveau_member_header_template_part() {
	$template = 'member-header';

	if ( bp_displayed_user_use_cover_image_header() ) {
		$template = 'cover-image-header';
	}

	/**
	 * Fires before the display of a member's header.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_member_header' );

	// Get the template part for the header
	bp_nouveau_member_get_template_part( $template );

	/**
	 * Fires after the display of a member's header.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_member_header' );

	//bp_nouveau_template_notices();
}

/**
 * Output the <option> of the data filter's <select> element.
 *
 * @since 3.0.0
 */
function unicamp_bp_nouveau_filter_options() {
	echo unicamp_bp_nouveau_get_filter_options();  // Escaped in inner functions.
}

/**
 * Unicamp support default value
 *
 * @see   bp_nouveau_get_filter_options()
 *
 * Get the <option> of the data filter's <select> element.
 *
 * @since 3.0.0
 *
 * @return string
 */
function unicamp_bp_nouveau_get_filter_options() {
	$output = '';

	if ( 'notifications' === bp_current_component() ) {
		$output = bp_nouveau_get_notifications_filters();

	} else {
		$filters = bp_nouveau_get_component_filters();

		/**
		 * Unicamp Change
		 */
		$default_filter = apply_filters( 'unicamp_bp_nouveau_default_filter_option', 'alphabetical' );

		foreach ( $filters as $key => $value ) {
			$output .= sprintf( '<option value="%1$s" %4$s>%2$s</option>%3$s',
				esc_attr( $key ),
				esc_html( $value ),
				PHP_EOL,
				selected( $default_filter, $key, false )
			);
		}
	}

	return $output;
}

/**
 * Unicamp change text
 *
 * @see   bp_get_activity_action()
 *
 * Return the activity action.
 *
 * @since 1.2.0
 * @since 1.7.0 Introduce function parameter, $args.
 *
 * @global object $activities_template {@link BP_Activity_Template}
 *
 * @param array   $args                {
 *
 * @type bool     $no_timestamp        Whether to exclude the timestamp.
 * }
 *
 * @return string The activity action.
 */
function unicamp_bp_get_activity_action( $args = array() ) {
	global $activities_template;

	$r = wp_parse_args( $args, array(
		'no_timestamp' => false,
	) );

	/**
	 * Unicamp custom action text.
	 */
	$activities_template->activity->action = '<a href="' . esc_url( $activities_template->activity->primary_link ) . '">' . $activities_template->activity->display_name . '</a>';

	/**
	 * Filters the activity action before the action is inserted as meta.
	 *
	 * @since 1.2.10
	 *
	 * @param array $value Array containing the current action, the current activity, and the $args array passed into the function.
	 */
	$action = apply_filters_ref_array( 'bp_get_activity_action_pre_meta', array(
		$activities_template->activity->action,
		&$activities_template->activity,
		$r,
	) );

	// Prepend the activity action meta (link, time since, etc...).
	if ( ! empty( $action ) && empty( $r['no_timestamp'] ) ) {
		$action = bp_insert_activity_meta( $action );
	}

	/**
	 * Filters the activity action after the action has been inserted as meta.
	 *
	 * @since 1.2.0
	 * @since 1.7.0 Now passes a 3rd parameter, $r, an array of arguments from the function.
	 *
	 * @param array $value Array containing the current action, the current activity, and the $r array passed into the function.
	 */
	return apply_filters_ref_array( 'bp_get_activity_action', array(
		$action,
		&$activities_template->activity,
		$r,
	) );
}

function unicamp_bp_notification_avatar() {
	$notification = buddypress()->notifications->query_loop->notification;
	$component    = $notification->component_name;

	switch ( $component ) {
		case 'groups':
			if ( ! empty( $notification->item_id ) ) {
				$item_id = $notification->item_id;
				$object  = 'group';
			}
			break;
		case 'follow':
		case 'friends':
			if ( ! empty( $notification->item_id ) ) {
				$item_id = $notification->item_id;
				$object  = 'user';
			}
			break;
		default:
			if ( ! empty( $notification->secondary_item_id ) ) {
				$item_id = $notification->secondary_item_id;
				$object  = 'user';
			} else {
				$item_id = $notification->item_id;
				$object  = 'user';
			}
			break;
	}

	if ( isset( $item_id, $object ) ) {

		if ( $object === 'group' ) {
			$group = new BP_Groups_Group( $item_id );
			$link  = bp_get_group_permalink( $group );
		} else {
			$user = new WP_User( $item_id );
			$link = bp_core_get_user_domain( $user->ID, $user->user_nicename, $user->user_login );
		}

		?>
		<a href="<?php echo esc_url( $link ); ?>">
			<?php echo bp_core_fetch_avatar( [ 'item_id' => $item_id, 'object' => $object ] ); ?>
			<?php if ( isset( $user ) ): ?>
				<?php unicamp_bp_user_status( $user->ID ); ?>
			<?php endif; ?>
		</a>
		<?php
	}

}

/**
 * BuddyPress user status
 *
 * @param $user_id
 */
function unicamp_bp_user_status( $user_id ) {
	if ( unicamp_bp_is_user_online( $user_id ) ) {
		echo '<span class="member-status online"></span>';
	}
}

/**
 * Is the current user online
 *
 * @param $user_id
 *
 * @return bool
 */
function unicamp_bp_is_user_online( $user_id ) {

	if ( ! function_exists( 'bp_get_user_last_activity' ) ) {
		return;
	}

	$last_activity = strtotime( bp_get_user_last_activity( $user_id ) );

	if ( empty( $last_activity ) ) {
		return false;
	}

	// the activity timeframe is 5 minutes
	$activity_timeframe = 5 * MINUTE_IN_SECONDS;

	return ( time() - $last_activity <= $activity_timeframe );
}
