<?php
/**
 * Loop Rating
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/rating.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! wc_review_ratings_enabled() ) {
	return;
}

$rating_count = $product->get_rating_count();

if ( $rating_count > 0 ) : ?>
	<div class="product-loop-rating">
		<?php Unicamp_Templates::render_rating( $product->get_average_rating(), [
			'wrapper_class' => 'product-star-rating',
		] ); ?>
		<div class="rating-count">
			<?php echo esc_html( sprintf( __( '(%s)', 'unicamp' ), $rating_count ) ); ?>
		</div>
	</div>
<?php endif; ?>
