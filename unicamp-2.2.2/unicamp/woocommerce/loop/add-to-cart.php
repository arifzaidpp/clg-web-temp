<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to unicamp-child/woocommerce/loop/add-to-cart.php.
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
 * @version       3.3.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$button_wrap_classes[] = 'product-action woocommerce_loop_add_to_cart_wrap';

if ( ! empty( $args['tooltip_position'] ) ) {
	$button_wrap_classes[] = 'hint--primary hint--bounce';
	$button_wrap_classes[] = 'hint--' . $args['tooltip_position'];
}
?>
<div class="<?php echo esc_attr( implode( ' ', $button_wrap_classes ) ); ?>"
     aria-label="<?php echo esc_attr( $product->add_to_cart_text() ); ?>">
	<?php
	echo apply_filters( 'woocommerce_loop_add_to_cart_link',
		sprintf(
			'<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
			esc_attr( $product->get_id() ),
			esc_attr( $product->get_sku() ),
			esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
			esc_html( $product->add_to_cart_text() )
		),
		$product
	);
	?>
</div>
