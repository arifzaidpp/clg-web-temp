<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Base\Document;
use Elementor\Plugin;

defined( 'ABSPATH' ) || exit;

class Widget_Tabs extends Base {

	public function __construct( array $data = [], array $args = null ) {
		parent::__construct( $data, $args );

		wp_register_script( 'unicamp-widget-tabs', UNICAMP_ELEMENTOR_URI . '/assets/js/widgets/widget-tabs.js', array(
			'elementor-frontend',
			'unicamp-tab-panel',
		), null, true );
	}

	public function get_name() {
		return 'tm-tabs';
	}

	public function get_title() {
		return esc_html__( 'Advanced Tabs', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-tabs';
	}

	public function get_keywords() {
		return [ 'advanced', 'modern', 'tabs' ];
	}

	public function get_script_depends() {
		return [ 'unicamp-widget-tabs' ];
	}

	public function is_reload_preview_required() {
		return false;
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_styling_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Items', 'unicamp' ),
		] );

		$this->add_control( 'style', [
			'label'        => esc_html__( 'Style', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'default'      => '01',
			'options'      => [
				'01' => '01',
				'02' => '02',
				'03' => '03',
				'04' => '04',
			],
			'prefix_class' => 'unicamp-tabs-style-',
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'title', [
			'label'       => esc_html__( 'Title', 'unicamp' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => esc_html__( 'Tab Title', 'unicamp' ),
			'label_block' => true,
			'dynamic'     => [
				'active' => true,
			],
		] );

		$repeater->add_control( 'description', [
			'label' => esc_html__( 'Description', 'unicamp' ),
			'type'  => Controls_Manager::TEXTAREA,
		] );

		$repeater->add_control( 'icon', [
			'label' => esc_html__( 'Icon', 'unicamp' ),
			'type'  => Controls_Manager::ICONS,
		] );

		$repeater->add_control( 'content_type', [
			'label'       => esc_html__( 'Content Type', 'unicamp' ),
			'type'        => Controls_Manager::CHOOSE,
			'label_block' => false,
			'toggle'      => false,
			'render_type' => 'template',
			'default'     => 'content',
			'separator'   => 'before',
			'options'     => [
				'content'  => [
					'title' => esc_html__( 'Content', 'unicamp' ),
					'icon'  => 'eicon-edit',
				],
				'template' => [
					'title' => esc_html__( 'Saved Template', 'unicamp' ),
					'icon'  => 'eicon-library-open',
				],
			],
		] );

		$repeater->add_control( 'content', [
			'label'      => esc_html__( 'Content', 'unicamp' ),
			'show_label' => false,
			'type'       => Controls_Manager::WYSIWYG,
			'condition'  => [
				'content_type' => 'content',
			],
		] );

		$document_types = Plugin::$instance->documents->get_document_types( [
			'show_in_library' => true,
		] );

		$repeater->add_control( 'template_id', [
			'label'        => esc_html__( 'Choose Template', 'unicamp' ),
			'label_block'  => true,
			'show_label'   => false,
			'type'         => Module_Query_Base::AUTOCOMPLETE_CONTROL_ID,
			'autocomplete' => [
				'object' => Module_Query_Base::QUERY_OBJECT_LIBRARY_TEMPLATE,
				'query'  => [
					'meta_query' => [
						[
							'key'     => Document::TYPE_META_KEY,
							'value'   => array_keys( $document_types ),
							'compare' => 'IN',
						],
					],
				],
			],
			'condition'    => [
				'content_type' => 'template',
			],
		] );

		$this->add_control( 'items', [
			'label'       => esc_html__( 'Items', 'unicamp' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[
					'title'   => esc_html__( 'Tab Title #1', 'unicamp' ),
					'content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'unicamp' ),
				],
				[
					'title'   => esc_html__( 'Tab Title #2', 'unicamp' ),
					'content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'unicamp' ),
				],
				[
					'title'   => esc_html__( 'Tab Title #3', 'unicamp' ),
					'content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'unicamp' ),
				],
			],
			'title_field' => '{{{ title }}}',
		] );

		$this->add_control( 'view', [
			'label'   => esc_html__( 'View', 'unicamp' ),
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'traditional',
		] );

		$this->add_control( 'type', [
			'label'        => esc_html__( 'Type', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'default'      => 'horizontal',
			'options'      => [
				'horizontal' => esc_html__( 'Horizontal', 'unicamp' ),
				'vertical'   => esc_html__( 'Vertical', 'unicamp' ),
			],
			'prefix_class' => 'unicamp-tabs-view-',
			'separator'    => 'before',
			'render_type'  => 'template',
		] );

		$this->add_control( 'nav_position_reversed', [
			'label'     => esc_html__( 'Nav Position Reversed?', 'unicamp' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => [
				'type' => 'vertical',
			],
		] );

		$this->end_controls_section();
	}

	private function add_styling_section() {
		$this->start_controls_section( 'tabs_styling_section', [
			'label' => esc_html__( 'Styling', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'nav_width', [
			'label'      => esc_html__( 'Nav Width', 'unicamp' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%', 'vw' ],
			'range'      => [
				'px' => [
					'min' => 1,
					'max' => 1000,
				],
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'vw' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .unicamp-tabpanel.unicamp-tabpanel-vertical .unicamp-nav-tabs' => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'type' => 'vertical',
			],
		] );

		$this->start_controls_tabs( 'title_colors_settings' );

		$this->start_controls_tab( 'nav_colors_normal', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_control( 'icon_color_hr', [
			'label' => esc_html__( 'Icon', 'unicamp' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'icon',
			'selector' => '{{WRAPPER}} .icon',
		] );

		$this->add_control( 'title_color_hr', [
			'label' => esc_html__( 'Title', 'unicamp' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title',
			'selector' => '{{WRAPPER}} .nav-tab-title',
		] );

		$this->add_control( 'description_color_hr', [
			'label' => esc_html__( 'Description', 'unicamp' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'description',
			'selector' => '{{WRAPPER}} .nav-tab-description',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'nav_colors_hover', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_control( 'hover_icon_color_hr', [
			'label' => esc_html__( 'Icon', 'unicamp' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'hover_icon',
			'selector' => '{{WRAPPER}} .unicamp-tab-title:hover .icon',
		] );

		$this->add_control( 'hover_title_color_hr', [
			'label' => esc_html__( 'Title', 'unicamp' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'hover_title',
			'selector' => '{{WRAPPER}} .unicamp-tab-title:hover .nav-tab-title',
		] );

		$this->add_control( 'hover_description_color_hr', [
			'label' => esc_html__( 'Description', 'unicamp' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'hover_description',
			'selector' => '{{WRAPPER}} .unicamp-tab-title:hover .nav-tab-description',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'nav_colors_active', [
			'label' => esc_html__( 'Active', 'unicamp' ),
		] );

		$this->add_control( 'active_icon_color_hr', [
			'label' => esc_html__( 'Icon', 'unicamp' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'active_icon',
			'selector' => '{{WRAPPER}} .active > .unicamp-tab-title .icon',
		] );

		$this->add_control( 'active_title_color_hr', [
			'label' => esc_html__( 'Title', 'unicamp' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'active_title',
			'selector' => '{{WRAPPER}} .active > .unicamp-tab-title .nav-tab-title',
		] );

		$this->add_control( 'active_description_color_hr', [
			'label' => esc_html__( 'Description', 'unicamp' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'active_description',
			'selector' => '{{WRAPPER}} .active > .unicamp-tab-title .nav-tab-description',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'heading_style_hr', [
			'type' => Controls_Manager::DIVIDER,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'desktop_title_typography',
			'label'    => esc_html__( 'Desktop Title Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .unicamp-desktop-heading',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'mobile_title_typography',
			'label'    => esc_html__( 'Mobile Title Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .unicamp-mobile-heading',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'description_typography',
			'label'    => esc_html__( 'Description Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .nav-tab-description',
		] );

		$this->add_control( 'icon_style_hr', [
			'label'     => esc_html__( 'Icon', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
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
				'{{WRAPPER}} .unicamp-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'icon_margin', [
			'label'      => esc_html__( 'Margin', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .unicamp-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Do nothing if there is not any items.
		if ( empty( $settings['items'] ) || count( $settings['items'] ) <= 0 ) {
			return;
		}

		$tabs = $settings['items'];
		$this->add_render_attribute( 'wrapper', 'class', 'unicamp-tabpanel unicamp-tabpanel-' . $settings['type'] );

		if ( isset( $settings['nav_position_reversed'] ) && 'yes' === $settings['nav_position_reversed'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'unicamp-tabpanel-nav-reversed' );
		}

		$this->add_render_attribute( 'wrapper', 'role', 'tablist' );

		$title_kses = [
			'i' => [
				'class' => [],
				'style' => [],
			],
		];

		$tab_navs_html   = '';
		$tab_panels_html = '';
		$loop_count      = 0;
		?>
		<?php foreach ( $tabs as $key => $tab ) : ?>
			<?php
			$tab_key = "nav_tab_{$key}_";
			$loop_count++;

			$this->add_render_attribute( $tab_key, 'role', 'tab' );

			if ( 1 === $loop_count ) {
				$this->add_render_attribute( $tab_key, 'class', 'active' );
			}

			$icon_key = $tab_key . '_icon';

			$this->add_render_attribute( $icon_key, 'class', [
				'nav-tab-icon',
				'unicamp-icon',
				'icon',
			] );

			$is_svg = isset( $tab['icon']['library'] ) && 'svg' === $tab['icon']['library'] ? true : false;

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

			ob_start();
			?>
			<li <?php $this->print_render_attribute_string( $tab_key ); ?>>
				<a href="javascript:void(0);" class="unicamp-tab-title unicamp-desktop-heading">
					<?php if ( ! empty( $tab['icon']['value'] ) ): ?>
						<div <?php $this->print_render_attribute_string( $icon_key ); ?>><?php $this->render_icon( $settings, $tab['icon'], [ 'aria-hidden' => 'true' ], $is_svg, 'icon' ); ?></div>
					<?php endif; ?>
					<div class="nav-tab-heading">
						<div class="nav-tab-title"><?php echo wp_kses( $tab['title'], $title_kses ); ?></div>
						<?php if ( isset( $tab['description'] ) && '' !== $tab['description'] )  : ?>
							<div
								class="nav-tab-description"><?php echo wp_kses( $tab['description'], $title_kses ); ?></div>
						<?php endif; ?>
					</div>
				</a>
			</li>
			<?php $tab_navs_html .= ob_get_clean(); ?>

			<?php
			$tab_panel_key = "content_tab_{$key}_";

			$this->add_render_attribute( $tab_panel_key, 'class', 'tab-panel' );

			if ( 1 === $loop_count ) {
				$this->add_render_attribute( $tab_panel_key, 'class', 'active' );
			}

			ob_start();
			?>
			<div <?php $this->print_render_attribute_string( $tab_panel_key ); ?>>
				<div class="unicamp-tab-title tab-mobile-heading">
					<?php if ( ! empty( $tab['icon']['value'] ) ): ?>
						<div <?php $this->print_render_attribute_string( $icon_key ); ?>><?php $this->render_icon( $settings, $tab['icon'], [ 'aria-hidden' => 'true' ], $is_svg, 'icon' ); ?></div>
					<?php endif; ?>
					<div class="nav-tab-heading">
						<div class="nav-tab-title"><?php echo wp_kses( $tab['title'], $title_kses ); ?></div>
						<?php if ( isset( $tab['description'] ) && '' !== $tab['description'] )  : ?>
							<div
								class="nav-tab-description"><?php echo wp_kses( $tab['description'], $title_kses ); ?></div>
						<?php endif; ?>
					</div>
				</div>
				<div class="tab-content">
					<?php if ( 'template' === $tab['content_type'] ): ?>
						<?php
						if ( isset( $tab['template_id'] ) && '' !== $tab['template_id'] ) {
							echo Plugin::$instance->frontend->get_builder_content_for_display( $tab['template_id'] );
						}
						?>
					<?php else: ?>
						<?php echo '' . $this->parse_text_editor( $tab['content'] ); ?>
					<?php endif; ?>
				</div>
			</div>
			<?php $tab_panels_html .= ob_get_clean(); ?>
		<?php endforeach; ?>

		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<ul class="unicamp-nav-tabs"><?php echo '' . $tab_navs_html; ?></ul>
			<div class="unicamp-tab-content"><?php echo '' . $tab_panels_html; ?></div>
		</div>
		<?php
	}
}
