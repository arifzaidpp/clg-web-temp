<?php
/**
 * Template for displaying price
 *
 * @since   v.1.0.0
 *
 * @author  Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

$is_purchasable = tutor_utils()->is_course_purchasable();
$price          = apply_filters( 'get_tutor_course_price', null, get_the_ID() );
$free_text      = __( 'Free', 'unicamp' );

// Free course.
if ( is_null( $price ) ) {
	$monetize_by = tutor_utils()->get_option( 'monetize_by' );

	switch ( $monetize_by ) {
		case 'wc':
			if ( tutor_utils()->has_wc() ) {
				$free_text = wc_price( 0 );
			}
			break;
		case 'edd':
			if ( tutor_utils()->has_edd() ) {
				$free_text = edd_currency_filter( edd_format_amount( 0 ) );
			}
			break;
	}
}
?>
<?php if ( $is_purchasable && $price ) : ?>
	<div class="tutor-price">
		<?php
		echo '' . $price;
		$badge_format = esc_html__( '%s off', 'unicamp' );
		$badge_text   = Unicamp_Tutor::instance()->get_course_price_badge_text( get_the_ID(), $badge_format );
		if ( ! empty( $badge_text ) ) {
			echo '<span class="course-price-badge onsale">' . $badge_text . '</span>';
		}
		?>
	</div>
<?php else : ?>
	<div class="tutor-price course-free">
		<div class="price"><?php echo apply_filters( 'unicamp_course_price_free_html', $free_text ); ?></div>
	</div>
<?php
endif;
