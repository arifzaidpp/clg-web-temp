<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to unicamp-child/woocommerce/content-product-cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.7.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="swiper-slide">
	<div <?php wc_product_cat_class( '', $category ); ?>>

		<?php
		/**
		 * woocommerce_before_subcategory hook.
		 *
		 * @hooked woocommerce_template_loop_category_link_open - 10
		 */
		do_action( 'woocommerce_before_subcategory', $category );
		?>

		<div class="cat-image">
			<?php
			/**
			 * woocommerce_before_subcategory_title hook.
			 *
			 * @hooked woocommerce_subcategory_thumbnail - 10
			 */
			do_action( 'woocommerce_before_subcategory_title', $category );
			?>
		</div>
		<div class="cat-text">
			<div class="inner">
				<h5 class="cat-title">
					<?php echo esc_html( $category->name ); ?>
				</h5>
				<p class="cat-count">
					<?php if ( $category->count > 0 ) {
						echo apply_filters( 'woocommerce_subcategory_count_html', ' ' . sprintf( _n( '%s product', '%s products', $category->count, 'unicamp' ), $category->count ), $category );
					} ?>
				</p>
				<?php
				/**
				 * woocommerce_after_subcategory_title hook
				 */
				do_action( 'woocommerce_after_subcategory_title', $category );
				?>
			</div>
		</div>

		<?php
		/**
		 * woocommerce_after_subcategory hook.
		 *
		 * @hooked woocommerce_template_loop_category_link_close - 10
		 */
		do_action( 'woocommerce_after_subcategory', $category );
		?>

	</div>
</div>
