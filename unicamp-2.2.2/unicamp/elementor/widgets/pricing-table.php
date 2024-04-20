<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Repeater;
use Elementor\Utils;

defined( 'ABSPATH' ) || exit;

class Widget_Pricing_Table extends Base {

	public function get_name() {
		return 'tm-pricing-table';
	}

	public function get_title() {
		return esc_html__( 'Modern Pricing', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-price-table';
	}

	public function get_keywords() {
		return [ 'modern', 'pricing', 'price' ];
	}

	protected function register_controls() {
		$this->add_layout_section();

		$this->add_header_section();

		$this->add_pricing_section();

		$this->add_features_section();

		$this->add_footer_section();

		$this->add_ribbon_section();

		$this->add_box_style_section();

		$this->add_content_style_section();

		$this->add_graphic_style_section();

		$this->add_icon_style_section();

		$this->register_common_button_style_section();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'unicamp' ),
		] );

		$this->add_control( 'style', [
			'label'        => esc_html__( 'Style', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				'01' => esc_html__( '01', 'unicamp' ),
				'02' => esc_html__( '02', 'unicamp' ),
			],
			'default'      => '01',
			'prefix_class' => 'unicamp-pricing-style-',
		] );

		$this->end_controls_section();
	}

	private function add_header_section() {
		$this->start_controls_section( 'header_section', [
			'label' => esc_html__( 'Header', 'unicamp' ),
		] );

		$this->add_control( 'heading', [
			'label'   => esc_html__( 'Title', 'unicamp' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'Enter your title', 'unicamp' ),
		] );

		$this->add_control( 'sub_heading', [
			'label' => esc_html__( 'Description', 'unicamp' ),
			'type'  => Controls_Manager::TEXT,
		] );

		$this->add_control( 'heading_tag', [
			'label'   => esc_html__( 'Heading Tag', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'h2' => 'H2',
				'h3' => 'H3',
				'h4' => 'H4',
				'h5' => 'H5',
				'h6' => 'H6',
			],
			'default' => 'h3',
		] );

		$this->add_control( 'graphic_element', [
			'label'       => esc_html__( 'Graphic Element', 'unicamp' ),
			'type'        => Controls_Manager::CHOOSE,
			'label_block' => false,
			'options'     => [
				'none'  => [
					'title' => esc_html__( 'None', 'unicamp' ),
					'icon'  => 'eicon-ban',
				],
				'image' => [
					'title' => esc_html__( 'Image', 'unicamp' ),
					'icon'  => 'fa fa-picture-o',
				],
				'icon'  => [
					'title' => esc_html__( 'Icon', 'unicamp' ),
					'icon'  => 'eicon-star',
				],
			],
			'default'     => 'none',
		] );

		$this->add_control( 'image', [
			'label'     => esc_html__( 'Choose Image', 'unicamp' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [
				'url' => Utils::get_placeholder_image_src(),
			],
			'dynamic'   => [
				'active' => true,
			],
			'condition' => [
				'graphic_element' => 'image',
			],
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'image', // Actually its `image_size`
			'default'   => 'thumbnail',
			'condition' => [
				'graphic_element' => 'image',
			],
		] );

		$this->add_control( 'icon', [
			'label'     => esc_html__( 'Icon', 'unicamp' ),
			'type'      => Controls_Manager::ICONS,
			'default'   => [
				'value'   => 'fas fa-star',
				'library' => 'fa-solid',
			],
			'condition' => [
				'graphic_element' => 'icon',
			],
		] );

		$this->add_control( 'icon_view', [
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
				'graphic_element' => 'icon',
				'icon[value]!'    => '',
			],
			'render_type'  => 'template',
		] );

		$this->add_control( 'icon_shape', [
			'label'        => esc_html__( 'Shape', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				'circle' => esc_html__( 'Circle', 'unicamp' ),
				'square' => esc_html__( 'Square', 'unicamp' ),
			],
			'default'      => 'circle',
			'condition'    => [
				'graphic_element' => 'icon',
				'icon[value]!'    => '',
				'icon_view'       => [ 'stacked' ],
			],
			'prefix_class' => 'unicamp-shape-',
		] );

		$this->end_controls_section();
	}

	private function add_pricing_section() {
		$this->start_controls_section( 'pricing_section', [
			'label' => esc_html__( 'Pricing', 'unicamp' ),
		] );

		$this->add_control( 'currency', [
			'label'   => esc_html__( 'Currency', 'unicamp' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '$',
		] );

		$this->add_control( 'price', [
			'label'   => esc_html__( 'Price', 'unicamp' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '39.99',
		] );

		$this->add_control( 'period', [
			'label'   => esc_html__( 'Period', 'unicamp' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'Monthly', 'unicamp' ),
		] );

		$this->end_controls_section();
	}

	private function add_features_section() {
		$this->start_controls_section( 'features_section', [
			'label' => esc_html__( 'Features', 'unicamp' ),
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'text', [
			'label'       => esc_html__( 'Text', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Text', 'unicamp' ),
			'label_block' => true,
		] );

		$repeater->add_control( 'icon', [
			'label' => esc_html__( 'Icon', 'unicamp' ),
			'type'  => Controls_Manager::ICONS,
		] );

		$this->add_control( 'features', [
			/*'label'       => esc_html__( 'Features', 'unicamp' ),
			'show_label'  => false,*/
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[
					'text' => esc_html__( 'List Item #1', 'unicamp' ),
				],
				[
					'text' => esc_html__( 'List Item #2', 'unicamp' ),
				],
				[
					'text' => esc_html__( 'List Item #3', 'unicamp' ),
				],
			],
			'title_field' => '{{{ elementor.helpers.renderIcon( this, icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} {{{ text }}}',
		] );

		$this->end_controls_section();
	}

	private function add_footer_section() {
		$this->start_controls_section( 'footer_section', [
			'label' => esc_html__( 'Footer', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Button::get_type(), [
			'name' => 'button',
		] );

		$this->end_controls_section();
	}

	private function add_ribbon_section() {
		$this->start_controls_section( 'ribbon_section', [
			'label' => esc_html__( 'Ribbon', 'unicamp' ),
		] );

		$this->add_control( 'show_ribbon', [
			'label' => esc_html__( 'Show', 'unicamp' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'ribbon_title', [
			'label'     => esc_html__( 'Title', 'unicamp' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => esc_html__( 'Popular', 'unicamp' ),
			'condition' => [
				'show_ribbon' => 'yes',
			],
		] );

		$this->end_controls_section();
	}

	private function add_box_style_section() {
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
				'{{WRAPPER}} .unicamp-box' => 'text-align: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .unicamp-box > .inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'{{WRAPPER}} .unicamp-box' => 'max-width: {{SIZE}}{{UNIT}};',
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
			'selector' => '{{WRAPPER}} .unicamp-box > .inner',
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .unicamp-box > .inner',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box',
			'selector' => '{{WRAPPER}} .unicamp-box',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'box_colors_hover', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box_hover',
			'selector' => '{{WRAPPER}} .unicamp-box:hover > .inner',
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'box_hover_border',
			'selector' => '{{WRAPPER}} .unicamp-box:hover > .inner',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_hover',
			'selector' => '{{WRAPPER}} .unicamp-box:hover',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_content_style_section() {
		$this->start_controls_section( 'content_style_section', [
			'label' => esc_html__( 'Content', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title',
			'label'    => esc_html__( 'Title Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .title',
			'scheme'   => Typography::TYPOGRAPHY_1,
		] );

		$this->add_control( 'pricing_content_skin_hr', [
			'label' => esc_html__( 'Skin', 'unicamp' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->start_controls_tabs( 'pricing_content_skin' );

		$this->start_controls_tab( 'pricing_content_skin_normal', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_control( 'title_skin_hr', [
			'label' => esc_html__( 'Title', 'unicamp' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'label'    => esc_html__( 'Title', 'unicamp' ),
			'name'     => 'title',
			'selector' => '{{WRAPPER}} .title',
		] );

		$this->add_control( 'features_skin_hr', [
			'label'     => esc_html__( 'Features', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'features_text_color', [
			'label'     => esc_html__( 'Text', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .unicamp-pricing-features' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'features_icon_color', [
			'label'     => esc_html__( 'Icon', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .unicamp-pricing-features .unicamp-icon' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'price_skin_hr', [
			'label'     => esc_html__( 'Pricing', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'price_color', [
			'label'     => esc_html__( 'Price', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .unicamp-pricing-price' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'price_currency_color', [
			'label'     => esc_html__( 'Currency', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .unicamp-pricing-currency' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'price_period_color', [
			'label'     => esc_html__( 'Period', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .unicamp-pricing-period' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'ribbon_skin_hr', [
			'label'     => esc_html__( 'Ribbon', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'ribbon_primary_color', [
			'label'     => esc_html__( 'Primary', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .unicamp-pricing-ribbon span' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'ribbon_secondary_color', [
			'label'     => esc_html__( 'Secondary', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .unicamp-pricing-ribbon:before' => 'border-top-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'pricing_content_skin_hover', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_control( 'title_hover_skin_hr', [
			'label' => esc_html__( 'Title', 'unicamp' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'label'    => esc_html__( 'Title', 'unicamp' ),
			'name'     => 'title_hover',
			'selector' => '{{WRAPPER}} .unicamp-box:hover .title',
		] );

		$this->add_control( 'features_hover_skin_hr', [
			'label'     => esc_html__( 'Features', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'features_text_hover_color', [
			'label'     => esc_html__( 'Text', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .unicamp-box:hover .unicamp-pricing-features' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'features_icon_hover_color', [
			'label'     => esc_html__( 'Icon', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .unicamp-box:hover .unicamp-pricing-features .unicamp-icon' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'price_hover_skin_hr', [
			'label'     => esc_html__( 'Pricing', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'price_hover_color', [
			'label'     => esc_html__( 'Price', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .unicamp-box:hover .unicamp-pricing-price' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'price_currency_hover_color', [
			'label'     => esc_html__( 'Currency', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .unicamp-box:hover .unicamp-pricing-currency' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'price_period_hover_color', [
			'label'     => esc_html__( 'Period', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .unicamp-box:hover .unicamp-pricing-period' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'ribbon_hover_skin_hr', [
			'label'     => esc_html__( 'Ribbon', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'ribbon_hover_primary_color', [
			'label'     => esc_html__( 'Primary', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .unicamp-box:hover .unicamp-pricing-ribbon span' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'ribbon_hover_secondary_color', [
			'label'     => esc_html__( 'Secondary', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .unicamp-box:hover .unicamp-pricing-ribbon:before' => 'border-top-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_graphic_style_section() {
		$this->start_controls_section( 'graphic_style_section', [
			'label'     => esc_html__( 'Graphic Element', 'unicamp' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'graphic_element!' => 'none',
			],
		] );

		$this->add_control( 'media_margin', [
			'label'      => esc_html__( 'Margin', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .unicamp-graphic-element' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'media_wrap_height', [
			'label'     => esc_html__( 'Wrap Height', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 6,
					'max' => 300,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .unicamp-graphic-element' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	private function add_icon_style_section() {
		$this->start_controls_section( 'icon_style_section', [
			'label'     => esc_html__( 'Icon', 'unicamp' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'graphic_element' => 'icon',
				'icon[value]!'    => '',
			],
		] );

		$this->start_controls_tabs( 'icon_colors' );

		$this->start_controls_tab( 'icon_colors_normal', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'icon',
			'selector' => '{{WRAPPER}} .icon',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'icon_colors_hover', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'hover_icon',
			'selector' => '{{WRAPPER}} .unicamp-box:hover .icon',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

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
			'separator' => 'before',
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
				'icon_view' => [ 'stacked', 'bubble' ],
			],
		] );

		$this->add_control( 'icon_padding', [
			'label'     => esc_html__( 'Padding', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'selectors' => [
				'{{WRAPPER}} .unicamp-icon-view' => 'padding: {{SIZE}}{{UNIT}};',
			],
			'range'     => [
				'em' => [
					'min' => 0,
					'max' => 5,
				],
			],
			'condition' => [
				'icon_view' => [ 'stacked' ],
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
				'icon_view' => [ 'stacked' ],
			],
		] );

		$this->start_controls_tabs( 'icon_view_colors', [
			'condition' => [
				'icon_view' => [ 'stacked', 'bubble' ],
			],
		] );

		$this->start_controls_tab( 'icon_view_colors_normal', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'icon_view',
			'selector' => '{{WRAPPER}} .unicamp-icon-view',
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
			'selector' => '{{WRAPPER}} .unicamp-box:hover .unicamp-icon-view',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'hover_icon_view',
			'selector' => '{{WRAPPER}} .unicamp-box:hover .unicamp-icon-view',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'unicamp-pricing unicamp-box' );

		if ( 'yes' === $settings['show_ribbon'] && isset( $settings['ribbon_title'] ) && '' !== $settings['ribbon_title'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'has-ribbon' );
		}

		$this->add_render_attribute( 'heading', 'class', 'title' );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<div class="inner">

				<?php $this->print_pricing_ribbon(); ?>

				<div class="unicamp-pricing-header">
					<?php if ( ! empty( $settings['heading'] ) ) : ?>
						<div class="heading-wrap">
							<?php printf( '<%1$s %2$s>%3$s</%1$s>', $settings['heading_tag'], $this->get_render_attribute_string( 'heading' ), $settings['heading'] ); ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $settings['sub_heading'] ) ) : ?>
						<div class="sub-heading-wrap">
							<?php echo esc_html( $settings['sub_heading'] ); ?>
						</div>
					<?php endif; ?>

					<?php $this->print_graphic_element(); ?>
				</div>

				<?php $this->print_pricing(); ?>

				<?php if ( '02' === $settings['style'] ) : ?>
					<?php $this->print_pricing_footer(); ?>
				<?php endif; ?>

				<div class="unicamp-pricing-body">
					<?php if ( $settings['features'] && count( $settings['features'] ) > 0 ) { ?>
						<ul class="unicamp-pricing-features">
							<?php foreach ( $settings['features'] as $item ) {
								$item_key = 'item_' . $item['_id'];
								$this->add_render_attribute( $item_key, 'class', 'item' );
								?>
								<li>
									<?php if ( ! empty( $item['icon']['value'] ) ) { ?>
										<div class="unicamp-icon icon">
											<?php $this->render_icon( $settings, $item['icon'], [ 'aria-hidden' => 'true' ], false, 'icon' ); ?>
										</div>
									<?php } ?>
									<?php echo wp_kses( $item['text'], 'unicamp-default' ); ?>
								</li>
							<?php } ?>
						</ul>
					<?php } ?>
				</div>

				<?php if ( '01' === $settings['style'] ) : ?>
					<?php $this->print_pricing_footer(); ?>
				<?php endif; ?>

			</div>
		</div>
		<?php
	}

	private function print_pricing_ribbon() {
		$settings = $this->get_settings_for_display();

		if ( 'yes' !== $settings['show_ribbon'] || ! isset( $settings['ribbon_title'] ) || '' === $settings['ribbon_title'] ) {
			return;
		}
		?>
		<div class="unicamp-pricing-ribbon">
			<span><?php echo esc_html( $settings['ribbon_title'] ); ?></span>
		</div>
		<?php
	}

	private function print_pricing() {
		$settings = $this->get_settings_for_display();

		if ( ! isset( $settings['price'] ) || '' === $settings['price'] ) {
			return;
		}
		?>
		<div class="price-wrap">
			<div class="price-wrap-inner">
				<?php if ( ! empty( $settings['currency'] ) ) : ?>
					<div class="unicamp-pricing-currency"><?php echo esc_html( $settings['currency'] ); ?></div>
				<?php endif; ?>

				<div class="unicamp-pricing-price"><?php echo esc_html( $settings['price'] ); ?></div>

				<?php if ( ! empty( $settings['period'] ) ) : ?>
					<div class="unicamp-pricing-period"><?php echo esc_html( $settings['period'] ); ?></div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	private function print_pricing_footer() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['button_text'] ) || empty( $settings['button_link'] ) ) {
			return;
		}
		?>
		<div class="unicamp-pricing-footer">
			<?php $this->render_common_button(); ?>
		</div>
		<?php
	}

	private function print_graphic_element() {
		$settings = $this->get_settings_for_display();

		if ( ! isset( $settings['graphic_element'] ) || 'none' === $settings['graphic_element'] ) {
			return;
		}

		switch ( $settings['graphic_element'] ) {
			case 'image' :
				$this->print_graphic_image();
				break;
			case 'icon' :
				$this->print_graphic_icon();
				break;
		}
	}

	private function print_graphic_image() {
		$settings = $this->get_settings_for_display();

		if ( 'image' !== $settings['graphic_element'] || empty( $settings['image']['url'] ) ) {
			return;
		}
		?>
		<div class="unicamp-graphic-element image">
			<?php echo \Unicamp_Image::get_elementor_attachment( [
				'settings'  => $settings,
				'image_key' => 'image',
			] ); ?>
		</div>
		<?php
	}

	private function print_graphic_icon() {
		$settings = $this->get_settings_for_display();

		if ( 'icon' !== $settings['graphic_element'] || empty( $settings["icon"]['value'] ) ) {
			return;
		}

		$icon_key = 'icon';

		$this->add_render_attribute( $icon_key, 'class', [
			'unicamp-icon',
			'icon',
		] );

		$is_svg = isset( $settings['icon']['library'] ) && 'svg' === $settings['icon']['library'] ? true : false;

		if ( $is_svg ) {
			$this->add_render_attribute( $icon_key, 'class', 'unicamp-svg-icon' );
		}

		if ( isset( $settings['icon_color_type'] ) && '' !== $settings['icon_color_type'] ) {
			switch ( $settings['icon_color_type'] ) {
				case 'gradient' :
					$this->add_render_attribute( $icon_key, 'class', 'unicamp-gradient-icon' );
					break;
				case 'classic' :
					$this->add_render_attribute( $icon_key, 'class', 'unicamp-solid-icon' );
					break;
			}
		}
		?>
		<div class="unicamp-graphic-element unicamp-icon-wrap">
			<div class="unicamp-icon-view first">
				<div class="unicamp-icon-view-inner">
					<div <?php $this->print_attributes_string( $icon_key ); ?>>
						<?php $this->render_icon( $settings, $settings['icon'], [ 'aria-hidden' => 'true' ], $is_svg, 'icon' ); ?>
					</div>
				</div>
			</div>

			<?php if ( 'bubble' === $settings['icon_view'] ) { ?>
				<div class="unicamp-icon-view second"></div>
			<?php } ?>
		</div>
		<?php
	}
}
