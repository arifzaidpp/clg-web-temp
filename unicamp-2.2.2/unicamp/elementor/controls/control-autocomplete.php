<?php

namespace Unicamp_Elementor;

use Elementor\Control_Select2;

defined( 'ABSPATH' ) || exit;

/**
 * Elementor autocomplete control.
 *
 * A base control for creating autocomplete control. Displays a select box control
 * based on select2 jQuery plugin @see https://select2.github.io/ .
 * It accepts an array in which the `key` is the value and the `value` is the
 * option name. Set `multiple` to `true` to allow multiple value selection.
 *
 * @since 1.0.0
 */
class Control_Autocomplete extends Control_Select2 {

	public function get_type() {
		return 'autocomplete';
	}

	/**
	 * 'query' can be used for passing query args in the structure and format used by WP_Query.
	 * @return array
	 */
	protected function get_default_settings() {
		return array_merge(
			parent::get_default_settings(), [
				'query' => '',
			]
		);
	}

	public function enqueue() {
		wp_register_script( 'autocomplete-control', UNICAMP_ELEMENTOR_URI . '/assets/js/controls/autocomplete.js', [ 'jquery' ], '1.0.0' );
		wp_enqueue_script( 'autocomplete-control' );
	}
}
