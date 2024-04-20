<?php
/**
 * BuddyPress - Members Settings ( Group Invites )
 *
 * @since   3.0.0
 * @version 8.0.0
 */

defined( 'ABSPATH' ) || exit;

$form_action = bp_displayed_user_domain() . bp_nouveau_get_component_slug( 'settings' ) . '/invites/';
?>
<h2 class="screen-heading group-invites-screen">
	<?php esc_html_e( 'Group Invites', 'unicamp' ); ?>
</h2>
<?php
if ( 1 === bp_nouveau_groups_get_group_invites_setting() ) {
	bp_nouveau_user_feedback( 'member-group-invites-friends-only' );
} else {
	bp_nouveau_user_feedback( 'member-group-invites-all' );
}
?>
<form action="<?php echo esc_url( $form_action ); ?>" name="account-group-invites-form" id="account-group-invites-form"
      class="standard-form user-group-invites-form" method="post">
	<label for="account-group-invites-preferences" class="form-label-checkbox">
		<input type="checkbox" name="account-group-invites-preferences" id="account-group-invites-preferences"
		       value="1" <?php checked( 1, bp_nouveau_groups_get_group_invites_setting() ); ?>/>
		<?php esc_html_e( 'I want to restrict Group invites to my friends only.', 'unicamp' ); ?>
	</label>
	<?php unicamp_bp_nouveau_submit_button( 'member-group-invites' ); ?>
</form>
