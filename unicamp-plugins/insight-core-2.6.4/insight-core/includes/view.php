<?php

class InsightCore_View {
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

		add_action( 'wp_ajax_ic_set_views', array( $this, 'set_views' ) );
		add_action( 'wp_ajax_nopriv_ic_set_views', array( $this, 'set_views' ) );
	}

	static function enqueue() {
		global $post;
		$ic_view_support = get_theme_support( 'insight-view' );
		if ( is_array( $ic_view_support ) && ( count( $ic_view_support ) > 0 ) ) {
			$ic_view_posttypes = $ic_view_support[0];
		} else {
			$ic_view_posttypes = array( 'post' );
		}
		if ( ! wp_is_post_revision( $post ) && is_singular( $ic_view_posttypes ) ) {
			wp_enqueue_script( 'insight-view', INSIGHT_CORE_PATH . 'assets/js/insight-view.js', array( 'jquery' ), INSIGHT_CORE_THEME_VERSION, true );
			wp_localize_script( 'insight-view', 'ic_vars', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'ic_nonce' => wp_create_nonce( 'ic_view' ),
				'post_id'  => (int) $post->ID
			) );
		}
	}

	static function set_views() {
		if ( ! isset( $_POST['ic_nonce'] ) || ! wp_verify_nonce( $_POST['ic_nonce'], 'ic_view' ) ) {
			die( 'Permissions check failed!' );
		}
		$post_id = (int) $_POST['post_id'];
		$count   = (int) get_post_meta( $post_id, '_ic_view', true );
		$count ++;
		update_post_meta( $post_id, '_ic_view', $count );

		echo $count;
		wp_die();
	}

	public static function get_views( $post_id = null ) {
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		return (int) get_post_meta( $post_id, '_ic_view', true );
	}
}

new InsightCore_View();