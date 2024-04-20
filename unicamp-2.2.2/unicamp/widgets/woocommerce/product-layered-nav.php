<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_WP_Widget_Product_Layered_Nav' ) ) {
	class Unicamp_WP_Widget_Product_Layered_Nav extends Unicamp_WC_Widget_Base {

		public function __construct() {
			$this->widget_id          = 'unicamp-wp-widget-product-layered-nav';
			$this->widget_cssclass    = 'unicamp-wp-widget-product-layered-nav unicamp-wp-widget-filter unicamp-wp-widget-product-filter';
			$this->widget_name        = esc_html__( '[Unicamp] Product Attribute Layered Nav', 'unicamp' );
			$this->widget_description = esc_html__( 'Display a list of attributes to filter products in your store.', 'unicamp' );
			$this->settings           = array(
				'title'        => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Filter by', 'unicamp' ),
					'label' => esc_html__( 'Title', 'unicamp' ),
				),
				'attribute'    => array(
					'type'    => 'select',
					'std'     => '',
					'label'   => esc_html__( 'Attribute', 'unicamp' ),
					'options' => [],
				),
				'query_type'   => array(
					'type'    => 'select',
					'std'     => 'and',
					'label'   => esc_html__( 'Query type', 'unicamp' ),
					'options' => array(
						'and' => esc_html__( 'AND', 'unicamp' ),
						'or'  => esc_html__( 'OR', 'unicamp' ),
					),
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
				'labels'       => array(
					'type'    => 'select',
					'std'     => 'on',
					'label'   => esc_html__( 'Show labels', 'unicamp' ),
					'options' => array(
						'on'  => esc_html__( 'ON', 'unicamp' ),
						'off' => esc_html__( 'OFF', 'unicamp' ),
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

			parent::__construct();
		}

		public function set_form_settings() {
			$attribute_array      = array();
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( $attribute_taxonomies ) {
				foreach ( $attribute_taxonomies as $tax ) {
					$attribute_array[ $tax->attribute_name ] = $tax->attribute_label;
				}
			}

			$this->settings['attribute']['options'] = $attribute_array;
		}

		function widget( $args, $instance ) {

			if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
				return;
			}

			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
			$taxonomy           = isset( $instance['attribute'] ) ? wc_attribute_taxonomy_name( $instance['attribute'] ) : '';
			$query_type         = isset( $instance['query_type'] ) ? $instance['query_type'] : 'and';
			$display_type       = isset( $instance['display_type'] ) ? $instance['display_type'] : 'list';

			if ( ! taxonomy_exists( $taxonomy ) ) {
				return;
			}

			$get_terms_args = [
				'taxonomy'   => $taxonomy,
				'hide_empty' => '1',
			];

			$orderby = wc_attribute_orderby( $taxonomy );

			switch ( $orderby ) {
				case 'name' :
					$get_terms_args['orderby']    = 'name';
					$get_terms_args['menu_order'] = false;
					break;
				case 'id' :
					$get_terms_args['orderby']    = 'id';
					$get_terms_args['order']      = 'ASC';
					$get_terms_args['menu_order'] = false;
					break;
				case 'menu_order' :
					$get_terms_args['menu_order'] = 'ASC';
					break;
			}

			$terms = get_terms( $get_terms_args );

			if ( 0 === sizeof( $terms ) ) {
				return;
			}

			switch ( $orderby ) {
				case 'name_num' :
					usort( $terms, '_wc_get_product_terms_name_num_usort_callback' );
					break;
				case 'parent' :
					usort( $terms, '_wc_get_product_terms_parent_usort_callback' );
					break;
			}

			ob_start();

			$this->widget_start( $args, $instance );

			if ( 'dropdown' === $display_type ) {
				$found = $this->layered_nav_dropdown( $terms, $taxonomy, $query_type );
			} else {
				$found = $this->layered_nav_list( $terms, $taxonomy, $query_type, $instance );
			}

			$this->widget_end( $args );

			// Force found when option is selected - do not force found on taxonomy attributes
			if ( ! is_tax() && is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) ) {
				$found = true;
			}

			if ( ! $found ) {
				ob_end_clean();
			} else {
				echo ob_get_clean();
			}
		}

		/**
		 * Show dropdown layered nav.
		 *
		 * @param  array  $terms
		 * @param  string $taxonomy
		 * @param  string $query_type
		 *
		 * @return bool Will nav display?
		 */
		protected function layered_nav_dropdown( $terms, $taxonomy, $query_type ) {
			$found = false;

			if ( $taxonomy !== $this->get_current_taxonomy() ) {
				$term_counts          = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ),
					$taxonomy,
					$query_type );
				$_chosen_attributes   = WC_Query::get_layered_nav_chosen_attributes();
				$taxonomy_filter_name = str_replace( 'pa_', '', $taxonomy );
				$taxonomy_label       = wc_attribute_label( $taxonomy );
				$any_label            = apply_filters( 'woocommerce_layered_nav_any_label',
					sprintf( esc_html__( 'Any %s', 'unicamp' ), $taxonomy_label ),
					$taxonomy_label,
					$taxonomy );

				echo '<a href="#" class="filter-pseudo-link link-taxonomy-' . $taxonomy_filter_name . '">' . esc_html__( 'Apply filter',
						'unicamp' ) . '</a>';

				echo '<select class="dropdown_layered_nav_' . $taxonomy_filter_name . '" data-filter-url="' . preg_replace( '%\/page\/[0-9]+%',
						'',
						str_replace( array(
							'&amp;',
							'%2C',
						),
							array(
								'&',
								',',
							),
							esc_js( add_query_arg( 'filtering',
								'1',
								remove_query_arg( array(
									'page',
									'_pjax',
									'filter_' . $taxonomy_filter_name,
								) ) ) ) ) ) . "&filter_" . esc_js( $taxonomy_filter_name ) . "=unicamp_FILTER_VALUE" . '">';

				echo '<option value="">' . esc_html( $any_label ) . '</option>';

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

		/**
		 * Count products within certain terms, taking the main WP query into consideration.
		 *
		 * @param  array  $term_ids
		 * @param  string $taxonomy
		 * @param  string $query_type
		 *
		 * @return array
		 */
		protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type ) {
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
			$query           = array();
			$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
			$query['from']   = "FROM {$wpdb->posts}";
			$query['join']   = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'];

			$query['where'] = "
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'
			" . $tax_query_sql['where'] . $meta_query_sql['where'] . "
			AND terms.term_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
		";

			if ( $search = WC_Query::get_main_search_query_sql() ) {
				$query['where'] .= ' AND ' . $search;
			}

			$query['group_by'] = "GROUP BY terms.term_id";
			$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
			$query             = implode( ' ', $query );
			$results           = $wpdb->get_results( $query );

			return wp_list_pluck( $results, 'term_count', 'term_count_id' );
		}

		/**
		 * Show list based layered nav.
		 *
		 * @param  array  $terms
		 * @param  string $taxonomy
		 * @param  string $query_type
		 *
		 * @return bool   Will nav display?
		 */
		protected function layered_nav_list( $terms, $taxonomy, $query_type, $instance ) {
			$labels       = isset( $instance['labels'] ) ? $instance['labels'] : 'on';
			$items_count  = $this->get_value( $instance, 'items_count' );
			$display_type = $this->get_value( $instance, 'display_type' );

			$class = 'show-labels-' . $labels;
			$class .= ' show-display-' . $display_type;
			$class .= ' show-items-count-' . $items_count;
			$class .= ' ' . $taxonomy;

			$attr_id   = wc_attribute_taxonomy_id_by_name( $taxonomy );
			$attr_info = wc_get_attribute( $attr_id );

			switch ( $attr_info->type ) {
				case 'color':
					$class .= ' filter-color-list';
					break;

				case 'image':
					$class .= ' filter-image-list';
					break;

				/*case 'text':
					$class .= ' filter-text-list';
					break;*/

				default:
					$class .= ' filter-checkbox-list';
					break;
			}

			echo '<ul class="' . $class . '">';

			$term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
			$found              = false;
			$base_link          = $this->get_current_page_url();

			foreach ( $terms as $term ) {

				$current_values = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
				$option_is_set  = in_array( $term->slug, $current_values );

				$count = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

				// Skip the term for the current archive.
				if ( $this->get_current_term_id() === $term->term_id ) {
					continue;
				}

				// Only show options with count > 0.
				if ( 0 < $count ) {
					$found = true;
				} elseif ( 0 === $count && ! $option_is_set ) {
					continue;
				}

				$filter_name    = 'filter_' . wc_attribute_taxonomy_slug( $taxonomy );
				$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : array(); // WPCS: input var ok, CSRF ok.
				$current_filter = array_map( 'sanitize_title', $current_filter );

				if ( ! in_array( $term->slug, $current_filter, true ) ) {
					$current_filter[] = $term->slug;
				}

				$link = remove_query_arg( $filter_name, $base_link );

				// Add current filters to URL.
				foreach ( $current_filter as $key => $value ) {
					// Exclude query arg for current term archive term.
					if ( $value === $this->get_current_term_slug() ) {
						unset( $current_filter[ $key ] );
					}

					// Exclude self so filter can be unset on click.
					if ( $option_is_set && $value === $term->slug ) {
						unset( $current_filter[ $key ] );
					}
				}

				if ( ! empty( $current_filter ) ) {
					asort( $current_filter );
					$link = add_query_arg( 'filtering', '1', $link );
					$link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );

					// Add Query type Arg to URL.
					if ( 'or' === $query_type && ! ( 1 === count( $current_filter ) && $option_is_set ) ) {
						$link = add_query_arg( 'query_type_' . wc_attribute_taxonomy_slug( $taxonomy ), 'or', $link );
					}
					$link = str_replace( '%2C', ',', $link );
				}

				$item_classes = [ 'wc-layered-nav-term' ];

				if ( $option_is_set ) {
					$item_classes[] = 'chosen';
				}

				switch ( $attr_info->type ) {

					case 'color':
						$swatch_span = '<span class="filter-swatch hint--top hint--bounce" aria-label="' . esc_attr( esc_html( $term->name ) ) . '" style="background-color: ' . get_term_meta( $term->term_id, 'sw_color', true ) . '"></span>';

						break;

					case 'image':
						$sw_val = get_term_meta( $term->term_id, 'sw_image', true );
						if ( $sw_val ) {
							$image = wp_get_attachment_thumb_url( $sw_val );
						} else {
							$image = wc_placeholder_img_src();
						}

						$swatch_span = '<span class="filter-swatch hint--top hint--bounce" aria-label="' . esc_attr( esc_html( $term->name ) ) . '"><img src="' . esc_url( $image ) . '" alt="' . esc_attr( $term->slug ) . '"/></span>';

						break;

					case 'text':
					default:
						$swatch_span = '<span class="filter-swatch">' . get_term_meta( $term->term_id, 'sw_text', true ) . '</span>';

						break;
				}

				$term_html = '<div class="term-item-swatch">' . $swatch_span . '</div><span class="term-name">' . esc_html( $term->name ) . '</span>';

				if ( $count > 0 || $option_is_set ) {
					$link = apply_filters( 'woocommerce_layered_nav_link', $link, $term, $taxonomy );

				} else {
					$link = false;
				}

				$count_html = '';

				if ( $items_count ) {
					$count_html = apply_filters( 'woocommerce_layered_nav_count',
						'<span class="count">(' . absint( $count ) . ')</span>',
						$count,
						$term );
				}

				$li_html = sprintf(
					'<li class="%1$s" ><a href="%2$s">%3$s %4$s</a></li>',
					implode( ' ', $item_classes ),
					! empty( $link ) ? esc_url( $link ) : 'javascript:void(0);',
					$term_html,
					$count_html
				);

				echo '' . $li_html;
			}

			echo '</ul>';

			return $found;
		}
	}
}
