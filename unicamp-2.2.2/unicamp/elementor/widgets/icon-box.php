<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography as Scheme_Typography;

defined( 'ABSPATH' ) || exit;

class Widget_Icon_Box extends Base {

	public function get_name() {
		return 'tm-icon-box';
	}

	public function get_title() {
		return esc_html__( 'Modern Icon Box', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-icon-box';
	}

	public function get_keywords() {
		return [ 'icon box', 'icon', 'box', 'box icon' ];
	}

	protected function register_controls() {
		$this->add_icon_box_section();

		$this->add_box_style_section();

		$this->add_icon_style_section();

		$this->add_title_style_section();

		$this->add_title_divider_style();

		$this->add_description_style_section();

		$this->register_common_button_style_section();
	}

	public function add_icon_box_section() {
		$this->start_controls_section( 'icon_box_section', [
			'label' => esc_html__( 'Icon Box', 'unicamp' ),
		] );

		$this->add_content_layout_settings();

		$this->add_content_icon_section();

		$this->add_content_title_section();

		$this->add_content_description_section();

		$this->add_group_control( Group_Control_Button::get_type(), [
			'name'           => 'button',
			// Use box link instead of.
			'exclude'        => [
				'link',
			],
			// Change button style text as default.
			'fields_options' => [
				'style' => [
					'default' => 'text',
				],
			],
		] );

		$this->end_controls_section();
	}

	public function add_content_layout_settings() {
		$this->add_control( 'style', [
			'label'        => esc_html__( 'Style', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''   => esc_html__( 'None', 'unicamp' ),
				'01' => '01',
				'02' => '02',
				'03' => '03',
				'04' => '04',
				'05' => '05',
				'06' => '06',
				'07' => '07',
				'08' => '08',
			],
			'default'      => '',
			'prefix_class' => 'unicamp-icon-box-style-',
		] );

		$this->add_control( 'link', [
			'label'       => esc_html__( 'Link', 'unicamp' ),
			'type'        => Controls_Manager::URL,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'https://your-link.com', 'unicamp' ),
			'separator'   => 'before',
		] );

		$this->add_control( 'link_click', [
			'label'     => esc_html__( 'Apply Link On', 'unicamp' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'box'    => esc_html__( 'Whole Box', 'unicamp' ),
				'button' => esc_html__( 'Button Only', 'unicamp' ),
			],
			'default'   => 'box',
			'condition' => [
				'link[url]!' => '',
			],
		] );
	}

