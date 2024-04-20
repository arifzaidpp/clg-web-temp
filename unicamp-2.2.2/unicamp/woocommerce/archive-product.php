<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to unicamp-child/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

$page_sidebar1 = Unicamp_Global::instance()->get_sidebar_1();
$page_sidebar2 = Unicamp_Global::instance()->get_sidebar_2();

$category_per_page = 4;

if ( $page_sidebar1 !== 'none' ) {
	$category_per_page = 3;
}
?>
	<div id="page-content" class="page-content">
		<div class="container">
			<div class="row">

				<?php Unicamp_Sidebar::instance()->render( 'left' ); ?>

				<div class="page-main-content">

					<?php
					/**
					 * woocommerce_archive_description hook.
					 *
					 * @hooked woocommerce_taxonomy_archive_description - 10
					 * @hooked woocommerce_product_archive_description - 10
					 */
					do_action( 'woocommerce_archive_description' );
					?>

					<?php
					/**
					 * @var string $shop_page_display
					 * empty value for only products.
					 */
					$shop_page_display = get_option( 'woocommerce_shop_page_display', '' );

					if ( function_exists( 'is_shop' ) && is_shop() && $shop_page_display !== '' ) {
						woocommerce_output_product_categories( array(
							'before' => '<div class="cats tm-swiper tm-slider" data-lg-items="' . $category_per_page . '" data-sm-items="1" data-lg-gutter="30" data-nav="1" data-loop="1"><div class="swiper-inner"><div class="swiper-container"><div class="swiper-wrapper">',
							'after'  => '</div></div></div></div>',
						) );
					}
					?>
					<?php if ( have_posts() ) : ?>

						<?php if ( ! is_shop() || $shop_page_display !== 'subcategories' ) : ?>
							<?php if ( wc_get_loop_prop( 'total' ) ) { ?>

								<?php
								/**
								 * Hook: woocommerce_before_shop_loop.
								 *
								 * @hooked wc_print_notices - 10
								 * @hooked woocommerce_result_count - 20
								 * @hooked woocommerce_catalog_ordering - 30
								 */
								do_action( 'woocommerce_before_shop_loop' );
								?>

								<?php
								$layout = Unicamp_Woo::instance()->get_shop_layout();

								$classes = [
									'hover-grayscale',
									'unicamp-main-post',
									'unicamp-grid-wrapper',
									'unicamp-product',
								];

								if ( 'list' === $layout ) {
									$classes[] = 'style-list';

									$grid_class = 'unicamp-grid grid-sm-1';

									$grid_options = [
										'type'    => 'grid',
										'columns' => 1,
									];
								} else {
									$classes[] = 'style-grid';

									$grid_class = 'unicamp-grid';
									$lg_columns = intval( Unicamp::setting( 'shop_archive_lg_columns' ) );
									$md_columns = Unicamp::setting( 'shop_archive_md_columns' );
									$sm_columns = Unicamp::setting( 'shop_archive_sm_columns' );

									if ( 'none' === Unicamp_Global::instance()->get_sidebar_status() ) {
										$lg_columns++;
									}

									$grid_class .= " grid-lg-{$lg_columns}";
									$grid_class .= " grid-md-{$md_columns}";
									$grid_class .= " grid-sm-{$sm_columns}";

									$grid_options = [
										'type'          => 'grid',
										'columns'       => $lg_columns,
										'columnsTablet' => $md_columns,
										'columnsMobile' => $sm_columns,
										'gutter'        => 30,
									];
								}
								?>
								<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
								     data-grid="<?php echo esc_attr( wp_json_encode( $grid_options ) ); ?>"
								>
									<div class="<?php echo esc_attr( $grid_class ); ?>">
										<div class="grid-sizer"></div>
										<?php
										while ( have_posts() ) {
											the_post();

											/**
											 * Hook: woocommerce_shop_loop.
											 *
											 * @hooked WC_Structured_Data::generate_product_data() - 10
											 */
											do_action( 'woocommerce_shop_loop' );

											wc_get_template_part( 'content-product', $layout );
										}
										?>
									</div>
								</div>

							<?php } ?>

							<?php
							/**
							 * woocommerce_after_shop_loop hook.
							 *
							 * @hooked woocommerce_pagination - 10
							 */
							do_action( 'woocommerce_after_shop_loop' );
							?>
						<?php endif; ?>

					<?php else : ?>

						<?php
						/**
						 * Hook: woocommerce_no_products_found.
						 *
						 * @hooked wc_no_products_found - 10
						 */
						do_action( 'woocommerce_no_products_found' );
						?>

					<?php endif; ?>
				</div>

				<?php Unicamp_Sidebar::instance()->render( 'right' ); ?>

			</div>
		</div>
	</div>
<?php
get_footer( 'shop' );

