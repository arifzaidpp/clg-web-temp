<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Top_Bar' ) ) {
	class Unicamp_Top_Bar {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {

		}

		/**
		 * @return array List top bar types include id & name.
		 */
		public function get_type() {
			return array(
				'01' => esc_html__( '01', 'unicamp' ),
				'02' => esc_html__( '02', 'unicamp' ),
				'03' => esc_html__( '03', 'unicamp' ),
				'04' => esc_html__( '04', 'unicamp' ),
			);
		}

		/**
		 * @param bool   $default_option Show or hide default select option.
		 * @param string $default_text   Custom text for default option.
		 *
		 * @return array A list of options for select field.
		 */
		public function get_list( $default_option = false, $default_text = '' ) {
			$top_bars = array(
				'none' => esc_html__( 'Hide', 'unicamp' ),
			);

			$top_bars += $this->get_type();

			if ( $default_option === true ) {
				if ( $default_text === '' ) {
					$default_text = esc_html__( 'Default', 'unicamp' );
				}

				$top_bars = array( '' => $default_text ) + $top_bars;
			}

			return $top_bars;
		}

		public function get_support_components() {
			$list = [
				'widget'            => esc_html__( 'Widget', 'unicamp' ),
				'text'              => esc_html__( 'Text', 'unicamp' ),
				'language_switcher' => esc_html__( 'Language Switcher', 'unicamp' ),
				'info_list'         => esc_html__( 'Info List', 'unicamp' ),
				'user_links'        => esc_html__( 'User Links', 'unicamp' ),
				'social_links'      => esc_html__( 'Social Links', 'unicamp' ),
				'secondary_menu'    => esc_html__( 'Secondary Menu', 'unicamp' ),
				'search_popup'      => esc_html__( 'Search Popup', 'unicamp' ),
			];

			$list = apply_filters( 'unicamp_top_bar_support_components', $list );

			return $list;
		}

		/**
		 * Add classes to the top barr.
		 *
		 * @var string $class Custom class.
		 */
		public function get_wrapper_class( $class = '' ) {
			$classes = array( 'page-top-bar' );

			$type = Unicamp_Global::instance()->get_top_bar_type();

			$classes[] = "top-bar-{$type}";

			if ( ! empty( $class ) ) {
				if ( ! is_array( $class ) ) {
					$class = preg_split( '#\s+#', $class );
				}
				$classes = array_merge( $classes, $class );
			} else {
				// Ensure that we always coerce class to being an array.
				$class = array();
			}

			$classes = apply_filters( 'unicamp_top_bar_class', $classes, $class );

			echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
		}

		public function render() {
			$type = Unicamp_Global::instance()->get_top_bar_type();

			if ( 'none' !== $type ) {
				unicamp_load_template( 'top-bar/top-bar', $type );
			}
		}

		public function get_active_components() {
			$type = Unicamp_Global::instance()->get_top_bar_type();

			$layout = Unicamp::setting( "top_bar_style_{$type}_layout" );

			$active_components = [];

			$left_components   = Unicamp::setting( "top_bar_style_{$type}_left_components" );
			$center_components = Unicamp::setting( "top_bar_style_{$type}_center_components" );
			$right_components  = Unicamp::setting( "top_bar_style_{$type}_right_components" );
			$has_left_column   = $has_center_column = $has_right_column = false;

			switch ( $layout ) {
				case '1l':
					$has_left_column = true;
					break;
				case '1c':
					$has_center_column = true;
					break;
				case '1r':
					$has_right_column = true;
					break;
				case '2':
					$has_left_column  = true;
					$has_right_column = true;
					break;
				case '3':
					$has_left_column   = true;
					$has_center_column = true;
					$has_right_column  = true;
					break;
			}

			if ( $has_left_column ) {
				$active_components = array_merge( $active_components, $left_components );
			}

			if ( $has_center_column ) {
				$active_components = array_merge( $active_components, $center_components );
			}

			if ( $has_right_column ) {
				$active_components = array_merge( $active_components, $right_components );
			}

			return $active_components;
		}

		public function print_components( $position = 'left' ) {
			$type       = Unicamp_Global::instance()->get_top_bar_type();
			$components = Unicamp::setting( "top_bar_style_{$type}_{$position}_components" );

			if ( empty( $components ) ) {
				return;
			}

			foreach ( $components as $component ) {
				switch ( $component ) {
					case 'text' :
						$this->print_text();
						break;
					case 'widget' :
						$this->print_widgets();
						break;
					case 'language_switcher' :
						$this->print_language_switcher();
						break;
					case 'info_list' :
						$this->print_info_list();
						break;
					case 'user_links' :
						$this->print_user_links();
						break;
					case 'social_links' :
						$this->print_social_links();
						break;
					case 'secondary_menu':
						$this->print_secondary_menu();
						break;
					case 'search_popup':
						$this->print_search_popup();
						break;
					default:
						do_action( 'unicamp_top_bar_print_components_' . $component );
						break;
				}
			}
		}

		public function print_text() {
			$type = Unicamp_Global::instance()->get_top_bar_type();
			$text = Unicamp::setting( "top_bar_style_{$type}_text" );

			unicamp_load_template( 'top-bar/components/text', null, $args = [ 'text' => $text ] );
		}

		/**
		 * Print WPML switcher html template.
		 *
		 * @var string $class Custom class.
		 */
		public function print_language_switcher() {
			$topbar_type = Unicamp_Global::instance()->get_top_bar_type();

			do_action( 'unicamp_before_add_language_selector_top_bar', $topbar_type, '1' );

			if ( ! defined( 'ICL_SITEPRESS_VERSION' ) ) {
				return;
			}
			?>
			<div id="switcher-language-wrapper" class="switcher-language-wrapper">
				<?php do_action( 'wpml_add_language_selector' ); ?>
			</div>
			<?php
		}

		public function print_button( $type = '01' ) {
			$button_text        = Unicamp::setting( "top_bar_style_{$type}_button_text" );
			$button_link        = Unicamp::setting( "top_bar_style_{$type}_button_link" );
			$button_link_target = Unicamp::setting( "top_bar_style_{$type}_button_link_target" );
			$button_classes     = 'top-bar-button';
			?>
			<?php if ( $button_link !== '' && $button_text !== '' ) : ?>
				<a class="<?php echo esc_attr( $button_classes ); ?>"
				   href="<?php echo esc_url( $button_link ); ?>"
					<?php if ( $button_link_target === '1' ) : ?>
						target="_blank"
					<?php endif; ?>
				>
					<?php echo esc_html( $button_text ); ?>
				</a>
			<?php endif;
		}

		public function print_user_links() {
			unicamp_load_template( 'top-bar/components/user-links' );
		}

		public function print_social_links() {
			unicamp_load_template( 'top-bar/components/socials' );
		}

		public function print_widgets() {
			unicamp_load_template( 'top-bar/components/widgets' );
		}

		public function print_search_popup() {
			unicamp_load_template( 'top-bar/components/search-popup' );
		}

		public function print_secondary_menu() {
			unicamp_load_template( 'navigation-secondary' );
		}

		public function print_info_list() {
			$type      = Unicamp_Global::instance()->get_top_bar_type();
			$info_list = Unicamp::setting( "top_bar_style_{$type}_info_list" );

			if ( ! empty( $info_list ) ) {
				unicamp_load_template( 'top-bar/components/info-list', null, $args = [ 'info_list' => $info_list ] );
			}
		}
	}

	Unicamp_Top_Bar::instance()->initialize();
}
