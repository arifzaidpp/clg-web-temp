<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || exit;

class Widget_Banner extends Base {

	public function get_name() {
		return 'tm-banner';
	}

	public function get_title() {
		return esc_html__( 'Banner', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-image-rollover';
	}

	public function get_keywords() {
		return [ 'banner' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_box_style_section();

		$this->add_content_style_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Content', 'unicamp' ),
		] );

		$this->add_control( 'style', [
			'label'   => esc_html__( 'Style', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'01' => '01',
			],
			'default' => '01',
		] );

		$this->add_control( 'hover_effect', [
			'label'        => esc_html__( 'Hover Effect', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''         => esc_html__( 'None', 'unicamp' ),
				'zoom-in'  => esc_html__( 'Zoom In', 'unicamp' ),
				'zoom-out' => esc_html__( 'Zoom Out', 'unicamp' ),
				'move-up'  => esc_html__( 'Move Up', 'unicamp' ),
			],
			'default'      => '',
			'prefix_class' => 'unicamp-animation-',
		] );

		$this->add_control( 'image', [
			'label'   => esc_html__( 'Choose Image', 'unicamp' ),
			'type'    => Controls_Manager::MEDIA,
			'dynamic' => [
				'active' => true,
			],
			'default' => [
				'url' => Utils::get_placeholder_image_src(),
			],
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'image',
			// Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
			'default'   => 'full',
			'separator' => 'none',
		] );

		$this->add_control( 'title_text', [
			'label'       => esc_html__( 'Title', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => esc_html__( 'This is the heading', 'unicamp' ),
			'placeholder' => esc_html__( 'Enter your title', 'unicamp' ),
			'label_block' => true,
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

		$this->add_control( 'title_size', [
			'label'   => esc_html__( 'Title HTML Tag', 'unicamp' ),
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

		$this->add_control( 'view', [
			'label'   => esc_html__( 'View', 'unicamp' ),
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'traditional',
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

		$this->end_controls_section();
	}

	private function add_content_style_section() {
		$this->start_controls_section( 'content_style_section', [
			'label' => esc_html__( 'Content', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'heading_title', [
			'label'     => esc_html__( 'Title', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'selector' => '{{WRAPPER}} .title',
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'unicamp-banner unicamp-box' );
		$this->add_render_attribute( 'wrapper', 'class', 'style-' . $settings['style'] );

		$box_tag = 'div';
		if ( ! empty( $settings['link']['url'] ) ) {
			$box_tag = 'a';
			$this->add_link_attributes( 'wrapper', $settings['link'] );
			$this->add_render_attribute( 'wrapper', 'class', 'link-secret' );
		}
		?>
		<?php printf( '<%1$s %2$s>', $box_tag, $this->get_render_attribute_string( 'wrapper' ) ); ?>
		<div class="content-wrap">

			<?php if ( ! empty( $settings['image']['url'] ) ) : ?>
				<div class="unicamp-image image">
					<?php echo \Unicamp_Image::get_elementor_attachment( [
						'settings' => $settings,
					] ); ?>
				</div>
			<?php endif; ?>

			<div class="content">
				<?php $this->print_title( $settings ); ?>
			</div>

		</div>
		<?php printf( '</%1$s>', $box_tag ); ?>
		<?php
	}

	protected function content_template() {
		// @formatter:off
		?>
		<#
		view.addRenderAttribute( 'wrapper', 'class', 'unicamp-banner unicamp-box' );
		view.addRenderAttribute( 'wrapper', 'class', 'style-' + settings.style );

		var boxTag = 'div';

		if( settings.link.url ) {
			boxTag = 'a';

			view.addRenderAttribute( 'wrapper', 'href', '#' );
		}

		var imageHTML = '';

		if ( settings.image.url ) {
			var image = {
				id: settings.image.id,
				url: settings.image.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var image_url = elementor.imagesManager.getImageUrl( image );
			view.addRenderAttribute( 'image', 'src', image_url );
			imageHTML = '<div class="unicamp-image image"><img ' + view.getRenderAttributeString( 'image' ) + ' /></div>';
		}
		#>
		<{{{ boxTag }}} {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
			<div class="content-wrap">
				{{{ imageHTML }}}

				<div class="content">
					<# if ( settings.title_text ) { #>
						<#
						view.addRenderAttribute( 'title_text', 'class', 'title' );
						view.addInlineEditingAttributes( 'title_text', 'none' );
						#>
						<{{{ settings.title_size }}} {{{ view.getRenderAttributeString( 'title_text' ) }}}>{{{ settings.title_text }}}</{{{ settings.title_size }}}>
					<# } #>
				</div>
			</div>
		</{{{ boxTag }}}>
		<?php
		// @formatter:off
	}

	private function print_title(array $settings) {
		if( empty( $settings['title_text'] ) ) {
			return;
		}

		$this->add_render_attribute( 'title_text', 'class', 'title' );

		$this->add_inline_editing_attributes( 'title_text', 'none' );

		$title_html = $settings['title_text'];

		printf( '<%1$s %2$s>%3$s</%1$s>', $settings['title_size'], $this->get_render_attribute_string( 'title_text' ), $title_html );
	}
}
