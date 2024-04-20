<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color as Scheme_Color;

defined( 'ABSPATH' ) || exit;

class Widget_Circle_Progress_Chart extends Base {

	public function get_name() {
		return 'tm-circle-progress-chart';
	}

	public function get_title() {
		return esc_html__( 'Circle Progress Chart', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-counter-circle';
	}

	public function get_keywords() {
		return [ 'chart', 'circle', 'pie', 'progress' ];
	}

	public function get_script_depends() {
		return [ 'unicamp-widget-circle-progress' ];
	}

	protected function register_controls() {
		$this->add_chart_section();

		$this->add_chart_style_section();
	}

	private function add_chart_section() {
		$this->start_controls_section( 'chart_section', [
			'label' => esc_html__( 'Chart', 'unicamp' ),
		] );

		$this->add_control( 'number', [
			'label'   => esc_html__( 'Number', 'unicamp' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 75,
			'min'     => 1,
			'max'     => 100,
		] );

		$this->add_control( 'size', [
			'label'       => esc_html__( 'Circle Size', 'unicamp' ),
			'description' => esc_html__( 'Controls the size of the pie chart circle.', 'unicamp' ),
			'type'        => Controls_Manager::NUMBER,
			'default'     => 180,
			'min'         => 100,
			'max'         => 1000,
		] );

		$this->add_control( 'unit', [
			'label'       => esc_html__( 'Measuring unit', 'unicamp' ),
			'description' => esc_html__( 'Controls the unit of chart.', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '%',
		] );

		$this->add_control( 'reverse', [
			'label' => esc_html__( 'Reverse animation and arc draw', 'unicamp' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'line_cap', [
			'label'   => esc_html__( 'Line Cap', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'butt'   => esc_html__( 'Butt', 'unicamp' ),
				'round'  => esc_html__( 'Round', 'unicamp' ),
				'square' => esc_html__( 'Square', 'unicamp' ),
			],
			'default' => 'square',
		] );

		$this->add_control( 'line_width', [
			'label'   => esc_html__( 'Line Width', 'unicamp' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 3,
			'min'     => 1,
			'max'     => 50,
		] );

		$this->add_control( 'inner_content_type', [
			'label'   => esc_html__( 'Inner Content', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				''     => esc_html__( 'Animate Number', 'unicamp' ),
				'text' => esc_html__( 'Custom Text', 'unicamp' ),
			],
			'default' => '',
		] );

		$this->add_control( 'inner_content_text', [
			'label'     => esc_html__( 'Text', 'unicamp' ),
			'type'      => Controls_Manager::TEXT,
			'condition' => [
				'inner_content_type' => 'text',
			],
		] );

		$this->end_controls_section();
	}

	private function add_chart_style_section() {
		$this->start_controls_section( 'chart_style_section', [
			'label' => esc_html__( 'Chart', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'align', [
			'label'        => esc_html__( 'Alignment', 'unicamp' ),
			'type'         => Controls_Manager::CHOOSE,
			'options'      => Widget_Utils::get_control_options_horizontal_alignment(),
			'prefix_class' => 'elementor%s-align-',
			'default'      => '',
		] );

		$this->add_control( 'bar_color', [
			'label'  => esc_html__( 'Bar Color', 'unicamp' ),
			'type'   => Controls_Manager::COLOR,
			'scheme' => [
				'type'  => Scheme_Color::get_type(),
				'value' => Scheme_Color::COLOR_1,
			],
		] );

		$this->add_control( 'track_color', [
			'label'   => esc_html__( 'Track Color', 'unicamp' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#ededed',
		] );

		$this->add_control( 'number_color', [
			'label'     => esc_html__( 'Number Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .chart-number' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$value = $settings['number'] / 100;

		$this->add_render_attribute( 'wrapper', 'class', 'tm-circle-progress-chart' );

		if ( $settings['inner_content_type'] === '' ) {
			$this->add_render_attribute( 'wrapper', 'data-use-number', '1' );
		}

		$this->add_render_attribute( 'chart', [
			'class'           => 'chart',
			'data-value'      => $value,
			'data-thickness'  => esc_attr( $settings['line_width'] ),
			'data-size'       => esc_attr( $settings['size'] ),
			'data-line-cap'   => esc_attr( $settings['line_cap'] ),
			'data-empty-fill' => esc_attr( $settings['track_color'] ),
			'style'           => sprintf( 'width: %1$spx; height: %1$spx;', $settings['size'] ),
		] );

		$bar_color = ! empty( $settings['bar_color'] ) ? $settings['bar_color'] : '#111111';
		$bar_color = '{ "color": "' . $bar_color . '" }';
		$this->add_render_attribute( 'chart', 'data-fill', esc_attr( $bar_color ) );

		if ( 'yes' === $settings['reverse'] ) {
			$this->add_render_attribute( 'chart', 'data-reverse', 1 );
		}

		$this->add_render_attribute( 'chart-number', [
			'class'      => 'chart-number',
			'data-max'   => esc_attr( $settings['number'] ),
			'data-units' => esc_attr( $settings['unit'] ),
		] );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<div class="chart-wrap">
				<div <?php $this->print_attributes_string( 'chart' ); ?>>
					<div class="inner-content">

						<?php if ( $settings['inner_content_type'] === 'text' ) { ?>
							<h6 class="chart-number"><?php echo esc_html( $settings['inner_content_text'] ); ?></h6>
						<?php } else { ?>
							<h6 <?php $this->print_attributes_string( 'chart-number' ); ?>>0</h6>
						<?php } ?>

					</div>

				</div>
			</div>
		</div>
		<?php
	}
}
