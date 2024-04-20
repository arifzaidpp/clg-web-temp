<?php

namespace Unicamp\Woo;

defined( 'ABSPATH' ) || exit;

class Quick_View {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'wp_ajax_product_quick_view', [ $this, 'get_quick_view_content' ] );
		add_action( 'wp_ajax_nopriv_product_quick_view', [ $this, 'get_quick_view_content' ] );
	}

	public function get_quick_view_content() {
		$productId = $_REQUEST['pid'];

		$post_object = get_post( $productId );

		setup_postdata( $GLOBALS['post'] =& $post_object );

		ob_start();
		wc_get_template_part( 'content', 'quick-view' );
		$template = ob_get_contents();
		ob_clean();

		$response['template'] = $template;

		echo json_encode( $response );

		wp_die();
	}
}

Quick_View::instance()->initialize();
