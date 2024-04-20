<?php

namespace Unicamp\Woo;

defined( 'ABSPATH' ) || exit;

class Archive_Product {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'body_class', [ $this, 'body_classes' ] );

		add_filter( 'unicamp_title_bar_heading_text', [ $this, 'archive_title_bar_heading' ] );

		// Remove category from product list.
		remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );

		add_filter( 'woocommerce_get_star_rating_html', [ $this, 'change_star_rating_html' ], 10, 3 );

		add_filter( 'woocommerce_catalog_orderby', [ $this, 'custom_product_sorting' ] );

		add_filter( 'loop_shop_per_page', [ $this, 'loop_shop_per_page' ], 20 );

		add_filter( 'woocommerce_pagination_args', [ $this, 'override_pagination_args' ] );

		// Remove thumbnail & sale flash. then use custom.
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash' );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );

		add_action( 'init', [ $this, 'move_price_before_title' ], 99 );

		// Add link to the product title of loop.
		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
		add_action( 'woocommerce_shop_loop_item_title', [ $this, 'template_loop_product_title', ], 10 );
	}

	public function body_classes( $classes ) {
		$classes[] = 'woocommerce';

		if ( \Unicamp_Woo::instance()->is_product_archive() ) {
			$classes[] = 'archive-shop';
		}

		return $classes;
	}

	public function archive_title_bar_heading( $text ) {
		if ( \Unicamp_Woo::instance()->is_product_archive() ) {
			$text = \Unicamp::setting( 'product_archive_title_bar_title' );
		}

		return $text;
	}

	public function change_star_rating_html( $rating_html, $rating, $count ) {
		$rating_html = \Unicamp_Templates::render_rating( $rating, [ 'echo' => false ] );

		return $rating_html;
	}

	/**
	 * Change text of select options.
	 *
	 * @param $sorting_options
	 *
	 * @return mixed
	 */
	public function custom_product_sorting( $sorting_options ) {
		if ( isset( $sorting_options['menu_order'] ) ) {
			$sorting_options['menu_order'] = esc_html__( 'Default', 'unicamp' );
		}

		if ( isset( $sorting_options['popularity'] ) ) {
			$sorting_options['popularity'] = esc_html__( 'Popularity', 'unicamp' );
		}

		if ( isset( $sorting_options['rating'] ) ) {
			$sorting_options['rating'] = esc_html__( 'Average rating', 'unicamp' );
		}

		if ( isset( $sorting_options['date'] ) ) {
			$sorting_options['date'] = esc_html__( 'Latest', 'unicamp' );
		}

		if ( isset( $sorting_options['price'] ) ) {
			$sorting_options['price'] = esc_html__( 'Price: low to high', 'unicamp' );
		}

		if ( isset( $sorting_options['price-desc'] ) ) {
			$sorting_options['price-desc'] = esc_html__( 'Price: high to low', 'unicamp' );
		}

		return $sorting_options;
	}

	public function loop_shop_per_page() {
		if ( isset( $_GET['shop_archive_preset'] ) && in_array( $_GET['shop_archive_preset'], [
				'03',
			] ) ) {
			// Hard set post per page. because override preset settings run after init hook.
			$number = 15;
		} else {
			$number = \Unicamp::setting( 'shop_archive_number_item' );
		}

		return isset( $_GET['product_per_page'] ) ? wc_clean( $_GET['product_per_page'] ) : $number;
	}

	public function override_pagination_args( $args ) {
		$args['prev_text'] = \Unicamp_Templates::get_pagination_prev_text();
		$args['next_text'] = \Unicamp_Templates::get_pagination_next_text();

		return $args;
	}

	public function move_price_before_title() {
		if ( 'grid' === \Unicamp_Woo::instance()->get_shop_layout() ) {
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
			add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		}
	}

	/**
	 * Custom product title instead of default product title
	 *
	 * @see woocommerce_template_loop_product_title()
	 */
	public function template_loop_product_title() {
		?>
		<h2 class="woocommerce-loop-product__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>
		<?php
	}
}

Archive_Product::instance()->initialize();
