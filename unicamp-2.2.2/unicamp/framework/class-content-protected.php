<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Content_Protected' ) ) {
	class Unicamp_Content_Protected {

		protected static $instance = null;

		static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_footer', array( $this, 'output_template' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		}

		public function output_template() {
			$content_protected = Unicamp::setting( 'content_protected_enable' );

			if ( ! $content_protected ) {
				return;
			}

			?>
			<div id="unicamp-content-protected-box" class="unicamp-content-protected-box">
				<?php printf( esc_html__(
					'%sAlert:%s You are not allowed to copy content or view source !!', 'unicamp'
				), '<span class="alert-label">', '</span>' ); ?>
			</div>
			<?php
		}

		public function frontend_scripts() {
			$min = Unicamp_Enqueue::instance()->get_min_suffix();

			$content_protected = Unicamp::setting( 'content_protected_enable' );

			if ( ! $content_protected ) {
				return;
			}

			wp_register_script( 'unicamp-content-protected', UNICAMP_THEME_ASSETS_URI . "/js/content-protected{$min}.js", [ 'jquery' ], null, true );

			wp_enqueue_script( 'unicamp-content-protected' );
		}
	}

	Unicamp_Content_Protected::instance()->initialize();
}
