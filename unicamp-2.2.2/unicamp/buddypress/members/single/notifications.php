<?php
/**
 * BuddyPress - Users Notifications
 *
 * @since   3.0.0
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
	<nav class="<?php bp_nouveau_single_item_subnav_classes(); ?>" id="subnav" role="navigation"
	     aria-label="<?php esc_attr_e( 'Notifications menu', 'unicamp' ); ?>">
		<ul class="subnav">
			<?php bp_get_template_part( 'members/single/parts/item-subnav' ); ?>
		</ul>
	</nav>
<?php
switch ( bp_current_action() ) :
	case 'unread':
	case 'read':
		?>
	<?php bp_get_template_part( 'common/search-and-filters-bar' ); ?>

		<div class="notifications-user-list-wrap unicamp-bp-box">
			<div id="notifications-user-list" class="notifications dir-list" data-bp-list="notifications">
				<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'member-notifications-loading' ); ?></div>
			</div><!-- #groups-dir-list -->
		</div>
		<?php
		break;

	// Any other actions.
	default:
		bp_get_template_part( 'members/single/plugins' );
		break;
endswitch;
