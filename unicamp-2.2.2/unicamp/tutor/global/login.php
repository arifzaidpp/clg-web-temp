<?php
/**
 * Display global login
 *
 * @since   v.1.0.0
 * @author  themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;
?>
<?php
$current_url   = tutils()->get_current_url();
$register_page = tutor_utils()->student_register_url();
$register_url  = add_query_arg( 'redirect_to', $current_url, $register_page );

//redirect_to
$args = array(
	'echo'                     => true,
	// Default 'redirect' value takes the user back to the request URI.
	'redirect'                 => home_url(),
	'form_id'                  => 'loginform',
	'label_username'           => __( 'Username or Email Address', 'unicamp' ),
	'label_password'           => __( 'Password', 'unicamp' ),
	'label_remember'           => __( 'Remember me', 'unicamp' ),
	'label_log_in'             => __( 'Log in', 'unicamp' ),
	'label_create_new_account' => __( 'Create a new account', 'unicamp' ),
	'id_username'              => 'user_login',
	'id_password'              => 'user_pass',
	'id_remember'              => 'rememberme',
	'id_submit'                => 'wp-submit',
	'remember'                 => true,
	'value_username'           => tutils()->input_old( 'log' ),
	// Set 'value_remember' to true to default the "Remember me" checkbox to checked.
	'value_remember'           => false,
	'wp_lostpassword_url'      => apply_filters( 'tutor_lostpassword_url', wp_lostpassword_url() ),
	'wp_lostpassword_label'    => __( 'Lost your password?', 'unicamp' ),
);

tutor_alert( null, 'warning' );
?>
<form name="<?php echo esc_attr( $args['form_id'] ); ?>" id="<?php echo esc_attr( $args['form_id'] ); ?>" method="post">
	<?php tutor_nonce_field(); ?>
	<input type="hidden" name="tutor_action" value="tutor_user_login"/>

	<div class="form-row login-username">
		<label
			for="<?php echo esc_attr( $args['id_username'] ); ?>"><?php echo esc_html( $args['label_username'] ); ?></label>
		<input type="text" name="log" id="<?php echo esc_attr( $args['id_username'] ) ?>" class="input"
		       value="<?php echo esc_attr( $args['value_username'] ); ?>" size="20"/>
	</div>
	<div class="form-row login-password">
		<label
			for="<?php echo esc_attr( $args['id_password'] ); ?>"><?php echo esc_html( $args['label_password'] ); ?></label>
		<input type="password" name="pwd" id="<?php echo esc_attr( $args['id_password'] ) ?>" class="input" value=""
		       size="20"/>
	</div>
	<div class="form-row login-remember-wrap">
		<div class="row-flex row-middle">
			<div class="col-grow">
				<?php if ( $args['remember'] ): ?>
					<p class="login-remember">
						<label class="form-label-checkbox" for="<?php echo esc_attr( $args['id_remember'] ); ?>">
							<input name="rememberme" type="checkbox"
							       id="<?php echo esc_attr( $args['id_remember'] ); ?>"
							       value="forever" <?php checked( $args['value_remember'], true ); ?> />
							<?php echo esc_html( $args['label_remember'] ); ?>
						</label>
					</p>
				<?php endif; ?>
			</div>
			<div class="col-shrink">
				<a href="<?php echo esc_url( $args['wp_lostpassword_url'] ); ?>"
				   class="link-transition-02 lost-password-link"><?php echo esc_html( $args['wp_lostpassword_label'] ); ?></a>
			</div>
		</div>
	</div>
	<div class="form-row login-submit">
		<input type="submit" name="wp-submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>"
		       class="button button-primary form-submit" value="<?php echo esc_attr( $args['label_log_in'] ); ?>"/>
		<input type="hidden" name="redirect_to" value="<?php echo esc_url( $args['redirect'] ); ?>"/>
	</div>
	<a href="<?php echo esc_url( $register_url ); ?>"
	   class="open-popup-register link-transition-01"><?php echo esc_html( $args['label_create_new_account'] ); ?></a>
</form>
