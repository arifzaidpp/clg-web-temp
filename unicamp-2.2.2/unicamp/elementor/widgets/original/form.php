<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Modify_Widget_Form extends Modify_Base {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function initialize() {
		add_action( 'elementor/element/form/section_form_fields/after_section_end', [
			$this,
			'section_form_fields',
		] );

		add_action( 'elementor/element/form/section_buttons/after_section_end', [
			$this,
			'section_buttons',
		] );

		add_action( 'elementor/element/form/section_field_style/before_section_end', [
			$this,
			'section_field_style',
		] );

		add_action( 'elementor/element/form/section_button_style/before_section_end', [
			$this,
			'section_button_style',
		] );

		add_action( 'elementor-pro/forms/pre_render', [ $this, 'pre_render_form' ], 10, 2 );
	}

	/**
	 * @param array                  $settings
	 * @param \Elementor\Widget_Base $form
	 */
	function pre_render_form( $settings, $form ) {
		if ( ! empty( $settings['button_css_class'] ) ) {
			$form->add_render_attribute( 'button', 'class', $settings['button_css_class'] );
		}
	}

	/**
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function section_form_fields( $element ) {
		/**
		 * Add custom width option for form fields.
		 */
		// Get control form field repeater.
		$form_fields = $element->get_controls( 'form_fields' );

		// Get form field list.
		$fields = $form_fields['fields'];

		// Get old width options.
		$old_option = $fields['width']['options'];

		// Append new options.
		$new_options = $old_option + [ 'fit' => esc_html__( 'Auto Fit', 'unicamp' ) ];

		// Assign for control width.
		$fields['width']['options'] = $new_options;

		// Finally update all changed.
		$element->update_control( 'form_fields', [
			'fields'      => $fields,
			// Better repeater item label.
			'title_field' => '<# if( field_label ) { #>{{{ field_label }}}<# } else { #>{{{ placeholder }}}<# } #>',
		] );
	}

	/**
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function section_buttons( $element ) {
		$element->start_injection( [
			'type' => 'control',
			'at'   => 'after',
			'of'   => 'button_icon_indent',
		] );

		$element->add_responsive_control( 'button_icon_top_spacing', [
			'label'     => esc_html__( 'Icon Top Spacing', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 50,
				],
			],
			'condition' => [
				'selected_button_icon[value]!' => '',
			],
			'selectors' => [
				'{{WRAPPER}} .elementor-button-icon' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$element->add_responsive_control( 'button_icon_size', [
			'label'     => esc_html__( 'Icon Size', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 8,
					'max' => 30,
				],
			],
			'condition' => [
				'selected_button_icon[value]!' => '',
			],
			'selectors' => [
				'{{WRAPPER}} .elementor-button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$element->end_injection();


		/**
		 * Add custom width option for button submit.
		 */
		$button_width         = $element->get_controls( 'button_width' );
		$button_width_options = $button_width['options'];
		$button_width_options += [ 'custom' => esc_html__( 'Custom', 'edumall' ) ];
		$element->update_responsive_control( 'button_width', [
			'options' => $button_width_options,
		] );

		// Add custom width control for button submit.
		$element->start_injection( [
			'type' => 'control',
			'at'   => 'before',
			'of'   => 'button_align',
		] );

		$element->add_responsive_control( 'button_custom_width', [
			'label'      => esc_html__( 'Custom Width', 'unicamp' ),
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
			'condition'  => [
				'button_width' => 'custom',
			],
			'selectors'   => [
				'{{WRAPPER}} .elementor-field-type-submit' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$element->end_injection();

		// Add custom class control for button submit.
		$element->start_injection( [
			'type' => 'control',
			'at'   => 'after',
			'of'   => 'button_css_id',
		] );

		$element->add_control(
			'button_css_class',
			[
				'label'       => __( 'Button Class', 'unicamp' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => false,
			]
		);

		$element->end_injection();
	}

	/**
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function section_button_style( $element ) {
		$element->add_responsive_control( 'submit_button_spacing', [
			'label'      => esc_html__( 'Margin', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .elementor-field-type-submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		/**
		 * Use responsive controls.
		 */
		$element->remove_control( 'button_border_radius' );

		$element->add_responsive_control( 'button_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
	}

	/**
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function section_field_style( $element ) {
		$element->add_responsive_control( 'field_padding', [
			'label'      => esc_html__( 'Padding', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .elementor-field-textual' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$element->start_injection( [
			'type' => 'control',
			'at'   => 'after',
			'of'   => 'field_border_color',
		] );

		$element->add_control( 'field_border_focus_color', [
			'label'     => __( 'Border Focus Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper):focus' => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select:focus'                                                  => 'border-color: {{VALUE}};',
			],
		] );

		$element->end_injection();

		/**
		 * Field border width control
		 * Use responsive controls instead of.
		 */
		$element->remove_control( 'field_border_width' );

		$element->add_responsive_control( 'field_border_width', [
			'label'       => esc_html__( 'Border Width', 'unicamp' ),
			'type'        => Controls_Manager::DIMENSIONS,
			'placeholder' => '1',
			'size_units'  => [ 'px' ],
			'selectors'   => [
				'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select'                                                  => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		/**
		 * Field border radius control
		 * Use responsive controls instead of.
		 */
		$element->remove_control( 'field_border_radius' );

		$element->add_responsive_control( 'field_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select'                                                  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
	}
}

Modify_Widget_Form::instance()->initialize();
