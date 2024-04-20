<?php
/**
 * Login OTP email template
 *
 * @package TutorPro\Auth
 * @subpackage Templates
 * @author Themeum <support@themeum.com>
 * @link https://themeum.com
 * @since 2.1.9
 */

$tutor_heading_background = sprintf( 'style="background: url(%s) top right no-repeat;"', TUTOR_EMAIL()->url . 'assets/images/heading.png' );
$email_banner_background  = false == get_tutor_option( 'email_disable_banner' ) ? $tutor_heading_background : '';
?>
<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
	<?php require TUTOR_EMAIL()->path . 'views/email_styles.php'; ?>
</head>

<body>
	<div class="tutor-email-body">
		<div class="tutor-email-wrapper" style="background-color: #fff;">
			
			<?php require TUTOR_PRO()->path . 'templates/email/email_header.php'; ?>
			
			<div class="tutor-email-content" <?php echo isset( $email_banner_background ) ? $email_banner_background : ''; ?>>
				<div>
					<p><?php echo _e( 'Login OTP:', 'tutor-pro' ); ?> <strong>{login_otp}</strong></p>
					<p style="width: 350px;"><?php echo _e( 'Please use the following OTP code to complete your login.', 'tutor-pro' ); ?></p>
				</div>
			</div>
			
			<div class="tutor-email-separator">
				<img style="z-index:9;position:relative;" src="<?php echo esc_url( TUTOR_EMAIL()->url . 'assets/images/email.png' ); ?>">
			</div>

			<div class="tutor-email-footer-text">
				<div data-source="email-footer-text">{additional_footer}</div>
			</div>

		</div>
		<!-- wrapper end -->
	</div>
	<!-- email body end -->

	<!-- global footer from tutor settings > email -->
	{footer_text}

</body>
</html>
