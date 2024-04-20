<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_WP_Widget_Course_Rating_Filter' ) ) {
	class Unicamp_WP_Widget_Course_Rating_Filter extends Unicamp_Course_Layered_Nav_Base {

		public function __construct() {
			$this->widget_id          = 'unicamp-wp-widget-course-rating-filter';
			$this->widget_cssclass    = 'unicamp-wp-widget-course-rating-filter unicamp-wp-widget-filter unicamp-wp-widget-course-filter';
			$this->widget_name        = esc_html__( '[Unicamp] Course Rating Filter', 'unicamp' );
			$this->widget_description = esc_html__( 'Shows rating in a widget which lets you narrow down the list of courses when viewing courses.', 'unicamp' );
			$this->settings           = array(
				'title'        => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Filter by rating', 'unicamp' ),
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
				'hide_empty'   => array(
					'type'    => 'select',
					'std'     => 'off',
					'label'   => esc_html__( 'Hide empty items', 'unicamp' ),
					'options' => array(
						'on'  => esc_html__( 'Yes', 'unicamp' ),
						'off' => esc_html__( 'No', 'unicamp' ),
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

			$taxonomy = Unicamp_Tutor::instance()->get_tax_visibility();

			if ( ! taxonomy_exists( $taxonomy ) ) {
				return;
			}

			// Get only parent terms. Methods will recursively retrieve children.
			$terms = Unicamp_Tutor::instance()->get_course_visibility_term_ids();

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
			$hide_empty   = $this->get_value( $instance, 'hide_empty' );

			$class = ' filter-checkbox-list';
			$class .= ' show-display-' . $display_type;
			$class .= ' show-items-count-' . $items_count;

			$filter_name    = 'rating_filter';
			$base_link      = Unicamp_Tutor::instance()->get_course_listing_page_url();
			$base_link      = remove_query_arg( $filter_name, $base_link );
			$current_values = isset( $_GET[ $filter_name ] ) ? explode( ',', Unicamp_Helper::data_clean( $_GET[ $filter_name ] ) ) : array();
			$current_values = array_map( 'intval', $current_values );

			// List display.
			echo '<ul class="' . esc_attr( $class ) . '">';

			for ( $rating = 5; $rating >= 1; $rating-- ) {
				$option_key  = $rating;
				$option_name = Unicamp_Templates::render_rating( $rating, [
					'style' => '02',
					'echo'  => false,
				] );

				$compare_slug = 'rated-' . $rating;


				$term_id = $terms[ $compare_slug ];

				$count = 0;

				if ( $term_id !== 0 ) {
					$count = $this->get_filtered_term_counts( $term_id, $taxonomy, 'or' );
				}

				// Only show options with count > 0.
				if ( 'on' === $hide_empty && empty( $count ) ) {
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

				$link = esc_url( $link );

				$item_classes = [];

				if ( $option_is_set ) {
					$item_classes [] = 'chosen';
				}

				if ( empty( $count ) ) {
					$item_classes [] = 'disabled';
					$link            = 'javascript:void(0);';
				}

				$count_html = '';

				if ( $items_count ) {
					$count_html = '<span class="count">(' . $count . ')</span>';
				}

				$li_html = sprintf(
					'<li class="%1$s" ><a href="%2$s">%3$s %4$s</a></li>',
					implode( ' ', $item_classes ),
					$link,
					$option_name, // WPCS: XSS ok.
					$count_html
				);

				echo '' . $li_html;
			}

			echo '</ul>';
		}
	}
}
