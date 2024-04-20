<?php
/**
 * User links on top bar
 *
 * @package Unicamp
 * @since   1.3.1
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="top-bar-user-links">
	<div class="link-wrap">
		<?php if ( ! is_user_logged_in() ) { ?>
			<?php
			$login_url    = wp_login_url();
			$register_url = wp_registration_url();
			?>
			<a href="<?php echo esc_url( $login_url ); ?>"
			   title="<?php esc_attr_e( 'Log in', 'unicamp' ); ?>"
			   class="open-popup-login top-bar-login-link"
			><?php esc_html_e( 'Log in', 'unicamp' ); ?></a>
			<a href="<?php echo esc_url( $register_url ); ?>"
			   title="<?php esc_attr_e( 'Register', 'unicamp' ); ?>"
			   class="open-popup-register top-bar-register-link"
			><?php esc_html_e( 'Register', 'unicamp' ); ?></a>
		<?php } else { ?>
			<?php
			$profile_url  = apply_filters( 'unicamp_user_profile_url', '' );
			$profile_text = apply_filters( 'unicamp_user_profile_text', esc_html__( 'Profile', 'unicamp' ) );
			?>
			<?php if ( '' !== $profile_url && '' !== $profile_text ): ?>
				<a href="<?php echo esc_url( $profile_url ); ?>"><?php echo esc_html( $profile_text ); ?></a>
			<?php endif; ?>
			<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><?php esc_html_e( 'Log out', 'unicamp' ); ?></a>
		<?php } ?>
	</div>
</div>
