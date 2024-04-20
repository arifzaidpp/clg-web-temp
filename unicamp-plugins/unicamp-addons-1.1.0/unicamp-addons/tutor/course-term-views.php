<?php

namespace Unicamp_Addons\Tutor;

defined( 'ABSPATH' ) || exit;

class Course_Term_Views {

	protected static $instance = null;

	const TERM_META_KEY = 'views';

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_enqueue' ] );

		add_action( 'wp_ajax_unicamp_course_term_set_views', [ $this, 'set_views' ] );
		add_action( 'wp_ajax_nopriv_unicamp_course_term_set_views', [ $this, 'set_views' ] );
	}

	public function frontend_enqueue() {
		if ( is_tax( 'course-tag' ) ) {
			$queried_object = get_queried_object();
			$term_id        = $queried_object->term_id;

			wp_enqueue_script( 'unicamp-term-view', UNICAMP_ADDONS_ASSETS_URI . '/js/terms-view.js', array( 'jquery' ), null, true );
			wp_localize_script( 'unicamp-term-view', 'unicampAddons', [
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'term_view' ),
				'term_id'  => (int) $term_id,
			] );
		}

	}

	public function set_views() {
		if ( ! isset( $_POST['unicamp_nonce'] ) || ! wp_verify_nonce( $_POST['unicamp_nonce'], 'term_view' ) ) {
			die( 'Permissions check failed!' );
		}

		$term_id = (int) $_POST['term_id'];
		$count   = (int) get_term_meta( $term_id, self::TERM_META_KEY, true );
		$count++;
		update_term_meta( $term_id, self::TERM_META_KEY, $count );

		echo $count;
		wp_die();
	}

	public function get_views( $term_id = null ) {
		if ( isset( $term_id ) ) {
			return (int) get_term_meta( $term_id, self::TERM_META_KEY, true );
		}

		if ( is_tax( 'course-tag' ) ) {
			$queried_object = get_queried_object();

			return (int) get_term_meta( $queried_object->term_id, self::TERM_META_KEY, true );
		}

		return 0;
	}
}

Course_Term_Views::instance()->initialize();
