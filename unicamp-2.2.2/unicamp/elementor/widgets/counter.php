<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Schemes\Typography as Scheme_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || exit;

class Widget_Counter extends Base {

	public function get_name() {
		return 'tm-counter';
	}

	public function get_title() {
		return esc_html__( 'Modern Counter', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-counter';
	}

	public function get_keywords() {
		return [ 'counter' ];
	}

	public function get_script_depends() {
		return [ 'unicamp-widget-counter' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_box_style_section();

		$this->add_number_style_section();

		$this->add_title_style_section();

		$this->add_graphic_style_section();

		$this->add_icon_style_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'counter_section', [
			'label' => esc_html__( 'Counter', 'unicamp' ),
		] );

		$this->add_control( 'style', [
			'label'        => esc_html__( 'Style', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''   => esc_html__( 'None', 'unicamp' ),
				'01' => '01',
				'02' => '02',
			],
			'default'      => '',
			'prefix_class' => 'unicamp-counter-style-',
		] );

		$this->add_control( 'number_heading', [
			'label'     => esc_html__( 'Number', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'starting_number', [
			'label'   => esc_html__( 'Starting Number', 'unicamp' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 0,
			'dynamic' => [
				'active' => true,
			],
		] );

		$this->add_control( 'ending_number', [
			'label'   => esc_html__( 'Ending Number', 'unicamp' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 100,
			'dynamic' => [
				'active' => true,
			],
		] );

		$this->add_control( 'prefix', [
			'label'   => esc_html__( 'Number Prefix', 'unicamp' ),
			'type'    => Controls_Manager::TEXT,
			'dynamic' => [
				'active' => true,
			],
		] );

		$this->add_control( 'suffix', [
			'label'   => esc_html__( 'Number Suffix', 'unicamp' ),
			'type'    => Controls_Manager::TEXT,
			'dynamic' => [
				'active' => true,
			],
		] );

		$this->add_control( 'duration', [
			'label'   => esc_html__( 'Animation Duration', 'unicamp' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 2000,
			'min'     => 100,
			'step'    => 100,
		] );

		$this->add_control( 'thousand_separator', [
			'label'     => esc_html__( 'Thousand Separator', 'unicamp' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'label_on'  => esc_html__( 'Show', 'unicamp' ),
			'label_off' => esc_html__( 'Hide', 'unicamp' ),
		] );

		$this->add_control( 'thousand_separator_char', [
			'label'     => esc_html__( 'Separator', 'unicamp' ),
			'type'      => Controls_Manager::SELECT,
			'condition' => [
				'thousand_separator' => 'yes',
			],
			'options'   => [
				''  => 'Default',
				'.' => 'Dot',
				' ' => 'Space',
			],
		] );

		$this->add_control( 'content_heading', [
			'label'     => esc_html__( 'Content', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
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

		$this->add_control( 'graphic_position', [
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
			'condition'    => [
				'graphic_element!' => 'none',
			],
		] );

		$this->add_control( 'graphic_vertical_alignment', [
			'label'        => esc_html__( 'Vertical Alignment', 'unicamp' ),
			'type'         => Controls_Manager::CHOOSE,
			'options'      => Widget_Utils::get_control_options_vertical_alignment(),
			'default'      => 'top',
			'prefix_class' => 'unicamp-graphic-ver-align-',
			'condition'    => [
				'graphic_element!'  => 'none',
				'graphic_position!' => 'top',
			],
		] );

		$this->add_control( 'title_text', [
			'label'       => esc_html__( 'Title', 'unicamp' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'Cool Number', 'unicamp' ),
			'separator'   => 'before',
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
				'{{WRAPPER}} .unicamp-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'selector' => '{{WRAPPER}} .unicamp-box',
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .unicamp-box',
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
			'selector' => '{{WRAPPER}} .unicamp-box:before',
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'box_hover_border',
			'selector' => '{{WRAPPER}} .unicamp-box:hover',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_hover',
			'selector' => '{{WRAPPER}} .unicamp-box:hover',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

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

	private function add_number_style_section() {
		$this->start_controls_section( 'number_style_section', [
			'label' => esc_html__( 'Number', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'number',
			'selector' => '{{WRAPPER}} .counter-number-wrap',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
		] );

		$this->start_controls_tabs( 'number_colors' );

		$this->start_controls_tab( 'number_color_normal', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'number',
			'selector' => '{{WRAPPER}} .counter-number-wrap',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'number_color_hover', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'number_hover',
			'selector' => '{{WRAPPER}} .unicamp-box:hover .counter-number-wrap',
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

	private function add_title_style_section() {
		$this->start_controls_section( 'title_style_section', [
			'label' => esc_html__( 'Title', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'heading_margin', [
			'label'      => esc_html__( 'Margin', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .heading-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title',
			'selector' => '{{WRAPPER}} .counter-heading',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
		] );

		$this->start_controls_tabs( 'title_colors' );

		$this->start_controls_tab( 'title_color_normal', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title',
			'selector' => '{{WRAPPER}} .counter-heading',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'title_color_hover', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title_hover',
			'selector' => '{{WRAPPER}} .unicamp-box:hover .counter-heading',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'box', 'class', 'unicamp-box unicamp-graphic-box tm-counter' );

		if ( ! isset( $settings['ending_number'] ) ) {
			return;
		}

		$starting_number = isset( $settings['starting_number'] ) ? intval( $settings['starting_number'] ) : 0;
		$ending_number   = intval( $settings['ending_number'] );
		$duration        = isset( $settings['duration'] ) ? intval( $settings['duration'] ) : 2000;
		$separator_char  = '';

		if ( 'yes' === $settings['thousand_separator'] ) {
			$separator_char = ! empty( $settings['thousand_separator_char'] ) ? $settings['thousand_separator_char'] : ',';
		}

		$counter_options = [
			'from'      => $starting_number,
			'to'        => $ending_number,
			'duration'  => $duration,
			'separator' => $separator_char,
		];

		$this->add_render_attribute( 'box', 'data-counter', wp_json_encode( $counter_options ) );
		?>
		<div <?php $this->print_render_attribute_string( 'box' ); ?>>
			<?php if ( 'none' !== $settings['graphic_element'] ) : ?>
				<?php
				switch ( $settings['graphic_element'] ) {
					case 'image' :
						$this->print_graphic_image();
						break;
					case 'icon' :
						$this->print_graphic_icon();
						break;
				}
				?>
			<?php endif; ?>

			<div class="unicamp-graphic-content counter-content">
				<div class="counter-number-wrap">
					<span class="counter-number-prefix"><?php echo esc_html( $settings['prefix'] ); ?></span>
					<span class="counter-number"><?php echo esc_html( $starting_number ); ?></span>
					<span class="counter-number-suffix"><?php echo esc_html( $settings['suffix'] ); ?></span>
				</div>

				<?php $this->print_title(); ?>
			</div>
		</div>
		<?php
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

	private function print_title() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['title_text'] ) ) {
			return;
		}

		$title_text = wp_kses( $settings['title_text'], [
			'br'   => [],
			'span' => [
				'class' => [],
			],
			'mark' => [
				'class' => [],
			],
		] );

		$this->add_render_attribute( 'title', 'class', 'counter-heading' );
		?>
		<div class="heading-wrap">
			<?php printf( '<%1$s %2$s>%3$s</%1$s>', 'h3', $this->get_render_attribute_string( 'title' ), $title_text ); ?>
		</div>
		<?php
	}
}
