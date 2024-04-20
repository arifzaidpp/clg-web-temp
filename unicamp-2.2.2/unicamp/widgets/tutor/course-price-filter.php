<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_WP_Widget_Course_Price_Filter' ) ) {
	class Unicamp_WP_Widget_Course_Price_Filter extends Unicamp_Course_Layered_Nav_Base {

		public function __construct() {
			$this->widget_id          = 'unicamp-wp-widget-course-price-filter';
			$this->widget_cssclass    = 'unicamp-wp-widget-course-price-filter unicamp-wp-widget-filter unicamp-wp-widget-course-filter';
			$this->widget_name        = esc_html__( '[Unicamp] Course Price Filter', 'unicamp' );
			$this->widget_description = esc_html__( 'Shows prices in a widget which lets you narrow down the list of courses when viewing courses.', 'unicamp' );
			$this->settings           = array(
				'title'        => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Filter by price', 'unicamp' ),
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

		public function widget( $args, $instance ) {
			global $wp_the_query;

			if ( ! $wp_the_query->post_count ) {
				return;
			}

			if ( ! Unicamp_Tutor::instance()->is_course_listing() && ! Unicamp_Tutor::instance()->is_taxonomy() ) {
				return;
			}

			$this->widget_start( $args, $instance );

			$this->layered_nav_list( $instance );

			$this->widget_end( $args );
		}

		protected function layered_nav_list( $instance ) {
			$items_count  = $this->get_value( $instance, 'items_count' );
			$display_type = $this->get_value( $instance, 'display_type' );

			$class = ' filter-radio-list';
			$class .= ' show-display-' . $display_type;
			$class .= ' show-items-count-' . $items_count;

			$price_options = [
				''     => esc_html__( 'All', 'unicamp' ),
				'free' => esc_html__( 'Free', 'unicamp' ),
				'paid' => esc_html__( 'Paid', 'unicamp' ),
			];

			$filter_name   = 'price_type';
			$base_link     = Unicamp_Tutor::instance()->get_course_listing_page_url();
			$base_link     = remove_query_arg( $filter_name, $base_link );
			$current_value = isset( $_GET[ $filter_name ] ) ? Unicamp_Helper::data_clean( $_GET[ $filter_name ] ) : '';

			// List display.
			echo '<ul class="' . esc_attr( $class ) . '">';

			foreach ( $price_options as $price_key => $price_name ) {
				$count = $this->get_filtered_course_count( $price_key );

				// Only show options with count > 0.
				if ( empty( $count ) ) {
					continue;
				}

				$option_is_set = $price_key === $current_value ? true : false;
				$item_classes  = [];
				$link          = $base_link;

				// Skip add param if price type is All.
				if ( ! $option_is_set && '' !== $price_key ) {
					$link = add_query_arg( array(
						'filtering'  => '1',
						$filter_name => $price_key,
					), $link );
				}

				if ( $option_is_set ) {
					$item_classes [] = 'chosen disabled';
					$link            = false;
				}

				$count_html = '';

				if ( $items_count ) {
					$count_html = '<span class="count">(' . $count . ')</span>';
				}

				$li_html = sprintf(
					'<li class="%1$s" ><a href="%2$s">%3$s %4$s</a></li>',
					implode( ' ', $item_classes ),
					! empty( $link ) ? esc_url( $link ) : 'javascript:void();',
					esc_html( $price_name ),
					$count_html
				);

				echo '' . $li_html;
			}

			echo '</ul>';
		}

		/**
		 * Count courses after other filters have occurred by adjusting the main query.
		 *
		 * @param string $price_type
		 *
		 * @return int
		 */
		protected function get_filtered_course_count( $price_type ) {
			global $wpdb;

			$tax_query  = Unicamp_Course_Query::instance()->get_main_tax_query();
			$meta_query = Unicamp_Course_Query::instance()->get_main_meta_query();

			// Unset current price type filter.
			foreach ( $meta_query as $key => $query ) {
				if ( isset( $query['key'] ) && '_tutor_course_product_id' === $query['key'] ) {
					unset( $meta_query[ $key ] );
					break;
				}
			}

			// Set new price type filter.
			$meta_query = Unicamp_Course_Query::set_meta_query_price( $meta_query, $price_type );

			$tax_query  = new WP_Tax_Query( $tax_query );
			$meta_query = new WP_Meta_Query( $meta_query );

			$tax_query_sql    = $tax_query->get_sql( $wpdb->posts, 'ID' );
			$meta_query_sql   = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$author_query_sql = Unicamp_Course_Query::get_main_author_sql();
			$search_query_sql = Unicamp_Course_Query::get_search_title_sql();

			$sql = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) FROM {$wpdb->posts} ";
			$sql .= $tax_query_sql['join'] . $meta_query_sql['join'];
			$sql .= " WHERE {$wpdb->posts}.post_type = 'courses' AND {$wpdb->posts}.post_status = 'publish' ";
			$sql .= $tax_query_sql['where'] . $meta_query_sql['where'] . $author_query_sql['where'] . $search_query_sql['where'];

			return absint( $wpdb->get_var( $sql ) ); // WPCS: unprepared SQL ok.
		}
	}
}
