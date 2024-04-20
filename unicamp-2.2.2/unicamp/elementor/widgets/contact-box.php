<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

class Widget_Contact_Box extends Base {

	public function get_name() {
		return 'tm-contact-box';
	}

	public function get_title() {
		return esc_html__( 'Contact Box', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-bullet-list';
	}

	public function get_keywords() {
		return [ 'contact', 'box' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_styling_section();

		$this->add_text_style_section();

		$this->add_badge_style_section();

		$this->add_icon_style_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Content', 'unicamp' ),
		] );

		$this->add_control( 'style', [
			'label'        => esc_html__( 'Style', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'default'      => '01',
			'options'      => [
				'01' => '01',
				'02' => '02',
			],
			'prefix_class' => 'unicamp-contact-box-style-',
		] );

		$this->add_control( 'title_text', [
			'label'       => esc_html__( 'Title', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'sub_title', [
			'label'       => esc_html__( 'Sub title', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
		] );

		$repeater->add_control( 'phone_number', [
			'label'       => esc_html__( 'Phone Number', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
		] );

		$repeater->add_control( 'email', [
			'label'       => esc_html__( 'Email', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
		] );

		$this->add_control( 'items', [
			'label'   => esc_html__( 'Items', 'unicamp' ),
			'type'    => Controls_Manager::REPEATER,
			'fields'  => $repeater->get_controls(),
			'default' => [
				[
					'phone_number' => '123 0039 68886',
					'email'        => 'example@unicamp.com',
				],
			],
		] );

		$this->end_controls_section();
	}

	private function add_styling_section() {
		$this->start_controls_section( 'styling_section', [
			'label' => esc_html__( 'Styling', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'items_vertical_spacing', [
			'label'      => esc_html__( 'Items Spacing', 'unicamp' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max'  => 200,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}}.unicamp-list-layout-block .item + .item, {{WRAPPER}}.unicamp-list-layout-columns .item:nth-child(2) ~ .item' => 'margin-top: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}}.unicamp-list-layout-inline .item'                                                                            => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'width', [
			'label'      => esc_html__( 'Width', 'unicamp' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'%'  => [
					'max'  => 100,
					'step' => 1,
				],
				'px' => [
					'max'  => 1000,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .unicamp-list' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'alignment', [
			'label'     => esc_html__( 'Alignment', 'unicamp' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => Widget_Utils::get_control_options_horizontal_alignment(),
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}}' => 'text-align: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'text_align', [
			'label'                => esc_html__( 'Text Align', 'unicamp' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .item' => 'text-align: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	private function add_text_style_section() {
		$this->start_controls_section( 'text_style_section', [
			'label' => esc_html__( 'Text', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'text_typography',
			'label'    => esc_html__( 'Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .text',
		] );

		$this->start_controls_tabs( 'text_style_tabs' );

		$this->start_controls_tab( 'text_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'text',
			'selector' => '{{WRAPPER}} .text',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'text_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'hover_text',
			'selector' => '{{WRAPPER}} .link:hover .text',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_badge_style_section() {
		$this->start_controls_section( 'badge_style_section', [
			'label' => esc_html__( 'Badge', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'badge_typography',
			'label'    => esc_html__( 'Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .badge',
		] );

		$this->start_controls_tabs( 'badge_style_tabs' );

		$this->start_controls_tab( 'badge_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'badge',
			'selector' => '{{WRAPPER}} .badge',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'badge_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'hover_badge',
			'selector' => '{{WRAPPER}} .link:hover .badge',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_icon_style_section() {
		$this->start_controls_section( 'icon_style_section', [
			'label' => esc_html__( 'Icon', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'icon_align', [
			'label'                => esc_html__( 'Alignment', 'unicamp' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
			'default'              => 'center',
			'selectors'            => [
				'{{WRAPPER}} .icon' => 'text-align: {{VALUE}};',
			],
		] );

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
				'{{WRAPPER}} .icon' => 'margin-right: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'icon_size', [
			'label'     => esc_html__( 'Size', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 3,
					'max' => 20,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'min_width', [
			'label'          => esc_html__( 'Min Width', 'unicamp' ),
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
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .icon' => 'min-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->start_controls_tabs( 'icon_style_tabs' );

		$this->start_controls_tab( 'icon_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'icon',
			'selector' => '{{WRAPPER}} .icon',
		] );

		$this->add_control( 'icon_marker_color', [
			'label'     => esc_html__( 'Icon Marker', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .icon-view' => 'color: {{VALUE}};',
			],
			'condition' => [
				'style' => [
					'icon-border',
					'icon-circle',
				],
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'icon_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'hover_icon',
			'selector' => '{{WRAPPER}} .link:hover .icon',
		] );

		$this->add_control( 'hover_icon_marker_color', [
			'label'     => esc_html__( 'Icon Marker', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .link:hover .icon-view' => 'color: {{VALUE}};',
			],
			'condition' => [
				'style' => [
					'icon-border',
					'icon-circle',
				],
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'unicamp-box unicamp-contact-box' );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>

			<?php if ( ! empty( $settings['title_text'] ) ) { ?>
				<h4 class="contact-box-title"><?php echo esc_html( $settings['title_text'] ); ?></h4>
			<?php } ?>

			<?php if ( $settings['items'] && count( $settings['items'] ) > 0 ) {
				foreach ( $settings['items'] as $key => $item ) {
					?>
					<div class="contact-box-info">
						<?php if ( ! empty( $item['sub_title'] ) ) { ?>
							<p class="contact-box-sub-title">
								<span class="text"><?php echo esc_html( $item['sub_title'] ); ?></span>
							</p>
						<?php } ?>

						<?php if ( ! empty( $item['phone_number'] ) ) { ?>
							<div class="contact-box-info-item phone-number">
								<span class="meta-label"><?php esc_html_e( 'Phone number', 'unicamp' ); ?></span>
								<span class="meta-value"><?php echo esc_html( $item['phone_number'] ); ?></span>
							</div>
						<?php } ?>

						<?php if ( ! empty( $item['email'] ) ) { ?>
							<div class="contact-box-info-item email-address">
								<span class="meta-label"><?php esc_html_e( 'Email', 'unicamp' ); ?></span>
								<span class="meta-value"><?php echo esc_html( $item['email'] ); ?></span>
							</div>
						<?php } ?>
					</div>
					<?php
				}
			}
			?>
		</div>
		<?php
	}
}
