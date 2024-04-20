<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;
?>
<h2><?php esc_html_e( 'Please Sign-In to view this section', 'unicamp' ); ?></h2>

<?php
/**
 * Use popup login/register instead of inline form.
 */
return false;
?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

<div class="row" id="customer_login">

	<div class="col-lg-5 col-md-6">

		<?php endif; ?>

		<div class="woocommerce-form-wrap woocommerce-form-has-background woocommerce-form-login-wrap">

			<h2><?php esc_html_e( 'Login', 'unicamp' ); ?></h2>

			<form class="woocommerce-form woocommerce-form-login login" method="post">

				<?php do_action( 'woocommerce_login_form_start' ); ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="username"><?php esc_html_e( 'Username or email address', 'unicamp' ); ?>&nbsp;<span
							class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username"
					       id="username" autocomplete="username"
					       value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide woocommerce-form-password">
					<label for="password"><?php esc_html_e( 'Password', 'unicamp' ); ?>&nbsp;<span
							class="required">*</span></label>
					<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password"
					       id="password" autocomplete="current-password"/>
				</p>

				<?php do_action( 'woocommerce_login_form' ); ?>

				<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<div class="row-flex row-middle">
						<div class="col-grow">
							<label
								class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
								<input class="woocommerce-form__input woocommerce-form__input-checkbox"
								       name="rememberme"
								       type="checkbox" id="rememberme" value="forever"/>
								<span><?php esc_html_e( 'Remember me', 'unicamp' ); ?></span>
							</label>
						</div>
						<div class="col-shrink woocommerce-LostPassword lost_password">
							<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"
							   class="link-transition-02"><?php esc_html_e( 'Lost your password?', 'unicamp' ); ?></a>
						</div>
					</div>
				</div>

				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login"
				        value="<?php esc_attr_e( 'Log in', 'unicamp' ); ?>"><?php esc_html_e( 'Log in', 'unicamp' ); ?></button>

				<?php do_action( 'woocommerce_login_form_end' ); ?>

			</form>

		</div>

		<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

	</div>

	<div class="col-md-6 col-lg-push-1">

		<div class="woocommerce-form-wrap woocommerce-form-register-wrap">

			<h2><?php esc_html_e( 'Register', 'unicamp' ); ?></h2>

			<form method="post"
			      class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="reg_username"><?php esc_html_e( 'Username', 'unicamp' ); ?>&nbsp;<span
								class="required">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username"
						       id="reg_username" autocomplete="username"
						       value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
					</p>

				<?php endif; ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_email"><?php esc_html_e( 'Email address', 'unicamp' ); ?>&nbsp;<span
							class="required">*</span></label>
					<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email"
					       id="reg_email" autocomplete="email"
					       value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
				</p>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="reg_password"><?php esc_html_e( 'Password', 'unicamp' ); ?>&nbsp;<span
								class="required">*</span></label>
						<input type="password" class="woocommerce-Input woocommerce-Input--text input-text"
						       name="password"
						       id="reg_password" autocomplete="new-password"/>
					</p>

				<?php else : ?>

					<p><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'unicamp' ); ?></p>

				<?php endif; ?>

				<?php do_action( 'woocommerce_register_form' ); ?>

				<p class="woocommerce-form-row form-row">
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
					<button type="submit"
					        class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit"
					        name="register"
					        value="<?php esc_attr_e( 'Register', 'unicamp' ); ?>"><?php esc_html_e( 'Register', 'unicamp' ); ?></button>
				</p>

				<?php do_action( 'woocommerce_register_form_end' ); ?>

			</form>

		</div>

	</div>

</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
