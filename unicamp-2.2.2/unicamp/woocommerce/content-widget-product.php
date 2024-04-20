<?php
/**
 * The template for displaying product widget entries.
 *
 * This template can be overridden by copying it to unicamp-child/woocommerce/content-widget-product.php.
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.5
 */

defined( 'ABSPATH' ) || exit;

/** @var WC_Product $product */
global $product;

if ( ! is_a( $product, 'WC_Product' ) ) {
	return;
}
?>
<li>
	<?php do_action( 'woocommerce_widget_product_item_start', $args ); ?>

	<div class="product-item">
		<div class="thumbnail">
			<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
				<?php echo wp_kses( $product->get_image( 'thumbnail' ), 'unicamp-img' ); ?>
			</a>
		</div>
		<div class="info">
			<span class="price">
				<?php echo wp_kses( $product->get_price_html(), 'unicamp-default' ); ?>
			</span>
			<h6 class="product-title">
				<a href="<?php the_permalink(); ?>">
					<?php echo wp_kses_post( $product->get_name() ); ?>
				</a>
			</h6>
			<?php if ( ! empty( $show_rating ) ) : ?>
				<?php echo wp_kses_post( wc_get_rating_html( $product->get_average_rating() ) ); ?>
			<?php endif; ?>
		</div>
	</div>

	<?php do_action( 'woocommerce_widget_product_item_end', $args ); ?>
</li>
