<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to unicamp-child/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

?>
<div class="woocommerce-form-coupon-wrap">
	<div class="woocommerce-form-coupon-toggle">
		<?php echo apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'unicamp' ) . ' <a href="#" class="showcoupon link-transition-02">' . esc_html__( 'Click here to enter your code', 'unicamp' ) . '</a>' ) ?>
	</div>

	<form class="checkout_coupon woocommerce-form-coupon" method="post" style="display:none">
		<div class="form-group">
			<input type="text" name="coupon_code" class="input-text"
			       placeholder="<?php esc_attr_e( 'Coupon code', 'unicamp' ); ?>" id="coupon_code" value=""/>
			<button type="submit" class="button btn-apply-coupon" name="apply_coupon"
			        value="<?php esc_attr_e( 'Apply coupon', 'unicamp' ); ?>"><span
					class="btn-icon fal fa-gift"></span><?php esc_html_e( 'Apply coupon', 'unicamp' ); ?></button>
		</div>
	</form>
</div>
