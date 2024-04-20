<?php
/**
 * User links box on header
 *
 * @package Unicamp
 * @since   1.3.1
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="header-user-links-box">
	<div class="user-icon">
		<span class="fal fa-user"></span>
	</div>
	<div class="user-links">
		<?php
		if ( ! is_user_logged_in() ) {
			$login_url    = wp_login_url();
			$register_url = wp_registration_url();
			?>
			<a class="header-login-link open-popup-login"
			   href="<?php echo esc_url( $login_url ); ?>"><?php esc_html_e( 'Log In', 'unicamp' ); ?></a>
			<a class="header-register-link open-popup-register"
			   href="<?php echo esc_url( $register_url ); ?>"><?php esc_html_e( 'Register', 'unicamp' ); ?></a>
			<?php
		} else {
			$profile_url  = apply_filters( 'unicamp_user_profile_url', '' );
			$profile_text = apply_filters( 'unicamp_user_profile_text', esc_html__( 'Profile', 'unicamp' ) );
			?>
			<a class="header-profile-link"
			   href="<?php echo esc_url( $profile_url ); ?>"><?php echo esc_html( $profile_text ); ?></a>
			<a class="header-logout-link"
			   href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><?php esc_html_e( 'Log out', 'unicamp' ); ?></a>
		<?php } ?>
	</div>
</div>
