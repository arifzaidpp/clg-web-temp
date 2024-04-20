<?php
/**
 * @package       TutorLMS/Templates
 * @version       1.4.3
 *
 * @theme-since   1.0.0
 * @theme-version 2.2.0
 */

defined( 'ABSPATH' ) || exit;

$product_id = tutor_utils()->get_course_product_id();
$product    = wc_get_product( $product_id );
if ( $product ) {
	?>

	<div class="tutor-course-purchase-box">
		<?php if ( tutor_utils()->is_course_added_to_cart( $product_id, true ) ) : ?>
			<a href="<?php echo wc_get_cart_url(); ?>">
				<button class="tutor-button"><?php esc_html_e( 'View Cart', 'unicamp' ); ?></button>
			</a>
		<?php else : ?>
			<form class="cart"
			      action="<?php echo esc_url( apply_filters( 'tutor_course_add_to_cart_form_action', get_permalink( get_the_ID() ) ) ); ?>"
			      method="post" enctype='multipart/form-data'>

				<?php do_action( 'tutor_before_add_to_cart_button' ); ?>

				<?php
				$notification_settings = [
					'image' => '',
					'title' => get_the_title(),
				];

				if ( has_post_thumbnail() ) {
					$thumbnail_id = get_post_thumbnail_id();

					$notification_settings['image'] = Unicamp_Image::get_attachment_url_by_id( [
						'id'   => $thumbnail_id,
						'size' => '80x80',
					] );
				}
				?>

				<div class="cart-notification"
				     data-notification="<?php echo esc_attr( wp_json_encode( $notification_settings ) ); ?>">
					<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"
					        class="single_add_to_cart_button ajax_add_to_cart tutor-button alt">
						<i class="far fa-shopping-cart"></i>
						<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
					</button>
				</div>
				<?php do_action( 'tutor_after_add_to_cart_button' ); ?>

			</form>
		<?php endif; ?>
	</div>

<?php } else { ?>
	<p class="tutor-alert-warning">
		<?php esc_html_e( 'Please make sure that your product exists and valid for this course', 'unicamp' ); ?>
	</p>
	<?php
}
