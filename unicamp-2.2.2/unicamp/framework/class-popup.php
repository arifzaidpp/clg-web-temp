<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Popup' ) ) {

	class Unicamp_Popup {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_footer', [ $this, 'add_popup_pre_loader' ] );

			add_action( 'wp_ajax_unicamp_lazy_load_template', [ $this, 'ajax_load_template_part' ] );
			add_action( 'wp_ajax_nopriv_unicamp_lazy_load_template', [ $this, 'ajax_load_template_part' ] );
		}

		public function add_popup_pre_loader() {
			?>
			<div id="popup-pre-loader" class="popup-pre-loader">
				<div class="popup-load-inner">
					<div class="popup-loader-wrap">
						<div class="wrap-2">
							<div class="inner">
								<?php unicamp_load_template( 'preloader/style', 'circle' ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		public function ajax_load_template_part() {
			$template = $_REQUEST['template'];

			if ( ! isset( $template ) || '' === $template ) {
				wp_die();
			}

			ob_start();

			get_template_part( $template );

			$html = ob_get_contents();
			ob_clean();

			if ( $html ) {
				echo '' . $html;
			} else {
				echo 0;
			}

			wp_die();
		}
	}

	Unicamp_Popup::instance()->initialize();
}
