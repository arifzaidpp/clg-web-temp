<?php
defined( 'ABSPATH' ) || exit;

/**
 * Enqueue scripts and styles.
 */
if ( ! class_exists( 'Unicamp_Enqueue' ) ) {
	class Unicamp_Enqueue {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			// Set priority 4 to make it run before elementor register scripts.
			add_action( 'wp_enqueue_scripts', array( $this, 'register_swiper' ), 4 );

			add_action( 'wp_enqueue_scripts', array( $this, 'polyfill_script' ), 1 );

			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );

			/**
			 * Make it run after main style & components.
			 */
			add_action( 'wp_enqueue_scripts', array( $this, 'rtl_styles' ), 20 );

			// Disable woocommerce all styles.
			add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

			// Disable all contact form 7 scripts.
			add_filter( 'wpcf7_load_js', '__return_false' );
			add_filter( 'wpcf7_load_css', '__return_false' );
		}

		/**
		 * Register swiper lib.
		 * Use on wp_enqueue_scripts action.
		 */
		public function register_swiper() {
			$min = $this->get_min_suffix();

			wp_register_style( 'swiper', UNICAMP_THEME_URI . '/assets/libs/swiper/css/swiper.min.css', null, '5.4.1' );
			wp_register_script( 'swiper', UNICAMP_THEME_URI . '/assets/libs/swiper/js/swiper.min.js', array(
				'jquery',
				'imagesloaded',
			), '5.4.1', true );

			wp_register_script( 'unicamp-swiper-wrapper', UNICAMP_THEME_URI . "/assets/js/swiper-wrapper{$min}.js", array( 'swiper' ), UNICAMP_THEME_VERSION, true );

			$unicamp_swiper_js = array(
				'fractionPrefixText' => esc_html__( 'Show', 'unicamp' ),
				'prevText'           => esc_html__( 'Prev', 'unicamp' ),
				'nextText'           => esc_html__( 'Next', 'unicamp' ),
			);
			wp_localize_script( 'unicamp-swiper-wrapper', '$unicampSwiper', $unicamp_swiper_js );
		}

		/**
		 * Fix elementor broken on old browsers
		 * Safari < 12.1
		 * Firefox < 55
		 * Chrome < 51
		 */
		public function polyfill_script() {
			wp_enqueue_script( 'intersection-observer', UNICAMP_THEME_URI . '/assets/libs/polyfill/intersection-observer.min.js', null, null, true );
		}

		/**
		 * Enqueue scripts & styles for frond-end.
		 *
		 * @access public
		 */
		public function frontend_scripts() {
			$min       = $this->get_min_suffix();
			$post_type = get_post_type();

			// Remove prettyPhoto, default light box of woocommerce.
			wp_dequeue_script( 'prettyPhoto' );
			wp_dequeue_script( 'prettyPhoto-init' );
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );

			// Remove font awesome from Yith Wishlist plugin.
			wp_dequeue_style( 'yith-wcwl-font-awesome' );

			// Remove hint from Woo Smart Compare plugin.
			wp_dequeue_style( 'hint' );

			// Remove feather font from Woo Smart Wishlist plugin.
			wp_dequeue_style( 'woosw-feather' );

			/*
			 * Begin register scripts & styles to be enqueued later.
			 */
			wp_register_style( 'unicamp-woocommerce', UNICAMP_THEME_URI . "/woocommerce{$min}.css", null, UNICAMP_THEME_VERSION );

			wp_register_style( 'font-awesome-pro', UNICAMP_THEME_URI . '/assets/fonts/awesome/css/fontawesome-all.min.css', null, '5.10.0' );

			wp_register_style( 'select2', UNICAMP_THEME_URI . '/assets/libs/select2/select2.css' );
			wp_register_script( 'select2', UNICAMP_THEME_URI . '/assets/libs/select2/select2.full.min.js', array( 'jquery' ), '4.0.3', true );
			wp_register_script( 'selectWoo', UNICAMP_THEME_URI . '/assets/libs/selectWoo/selectWoo.full.min.js', array( 'jquery' ), '1.0.6', true );

			wp_register_style( 'justifiedGallery', UNICAMP_THEME_URI . '/assets/libs/justifiedGallery/css/justifiedGallery.min.css', null, '3.7.0' );
			wp_register_script( 'justifiedGallery', UNICAMP_THEME_URI . '/assets/libs/justifiedGallery/js/jquery.justifiedGallery.min.js', array( 'jquery' ), '3.7.0', true );

			wp_register_script( 'powertip', UNICAMP_THEME_URI . '/assets/libs/powertip/js/jquery.powertip.min.js', array( 'jquery' ), '1.3.1', true );

			wp_register_style( 'lightgallery', UNICAMP_THEME_URI . '/assets/libs/lightGallery/css/lightgallery.min.css', null, '1.6.12' );
			wp_register_script( 'lightgallery', UNICAMP_THEME_URI . '/assets/libs/lightGallery/js/lightgallery-all.min.js', array(
				'jquery',
			), '1.6.12', true );

			wp_register_script( 'unicamp-modal', UNICAMP_THEME_URI . "/assets/js/modal{$min}.js", [ 'jquery' ], UNICAMP_THEME_VERSION, true );

			wp_register_style( 'magnific-popup', UNICAMP_THEME_URI . '/assets/libs/magnific-popup/magnific-popup.css' );
			wp_register_script( 'magnific-popup', UNICAMP_THEME_URI . '/assets/libs/magnific-popup/jquery.magnific-popup.js', array( 'jquery' ), UNICAMP_THEME_VERSION, true );

			wp_register_style( 'growl', UNICAMP_THEME_URI . '/assets/libs/growl/css/jquery.growl.min.css', null, '1.3.3' );
			wp_register_script( 'growl', UNICAMP_THEME_URI . '/assets/libs/growl/js/jquery.growl.min.js', array( 'jquery' ), '1.3.3', true );

			wp_register_script( 'matchheight', UNICAMP_THEME_URI . '/assets/libs/matchHeight/jquery.matchHeight-min.js', array( 'jquery' ), UNICAMP_THEME_VERSION, true );

			wp_register_script( 'countdown', UNICAMP_THEME_URI . "/assets/libs/jquery.countdown/js/jquery.countdown{$min}.js", [ 'jquery' ], '1.0.0', true );

			wp_register_script( 'smooth-scroll', UNICAMP_THEME_URI . '/assets/libs/smooth-scroll-for-web/SmoothScroll.min.js', array(
				'jquery',
			), '1.4.9', true );

			// Fix Wordpress old version not registered this script.
			if ( ! wp_script_is( 'imagesloaded', 'registered' ) ) {
				wp_register_script( 'imagesloaded', UNICAMP_THEME_URI . '/assets/libs/imagesloaded/imagesloaded.min.js', array( 'jquery' ), null, true );
			}

			/**
			 * Fix Elementor load out-date version 1.0.1
			 */
			wp_deregister_script( 'smartmenus' );
			wp_register_script( 'smartmenus', UNICAMP_THEME_URI . '/assets/libs/smartmenus/jquery.smartmenus.min.js', array( 'jquery' ), '1.1.1', true );

			$this->register_swiper();

			wp_register_script( 'sticky-kit', UNICAMP_THEME_URI . '/assets/js/jquery.sticky-kit.min.js', array(
				'jquery',
				'unicamp-script',
			), UNICAMP_THEME_VERSION, true );

			wp_register_script( 'picturefill', UNICAMP_THEME_URI . '/assets/libs/picturefill/picturefill.min.js', array( 'jquery' ), null, true );

			wp_register_script( 'mousewheel', UNICAMP_THEME_URI . '/assets/libs/mousewheel/jquery.mousewheel.min.js', array( 'jquery' ), UNICAMP_THEME_VERSION, true );

			$google_api_key = $this->get_google_api_key();
			wp_register_script( 'unicamp-gmap3', UNICAMP_THEME_URI . '/assets/libs/gmap3/gmap3.min.js', array( 'jquery' ), UNICAMP_THEME_VERSION, true );
			wp_register_script( 'unicamp-maps', UNICAMP_PROTOCOL . '://maps.google.com/maps/api/js?key=' . $google_api_key . '&amp;language=en' );

			wp_register_script( 'isotope-masonry', UNICAMP_THEME_URI . '/assets/libs/isotope/js/isotope.pkgd.js', array( 'jquery' ), UNICAMP_THEME_VERSION, true );
			wp_register_script( 'isotope-packery', UNICAMP_THEME_URI . '/assets/libs/packery-mode/packery-mode.pkgd.js', array( 'jquery' ), UNICAMP_THEME_VERSION, true );

			wp_register_script( 'unicamp-grid-layout', UNICAMP_THEME_ASSETS_URI . "/js/grid-layout{$min}.js", array(
				'jquery',
				'imagesloaded',
				'matchheight',
				'isotope-masonry',
				'isotope-packery',
			), null, true );

			wp_register_script( 'unicamp-common-archive', UNICAMP_THEME_URI . "/assets/js/common-archive{$min}.js", [
				'jquery',
				'perfect-scrollbar',
			], UNICAMP_THEME_VERSION, true );
			wp_register_script( 'unicamp-quantity-button', UNICAMP_THEME_URI . "/assets/js/woo/quantity-button{$min}.js", [ 'jquery' ], UNICAMP_THEME_VERSION, true );

			wp_register_script( 'unicamp-woo-general', UNICAMP_THEME_URI . "/assets/js/woo/general{$min}.js", [ 'jquery' ], UNICAMP_THEME_VERSION, true );
			wp_register_script( 'unicamp-woo-checkout', UNICAMP_THEME_URI . "/assets/js/woo/checkout{$min}.js", [ 'jquery' ], UNICAMP_THEME_VERSION, true );
			wp_register_script( 'unicamp-woo-single', UNICAMP_THEME_URI . "/assets/js/woo/single{$min}.js", [ 'jquery' ], UNICAMP_THEME_VERSION, true );

			wp_register_script( 'unicamp-tab-panel', UNICAMP_THEME_URI . "/assets/js/tab-panel{$min}.js", [ 'jquery' ], UNICAMP_THEME_VERSION, true );

			/*
			 * End register scripts
			 */

			wp_enqueue_style( 'font-awesome-pro' );
			wp_enqueue_style( 'swiper' );

			/*
			 * Enqueue the theme's style.css.
			 * This is recommended because we can add inline styles there
			 * and some plugins use it to do exactly that.
			 */
			wp_enqueue_style( 'unicamp-style', get_template_directory_uri() . "/style{$min}.css" );

			if ( Unicamp::setting( 'header_sticky_enable' ) ) {
				wp_enqueue_script( 'headroom', UNICAMP_THEME_URI . '/assets/js/headroom.min.js', array( 'jquery' ), UNICAMP_THEME_VERSION, true );
			}

			if ( Unicamp::setting( 'smooth_scroll_enable' ) && ! Unicamp_Helper::is_elementor_editor() ) {
				wp_enqueue_script( 'smooth-scroll' );
			}


			// Use waypoints lib edited by Elementor to avoid duplicate the script.
			if ( ! wp_script_is( 'elementor-waypoints', 'registered' ) ) {
				wp_register_script( 'elementor-waypoints', UNICAMP_THEME_URI . '/assets/libs/elementor-waypoints/jquery.waypoints.min.js', array( 'jquery' ), null, true );
			}

			wp_enqueue_script( 'elementor-waypoints' );

			wp_enqueue_script( 'powertip' );

			wp_enqueue_script( 'jquery-smooth-scroll', UNICAMP_THEME_URI . '/assets/libs/smooth-scroll/jquery.smooth-scroll.min.js', array( 'jquery' ), UNICAMP_THEME_VERSION, true );

			wp_enqueue_script( 'unicamp-swiper-wrapper' );

			wp_enqueue_script( 'smartmenus' );

			wp_enqueue_style( 'perfect-scrollbar', UNICAMP_THEME_URI . '/assets/libs/perfect-scrollbar/css/perfect-scrollbar.min.css' );
			wp_enqueue_script( 'perfect-scrollbar', UNICAMP_THEME_URI . '/assets/libs/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js', array( 'jquery' ), UNICAMP_THEME_VERSION, true );

			wp_enqueue_script( 'growl' );
			wp_enqueue_style( 'growl' );

			if ( is_singular( 'post' ) ) {
				wp_enqueue_script( 'lightgallery' );
				wp_enqueue_style( 'lightgallery' );
			}

			wp_enqueue_script( 'unicamp-modal' );

			if ( is_search() ) {
				wp_enqueue_script( 'unicamp-grid-layout' );
			}

			/*
			 * The comment-reply script.
			 */
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				switch ( $post_type ) {
					case 'post':
						if ( Unicamp::setting( 'single_post_comment_enable' ) === '1' ) {
							wp_enqueue_script( 'comment-reply' );
						}
						break;
					default:
						wp_enqueue_script( 'comment-reply' );
						break;
				}
			}

			wp_enqueue_script( 'unicamp-nice-select', UNICAMP_THEME_URI . "/assets/js/nice-select{$min}.js", array(
				'jquery',
			), UNICAMP_THEME_VERSION, true );

			/*
			 * Enqueue main JS
			 */
			wp_enqueue_script( 'unicamp-script', UNICAMP_THEME_URI . "/assets/js/main{$min}.js", array(
				'jquery',
			), UNICAMP_THEME_VERSION, true );

			if ( Unicamp_Woo::instance()->is_activated() ) {
				wp_enqueue_style( 'unicamp-woocommerce' );

				if ( '1' === Unicamp::setting( 'shop_archive_quick_view' ) ) {
					wp_enqueue_style( 'magnific-popup' );
					wp_enqueue_script( 'magnific-popup' );

					/**
					 * Enable ajax add to cart variation in Quick View popup on all pages.
					 */
					wp_enqueue_script( 'wc-add-to-cart-variation' );

					// Quick view need change quantity.
					wp_enqueue_script( 'unicamp-quantity-button' );
				}


				wp_enqueue_script( 'unicamp-woo-general' );

				if ( Unicamp_Woo::instance()->is_product_archive() ) {
					wp_enqueue_script( 'unicamp-grid-layout' );
					wp_enqueue_script( 'unicamp-common-archive' );
				}

				if ( is_cart() || is_product() ) {
					wp_enqueue_script( 'unicamp-quantity-button' );
				}

				if ( is_checkout() ) {
					wp_enqueue_script( 'unicamp-woo-checkout' );
				}

				if ( is_product() ) {
					$single_product_sticky = Unicamp::setting( 'single_product_sticky_enable' );
					if ( '1' === $single_product_sticky ) {
						wp_enqueue_script( 'sticky-kit' );
					}

					wp_enqueue_style( 'lightgallery' );
					wp_enqueue_script( 'lightgallery' );

					wp_enqueue_script( 'unicamp-woo-single' );
				}
			}

			/*
			 * Enqueue custom variable JS
			 */
			if ( class_exists( 'WooCommerce' ) ) {
				$notice_cart_url = wc_get_cart_url();
			} else {
				$notice_cart_url = get_home_url( 'cart' );
			}

			$js_variables = array(
				'ajaxurl'                   => admin_url( 'admin-ajax.php' ),
				'header_sticky_enable'      => Unicamp::setting( 'header_sticky_enable' ),
				'header_sticky_height'      => Unicamp::setting( 'header_sticky_height' ),
				'scroll_top_enable'         => Unicamp::setting( 'scroll_top_enable' ),
				'light_gallery_auto_play'   => Unicamp::setting( 'light_gallery_auto_play' ),
				'light_gallery_download'    => Unicamp::setting( 'light_gallery_download' ),
				'light_gallery_full_screen' => Unicamp::setting( 'light_gallery_full_screen' ),
				'light_gallery_zoom'        => Unicamp::setting( 'light_gallery_zoom' ),
				'light_gallery_thumbnail'   => Unicamp::setting( 'light_gallery_thumbnail' ),
				'light_gallery_share'       => Unicamp::setting( 'light_gallery_share' ),
				'mobile_menu_breakpoint'    => Unicamp::setting( 'mobile_menu_breakpoint' ),
				'isRTL'                     => UNICAMP_IS_RTL,
				'isSingle'                  => is_singular(),
				'postType'                  => get_post_type(),
				'productFeatureStyle'       => Unicamp_Woo::instance()->get_single_product_style(),
				'noticeCookieEnable'        => Unicamp::setting( 'notice_cookie_enable' ),
				'noticeCookieConfirm'       => isset( $_COOKIE['notice_cookie_confirm'] ) ? 'yes' : 'no',
				'noticeCookieMessages'      => Unicamp_Notices::instance()->get_notice_cookie_messages(),
				'noticeCartUrl'             => esc_js( $notice_cart_url ),
				'noticeCartText'            => esc_js( esc_html__( 'View Cart', 'unicamp' ) ),
				'noticeAddedCartText'       => esc_js( esc_html__( 'added to cart!', 'unicamp' ) ),
				'countdownDaysText'         => esc_html__( 'Days', 'unicamp' ),
				'countdownHoursText'        => esc_html__( 'Hours', 'unicamp' ),
				'countdownMinutesText'      => esc_html__( 'Mins', 'unicamp' ),
				'countdownSecondsText'      => esc_html__( 'Secs', 'unicamp' ),
			);
			wp_localize_script( 'unicamp-script', '$unicamp', $js_variables );

			/**
			 * Custom JS
			 */
			if ( Unicamp::setting( 'custom_js_enable' ) == 1 ) {
				wp_add_inline_script( 'unicamp-script', html_entity_decode( Unicamp::setting( 'custom_js' ) ) );
			}
		}

		public function rtl_styles() {
			$min = $this->get_min_suffix();

			wp_register_style( 'unicamp-style-rtl', UNICAMP_THEME_URI . "/style-rtl$min.css", null, UNICAMP_THEME_VERSION );

			if ( is_rtl() ) {
				wp_enqueue_style( 'unicamp-style-rtl' );
			}
		}

		public function get_google_api_key() {
			if ( defined( 'UNICAMP_GOOGLE_MAP_API_KEY' ) && ! empty( UNICAMP_GOOGLE_MAP_API_KEY ) ) {
				return UNICAMP_GOOGLE_MAP_API_KEY;
			}

			return Unicamp::setting( 'google_api_key' );
		}

		/**
		 * @return string
		 */
		public function get_min_suffix() {
			$min = '.min';

			if ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) {
				$min = '';
			}

			return $min;
		}
	}

	Unicamp_Enqueue::instance()->initialize();
}
