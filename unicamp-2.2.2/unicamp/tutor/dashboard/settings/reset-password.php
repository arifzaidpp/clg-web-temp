<?php
/**
 * @package       TutorLMS/Templates
 * @version       1.7.5
 * @theme-version 2.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<h3><?php esc_html_e( 'Settings', 'unicamp' ) ?></h3>

<div class="tutor-dashboard-content-inner">
	<div class="tutor-dashboard-inline-links">
		<?php tutor_load_template( 'dashboard.settings.nav-bar', [ 'active_setting_nav' => 'reset_password' ] ); ?>
	</div>

	<div class="tutor-reset-password-form-wrap">
		<form action="" method="post" enctype="multipart/form-data"
		      class="dashboard-settings-form dashboard-settings-reset-password-form">
			<?php /*wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); */ ?><!--
			<input type="hidden" value="tutor_reset_password" name="tutor_action"/>-->

			<?php do_action( 'tutor_reset_password_input_before' ) ?>

			<div class="row">
				<div class="col-md-12 col-lg-6">
					<div class="dashboard-content-box">
						<h4 class="dashboard-content-box-title"><?php esc_html_e( 'Reset Password', 'unicamp' ); ?></h4>

						<div class="tutor-form-group">
							<label> <?php esc_html_e( 'Current Password', 'unicamp' ); ?> </label>
							<input type="password" name="previous_password">
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="tutor-form-group">
									<label><?php esc_html_e( 'New Password', 'unicamp' ); ?></label>
									<input type="password" name="new_password">
								</div>
							</div>
							<div class="col-md-6">
								<div class="tutor-form-group">
									<label><?php esc_html_e( 'Confirm New Password', 'unicamp' ); ?></label>
									<input type="password" name="confirm_new_password">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="tutor-form-group form-submit-wrap">
				<button type="submit" class="tutor-button tutor-profile-password-reset">
					<?php esc_html_e( 'Reset Password', 'unicamp' ); ?>
				</button>
			</div>

			<?php do_action( 'tutor_reset_password_input_after' ) ?>

		</form>
	</div>
</div>




