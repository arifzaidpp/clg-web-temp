<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Search_Form extends Base {

	public function get_name() {
		return 'tm-search-form';
	}

	public function get_title() {
		return esc_html__( 'Search Form', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-site-search';
	}

	public function get_keywords() {
		return [ 'course', 'form', 'search' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_field_style_section();

		$this->add_button_style_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Layout', 'unicamp' ),
		] );

		$this->add_control( 'source', [
			'label'   => esc_html__( 'Source', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'all'     => esc_html__( 'All Content', 'unicamp' ),
				'courses' => esc_html__( 'Only Courses', 'unicamp' ),
			],
			'default' => 'all',
		] );

		$this->add_control( 'style', [
			'label'        => esc_html__( 'Style', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				'01' => '01',
				'02' => '02',
			],
			'default'      => '01',
			'prefix_class' => 'style-',
		] );

		$this->add_control( 'search_field_placeholder', [
			'label'       => esc_html__( 'Placeholder Text', 'unicamp' ),
			'description' => esc_html__( 'Leave blank to use default.', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
		] );

		$this->add_control( 'button_hr', [
			'label'     => esc_html__( 'Button', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'button_text', [
			'label'       => esc_html__( 'Text', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => false,
		] );

		$this->add_control( 'button_icon', [
			'label'   => esc_html__( 'Icon', 'unicamp' ),
			'type'    => Controls_Manager::ICONS,
			'default' => [
				'value'   => 'fas fa-search',
				'library' => 'fa-solid',
			],
		] );

		$this->end_controls_section();
	}

	private function add_field_style_section() {
		$this->start_controls_section( 'form_field_style_section', [
			'label' => esc_html__( 'Field', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'field_padding', [
			'label'      => esc_html__( 'Padding', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .form-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'field_border_width', [
			'label'       => esc_html__( 'Border Width', 'unicamp' ),
			'type'        => Controls_Manager::DIMENSIONS,
			'placeholder' => '1',
			'size_units'  => [ 'px' ],
			'selectors'   => [
				'{{WRAPPER}} .form-input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'field_border_radius', [
			'label'       => esc_html__( 'Border Width', 'unicamp' ),
			'type'        => Controls_Manager::DIMENSIONS,
			'placeholder' => '5',
			'size_units'  => [ 'px', '%' ],
			'selectors'   => [
				'{{WRAPPER}} .form-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->start_controls_tabs( 'field_colors_tabs' );

		$this->start_controls_tab( 'field_colors_normal_tab', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_control( 'field_text_color', [
			'label'     => esc_html__( 'Text Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-input' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'field_background_color', [
			'label'     => esc_html__( 'Background Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-input' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'field_border_color', [
			'label'     => esc_html__( 'Border Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-input' => 'border-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'field_colors_focus_tab', [
			'label' => esc_html__( 'Focus', 'unicamp' ),
		] );

		$this->add_control( 'field_text_focus_color', [
			'label'     => esc_html__( 'Text Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-input:focus' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'field_background_focus_color', [
			'label'     => esc_html__( 'Background Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-input:focus' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'field_border_focus_color', [
			'label'     => esc_html__( 'Border Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-input:focus' => 'border-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_button_style_section() {
		$this->start_controls_section( 'form_button_style_section', [
			'label' => esc_html__( 'Button', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'button_width', [
			'label'      => esc_html__( 'Width', 'unicamp' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%', 'vw' ],
			'range'      => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} button' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_padding', [
			'label'      => esc_html__( 'Padding', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_border_width', [
			'label'       => esc_html__( 'Border Width', 'unicamp' ),
			'type'        => Controls_Manager::DIMENSIONS,
			'placeholder' => '1',
			'size_units'  => [ 'px' ],
			'selectors'   => [
				'{{WRAPPER}} button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_border_radius', [
			'label'       => esc_html__( 'Border Width', 'unicamp' ),
			'type'        => Controls_Manager::DIMENSIONS,
			'placeholder' => '5',
			'size_units'  => [ 'px', '%' ],
			'selectors'   => [
				'{{WRAPPER}} button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->start_controls_tabs( 'button_colors_tabs' );

		$this->start_controls_tab( 'button_colors_normal_tab', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_control( 'button_text_color', [
			'label'     => esc_html__( 'Text Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} button' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_border_color', [
			'label'     => esc_html__( 'Border Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} button' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_background_color', [
			'label'     => esc_html__( 'Background Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} button' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'button_colors_hover_tab', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_control( 'button_text_hover_color', [
			'label'     => esc_html__( 'Text Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} button:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_border_hover_color', [
			'label'     => esc_html__( 'Border Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} button:hover' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_background_hover_color', [
			'label'     => esc_html__( 'Background Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} button:hover' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}


	protected function get_html_wrapper_class() {
		return 'unicamp-widget-search-form';
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$source   = $settings['source'];

		if ( ! empty( $settings['search_field_placeholder'] ) ) {
			$placeholder_text = $settings['search_field_placeholder'];
		} else {
			switch ( $source ) {
				case 'courses' :
					$placeholder_text = esc_attr_x( 'What do you want to learn?', 'placeholder', 'unicamp' );
					break;
				default:
					$placeholder_text = esc_attr_x( 'Search&hellip;', 'placeholder', 'unicamp' );
					break;
			}
		}

		$this->add_render_attribute( 'input', [
			'placeholder' => $placeholder_text,
			'class'       => 'search-field form-input',
			'type'        => 'search',
			'name'        => 'filter_name',
			'title'       => esc_attr__( 'Search', 'unicamp' ),
			'value'       => '',
		] );

		switch ( $source ) {
			case 'courses' :
				$form_action = get_post_type_archive_link( \Unicamp_Tutor::instance()->get_course_type() );
				break;
			default:
				$form_action = home_url( '/' );
				break;
		}

		$is_svg = false;
		?>
		<form class="unicamp-search-form" role="search" method="get" action="<?php echo esc_url( $form_action ); ?>">
			<div class="form-group">
				<label class="screen-reader-text"><?php esc_html_e( 'Search for:', 'unicamp' ); ?></label>
				<input <?php $this->print_render_attribute_string( 'input' ); ?>/>
			</div>
			<button type="submit" class="search-submit">
				<?php if ( ! empty( $settings['button_icon']['value'] ) ) : ?>
					<span class="search-btn-icon">
						<?php $this->render_icon( $settings, $settings['button_icon'], [ 'aria-hidden' => 'true' ], $is_svg, 'icon' ); ?>
					</span>
				<?php endif; ?>
				<?php if ( ! empty( $settings['button_text'] ) ) : ?>
					<span class="search-btn-text">
						<?php echo esc_html_x( 'Search', 'submit button', 'unicamp' ); ?>
					</span>
				<?php endif; ?>
			</button>
		</form>
		<?php
	}
}
