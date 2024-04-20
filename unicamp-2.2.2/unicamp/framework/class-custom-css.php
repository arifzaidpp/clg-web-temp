<?php
defined( 'ABSPATH' ) || exit;

/**
 * Enqueue custom styles.
 */
if ( ! class_exists( 'Unicamp_Custom_Css' ) ) {
	class Unicamp_Custom_Css {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_root_css' ] );

			add_action( 'wp_enqueue_scripts', [ $this, 'extra_css' ] );
		}

		public function get_root_css() {
			$primary_color     = Unicamp::setting( 'primary_color' );
			$primary_color_rgb = Unicamp_Color::hex2rgb_string( $primary_color );
			$secondary_color   = Unicamp::setting( 'secondary_color' );
			$third_color       = Unicamp::setting( 'third_color' );

			$button_colors          = Unicamp::setting( 'button_style_flat_color' );
			$button_hover_colors    = Unicamp::setting( 'button_style_flat_hover_color' );
			$form_colors            = Unicamp::setting( 'form_input_color' );
			$form_focus_colors      = Unicamp::setting( 'form_input_focus_color' );
			$text_color             = Unicamp::setting( 'body_color' );
			$text_bit_lighten_color = '#7e7e7e';
			$text_lighten_color     = Unicamp::setting( 'body_lighten_color' );
			$heading_color          = Unicamp::setting( 'heading_color' );
			$link_color             = Unicamp::setting( 'link_color' );
			$box_white_border       = '#ededed';
			$box_white_bg           = '#fff';
			$box_grey_bg            = '#f8f8f8';
			$box_light_grey_bg      = '#f9f9fb';
			$box_separator          = '#eee';
			$box_border             = '#eee';
			$box_border_lighten     = '#ededed';
			$sub_menu_background    = Unicamp::setting( 'navigation_dropdown_bg_color' );
			$sub_menu_border        = '#ededed';

			$form_text             = $form_colors['color'];
			$form_border           = $form_colors['border'];
			$form_background       = $form_colors['background'];
			$form_focus_text       = $form_focus_colors['color'];
			$form_focus_border     = $form_focus_colors['border'];
			$form_focus_background = $form_focus_colors['background'];

			$button_text             = $button_colors['color'];
			$button_border           = $button_colors['border'];
			$button_background       = $button_colors['background'];
			$button_hover_text       = $button_hover_colors['color'];
			$button_hover_border     = $button_hover_colors['border'];
			$button_hover_background = $button_hover_colors['background'];

			$body_font        = Unicamp::setting( 'typography_body' );
			$body_font_weight = $body_font['variant'];
			$body_font_weight = 'regular' === $body_font_weight ? 400 : $body_font_weight; // Fix regular is not valid weight.

			$heading_font = Unicamp::setting( 'typography_heading' );

			$heading_font_family = '' === $heading_font['font-family'] ? 'inherit' : $heading_font['font-family'];
			$heading_font_weight = $heading_font['variant'];
			$heading_font_weight = 'regular' === $heading_font_weight ? 400 : $heading_font_weight; // Fix regular is not valid weight.

			$css = ":root {
				--unicamp-typography-body-font-family: {$body_font['font-family']};
				--unicamp-typography-body-font-size: {$body_font['font-size']};
				--unicamp-typography-body-font-weight: {$body_font_weight};
				--unicamp-typography-body-line-height: {$body_font['line-height']};
				--unicamp-typography-body-letter-spacing: {$body_font['letter-spacing']};
				--unicamp-typography-headings-font-family: {$heading_font_family};
				--unicamp-typography-headings-font-weight: {$heading_font_weight};
				--unicamp-typography-headings-line-height: {$heading_font['line-height']};
				--unicamp-typography-headings-letter-spacing: {$heading_font['letter-spacing']};
				--unicamp-color-primary: {$primary_color};
				--unicamp-color-primary-rgb: {$primary_color_rgb};
				--unicamp-color-secondary: {$secondary_color};
				--unicamp-color-third: {$third_color};
				--unicamp-color-text: {$text_color};
				--unicamp-color-text-bit-lighten: {$text_bit_lighten_color};
				--unicamp-color-text-lighten: {$text_lighten_color};
				--unicamp-color-heading: {$heading_color};
				--unicamp-color-link: {$link_color['normal']};
				--unicamp-color-link-hover: {$link_color['hover']};
				--unicamp-color-box-white-background: {$box_white_bg};
				--unicamp-color-box-white-border: {$box_white_border};
				--unicamp-color-box-grey-background: {$box_grey_bg};
				--unicamp-color-box-light-grey-background: {$box_light_grey_bg};
				--unicamp-color-box-fill-separator: {$box_separator};
				--unicamp-color-box-border: {$box_border};
				--unicamp-color-box-border-lighten: {$box_border_lighten};
				--unicamp-color-button-text: {$button_text};
				--unicamp-color-button-border: {$button_border};
				--unicamp-color-button-background: {$button_background};
				--unicamp-color-button-hover-text: {$button_hover_text};
				--unicamp-color-button-hover-border: {$button_hover_border};
				--unicamp-color-button-hover-background: {$button_hover_background};
				--unicamp-color-form-text: {$form_text};
				--unicamp-color-form-border: {$form_border};
				--unicamp-color-form-background: {$form_background};
				--unicamp-color-form-focus-text: {$form_focus_text};
				--unicamp-color-form-focus-border: {$form_focus_border};
				--unicamp-color-form-focus-background: {$form_focus_background};
				--unicamp-color-sub-menu-border: {$sub_menu_border};
				--unicamp-color-sub-menu-background: {$sub_menu_background};
			}";

			return $css;
		}

		public function frontend_root_css() {
			$css = $this->get_root_css();

			wp_add_inline_style( 'unicamp-style', html_entity_decode( $css, ENT_QUOTES ) );
		}

		/**
		 * Responsive styles.
		 *
		 * @access public
		 */
		public function extra_css() {
			$extra_style = '';

			$custom_logo_width        = Unicamp_Helper::get_post_meta( 'custom_logo_width', '' );
			$custom_sticky_logo_width = Unicamp_Helper::get_post_meta( 'custom_sticky_logo_width', '' );

			if ( $custom_logo_width !== '' ) {
				$extra_style .= ".branding__logo img { 
                    width: {$custom_logo_width} !important; 
                }";
			}

			if ( $custom_sticky_logo_width !== '' ) {
				$extra_style .= ".headroom--not-top .branding__logo .sticky-logo { 
                    width: {$custom_sticky_logo_width} !important; 
                }";
			}

			$site_width = Unicamp_Helper::get_post_meta( 'site_width', '' );
			if ( $site_width === '' ) {
				$site_width = Unicamp::setting( 'site_width' );
			}

			if ( $site_width !== '' ) {
				$extra_style .= "
				.boxed
				{
	                max-width: $site_width;
	            }";
			}

			$site_top_spacing = Unicamp_Helper::get_post_meta( 'site_top_spacing', '' );

			if ( $site_top_spacing !== '' ) {
				$extra_style .= "
				.boxed
				{
	                margin-top: {$site_top_spacing};
	            }";
			}

			$site_bottom_spacing = Unicamp_Helper::get_post_meta( 'site_bottom_spacing', '' );

			if ( $site_bottom_spacing !== '' ) {
				$extra_style .= "
				.boxed
				{
	                margin-bottom: {$site_bottom_spacing};
	            }";
			}

			$tmp = '';

			$site_background_color = Unicamp_Helper::get_post_meta( 'site_background_color', '' );
			if ( $site_background_color !== '' ) {
				$tmp .= "background-color: $site_background_color !important;";
			}

			$site_background_image = Unicamp_Helper::get_post_meta( 'site_background_image', '' );
			if ( $site_background_image !== '' ) {
				$site_background_repeat = Unicamp_Helper::get_post_meta( 'site_background_repeat', '' );
				$tmp                    .= "background-image: url( $site_background_image ) !important; background-repeat: $site_background_repeat !important;";
			}

			$site_background_position = Unicamp_Helper::get_post_meta( 'site_background_position', '' );
			if ( $site_background_position !== '' ) {
				$tmp .= "background-position: $site_background_position !important;";
			}

			$site_background_size = Unicamp_Helper::get_post_meta( 'site_background_size', '' );
			if ( $site_background_size !== '' ) {
				$tmp .= "background-size: $site_background_size !important;";
			}

			$site_background_attachment = Unicamp_Helper::get_post_meta( 'site_background_attachment', '' );
			if ( $site_background_attachment !== '' ) {
				$tmp .= "background-attachment: $site_background_attachment !important;";
			}

			if ( $tmp !== '' ) {
				$extra_style .= "body { $tmp; }";
			}

			$tmp = '';

			$content_background_color = Unicamp_Helper::get_post_meta( 'content_background_color', '' );
			if ( $content_background_color !== '' ) {
				$tmp .= "background-color: $content_background_color !important;";
			}

			$content_background_image = Unicamp_Helper::get_post_meta( 'content_background_image', '' );
			if ( $content_background_image !== '' ) {
				$content_background_repeat = Unicamp_Helper::get_post_meta( 'content_background_repeat', '' );
				$tmp                       .= "background-image: url( $content_background_image ) !important; background-repeat: $content_background_repeat !important;";
			}

			$content_background_position = Unicamp_Helper::get_post_meta( 'content_background_position', '' );
			if ( $content_background_position !== '' ) {
				$tmp .= "background-position: $content_background_position !important;";
			}

			if ( $tmp !== '' ) {
				$extra_style .= ".site { $tmp; }";
			}

			$extra_style .= $this->primary_color_css();
			$extra_style .= $this->header_css();
			$extra_style .= $this->sidebar_css();
			$extra_style .= $this->title_bar_css();
			$extra_style .= $this->light_gallery_css();
			$extra_style .= $this->off_canvas_menu_css();
			$extra_style .= $this->mobile_menu_css();

			$extra_style = apply_filters( 'unicamp_custom_css', $extra_style );

			$extra_style = Unicamp_Minify::css( $extra_style );

			wp_add_inline_style( 'unicamp-style', html_entity_decode( $extra_style, ENT_QUOTES ) );
		}

		function header_css() {
			$header_type = Unicamp_Global::instance()->get_header_type();
			$css         = '';

			$nav_bg_type = Unicamp::setting( "header_style_{$header_type}_navigation_background_type" );

			if ( $nav_bg_type === 'gradient' ) {

				$gradient = Unicamp::setting( "header_style_{$header_type}_navigation_background_gradient" );
				$_color_1 = $gradient['from'];
				$_color_2 = $gradient['to'];

				$css .= "
				.header-$header_type .header-bottom {
					background: {$_color_1};
                    background: -webkit-linear-gradient(-136deg, {$_color_2} 0%, {$_color_1} 100%);
                    background: linear-gradient(-136deg, {$_color_2} 0%, {$_color_1} 100%);
				}";
			}

			return $css;
		}

		function sidebar_css() {
			$css = '';

			$page_sidebar1  = Unicamp_Global::instance()->get_sidebar_1();
			$page_sidebar2  = Unicamp_Global::instance()->get_sidebar_2();
			$sidebar_status = Unicamp_Global::instance()->get_sidebar_status();

			if ( 'none' !== $page_sidebar1 ) {

				if ( $sidebar_status === 'both' ) {
					$sidebars_breakpoint = Unicamp::setting( 'both_sidebar_breakpoint' );
				} else {
					$sidebars_breakpoint = Unicamp::setting( 'one_sidebar_breakpoint' );
				}

				$sidebars_below = Unicamp::setting( 'sidebars_below_content_mobile' );

				if ( 'none' !== $page_sidebar2 ) {
					$sidebar_width  = apply_filters( 'unicamp_dual_sidebar_width', Unicamp::setting( 'dual_sidebar_width' ) );
					$sidebar_offset = apply_filters( 'unicamp_dual_sidebar_offset', Unicamp::setting( 'dual_sidebar_offset' ) );

					$content_width = 100 - $sidebar_width * 2;
				} else {
					$sidebar_width  = apply_filters( 'unicamp_one_sidebar_width', Unicamp::setting( 'single_sidebar_width' ) );
					$sidebar_offset = apply_filters( 'unicamp_one_sidebar_offset', Unicamp::setting( 'single_sidebar_offset' ) );

					$content_width = 100 - $sidebar_width;
				}

				$css .= "
				@media (min-width: {$sidebars_breakpoint}px) {
					.page-sidebar {
						flex: 0 0 $sidebar_width%;
						max-width: $sidebar_width%;
					}
					.page-main-content {
						flex: 0 0 $content_width%;
						max-width: $content_width%;
					}
				}";

				if ( is_rtl() ) {
					$css .= "@media (min-width: 1200px) {
						.page-sidebar-left .page-sidebar-inner {
							padding-left: $sidebar_offset;
						}
						.page-sidebar-right .page-sidebar-inner {
							padding-right: $sidebar_offset;
						}
					}";
				} else {
					$css .= "@media (min-width: 1200px) {
						.page-sidebar-left .page-sidebar-inner {
							padding-right: $sidebar_offset;
						}
						.page-sidebar-right .page-sidebar-inner {
							padding-left: $sidebar_offset;
						}
					}";
				}

				$_max_width_breakpoint = $sidebars_breakpoint - 1;

				if ( $sidebars_below === '1' ) {
					$css .= "
					@media (max-width: {$_max_width_breakpoint}px) {
						.page-sidebar {
							margin-top: 80px;
						}
					
						.page-main-content {
							-webkit-order: -1;
							-moz-order: -1;
							order: -1;
						}
					}";
				}
			}

			return $css;
		}

		function title_bar_css() {
			$css = $title_bar_tmp = $overlay_tmp = '';

			$type    = Unicamp_Global::instance()->get_title_bar_type();
			$bg_type = Unicamp::setting( "title_bar_{$type}_background_type" );

			if ( 'gradient' === $bg_type ) {
				$gradient_color = Unicamp::setting( "title_bar_{$type}_background_gradient" );
				$color1         = $gradient_color['color_1'];
				$color2         = $gradient_color['color_2'];

				$css .= "
					.page-title-bar-bg
					{
						background-color: $color1;
						background-image: linear-gradient(-180deg, {$color1} 0%, {$color2} 100%);
					}
				";
			}

			$bg_color   = Unicamp_Helper::get_post_meta( 'page_title_bar_background_color', '' );
			$bg_image   = Unicamp_Helper::get_post_meta( 'page_title_bar_background', '' );
			$bg_overlay = Unicamp_Helper::get_post_meta( 'page_title_bar_background_overlay', '' );

			if ( $bg_color !== '' ) {
				$title_bar_tmp .= "background-color: {$bg_color}!important;";
			}

			if ( '' !== $bg_image ) {
				$title_bar_tmp .= "background-image: url({$bg_image})!important;";
			}

			if ( '' !== $bg_overlay ) {
				$overlay_tmp .= "background-color: {$bg_overlay}!important;";
			}

			if ( '' !== $title_bar_tmp ) {
				$css .= ".page-title-bar-bg{ {$title_bar_tmp} }";
			}

			if ( '' !== $overlay_tmp ) {
				$css .= ".page-title-bar-bg:before{ {$overlay_tmp} }";
			}

			$bottom_spacing = Unicamp_Helper::get_post_meta( 'page_title_bar_bottom_spacing', '' );
			if ( '' !== $bottom_spacing ) {
				$css .= "#page-title-bar{ margin-bottom: {$bottom_spacing}; }";
			}

			return $css;
		}

		function primary_color_css() {
			$color = Unicamp::setting( 'primary_color' );

			$css = "
				::-moz-selection { color: #fff; background-color: $color }
				::selection { color: #fff; background-color: $color }
				.primary-fill-color { fill: $color }
			";

			return $css;
		}

		function light_gallery_css() {
			$css                    = '';
			$primary_color          = Unicamp::setting( 'primary_color' );
			$secondary_color        = Unicamp::setting( 'secondary_color' );
			$cutom_background_color = Unicamp::setting( 'light_gallery_custom_background' );
			$background             = Unicamp::setting( 'light_gallery_background' );

			$tmp = '';

			if ( $background === 'primary' ) {
				$tmp .= "background-color: {$primary_color} !important;";
			} elseif ( $background === 'secondary' ) {
				$tmp .= "background-color: {$secondary_color} !important;";
			} else {
				$tmp .= "background-color: {$cutom_background_color} !important;";
			}

			$css .= ".lg-backdrop { $tmp }";

			return $css;
		}

		function off_canvas_menu_css() {
			$css  = '';
			$type = Unicamp::setting( 'navigation_minimal_01_background_type' );
			if ( $type === 'gradient' ) {
				$gradient = Unicamp::setting( 'navigation_minimal_01_background_gradient_color' );

				$css .= ".popup-canvas-menu {
				    background-color: {$gradient['color_1']};
					background-image: linear-gradient(138deg, {$gradient['color_1']} 0%, {$gradient['color_2']} 100%);
				}";
			}

			return $css;
		}

		function mobile_menu_css() {
			$css  = '';
			$type = Unicamp::setting( 'mobile_menu_background_type' );
			if ( $type === 'gradient' ) {
				$gradient = Unicamp::setting( 'mobile_menu_background_gradient_color' );

				$css .= ".page-mobile-main-menu > .inner {
				    background-color: {$gradient['color_1']};
					background-image: linear-gradient(138deg, {$gradient['color_1']} 0%, {$gradient['color_2']} 100%);
				}";
			} else {
				/**
				 * Lazy load image
				 */
				$background = Unicamp::setting( 'mobile_menu_background' );

				$background_css = '';

				if ( ! empty( $background['background-color'] ) ) {
					$background_css .= "background-color: {$background['background-color']};";
				}

				if ( ! empty( $background['background-image'] ) ) {
					$background_css .= "background-repeat: {$background['background-repeat']};";
					$background_css .= "background-size: {$background['background-size']};";
					$background_css .= "background-attachment: {$background['background-attachment']};";
					$background_css .= "background-position: {$background['background-position']};";
				}

				if ( ! empty( $background_css ) ) {
					$css .= ".page-mobile-main-menu > .inner  { $background_css }";
				}
			}

			return $css;
		}
	}

	Unicamp_Custom_Css::instance()->initialize();
}
