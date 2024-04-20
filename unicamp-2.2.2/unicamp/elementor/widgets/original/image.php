<?php

namespace Unicamp_Elementor;

defined( 'ABSPATH' ) || exit;

class Modify_Widget_Image extends Modify_Base {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function initialize() {
		add_action( 'elementor/element/image/section_image/after_section_end', [
			$this,
			'update_image',
		] );
	}

	/**
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function update_image( $element ) {
		/**
		 * Better output for RTL
		 */
		$element->update_responsive_control( 'align', [
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );
	}
}

Modify_Widget_Image::instance()->initialize();
