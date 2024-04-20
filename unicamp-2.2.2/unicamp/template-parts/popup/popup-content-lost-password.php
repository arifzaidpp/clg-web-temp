<?php
/**
 * Template part for display lost password form on popup.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Unicamp
 * @since   1.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="popup-content-header">
	<h3 class="popup-title"><?php esc_html_e( 'Lost your password?', 'unicamp' ); ?></h3>
	<p class="popup-description">
		<?php esc_html_e( 'Please enter your username or email address. You will receive a link to create a new password via email.', 'unicamp' ); ?>
		<?php printf( esc_html__( 'Remember now? %1$sBack to login%2$s', 'unicamp' ), '<a href="#" class="open-popup-login link-transition-02">', '</a>' ); ?>
	</p>
</div>

<div class="popup-content-body">
	<form id="unicamp-lost-password-form" class="unicamp-lost-password-form" method="post">

		<?php do_action( 'unicamp_popup_lost_password_before_form_fields' ); ?>

		<div class="form-group">
			<label for="lost_password_user_login"
			       class="form-label"><?php esc_html_e( 'Username or email', 'unicamp' ); ?></label>
			<input type="text" id="lost_password_user_login" class="form-control form-input" name="user_login"
			       placeholder="<?php esc_attr_e( 'Your username or email', 'unicamp' ); ?>">
		</div>

		<?php do_action( 'unicamp_popup_lost_password_after_form_fields' ); ?>

		<div class="form-response-messages"></div>

		<div class="form-group">
			<?php wp_nonce_field( 'user_reset_password', 'user_reset_password_nonce' ); ?>
			<input type="hidden" name="action" value="unicamp_user_reset_password">
			<button type="submit" class="button form-submit"><?php esc_html_e( 'Reset password', 'unicamp' ); ?></button>
		</div>
	</form>
</div>
