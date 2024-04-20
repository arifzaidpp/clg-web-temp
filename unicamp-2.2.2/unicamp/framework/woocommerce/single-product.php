<?php

namespace Unicamp\Woo;

defined( 'ABSPATH' ) || exit;

class Single_Product {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'body_class', [ $this, 'body_classes' ] );

		add_filter( 'unicamp_title_bar_heading_text', [ $this, 'single_title_bar_heading' ] );

		add_action( 'woocommerce_single_product_summary', [ $this, 'template_single_category' ], 4 );

		add_filter( 'woocommerce_output_related_products_args', [ $this, 'related_products_args' ] );

		// Move product rating after product price.
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
		add_action( 'woocommerce_single_product_summary', [ $this, 'template_single_review_rating' ], 5 );
		add_action( 'unicamp_template_before_product_single_rating', [
			$this,
			'add_custom_attribute_beside_rating',
		] );

		// Add sharing list.
		add_action( 'woocommerce_share', [ $this, 'entry_sharing' ] );

		// Change review avatar size.
		add_filter( 'woocommerce_review_gravatar_size', [ $this, 'woocommerce_review_gravatar_size' ] );

		add_action( 'woocommerce_before_add_to_cart_button', [ $this, 'add_begin_wrapper_button_template', ], 0 );
		add_action( 'woocommerce_after_add_to_cart_button', [ $this, 'add_end_wrapper_button_template', ], 9999 );

		// Change compare button color on popup.
		add_filter( 'woosc_bar_btn_color_default', [ $this, 'change_compare_button_color' ] );

		add_action( 'woocommerce_before_quantity_input_field', [ $this, 'add_quantity_increase_button' ] );
		add_action( 'woocommerce_after_quantity_input_field', [ $this, 'add_quantity_decrease_button' ] );

		// Add div tag wrapper quantity.
		add_action( 'woocommerce_before_add_to_cart_quantity', [ $this, 'add_quantity_open_wrapper' ] );
		add_action( 'woocommerce_after_add_to_cart_quantity', [ $this, 'add_quantity_close_wrapper' ] );
	}

	public function body_classes( $classes ) {
		if ( is_singular( 'product' ) ) {
			$product_feature_style = \Unicamp_Woo::instance()->get_single_product_style();
			$classes[]             = "single-product-{$product_feature_style}";
		}

		return $classes;
	}

	public function template_single_category() {
		global $product;

		if ( '1' === \Unicamp::setting( 'single_product_categories_enable' ) ) {
			echo wc_get_product_category_list( $product->get_id(), ' / ', '<div class="entry-product-categories">', '</div>' );
		}
	}

	public function single_title_bar_heading( $text ) {
		if ( is_product() ) {
			$text = \Unicamp_Helper::get_post_meta( 'page_title_bar_custom_heading', '' );

			if ( '' === $text ) {
				$text = \Unicamp::setting( 'product_single_title_bar_title' );
			}

			if ( '' === $text ) {
				$text = get_the_title();
			}
		}

		return $text;
	}

	public function related_products_args( $args ) {
		$number = \Unicamp::setting( 'product_related_number' );

		$args['posts_per_page'] = $number;

		return $args;
	}

	public function template_single_review_rating() {
		?>
		<div class="entry-meta-review-rating">
			<div class="inner">
				<?php do_action( 'unicamp_template_before_product_single_rating' ); ?>

				<?php
				if ( function_exists( 'woocommerce_template_single_rating' ) ) {
					woocommerce_template_single_rating();
				}
				?>
			</div>
		</div>
		<?php
	}

	public function add_custom_attribute_beside_rating() {
		$attribute_name = \Unicamp::setting( 'single_product_custom_attribute' );

		if ( empty( $attribute_name ) ) {
			return;
		}

		global $product;

		$attribute_value = $product->get_attribute( $attribute_name );

		if ( empty( $attribute_value ) ) {
			return;
		}
		?>
		<div class="entry-product-custom-attribute">
			<?php if ( '1' === \Unicamp::setting( 'single_product_custom_attribute_label' ) ): ?>
				<span class="custom-attribute-name">
						<?php
						$attribute_id   = wc_attribute_taxonomy_id_by_name( $attribute_name );
						$attribute_info = wc_get_attribute( $attribute_id );

						echo esc_html( $attribute_info->name . ': ' );
						?>
					</span>
			<?php endif; ?>

			<span class="custom-attribute-value primary-color"><?php echo esc_html( $attribute_value ); ?></span>
		</div>
		<?php
	}

	public function entry_sharing() {
		if ( '1' === \Unicamp::setting( 'single_product_sharing_enable' ) && class_exists( 'InsightCore' ) ) :
			$social_sharing = \Unicamp::setting( 'social_sharing_item_enable' );
			if ( ! empty( $social_sharing ) ) {
				?>
				<div class="entry-product-share">
					<div class="inner">
						<?php \Unicamp_Templates::get_sharing_list( [
							'brand_color' => true,
						] ); ?>
					</div>
				</div>
				<?php
			}
		endif;
	}

	public function woocommerce_review_gravatar_size() {
		return \Unicamp::COMMENT_AVATAR_SIZE;
	}

	public function add_begin_wrapper_button_template() {
		echo '<div class="entry-product-actions">';
	}

	public function add_end_wrapper_button_template() {
		echo '</div>';
	}

	public function change_compare_button_color() {
		$primary_color = \Unicamp::setting( 'primary_color' );

		return $primary_color;
	}

	public function add_quantity_increase_button() {
		echo '<button type="button" class="increase"></button>';
	}

	public function add_quantity_decrease_button() {
		echo '<button type="button" class="decrease"></button>';
	}

	public function add_quantity_open_wrapper() {
		?>
		<div class="quantity-button-wrapper">
		<label><?php esc_html_e( 'Quantity', 'unicamp' ); ?></label>
		<?php
	}

	public function add_quantity_close_wrapper() {
		global $product;

		echo wc_get_stock_html( $product ); // WPCS: XSS ok.
		?>
		</div>
		<?php
	}
}

Single_Product::instance()->initialize();
