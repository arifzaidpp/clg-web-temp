<?php
/**
 * BuddyPress - Members Settings ( General )
 *
 * @since   3.0.0
 * @version 8.0.0
 */

defined( 'ABSPATH' ) || exit;

$form_action = bp_displayed_user_domain() . bp_nouveau_get_component_slug( 'settings' ) . '/general';

bp_nouveau_member_hook( 'before', 'settings_template' ); ?>

	<h2 class="screen-heading general-settings-screen">
		<?php esc_html_e( 'Email & Password', 'unicamp' ); ?>
	</h2>

	<p class="info email-pwd-info">
		<?php esc_html_e( 'Update your email and or password.', 'unicamp' ); ?>
	</p>

	<form action="<?php echo esc_url( $form_action ); ?>" method="post" class="standard-form" id="your-profile">

		<div class="info bp-feedback">
			<span class="bp-icon" aria-hidden="true"></span>
			<p class="text"><?php esc_html_e( 'Click on the "Generate Password" button to change your password.', 'unicamp' ); ?></p>
		</div>

		<?php if ( ! is_super_admin() ) : ?>

			<label for="pwd">
				<?php
				/* translators: %s: email requirement explanations */
				printf( esc_html__( 'Current Password %s', 'unicamp' ), '<span>' . esc_html__( '(required to update email or change current password)', 'unicamp' ) . '</span>' );
				?>
			</label>
			<input type="password" name="pwd" id="pwd" value="" size="24"
			       class="settings-input small" <?php bp_form_field_attributes( 'password' ); ?>/> &nbsp;<a
				href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'unicamp' ); ?></a>

		<?php endif; ?>

		<label for="email"><?php esc_html_e( 'Account Email', 'unicamp' ); ?></label>
		<input type="email" name="email" id="email" value="<?php echo esc_attr( bp_get_displayed_user_email() ); ?>"
		       class="settings-input" <?php bp_form_field_attributes( 'email' ); ?>/>

		<?php
		$social_fields = Unicamp_Helper::get_user_social_networks_support();
		?>
		<?php if ( ! empty( $social_fields ) ) : ?>
			<?php foreach ( $social_fields as $key => $social ) : ?>
				<label
					for="<?php echo esc_attr( 'input-profile-' . $key ); ?>"><?php echo esc_html( $social['label'] ); ?></label>
				<input type="text" name="<?php echo esc_attr( $key ); ?>"
				       id="<?php echo esc_attr( 'input-profile-' . $key ); ?>"
				       value="<?php echo esc_attr( get_user_meta( get_current_user_id(), $key, true ) ); ?>"
				       class="settings-input"/>
			<?php endforeach; ?>
		<?php endif; ?>

		<div class="user-pass1-wrap">
			<button type="button" class="button wp-generate-pw">
				<?php esc_html_e( 'Generate Password', 'unicamp' ); ?>
			</button>

			<div class="wp-pwd">
				<label for="pass1"><?php esc_html_e( 'Add Your New Password', 'unicamp' ); ?></label>
				<span class="password-input-wrapper">
				<input type="password" name="pass1" id="pass1" size="24" class="settings-input small password-entry"
				       value="" <?php bp_form_field_attributes( 'password', array(
					'data-pw'          => wp_generate_password( 24 ),
					'aria-describedby' => 'pass-strength-result',
				) ); ?> />
			</span>
				<button type="button" class="button wp-hide-pw" data-toggle="0"
				        aria-label="<?php esc_attr_e( 'Hide password', 'unicamp' ); ?>">
					<span class="dashicons dashicons-hidden" aria-hidden="true"></span>
					<span class="text bp-screen-reader-text"><?php esc_html_e( 'Hide', 'unicamp' ); ?></span>
				</button>
				<button type="button" class="button wp-cancel-pw" data-toggle="0"
				        aria-label="<?php esc_attr_e( 'Cancel password change', 'unicamp' ); ?>">
					<span class="text"><?php esc_html_e( 'Cancel', 'unicamp' ); ?></span>
				</button>
				<div id="pass-strength-result" aria-live="polite"></div>
			</div>
		</div>

		<div class="user-pass2-wrap">
			<label class="label" for="pass2"><?php esc_html_e( 'Repeat Your New Password', 'unicamp' ); ?></label>
			<input name="pass2" type="password" id="pass2" size="24" class="settings-input small password-entry-confirm"
			       value="" <?php bp_form_field_attributes( 'password' ); ?> />
		</div>

		<div class="pw-weak">
			<label>
				<input type="checkbox" name="pw_weak" class="pw-checkbox"/>
				<span
					id="pw-weak-text-label"><?php esc_html_e( 'Confirm use of potentially weak password', 'unicamp' ); ?></span>
			</label>
		</div>

		<?php unicamp_bp_nouveau_submit_button( 'members-general-settings' ); ?>

	</form>

<?php
bp_nouveau_member_hook( 'after', 'settings_template' );
