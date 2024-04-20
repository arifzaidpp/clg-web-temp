<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;

defined( 'ABSPATH' ) || exit;

class Widget_Image_Carousel extends Carousel_Base {

	public function get_name() {
		return 'tm-image-carousel';
	}

	public function get_title() {
		return esc_html__( 'Image Carousel', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-slider-push';
	}

	public function get_keywords() {
		return [ 'image', 'photo', 'visual', 'carousel', 'slider' ];
	}

	protected function register_controls() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Content', 'unicamp' ),
		] );

		$this->add_control( 'gallery', [
			'label'      => esc_html__( 'Add Images', 'unicamp' ),
			'type'       => Controls_Manager::GALLERY,
			'show_label' => false,
			'dynamic'    => [
				'active' => true,
			],
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'thumbnail',
			'separator' => 'none',
		] );

		$this->end_controls_section();

		parent::register_controls();

		$this->add_image_style_section();

		$this->update_controls();
	}

	private function update_controls() {
		$this->update_responsive_control( 'swiper_items', [
			'default'        => '1',
			'tablet_default' => '1',
			'mobile_default' => '1',
		] );
	}

	protected function print_slides( array $settings ) {
		if ( empty( $settings['gallery'] ) ) {
			return;
		}

		$image_size = \Unicamp_Image::elementor_parse_image_size( $settings, '1170x670' );

		foreach ( $settings['gallery'] as $image ) {

			?>
			<div class="swiper-slide">
				<div class="image">
					<?php \Unicamp_Image::the_attachment_by_id( array(
						'id'   => $image['id'],
						'size' => $image_size,
					) ); ?>
				</div>
			</div>
			<?php
		}
	}

	private function add_image_style_section() {
		$this->start_controls_section( 'images_style_section', [
			'label' => esc_html__( 'Images', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'image_height', [
			'label'       => esc_html__( 'Height', 'unicamp' ),
			'description' => esc_html__( 'Controls the height of images. Use for slider per view auto.', 'unicamp' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ '%', 'px' ],
			'range'       => [
				'%'  => [
					'min' => 5,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1000,
				],
			],
			'selectors'   => [
				'{{WRAPPER}} .image img' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->start_controls_tabs( 'images_effects' );

		$this->start_controls_tab( 'images_effects_normal_tab', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'images_border',
			'selector' => '{{WRAPPER}} .image img',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'images_shadow',
			'selector' => '{{WRAPPER}} .image img',
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'css_filters',
			'selector' => '{{WRAPPER}} .image img',
		] );

		$this->add_control( 'images_opacity', [
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
				'{{WRAPPER}} .image img' => 'opacity: {{SIZE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'images_effects_hover_tab', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'images_border_hover',
			'selector' => '{{WRAPPER}} .image:hover img',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'images_shadow_hover',
			'selector' => '{{WRAPPER}} .image:hover img',
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'css_filters_hover',
			'selector' => '{{WRAPPER}} .image:hover img',
		] );

		$this->add_control( 'images_opacity_hover', [
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
				'{{WRAPPER}} .image:hover img' => 'opacity: {{SIZE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}
}
