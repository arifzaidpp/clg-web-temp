<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to unicamp-child/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>
<table class="shop_table woocommerce-checkout-review-order-table">
	<tbody>
	<?php
	do_action( 'woocommerce_review_order_before_cart_contents' );

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		/**
		 * @var WC_Product $_product
		 */
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

		if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
			?>
			<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
				<td class="product-info">
					<div class="product-wrapper">
						<div class="product-thumbnail">
							<?php
							$product_image = Unicamp_Woo::instance()->get_product_image( $_product, '80x9999' );

							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $product_image, $cart_item, $cart_item_key );
							echo '' . $thumbnail;
							?>
						</div>

						<div class="product-caption">
							<h3 class="product-name">
								<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?>
								<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( 'x%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</h3>
							<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</div>
				</td>
				<td class="product-total">
					<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</td>
			</tr>
			<?php
		}
	}

	do_action( 'woocommerce_review_order_after_cart_contents' );
	?>
	</tbody>
	<tfoot>

	<tr class="cart-subtotal">
		<th><?php esc_html_e( 'Subtotal', 'unicamp' ); ?></th>
		<td><?php wc_cart_totals_subtotal_html(); ?></td>
	</tr>

	<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
		<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
			<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
			<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
		</tr>
	<?php endforeach; ?>

	<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
		<?php //wc_cart_totals_shipping_html(); ?>
		<tr class="woocommerce-shipping-totals shipping">
			<th><?php esc_html_e( 'Shipping', 'unicamp' ); ?></th>
			<td>
				<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
				<?php
				/**
				 * Custom html. Show selected shipping method.
				 */
				$packages = WC()->shipping()->get_packages();
				foreach ( $packages as $i => $package ) {
					$chosen_method = isset( WC()->session->chosen_shipping_methods[ $i ] ) ? WC()->session->chosen_shipping_methods[ $i ] : '';
					$product_names = array();

					if ( count( $packages ) > 1 ) {
						foreach ( $package['contents'] as $item_id => $values ) {
							$product_names[ $item_id ] = $values['data']->get_name() . ' &times;' . $values['quantity'];
						}
						$product_names = apply_filters( 'woocommerce_shipping_package_details_array', $product_names, $package );
					}

					// Available methods.
					if ( ! empty( $package['rates'] ) ) {
						foreach ( $package['rates'] as $method ) :
							if ( $method->id === $chosen_method ) {
								echo wc_cart_totals_shipping_method_label( $method );
							}
						endforeach;
					}
				}
				?>

				<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
			</td>
		</tr>
	<?php endif; ?>

	<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
		<tr class="fee">
			<th><?php echo esc_html( $fee->name ); ?></th>
			<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
		</tr>
	<?php endforeach; ?>

	<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
		<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
			<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited ?>
				<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<th><?php echo esc_html( $tax->label ); ?></th>
					<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr class="tax-total">
				<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
				<td><?php wc_cart_totals_taxes_total_html(); ?></td>
			</tr>
		<?php endif; ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

	<tr class="order-total">
		<th><?php esc_html_e( 'Total', 'unicamp' ); ?></th>
		<td><?php wc_cart_totals_order_total_html(); ?></td>
	</tr>

	<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</tfoot>
</table>
