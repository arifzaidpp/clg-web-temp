<?php

namespace Unicamp_Elementor;

use Elementor\Group_Control_Base;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Elementor tooltip control.
 *
 * A base control for creating tooltip control.
 *
 * @since 1.0.0
 */
class Group_Control_Tooltip extends Group_Control_Base {

	protected static $fields;

	public static function get_type() {
		return 'tooltip';
	}

	protected function init_fields() {
		$fields = [];

		$fields['skin'] = [
			'label'   => esc_html__( 'Tooltip Skin', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				''        => esc_html__( 'Black', 'unicamp' ),
				'white'   => esc_html__( 'White', 'unicamp' ),
				'primary' => esc_html__( 'Primary', 'unicamp' ),
			],
			'default' => '',
		];

		$fields['position'] = [
			'label'   => esc_html__( 'Tooltip Position', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'top'          => esc_html__( 'Top', 'unicamp' ),
				'right'        => esc_html__( 'Right', 'unicamp' ),
				'bottom'       => esc_html__( 'Bottom', 'unicamp' ),
				'left'         => esc_html__( 'Left', 'unicamp' ),
				'top-left'     => esc_html__( 'Top Left', 'unicamp' ),
				'top-right'    => esc_html__( 'Top Right', 'unicamp' ),
				'bottom-left'  => esc_html__( 'Bottom Left', 'unicamp' ),
				'bottom-right' => esc_html__( 'Bottom Right', 'unicamp' ),
			],
			'default' => 'top',
		];

		return $fields;
	}

	protected function get_default_options() {
		return [
			'popover' => [
				'starter_title' => _x( 'Tooltip', 'Tooltip Control', 'unicamp' ),
				'starter_name'  => 'enable',
				'starter_value' => 'yes',
				'settings'      => [
					'render_type' => 'template',
				],
			],
		];
	}
}
