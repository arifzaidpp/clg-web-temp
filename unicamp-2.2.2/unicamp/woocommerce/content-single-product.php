<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to unicamp-child/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.

	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'entry-product', $product ); ?>>

	<div id="woo-single-info" class="row tm-sticky-parent woo-single-info">
		<div class="col-md-6">
			<div class="tm-sticky-column">
				<div class="woo-single-images">
					<?php
					/**
					 * woocommerce_before_single_product_summary hook.
					 *
					 * @hooked woocommerce_show_product_sale_flash - 10
					 * @hooked woocommerce_show_product_images - 20
					 */
					do_action( 'woocommerce_before_single_product_summary' );
					?>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="tm-sticky-column">
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
				<div class="summary entry-summary cart-notification" data-notification="<?php echo esc_attr( wp_json_encode( $notification_settings ) ); ?>">
					<?php
					/**
					 * woocommerce_single_product_summary hook.
					 *
					 * @hooked woocommerce_template_single_title - 5
					 * @hooked woocommerce_template_single_rating - 10
					 * @hooked woocommerce_template_single_price - 10
					 * @hooked woocommerce_template_single_excerpt - 20
					 * @hooked woocommerce_template_single_add_to_cart - 30
					 * @hooked woocommerce_template_single_meta - 40
					 * @hooked woocommerce_template_single_sharing - 50
					 */
					do_action( 'woocommerce_single_product_summary' );
					?>
				</div>
			</div>
		</div>
	</div>

	<?php
	/**
	 * woocommerce_after_single_product_summary hook.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>

</div>
