<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_WP_Widget_Course_Levels' ) ) {
	class Unicamp_WP_Widget_Course_Levels extends Unicamp_WP_Widget_Base {

		public function __construct() {
			$this->widget_id          = 'unicamp-wp-widget-course-levels';
			$this->widget_cssclass    = 'unicamp-wp-widget-course-levels';
			$this->widget_name        = esc_html__( '[Unicamp] Course Levels', 'unicamp' );
			$this->widget_description = esc_html__( 'A list or dropdown of course levels.', 'unicamp' );
			$this->settings           = array(
				'title'      => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Course Levels', 'unicamp' ),
					'label' => esc_html__( 'Title', 'unicamp' ),
				),
				'style'      => array(
					'type'    => 'select',
					'std'     => 'list',
					'label'   => esc_html__( 'Style', 'unicamp' ),
					'options' => array(
						'list'       => esc_html__( 'List', 'unicamp' ),
						'check-list' => esc_html__( 'Check List', 'unicamp' ),
					),
				),
				'count'      => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Show course counts', 'unicamp' ),
				),
				'hide_empty' => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Hide empty levels', 'unicamp' ),
				),
			);

			parent::__construct();
		}

		public function widget( $args, $instance ) {
			$style      = $this->get_value( $instance, 'style' );
			$show_count = $this->get_value( $instance, 'count' );
			$hide_empty = $this->get_value( $instance, 'hide_empty' );

			$levels = tutor_utils()->course_levels();

			if ( empty( $levels ) ) {
				return;
			}

			$this->widget_start( $args, $instance );

			$wrapper_classes = 'unicamp-widget-term-list style-' . $style;

			echo '<div class="' . esc_attr( $wrapper_classes ) . '">';
			echo '<ul class="course-levels">';

			$base_link   = Unicamp_Tutor::instance()->get_course_listing_base_url();
			$filter_name = 'level';

			foreach ( $levels as $level_value => $level_name ) {
				$post_count = $this->get_course_count( $level_value );

				if ( $hide_empty && $post_count < 1 ) {
					continue;
				}

				$link_class = [ 'term-item term-item-' . $level_value ];

				$link = $base_link;
				$link = add_query_arg( array(
					'filtering'  => '1',
					$filter_name => $level_value,
				), $link );

				$count_html = '';

				if ( $show_count ) {
					$count_html = '<span>(' . $post_count . ')</span>';
				}

				echo sprintf(
					'<li class="%1$s"><a href="%2$s">%3$s %4$s</a></li>',
					esc_attr( implode( ' ', $link_class ) ),
					esc_url( $link ),
					esc_html( $level_name ),
					$count_html
				);
			}

			echo '</ul>';

			echo '</div>';

			$this->widget_end( $args );
		}

		public function get_course_count( $level ) {
			global $wpdb;

			$sql_query = $wpdb->prepare( "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) FROM {$wpdb->posts}
			 INNER JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id
			 WHERE {$wpdb->posts}.post_type IN ( 'courses' )
			 AND {$wpdb->posts}.post_status = 'publish'
			 AND ({$wpdb->postmeta}.meta_key = '_tutor_course_level' AND {$wpdb->postmeta}.meta_value = %s )", $level );

			$result = absint( $wpdb->get_var( $sql_query ) );

			return $result;
		}
	}
}
