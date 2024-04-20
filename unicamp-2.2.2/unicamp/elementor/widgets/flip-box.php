<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography as Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Utils;

defined( 'ABSPATH' ) || exit;

class Widget_Flip_Box extends Base {

	public function get_name() {
		return 'tm-flip-box';
	}

	public function get_title() {
		return esc_html__( 'Advanced Flip Box', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-flip-box';
	}

	public function get_script_depends() {
		return [ 'unicamp-widget-flip-box' ];
	}

	protected function register_controls() {
		// Content Tab.
		$this->add_front_side_content_section();

		$this->add_back_side_content_section();

		$this->add_box_settings_section();

		// Style Tab - Front Side.
		$this->add_front_side_box_style_section();

		$this->add_front_side_image_style_section();

		$this->add_front_side_icon_style_section();

		$this->add_front_side_heading_style_section();

		$this->add_front_side_description_style_section();

		// Style Tab - Back Side.
		$this->add_back_side_box_style_section();

		$this->add_back_side_image_style_section();

		$this->add_back_side_icon_style_section();

		$this->add_back_side_heading_style_section();

		$this->add_back_side_description_style_section();

		$this->register_common_button_style_section();
	}

	private function add_front_side_content_section() {
		$this->start_controls_section( 'content_front_side_section', [
			'label' => esc_html__( 'Front', 'unicamp' ),
		] );

		$this->start_controls_tabs( 'side_a_content_tabs' );

		$this->start_controls_tab( 'side_a_content_tab', [ 'label' => esc_html__( 'Content', 'unicamp' ) ] );

		$this->add_control( 'graphic_element_a', [
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
			'default'     => 'icon',
		] );

		$this->add_control( 'image_a', [
			'label'     => esc_html__( 'Choose Image', 'unicamp' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [
				'url' => Utils::get_placeholder_image_src(),
			],
			'dynamic'   => [
				'active' => true,
			],
			'condition' => [
				'graphic_element_a' => 'image',
			],
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'image_a', // Actually its `image_a_size`
			'default'   => 'thumbnail',
			'condition' => [
				'graphic_element_a' => 'image',
			],
		] );

		$this->add_control( 'icon_a', [
			'label'     => esc_html__( 'Icon', 'unicamp' ),
			'type'      => Controls_Manager::ICONS,
			'default'   => [
				'value'   => 'fas fa-star',
				'library' => 'fa-solid',
			],
			'condition' => [
				'graphic_element_a' => 'icon',
			],
		] );

		$this->add_control( 'title_text_a', [
			'label'       => esc_html__( 'Title & Description', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'This is the heading', 'unicamp' ),
			'placeholder' => esc_html__( 'Enter your title', 'unicamp' ),
			'dynamic'     => [
				'active' => true,
			],
			'label_block' => true,
			'separator'   => 'before',
		] );

		$this->add_control( 'description_text_a', [
			'label'       => esc_html__( 'Description', 'unicamp' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'unicamp' ),
			'placeholder' => esc_html__( 'Enter your description', 'unicamp' ),
			'separator'   => 'none',
			'dynamic'     => [
				'active' => true,
			],
			'rows'        => 10,
			'show_label'  => false,
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'side_a_background_tab', [ 'label' => esc_html__( 'Background', 'unicamp' ) ] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'background_a',
			'types'    => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} .front-side',
		] );

		$this->add_control( 'background_overlay_a', [
			'label'     => esc_html__( 'Background Overlay', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .front-side .overlay' => 'background-color: {{VALUE}};',
			],
			'separator' => 'before',
			'condition' => [
				'background_a_image[id]!' => '',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_back_side_content_section() {
		$this->start_controls_section( 'content_back_side_section', [
			'label' => esc_html__( 'Back', 'unicamp' ),
		] );

		$this->start_controls_tabs( 'side_b_content_tabs' );

		$this->start_controls_tab( 'side_b_content_tab', [ 'label' => esc_html__( 'Content', 'unicamp' ) ] );

		$this->add_control( 'graphic_element_b', [
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
			'default'     => 'icon',
		] );

		$this->add_control( 'image_b', [
			'label'     => esc_html__( 'Choose Image', 'unicamp' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [
				'url' => Utils::get_placeholder_image_src(),
			],
			'dynamic'   => [
				'active' => true,
			],
			'condition' => [
				'graphic_element_b' => 'image',
			],
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'image_b', // Actually its `image_b_size`
			'default'   => 'thumbnail',
			'condition' => [
				'graphic_element_b' => 'image',
			],
		] );

		$this->add_control( 'icon_b', [
			'label'     => esc_html__( 'Icon', 'unicamp' ),
			'type'      => Controls_Manager::ICONS,
			'default'   => [
				'value'   => 'fas fa-star',
				'library' => 'fa-solid',
			],
			'condition' => [
				'graphic_element_b' => 'icon',
			],
		] );

		$this->add_control( 'title_text_b', [
			'label'       => esc_html__( 'Title & Description', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'This is the heading', 'unicamp' ),
			'placeholder' => esc_html__( 'Enter your title', 'unicamp' ),
			'dynamic'     => [
				'active' => true,
			],
			'label_block' => true,
		] );

		$this->add_control( 'description_text_b', [
			'label'       => esc_html__( 'Description', 'unicamp' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
			'placeholder' => esc_html__( 'Enter your description', 'unicamp' ),
			'separator'   => 'none',
			'dynamic'     => [
				'active' => true,
			],
			'rows'        => 10,
			'show_label'  => false,
		] );

		$this->add_group_control( Group_Control_Button::get_type(), [
			'name'           => 'button',
			// Use box link instead of.
			'exclude'        => [
				'link',
			],
			// Change button style text as default.
			'fields_options' => [
				'style' => [
					'default' => 'bottom-line',
				],
			],
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
			'default'   => 'button',
			'condition' => [
				'link[url]!' => '',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'side_b_background_tab', [ 'label' => esc_html__( 'Background', 'unicamp' ) ] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'background_b',
			'types'    => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} .back-side',
		] );

		$this->add_control( 'background_overlay_b', [
			'label'     => esc_html__( 'Background Overlay', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .back-side .overlay' => 'background-color: {{VALUE}};',
			],
			'separator' => 'before',
			'condition' => [
				'background_b_image[id]!' => '',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_box_settings_section() {
		$this->start_controls_section( 'section_box_settings', [
			'label' => esc_html__( 'Settings', 'unicamp' ),
		] );

		$this->add_responsive_control( 'min_height', [
			'label'      => esc_html__( 'Min Height', 'unicamp' ),
			'type'       => Controls_Manager::SLIDER,
			'range'      => [
				'px' => [
					'min' => 100,
					'max' => 1000,
				],
			],
			'size_units' => [ 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .unicamp-flip-box' => 'min-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'border_radius', [
			'label'      => esc_html__( 'Border Radius', 'unicamp' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'separator'  => 'after',
			'selectors'  => [
				'{{WRAPPER}} .layer, {{WRAPPER}} .overlay' => 'border-radius: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_control( 'flip_effect', [
			'label'        => esc_html__( 'Flip Effect', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'default'      => 'flip',
			'options'      => [
				'flip'     => 'Flip',
				'slide'    => 'Slide',
				'push'     => 'Push',
				'zoom-in'  => 'Zoom In',
				'zoom-out' => 'Zoom Out',
				'fade'     => 'Fade',
			],
			'prefix_class' => 'unicamp-flip-box--effect-',
		] );

		$this->add_control( 'flip_direction', [
			'label'        => esc_html__( 'Flip Direction', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'default'      => 'up',
			'options'      => [
				'left'  => esc_html__( 'Left', 'unicamp' ),
				'right' => esc_html__( 'Right', 'unicamp' ),
				'up'    => esc_html__( 'Up', 'unicamp' ),
				'down'  => esc_html__( 'Down', 'unicamp' ),
			],
			'condition'    => [
				'flip_effect!' => [
					'fade',
					'zoom-in',
					'zoom-out',
				],
			],
			'prefix_class' => 'unicamp-flip-box--direction-',
		] );

		$this->add_control( 'flip_3d', [
			'label'        => esc_html__( '3D Depth', 'unicamp' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'On', 'unicamp' ),
			'label_off'    => esc_html__( 'Off', 'unicamp' ),
			'return_value' => 'unicamp-flip-box--3d',
			'default'      => '',
			'prefix_class' => '',
			'condition'    => [
				'flip_effect' => 'flip',
			],
		] );

		$this->end_controls_section();
	}

	private function add_front_side_box_style_section() {
		$this->start_controls_section( 'box_style_front_side_section', [
			'label' => esc_html__( 'Front - Box', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'padding_a', [
			'label'      => esc_html__( 'Padding', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .front-side .layer-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'alignment_a', [
			'label'       => esc_html__( 'Alignment', 'unicamp' ),
			'type'        => Controls_Manager::CHOOSE,
			'label_block' => false,
			'options'     => [
				'left'   => [
					'title' => esc_html__( 'Left', 'unicamp' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'unicamp' ),
					'icon'  => 'eicon-text-align-center',
				],
				'right'  => [
					'title' => esc_html__( 'Right', 'unicamp' ),
					'icon'  => 'eicon-text-align-right',
				],
			],
			'selectors'   => [
				'{{WRAPPER}} .front-side' => 'text-align: {{VALUE}}',
			],
		] );

		$this->add_control( 'vertical_position_a', [
			'label'                => esc_html__( 'Vertical Position', 'unicamp' ),
			'type'                 => Controls_Manager::CHOOSE,
			'label_block'          => false,
			'options'              => [
				'top'    => [
					'title' => esc_html__( 'Top', 'unicamp' ),
					'icon'  => 'eicon-v-align-top',
				],
				'middle' => [
					'title' => esc_html__( 'Middle', 'unicamp' ),
					'icon'  => 'eicon-v-align-middle',
				],
				'bottom' => [
					'title' => esc_html__( 'Bottom', 'unicamp' ),
					'icon'  => 'eicon-v-align-bottom',
				],
			],
			'selectors_dictionary' => [
				'top'    => 'flex-start',
				'middle' => 'center',
				'bottom' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .front-side .layer-inner' => 'align-items: {{VALUE}}',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => 'border_a',
			'selector'  => '{{WRAPPER}} .front-side',
			'separator' => 'before',
		] );

		$this->end_controls_section();
	}

	private function add_front_side_image_style_section() {
		$this->start_controls_section( 'image_style_front_side_section', [
			'label'     => esc_html__( 'Front - Image', 'unicamp' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'graphic_element_a' => 'image',
			],
		] );

		$this->add_control( 'image_spacing_a', [
			'label'     => esc_html__( 'Spacing', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .front-side .image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'image_width_a', [
			'label'      => esc_html__( 'Size', 'unicamp' ) . ' (%)',
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%' ],
			'default'    => [
				'unit' => '%',
			],
			'range'      => [
				'%' => [
					'min' => 5,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .front-side .image img' => 'width: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_control( 'image_opacity_a', [
			'label'     => esc_html__( 'Opacity', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'max'  => 1,
					'min'  => 0.10,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .front-side .image' => 'opacity: {{SIZE}};',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => 'image_border_a',
			'selector'  => '{{WRAPPER}} .front-side .image img',
			'separator' => 'before',
		] );

		$this->add_control( 'image_border_radius_a', [
			'label'     => esc_html__( 'Border Radius', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .front-side .image img' => 'border-radius: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->end_controls_section();
	}

	private function add_front_side_icon_style_section() {
		$this->start_controls_section( 'icon_style_front_side_section', [
			'label'     => esc_html__( 'Front - Icon', 'unicamp' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'graphic_element_a' => 'icon',
			],
		] );

		$this->add_control( 'icon_spacing_a', [
			'label'     => esc_html__( 'Spacing', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .front-side .unicamp-icon-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'icon_a',
			'selector' => '{{WRAPPER}} .front-side .icon',
		] );

		$this->add_responsive_control( 'icon_size_a', [
			'label'     => esc_html__( 'Size', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 6,
					'max' => 300,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .front-side .unicamp-icon-view, {{WRAPPER}} .front-side .unicamp-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'icon_rotate_a', [
			'label'     => esc_html__( 'Rotate', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'default'   => [
				'unit' => 'deg',
			],
			'selectors' => [
				'{{WRAPPER}} .front-side .unicamp-icon > svg, {{WRAPPER}} .front-side .unicamp-icon > i' => 'transform: rotate({{SIZE}}{{UNIT}});',
			],
		] );

		$this->end_controls_section();
	}

	private function add_front_side_heading_style_section() {
		$this->start_controls_section( 'heading_style_front_side_section', [
			'label'     => esc_html__( 'Front - Heading', 'unicamp' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'title_text_a!' => '',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_a',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .front-side .heading',
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title_a',
			'selector' => '{{WRAPPER}} .front-side .heading',
		] );

		$this->end_controls_section();
	}

	private function add_front_side_description_style_section() {
		$this->start_controls_section( 'description_style_front_side_section', [
			'label'     => esc_html__( 'Front - Description', 'unicamp' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'description_text_a!' => '',
			],
		] );

		$this->add_control( 'description_spacing_a', [
			'label'     => esc_html__( 'Spacing', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .front-side .description-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'description_a',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
			'selector' => '{{WRAPPER}} .front-side .description',
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'description_a',
			'selector' => '{{WRAPPER}} .front-side .description',
		] );

		$this->end_controls_section();
	}

	private function add_back_side_box_style_section() {
		$this->start_controls_section( 'box_style_back_side_section', [
			'label' => esc_html__( 'Back - Box', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'padding_b', [
			'label'      => esc_html__( 'Padding', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .back-side .layer-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'alignment_b', [
			'label'       => esc_html__( 'Alignment', 'unicamp' ),
			'type'        => Controls_Manager::CHOOSE,
			'label_block' => false,
			'options'     => [
				'left'   => [
					'title' => esc_html__( 'Left', 'unicamp' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'unicamp' ),
					'icon'  => 'eicon-text-align-center',
				],
				'right'  => [
					'title' => esc_html__( 'Right', 'unicamp' ),
					'icon'  => 'eicon-text-align-right',
				],
			],
			'selectors'   => [
				'{{WRAPPER}} .back-side' => 'text-align: {{VALUE}}',
			],
		] );

		$this->add_control( 'vertical_position_b', [
			'label'                => esc_html__( 'Vertical Position', 'unicamp' ),
			'type'                 => Controls_Manager::CHOOSE,
			'label_block'          => false,
			'options'              => [
				'top'    => [
					'title' => esc_html__( 'Top', 'unicamp' ),
					'icon'  => 'eicon-v-align-top',
				],
				'middle' => [
					'title' => esc_html__( 'Middle', 'unicamp' ),
					'icon'  => 'eicon-v-align-middle',
				],
				'bottom' => [
					'title' => esc_html__( 'Bottom', 'unicamp' ),
					'icon'  => 'eicon-v-align-bottom',
				],
			],
			'selectors_dictionary' => [
				'top'    => 'flex-start',
				'middle' => 'center',
				'bottom' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .back-side .layer-inner' => 'align-items: {{VALUE}}',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => 'border_b',
			'selector'  => '{{WRAPPER}} .back-side',
			'separator' => 'before',
		] );

		$this->end_controls_section();
	}

	private function add_back_side_image_style_section() {
		$this->start_controls_section( 'image_style_back_side_section', [
			'label'     => esc_html__( 'Back - Image', 'unicamp' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'graphic_element_b' => 'image',
			],
		] );

		$this->add_control( 'image_spacing_b', [
			'label'     => esc_html__( 'Spacing', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .back-side .image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'image_width_b', [
			'label'      => esc_html__( 'Size', 'unicamp' ) . ' (%)',
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%' ],
			'default'    => [
				'unit' => '%',
			],
			'range'      => [
				'%' => [
					'min' => 5,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .back-side .image img' => 'width: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_control( 'image_opacity_b', [
			'label'     => esc_html__( 'Opacity', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'max'  => 1,
					'min'  => 0.10,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .back-side .image' => 'opacity: {{SIZE}};',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => 'image_border_b',
			'selector'  => '{{WRAPPER}} .back-side .image img',
			'separator' => 'before',
		] );

		$this->add_control( 'image_border_radius_b', [
			'label'     => esc_html__( 'Border Radius', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .back-side .image img' => 'border-radius: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->end_controls_section();
	}

	private function add_back_side_icon_style_section() {
		$this->start_controls_section( 'icon_style_back_side_section', [
			'label'     => esc_html__( 'Back - Icon', 'unicamp' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'graphic_element_b' => 'icon',
			],
		] );

		$this->add_control( 'icon_spacing_b', [
			'label'     => esc_html__( 'Spacing', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .back-side .unicamp-icon-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'icon_b',
			'selector' => '{{WRAPPER}} .back-side .icon',
		] );

		$this->add_responsive_control( 'icon_size_b', [
			'label'     => esc_html__( 'Size', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 6,
					'max' => 300,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .back-side .unicamp-icon-view, {{WRAPPER}} .back-side .unicamp-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'icon_rotate_b', [
			'label'     => esc_html__( 'Rotate', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'default'   => [
				'unit' => 'deg',
			],
			'selectors' => [
				'{{WRAPPER}} .back-side .unicamp-icon > svg, {{WRAPPER}} .back-side .unicamp-icon > i' => 'transform: rotate({{SIZE}}{{UNIT}});',
			],
		] );

		$this->end_controls_section();
	}

	private function add_back_side_heading_style_section() {
		$this->start_controls_section( 'heading_style_back_side_section', [
			'label'     => esc_html__( 'Back - Heading', 'unicamp' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'title_text_b!' => '',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_b',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .back-side .heading',
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title_b',
			'selector' => '{{WRAPPER}} .back-side .heading',
		] );

		$this->end_controls_section();
	}

	private function add_back_side_description_style_section() {
		$this->start_controls_section( 'description_style_back_side_section', [
			'label'     => esc_html__( 'Back - Description', 'unicamp' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'description_text_b!' => '',
			],
		] );

		$this->add_control( 'description_spacing_b', [
			'label'     => esc_html__( 'Spacing', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .back-side .description-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'description_b',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
			'selector' => '{{WRAPPER}} .back-side .description',
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'description_b',
			'selector' => '{{WRAPPER}} .back-side .description',
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="unicamp-flip-box">
			<?php $this->print_front_side_html( $settings ); ?>

			<?php $this->print_back_side_html( $settings ); ?>
		</div>
		<?php
	}

	private function print_front_side_html( array $settings ) {
		?>
		<div class="layer front-side">
			<div class="overlay"></div>
			<div class="layer-inner">
				<div class="layer-content">
					<?php
					switch ( $settings['graphic_element_a'] ) {
						case 'image':
							$this->print_graphic_image( $settings );
							break;
						case 'icon':
							$this->print_graphic_icon( $settings );
							break;
					}
					?>

					<?php $this->print_heading( $settings ); ?>

					<?php $this->print_description( $settings ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	private function print_back_side_html( array $settings ) {
		$wrapper_tag = 'div';

		$this->add_render_attribute( 'wrapper', 'class', 'layer back-side' );

		if ( ! empty( $settings['link']['url'] ) && 'box' === $settings['link_click'] ) {
			$wrapper_tag = 'a';

			$this->add_link_attributes( 'wrapper', $settings['link'] );
		}

		printf( '<%1$s %2$s>', $wrapper_tag, $this->get_render_attribute_string( 'wrapper' ) );
		?>
		<div class="overlay"></div>
		<div class="layer-inner">
			<div class="layer-content">
				<?php
				switch ( $settings['graphic_element_b'] ) {
					case 'image' :
						$this->print_graphic_image( $settings, 'b' );
						break;
					case 'icon' :
						$this->print_graphic_icon( $settings, 'b' );
						break;
				}
				?>

				<?php $this->print_heading( $settings, 'b' ); ?>

				<?php $this->print_description( $settings, 'b' ); ?>

				<?php $this->render_common_button(); ?>
			</div>
		</div>
		<?php
		printf( '</%1$s>', $wrapper_tag );
	}

	private function print_graphic_image( array $settings, $side = 'a' ) {
		$image_key = "image_{$side}";

		if ( empty( $settings[ $image_key ]['url'] ) ) {
			return;
		}
		?>
		<div class="image">
			<?php echo \Unicamp_Image::get_elementor_attachment( [
				'settings'  => $settings,
				'image_key' => $image_key,
			] ); ?>
		</div>
		<?php
	}

	private function print_graphic_icon( array $settings, $side = 'a' ) {
		if ( empty( $settings["icon_{$side}"]['value'] ) ) {
			return;
		}

		$icon_key = "icon-wrapper-{$side}";

		$this->add_render_attribute( $icon_key, 'class', [
			'unicamp-icon',
			'icon',
		] );

		$is_svg = isset( $settings["icon_{$side}"]['library'] ) && 'svg' === $settings["icon_{$side}"]['library'] ? true : false;

		if ( $is_svg ) {
			$this->add_render_attribute( $icon_key, 'class', [
				'unicamp-svg-icon',
			] );
		}

		switch ( $settings["icon_{$side}_color_type"] ) {
			case 'gradient' :
				$this->add_render_attribute( $icon_key, 'class', 'unicamp-gradient-icon' );
				break;
			case 'classic' :
				$this->add_render_attribute( $icon_key, 'class', 'unicamp-solid-icon' );
				break;
		}
		?>
		<div class="unicamp-icon-wrap">
			<div class="unicamp-icon-view">
				<div <?php $this->print_attributes_string( $icon_key ); ?>>
					<?php $this->render_icon( $settings, $settings["icon_{$side}"], [ 'aria-hidden' => 'true' ], $is_svg, "icon_{$side}" ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	private function print_heading( array $settings, $side = 'a' ) {
		if ( empty( $settings["title_text_{$side}"] ) ) {
			return;
		}
		?>
		<div class="heading-wrap">
			<h3 class="heading">
				<?php echo wp_kses( $settings["title_text_{$side}"], 'unicamp-default' ); ?>
			</h3>
		</div>
		<?php
	}

	private function print_description( array $settings, $side = 'a' ) {
		if ( empty( $settings["description_text_{$side}"] ) ) {
			return;
		}
		?>
		<div class="description-wrap">
			<div class="description">
				<?php echo wp_kses( $settings["description_text_{$side}"], 'unicamp-default' ); ?>
			</div>
		</div>
		<?php
	}

	protected function content_template() {
		$id = uniqid( 'svg-gradient' );
		// @formatter:off
		?>
		<# var svg_id = '<?php echo esc_html( $id ); ?>'; #>

		<#
		var icon_a_svg_id = 'icon-a-' + svg_id;
		var icon_b_svg_id = 'icon-b-' + svg_id;

		// Image Front Side.
		if ( 'image' === settings.graphic_element_a && '' !== settings.image_a.url ) {
			var imageA = {
				id: settings.image_a.id,
				url: settings.image_a.url,
				size: settings.image_a_size,
				dimension: settings.image_a_custom_dimension,
				model: view.getEditModel()
			};

			var imageSideAUrl = elementor.imagesManager.getImageUrl( imageA );
			view.addRenderAttribute( 'image-a', 'src', imageSideAUrl );
		}

		// Image Back Side.
		if ( 'image' === settings.graphic_element_b && '' !== settings.image_b.url ) {
			var imageB = {
				id: settings.image_b.id,
				url: settings.image_b.url,
				size: settings.image_b_size,
				dimension: settings.image_b_custom_dimension,
				model: view.getEditModel()
			};

			var imageSideBUrl = elementor.imagesManager.getImageUrl( imageB );
			view.addRenderAttribute( 'image-b', 'src', imageSideBUrl );
		}

		var wrapperTag = 'div';

		if ( '' !== settings.link.url && 'box' === settings.link_click ) {
			wrapperTag = 'a';
		}
		#>
		<div class="unicamp-flip-box">

			<div class="layer front-side">
				<div class="overlay"></div>
				<div class="layer-inner">
					<div class="layer-content">
						<# if ( 'image' === settings.graphic_element_a && '' !== settings.image_a.url ) { #>
							<div class="image">
								<img {{{ view.getRenderAttributeString( 'image-a' ) }}} />
							</div>
						<#  } else if ( 'icon' === settings.graphic_element_a ) { #>
							<#
							view.addRenderAttribute( 'iconA', 'class', 'unicamp-icon icon' );

							if ( 'svg' === settings.icon_a.library ) {
								view.addRenderAttribute( 'iconA', 'class', 'unicamp-svg-icon' );
							}

							switch( settings.icon_a_color_type ) {
								case 'gradient':
									view.addRenderAttribute( 'iconA', 'class', 'unicamp-gradient-icon' );
									break;
								case 'classic':
									view.addRenderAttribute( 'iconA', 'class', 'unicamp-solid-icon' );
									break;
							}

							var iconAHTML = elementor.helpers.renderIcon( view, settings.icon_a, { 'aria-hidden': true }, 'i' , 'object' );
							#>
							<div class="unicamp-icon-wrap">
								<div class="unicamp-icon-view">
									<div {{{ view.getRenderAttributeString( 'iconA' ) }}}>
										<# if ( iconAHTML.rendered ) { #>
											<#
											var stop_a = settings.icon_a_color_a_stop.size + settings.icon_a_color_a_stop.unit;
											var stop_b = settings.icon_a_color_b_stop.size + settings.icon_a_color_b_stop.unit;

											var iconValue = iconAHTML.value;
											if ( typeof iconValue === 'string' ) {
												var strokeAttr = 'stroke="' + 'url(#' + icon_a_svg_id + ')"';
												var fillAttr = 'fill="' + 'url(#' + icon_a_svg_id + ')"';

												iconValue = iconValue.replace(new RegExp(/stroke="#(.*?)"/, 'g'), strokeAttr);
												iconValue = iconValue.replace(new RegExp(/fill="#(.*?)"/, 'g'), fillAttr);
											}
											#>

											<# if ( 'gradient' === settings.icon_a_color_type ) { #>
											<svg aria-hidden="true" focusable="false" class="svg-defs-gradient">
												<defs>
													<linearGradient id="{{{ icon_a_svg_id }}}" x1="0%" y1="0%" x2="0%" y2="100%">
														<stop class="stop-a" offset="{{{ stop_a }}}"/>
														<stop class="stop-b" offset="{{{ stop_b }}}"/>
													</linearGradient>
												</defs>
											</svg>
											<# } #>
											{{{ iconValue }}}
										<# } #>
									</div>
								</div>
							</div>
						<# } #>

						<# if ( settings.title_text_a ) { #>
							<div class="heading-wrap">
								<h3 class="heading">{{{ settings.title_text_a }}}</h3>
							</div>
						<# } #>

						<# if ( settings.description_text_a ) { #>
							<div class="description-wrap">
								<div class="description">{{{ settings.description_text_a }}}</div>
							</div>
						<# } #>
					</div>
				</div>
			</div>

			<{{ wrapperTag }} class="layer back-side">
				<div class="overlay"></div>
				<div class="layer-inner">
					<div class="layer-content">
						<# if ( 'image' === settings.graphic_element_b && '' !== settings.image_b.url ) { #>
							<div class="image">
								<img {{{ view.getRenderAttributeString( 'image-b' ) }}} />
							</div>
						<#  } else if ( 'icon' === settings.graphic_element_b ) { #>
							<#
							view.addRenderAttribute( 'iconB', 'class', 'unicamp-icon icon' );

							if ( 'svg' === settings.icon_b.library ) {
								view.addRenderAttribute( 'iconB', 'class', 'unicamp-svg-icon' );
							}

							switch( settings.icon_b_color_type ) {
								case 'gradient':
									view.addRenderAttribute( 'iconB', 'class', 'unicamp-gradient-icon' );
									break;
								case 'classic':
									view.addRenderAttribute( 'iconB', 'class', 'unicamp-solid-icon' );
									break;
							}

							var iconBHTML = elementor.helpers.renderIcon( view, settings.icon_b, { 'aria-hidden': true }, 'i' , 'object' );
							#>
							<div class="unicamp-icon-wrap">
								<div class="unicamp-icon-view">
									<div {{{ view.getRenderAttributeString( 'iconB' ) }}}>
										<# if ( iconBHTML.rendered ) { #>
											<#
											var stop_a = settings.icon_b_color_a_stop.size + settings.icon_b_color_a_stop.unit;
											var stop_b = settings.icon_b_color_b_stop.size + settings.icon_b_color_b_stop.unit;

											var iconValue = iconBHTML.value;
											if ( typeof iconValue === 'string' ) {
												var strokeAttr = 'stroke="' + 'url(#' + icon_b_svg_id + ')"';
												var fillAttr = 'fill="' + 'url(#' + icon_b_svg_id + ')"';

												iconValue = iconValue.replace(new RegExp(/stroke="#(.*?)"/, 'g'), strokeAttr);
												iconValue = iconValue.replace(new RegExp(/fill="#(.*?)"/, 'g'), fillAttr);
											}
											#>

											<# if ( 'gradient' === settings.icon_b_color_type ) { #>
											<svg aria-hidden="true" focusable="false" class="svg-defs-gradient">
												<defs>
													<linearGradient id="{{{ icon_b_svg_id }}}" x1="0%" y1="0%" x2="0%" y2="100%">
														<stop class="stop-a" offset="{{{ stop_a }}}"/>
														<stop class="stop-b" offset="{{{ stop_b }}}"/>
													</linearGradient>
												</defs>
											</svg>
											<# } #>
											{{{ iconValue }}}
										<# } #>
									</div>
								</div>
							</div>
						<# } #>

						<# if ( settings.title_text_b ) { #>
							<div class="heading-wrap">
								<h3 class="heading">{{{ settings.title_text_b }}}</h3>
							</div>
						<# } #>

						<# if ( settings.description_text_b ) { #>
							<div class="description-wrap">
								<div class="description">{{{ settings.description_text_b }}}</div>
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
			</{{ wrapperTag }}>

		</div>
		<?php
		// @formatter:off
	}
}
