<?php
/**
 * Contact info box on header
 *
 * @package Unicamp
 * @since   1.3.1
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$phone_number = Unicamp::setting( 'contact_info_phone' );
$email        = Unicamp::setting( 'contact_info_email' );

$phone_number_url = str_replace( ' ', '', $phone_number );
?>
<div class="header-contact-links-box">
	<div class="contact-icon">
		<span class="fal fa-phone"></span>
	</div>
	<div class="contact-links">
		<?php if ( ! empty( $phone_number ) ): ?>
			<a class="header-contact-phone"
			   href="<?php echo esc_url( 'tel:' . $phone_number_url ); ?>"><?php echo esc_html( $phone_number ); ?></a>
		<?php endif; ?>

		<?php if ( ! empty( $email ) ): ?>
			<a class="header-contact-email"
			   href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php echo esc_html( $email ); ?></a>
		<?php endif; ?>
	</div>
</div>
