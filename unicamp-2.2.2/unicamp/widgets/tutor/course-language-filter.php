<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_WP_Widget_Course_Language_Filter' ) ) {
	class Unicamp_WP_Widget_Course_Language_Filter extends Unicamp_Course_Layered_Nav_Base {

		public function __construct() {
			$this->widget_id          = 'unicamp-wp-widget-course-language-filter';
			$this->widget_cssclass    = 'unicamp-wp-widget-course-language-filter unicamp-wp-widget-filter unicamp-wp-widget-course-filter';
			$this->widget_name        = esc_html__( '[Unicamp] Course Language Filter', 'unicamp' );
			$this->widget_description = esc_html__( 'Shows language in a widget which lets you narrow down the list of courses when viewing courses.', 'unicamp' );
			$this->settings           = array(
				'title'        => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Filter by language', 'unicamp' ),
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

			$taxonomy = Unicamp_Tutor::instance()->get_tax_language();

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

			$this->widget_start( $args, $instance );

			$this->layered_nav_list( $terms, $taxonomy, $instance );

			$this->widget_end( $args );
		}

		protected function layered_nav_list( $terms, $taxonomy, $instance ) {
			$items_count  = $this->get_value( $instance, 'items_count' );
			$display_type = $this->get_value( $instance, 'display_type' );

			$class = ' filter-checkbox-list';
			$class .= ' show-display-' . $display_type;
			$class .= ' show-items-count-' . $items_count;

			$filter_name = 'filter_' . $taxonomy;

			$base_link      = Unicamp_Tutor::instance()->get_course_listing_page_url();
			$base_link      = remove_query_arg( $filter_name, $base_link );
			$current_values = isset( $_GET[ $filter_name ] ) ? array_map( 'intval', explode( ',', $_GET[ $filter_name ] ) ) : array();

			// List display.
			echo '<ul class="' . esc_attr( $class ) . '">';

			foreach ( $terms as $term_key => $term ) {
				$option_key  = $term->term_id;
				$option_name = $term->name;

				$count = $this->get_filtered_term_counts( $term->term_id, $taxonomy, 'or' );

				// Only show options with count > 0.
				if ( empty( $count ) ) {
					continue;
				}

				$option_is_set = in_array( $option_key, $current_values, true );

				$current_filter = $current_values;

				if ( ! $option_is_set ) {
					$current_filter[] = $option_key;
				}

				foreach ( $current_filter as $key => $value ) {
					if ( $option_is_set && $value === $option_key ) {
						unset( $current_filter[ $key ] );
					}
				}

				$link = $base_link;

				if ( ! empty( $current_filter ) ) {
					$link = add_query_arg( array(
						'filtering'  => '1',
						$filter_name => implode( ',', $current_filter ),
					), $link );
				}

				$item_classes = [ 'term-item-' . $option_key ];

				if ( $option_is_set ) {
					$item_classes [] = 'chosen';
				}

				$count_html = '';

				if ( $items_count ) {
					$count_html = '<span class="count">(' . $count . ')</span>';
				}

				$li_html = sprintf(
					'<li class="%1$s" ><a href="%2$s">%3$s %4$s</a></li>',
					implode( ' ', $item_classes ),
					esc_url( $link ),
					esc_html( $option_name ),
					$count_html
				);

				echo '' . $li_html;
			}

			echo '</ul>';
		}
	}
}

