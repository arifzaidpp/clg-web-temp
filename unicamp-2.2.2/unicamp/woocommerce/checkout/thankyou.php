<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to unicamp-child/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order">

	<?php if ( $order ) : ?>
		<div class="row">
			<div class="col-lg-7 left-box">
				<?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

				<?php if ( $order->has_status( 'failed' ) ) : ?>

					<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'unicamp' ); ?></p>

					<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
						<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>"
						   class="button pay"><?php esc_html_e( 'Pay', 'unicamp' ); ?></a>
						<?php if ( is_user_logged_in() ) : ?>
							<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"
							   class="button pay"><?php esc_html_e( 'My account', 'unicamp' ); ?></a>
						<?php endif; ?>
					</p>

				<?php else : ?>

					<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'unicamp' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

					<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
						<li class="woocommerce-order-overview__order order">
							<span class="order-overview-label"><?php esc_html_e( 'Order number:', 'unicamp' ); ?></span>
							<span
								class="order-overview-value"><?php echo '' . $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</li>

						<li class="woocommerce-order-overview__date date">
							<span class="order-overview-label"><?php esc_html_e( 'Date:', 'unicamp' ); ?></span>
							<span
								class="order-overview-value"><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</li>

						<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
							<li class="woocommerce-order-overview__email email">
								<span class="order-overview-label"><?php esc_html_e( 'Email:', 'unicamp' ); ?></span>
								<span
									class="order-overview-value"><?php echo '' . $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							</li>
						<?php endif; ?>

						<li class="woocommerce-order-overview__total total">
							<span class="order-overview-label"><?php esc_html_e( 'Total:', 'unicamp' ); ?></span>
							<span
								class="order-overview-value"><?php echo '' . $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</li>

						<?php if ( $order->get_payment_method_title() ) : ?>
							<li class="woocommerce-order-overview__payment-method method">
								<span
									class="order-overview-label"><?php esc_html_e( 'Payment method:', 'unicamp' ); ?></span>
								<span
									class="order-overview-value"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></span>
							</li>
						<?php endif; ?>
					</ul>


				<?php endif; ?>

				<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
				<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

				<?php
				$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();

				if ( $show_customer_details ) {
					wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
				}
				?>
			</div>

			<div class="col-lg-5 right-box">
				<?php do_action( 'unicamp_woocommerce_after_thankyou', $order->get_id() ); ?>
			</div>
		</div>


	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'unicamp' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

	<?php endif; ?>

</div>
