<?php

namespace Unicamp\Woo;

defined( 'ABSPATH' ) || exit;

class Cart {
	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		// Check for empty-cart get param to clear the cart.
		add_action( 'init', [ $this, 'woocommerce_clear_cart_url' ] );

		add_filter( 'wc_empty_cart_message', [ $this, 'change_empty_cart_messages' ] );

		add_action( 'wc_ajax_unicamp_add_to_cart', [ $this, 'add_to_cart_variable' ] );
	}

	public function woocommerce_clear_cart_url() {
		global $woocommerce;

		if ( isset( $_GET['empty-cart'] ) ) {
			$woocommerce->cart->empty_cart();
		}
	}

	public function change_empty_cart_messages() {
		?>
		<div class="empty-cart-messages">
			<div class="empty-cart-icon">
				<?php echo \Unicamp_Helper::get_file_contents( UNICAMP_THEME_DIR . '/assets/svg/empty-cart.svg' ); ?>
			</div>
			<h2 class="empty-cart-heading"><?php esc_html_e( 'Your cart is currently empty.', 'unicamp' ); ?></h2>
			<p class="empty-cart-text"><?php esc_html_e( 'You may check out all the available products and buy some in the shop.', 'unicamp' ); ?></p>
		</div>
		<?php
	}

	/**
	 * @see \WC_AJAX::add_to_cart()
	 * @see \WC_Form_Handler::add_to_cart_action()
	 */
	public function add_to_cart_variable() {
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( ! isset( $_POST['product_id'] ) ) {
			return;
		}

		$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
		$product           = wc_get_product( $product_id );
		$quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
		$product_status    = get_post_status( $product_id );
		$variation_id      = ! empty( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
		$variation         = array();

		if ( $product && 'variation' === $product->get_type() && empty( $variation_id ) ) {
			$variation_id = $product_id;
			$product_id   = $product->get_parent_id();
		}

		foreach ( $_POST as $key => $value ) {
			if ( 'attribute_' !== substr( $key, 0, 10 ) ) {
				continue;
			}

			$variation[ sanitize_title( wp_unslash( $key ) ) ] = wp_unslash( $value );
		}

		if ( $passed_validation && false !== \WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) && 'publish' === $product_status ) {

			do_action( 'woocommerce_ajax_added_to_cart', $product_id );

			if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
				wc_add_to_cart_message( array( $product_id => $quantity ), true );
			}

			\WC_AJAX::get_refreshed_fragments();

		} else {
			// If there was an error adding to the cart, redirect to the product page to show any errors.
			$data = array(
				'error'       => true,
				'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
			);

			wp_send_json( $data );
		}
	}
}

Cart::instance()->initialize();
