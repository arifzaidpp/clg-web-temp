<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

defined( 'ABSPATH' ) || exit;

class Widget_Rich_Carousel extends Carousel_Base {

	public function get_name() {
		return 'tm-rich-carousel';
	}

	public function get_title() {
		return esc_html__( 'Rich Carousel', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-posts-carousel';
	}

	public function get_keywords() {
		return [ 'rich', 'carousel' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_style_section();

		parent::register_controls();

		$this->update_controls();
	}

	private function update_controls() {
		$this->update_responsive_control( 'swiper_items', [
			'default'        => '3',
			'tablet_default' => '2',
			'mobile_default' => '1',
		] );

		$this->update_responsive_control( 'swiper_gutter', [
			'default' => 30,
		] );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'unicamp-rich-carousel' );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php $this->print_slider( $settings ); ?>
		</div>
		<?php
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Content', 'unicamp' ),
		] );

		$this->add_control( 'style', [
			'label'        => esc_html__( 'Style', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => array(
				'01' => '01',
			),
			'default'      => '01',
			'prefix_class' => 'unicamp-rich-carousel-style-',
			'render_type'  => 'template',
		] );

		$this->add_control( 'hover_effect', [
			'label'        => esc_html__( 'Hover Effect', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''         => esc_html__( 'None', 'unicamp' ),
				'zoom-in'  => esc_html__( 'Zoom In', 'unicamp' ),
				'zoom-out' => esc_html__( 'Zoom Out', 'unicamp' ),
			],
			'default'      => '',
			'prefix_class' => 'unicamp-animation-',
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'label'     => esc_html__( 'Image Size', 'unicamp' ),
			'name'      => 'image',
			'default'   => 'full',
			'separator' => 'before',
		] );

		$this->add_control( 'link_click', [
			'label'   => esc_html__( 'Apply Link On', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'box'    => esc_html__( 'Whole Slide', 'unicamp' ),
				'button' => esc_html__( 'Button Only', 'unicamp' ),
			],
			'default' => 'box',
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'image', [
			'label'   => esc_html__( 'Image', 'unicamp' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [
				'url' => Utils::get_placeholder_image_src(),
			],
		] );

		$repeater->add_control( 'title', [
			'label'       => esc_html__( 'Title', 'unicamp' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'Enter your title', 'unicamp' ),
			'default'     => esc_html__( 'Add Your Heading Text Here', 'unicamp' ),
		] );

		$repeater->add_control( 'description', [
			'label' => esc_html__( 'Description', 'unicamp' ),
			'type'  => Controls_Manager::TEXTAREA,
		] );

		$repeater->add_control( 'button_text', [
			'label' => esc_html__( 'Button Text', 'unicamp' ),
			'type'  => Controls_Manager::TEXT,
		] );

		$repeater->add_control( 'link', [
			'label'       => esc_html__( 'Link', 'unicamp' ),
			'type'        => Controls_Manager::URL,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'https://your-link.com', 'unicamp' ),
		] );

		$this->add_control( 'slides', [
			'label'       => esc_html__( 'Slides', 'unicamp' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[
					'title'       => 'Automatic Updates',
					'description' => 'Lorem ipsum dolor sit amet, consect etur elit. Suspe ndisse suscipit',
				],
				[
					'title'       => 'Flexible Options',
					'description' => 'Lorem ipsum dolor sit amet, consect etur elit. Suspe ndisse suscipit',
				],
				[
					'title'       => 'Lifetime Use',
					'description' => 'Lorem ipsum dolor sit amet, consect etur elit. Suspe ndisse suscipit',
				],
			],
			'title_field' => '{{{ title }}}',
		] );

		$this->end_controls_section();
	}

	private function add_style_section() {
		$this->start_controls_section( 'style_section', [
			'label' => esc_html__( 'Style', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'slide_wrapper_heading', [
			'label' => esc_html__( 'Wrapper', 'unicamp' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_responsive_control( 'text_align', [
			'label'                => esc_html__( 'Text Align', 'unicamp' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .slide-content' => 'text-align: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'slide_wrapper_padding', [
			'label'      => esc_html__( 'Padding', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .slide-layers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .unicamp-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'image_style_heading', [
			'label'     => esc_html__( 'Image', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'image_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .slide-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'images_shadow',
			'selector' => '{{WRAPPER}} .slide-image img',
		] );

		$this->add_control( 'title_style_heading', [
			'label'     => esc_html__( 'Title', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'title_margin', [
			'label'      => esc_html__( 'Margin', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => esc_html__( 'Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .title',
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'title_hover_color', [
			'label'     => esc_html__( 'Hover Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .title:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'description_style_heading', [
			'label'     => esc_html__( 'Description', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'description_typography',
			'label'    => esc_html__( 'Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .description',
		] );

		$this->add_control( 'description_color', [
			'label'     => esc_html__( 'Text Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .description' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function print_slides( array $settings ) {

		foreach ( $settings['slides'] as $slide ) :
			$slide_id = $slide['_id'];
			$item_key = 'item_' . $slide_id;
			$box_key = 'box_' . $slide_id;
			$box_tag = 'div';

			$this->add_render_attribute( $item_key, 'class', [
				'swiper-slide',
				'elementor-repeater-item-' . $slide_id,
			] );

			$this->add_render_attribute( $box_key, 'class', 'unicamp-box slide-wrapper' );

			if ( ! empty( $slide['link']['url'] ) && 'box' === $settings['link_click'] ) {
				$box_tag = 'a';
				$this->add_render_attribute( $box_key, 'class', 'link-secret' );
				$this->add_link_attributes( $box_key, $slide['link'] );
			}
			?>
			<div <?php $this->print_attributes_string( $item_key ); ?>>
				<?php printf( '<%1$s %2$s>', $box_tag, $this->get_render_attribute_string( $box_key ) ); ?>
				<div class="slide-image unicamp-image">
					<?php
					echo \Unicamp_Image::get_elementor_attachment( [
						'settings'      => $slide,
						'size_settings' => $settings,
					] );
					?>
				</div>
				<?php if ( ! empty( $slide['title'] ) || ! empty( $slide['description'] ) || ! empty( $slide['button_text'] ) ) : ?>
					<div class="slide-content">
						<div class="slide-layers">
							<?php if ( ! empty( $slide['title'] ) ) : ?>
								<div class="slide-layer-wrap title-wrap">
									<div class="slide-layer">
										<h3 class="title"><?php echo wp_kses( $slide['title'], 'unicamp-default' ); ?></h3>
									</div>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $slide['description'] ) || ! empty( $slide['button_text'] ) ) : ?>
								<div class="slide-caption">
									<?php if ( ! empty( $slide['description'] ) ) : ?>
										<div class="slide-layer-wrap description-wrap">
											<div class="slide-layer">
												<div
													class="description"><?php echo esc_html( $slide['description'] ); ?></div>
											</div>
										</div>
									<?php endif; ?>

									<?php if ( ! empty( $slide['button_text'] ) ) : ?>
										<div class="slide-layer-wrap button-wrap">
											<div class="slide-layer">
												<?php
												$button_args = [
													'style'      => 'text',
													'text'       => $slide['button_text'],
													'icon'       => 'fas fa-long-arrow-right',
													'icon_align' => 'right',
												];

												if ( ! empty( $slide['link']['url'] ) && 'button' === $settings['link_click'] ) {
													$button_args['link'] = [
														'url' => $slide['link']['url'],
													];
												}

												\Unicamp_Templates::render_button( $button_args );
												?>
											</div>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
				<?php printf( '</%1$s>', $box_tag ); ?>
			</div>
		<?php endforeach;
	}
}
