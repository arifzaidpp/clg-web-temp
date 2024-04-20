<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Posttypes Class
 *
 * @package Core
 */
class Insight_Register_Posttypes {

	public $posttypes;

	/**
	 * The constructor.
	 */
	public function __construct() {

		// Do register
		add_action( 'init', array( $this, 'register_posttypes' ), 10 );
	}

	/**
	 * The Register posttypes.
	 */
	public function register_posttypes() {
		$this->posttypes = apply_filters( 'insight_posttypes', array() );

		if ( empty( $this->posttypes ) ) {
			return;
		}

		foreach ( $this->posttypes as $slug => $posttype ) {
			if ( ! empty( $slug ) ) {
				register_post_type( $slug, $posttype );
			}
		}

	}

}

new Insight_Register_Posttypes();


/**
 * Insight_Register_Taxonomy Class
 *
 * @package Core
 */
class Insight_Register_Taxonomy {

	public $taxonomy;

	/**
	 * The constructor.
	 */
	public function __construct() {

		// Do register
		add_action( 'init', array( $this, 'register_taxonomy' ) );
	}

	/**
	 * The Register taxonomy.
	 */
	public function register_taxonomy() {
		$this->taxonomy = apply_filters( 'insight_taxonomy', array() );

		if ( empty( $this->taxonomy ) ) {
			return;
		}

		foreach ( $this->taxonomy as $slug => $taxonomy ) {
			if ( ! empty( $slug ) && count( $taxonomy ) == 2 ) {
				register_taxonomy( $slug, $taxonomy[0], $taxonomy[1] );
			}
		}

	}

}

new Insight_Register_Taxonomy();
