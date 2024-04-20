<?php
/**
 * BuddyPress - Members Settings (Export Data)
 *
 * @since   3.1.0
 * @version 8.0.0
 */

defined( 'ABSPATH' ) || exit;

bp_nouveau_member_hook( 'before', 'settings_template' ); ?>

	<h2 class="screen-heading data-settings-screen">
		<?php esc_html_e( 'Data Export', 'unicamp' ); ?>
	</h2>

<?php $request = bp_settings_get_personal_data_request(); ?>

<?php if ( $request ) : ?>

	<?php if ( 'request-completed' === $request->status ) : ?>

		<?php if ( bp_settings_personal_data_export_exists( $request ) ) : ?>

			<p><?php esc_html_e( 'Your request for an export of personal data has been completed.', 'unicamp' ); ?></p>
			<p><?php printf( esc_html__( 'You may download your personal data by clicking on the link below. For privacy and security, we will automatically delete the file on %s, so please download it before then.', 'unicamp' ), bp_settings_get_personal_data_expiration_date( $request ) ); ?></p>

			<p>
				<strong><?php printf( '<a href="%1$s">%2$s</a>', bp_settings_get_personal_data_export_url( $request ), esc_html__( 'Download personal data', 'unicamp' ) ); ?></strong>
			</p>

		<?php else : ?>

			<p><?php esc_html_e( 'Your previous request for an export of personal data has expired.', 'unicamp' ); ?></p>
			<p><?php esc_html_e( 'Please click on the button below to make a new request.', 'unicamp' ); ?></p>

			<form id="bp-data-export" method="post">
				<input type="hidden" name="bp-data-export-delete-request-nonce"
				       value="<?php echo wp_create_nonce( 'bp-data-export-delete-request' ); ?>"/>
				<button type="submit" name="bp-data-export-nonce"
				        value="<?php echo wp_create_nonce( 'bp-data-export' ); ?>"><?php esc_html_e( 'Request new data export', 'unicamp' ); ?></button>
			</form>

		<?php endif; ?>

	<?php elseif ( 'request-confirmed' === $request->status ) : ?>

		<p><?php printf( esc_html__( 'You previously requested an export of your personal data on %s.', 'unicamp' ), bp_settings_get_personal_data_confirmation_date( $request ) ); ?></p>
		<p><?php esc_html_e( 'You will receive a link to download your export via email once we are able to fulfill your request.', 'unicamp' ); ?></p>

	<?php endif; ?>

<?php else : ?>

	<p><?php esc_html_e( 'You can request an export of your personal data, containing the following items if applicable:', 'unicamp' ); ?></p>

	<div class="user-data-exporter-items"><?php bp_settings_data_exporter_items(); ?></div>

	<p><?php esc_html_e( 'If you want to make a request, please click on the button below:', 'unicamp' ); ?></p>

	<form id="bp-data-export" method="post">
		<button type="submit" name="bp-data-export-nonce"
		        value="<?php echo wp_create_nonce( 'bp-data-export' ); ?>"><?php esc_html_e( 'Request personal data export', 'unicamp' ); ?></button>
	</form>

<?php endif; ?>

	<h2 class="screen-heading data-settings-screen">
		<?php esc_html_e( 'Data Erase', 'unicamp' ); ?>
	</h2>

<?php /* translators: Link to Delete Account Settings page */ ?>
	<p><?php esc_html_e( 'To erase all data associated with your account, your user account must be completely deleted.', 'unicamp' ); ?><?php if ( bp_disable_account_deletion() ) : ?><?php esc_html_e( 'Please contact the site administrator to request account deletion.', 'unicamp' ); ?><?php else : ?><?php printf( esc_html__( 'You may delete your account by visiting the %s page.', 'unicamp' ), sprintf( '<a href="%s">%s</a>', bp_displayed_user_domain() . bp_nouveau_get_component_slug( 'settings' ) . '/delete-account/', esc_html__( 'Delete Account', 'unicamp' ) ) ); ?><?php endif; ?></p>

<?php
bp_nouveau_member_hook( 'after', 'settings_template' );
