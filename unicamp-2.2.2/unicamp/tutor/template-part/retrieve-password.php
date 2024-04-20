<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="page-content">
	<div class="container">
		<div class="row">
			<div class="page-main-content">
				<?php tutor_alert( null, 'any' ); ?>

				<div class="user-form-wrap">
					<?php if ( tutils()->array_get( 'reset_key', $_GET ) && tutils()->array_get( 'user_id', $_GET ) ) : ?>
						<?php tutor_load_template( 'template-part.form-retrieve-password' ); ?>
					<?php else: ?>
						<?php do_action( 'tutor_before_retrieve_password_form' ); ?>

						<h4 class="form-title"><?php esc_html_e( 'Lost your password?', 'unicamp' ); ?></h4>

						<form method="post" class="tutor-ResetPassword lost_reset_password">
							<?php tutor_nonce_field(); ?>
							<input type="hidden" name="tutor_action" value="tutor_retrieve_password">

							<p><?php echo esc_html( apply_filters( 'tutor_lost_password_message', __( 'Please enter your username or email address. You will receive a link to create a new password via email.', 'unicamp' ) ) ); ?></p><?php // @codingStandardsIgnoreLine
							?>
							<div class="form-row">
								<label><?php esc_html_e( 'Username or email', 'unicamp' ); ?></label>
								<input type="text" name="user_login" id="user_login" autocomplete="username">
							</div>

							<div class="clear"></div>

							<?php do_action( 'tutor_lostpassword_form' ); ?>

							<div class="tutor-form-group">
								<button type="submit" class="tutor-button tutor-button-primary form-submit"
								        value="<?php esc_attr_e( 'Reset password', 'unicamp' ); ?>"><?php
									esc_html_e( 'Reset password', 'unicamp' ); ?></button>
							</div>
						</form>

						<?php do_action( 'tutor_after_retrieve_password_form' ); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
