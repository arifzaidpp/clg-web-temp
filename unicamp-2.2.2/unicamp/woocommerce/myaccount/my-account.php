<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to unicamp-child/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;
?>
<?php if ( is_user_logged_in() ) : ?>
	<?php
	$current_user = wp_get_current_user();
	if ( ( $current_user instanceof WP_User ) ) {
		?>
		<div class="my-account-profile">
			<div class="my-avatar">
				<?php echo get_avatar( $current_user->ID, 100 ); ?>
			</div>
			<div class="my-info">
				<div class="welcome-text"><?php esc_html_e( 'Hello!', 'unicamp' ); ?></div>
				<h6 class="my-name fn"><?php echo esc_html( $current_user->display_name ); ?></h6>
			</div>
		</div>
	<?php } ?>
<?php endif; ?>
<div class="row">
	<div class="col-lg-3 col-md-4">
		<?php
		/**
		 * My Account navigation.
		 *
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_account_navigation' );
		?>
	</div>
	<div class="col-lg-9 col-md-8">
		<div class="woocommerce-MyAccount-content">
			<?php
			/**
			 * My Account content.
			 *
			 * @since 2.6.0
			 */
			do_action( 'woocommerce_account_content' );
			?>
		</div>
	</div>
</div>
