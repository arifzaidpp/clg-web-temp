<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Tutor_Cart' ) ) {
	class Unicamp_Tutor_Cart {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			/**
			 * Show course title, thumbnail, link instead of.
			 */
			add_filter( 'woocommerce_cart_item_name', [ $this, 'change_cart_item_name' ], 10, 3 );
			add_filter( 'woocommerce_cart_item_permalink', [ $this, 'change_cart_item_permalink' ], 10, 3 );
			add_filter( 'woocommerce_cart_item_thumbnail', [ $this, 'change_cart_item_thumbnail' ], 10, 3 );

			/**
			 * Show View cart button instead of Add to cart if the product added to cart.
			 */
			add_filter( 'woocommerce_loop_add_to_cart_link', [ $this, 'use_view_cart_button' ], 10, 2 );
		}

		public function change_cart_item_name( $name, $cart_item, $cart_item_key ) {
			/**
			 * @var WC_Product $_product
			 */
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			$course = Unicamp_Tutor::instance()->get_course_by_wc_product( $_product->get_id() );

			if ( ! empty( $course ) ) {
				$name = $course->post_title;
			}

			return $name;
		}

		public function change_cart_item_permalink( $link, $cart_item, $cart_item_key ) {
			/**
			 * @var WC_Product $_product
			 */
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			$course = Unicamp_Tutor::instance()->get_course_by_wc_product( $_product->get_id() );

			if ( ! empty( $course ) ) {
				$link = get_permalink( $course );
			}

			return $link;
		}

		public function change_cart_item_thumbnail( $thumbnail, $cart_item, $cart_item_key ) {
			/**
			 * @var WC_Product $_product
			 */
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			$course = Unicamp_Tutor::instance()->get_course_by_wc_product( $_product->get_id() );

			if ( ! empty( $course ) ) {
				$thumbnail = get_the_post_thumbnail( $course, 'thumbnail' );
			}

			return $thumbnail;
		}

		/**
		 * Show view cart button instead of add to cart when course adding to cart.
		 *
		 * @since   2.0.0
		 * @version 2.0.0
		 *
		 * @param string     $link Old link html
		 * @param WC_Product $product
		 *
		 * @return string $link Final link html
		 */
		public function use_view_cart_button( $link, $product ) {
			if ( Unicamp_Woo::instance()->is_tutor_product( $product ) ) {
				if ( Unicamp_Woo::instance()->is_product_in_cart( $product->get_id() ) ) {
					$cart_url = apply_filters( 'woocommerce_add_to_cart_redirect', wc_get_cart_url(), null );

					$link = apply_filters( 'unicamp_course_loop_view_cart_link', sprintf(
						'<a href="%1$s" class="added_to_cart wc-forward" title="%2$s">%2$s</a>',
						$cart_url,
						esc_attr__( 'View cart', 'unicamp' )
					), $link, $product );
				}
			}

			return $link;
		}
	}
}
