<?php
defined( 'ABSPATH' ) || exit;

/**
 * Custom functions, filters, actions for WooCommerce.
 */
if ( ! class_exists( 'Unicamp_Woo' ) ) {
	class Unicamp_Woo {

		protected static $instance = null;
		const SIDEBAR_FILTERS = 'shop_filters';

		public static $product_image_size_width  = '';
		public static $product_image_size_height = '';
		public static $product_image_crop        = true;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function define_constants() {
			define( 'UNICAMP_WOO_DIR', get_template_directory() . UNICAMP_DS . 'woocommerce' );
			define( 'UNICAMP_WOO_CORE_DIR', UNICAMP_FRAMEWORK_DIR . UNICAMP_DS . 'woocommerce' );
		}

		public function initialize() {
			// Do nothing if Woo plugin not activated.
			if ( ! $this->is_activated() ) {
				return;
			}

			$this->define_constants();

			require_once UNICAMP_WOO_CORE_DIR . UNICAMP_DS . 'quick-view.php';
			require_once UNICAMP_WOO_CORE_DIR . UNICAMP_DS . 'shop-layout-switcher.php';
			require_once UNICAMP_WOO_CORE_DIR . UNICAMP_DS . 'cart.php';
			require_once UNICAMP_WOO_CORE_DIR . UNICAMP_DS . 'checkout.php';
			require_once UNICAMP_WOO_CORE_DIR . UNICAMP_DS . 'compare.php';
			require_once UNICAMP_WOO_CORE_DIR . UNICAMP_DS . 'wishlist.php';
			require_once UNICAMP_WOO_CORE_DIR . UNICAMP_DS . 'my-account.php';
			require_once UNICAMP_WOO_CORE_DIR . UNICAMP_DS . 'archive-product.php';
			require_once UNICAMP_WOO_CORE_DIR . UNICAMP_DS . 'single-product.php';

			add_filter( 'unicamp_user_profile_url', [ $this, 'update_profile_url' ] );
			add_filter( 'unicamp_user_profile_text', [ $this, 'update_profile_text' ] );

			// Register widget areas.
			add_action( 'widgets_init', [ $this, 'register_sidebars' ] );

			// Different style for sidebar.
			add_filter( 'unicamp_page_sidebar_class', [ $this, 'sidebar_class' ] );

			// Custom sidebar width.
			add_filter( 'unicamp_one_sidebar_width', [ $this, 'one_sidebar_width' ] );

			// Custom sidebar offset.
			add_filter( 'unicamp_one_sidebar_offset', [ $this, 'one_sidebar_offset' ] );

			// Add inner sidebar in main sidebar.
			add_action( 'unicamp_page_sidebar_after_content', [ $this, 'add_shop_sidebar_filter' ], 10, 2 );

			add_filter( 'woocommerce_widget_get_current_page_url', [
				$this,
				'add_shop_layout_preset_for_layered_widgets',
			], 10, 2 );

			add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'header_add_to_cart_fragment' ) );

			/**
			 * Move regular price before sale price.
			 */
			add_filter( 'woocommerce_get_price_html', [ $this, 'simple_product_price_html' ], 100, 2 );
			add_filter( 'woocommerce_variation_sale_price_html', [ $this, 'product_price_html' ], 10, 2 );
			add_filter( 'woocommerce_variation_price_html', [ $this, 'product_price_html' ], 10, 2 );
			add_filter( 'woocommerce_variable_sale_price_html', [ $this, 'product_minmax_price_html' ], 10, 2 );
			add_filter( 'woocommerce_variable_price_html', [ $this, 'product_minmax_price_html' ], 10, 2 );

			// Add span tag wrap around decimal separator.
			add_filter( 'formatted_woocommerce_price', [ $this, 'formatted_woocommerce_price' ], 10, 5 );

			add_action( 'wp_head', array( $this, 'wp_init' ) );

			// Move nav count to link.
			add_filter( 'woocommerce_layered_nav_term_html', array(
				$this,
				'move_layered_nav_count_inside_link',
			), 10, 4 );

			// Add compare & wishlist button again.
			add_action( 'woocommerce_after_add_to_cart_button', [ $this, 'get_wishlist_button_template' ] );
			add_action( 'woocommerce_after_add_to_cart_button', [ $this, 'get_compare_button_template' ] );

			/**
			 * Begin ajax requests.
			 */
			// Load more for widget Product.
			add_action( 'wp_ajax_product_infinite_load', array( $this, 'product_infinite_load' ) );
			add_action( 'wp_ajax_nopriv_product_infinite_load', array( $this, 'product_infinite_load' ) );
			/**
			 * End ajax requests.
			 */

			add_action( 'after_switch_theme', array( $this, 'change_product_image_size' ), 2 );
			add_action( 'after_setup_theme', array( $this, 'modify_theme_support' ), 10 );

			// Fix Cart fragments issue with WC 7.8.0
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_wc_cart_fragments' ), 99 );
		}

		public function enqueue_wc_cart_fragments() {
			wp_enqueue_script( 'wc-cart-fragments' );
		}

		/**
		 * Check woocommerce plugin active
		 *
		 * @return boolean true if plugin activated
		 */
		function is_activated() {
			if ( class_exists( 'WooCommerce' ) ) {
				return true;
			}

			return false;
		}

		public function update_profile_url() {
			return wc_get_page_permalink( 'myaccount' );
		}

		public function update_profile_text() {
			return esc_html__( 'My Account', 'unicamp' );
		}

		public function register_sidebars() {
			$default_args = Unicamp_Sidebar::instance()->get_default_sidebar_args();

			register_sidebar( array_merge( $default_args, [
				'id'          => 'shop_filters',
				'name'        => esc_html__( 'Shop Top Filters', 'unicamp' ),
				'description' => esc_html__( 'This sidebar displays above products list on shop catalog pages.', 'unicamp' ),
			] ) );

			register_sidebar( array_merge( $default_args, [
				'id'          => 'shop_sidebar_filters',
				'name'        => esc_html__( 'Shop Sidebar Filters', 'unicamp' ),
				'description' => esc_html__( 'This sidebar displays below Sidebar 1 on shop catalog pages.', 'unicamp' ),
			] ) );

			register_sidebar( array_merge( $default_args, [
				'id'          => 'shop_sidebar',
				'name'        => esc_html__( 'Shop Sidebar', 'unicamp' ),
				'description' => esc_html__( 'Add widgets displays on shop catalog pages.', 'unicamp' ),
			] ) );

			register_sidebar( array_merge( $default_args, [
				'id'          => 'single_shop_sidebar',
				'name'        => esc_html__( 'Single Shop Sidebar', 'unicamp' ),
				'description' => esc_html__( 'Add widgets displays on single product pages.', 'unicamp' ),
			] ) );
		}

		public function add_shop_sidebar_filter( $name, $is_first_sidebar ) {
			if ( ! $is_first_sidebar || ! $this->is_product_archive() ) {
				return;
			}
			?>
			<div class="archive-sidebar-filter">
				<p class="widget-title heading archive-sidebar-filter-heading">
					<span><?php esc_html_e( 'Filter by', 'unicamp' ); ?></span></p>
				<?php Unicamp_Sidebar::instance()->generated_sidebar( 'shop_sidebar_filters' ); ?>
			</div>
			<?php
		}

		public function add_shop_layout_preset_for_layered_widgets( $link, $widget ) {
			// Shop layout preset.
			if ( isset( $_GET['shop_archive_preset'] ) ) {
				$link = add_query_arg( 'shop_archive_preset', wc_clean( wp_unslash( $_GET['shop_archive_preset'] ) ), $link );
			}

			return $link;
		}

		public function sidebar_class( $class ) {
			if ( $this->is_woocommerce_page() ) {
				$class[] = 'style-01';
			}

			return $class;
		}

		public function one_sidebar_width( $width ) {
			if ( Unicamp_Woo::instance()->is_product_archive() ) {
				$new_width = Unicamp::setting( 'product_archive_single_sidebar_width' );
			} elseif ( is_singular( 'product' ) ) {
				$new_width = Unicamp::setting( 'product_page_single_sidebar_width' );
			}

			// Use isset instead of empty avoid skip value 0.
			if ( isset( $new_width ) && '' !== $new_width ) {
				return $new_width;
			}

			return $width;
		}

		public function one_sidebar_offset( $offset ) {
			if ( Unicamp_Woo::instance()->is_product_archive() ) {
				$new_offset = Unicamp::setting( 'product_archive_single_sidebar_offset' );
			} elseif ( is_singular( 'product' ) ) {
				$new_offset = Unicamp::setting( 'product_page_single_sidebar_offset' );
			}

			// Use isset instead of empty avoid skip value 0.
			if ( isset( $new_offset ) && '' !== $new_offset ) {
				return $new_offset;
			}

			return $offset;
		}

		/**
		 * Add span tag wrap around decimal separator
		 *
		 * @param $formatted_price
		 * @param $price
		 * @param $number_decimals
		 * @param $decimals_separator
		 * @param $thousand_separator
		 *
		 * @return mixed|string
		 */
		public function formatted_woocommerce_price( $formatted_price, $price, $number_decimals, $decimals_separator, $thousand_separator ) {
			if ( $number_decimals > 0 && ! empty( $decimals_separator ) ) {
				$origin_price = str_replace( $decimals_separator, '<span class="decimals-separator">' . $decimals_separator, $formatted_price );
				$origin_price .= '</span>';

				return $origin_price;
			}

			return $formatted_price;
		}

		function custom_price_html( $price_amt, $regular_price, $sale_price ) {
			$html_price = '';

			if ( $price_amt == $sale_price ) {
				$html_price .= '<ins>' . wc_price( $sale_price ) . '</ins>';
				$html_price .= '<del>' . wc_price( $regular_price ) . '</del>';
			} else if ( $price_amt == $regular_price ) {
				$html_price .= '<ins>' . wc_price( $regular_price ) . '</ins>';
			}

			$html_price = '<p class="price">' . $html_price . '</p>';

			return $html_price;
		}

		/**
		 * @param            $price
		 * @param WC_Product $product
		 *
		 * @return string
		 */
		public function simple_product_price_html( $price, $product ) {
			if ( $product->is_type( 'simple' ) ) {
				$regular_price = $product->get_regular_price();
				$sale_price    = $product->get_sale_price();
				$price_amt     = $product->get_price();

				return $this->custom_price_html( $price_amt, $regular_price, $sale_price );
			} else {
				return $price;
			}
		}

		public function product_price_html( $price, $variation ) {
			$variation_id = $variation->variation_id;
			//creating the product object
			$variable_product = new WC_Product( $variation_id );

			$regular_price = $variable_product->get_regular_price();
			$sale_price    = $variable_product->get_sale_price();
			$price_amt     = $variable_product->get_price();

			return $this->custom_price_html( $price_amt, $regular_price, $sale_price );
		}

		/**
		 * @param                     $price
		 * @param WC_Product_Variable $product
		 *
		 * @return string
		 */
		public function product_minmax_price_html( $price, $product ) {
			$variation_min_price         = $product->get_variation_price( 'min', true );
			$variation_max_price         = $product->get_variation_price( 'max', true );
			$variation_min_regular_price = $product->get_variation_regular_price( 'min', true );
			$variation_max_regular_price = $product->get_variation_regular_price( 'max', true );

			if ( ( $variation_min_price == $variation_min_regular_price ) && ( $variation_max_price == $variation_max_regular_price ) ) {
				$html_min_max_price = $price;
			} else {
				$html_price         = '<p class="price">';
				$html_price         .= '<ins>' . wc_price( $variation_min_price ) . '-' . wc_price( $variation_max_price ) . '</ins>';
				$html_price         .= '<del>' . wc_price( $variation_min_regular_price ) . '-' . wc_price( $variation_max_regular_price ) . '</del>';
				$html_min_max_price = $html_price;
			}

			return $html_min_max_price;
		}

		/*
		 * Change woocommerce product image size on first time switch to this theme.
		 */
		public function change_product_image_size() {
			global $pagenow;

			if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
				return;
			}

			// Update single image width
			//update_option( 'woocommerce_single_image_width', 760 );

			// Update thumbnail image width.
			update_option( 'woocommerce_thumbnail_image_width', 480 );

			// Update thumbnail cropping ratio.
			update_option( 'woocommerce_thumbnail_cropping', 'custom' );
			update_option( 'woocommerce_thumbnail_cropping_custom_width', 13 );
			update_option( 'woocommerce_thumbnail_cropping_custom_height', 15 );
		}

		/**
		 * Modify image width theme support.
		 */
		function modify_theme_support() {
			/*$theme_support                          = get_theme_support( 'woocommerce' );
			$theme_support                          = is_array( $theme_support ) ? $theme_support[0] : array();
			$theme_support['single_image_width']    = 760;
			$theme_support['thumbnail_image_width'] = 400;

			remove_theme_support( 'woocommerce' );*/
			add_theme_support( 'woocommerce' );
		}

		/**
		 * Returns true if on a page which uses WooCommerce templates exclude single product (cart and checkout are standard pages with shortcodes and which are also included)
		 *
		 * @access public
		 * @return bool
		 */
		function is_woocommerce_page_without_product() {
			if ( function_exists( 'is_shop' ) && is_shop() ) {
				return true;
			}

			if ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) {
				return true;
			}

			if ( is_post_type_archive( 'product' ) ) {
				return true;
			}

			$the_id = get_the_ID();

			if ( $the_id !== false ) {
				$woocommerce_keys = array(
					'woocommerce_shop_page_id',
					'woocommerce_terms_page_id',
					'woocommerce_cart_page_id',
					'woocommerce_checkout_page_id',
					'woocommerce_myaccount_page_id',
					'woocommerce_logout_page_id',
				);

				foreach ( $woocommerce_keys as $wc_page_id ) {
					if ( $the_id == get_option( $wc_page_id, 0 ) ) {
						return true;
					}
				}
			}

			return false;
		}

		/**
		 * Returns true if on a page which uses WooCommerce templates (cart and checkout are standard pages with shortcodes and which are also included)
		 *
		 * @access public
		 * @return bool
		 */
		function is_woocommerce_page() {
			if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
				return true;
			}

			$woocommerce_keys = array(
				'woocommerce_shop_page_id',
				'woocommerce_cart_page_id',
				'woocommerce_checkout_page_id',
				'woocommerce_myaccount_page_id',
				'woocommerce_logout_page_id',
			);

			foreach ( $woocommerce_keys as $wc_page_id ) {
				if ( get_the_ID() == get_option( $wc_page_id, 0 ) && 0 !== get_the_ID() ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Returns true if on a archive product pages.
		 *
		 * @access public
		 * @return bool
		 */
		function is_product_archive() {
			if ( is_post_type_archive( 'product' ) || ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Get list of custom attributes used for customize.
		 */
		public function get_custom_attributes_list() {
			$results = [
				'' => esc_html__( 'None', 'unicamp' ),
			];

			if ( is_admin() && $this->is_activated() ) {
				$attributes = wc_get_attribute_taxonomies();

				if ( ! empty( $attributes ) ) {
					foreach ( $attributes as $attribute ) {
						$results[ $attribute->attribute_name ] = $attribute->attribute_label;
					}
				}
			}

			return $results;
		}

		function wp_init() {
			$tabs_display = Unicamp::setting( 'single_product_tabs_style' );

			if ( 'list' === $tabs_display ) {
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
				add_action( 'woocommerce_after_single_product_summary', array(
					$this,
					'output_product_data_tabs_as_list',
				), 10 );
			}

			/**
			 * Move Up-sell section below page content.
			 */
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
			if ( Unicamp::setting( 'single_product_up_sells_enable' ) === '1' ) {
				add_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display', 15 );
			}

			/**
			 * Move Related section below page content.
			 */
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
			if ( Unicamp::setting( 'single_product_related_enable' ) === '1' ) {
				add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 15 );
			}

			// Remove Cross Sells from default position at Cart. Then add them back UNDER the Cart Table.
			remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
			if ( Unicamp::setting( 'shopping_cart_cross_sells_enable' ) === '1' ) {
				add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );
			}

			/**
			 * Hook: woocommerce_before_shop_loop.
			 *
			 * @hooked wc_print_notices - 10
			 * @hooked woocommerce_result_count - 20
			 * @hooked woocommerce_catalog_ordering - 30
			 */
			// @hooked wc_print_notices - 10
			add_action( 'woocommerce_before_shop_loop', [ $this, 'add_shop_action_begin_wrapper' ], 15 );
			// @hooked woocommerce_result_count - 20
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

			add_action( 'woocommerce_before_shop_loop', [
				$this,
				'woocommerce_result_count',
			], 20 ); // Use custom function.

			add_action( 'woocommerce_before_shop_loop', [ $this, 'add_shop_action_right_toolbar_begin_wrapper' ], 25 );

			/**
			 * Change order template priority 30 -> 40.
			 */
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

			if ( '1' === Unicamp::setting( 'shop_archive_sorting' ) ) {
				add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 40 );
			}

			if ( '1' === Unicamp::setting( 'shop_archive_filtering' ) && is_active_sidebar( self::SIDEBAR_FILTERS ) ) {
				add_action( 'woocommerce_before_shop_loop', [
					$this,
					'add_shop_action_right_toolbar_filter_button',
				], 40 );
			}

			add_action( 'woocommerce_before_shop_loop', [ $this, 'add_shop_action_right_toolbar_end_wrapper' ], 50 );

			if ( '1' === Unicamp::setting( 'shop_archive_filtering' ) ) {
				add_action( 'woocommerce_before_shop_loop', [ $this, 'add_shop_action_filter_widgets' ], 60 );
			}

			add_action( 'woocommerce_before_shop_loop', [ $this, 'add_shop_action_end_wrapper' ], 70 );
		}

		public function output_product_data_tabs_as_list() {
			$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

			if ( ! empty( $product_tabs ) ) : ?>
				<div class="entry-product-tab-list">
					<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
						<div class="entry-product-tab-list-item">
							<?php
							if ( isset( $product_tab['callback'] ) ) {
								call_user_func( $product_tab['callback'], $key, $product_tab );
							}
							?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif;
		}

		/**
		 * Output the result count text (Showing x - x of x results).
		 */
		function woocommerce_result_count() {
			/*if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
				return;
			}*/
			$args = array(
				'total'    => wc_get_loop_prop( 'total' ),
				'per_page' => wc_get_loop_prop( 'per_page' ),
				'current'  => wc_get_loop_prop( 'current_page' ),
			);

			wc_get_template( 'loop/result-count.php', $args );
		}

		public function get_shop_base_url() {
			if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
				$link = home_url();
			} elseif ( is_shop() ) {
				$link = get_permalink( wc_get_page_id( 'shop' ) );
			} elseif ( is_product_category() ) {
				$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
			} elseif ( is_product_tag() ) {
				$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
			} else {
				$queried_object = get_queried_object();
				$link           = get_term_link( $queried_object->slug, $queried_object->taxonomy );
			}

			return $link;
		}

		public function add_shop_action_filter_widgets() {
			$filtering_enable = Unicamp::setting( 'shop_archive_filtering' );

			if ( '1' === $filtering_enable && is_active_sidebar( self::SIDEBAR_FILTERS ) ) {
				?>
				<div id="archive-top-filter-widgets" class="col-md-12 archive-top-filter-widgets">
					<div class="inner">
						<div class="archive-top-filter-content">
							<?php dynamic_sidebar( self::SIDEBAR_FILTERS ); ?>
						</div>

						<?php
						$has_filters = isset( $_GET['filtering'] ) ? true : false;

						if ( $has_filters ) {
							$reset_link = $this->get_shop_base_url();

							Unicamp_Templates::render_button( [
								'wrapper_class' => 'btn-reset-filters',
								'extra_class'   => 'button-white',
								'text'          => esc_html__( 'Clear filters', 'unicamp' ),
								'link'          => [
									'url' => $reset_link,
								],
								'icon'          => 'far fa-times',
								'size'          => 'xs',
							] );
						}
						?>
					</div>
				</div>
				<?php
			}
		}

		public function add_shop_action_right_toolbar_filter_button() {
			Unicamp_Templates::render_button( [
				'wrapper_class' => 'btn-toggle-shop-filters',
				'extra_class'   => 'btn-toggle-archive-top-filters',
				'text'          => esc_html__( 'Filters', 'unicamp' ),
				'link'          => [
					'url' => 'javascript:void(0)',
				],
				'icon'          => 'far fa-filter',
				'id'            => 'btn-toggle-archive-top-filters',
			] );
		}

		public function add_shop_action_begin_wrapper() {
			echo '<div class="archive-filter-bars row row-xs-center">';
		}

		public function add_shop_action_end_wrapper() {
			echo '</div>';
		}

		public function add_shop_action_right_toolbar_begin_wrapper() {
			echo '<div class="archive-filter-bar archive-filter-bar-right col-md-6"><div class="inner">';
		}

		public function add_shop_action_right_toolbar_end_wrapper() {
			echo '</div></div>';
		}

		/**
		 * Ensure cart contents update when products are added to the cart via AJAX
		 * ========================================================================
		 *
		 * @param $fragments
		 *
		 * @return mixed
		 */
		function header_add_to_cart_fragment( $fragments ) {
			ob_start();
			$cart_html = $this->get_mini_cart();
			echo '' . $cart_html;
			$fragments['.mini-cart__button'] = ob_get_clean();

			return $fragments;
		}

		/**
		 * Get mini cart HTML
		 * ==================
		 *
		 * @return string
		 */
		function get_mini_cart() {
			global $woocommerce;
			$cart_url = '/cart';
			if ( isset( $woocommerce ) ) {
				$cart_url = wc_get_cart_url();
			}

			$cart_html = '';
			$qty       = WC()->cart->get_cart_contents_count();
			$cart_html .= '<a href="' . esc_url( $cart_url ) . '" class="mini-cart__button header-icon" title="' . esc_attr__( 'View your shopping cart', 'unicamp' ) . '">';
			$cart_html .= '<span class="mini-cart-icon" data-count="' . $qty . '"></span>';
			$cart_html .= '</a>';

			return $cart_html;
		}

		function render_mini_cart() {
			$header_type = Unicamp_Global::instance()->get_header_type();

			$enabled = Unicamp::setting( "header_style_{$header_type}_cart_enable" );

			if ( $this->is_activated() && in_array( $enabled, array( '1', 'hide_on_empty' ) ) ) {
				$classes = 'mini-cart';
				if ( $enabled === 'hide_on_empty' ) {
					$classes .= ' hide-on-empty';
				}

				if ( '03' === $header_type ) {
					$classes .= ' style-svg';
				} else {
					$classes .= ' style-normal';
				}
				?>
				<div id="mini-cart" class="<?php echo esc_attr( $classes ); ?>">
					<?php echo '' . $this->get_mini_cart(); ?>
					<div class="widget_shopping_cart_content"></div>
				</div>
			<?php }
		}

		/**
		 * @param WC_Product $product
		 *
		 * @return string
		 */
		function get_percentage_price( $product = null ) {
			if ( ! $product ) {
				global $product;
			}

			if ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) ) {
				$_regular_price = $product->get_regular_price();
				$_sale_price    = $product->get_sale_price();

				$percentage = round( ( ( $_regular_price - $_sale_price ) / $_regular_price ) * 100 );

				return "-{$percentage}%";
			} else {
				return esc_html__( 'Sale !', 'unicamp' );
			}
		}

		function get_wishlist_button_template( $args = array() ) {
			if ( ( Unicamp::setting( 'shop_archive_wishlist' ) !== '1' ) || ! class_exists( 'WPcleverWoosw' ) ) {
				return;
			}

			global $product;
			$product_id = $product->get_id();

			$defaults = array(
				'show_tooltip'     => true,
				'tooltip_position' => 'top',
				'tooltip_skin'     => 'primary',
				'style'            => '02',
			);
			$args     = wp_parse_args( $args, $defaults );

			$_wrapper_classes = "product-action wishlist-btn style-{$args['style']}";

			if ( $args['show_tooltip'] === true ) {
				$_wrapper_classes .= ' hint--bounce';
				$_wrapper_classes .= " hint--{$args['tooltip_position']}";
				$_wrapper_classes .= " hint--{$args['tooltip_skin']}";
			}
			?>
			<div class="<?php echo esc_attr( $_wrapper_classes ); ?>"
			     aria-label="<?php esc_attr_e( 'Add to wishlist', 'unicamp' ) ?>">
				<?php echo do_shortcode( '[woosw id="' . $product_id . '" type="link"]' ); ?>
			</div>
			<?php
		}

		function get_compare_button_template( $args = array() ) {
			if ( Unicamp::setting( 'shop_archive_compare' ) !== '1' || wp_is_mobile() || ! class_exists( 'WPCleverWoosc' ) ) {
				return;
			}

			global $product;
			$product_id = $product->get_id();

			$defaults = array(
				'show_tooltip'     => true,
				'tooltip_position' => 'top',
				'tooltip_skin'     => 'primary',
				'style'            => '02',
			);
			$args     = wp_parse_args( $args, $defaults );

			$_wrapper_classes = "product-action compare-btn style-{$args['style']}";

			if ( $args['show_tooltip'] === true ) {
				$_wrapper_classes .= ' hint--bounce';
				$_wrapper_classes .= " hint--{$args['tooltip_position']}";
				$_wrapper_classes .= " hint--{$args['tooltip_skin']}";
			}
			?>
			<div class="<?php echo esc_attr( $_wrapper_classes ); ?>"
			     aria-label="<?php esc_attr_e( 'Compare', 'unicamp' ) ?>">
				<?php echo do_shortcode( '[woosc id="' . $product_id . '" type="link"]' ); ?>
			</div>
			<?php
		}

		public function get_quick_view_button_template( $args = array() ) {
			if ( '1' !== Unicamp::setting( 'shop_archive_quick_view' ) || wp_is_mobile() ) {
				return;
			}

			global $product;
			$product_id = $product->get_id();

			$defaults = array(
				'show_tooltip'     => true,
				'tooltip_position' => 'top',
				'tooltip_skin'     => 'primary',
				'style'            => '01',
			);
			$args     = wp_parse_args( $args, $defaults );

			$_wrapper_classes = "product-action quick-view-btn style-{$args['style']}";

			if ( $args['show_tooltip'] === true ) {
				$_wrapper_classes .= ' hint--bounce';
				$_wrapper_classes .= " hint--{$args['tooltip_position']}";
				$_wrapper_classes .= " hint--{$args['tooltip_skin']}";
			}
			?>
			<div class="<?php echo esc_attr( $_wrapper_classes ); ?>"
			     aria-label="<?php echo esc_attr__( 'Quick view', 'unicamp' ) ?>"
			     data-pid="<?php echo esc_attr( $product_id ); ?>"
			     data-pnonce="<?php echo wp_create_nonce( 'woo_quick_view' ); ?>">
				<a class="quick-view-icon" href="#"></a>
			</div>
			<?php
		}

		public function product_infinite_load() {
			$source = isset( $_POST['source'] ) ? $_POST['source'] : '';

			if ( 'current_query' === $source ) {
				$query_vars                = $_POST['query_vars'];
				$query_vars['paged']       = $_POST['paged'];
				$query_vars['nopaging']    = false;
				$query_vars['post_status'] = 'publish';

				$unicamp_query = new WP_Query( $query_vars );
			} else {
				$query_args = array(
					'post_type'      => $_POST['post_type'],
					'posts_per_page' => $_POST['posts_per_page'],
					'orderby'        => $_POST['orderby'],
					'order'          => $_POST['order'],
					'paged'          => $_POST['paged'],
					'post_status'    => 'publish',
				);

				if ( ! empty( $_POST['extra_taxonomy'] ) ) {
					$query_args = Unicamp_Helper::build_extra_terms_query( $query_args, $_POST['extra_taxonomy'] );
				}

				$unicamp_query = new WP_Query( $query_args );
			}

			$response = array(
				'max_num_pages' => $unicamp_query->max_num_pages,
				'found_posts'   => $unicamp_query->found_posts,
				'count'         => $unicamp_query->post_count,
			);

			ob_start();

			if ( $unicamp_query->have_posts() ) :

				while ( $unicamp_query->have_posts() ) : $unicamp_query->the_post();
					wc_get_template_part( 'content', 'product' );
				endwhile;

				wp_reset_postdata();

			endif;

			$template = ob_get_contents();
			ob_clean();

			$response['template'] = $template;

			echo json_encode( $response );

			wp_die();
		}

		function get_product_image_loop( $args = array() ) {
			$defaults = array(
				'extra_class' => '',
			);

			$args = wp_parse_args( $args, $defaults );

			// Calculate product loop image size.
			if ( self::$product_image_size_width === '' ) {
				$width = 400;

				$cropping = get_option( 'woocommerce_thumbnail_cropping' );
				$height   = $width;

				if ( $cropping === 'custom' ) {
					$ratio_w = get_option( 'woocommerce_thumbnail_cropping_custom_width' );
					$ratio_h = get_option( 'woocommerce_thumbnail_cropping_custom_height' );

					$height = ( $width * $ratio_h ) / $ratio_w;
					$height = (int) $height;
				} elseif ( $cropping === 'uncropped' ) {
					self::$product_image_crop = false;
					$height                   = 9999;
				}

				self::$product_image_size_width  = $width;
				self::$product_image_size_height = $height;
			}

			$image_args = array(
				'id'     => $args['id'],
				'size'   => 'custom',
				'width'  => self::$product_image_size_width,
				'height' => self::$product_image_size_height,
				'crop'   => self::$product_image_crop,
			);

			if ( $args['extra_class'] !== '' ) {
				$image_args['class'] = $args['extra_class'];
			}

			Unicamp_Image::the_attachment_by_id( $image_args );
		}

		function move_layered_nav_count_inside_link( $term_html, $term, $link, $count ) {
			if ( $count > 0 ) {
				$term_html = str_replace( '</a>', '', $term_html );

				$find    = '</span>';
				$replace = '</span></a>';
				$pos     = strrpos( $term_html, $find );

				if ( $pos !== false ) {
					$term_html = substr_replace( $term_html, $replace, $pos, strlen( $find ) );
				}
			}

			return $term_html;
		}

		public function get_single_product_style() {
			$style = Unicamp_Helper::get_post_meta( 'single_product_layout_style' );

			if ( empty( $style ) ) {
				$style = Unicamp::setting( 'single_product_layout_style' );
			}

			return $style;
		}

		/**
		 * @param WC_Product $product Check if product is in best selling list.
		 *
		 * @return bool
		 */
		public function is_product_best_seller( $product ) {
			$product_id       = $product->get_id();
			$best_selling_ids = $this->get_best_selling_products();

			if ( in_array( $product_id, $best_selling_ids ) ) {
				return true;
			}

			return false;
		}

		public function get_best_selling_products() {
			$transient_name = 'unicamp-product-best-selling-ids';

			$products = get_transient( $transient_name );

			if ( false === $products ) {
				$number = Unicamp::setting( 'shop_badge_best_seller_number' );

				$args = [
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'no_found_rows'  => true,
					'posts_per_page' => $number,
					'meta_query'     => array(
						array(
							'key'     => 'total_sales',
							'value'   => '0',
							'compare' => '>',
						),
					),
					'meta_key'       => 'total_sales',
					'orderby'        => 'meta_value_num',
					'order'          => 'DESC',
				];

				$query = new WP_Query( $args );

				$products = [];

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();

						$products[] = get_the_ID();
					}

					wp_reset_postdata();
				}

				set_transient( $transient_name, $products, DAY_IN_SECONDS );
			}

			return $products;
		}

		/**
		 * Check if product is a tutor product.
		 *
		 * @param mixed int|WC_Product $product
		 *
		 * @return bool
		 */
		public function is_tutor_product( $product = null ) {
			if ( null === $product ) {
				global $product;
			}

			$product_id = $product;

			if ( $product instanceof WC_Product ) {
				$product_id = $product->get_id();
			}

			$is_tutor = get_post_meta( $product_id, '_tutor_product', true );

			if ( 'yes' === $is_tutor ) {
				return true;
			}

			return false;
		}

		/**
		 * Check product is added to cart
		 *
		 * @param $product_id
		 *
		 * @since   2.0.0
		 * @version 2.0.0
		 *
		 * @return bool
		 */
		public function is_product_in_cart( $product_id ) {
			if ( ! empty( WC()->cart ) && ! WC()->cart->is_empty() ) {
				// Loop though cart items.
				foreach ( WC()->cart->get_cart() as $cart_item ) :
					// Handling also variable products and their products variations.
					$cart_item_ids = array( $cart_item['product_id'], $cart_item['variation_id'] );

					if ( in_array( $product_id, $cart_item_ids ) ) {
						return true;
					}
				endforeach;
			}

			return false;
		}

		public function get_shop_layout() {
			if ( '1' === Unicamp::setting( 'shop_archive_layout_switcher' ) && isset( $_COOKIE['shop_layout'] ) ) {
				return $_COOKIE['shop_layout'];
			}

			return Unicamp::setting( 'shop_archive_layout' );
		}

		/**
		 * Alt function to get image of product with custom size
		 *
		 * @see    WC_Product::get_image()
		 *
		 * @param WC_Product $product
		 * @param string     $size
		 *
		 * @return string $image HTML img tag
		 */
		public function get_product_image( $product, $size = 'thumbnail' ) {
			$image = '';
			if ( $product->get_image_id() ) {
				$image = Unicamp_Image::get_attachment_by_id( [
					'id'   => $product->get_image_id(),
					'size' => $size,
				] );
			} elseif ( $product->get_parent_id() ) {
				$parent_product = wc_get_product( $product->get_parent_id() );
				if ( $parent_product ) {
					$image = Unicamp_Image::get_attachment_by_id( [
						'id'   => $parent_product->get_image_id(),
						'size' => $size,
					] );
				}
			}

			if ( ! $image ) {
				$image = wc_placeholder_img( $size );
			}

			return $image;
		}
	}

	Unicamp_Woo::instance()->initialize();
}
