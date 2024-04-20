<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_WP_Widget_Product_Price_Filter' ) ) {
	class Unicamp_WP_Widget_Product_Price_Filter extends Unicamp_WC_Widget_Base {

		private $_check_ranges = false;

		public function __construct() {
			$this->widget_id          = 'unicamp-wp-widget-product-price-filter';
			$this->widget_cssclass    = 'unicamp-wp-widget-product-price-filter unicamp-wp-widget-filter unicamp-wp-widget-product-filter';
			$this->widget_name        = esc_html__( '[Unicamp] Product Price Filter', 'unicamp' );
			$this->widget_description = esc_html__( 'Display a price range list to filter products in your store.', 'unicamp' );
			$this->settings           = array(
				'title'        => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Price Filter', 'unicamp' ),
					'label' => esc_html__( 'Title', 'unicamp' ),
				),
				'display_type' => array(
					'type'    => 'select',
					'std'     => 'list',
					'label'   => esc_html__( 'Display type', 'unicamp' ),
					'options' => array(
						'list'   => esc_html__( 'List', 'unicamp' ),
						'inline' => esc_html__( 'Inline', 'unicamp' ),
					),
				),
			);

			parent::__construct();
		}

		public function widget( $args, $instance ) {
			// Based on woo widget @version  2.3.0
			global $wp_the_query;

			if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
				return;
			}

			if ( ! $wp_the_query->post_count ) {
				return;
			}

			$min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';
			$max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '';

			// Find min and max price in current result set
			$prices = $this->get_filtered_price();
			$min    = floor( $prices->min_price );
			$max    = ceil( $prices->max_price );

			if ( $min === $max ) {
				return;
			}

			$this->widget_start( $args, $instance );

			/**
			 * Adjust max if the store taxes are not displayed how they are stored.
			 * Min is left alone because the product may not be taxable.
			 * Kicks in when prices excluding tax are displayed including tax.
			 */
			if ( wc_tax_enabled() && 'incl' === get_option( 'woocommerce_tax_display_shop' ) && ! wc_prices_include_tax() ) {
				$tax_classes = array_merge( array( '' ), WC_Tax::get_tax_classes() );
				$class_max   = $max;

				foreach ( $tax_classes as $tax_class ) {
					if ( $tax_rates = WC_Tax::get_rates( $tax_class ) ) {
						$class_max = $max + WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $max, $tax_rates ) );
					}
				}

				$max = $class_max;
			}

			$links = $this->generate_price_links( $min, $max, $min_price, $max_price );


			$display_type = $this->get_value( $instance, 'display_type' );

			$class = ' filter-radio-list unicamp-product-price-filter';
			$class .= ' show-display-' . $display_type;

			if ( ! empty( $links ) ) {
				?>
				<ul class="<?php echo esc_attr( $class ); ?>">
					<?php foreach ( $links as $link ) : ?>
						<li class="<?php echo esc_attr( $link['class'] ); ?>">
							<a href="<?php echo esc_url( $link['href'] ); ?>"><?php echo '' . $link['title']; ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php
			}
			$this->widget_end( $args );
		}

		/**
		 * Get filtered min price for current products.
		 *
		 * @return mixed
		 */
		protected function get_filtered_price() {
			global $wpdb, $wp_the_query;

			$args       = $wp_the_query->query_vars;
			$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
			$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

			if ( ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
				$tax_query[] = array(
					'taxonomy' => $args['taxonomy'],
					'terms'    => array( $args['term'] ),
					'field'    => 'slug',
				);
			}

			foreach ( $meta_query as $key => $query ) {
				if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
					unset( $meta_query[ $key ] );
				}
			}

			$meta_query = new WP_Meta_Query( $meta_query );
			$tax_query  = new WP_Tax_Query( $tax_query );

			$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

			$sql = "SELECT min( CAST( price_meta.meta_value AS UNSIGNED ) ) as min_price, max( CAST( price_meta.meta_value AS UNSIGNED ) ) as max_price FROM {$wpdb->posts} ";
			$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
			$sql .= " 	WHERE {$wpdb->posts}.post_type = 'product'
						AND {$wpdb->posts}.post_status = 'publish'
						AND price_meta.meta_key IN ('" . implode( "','",
					array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
						AND price_meta.meta_value > '' ";
			$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

			return $wpdb->get_row( $sql );
		}

		private function generate_price_links( $min, $max, $min_price, $max_price ) {
			$links = array();

			// Remember current filters/search
			$link          = $this->get_current_page_url();
			$link_no_price = remove_query_arg( 'min_price', $link );
			$link_no_price = remove_query_arg( 'max_price', $link_no_price );

			$need_more = false;

			$steps = apply_filters( 'unicamp_price_filter_steps', 5 );

			$step_value = $max / $steps;

			if ( $step_value < 10 ) {
				$step_value = 10;
			}

			$step_value = round( $step_value, -1 );

			// Link to all prices
			$links[] = array(
				'href'  => $link_no_price,
				'title' => esc_html__( 'All', 'unicamp' ),
				'class' => '',
			);

			for ( $i = 0; $i < (int) $steps; $i++ ) {

				$step_class = $href = '';

				$step_min = $step_value * $i;

				$step_max = $step_value * ( $i + 1 );

				if ( $step_max > $max ) {
					$need_more = true;
					$i++;
					break;
				}

				$href = add_query_arg( 'min_price', $step_min, $link );
				$href = add_query_arg( 'max_price', $step_max, $href );
				$href = add_query_arg( 'filtering', '1', $href );

				$step_title = wc_price( $step_min ) . ' - ' . wc_price( $step_max );

				if ( ! empty( $min_price ) && ! empty( $max_price ) && ( $min_price >= $step_min && $max_price <= $step_max ) || ( $i == 0 && ! empty( $max_price ) && $max_price <= $step_max ) ) {
					$step_class = 'chosen';
				}

				if ( $this->check_range( $step_min, $step_max ) ) {
					$links[] = array(
						'href'  => $href,
						'title' => $step_title,
						'class' => $step_class,
					);
				}
			}

			if ( $max > $step_max ) {
				$need_more = true;
				$step_min  = $step_value * $i;
			}

			if ( $need_more ) {

				$step_class = $href = '';

				$href = add_query_arg( 'min_price', $step_min, $link );
				$href = add_query_arg( 'max_price', $max, $href );
				$href = add_query_arg( 'filtering', '1', $href );

				$step_title = wc_price( $step_min ) . ' +';

				if ( $min_price >= $step_min && $max_price <= $max ) {
					$step_class = 'chosen';
				}

				if ( $this->check_range( $step_min, $max ) ) {
					$links[] = array(
						'href'  => $href,
						'title' => $step_title,
						'class' => $step_class,
					);
				}
			}

			return $links;
		}

		private function check_range( $min, $max ) {

			if ( ! $this->_check_ranges ) {
				return true;
			}

			if ( 0 === sizeof( WC()->query->layered_nav_product_ids ) ) {
				$min = floor( $wpdb->get_var( "
					SELECT min(meta_value + 0)
					FROM {$wpdb->posts} as posts
					LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
					WHERE meta_key IN ('" . implode( "','",
						array_map( 'esc_sql',
							apply_filters( 'woocommerce_price_filter_meta_keys',
								array( '_price', '_min_variation_price' ) ) ) ) . "')
					AND meta_value != ''
				" ) );
				$max = ceil( $wpdb->get_var( "
					SELECT max(meta_value + 0)
					FROM {$wpdb->posts} as posts
					LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
					WHERE meta_key IN ('" . implode( "','",
						array_map( 'esc_sql',
							apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
				" ) );
			} else {
				$min = floor( $wpdb->get_var( "
					SELECT min(meta_value + 0)
					FROM {$wpdb->posts} as posts
					LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
					WHERE meta_key IN ('" . implode( "','",
						array_map( 'esc_sql',
							apply_filters( 'woocommerce_price_filter_meta_keys',
								array( '_price', '_min_variation_price' ) ) ) ) . "')
					AND meta_value != ''
					AND (
						posts.ID IN (" . implode( ',', array_map( 'absint', WC()->query->layered_nav_product_ids ) ) . ")
						OR (
							posts.post_parent IN (" . implode( ',',
						array_map( 'absint', WC()->query->layered_nav_product_ids ) ) . ")
							AND posts.post_parent != 0
						)
					)
				" ) );
				$max = ceil( $wpdb->get_var( "
					SELECT max(meta_value + 0)
					FROM {$wpdb->posts} as posts
					LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
					WHERE meta_key IN ('" . implode( "','",
						array_map( 'esc_sql',
							apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
					AND (
						posts.ID IN (" . implode( ',', array_map( 'absint', WC()->query->layered_nav_product_ids ) ) . ")
						OR (
							posts.post_parent IN (" . implode( ',',
						array_map( 'absint', WC()->query->layered_nav_product_ids ) ) . ")
							AND posts.post_parent != 0
						)
					)
				" ) );
			}

			return true;
		}
	}
}
