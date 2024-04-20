<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || exit;

class Widget_Event extends Posts_Base {

	public function get_name() {
		return 'tm-event';
	}

	public function get_title() {
		return esc_html__( 'Event', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-posts-grid';
	}

	public function get_keywords() {
		return [ 'event', 'events', 'grid' ];
	}

	public function get_script_depends() {
		return [ 'unicamp-group-widget-grid' ];
	}

	protected function get_post_type() {
		return 'tp_event';
	}

	protected function get_post_category() {
		return 'tp_event_category';
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_grid_options_section();

		$this->add_style_section();

		parent::register_controls();
	}

	protected function add_grid_options_section() {
		$this->start_controls_section( 'grid_options_section', [
			'label'     => esc_html__( 'Grid Options', 'unicamp' ),
			'condition' => [
				'layout' => [
					'grid-01',
					'grid-02',
					'grid-03',
					'metro-01',
				],
			],
		] );

		$this->add_responsive_control( 'grid_columns', [
			'label'          => esc_html__( 'Columns', 'unicamp' ),
			'type'           => Controls_Manager::NUMBER,
			'min'            => 1,
			'max'            => 12,
			'step'           => 1,
			'default'        => 3,
			'tablet_default' => 2,
			'mobile_default' => 1,
		] );

		$this->add_responsive_control( 'grid_gutter', [
			'label'   => esc_html__( 'Gutter', 'unicamp' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0,
			'max'     => 200,
			'step'    => 1,
			'default' => 30,
		] );

		$this->end_controls_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Content', 'unicamp' ),
		] );

		$this->add_control( 'layout', [
			'label'   => esc_html__( 'Layout', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'grid-01'           => esc_attr__( 'Grid 01', 'unicamp' ),
				'grid-02'           => esc_attr__( 'Grid 02', 'unicamp' ),
				'grid-03'           => esc_attr__( 'Grid 03', 'unicamp' ),
				'list'              => esc_attr__( 'List 01', 'unicamp' ),
				'list-02'           => esc_attr__( 'List 02', 'unicamp' ),
				'metro-01'          => esc_attr__( 'Metro 01', 'unicamp' ),
				'one-left-featured' => esc_html__( '1 Left Featured', 'unicamp' ),
			],
			'default' => 'grid-01',
		] );

		$this->add_control( 'hover_effect', [
			'label'        => esc_html__( 'Hover Effect', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''         => esc_html__( 'None', 'unicamp' ),
				'zoom-in'  => esc_html__( 'Zoom In', 'unicamp' ),
				'zoom-out' => esc_html__( 'Zoom Out', 'unicamp' ),
			],
			'default'      => '',
			'prefix_class' => 'unicamp-animation-',
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'label'     => esc_html__( 'Image Size', 'unicamp' ),
			'name'      => 'image',
			'default'   => 'full',
			'separator' => 'before',
		] );

		$this->end_controls_section();
	}

	private function add_style_section() {
		$this->start_controls_section( 'style_section', [
			'label' => esc_html__( 'Style', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'title_style_heading', [
			'label'     => esc_html__( 'Title', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'title_margin', [
			'label'      => esc_html__( 'Margin', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .event-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => esc_html__( 'Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .event-title',
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .event-title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'title_hover_color', [
			'label'     => esc_html__( 'Hover Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .event-title:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'date_style_heading', [
			'label'     => esc_html__( 'Date', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'date_typography',
			'label'    => esc_html__( 'Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .event-date',
		] );

		$this->add_control( 'date_color', [
			'label'     => esc_html__( 'Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .event-date-time' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'date_day_typography',
			'label'    => esc_html__( 'Day Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .event-date .event-date--day',
		] );

		$this->add_control( 'location_style_heading', [
			'label'     => esc_html__( 'Location', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'location_typography',
			'label'    => esc_html__( 'Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .event-location',
		] );

		$this->add_control( 'location_color', [
			'label'     => esc_html__( 'Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .event-location' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	/**
	 * Check if layout is grid|metro|masonry
	 *
	 * @return bool
	 */
	protected function is_grid() {
		$layout       = $this->get_settings_for_display( 'layout' );
		$grid_layouts = [
			'grid-01',
			'grid-02',
			'grid-03',
			'metro-01',
		];

		if ( ! empty( $layout ) && in_array( $layout, $grid_layouts, true ) ) {
			return true;
		}

		return false;
	}

	protected function get_grid_type() {
		$layout = $this->get_settings_for_display( 'layout' );

		if ( $layout ) {
			switch ( $layout ) {
				case 'grid-01';
				case 'grid-02';
				case 'grid-03';
				case 'metro-01';
					return self::LAYOUT_GRID;
			}
		}

		return false;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->query_posts();
		/**
		 * @var $query \WP_Query
		 */
		$query     = $this->get_query();
		$post_type = $this->get_post_type();

		$this->add_render_attribute( 'wrapper', 'class', [
			'unicamp-grid-wrapper unicamp-event',
			'style-' . $settings['layout'],
		] );

		$this->add_render_attribute( 'content-wrapper', 'class', 'unicamp-grid' );

		if ( $this->is_grid() ) {
			$grid_options = $this->get_grid_options( $settings );

			$this->add_render_attribute( 'wrapper', 'data-grid', wp_json_encode( $grid_options ) );

			$this->add_render_attribute( 'content-wrapper', 'class', 'lazy-grid' );
		}

		if ( 'current_query' === $settings['query_source'] ) {
			$this->add_render_attribute( 'wrapper', 'data-query-main', '1' );
		}
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php if ( $query->have_posts() ) : ?>
				<div <?php $this->print_attributes_string( 'content-wrapper' ); ?>>
					<?php if ( $this->is_grid() ) : ?>
						<div class="grid-sizer"></div>
					<?php endif; ?>

					<?php set_query_var( 'settings', $settings ); ?>

					<?php
					// Custom loop.
					?>
					<?php if ( in_array( $settings['layout'], [ 'one-left-featured', 'metro-01' ], true ) ) : ?>
						<?php set_query_var( 'unicamp_query', $query ); ?>
						<?php get_template_part( 'wp-events-manager/content-event', $settings['layout'] ); ?>
					<?php else : ?>
						<?php while ( $query->have_posts() ) : $query->the_post(); ?>
							<?php get_template_part( 'wp-events-manager/content-event', $settings['layout'] ); ?>
						<?php endwhile; ?>
					<?php endif; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
