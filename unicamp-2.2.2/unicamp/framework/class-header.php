<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Header' ) ) {

	class Unicamp_Header {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_ajax_unicamp_actions', [ $this, 'unicamp_actions' ] );
			add_action( 'wp_ajax_nopriv_unicamp_actions', [ $this, 'unicamp_actions' ] );
		}

		public function unicamp_actions() {
			$id     = $_POST['cat_id'];
			$action = $_POST['action'];

			$query_args = array(
				'post_type'      => Unicamp_Tutor::instance()->get_course_type(),
				'orderby'        => 'date',
				'order'          => 'DESC',
				'posts_per_page' => 6,
				'no_found_rows'  => true,
				'tax_query'      => array(
					array(
						'taxonomy' => Unicamp_Tutor::instance()->get_tax_category(),
						'field'    => 'id',
						'terms'    => $id,
					),
				),
			);

			$query    = new WP_Query( $query_args );
			$response = [];
			ob_start();

			if ( $query->have_posts() ) {
				set_query_var( 'unicamp_query', $query );
				get_template_part( 'loop/menu/category' );
				wp_reset_postdata();
			} else {
				get_template_part( 'loop/menu/content-none' );
			}

			$template             = ob_get_clean();
			$template             = preg_replace( '~>\s+<~', '><', $template );
			$response['template'] = $template;

			echo json_encode( $response );

			wp_die();
		}

		/**
		 * @return array List header types include id & name.
		 */
		public function get_type() {
			return array(
				'01' => esc_html__( 'Style 01', 'unicamp' ),
				'02' => esc_html__( 'Style 02', 'unicamp' ),
				'03' => esc_html__( 'Style 03', 'unicamp' ),
				'04' => esc_html__( 'Style 04', 'unicamp' ),
				'05' => esc_html__( 'Style 05', 'unicamp' ),
				'06' => esc_html__( 'Style 06', 'unicamp' ),
				'07' => esc_html__( 'Style 07', 'unicamp' ),
				'08' => esc_html__( 'Style 08', 'unicamp' ),
			);
		}

		/**
		 * @param bool   $default_option Show or hide default select option.
		 * @param string $default_text   Custom text for default option.
		 *
		 * @return array A list of options for select field.
		 */
		public function get_list( $default_option = false, $default_text = '' ) {
			$headers = array(
				'none' => esc_html__( 'Hide', 'unicamp' ),
			);

			$headers += $this->get_type();

			if ( $default_option === true ) {
				if ( $default_text === '' ) {
					$default_text = esc_html__( 'Default', 'unicamp' );
				}

				$headers = array( '' => $default_text ) + $headers;
			}

			return $headers;
		}

		/**
		 * Get list of button style option for customizer.
		 *
		 * @return array
		 */
		public function get_button_style() {
			return array(
				'flat'         => esc_attr__( 'Flat', 'unicamp' ),
				'border'       => esc_attr__( 'Border', 'unicamp' ),
				'thick-border' => esc_attr__( 'Thick Border', 'unicamp' ),
			);
		}

		/**
		 * Get list of button skin option for customizer.
		 *
		 * @return array
		 */
		public function get_button_skin_options() {
			return array(
				''           => esc_attr__( 'Default', 'unicamp' ),
				'grey'       => esc_attr__( 'Grey', 'unicamp' ),
				'white'      => esc_attr__( 'White', 'unicamp' ),
				'grey-white' => esc_attr__( 'Grey White', 'unicamp' ),
			);
		}

		public function get_button_kirki_output( $header_style, $header_skin, $hover = false ) {
			$prefix_selector = ".header-{$header_style}.header-{$header_skin} ";

			if ( $hover ) {
				$button_selector    = $prefix_selector . ".header-button:hover";
				$button_bg_selector = $prefix_selector . ".header-button:after";
			} else {
				$button_selector    = $prefix_selector . ".header-button";
				$button_bg_selector = $prefix_selector . ".header-button:before";
			}

			return array(
				array(
					'choice'   => 'color',
					'property' => 'color',
					'element'  => $button_selector,
				),
				array(
					'choice'   => 'border',
					'property' => 'border-color',
					'element'  => $button_selector,
				),
				array(
					'choice'   => 'background',
					'property' => 'background',
					'element'  => $button_bg_selector,
				),
			);
		}

		public function get_search_form_kirki_output( $header_style, $header_skin, $hover = false ) {
			$prefix_selector = ".header-{$header_style}.header-{$header_skin} ";
			$scheme_selector = '';
			$field_selector  = '.search-field';

			if ( $hover ) {
				$field_selector .= ':focus';
			}

			$form_selector = $scheme_selector . $prefix_selector . $field_selector;

			return array(
				array(
					'choice'   => 'color',
					'property' => 'color',
					'element'  => $form_selector,
				),
				array(
					'choice'   => 'border',
					'property' => 'border-color',
					'element'  => $form_selector,
				),
				array(
					'choice'   => 'background',
					'property' => 'background',
					'element'  => $form_selector,
				),
			);
		}

		/**
		 * Add classes to the header.
		 *
		 * @var string $class Custom class.
		 */
		public function get_wrapper_class( $class = '' ) {
			$classes = array( 'page-header' );

			$header_type    = Unicamp_Global::instance()->get_header_type();
			$header_overlay = Unicamp_Global::instance()->get_header_overlay();
			$header_skin    = Unicamp_Global::instance()->get_header_skin();

			$classes[] = "header-{$header_type}";
			$classes[] = "header-{$header_skin}";

			if ( '1' === $header_overlay ) {
				$classes[] = 'header-layout-fixed';
			}

			$classes[] = 'nav-links-hover-style-01';

			$_sticky_logo = Unicamp::setting( 'header_sticky_logo' );
			$classes[]    = "header-sticky-$_sticky_logo-logo";

			if ( ! empty( $class ) ) {
				if ( ! is_array( $class ) ) {
					$class = preg_split( '#\s+#', $class );
				}
				$classes = array_merge( $classes, $class );
			} else {
				// Ensure that we always coerce class to being an array.
				$class = array();
			}

			$classes = apply_filters( 'unicamp_header_class', $classes, $class );

			echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
		}

		/**
		 * Print WPML switcher html template.
		 *
		 * @var string $class Custom class.
		 */
		public function print_language_switcher() {
			$header_type = Unicamp_Global::instance()->get_header_type();
			$enabled     = Unicamp::setting( "header_style_{$header_type}_language_switcher_enable" );

			do_action( 'unicamp_before_add_language_selector_header', $header_type, $enabled );

			if ( $enabled !== '1' || ! defined( 'ICL_SITEPRESS_VERSION' ) ) {
				return;
			}
			?>
			<div id="switcher-language-wrapper" class="switcher-language-wrapper">
				<?php do_action( 'wpml_add_language_selector' ); ?>
			</div>
			<?php
		}

		public function print_social_networks( $args = array() ) {
			$header_type   = Unicamp_Global::instance()->get_header_type();
			$social_enable = Unicamp::setting( "header_style_{$header_type}_social_networks_enable" );

			if ( '1' !== $social_enable ) {
				return;
			}

			$defaults = array(
				'style' => 'icons',
			);

			$args       = wp_parse_args( $args, $defaults );
			$el_classes = 'header-social-networks';

			if ( ! empty( $args['style'] ) ) {
				$el_classes .= " style-{$args['style']}";
			}
			?>
			<div class="<?php echo esc_attr( $el_classes ); ?>">
				<div class="inner">
					<?php
					$defaults = array(
						'tooltip_position' => 'bottom-left',
					);

					$args = wp_parse_args( $args, $defaults );

					Unicamp_Templates::social_icons( $args );
					?>
				</div>
			</div>
			<?php
		}

		public function print_widgets() {
			$header_type = Unicamp_Global::instance()->get_header_type();

			$enabled = Unicamp::setting( "header_style_{$header_type}_widgets_enable" );
			if ( '1' === $enabled ) {
				unicamp_load_template( 'header/components/widgets' );
			}
		}

		public function print_search() {
			$header_type = Unicamp_Global::instance()->get_header_type();
			$search_type = Unicamp::setting( "header_style_{$header_type}_search_enable" );

			if ( 'inline' === $search_type ) {
				unicamp_load_template( 'header/components/search-form' );
			} elseif ( 'popup' === $search_type ) {
				unicamp_load_template( 'header/components/search-popup' );
			}
		}

		public function print_notification() {
			$header_type  = Unicamp_Global::instance()->get_header_type();
			$component_on = Unicamp::setting( "header_style_{$header_type}_notification_enable" );

			if ( ! is_user_logged_in() || '1' !== $component_on ) {
				return;
			}

			if ( ! function_exists( 'bp_is_active' ) || ! bp_is_active( 'notifications' ) ) {
				return;
			}

			unicamp_load_template( 'header/components/notification' );
		}

		public function print_category_menu() {
			$header_type     = Unicamp_Global::instance()->get_header_type();
			$category_enable = Unicamp::setting( "header_style_{$header_type}_category_menu_enable" );

			if ( '1' !== $category_enable ) {
				return;
			}

			unicamp_load_template( 'header/components/category-menu' );
		}

		/**
		 * Print login button + register button.
		 * If logged in then print profile & logout instead of.
		 */
		public function print_user_buttons() {
			$header_type     = Unicamp_Global::instance()->get_header_type();
			$user_buttons_on = Unicamp::setting( "header_style_{$header_type}_login_enable" );

			if ( '1' !== $user_buttons_on ) {
				return;
			}

			unicamp_load_template( 'header/components/user-buttons' );
		}

		/**
		 * Other style for user links
		 *
		 * @see Unicamp_Header::print_user_buttons()
		 */
		public function print_user_links_box() {
			$header_type     = Unicamp_Global::instance()->get_header_type();
			$user_buttons_on = Unicamp::setting( "header_style_{$header_type}_login_enable" );

			if ( '1' !== $user_buttons_on ) {
				return;
			}

			unicamp_load_template( 'header/components/user-links-box' );
		}

		public function print_contact_info_box() {
			$header_type     = Unicamp_Global::instance()->get_header_type();
			$contact_info_on = Unicamp::setting( "header_style_{$header_type}_contact_info_enable" );

			if ( '1' !== $contact_info_on ) {
				return;
			}

			unicamp_load_template( 'header/components/contact-info-box' );
		}

		public function print_button( $args = array() ) {
			$header_type = Unicamp_Global::instance()->get_header_type();

			$button_style        = Unicamp::setting( "header_style_{$header_type}_button_style" );
			$button_text         = Unicamp::setting( "header_style_{$header_type}_button_text" );
			$button_link         = Unicamp::setting( "header_style_{$header_type}_button_link" );
			$button_link_target  = Unicamp::setting( "header_style_{$header_type}_button_link_target" );
			$button_link_rel     = Unicamp::setting( "header_style_{$header_type}_button_link_rel" );
			$button_classes      = 'tm-button';
			$sticky_button_style = Unicamp::setting( "header_sticky_button_style" );

			$icon_class = Unicamp::setting( "header_style_{$header_type}_button_icon" );
			$icon_align = 'right';

			if ( $icon_class !== '' ) {
				$button_classes .= ' has-icon icon-right';
			}

			$defaults = array(
				'extra_class' => '',
				'style'       => '',
				'size'        => 'nm',
			);

			$args = wp_parse_args( $args, $defaults );

			if ( $args['extra_class'] !== '' ) {
				$button_classes .= " {$args['extra_class']}";
			}

			$header_button_classes = $button_classes . " tm-button-{$args['size']} header-button";
			$sticky_button_classes = $button_classes . ' tm-button-xs header-sticky-button';

			$header_button_classes .= " style-{$button_style}";
			$sticky_button_classes .= " style-{$sticky_button_style}";
			?>
			<?php if ( $button_link !== '' && $button_text !== '' ) : ?>

				<?php ob_start(); ?>

				<?php if ( $icon_class !== '' && $icon_align === 'right' ) { ?>
					<span class="button-icon">
						<i class="<?php echo esc_attr( $icon_class ); ?>"></i>
					</span>
				<?php } ?>

				<span class="button-text">
					<?php echo esc_html( $button_text ); ?>
				</span>

				<?php if ( $icon_class !== '' && $icon_align === 'right' ) { ?>
					<span class="button-icon">
						<i class="<?php echo esc_attr( $icon_class ); ?>"></i>
					</span>
				<?php } ?>

				<?php $button_content_html = ob_get_clean(); ?>

				<div class="header-buttons">
					<a class="<?php echo esc_attr( $header_button_classes ); ?>"
					   href="<?php echo esc_url( $button_link ); ?>"

						<?php if ( '1' === $button_link_target ) : ?>
							target="_blank"
						<?php endif; ?>

						<?php if ( ! empty ( $button_link_rel ) ) : ?>
							rel="<?php echo esc_attr( $button_link_rel ); ?>"
						<?php endif; ?>
					>
						<?php echo '' . $button_content_html; ?>
					</a>
					<a class="<?php echo esc_attr( $sticky_button_classes ); ?>"
					   href="<?php echo esc_url( $button_link ); ?>"

						<?php if ( '1' === $button_link_target ) : ?>
							target="_blank"
						<?php endif; ?>

						<?php if ( ! empty ( $button_link_rel ) ) : ?>
							rel="<?php echo esc_attr( $button_link_rel ); ?>"
						<?php endif; ?>
					>
						<?php echo '' . $button_content_html; ?>
					</a>
				</div>
			<?php endif;
		}

		public function print_buttons( $args = array() ) {
			$header_type = Unicamp_Global::instance()->get_header_type();
			$buttons     = Unicamp::setting( "header_style_{$header_type}_buttons" );

			if ( empty( $buttons ) ) {
				return;
			}

			echo '<div class="header-buttons">';

			foreach ( $buttons as $button ) {
				$button_style     = ! empty( $button['style'] ) ? $button['style'] : 'flat';
				$button_skin      = isset( $button['skin'] ) ? $button['skin'] : '';
				$button_text      = isset( $button['text'] ) ? $button['text'] : '';
				$button_icon      = isset( $button['icon_class'] ) ? $button['icon_class'] : '';
				$link_url         = isset( $button['link_url'] ) ? $button['link_url'] : '';
				$link_is_external = isset( $button['link_is_external'] ) && '1' === $button['link_is_external'] ? true : false;
				$link_nofollow    = isset( $button['link_nofollow'] ) && '1' === $button['link_nofollow'] ? true : false;

				if ( empty( $button_text ) && empty( $button_icon ) ) {
					continue;
				}

				$button_css_classes = 'header-button';

				if ( ! empty( $button_skin ) ) {
					$button_css_classes .= ' button-' . $button_skin;
				}

				$defaults = array(
					'link'        => [
						'url'         => $link_url,
						'is_external' => $link_is_external,
						'nofollow'    => $link_nofollow,
					],
					'text'        => $button_text,
					'icon'        => $button_icon,
					'style'       => $button_style,
					'size'        => 'nm',
					'extra_class' => $button_css_classes,
					'wrapper'     => false,
				);

				$button_args = wp_parse_args( $args, $defaults );

				Unicamp_Templates::render_button( $button_args );
			}

			echo '</div>';
		}

		public function print_open_mobile_menu_button() {
			unicamp_load_template( 'header/components/open-mobile-menu-button' );
		}

		public function print_more_tools_button() {
			unicamp_load_template( 'header/components/more-tools-button' );
		}

		public function print_secondary_menu() {
			$header_type       = Unicamp_Global::instance()->get_header_type();
			$secondary_menu_on = Unicamp::setting( "header_style_{$header_type}_secondary_menu_enable" );

			if ( '1' !== $secondary_menu_on ) {
				return;
			}

			unicamp_load_template( 'navigation-secondary' );
		}

		public function print_open_canvas_menu_button( $args = array() ) {
			if ( ! has_nav_menu( 'off_canvas' ) ) {
				return;
			}

			$defaults = array(
				'extra_class' => '',
				'style'       => '01',
			);
			$args     = wp_parse_args( $args, $defaults );

			unicamp_load_template( 'header/components/open-canvas-menu-button', null, $args );
		}
	}

	Unicamp_Header::instance()->initialize();
}
