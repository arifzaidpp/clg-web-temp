<?php

namespace Unicamp\Woo;

defined( 'ABSPATH' ) || exit;

class Checkout {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
		add_action( 'woocommerce_checkout_after_order_review', [
			$this,
			'template_checkout_payment_title',
		], 10 );
		add_action( 'woocommerce_checkout_after_order_review', 'woocommerce_checkout_payment', 20 );

		add_filter( 'woocommerce_checkout_fields', [ $this, 'override_checkout_fields' ] );

		add_filter( 'woocommerce_gateway_icon', [ $this, 'new_gateway_icon' ], 99, 2 );

		// Checkout Received.
		remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
		add_action( 'unicamp_woocommerce_after_thankyou', 'woocommerce_order_details_table', 10 );
	}

	public function template_checkout_payment_title() {
		?>
		<h3 class="checkout-payment-info-heading"><?php esc_html_e( 'Payment information', 'unicamp' ); ?></h3>
		<?php
	}

	/**
	 * Add placeholder for all fields.
	 *
	 * @param $fields
	 *
	 * @return mixed
	 */
	public function override_checkout_fields( $fields ) {
		// Add placeholder for billing form.
		foreach ( $fields['billing'] as $field => $value ) {
			// If has label & not has placeholder.
			if ( ! empty( $fields['billing'][ $field ]['label'] ) && empty( $fields['billing'][ $field ]['placeholder'] ) ) {
				$fields['billing'][ $field ]['placeholder'] = $fields['billing'][ $field ]['label'];
			}

			/**
			 * Add custom class for some fields
			 */
			switch ( $field ) {
				case 'billing_first_name':
				case 'billing_last_name':
				case 'billing_phone':
				case 'billing_email':
					$fields['billing'][ $field ]['class'][] = 'col-sm-6';
					break;

				case 'billing_address_1':
					$fields['billing'][ $field ]['class'][] = 'col-sm-8';
					break;

				case 'billing_address_2':
					// Remove class to avoid broken layout.
					$label_classes = $fields['billing'][ $field ]['label_class'];

					foreach ( $label_classes as $key => $class ) {
						if ( 'screen-reader-text' === $class ) {
							unset( $label_classes[ $key ] );

							break;
						}
					}

					$fields['billing'][ $field ]['label_class'] = $label_classes;

					$fields['billing'][ $field ]['class'][] = 'col-sm-4';
					break;

				case 'billing_city':
				case 'billing_state':
				case 'billing_postcode':
					$fields['billing'][ $field ]['class'][] = 'col-sm-4';
					break;

				default :
					$fields['billing'][ $field ]['class'][] = 'col-sm-12';
					break;
			}
		}

		// Add placeholder for shipping form.
		foreach ( $fields['shipping'] as $field => $value ) {
			// If has label & not has placeholder.
			if ( ! empty( $fields['shipping'][ $field ]['label'] ) && empty( $fields['shipping'][ $field ]['placeholder'] ) ) {
				$fields['shipping'][ $field ]['placeholder'] = $fields['shipping'][ $field ]['label'];
			}

			/**
			 * Add custom class for some fields
			 */
			switch ( $field ) {
				case 'shipping_first_name':
				case 'shipping_last_name':
				case 'shipping_phone':
				case 'shipping_email':
					$fields['shipping'][ $field ]['class'][] = 'col-sm-6';
					break;

				case 'shipping_address_1':
					$fields['shipping'][ $field ]['class'][] = 'col-sm-8';
					break;

				case 'shipping_address_2':
					$fields['shipping'][ $field ]['class'][] = 'col-sm-4';
					$fields['shipping'][ $field ]['label']   = esc_html__( 'Apt/Suite', 'unicamp' );
					break;

				case 'shipping_city':
				case 'shipping_state':
				case 'shipping_postcode':
					$fields['shipping'][ $field ]['class'][] = 'col-sm-4';
					break;

				default :
					$fields['shipping'][ $field ]['class'][] = 'col-sm-12';
					break;
			}
		}

		return $fields;
	}

	/**
	 * @param string $icon Old html icon string of the payment gateway.
	 * @param string $id   Id of the payment gateway.
	 *
	 * @return mixed
	 */
	public function new_gateway_icon( $icon, $id ) {
		switch ( $id ) {
			// Direct bank transfer.
			case 'bacs':
				$icon = \Unicamp_Helper::get_file_contents( UNICAMP_THEME_SVG_DIR . '/payments/pay.svg' );
				break;

			// Check Payments.
			case 'cheque':
				$icon = \Unicamp_Helper::get_file_contents( UNICAMP_THEME_SVG_DIR . '/payments/check-payments.svg' );
				break;

			// Cash on delivery.
			case 'cod':
				$icon = \Unicamp_Helper::get_file_contents( UNICAMP_THEME_SVG_DIR . '/payments/cash-on-delivery.svg' );
				break;

			// Paypal.
			case 'paypal':
				$icon = \Unicamp_Helper::get_file_contents( UNICAMP_THEME_SVG_DIR . '/payments/paypal.svg' );
				break;

			// Credit Card (Stripe).
			case 'stripe':
				$icon = \Unicamp_Helper::get_file_contents( UNICAMP_THEME_SVG_DIR . '/payments/card-credits.svg' );
				break;

			// Bitcoin.
			case 'triplea_payment_gateway':
				$icon = \Unicamp_Helper::get_file_contents( UNICAMP_THEME_SVG_DIR . '/payments/bitcoin.svg' );
				break;

			// Default Icon for other gateways.
			default:
				$icon = \Unicamp_Helper::get_file_contents( UNICAMP_THEME_SVG_DIR . '/payments/card.svg' );
				break;
		}

		return $icon;
	}
}

Checkout::instance()->initialize();
