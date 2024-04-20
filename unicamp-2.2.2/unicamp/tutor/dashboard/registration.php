<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="user-form-wrap form-register-wrap">
	<h4 class="form-title"><?php esc_html_e( 'Student registration', 'unicamp' ); ?></h4>

	<form method="post" enctype="multipart/form-data">
		<?php wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); ?>
		<input type="hidden" value="tutor_register_student" name="tutor_action"/>

		<?php
		$errors = apply_filters( 'tutor_student_register_validation_errors', array() );
		if ( is_array( $errors ) && count( $errors ) ) {
			echo '<div class="tutor-alert-warning tutor-mb-10"><ul class="tutor-required-fields">';
			foreach ( $errors as $error_key => $error_value ) {
				echo "<li>{$error_value}</li>";
			}
			echo '</ul></div>';
		}
		?>
		<div class="tutor-form-row">
			<div class="tutor-form-col-6">
				<div class="tutor-form-group">
					<label><?php esc_html_e( 'First Name', 'unicamp' ); ?></label>
					<input type="text" name="first_name"
					       value="<?php echo esc_attr( tutor_utils()->input_old( 'first_name' ) ); ?>">
				</div>
			</div>
			<div class="tutor-form-col-6">
				<div class="tutor-form-group">
					<label><?php esc_html_e( 'Last Name', 'unicamp' ); ?></label>
					<input type="text" name="last_name"
					       value="<?php echo esc_attr( tutor_utils()->input_old( 'last_name' ) ); ?>">
				</div>
			</div>
		</div>

		<div class="tutor-form-row">
			<div class="tutor-form-col-6">
				<div class="tutor-form-group">
					<label><?php esc_html_e( 'User Name', 'unicamp' ); ?></label>
					<input type="text" name="user_login" class="tutor_user_name"
					       value="<?php echo esc_attr( tutor_utils()->input_old( 'user_login' ) ); ?>">
				</div>
			</div>
			<div class="tutor-form-col-6">
				<div class="tutor-form-group">
					<label><?php esc_html_e( 'E-Mail', 'unicamp' ); ?></label>
					<input type="text" name="email"
					       value="<?php echo esc_attr( tutor_utils()->input_old( 'email' ) ); ?>">
				</div>
			</div>

		</div>

		<div class="tutor-form-row">
			<div class="tutor-form-col-6">
				<div class="tutor-form-group">
					<label><?php esc_html_e( 'Password', 'unicamp' ); ?></label>
					<input type="password" name="password"
					       value="<?php echo esc_attr( tutor_utils()->input_old( 'password' ) ); ?>">
				</div>
			</div>

			<div class="tutor-form-col-6">
				<div class="tutor-form-group">
					<label><?php esc_html_e( 'Password confirmation', 'unicamp' ); ?></label>
					<input type="password" name="password_confirmation"
					       value="<?php echo esc_attr( tutor_utils()->input_old( 'password_confirmation' ) ); ?>">
				</div>
			</div>
		</div>

		<div class="tutor-form-row">
			<div class="tutor-form-col-12">
				<div class="tutor-form-group">
					<button type="submit" name="tutor_register_student_btn" value="register"
					        class="tutor-button form-submit"><?php esc_html_e( 'Register', 'unicamp' ); ?></button>
				</div>
			</div>
		</div>

	</form>
</div>
