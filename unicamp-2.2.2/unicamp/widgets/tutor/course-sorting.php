<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_WP_Widget_Course_Sorting' ) ) {
	class Unicamp_WP_Widget_Course_Sorting extends Unicamp_Course_Layered_Nav_Base {

		public function __construct() {
			$this->widget_id          = 'unicamp-wp-widget-course-sorting';
			$this->widget_cssclass    = 'unicamp-wp-widget-course-sorting unicamp-wp-widget-filter unicamp-wp-widget-course-filter';
			$this->widget_name        = esc_html__( '[Unicamp] Course Sorting', 'unicamp' );
			$this->widget_description = esc_html__( 'Sort', 'unicamp' );
			$this->settings           = array(
				'title'        => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Sort by', 'unicamp' ),
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
			$display_type = $this->get_value( $instance, 'display_type' );

			$class = ' filter-radio-list';
			$class .= ' show-display-' . $display_type;

			$sorting_options = Unicamp_Tutor::instance()->get_course_sorting_options();

			$filter_name   = 'orderby';
			$base_link     = Unicamp_Tutor::instance()->get_course_listing_page_url();
			$base_link     = remove_query_arg( $filter_name, $base_link );
			$sort_selected = isset( $_GET[ $filter_name ] ) ? Unicamp_Helper::data_clean( $_GET[ $filter_name ] ) : Unicamp_Tutor::instance()->get_course_default_sort_option();

			// List display.
			echo '<ul class="' . esc_attr( $class ) . '">';

			foreach ( $sorting_options as $option_key => $option_name ) {
				$option_is_set = $option_key === $sort_selected;
				$item_classes  = [];
				$link          = $base_link;

				if ( ! $option_is_set ) {
					$link = add_query_arg( array(
						$filter_name => $option_key,
					), $link );
				}

				if ( $option_is_set ) {
					$item_classes [] = 'chosen disabled';
					$link            = false;
				}

				$li_html = sprintf(
					'<li class="%1$s" ><a href="%2$s">%3$s</a></li>',
					implode( ' ', $item_classes ),
					! empty( $link ) ? esc_url( $link ) : 'javascript:void();',
					esc_html( $option_name )
				);

				echo '' . $li_html;
			}

			echo '</ul>';
		}
	}
}
