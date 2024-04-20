<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Modify_Widget_Testimonial extends Modify_Base {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function initialize() {
		add_action( 'elementor/element/testimonial/section_style_testimonial_content/before_section_end', [
			$this,
			'add_content_style_settings',
		] );

		add_action( 'elementor/element/testimonial/section_style_testimonial_image/before_section_end', [
			$this,
			'add_image_style_settings',
		] );

		add_action( 'elementor/element/testimonial/section_style_testimonial_job/before_section_end', [
			$this,
			'add_job_style_settings',
		] );
	}

	/**
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function add_content_style_settings( $element ) {
		$element->add_responsive_control( 'content_margin', [
			'label'      => esc_html__( 'Margin', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .elementor-testimonial-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
	}

	/**
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function add_image_style_settings( $element ) {
		$element->add_responsive_control( 'image_spacing', [
			'label'     => esc_html__( 'Spacing', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .elementor-testimonial-meta.elementor-testimonial-image-position-aside .elementor-testimonial-image' => 'padding-right: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .elementor-testimonial-meta.elementor-testimonial-image-position-top .elementor-testimonial-image'   => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		] );
	}

	/**
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function add_job_style_settings( $element ) {
		$element->add_responsive_control( 'job_margin', [
			'label'      => esc_html__( 'Margin', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .elementor-testimonial-job' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
	}
}

Modify_Widget_Testimonial::instance()->initialize();
