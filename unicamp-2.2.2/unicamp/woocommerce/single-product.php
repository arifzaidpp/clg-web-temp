<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to unicamp-child/woocommerce/single-product.php.
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

get_header( 'shop' );
?>
	<div id="page-content" class="page-content">
		<div class="container">
			<div class="row">

				<?php Unicamp_Sidebar::instance()->render( 'left' ); ?>

				<div class="page-main-content">
					<?php while ( have_posts() ) : the_post(); ?>

						<?php wc_get_template_part( 'content', 'single-product' ); ?>

					<?php endwhile; ?>
				</div>

				<?php Unicamp_Sidebar::instance()->render( 'right' ); ?>

			</div>
		</div>
		<?php if ( ! Unicamp_Woo::instance()->is_tutor_product() ) : ?>
			<div class="entry-after-product">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<?php do_action( 'woocommerce_after_single_product' ); ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
<?php
get_footer( 'shop' );

