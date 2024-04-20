<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to unicamp-child/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.9.0
 */

defined( 'ABSPATH' ) || exit;

$lg_items = 5;
if ( 'none' === Unicamp_Global::instance()->get_sidebar_status() ) {
	$lg_items--;
}

if ( $related_products ) : ?>

	<div class="related products">
		<?php
		$heading = apply_filters( 'woocommerce_product_related_products_heading', esc_html__( 'Related products', 'unicamp' ) );

		if ( $heading ) :
			?>
			<h2 class="product-section-heading"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

		<div class="tm-swiper tm-slider unicamp-product style-grid"
		     data-lg-items="<?php echo esc_attr( $lg_items ); ?>"
		     data-md-items="3"
		     data-sm-items="2"
		     data-lg-gutter="30"
		     data-nav="1"
		>
			<div class="swiper-inner">
				<div class="swiper-container">
					<div class="swiper-wrapper">
						<?php foreach ( $related_products as $related_product ) : ?>

							<?php
							$post_object = get_post( $related_product->get_id() );
							setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
							?>
							<div class="swiper-slide">
								<?php wc_get_template_part( 'content', 'product' ); ?>
							</div>

						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>

	</div>

<?php endif;

wp_reset_postdata();
