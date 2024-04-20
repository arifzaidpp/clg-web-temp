<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

class Widget_List extends Base {

	public function get_name() {
		return 'tm-list';
	}

	public function get_title() {
		return esc_html__( 'Modern Icon List', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-bullet-list';
	}

	public function get_keywords() {
		return [ 'modern', 'icon list', 'icon', 'list' ];
	}

	protected function register_controls() {
		$this->add_list_section();

		$this->add_styling_section();

		$this->add_text_style_section();

		$this->add_badge_style_section();

		$this->add_icon_style_section();
	}

	private function add_list_section() {
		$this->start_controls_section( 'list_section', [
			'label' => esc_html__( 'Icon List', 'unicamp' ),
		] );

		$this->add_control( 'style', [
			'label'        => esc_html__( 'Style', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'default'      => '',
			'options'      => [
				''               => esc_html__( 'Normal', 'unicamp' ),
				'icon-border'    => esc_html__( 'Icon Border', 'unicamp' ),
				'icon-circle'    => esc_html__( 'Icon Circle', 'unicamp' ),
				'bottom-line'    => esc_html__( 'Bottom Line 01', 'unicamp' ),
				'bottom-line-02' => esc_html__( 'Bottom Line 02', 'unicamp' ),
				'thin-text'      => esc_html__( 'Thin Text', 'unicamp' ),
			],
			'prefix_class' => 'unicamp-list-style-',
		] );

		$this->add_control( 'layout', [
			'label'        => esc_html__( 'Layout', 'unicamp' ),
			'label_block'  => false,
			'type'         => Controls_Manager::CHOOSE,
			'default'      => 'block',
			'options'      => [
				'block'   => [
					'title' => esc_html__( 'Default', 'unicamp' ),
					'icon'  => 'eicon-editor-list-ul',
				],
				'inline'  => [
					'title' => esc_html__( 'Inline', 'unicamp' ),
					'icon'  => 'eicon-ellipsis-h',
				],
				'columns' => [
					'title' => esc_html__( 'Columns', 'unicamp' ),
					'icon'  => 'eicon-columns',
				],
			],
			'prefix_class' => 'unicamp-list-layout-',
			'separator'    => 'after',
		] );

		$this->add_control( 'graphic_element', [
			'label'       => esc_html__( 'Graphic Element', 'unicamp' ),
			'type'        => Controls_Manager::CHOOSE,
			'label_block' => false,
			'options'     => [
				'icon'  => [
					'title' => esc_html__( 'Icon', 'unicamp' ),
					'icon'  => 'eicon-star',
				],
				'image' => [
					'title' => esc_html__( 'Image', 'unicamp' ),
					'icon'  => 'fa fa-picture-o',
				],
			],
			'default'     => 'icon',
		] );

		$this->add_control( 'icon', [
			'label'       => esc_html__( 'Default Icon', 'unicamp' ),
			'description' => esc_html__( 'Choose default icon for all items.', 'unicamp' ),
			'type'        => Controls_Manager::ICONS,
			'condition'   => [
				'graphic_element' => 'icon',
			],
		] );

		$this->add_control( 'image', [
			'label'       => esc_html__( 'Default Image', 'unicamp' ),
			'description' => esc_html__( 'Choose default image for all items.', 'unicamp' ),
			'type'        => Controls_Manager::MEDIA,
			'condition'   => [
				'graphic_element' => 'image',
			],
			'classes'     => 'unicamp-control-media-auto',
		] );

		$this->add_control( 'icon_vertical_alignment', [
			'label'                => esc_html__( 'Graphic Alignment', 'unicamp' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_vertical_alignment(),
			'default'              => 'middle',
			'selectors_dictionary' => [
				'top'    => 'flex-start',
				'middle' => 'center',
				'bottom' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .list-header' => 'align-items: {{VALUE}}',
			],
		] );

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'list_item_tabs' );

		$repeater->start_controls_tab( 'list_item_content_tab', [
			'label' => esc_html__( 'Content', 'unicamp' ),
		] );

		$repeater->add_control( 'text', [
			'label'       => esc_html__( 'Text', 'unicamp' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => esc_html__( 'Text', 'unicamp' ),
			'label_block' => true,
		] );

		$repeater->add_control( 'link', [
			'label'       => esc_html__( 'Link', 'unicamp' ),
			'type'        => Controls_Manager::URL,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'https://your-link.com', 'unicamp' ),
		] );

		$repeater->add_control( 'description', [
			'label'       => esc_html__( 'Description', 'unicamp' ),
			'type'        => Controls_Manager::TEXTAREA,
			'label_block' => true,
		] );

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'list_item_graphic_tab', [
			'label' => esc_html__( 'Graphic', 'unicamp' ),
		] );

		$repeater->add_control( 'graphic_element', [
			'label'       => esc_html__( 'Graphic Element', 'unicamp' ),
			'type'        => Controls_Manager::CHOOSE,
			'label_block' => false,
			'options'     => [
				'global' => [
					'title' => esc_html__( 'Use Global Setting', 'unicamp' ),
					'icon'  => 'eicon-global-settings',
				],
				'icon'   => [
					'title' => esc_html__( 'Icon', 'unicamp' ),
					'icon'  => 'eicon-star',
				],
				'image'  => [
					'title' => esc_html__( 'Image', 'unicamp' ),
					'icon'  => 'fa fa-picture-o',
				],
			],
			'default'     => 'global',
		] );

		$repeater->add_control( 'icon', [
			'label'     => esc_html__( 'Icon', 'unicamp' ),
			'type'      => Controls_Manager::ICONS,
			'condition' => [
				'graphic_element' => [
					'global',
					'icon',
				],
			],
		] );

		$repeater->add_control( 'image', [
			'label'     => esc_html__( 'Image', 'unicamp' ),
			'type'      => Controls_Manager::MEDIA,
			'condition' => [
				'graphic_element' => [
					'global',
					'image',
				],
			],
			'classes'   => 'unicamp-control-media-auto',
		] );

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'list_item_badge_tab', [
			'label' => esc_html__( 'Badge', 'unicamp' ),
		] );

		$repeater->add_control( 'badge', [
			'label' => esc_html__( 'Badge', 'unicamp' ),
			'type'  => Controls_Manager::TEXT,
		] );

		$repeater->add_control( 'item_badge_normal_hr', [
			'label'     => esc_html__( 'Normal', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$repeater->add_control( 'item_badge_color', [
			'label'     => esc_html__( 'Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .badge' => 'color: {{VALUE}};',
			],
		] );

		$repeater->add_control( 'item_badge_background', [
			'label'     => esc_html__( 'Background', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .badge' => 'background: {{VALUE}};',
			],
		] );

		$repeater->add_control( 'item_badge_hover_hr', [
			'label'     => esc_html__( 'Hover', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$repeater->add_control( 'item_hover_badge_color', [
			'label'     => esc_html__( 'Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .link:hover .badge' => 'color: {{VALUE}};',
			],
		] );

		$repeater->add_control( 'item_hover_badge_background', [
			'label'     => esc_html__( 'Background', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .link:hover .badge' => 'background: {{VALUE}};',
			],
		] );

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control( 'items', [
			'label'       => esc_html__( 'Items', 'unicamp' ),
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
			'separator'   => 'before',
			'title_field' => '{{{ elementor.helpers.renderIcon( this, icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} {{{ text }}}',
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
		if ( in_array( $settings['style'], [ 'bottom-line', 'bottom-line-02' ] ) ) {
			$this->add_render_attribute( '_wrapper', 'class', 'unicamp-list-group-style-bottom-line' );
		}

		$this->add_render_attribute( 'wrapper', 'class', 'unicamp-list' );

		$global_graphic_element = $settings['graphic_element'];
		$global_graphic_html    = '';

		switch ( $global_graphic_element ) {
			case 'icon':
				if ( ! empty ( $settings['icon']['value'] ) ) {
					$default_icon        = $this->get_render_icon( $settings, $settings['icon'], [ 'aria-hidden' => 'true' ], false, 'icon' );
					$global_graphic_html = '<div class="unicamp-icon icon"><div class="icon-view"></div>' . $default_icon . '</div>';
				}

				break;
			case 'image':
				if ( ! empty( $settings['image']['url'] ) ) {
					$default_img         = \Unicamp_Image::get_elementor_attachment( [
						'settings'  => $settings,
						'image_key' => 'image',
					] );
					$global_graphic_html = '<div class="unicamp-icon icon"><div class="icon-view"></div>' . $default_img . '</div>';
				}

				break;
		}
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php if ( $settings['items'] && count( $settings['items'] ) > 0 ) {
				foreach ( $settings['items'] as $key => $item ) {
					$item_key = 'item_' . $item['_id'];
					$this->add_render_attribute( $item_key, 'class', [
						'item',
						'elementor-repeater-item-' . $item['_id'],
					] );

					$link_tag = 'div';

					$item_link_key = 'item_link_' . $item['_id'];

					$this->add_render_attribute( $item_link_key, 'class', 'link' );

					if ( ! empty( $item['link']['url'] ) ) {
						$link_tag = 'a';
						$this->add_link_attributes( $item_link_key, $item['link'] );
					}

					$item_graphic_element = 'global' === $item['graphic_element'] ? $global_graphic_element : $item['graphic_element'];
					$item_graphic_html    = '';

					switch ( $item_graphic_element ) {
						case 'icon':
							if ( ! empty ( $item['icon']['value'] ) ) {
								$item_icon         = $this->get_render_icon( $settings, $item['icon'], [ 'aria-hidden' => 'true' ], false, 'icon' );
								$item_graphic_html = '<div class="unicamp-icon icon"><div class="icon-view"></div>' . $item_icon . '</div>';
							}
							break;

						case 'image':
							if ( ! empty( $item['image']['url'] ) ) {
								$item_img          = \Unicamp_Image::get_elementor_attachment( [
									'settings'  => $item,
									'image_key' => 'image',
								] );
								$item_graphic_html = '<div class="unicamp-icon icon"><div class="icon-view"></div>' . $item_img . '</div>';
							}
							break;
					}
					?>
					<div <?php $this->print_attributes_string( $item_key ); ?>>

						<?php printf( '<%1$s %2$s>', $link_tag, $this->get_render_attribute_string( $item_link_key ) ); ?>

						<div class="list-header">
							<?php echo ! empty( $item_graphic_html ) ? $item_graphic_html : $global_graphic_html; ?>
							<div class="text-wrap">
								<?php if ( ! empty( $item['text'] ) ) { ?>
									<div class="text"><?php echo wp_kses_post( $item['text'] ); ?></div>
								<?php } ?>
							</div>
							<?php if ( ! empty( $item['badge'] ) ) { ?>
								<div class="badge"><?php echo esc_html( $item['badge'] ); ?></div>
							<?php } ?>
						</div>

						<?php if ( ! empty( $item['description'] ) ) { ?>
							<div class="description"><?php echo esc_html( $item['description'] ); ?></div>
						<?php } ?>

						<?php printf( '</%1$s>', $link_tag ); ?>

					</div>
					<?php
				}
			}
			?>
		</div>
		<?php
	}

	protected function content_template() {
		// @formatter:off
		?>
		<#
		var globalGraphicElement = settings.graphic_element;
		var globalGraphicHtml = '';

		switch( globalGraphicElement ) {
			case 'icon':
				if ( '' !== settings.icon.value ) {
					var defaultIcon = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' );
					globalGraphicHtml = '<div class="unicamp-icon icon"><div class="icon-view"></div>' + defaultIcon.value + '</div>';
				}
				break;

			case 'image':
				if ( '' !== settings.image.url ) {
					var imageUrl = elementor.imagesManager.getImageUrl({
						id: settings.image.id,
						url: settings.image.url,
						model: view.getEditModel()
					});

					var defaultImage = '<img src="' + imageUrl + '" />';

					globalGraphicHtml = '<div class="unicamp-icon icon"><div class="icon-view"></div>' + defaultImage + '</div>';
				}
				break;
		}
		#>
		<div class="unicamp-list">
			<# _.each( settings.items, function( item, index ) { #>
				<#
				var item_key = 'item';
				view.addRenderAttribute( item_key, 'class', 'item' );
				view.addRenderAttribute( item_key, 'class', 'elementor-repeater-item-' + item._id );

				var item_link_key = 'item_link_' + item._id;
				var link_tag = 'div';
				view.addRenderAttribute( item_link_key, 'class', 'link' );

				if ( '' !== item.link.url ) {
					link_tag = 'a';

					view.addRenderAttribute( item_link_key, 'href', '#' );
				}

				var itemGraphicElement = 'global' === item.graphic_element ? globalGraphicElement : item.graphic_element;
				var itemGraphicHtml = '';

				switch( itemGraphicElement ) {
					case 'icon':
						if ( '' !== item.icon.value ) {
							var itemIcon = elementor.helpers.renderIcon( view, item.icon, { 'aria-hidden': true }, 'i' , 'object' );
							itemGraphicHtml = '<div class="unicamp-icon icon"><div class="icon-view"></div>' + itemIcon.value + '</div>';
						}
						break;

					case 'image':
						if ( '' !== item.image.url ) {
							var itemImageUrl = elementor.imagesManager.getImageUrl({
								id: item.image.id,
								url: item.image.url,
								model: view.getEditModel()
							});

							var itemImage = '<img src="' + itemImageUrl + '" />';

							itemGraphicHtml = '<div class="unicamp-icon icon"><div class="icon-view"></div>' + itemImage + '</div>';
						}
						break;
				}
				#>
				<div {{{ view.getRenderAttributeString( item_key ) }}}>

					<{{{ link_tag }}} {{{ view.getRenderAttributeString( item_link_key ) }}}>

					<div class="list-header">
						<# if ( '' !== itemGraphicHtml ) { #>
							{{{ itemGraphicHtml }}}
						<# } else { #>
							{{{ globalGraphicHtml }}}
						<# } #>

						<div class="text-wrap">
							<# if ( '' !== item.text ) { #>
								<span class="text">{{{ item.text }}}</span>
							<# } #>
						</div>

						<# if ( '' !== item.badge ) { #>
							<div class="badge">{{{ item.badge }}}</div>
						<# } #>
					</div>

					<# if ( '' !== item.description ) { #>
						<div class="description">{{{ item.description }}}</div>
					<# } #>

					</{{{ link_tag }}}>

				</div>
			<# }); #>
		</div>
		<?php
		// @formatter:off
	}
}
