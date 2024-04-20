<?php
defined( 'ABSPATH' ) || exit;

/**
 * Initialize Global Variables
 */
if ( ! class_exists( 'Unicamp_Global' ) ) {
	class Unicamp_Global {

		protected static $instance         = null;
		protected static $slider           = '';
		protected static $slider_position  = 'below';
		protected static $top_bar_type     = '01';
		protected static $header_type      = '01';
		protected static $header_overlay   = '0';
		protected static $header_skin      = 'dark';
		protected static $title_bar_type   = '01';
		protected static $sidebar_1        = '';
		protected static $sidebar_2        = '';
		protected static $sidebar_position = '';
		protected static $sidebar_status   = 'none';
		protected static $popup_search     = false;
		protected static $footer           = '';

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			/**
			 * Use hook wp instead of init because we need post meta setup.
			 * then we must wait for post loaded.
			 */
			add_action( 'wp', array( $this, 'init_global_variable' ) );

			/**
			 * Setup global variables.
			 * Used priority 12 to wait override settings setup.
			 *
			 * @see Unicamp_Customize->setup_override_settings()
			 */
			add_action( 'wp', array( $this, 'setup_global_variables' ), 12 );
		}

		function init_global_variable() {
			global $unicamp_page_options;
			if ( is_singular( 'post' ) ) {
				$unicamp_page_options = unserialize( get_post_meta( get_the_ID(), 'insight_post_options', true ) );
			} elseif ( is_singular( 'page' ) ) {
				$unicamp_page_options = unserialize( get_post_meta( get_the_ID(), 'insight_page_options', true ) );
			} elseif ( is_singular( 'product' ) ) {
				$unicamp_page_options = unserialize( get_post_meta( get_the_ID(), 'insight_product_options', true ) );
			}
			if ( function_exists( 'is_shop' ) && is_shop() ) {
				// Get page id of shop.
				$page_id              = wc_get_page_id( 'shop' );
				$unicamp_page_options = unserialize( get_post_meta( $page_id, 'insight_page_options', true ) );
			}
		}

		public function setup_global_variables() {
			$this->set_slider();
			$this->set_top_bar_type();
			$this->set_header_options();
			$this->set_title_bar_type();
			$this->set_sidebars();
			$this->set_popup_search();
			$this->set_footer();
		}

		function set_slider() {
			$alias    = Unicamp_Helper::get_post_meta( 'revolution_slider', '' );
			$position = Unicamp_Helper::get_post_meta( 'slider_position', '' );

			self::$slider          = $alias;
			self::$slider_position = $position;
		}

		function get_slider_alias() {
			return self::$slider;
		}

		function get_slider_position() {
			return self::$slider_position;
		}

		function set_top_bar_type() {
			$type = Unicamp_Helper::get_post_meta( 'top_bar_type', '' );

			if ( $type === '' ) {
				$type = Unicamp::setting( 'global_top_bar' );
			}

			$type = apply_filters( 'unicamp_top_bar_type', $type );

			self::$top_bar_type = $type;
		}

		function get_top_bar_type() {
			return self::$top_bar_type;
		}

		function set_header_options() {
			$header_type    = Unicamp_Helper::get_post_meta( 'header_type', '' );
			$header_overlay = Unicamp_Helper::get_post_meta( 'header_overlay', '' );
			$header_skin    = Unicamp_Helper::get_post_meta( 'header_skin', '' );

			if ( Unicamp_Woo::instance()->is_woocommerce_page_without_product() ) {

				if ( $header_type === '' ) {
					$header_type = Unicamp::setting( 'product_archive_header_type' );
				}

				if ( $header_overlay === '' ) {
					$header_overlay = Unicamp::setting( 'product_archive_header_overlay' );
				}

				if ( $header_skin === '' ) {
					$header_skin = Unicamp::setting( 'product_archive_header_skin' );
				}

			} elseif ( Unicamp_Post::instance()->is_archive() ) {
				if ( $header_type === '' ) {
					$header_type = Unicamp::setting( 'blog_archive_header_type' );
				}

				if ( $header_overlay === '' ) {
					$header_overlay = Unicamp::setting( 'blog_archive_header_overlay' );
				}

				if ( $header_skin === '' ) {
					$header_skin = Unicamp::setting( 'blog_archive_header_skin' );
				}

			} elseif ( is_singular( 'post' ) ) {

				if ( $header_type === '' ) {
					$header_type = Unicamp::setting( 'blog_single_header_type' );
				}

				if ( $header_overlay === '' ) {
					$header_overlay = Unicamp::setting( 'blog_single_header_overlay' );
				}

				if ( $header_skin === '' ) {
					$header_skin = Unicamp::setting( 'blog_single_header_skin' );
				}

			} elseif ( is_singular( Unicamp_Event::POST_TYPE ) ) {

				if ( $header_type === '' ) {
					$header_type = Unicamp::setting( 'event_single_header_type' );
				}

				if ( $header_overlay === '' ) {
					$header_overlay = Unicamp::setting( 'event_single_header_overlay' );
				}

				if ( $header_skin === '' ) {
					$header_skin = Unicamp::setting( 'event_single_header_skin' );
				}

			} elseif ( is_singular( 'product' ) ) {

				if ( $header_type === '' ) {
					$header_type = Unicamp::setting( 'product_single_header_type' );
				}

				if ( $header_overlay === '' ) {
					$header_overlay = Unicamp::setting( 'product_single_header_overlay' );
				}

				if ( $header_skin === '' ) {
					$header_skin = Unicamp::setting( 'product_single_header_overlay' );
				}

			} elseif ( is_singular( 'page' ) ) {

				if ( $header_type === '' ) {
					$header_type = Unicamp::setting( 'page_header_type' );
				}

				if ( $header_overlay === '' ) {
					$header_overlay = Unicamp::setting( 'page_header_overlay' );
				}

				if ( $header_skin === '' ) {
					$header_skin = Unicamp::setting( 'page_header_skin' );
				}

			} else {

				if ( $header_type === '' ) {
					$header_type = Unicamp::setting( 'global_header' );
				}

				if ( $header_overlay === '' ) {
					$header_overlay = Unicamp::setting( 'global_header_overlay' );
				}

				if ( $header_skin === '' ) {
					$header_skin = Unicamp::setting( 'global_header_skin' );
				}
			}

			if ( $header_type === '' ) {
				$header_type = Unicamp::setting( 'global_header' );
			}

			if ( $header_overlay === '' ) {
				$header_overlay = Unicamp::setting( 'global_header_overlay' );
			}

			if ( $header_skin === '' ) {
				$header_skin = Unicamp::setting( 'global_header_skin' );
			}

			$header_type    = apply_filters( 'unicamp_header_type', $header_type );
			$header_overlay = apply_filters( 'unicamp_header_overlay', $header_overlay );
			$header_skin    = apply_filters( 'unicamp_header_skin', $header_skin );

			self::$header_type    = $header_type;
			self::$header_overlay = $header_overlay;
			self::$header_skin    = $header_skin;
		}

		function get_header_type() {
			return self::$header_type;
		}

		function get_header_overlay() {
			return self::$header_overlay;
		}

		/**
		 * @return string dark|light
		 */
		function get_header_skin() {
			return self::$header_skin;
		}

		function set_title_bar_type() {
			$type = Unicamp_Helper::get_post_meta( 'page_title_bar_layout', '' );

			if ( $type === '' ) {
				if ( Unicamp_Woo::instance()->is_woocommerce_page_without_product() ) {
					$type = Unicamp::setting( 'product_archive_title_bar_layout' );
				} elseif ( Unicamp_Event::instance()->is_archive() ) {
					$type = Unicamp::setting( 'event_archive_title_bar_layout' );
				} elseif ( Unicamp_Post::instance()->is_archive() ) {
					$type = Unicamp::setting( 'blog_archive_title_bar_layout' );
				} elseif ( is_singular( 'post' ) ) {
					$type = Unicamp::setting( 'blog_single_title_bar_layout' );
				} elseif ( is_singular( 'page' ) ) {
					$type = Unicamp::setting( 'page_title_bar_layout' );
				} elseif ( is_singular( 'product' ) ) {
					$type = Unicamp::setting( 'product_single_title_bar_layout' );
				} else {
					$type = Unicamp::setting( 'title_bar_layout' );
				}

				if ( $type === '' ) {
					$type = Unicamp::setting( 'title_bar_layout' );
				}
			}

			$type = apply_filters( 'unicamp_title_bar_type', $type );

			self::$title_bar_type = $type;
		}

		function get_title_bar_type() {
			return self::$title_bar_type;
		}

		function set_sidebars() {
			$sidebar_position = 'right';

			if ( Unicamp_Woo::instance()->is_product_archive() ) {
				$page_sidebar1    = Unicamp::setting( 'product_archive_page_sidebar_1' );
				$page_sidebar2    = Unicamp::setting( 'product_archive_page_sidebar_2' );
				$sidebar_position = Unicamp::setting( 'product_archive_page_sidebar_position' );
			} elseif ( Unicamp_Event::instance()->is_archive() ) {
				$page_sidebar1    = Unicamp::setting( 'event_archive_page_sidebar_1' );
				$page_sidebar2    = Unicamp::setting( 'event_archive_page_sidebar_2' );
				$sidebar_position = Unicamp::setting( 'event_archive_page_sidebar_position' );
			} elseif ( Unicamp_FAQ::instance()->is_archive() ) {
				$page_sidebar1    = Unicamp::setting( 'faq_archive_page_sidebar_1' );
				$page_sidebar2    = Unicamp::setting( 'faq_archive_page_sidebar_2' );
				$sidebar_position = Unicamp::setting( 'faq_archive_page_sidebar_position' );
			} elseif ( Unicamp_Post::instance()->is_archive() ) {
				$page_sidebar1    = Unicamp::setting( 'blog_archive_page_sidebar_1' );
				$page_sidebar2    = Unicamp::setting( 'blog_archive_page_sidebar_2' );
				$sidebar_position = Unicamp::setting( 'blog_archive_page_sidebar_position' );
			} elseif ( is_singular() ) {
				$post_type = get_post_type();

				// Get values from page options.
				$page_sidebar1    = Unicamp_Helper::get_post_meta( 'page_sidebar_1', 'default' );
				$page_sidebar2    = Unicamp_Helper::get_post_meta( 'page_sidebar_2', 'default' );
				$sidebar_position = Unicamp_Helper::get_post_meta( 'page_sidebar_position', 'default' );

				switch ( $post_type ) {
					case 'post' :
						if ( $page_sidebar1 === 'default' ) {
							$page_sidebar1 = Unicamp::setting( 'post_page_sidebar_1' );
						}

						if ( $page_sidebar2 === 'default' ) {
							$page_sidebar2 = Unicamp::setting( 'post_page_sidebar_2' );
						}

						if ( $sidebar_position === 'default' ) {
							$sidebar_position = Unicamp::setting( 'post_page_sidebar_position' );
						}
						break;

					case 'product' :
						if ( $page_sidebar1 === 'default' ) {
							$page_sidebar1 = Unicamp::setting( 'product_page_sidebar_1' );
						}

						if ( $page_sidebar2 === 'default' ) {
							$page_sidebar2 = Unicamp::setting( 'product_page_sidebar_2' );
						}

						if ( $sidebar_position === 'default' ) {
							$sidebar_position = Unicamp::setting( 'product_page_sidebar_position' );
						}
						break;
					case Unicamp_Event::POST_TYPE:
						if ( $page_sidebar1 === 'default' ) {
							$page_sidebar1 = Unicamp::setting( 'event_page_sidebar_1' );
						}

						if ( $page_sidebar2 === 'default' ) {
							$page_sidebar2 = Unicamp::setting( 'event_page_sidebar_2' );
						}

						if ( $sidebar_position === 'default' ) {
							$sidebar_position = Unicamp::setting( 'event_page_sidebar_position' );
						}
						break;
					case Unicamp_FAQ::instance()->get_post_type():
						if ( $page_sidebar1 === 'default' ) {
							$page_sidebar1 = Unicamp::setting( 'faq_page_sidebar_1' );
						}

						if ( $page_sidebar2 === 'default' ) {
							$page_sidebar2 = Unicamp::setting( 'faq_page_sidebar_2' );
						}

						if ( $sidebar_position === 'default' ) {
							$sidebar_position = Unicamp::setting( 'faq_page_sidebar_position' );
						}
						break;
					default:
						if ( $page_sidebar1 === 'default' ) {
							$page_sidebar1 = Unicamp::setting( 'page_sidebar_1' );
						}

						if ( $page_sidebar2 === 'default' ) {
							$page_sidebar2 = Unicamp::setting( 'page_sidebar_2' );
						}

						if ( $sidebar_position === 'default' ) {
							$sidebar_position = Unicamp::setting( 'page_sidebar_position' );
						}

						break;
				}
			}

			if ( empty( $page_sidebar1 ) || ! is_active_sidebar( $page_sidebar1 ) ) {
				$page_sidebar1 = 'none';
			}

			if ( empty( $page_sidebar2 ) || ! is_active_sidebar( $page_sidebar2 ) ) {
				$page_sidebar2 = 'none';
			}

			$page_sidebar1    = apply_filters( 'unicamp_sidebar_1', $page_sidebar1 );
			$page_sidebar2    = apply_filters( 'unicamp_sidebar_2', $page_sidebar2 );
			$sidebar_position = apply_filters( 'unicamp_sidebar_position', $sidebar_position );

			self::$sidebar_1        = $page_sidebar1;
			self::$sidebar_2        = $page_sidebar2;
			self::$sidebar_position = $sidebar_position;

			if ( $page_sidebar1 !== 'none' || $page_sidebar2 !== 'none' ) {
				self::$sidebar_status = 'one';
			}

			if ( $page_sidebar1 !== 'none' && $page_sidebar2 !== 'none' ) {
				self::$sidebar_status = 'both';
			}
		}

		function get_sidebar_1() {
			return self::$sidebar_1;
		}

		function get_sidebar_2() {
			return self::$sidebar_2;
		}

		/**
		 * @return string left|right
		 */
		function get_sidebar_position() {
			return self::$sidebar_position;
		}

		/**
		 * @return string one|both|none
		 */
		function get_sidebar_status() {
			return self::$sidebar_status;
		}

		function set_popup_search() {
			$header_type        = $this->get_header_type();
			$header_search_type = Unicamp::setting( "header_style_{$header_type}_search_enable" );

			if ( 'popup' === $header_search_type ) {
				self::$popup_search = true;
			}

			$top_bar_components = Unicamp_Top_Bar::instance()->get_active_components();
			if ( ! empty( $top_bar_components ) && in_array( 'search_popup', $top_bar_components, true ) ) {
				self::$popup_search = true;
			}
		}

		function get_popup_search() {
			return self::$popup_search;
		}

		function set_footer() {
			$footer = Unicamp_Helper::get_post_meta( 'footer_enable', '' );

			$footer = apply_filters( 'unicamp_footer', $footer );

			self::$footer = $footer;
		}

		function get_footer() {
			return self::$footer;
		}
	}

	Unicamp_Global::instance()->initialize();
}
