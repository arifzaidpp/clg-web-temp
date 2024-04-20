<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Footer' ) ) {

	class Unicamp_Footer {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_footer', array( $this, 'scroll_top' ) );
			add_action( 'wp_footer', array( $this, 'popup_search' ) );
			add_action( 'wp_footer', array( $this, 'mobile_menu_template' ) );
		}

		/**
		 * Add popup search template to footer
		 */
		function popup_search() {
			$enable = Unicamp_Global::instance()->get_popup_search();
			if ( $enable !== true ) {
				return;
			}
			?>
			<div id="popup-search" class="page-popup page-search-popup">
				<div id="search-popup-close" class="popup-close-button">
					<div class="burger-icon">
						<span class="burger-icon-top"></span>
						<span class="burger-icon-bottom"></span>
					</div>
				</div>

				<div class="page-search-popup-content">
					<?php get_search_form(); ?>
				</div>
			</div>
			<?php
		}

		function mobile_menu_logo() {
			$mobile_logo = Unicamp::setting( 'mobile_menu_logo' );
			$logo_width  = Unicamp::setting( 'mobile_logo_width' );

			if ( isset( $mobile_logo['id'] ) ) {
				$logo_url = Unicamp_Image::get_attachment_url_by_id( array(
					'id'   => $mobile_logo['id'],
					'size' => "{$logo_width}x9999",
					'crop' => false,
				) );
			} else {
				$logo_url = $mobile_logo['url'];
			}
			?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<img src="<?php echo esc_url( $logo_url ); ?>"
				     alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"
				     width="<?php echo esc_attr( $logo_width ); ?>"/>
			</a>
			<?php
		}

		/**
		 * Add mobile menu template to footer
		 */
		function mobile_menu_template() {
			$background       = Unicamp::setting( 'mobile_menu_background' );
			$background_image = isset( $background['background-image'] ) ? $background['background-image'] : '';
			?>
			<div id="page-mobile-main-menu" class="page-mobile-main-menu"
			     data-background="<?php echo esc_url( $background_image ); ?>">
				<div class="inner">
					<div class="page-mobile-menu-header">
						<div class="page-mobile-popup-logo page-mobile-menu-logo">
							<?php $this->mobile_menu_logo(); ?>
						</div>
						<div id="page-close-mobile-menu" class="page-close-mobile-menu">
							<div class="burger-icon burger-icon-close">
								<span class="burger-icon-top"></span>
								<span class="burger-icon-bottom"></span>
							</div>
						</div>
					</div>

					<div class="page-mobile-menu-content">
						<?php Unicamp::menu_mobile_primary(); ?>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Add scroll to top template to footer
		 */
		function scroll_top() {
			?>
			<?php if ( Unicamp::setting( 'scroll_top_enable' ) ) : ?>
				<a class="page-scroll-up" id="page-scroll-up">
					<i class="arrow-top fal fa-long-arrow-up"></i>
					<i class="arrow-bottom fal fa-long-arrow-up"></i>
				</a>
			<?php endif; ?>
			<?php
		}
	}

	Unicamp_Footer::instance()->initialize();
}
