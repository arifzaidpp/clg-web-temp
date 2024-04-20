<?php
/**
 * Result Count
 *
 * Shows text: Showing x - x of x results.
 *
 * This template can be overridden by copying it to unicamp-child/woocommerce/loop/result-count.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="archive-filter-bar archive-filter-bar-left col-md-6">
	<div class="archive-result-count woocommerce-result-count">
		<?php
		$result_count_html = sprintf( _n( '%s product', '%s products', $total, 'unicamp' ), '<span class="count">' . number_format_i18n( $total ) . '</span>' );
		printf(
			wp_kses(
				__( 'We found %s available for you', 'unicamp' ),
				array( 'span' => [ 'class' => [] ] )
			),
			$result_count_html
		);
		?>
	</div>
</div>
