<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_WP_Widget_Product_Categories_Layered_Nav' ) ) {
	class Unicamp_WP_Widget_Product_Categories_Layered_Nav extends Unicamp_WC_Widget_Base {

		public function __construct() {
			$this->widget_id          = 'unicamp-wp-widget-product-categories-layered-nav';
			$this->widget_cssclass    = 'unicamp-wp-widget-product-categories-layered-nav unicamp-wp-widget-filter unicamp-wp-widget-product-filter';
			$this->widget_name        = esc_html__( '[Unicamp] Product Categories Layered Nav', 'unicamp' );
			$this->widget_description = esc_html__( 'Shows categories in a widget which lets you narrow down the list of products when viewing products.', 'unicamp' );
			$this->settings           = array(
				'title'        => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Filter By Categories', 'unicamp' ),
					'label' => esc_html__( 'Title', 'unicamp' ),
				),
				'display_type' => array(
					'type'    => 'select',
					'std'     => 'list',
					'label'   => esc_html__( 'Display type', 'unicamp' ),
					'options' => array(
						'list'     => esc_html__( 'List', 'unicamp' ),
						'inline'   => esc_html__( 'Inline', 'unicamp' ),
						'dropdown' => esc_html__( 'Dropdown', 'unicamp' ),
					),
				),
				'items_count'  => array(
					'type'    => 'select',
					'std'     => 'on',
					'label'   => esc_html__( 'Show items count', 'unicamp' ),
					'options' => array(
						'on'  => esc_html__( 'ON', 'unicamp' ),
						'off' => esc_html__( 'OFF', 'unicamp' ),
					),
				),
			);

			add_filter( 'woocommerce_product_query_tax_query', array(
				$this,
				'update_product_query_tax_query',
			), 10, 2 );

			parent::__construct();
		}

		/**
		 * @param array    $tax_query
		 * @param WC_Query $wc_query
		 *
		 * @return array
		 */
		public function update_product_query_tax_query( array $tax_query, WC_Query $wc_query ) {
			if ( isset( $_GET['filter_product_cat'] ) ) { // WPCS: input var ok, CSRF ok.
				$cats = array_filter( array_map( 'absint', explode( ',', $_GET['filter_product_cat'] ) ) ); // WPCS: input var ok, CSRF ok, Sanitization ok.

				if ( $cats ) {
					$tax_query[] = array(
						'taxonomy' => 'product_cat',
						'terms'    => $cats,
						'operator' => 'IN',
					);
				}
			}

			return $tax_query;
		}

		public function widget( $args, $instance ) {
			$attribute_array      = array();
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( ! empty( $attribute_taxonomies ) ) {
				foreach ( $attribute_taxonomies as $tax ) {
					if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
						$attribute_array[ $tax->attribute_name ] = $tax->attribute_name;
					}
				}
			}

			if ( ! is_post_type_archive( 'product' ) && ! is_tax( array_merge( is_array( $attribute_array ) ? $attribute_array : array(), array(
					'product_cat',
					'product_tag',
				) ) ) ) {
				return;
			}

			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();

			$current_term = $attribute_array && is_tax( $attribute_array ) ? get_queried_object()->term_id : '';
			$current_tax  = $attribute_array && is_tax( $attribute_array ) ? get_queried_object()->taxonomy : '';

			$taxonomy     = 'product_cat';
			$display_type = isset( $instance['display_type'] ) ? $instance['display_type'] : 'list';

			if ( ! taxonomy_exists( $taxonomy ) ) {
				return;
			}

			// Get only parent terms. Methods will recursively retrieve children.
			$terms = get_terms( [
				'taxonomy'   => $taxonomy,
				'hide_empty' => '1',
				'parent'     => 0,
			] );

			if ( empty( $terms ) ) {
				return;
			}

			ob_start();

			$this->widget_start( $args, $instance );

			if ( 'dropdown' === $display_type ) {
				$found = $this->layered_nav_dropdown( $terms, $taxonomy, $instance );
			} else {
				$found = $this->layered_nav_list( $terms, $taxonomy, $instance );
			}

			$this->widget_end( $args );

			// Force found when option is selected - do not force found on taxonomy attributes.
			if ( ! is_tax() && is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) ) {
				$found = true;
			}

			if ( ! $found ) {
				ob_end_clean();
			} else {
				echo ob_get_clean();
			}
		}

		public function get_chosen_attributes() {
			if ( ! empty( $_GET['filter_product_cat'] ) ) {
				return array_map( 'intval', explode( ',', $_GET['filter_product_cat'] ) );
			}

			return array();
		}

		protected function layered_nav_dropdown( $terms, $taxonomy, $instance, $depth = 0 ) {
			$found = false;

			if ( $taxonomy !== $this->get_current_taxonomy() ) {
				$term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, 'or' );
				$_chosen_attributes = $this->get_chosen_attributes();

				echo '<select class="unicamp-product-categories-dropdown-layered-nav">';
				echo '<option value="">' . esc_html__( 'Any Category', 'unicamp' ) . '</option>';

				foreach ( $terms as $term ) {

					// If on a term page, skip that term in widget list
					if ( $term->term_id === $this->get_current_term_id() ) {
						continue;
					}

					// Get count based on current view
					$current_values = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
					$option_is_set  = in_array( $term->slug, $current_values );
					$count          = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

					// Only show options with count > 0
					if ( 0 < $count ) {
						$found = true;
					} elseif ( 0 === $count && ! $option_is_set ) {
						continue;
					}

					echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( $option_is_set,
							true,
							false ) . '>' . esc_html( $term->name ) . '</option>';

				}

				echo '</select>';
			}

			return $found;
		}

		protected function layered_nav_list( $terms, $taxonomy, $instance, $depth = 0 ) {
			// List display
			$found = false;
			if ( $taxonomy !== $this->get_current_taxonomy() ) {

				$labels       = isset( $instance['labels'] ) ? $instance['labels'] : 'on';
				$items_count  = $this->get_value( $instance, 'items_count' );
				$display_type = $this->get_value( $instance, 'display_type' );

				$class = 'filter-checkbox-list';
				$class .= ' show-labels-' . $labels;
				$class .= ' show-display-' . $display_type;
				$class .= ' show-items-count-' . $items_count;
				$class .= ' ' . $taxonomy;
				$class .= ( 0 == $depth ? '' : 'children ' );

				// List display
				echo '<ul class="' . esc_attr( $class ) . '">';

				$term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, 'or' );
				$_chosen_attributes = $this->get_chosen_attributes();
				$current_values     = ! empty( $_chosen_attributes ) ? $_chosen_attributes : array();
				$found              = false;

				$filter_name = 'filter_' . $taxonomy;

				foreach ( $terms as $term ) {
					$option_is_set = in_array( $term->term_id, $current_values );
					$count         = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

					// skip the term for the current archive
					if ( $this->get_current_term_id() === $term->term_id ) {
						continue;
					}

					// Only show options with count > 0
					if ( 0 < $count ) {
						$found = true;
					} elseif ( 0 === $count && ! $option_is_set ) {
						continue;
					}

					$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( $_GET[ $filter_name ] ) ) : array();
					$current_filter = array_map( 'intval', $current_filter );

					if ( ! in_array( $term->term_id, $current_filter ) ) {
						$current_filter[] = $term->term_id;
					}

					$link = $this->get_current_page_url();

					// Add current filters to URL.
					foreach ( $current_filter as $key => $value ) {
						// Exclude query arg for current term archive term
						if ( $value === $this->get_current_term_id() ) {
							unset( $current_filter[ $key ] );
						}

						// Exclude self so filter can be unset on click.
						if ( $option_is_set && $value === $term->term_id ) {
							unset( $current_filter[ $key ] );
						}
					}

					if ( ! empty( $current_filter ) ) {
						$link = add_query_arg( array(
							'filtering'  => '1',
							$filter_name => implode( ',', $current_filter ),
						), $link );
					}

					$item_classes = [ 'wc-layered-nav-term' ];

					if ( $option_is_set ) {
						$item_classes[] = 'chosen';
					}

					if ( $count > 0 || $option_is_set ) {
						$link = apply_filters( 'woocommerce_layered_nav_link', $link );
					} else {
						$link = false;
					}

					$count_html = '';

					if ( $items_count ) {
						$count_html = '<span class="count">(' . $count . ')</span>';
					}

					printf( '<li class="%s"><a href="%s">%s %s</a></li>',
						esc_attr( implode( ' ', $item_classes ) ),
						! empty( $link ) ? esc_url( $link ) : 'javascript:void(0);',
						esc_html( $term->name ),
						$count_html
					); // WPCS: XSS ok. 

					$child_terms = get_terms( [
						'taxonomy'   => $taxonomy,
						'hide_empty' => 1,
						'parent'     => $term->term_id,
					] );

					if ( ! empty( $child_terms ) ) {
						$found |= $this->layered_nav_list( $child_terms, $taxonomy, $instance, $depth + 1 );
					}

					echo '</li>';
				}

				echo '</ul>';
			}

			return $found;
		}

		protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type = 'and' ) {
			global $wpdb;

			$tax_query  = WC_Query::get_main_tax_query();
			$meta_query = WC_Query::get_main_meta_query();

			if ( 'or' === $query_type ) {
				foreach ( $tax_query as $key => $query ) {
					if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
						unset( $tax_query[ $key ] );
					}
				}
			}

			$meta_query     = new WP_Meta_Query( $meta_query );
			$tax_query      = new WP_Tax_Query( $tax_query );
			$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

			// Generate query
			$query             = array();
			$query['select']   = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
			$query['from']     = "FROM {$wpdb->posts}";
			$query['join']     = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'];
			$query['where']    = "
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'
			" . $tax_query_sql['where'] . $meta_query_sql['where'] . "
			AND terms.term_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
		";
			$query['group_by'] = "GROUP BY terms.term_id";
			$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
			$query             = implode( ' ', $query );
			$results           = $wpdb->get_results( $query );

			return wp_list_pluck( $results, 'term_count', 'term_count_id' );
		}
	}
}
