<?php
/**
 * Product loop sale flash
 *
 * This template can be overridden by copying it to unicamp-child/woocommerce/loop/sale-flash.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       1.6.4
 */

defined( 'ABSPATH' ) || exit;

global $post, $product;
$_html = '';

if ( ! $product->is_in_stock() ) {
	$_html .= '<span class="out-of-stock">' . esc_html__( 'Sold out', 'unicamp' ) . '</span>';
} else {

	if ( '1' === Unicamp::setting( 'shop_badge_best_seller' ) && Unicamp_Woo::instance()->is_product_best_seller( $product ) ) {
		$_html .= '<span class="best-seller">' . esc_html__( 'Best Seller', 'unicamp' ) . '</span>';
	}

	if ( '1' === Unicamp::setting( 'shop_badge_hot' ) && $product->is_featured() ) {
		$_html .= '<span class="hot">' . esc_html__( 'Hot', 'unicamp' ) . '</span>';
	}

	if ( '1' === Unicamp::setting( 'shop_badge_sale' ) && $product->is_on_sale() ) {
		$_final_price = Unicamp_Woo::instance()->get_percentage_price();
		$_html        .= apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . $_final_price . '</span>', $post, $product );
	}

	if ( '1' === Unicamp::setting( 'shop_badge_new' ) ) {
		$days            = Unicamp::setting( 'shop_badge_new_days' );
		$postdate        = get_the_time( 'Y-m-d', $product->get_id() );
		$post_date_stamp = strtotime( $postdate );

		if ( ( time() - ( 60 * 60 * 24 * $days ) ) < $post_date_stamp ) {
			$_html .= '<span class="new">' . esc_html__( 'New', 'unicamp' ) . '</span>';
		}
	}

	if ( '1' === Unicamp::setting( 'shop_badge_free' ) && 0 == $product->get_price() ) {
		$_html .= '<span class="free">' . esc_html__( 'Free', 'unicamp' ) . '</span>';
	}
}

if ( $_html !== '' ) {
	echo '<div class="product-badges">' . $_html . '</div>';
}
