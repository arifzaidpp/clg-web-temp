<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Tutor_Custom_Css' ) ) {
	class Unicamp_Tutor_Custom_Css {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'unicamp_customize_output_button_typography_selectors', [
				$this,
				'customize_output_button_typography_selectors',
			] );

			add_filter( 'unicamp_customize_output_button_selectors', [
				$this,
				'customize_output_button_selectors',
			] );

			add_filter( 'unicamp_customize_output_button_hover_selectors', [
				$this,
				'customize_output_button_hover_selectors',
			] );
		}

		public function customize_output_button_typography_selectors( $selectors ) {
			$new_selectors = [ '.single_add_to_cart_button, a.tutor-button, .tutor-button, a.tutor-btn, .tutor-btn' ];

			$final_selectors = array_merge( $selectors, $new_selectors );

			return $final_selectors;
		}

		public function customize_output_button_selectors( $selectors ) {
			$new_selectors = [ '.single_add_to_cart_button, a.tutor-button, .tutor-button, a.tutor-btn, .tutor-btn, .tutor-button.tutor-success' ];

			$final_selectors = array_merge( $selectors, $new_selectors );

			return $final_selectors;
		}

		public function customize_output_button_hover_selectors( $selectors ) {
			$new_selectors = [ '.single_add_to_cart_button:hover, a.tutor-button:hover, .tutor-button:hover, a.tutor-btn:hover, .tutor-btn:hover, .tutor-button.tutor-success:hover' ];

			$final_selectors = array_merge( $selectors, $new_selectors );

			return $final_selectors;
		}
	}
}
