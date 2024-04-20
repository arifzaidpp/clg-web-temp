<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Widgets Class
 *
 * @package Core
 */
class Insight_Register_Widgets {

	public $widgets;

	/**
	 * The constructor.
	 */
	public function __construct() {

		// Do register
		add_action( 'widgets_init', array( $this, 'register_widgets' ), 10 );
	}

	/**
	 * The Register widgets.
	 */
	public function register_widgets() {
		$this->widgets = apply_filters( 'insight_widgets', array() );

		if ( empty( $this->widgets ) ) {
			return;
		}

		foreach ( $this->widgets as $widget ) {
			if ( class_exists( $widget ) ) {
				register_widget( $widget );
			}
		}

	}

}

new Insight_Register_Widgets();