	private function add_content_icon_section() {
		$this->add_control( 'icon_heading', [
			'label'     => esc_html__( 'Icon', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'icon', [
			'label'      => esc_html__( 'Icon', 'unicamp' ),
			'show_label' => false,
			'type'       => Controls_Manager::ICONS,
			'default'    => [
				'value'   => 'fas fa-star',
				'library' => 'fa-solid',
			],
		] );

		$this->add_control( 'view', [
			'label'        => esc_html__( 'View', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				'default' => esc_html__( 'Default', 'unicamp' ),
				'stacked' => esc_html__( 'Stacked', 'unicamp' ),
				'bubble'  => esc_html__( 'Bubble', 'unicamp' ),
			],
			'default'      => 'default',
			'prefix_class' => 'unicamp-view-',
			'condition'    => [
				'icon[value]!' => '',
			],
		] );

		$this->add_control( 'shape', [
			'label'        => esc_html__( 'Shape', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				'circle' => esc_html__( 'Circle', 'unicamp' ),
				'square' => esc_html__( 'Square', 'unicamp' ),
			],
			'default'      => 'circle',
			'condition'    => [
				'view'         => [ 'stacked' ],
				'icon[value]!' => '',
			],
			'prefix_class' => 'unicamp-shape-',
		] );

		$this->add_control( 'position', [
			'label'        => esc_html__( 'Position', 'unicamp' ),
			'type'         => Controls_Manager::CHOOSE,
			'default'      => 'top',
			'options'      => [
				'left'  => [
					'title' => esc_html__( 'Left', 'unicamp' ),
					'icon'  => 'eicon-h-align-left',
				],
				'top'   => [
					'title' => esc_html__( 'Top', 'unicamp' ),
					'icon'  => 'eicon-v-align-top',
				],
				'right' => [
					'title' => esc_html__( 'Right', 'unicamp' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'prefix_class' => 'unicamp-graphic-position-',
			'toggle'       => false,
			// Do not turn on condition. Animated Icon Box not align properly.
			//'condition'    => [ 'icon[value]!' => '' ],
		] );

		$this->add_control( 'mobile_top_position', [
			'label'        => esc_html__( 'Mobile Top Position', 'unicamp' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'condition'    => [
				'position!' => 'top',
			],
			'prefix_class' => 'unicamp-graphic-mobile-position-top-',
		] );

		$this->add_control( 'content_vertical_alignment', [
			'label'        => esc_html__( 'Vertical Alignment', 'unicamp' ),
			'type'         => Controls_Manager::CHOOSE,
			'options'      => Widget_Utils::get_control_options_vertical_alignment(),
			'default'      => 'top',
			'prefix_class' => 'unicamp-graphic-ver-align-',
			'condition'    => [
				'icon[value]!' => '',
				'position!'    => 'top',
			],
		] );
	}

	public function add_content_title_section() {
		$this->add_control( 'title_heading', [
			'label'     => esc_html__( 'Title', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'title_text', [
			'label'       => esc_html__( 'Text', 'unicamp' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => esc_html__( 'This is the heading', 'unicamp' ),
			'placeholder' => esc_html__( 'Enter your title', 'unicamp' ),
			'label_block' => true,
		] );

		$this->add_control( 'title_size', [
			'label'   => esc_html__( 'HTML Tag', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'h1'   => 'H1',
				'h2'   => 'H2',
				'h3'   => 'H3',
				'h4'   => 'H4',
				'h5'   => 'H5',
				'h6'   => 'H6',
				'div'  => 'div',
				'span' => 'span',
				'p'    => 'p',
			],
			'default' => 'h3',
		] );

		// Divider.
		$this->add_control( 'title_divider_enable', [
			'label' => esc_html__( 'Display Divider', 'unicamp' ),
			'type'  => Controls_Manager::SWITCHER,
		] );
	}

	public function add_content_description_section() {
		$this->add_control( 'description_heading', [
			'label'     => esc_html__( 'Description', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'description_text', [
			'label'       => esc_html__( 'Text', 'unicamp' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis.', 'unicamp' ),
			'placeholder' => esc_html__( 'Enter your description', 'unicamp' ),
			'rows'        => 10,
			'separator'   => 'none',
		] );
	}

	public function add_icon_svg_animate_section() {
		$this->start_controls_section( 'icon_svg_animate_section', [
			'label'     => esc_html__( 'Icon SVG Animate', 'unicamp' ),
			'condition' => [
				'icon[library]' => 'svg',
			],
		] );

		$this->add_control( 'icon_svg_animate_alert', [
			'type'            => Controls_Manager::RAW_HTML,
			'content_classes' => 'elementor-control-field-description',
			'raw'             => esc_html__( 'Note: Animate works only with Stroke SVG Icon.', 'unicamp' ),
			'separator'       => 'after',
		] );

		$this->add_control( 'icon_svg_animate', [
			'label' => esc_html__( 'SVG Animate', 'unicamp' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'icon_svg_animate_play_on_hover', [
			'label' => esc_html__( 'Play on hover', 'unicamp' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'icon_svg_animate_type', [
			'label'   => esc_html__( 'Type', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'delayed'  => esc_html__( 'Delayed', 'unicamp' ),
				'sync'     => esc_html__( 'Sync', 'unicamp' ),
				'oneByOne' => esc_html__( 'One By One', 'unicamp' ),
			],
			'default' => 'delayed',
		] );

		$this->add_control( 'icon_svg_animate_duration', [
			'label'   => esc_html__( 'Transition Duration', 'unicamp' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 120,
		] );

		$this->end_controls_section();
	}

	public function add_box_style_section() {
		$this->start_controls_section( 'box_style_section', [
			'label' => esc_html__( 'Box', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'text_align', [
			'label'                => esc_html__( 'Alignment', 'unicamp' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align_full(),
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
			'selectors'            => [
				'{{WRAPPER}} .icon-box-wrapper' => 'text-align: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .tm-icon-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_max_width', [
			'label'      => esc_html__( 'Max Width', 'unicamp' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%' ],
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
				'{{WRAPPER}} .tm-icon-box' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_horizontal_alignment', [
			'label'                => esc_html__( 'Horizontal Alignment', 'unicamp' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'default'              => 'center',
			'toggle'               => false,
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .elementor-widget-container' => 'display: flex; justify-content: {{VALUE}}',
			],
		] );

		$this->start_controls_tabs( 'box_colors' );

		$this->start_controls_tab( 'box_colors_normal', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box',
			'selector' => '{{WRAPPER}} .tm-icon-box',
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .tm-icon-box',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box',
			'selector' => '{{WRAPPER}} .tm-icon-box',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'box_colors_hover', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box_hover',
			'selector' => '{{WRAPPER}} .tm-icon-box:before',
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'box_hover_border',
			'selector' => '{{WRAPPER}} .tm-icon-box:hover',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_hover',
			'selector' => '{{WRAPPER}} .tm-icon-box:hover',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'box_line_heading', [
			'label'     => esc_html__( 'Special Line', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'style' => [ '02', '08' ],
			],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => 'box_line',
			'selector'  => '{{WRAPPER}} .tm-icon-box:after',
			'condition' => [
				'style' => [ '02', '08' ],
			],
		] );

		$this->end_controls_section();
	}

	public function add_icon_style_section() {
		$this->start_controls_section( 'icon_style_section', [
			'label'     => esc_html__( 'Icon', 'unicamp' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'icon[value]!' => '',
			],
		] );

		$this->add_responsive_control( 'icon_wrap_height', [
			'label'     => esc_html__( 'Wrap Height', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 6,
					'max' => 300,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .unicamp-icon-wrap' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'icon_wrap_width', [
			'label'     => esc_html__( 'Wrap Width', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 6,
					'max' => 300,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .unicamp-icon-wrap' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->start_controls_tabs( 'icon_colors' );

		$this->start_controls_tab( 'icon_colors_normal', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_control( 'icon_duotone_color_1_hr', [
			'label'     => esc_html__( 'Color 1', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'icon[value]!'  => '',
				'icon[library]' => 'fa-duotone',
			],
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'icon',
			'selector' => '{{WRAPPER}} .icon',
		] );

		// Icon Duotone colors.
		$this->add_control( 'icon_duotone_before_opacity', [
			'label'     => esc_html__( 'Opacity', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'max'  => 1,
					'min'  => 0,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .icon .fad:before' => 'opacity: {{SIZE}};',
			],
			'condition' => [
				'icon[value]!'  => '',
				'icon[library]' => 'fa-duotone',
			],
		] );

		$this->add_control( 'icon_duotone_color_2_hr', [
			'label'     => esc_html__( 'Color 2', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'icon[value]!'  => '',
				'icon[library]' => 'fa-duotone',
			],
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'      => 'icon_2',
			'selector'  => '{{WRAPPER}} .icon .fad:after',
			'condition' => [
				'icon[value]!'  => '',
				'icon[library]' => 'fa-duotone',
			],
		] );

		$this->add_control( 'icon_duotone_after_opacity', [
			'label'     => esc_html__( 'Opacity', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'max'  => 1,
					'min'  => 0,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .icon .fad:after' => 'opacity: {{SIZE}};',
			],
			'condition' => [
				'icon[value]!'  => '',
				'icon[library]' => 'fa-duotone',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'icon_colors_hover', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'hover_icon',
			'selector' => '{{WRAPPER}} .tm-icon-box:hover .icon',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control( 'icon_space', [
			'label'     => esc_html__( 'Spacing', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}}.unicamp-graphic-position-right .unicamp-icon-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}}.unicamp-graphic-position-left .unicamp-icon-wrap'  => 'margin-right: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}}.unicamp-graphic-position-top .unicamp-icon-wrap'   => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'icon_size', [
			'label'     => esc_html__( 'Size', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 6,
					'max' => 300,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .unicamp-icon-view, {{WRAPPER}} .unicamp-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'icon_rotate', [
			'label'     => esc_html__( 'Rotate', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'default'   => [
				'unit' => 'deg',
			],
			'selectors' => [
				'{{WRAPPER}} .unicamp-icon > svg, {{WRAPPER}} .unicamp-icon > i' => 'transform: rotate({{SIZE}}{{UNIT}});',
			],
		] );

		// Icon View Settings.
		$this->add_control( 'icon_view_heading', [
			'label'     => esc_html__( 'Icon View', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'view' => [ 'stacked', 'bubble' ],
			],
		] );

		$this->add_responsive_control( 'icon_padding', [
			'label'      => esc_html__( 'Padding', 'unicamp' ),
			'type'       => Controls_Manager::SLIDER,
			'selectors'  => [
				'{{WRAPPER}} .unicamp-icon-view' => 'padding: {{SIZE}}{{UNIT}};',
			],
			'size_units' => [ 'px', '%', 'em' ],
			'range'      => [
				'em' => [
					'min' => 0,
					'max' => 5,
				],
			],
			'condition'  => [
				'view' => [ 'stacked' ],
			],
		] );

		$this->add_control( 'icon_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .unicamp-icon-view' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'view' => [ 'stacked' ],
			],
		] );

		$this->start_controls_tabs( 'icon_view_colors', [
			'condition' => [
				'view' => [ 'stacked', 'bubble' ],
			],
		] );

		$this->start_controls_tab( 'icon_view_colors_normal', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'icon_view',
			'selector' => '{{WRAPPER}} .unicamp-icon-view',
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'icon_view_border',
			'selector' => '{{WRAPPER}} .unicamp-icon-view',
			'exclude'  => [
				'radius',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'icon_view',
			'selector' => '{{WRAPPER}} .unicamp-icon-view',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'icon_view_colors_hover', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'hover_icon_view',
			'selector' => '{{WRAPPER}} .tm-icon-box:hover .unicamp-icon-view',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'hover_icon_view',
			'selector' => '{{WRAPPER}} .tm-icon-box:hover .unicamp-icon-view',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function add_title_style_section() {
		$this->start_controls_section( 'title_style_section', [
			'label' => esc_html__( 'Title', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title',
			'selector' => '{{WRAPPER}} .heading',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
		] );

		$this->start_controls_tabs( 'title_colors' );

		$this->start_controls_tab( 'title_color_normal', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title',
			'selector' => '{{WRAPPER}} .heading',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'title_color_hover', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title_hover',
			'selector' => '{{WRAPPER}} .tm-icon-box:hover .heading',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function add_title_divider_style() {
		$this->start_controls_section( 'title_divider_style_section', [
			'label'     => esc_html__( 'Title Divider', 'unicamp' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'title_divider_enable' => 'yes',
			],
		] );

		$this->add_responsive_control( 'divider_spacing', [
			'label'      => esc_html__( 'Spacing', 'unicamp' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%', 'em' ],
			'range'      => [
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .heading-divider' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'divider_width', [
			'label'          => esc_html__( 'Width', 'unicamp' ),
			'type'           => Controls_Manager::SLIDER,
			'default'        => [
				'unit' => 'px',
			],
			'tablet_default' => [
				'unit' => 'px',
			],
			'mobile_default' => [
				'unit' => 'px',
			],
			'size_units'     => [ 'px', '%' ],
			'range'          => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .heading-divider:before' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'divider_height', [
			'label'          => esc_html__( 'Height', 'unicamp' ),
			'type'           => Controls_Manager::SLIDER,
			'default'        => [
				'unit' => 'px',
			],
			'tablet_default' => [
				'unit' => 'px',
			],
			'mobile_default' => [
				'unit' => 'px',
			],
			'size_units'     => [ 'px', '%' ],
			'range'          => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .heading-divider:before' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->start_controls_tabs( 'title_divider_colors' );

		$this->start_controls_tab( 'title_divider_colors_normal', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'title_divider',
			'selector' => '{{WRAPPER}} .heading-divider:before',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'title_divider_colors_hover', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'hover_title_divider',
			'selector' => '{{WRAPPER}} .unicamp-box:hover .heading-divider:before',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function add_description_style_section() {
		$this->start_controls_section( 'description_style_section', [
			'label'     => esc_html__( 'Description', 'unicamp' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'description_text!' => '',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'description',
			'selector' => '{{WRAPPER}} .description',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
		] );

		$this->start_controls_tabs( 'description_colors' );

		$this->start_controls_tab( 'description_color_normal', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'description',
			'selector' => '{{WRAPPER}} .description',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'description_color_hover', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'description_hover',
			'selector' => '{{WRAPPER}} .tm-icon-box:hover .description',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control( 'description_spacing', [
			'label'      => esc_html__( 'Spacing', 'unicamp' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%', 'em' ],
			'range'      => [
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .description-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
			'separator'  => 'before',
		] );

		$this->add_responsive_control( 'description_max_width', [
			'label'      => esc_html__( 'Max Width', 'unicamp' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%' ],
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
				'{{WRAPPER}} .description' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'box', 'class', 'tm-icon-box unicamp-box' );

		if ( ! empty( $settings['icon_svg_animate'] ) && 'yes' === $settings['icon_svg_animate'] ) {
			$vivus_settings = [
				'enable'        => $settings['icon_svg_animate'],
				'type'          => $settings['icon_svg_animate_type'],
				'duration'      => $settings['icon_svg_animate_duration'],
				'play_on_hover' => $settings['icon_svg_animate_play_on_hover'],
			];
			$this->add_render_attribute( 'box', 'data-vivus', wp_json_encode( $vivus_settings ) );
		}

		$box_tag = 'div';

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'box', 'class', 'has-link' );

			if ( 'box' === $settings['link_click'] ) {
				$box_tag = 'a';

				$this->add_render_attribute( 'box', 'class', 'link-secret' );
				$this->add_link_attributes( 'box', $settings['link'] );
			}
		}
		?>
		<?php printf( '<%1$s %2$s>', $box_tag, $this->get_render_attribute_string( 'box' ) ); ?>

		<div class="unicamp-graphic-box icon-box-wrapper">
			<?php $this->print_icon( $settings ); ?>

			<div class="unicamp-graphic-content icon-box-content">
				<?php $this->print_title( $settings ); ?>

				<?php $this->print_description( $settings ); ?>

				<?php $this->render_common_button(); ?>
			</div>
		</div>

		<?php printf( '</%1$s>', $box_tag ); ?>
		<?php
	}

	protected function content_template() {
		$id = uniqid( 'svg-gradient' );
		// @formatter:off
		?>
		<# var svg_id = '<?php echo esc_html( $id ); ?>'; #>

		<#
		view.addRenderAttribute( 'box', 'class', 'tm-icon-box unicamp-box' );
		var box_tag = 'div';

		if ( '' !== settings.link.url && 'box' === settings.link_click ) {
			box_tag = 'a';

			view.addRenderAttribute( 'box', 'class', 'link-secret' );
			view.addRenderAttribute( 'box', 'href', '#' );
		}

		view.addRenderAttribute( 'icon', 'class', 'unicamp-icon icon' );

		if ( 'svg' === settings.icon.library ) {
			view.addRenderAttribute( 'icon', 'class', 'unicamp-svg-icon' );
		}

		switch( settings.icon_color_type ) {
			case 'gradient':
				view.addRenderAttribute( 'icon', 'class', 'unicamp-gradient-icon' );
				break;
			case 'classic':
				view.addRenderAttribute( 'icon', 'class', 'unicamp-solid-icon' );
				break;
		}

		var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' );
		var iconValue = iconHTML.value;
		#>
		<{{{ box_tag }}} {{{ view.getRenderAttributeString( 'box' ) }}}>
			<div class="unicamp-graphic-box icon-box-wrapper">
				<# if ( iconHTML.rendered ) { #>
					<div class="unicamp-graphic-element unicamp-icon-wrap">
						<div class="unicamp-icon-view first">
							<div class="unicamp-icon-view-inner">
								<div {{{ view.getRenderAttributeString( 'icon' ) }}}>
									<# if ( '' !== settings.icon_color_type ) { #>
										<#
										var stop_a = settings.icon_color_a_stop.size + settings.icon_color_a_stop.unit;
										var stop_b = settings.icon_color_b_stop.size + settings.icon_color_b_stop.unit;

										if ( typeof iconValue === 'string' ) {
											var strokeAttr = 'stroke="' + 'url(#' + svg_id + ')"';
											var fillAttr = 'fill="' + 'url(#' + svg_id + ')"';

											iconValue = iconValue.replace(new RegExp(/stroke="#(.*?)"/, 'g'), strokeAttr);
											iconValue = iconValue.replace(new RegExp(/fill="#(.*?)"/, 'g'), fillAttr);
										}
										#>
										<# if( 'gradient' === settings.icon_color_type ) { #>
										<svg aria-hidden="true" focusable="false" class="svg-defs-gradient">
											<defs>
												<linearGradient id="{{{ svg_id }}}" x1="0%" y1="0%" x2="0%" y2="100%">
													<stop class="stop-a" offset="{{{ stop_a }}}"/>
													<stop class="stop-b" offset="{{{ stop_b }}}"/>
												</linearGradient>
											</defs>
										</svg>
										<# } #>
									<# } #>
									{{{ iconValue }}}
								</div>
							</div>
						</div>

						<# if ( 'bubble' == settings.view ) { #>
							<div class="unicamp-icon-view second"></div>
						<# } #>
					</div>
				<# } #>
				<div class="unicamp-graphic-content icon-box-content">
					<# if ( settings.title_text ) { #>
						<#
						view.addRenderAttribute( 'title', 'class', 'heading' );
						#>
						<div class="heading-wrap">
							<{{{ settings.title_size }}} {{{ view.getRenderAttributeString( 'title' ) }}}>
								{{{ settings.title_text }}}
							</{{{ settings.title_size }}}>

							<# if ( 'yes' === settings.title_divider_enable ) { #>
								<div class="heading-divider"></div>
							<# } #>
						</div>
					<# } #>

					<# if ( settings.description_text ) { #>
						<#
						view.addRenderAttribute( 'description', 'class', 'description' );
						#>
						<div class="description-wrap">
							<div {{{ view.getRenderAttributeString( 'description' ) }}}>
								{{{ settings.description_text }}}
							</div>
						</div>
					<# } #>

					<# if ( settings.button_text || settings.button_icon.value ) { #>
						<#
						var buttonIconHTML = elementor.helpers.renderIcon( view, settings.button_icon, { 'aria-hidden': true }, 'i' , 'object' );
						var buttonTag = 'div';

						view.addRenderAttribute( 'button', 'class', 'tm-button style-' + settings.button_style );
						view.addRenderAttribute( 'button', 'class', 'tm-button-' + settings.button_size );

						if ( '' !== settings.link.url && 'button' === settings.link_click ) {
							buttonTag = 'a';
							view.addRenderAttribute( 'button', 'href', '#' );
						}

						if ( settings.button_icon.value ) {
							view.addRenderAttribute( 'button', 'class', 'icon-' + settings.button_icon_align );
						}

						view.addRenderAttribute( 'button-icon', 'class', 'button-icon' );
						#>
						<div class="tm-button-wrapper">
							<{{{ buttonTag }}} {{{ view.getRenderAttributeString( 'button' ) }}}>
								<div class="button-content-wrapper">
									<# if ( buttonIconHTML.rendered && 'left' === settings.button_icon_align ) { #>
										<span {{{ view.getRenderAttributeString( 'button-icon' ) }}}>
											{{{ buttonIconHTML.value }}}
										</span>
									<# } #>

									<# if ( settings.button_text ) { #>
										<span class="button-text">{{{ settings.button_text }}}</span>
									<# } #>

									<# if ( buttonIconHTML.rendered && 'right' === settings.button_icon_align ) { #>
										<span {{{ view.getRenderAttributeString( 'button-icon' ) }}}>
											{{{ buttonIconHTML.value }}}
										</span>
									<# } #>
								</div>
							</{{{ buttonTag }}}>
						</div>
					<# } #>
				</div>

			</div>
		</{{{ box_tag }}}>
		<?php
		// @formatter:off
	}

	private function print_icon( array $settings ) {
		if( empty($settings['icon']['value']) ) {
			return;
		}

		$this->add_render_attribute( 'icon', 'class', [
			'unicamp-icon',
			'icon',
		] );

		$is_svg = isset( $settings['icon']['library'] ) && 'svg' === $settings['icon']['library'] ? true : false;

		if ( $is_svg ) {
			$this->add_render_attribute( 'icon', 'class', 'unicamp-svg-icon');
		}

		if ( isset( $settings['icon_color_type'] ) && '' !== $settings['icon_color_type'] ) {
			switch ( $settings['icon_color_type'] ) {
				case 'gradient' :
					$this->add_render_attribute( 'icon', 'class', 'unicamp-gradient-icon' );
					break;
				case 'classic' :
					$this->add_render_attribute( 'icon', 'class', 'unicamp-solid-icon' );
					break;
			}
		}
		?>
		<div class="unicamp-graphic-element unicamp-icon-wrap">
			<div class="unicamp-icon-view first">
				<div class="unicamp-icon-view-inner">
					<div <?php $this->print_attributes_string( 'icon' ); ?>>
						<?php $this->render_icon( $settings, $settings['icon'], [ 'aria-hidden' => 'true' ], $is_svg, 'icon' ); ?>
					</div>
				</div>
			</div>

			<?php if( 'bubble' === $settings['view'] ) { ?>
				<div class="unicamp-icon-view second"></div>
			<?php } ?>
		</div>
		<?php
	}

	public function print_title( array $settings ) {
		if ( empty( $settings['title_text'] ) ) {
			return;
		}

		$title_text = wp_kses($settings['title_text'], [
			'br'=> [],
			'span' => [
				'class' => []
			],
			'mark' => [
				'class' => []
			],
			'strong' => [
				'class' => []
			]
		]);

		$this->add_render_attribute( 'title', 'class', 'heading' );
		?>
		<div class="heading-wrap">
			<?php printf( '<%1$s %2$s>%3$s</%1$s>', $settings['title_size'], $this->get_render_attribute_string( 'title' ), $title_text ); ?>

			<?php $this->print_title_divider( $settings ); ?>
		</div>
		<?php
	}

	public function print_title_divider( array $settings ) {
		if ( empty( $settings['title_divider_enable'] ) || 'yes' !== $settings['title_divider_enable'] ) {
			return;
		}
		?>
		<div class="heading-divider"></div>
		<?php
	}

	public function print_description( array $settings ) {
		if ( empty( $settings['description_text'] ) ) {
			return;
		}

		$this->add_render_attribute( 'description', 'class', 'description' );
		?>
		<div class="description-wrap">
			<div <?php $this->print_attributes_string( 'description' ); ?>>
				<?php echo wp_kses_post($settings['description_text']); ?>
			</div>
		</div>
		<?php
	}
}
