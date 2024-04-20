<?php
/**
 * Template part for display register form on popup.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Unicamp
 * @since   1.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="popup-content-header">
	<h3 class="popup-title"><?php esc_html_e( 'Sign Up', 'unicamp' ); ?></h3>
	<p class="popup-description">
		<?php printf( esc_html__( 'Already have an account? %sLog in%s', 'unicamp' ), '<a href="#" class="open-popup-login link-transition-02">', '</a>' ); ?>
	</p>
</div>

<div class="popup-content-body">
	<form id="unicamp-register-form" class="unicamp-register-form" method="post">

		<?php do_action( 'unicamp_popup_register_before_form_fields' ); ?>

		<div class="row">
			<div class="col-xs-6">
				<div class="form-group">
					<label for="ip_reg_firstname"
					       class="form-label"><?php esc_html_e( 'First Name', 'unicamp' ); ?></label>
					<input type="text" id="ip_reg_firstname" class="form-control form-input"
					       name="firstname" placeholder="<?php esc_attr_e( 'First Name', 'unicamp' ); ?>">
				</div>
			</div>
			<div class="col-xs-6">
				<div class="form-group">
					<label for="ip_reg_lastname"
					       class="form-label"><?php esc_html_e( 'Last Name', 'unicamp' ); ?></label>
					<input type="text" id="ip_reg_lastname" class="form-control form-input"
					       name="lastname" placeholder="<?php esc_attr_e( 'Last Name', 'unicamp' ); ?>">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-6">
				<div class="form-group">
					<label for="ip_reg_username"
					       class="form-label"><?php esc_html_e( 'Username', 'unicamp' ); ?></label>
					<input type="text" id="ip_reg_username" class="form-control form-input"
					       name="username" placeholder="<?php esc_attr_e( 'Username', 'unicamp' ); ?>"/>
				</div>
			</div>
			<div class="col-xs-6">
				<div class="form-group">
					<label for="ip_reg_email"
					       class="form-label"><?php esc_html_e( 'Email', 'unicamp' ); ?></label>
					<input type="email" id="ip_reg_email" class="form-control form-input"
					       name="email" placeholder="<?php esc_attr_e( 'Your Email', 'unicamp' ); ?>"/>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-6">
				<div class="form-group">
					<label for="ip_reg_password"
					       class="form-label"><?php esc_html_e( 'Password', 'unicamp' ); ?></label>
					<div class="form-input-group form-input-password">
						<input type="password" id="ip_reg_password" class="form-control form-input"
						       name="password" placeholder="<?php esc_attr_e( 'Password', 'unicamp' ); ?>">
						<button type="button" class="btn-pw-toggle" data-toggle="0"
						        aria-label="<?php esc_attr_e( 'Show password', 'unicamp' ); ?>">
						</button>
					</div>
				</div>
			</div>
			<div class="col-xs-6">
				<div class="form-group">
					<label for="ip_reg_password2"
					       class="form-label"><?php esc_html_e( 'Re-Enter Password', 'unicamp' ); ?></label>
					<div class="form-input-group form-input-password">
						<input type="password" id="ip_reg_password2" class="form-control form-input"
						       name="password2"
						       placeholder="<?php esc_attr_e( 'Re-Enter Password', 'unicamp' ); ?>">
						<button type="button" class="btn-pw-toggle" data-toggle="0"
						        aria-label="<?php esc_attr_e( 'Show password', 'unicamp' ); ?>">
						</button>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group accept-account">
			<label class="form-label form-label-checkbox" for="ip_accept_account">
				<input type="checkbox" id="ip_accept_account" class="form-control"
				       name="accept_account" required>
				<?php printf( esc_html__( 'Accept the %1$s and %2$s', 'unicamp' ), '<a href="#">' . esc_html__( 'Terms', 'unicamp' ) . '</a>', '<a href="#">' . esc_html__( 'Privacy Policy', 'unicamp' ) . '</a>' ); ?>
			</label>
		</div>

		<?php do_action( 'unicamp_popup_register_after_form_fields' ); ?>

		<div class="form-response-messages"></div>

		<div class="form-group">
			<?php wp_nonce_field( 'user_register', 'user_register_nonce' ); ?>
			<input type="hidden" name="action" value="unicamp_user_register">
			<button type="submit" class="button form-submit"><?php esc_html_e( 'Register', 'unicamp' ); ?></button>
		</div>
	</form>
</div>
