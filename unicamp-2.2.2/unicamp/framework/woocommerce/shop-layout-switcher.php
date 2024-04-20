<?php

namespace Unicamp\Woo;

defined( 'ABSPATH' ) || exit;

class Layout_Switcher {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'woocommerce_before_shop_loop', [ $this, 'add_switcher_button' ], 30 );

		add_action( 'wp_ajax_shop_layout_change', [ $this, 'do_switch_layout' ] );
		add_action( 'wp_ajax_nopriv_shop_layout_change', [ $this, 'do_switch_layout' ] );
	}

	public function add_switcher_button() {
		if ( '1' !== \Unicamp::setting( 'shop_archive_layout_switcher' ) ) {
			return;
		}

		$layout = \Unicamp_Woo::instance()->get_shop_layout();
		?>
		<form id="archive-layout-switcher" class="archive-layout-switcher">
			<?php
			$switcher_grid_classes = 'switcher-item grid';
			$switcher_list_classes = 'switcher-item list';

			if ( 'grid' === $layout ) {
				$switcher_grid_classes .= ' selected';
			} else {
				$switcher_list_classes .= ' selected';
			}

			$id = uniqid( 'switcher-' );
			?>
			<div class="archive-layout-switcher-label"><?php esc_html_e( 'See', 'unicamp' ); ?></div>
			<label class="<?php echo esc_attr( $switcher_grid_classes ); ?>"
			       for="<?php echo esc_attr( $id . '-grid' ); ?>">
				<input type="radio" name="layout" value="grid" <?php checked( $layout, 'grid' ); ?>
				       id="<?php echo esc_attr( $id . '-grid' ); ?>">
			</label>
			<label class="<?php echo esc_attr( $switcher_list_classes ); ?>"
			       for="<?php echo esc_attr( $id . '-list' ); ?>">
				<input type="radio" name="layout" value="list" <?php checked( $layout, 'list' ); ?>
				       id="<?php echo esc_attr( $id . '-list' ); ?>">
			</label>
			<input type="hidden" name="action" value="shop_layout_change">
		</form>
		<?php
	}

	public function do_switch_layout() {
		$layout = 'grid';

		if ( isset( $_POST['layout'] ) && 'list' === $_POST['layout'] ) {
			$layout = 'list';
		}

		setcookie( 'shop_layout', $layout, time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );

		echo wp_json_encode( [
			'success' => 1,
		] );

		wp_die();
	}
}

Layout_Switcher::instance()->initialize();
