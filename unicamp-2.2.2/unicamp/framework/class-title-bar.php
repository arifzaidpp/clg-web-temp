<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Title_Bar' ) ) {

	class Unicamp_Title_Bar {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			// Adds custom classes to the array of body classes.
			add_filter( 'body_class', [ $this, 'body_classes' ] );
		}

		public function body_classes( $classes ) {
			$title_bar = Unicamp_Global::instance()->get_title_bar_type();
			$classes[] = "title-bar-{$title_bar}";

			/**
			 * Add class to hide entry title if this title bar has post title also.
			 */
			// Title Bars support heading.
			if ( in_array( $title_bar, [ '01', '02' ], true ) && is_singular() ) {
				$post_type = get_post_type();
				$title     = '';

				switch ( $post_type ) {
					case 'post' :
						$title = Unicamp::setting( 'blog_single_title_bar_title' );
						break;
					case 'product' :
						$title = Unicamp::setting( 'product_single_title_bar_title' );
						break;
				}

				if ( '' === $title ) {
					$classes[] = 'title-bar-has-post-title';
				}
			}

			return $classes;
		}

		public function get_list( $default_option = false, $default_text = '' ) {
			$options = array(
				'none' => esc_html__( 'Hide', 'unicamp' ),
				'01'   => esc_html__( '01 - Grey', 'unicamp' ),
				'02'   => esc_html__( '02 - Cover', 'unicamp' ),
				'03'   => esc_html__( '03 - Minimal - Transparent', 'unicamp' ),
				'04'   => esc_html__( '04 - Minimal - Dark - Overlay', 'unicamp' ),
				'07'   => esc_html__( '07 - Minimal - Light - Overlay', 'unicamp' ),
				'08'   => esc_html__( '08 - Search Form', 'unicamp' ),
			);

			if ( $default_option === true ) {
				if ( $default_text === '' ) {
					$default_text = esc_html__( 'Default', 'unicamp' );
				}

				$options = array( '' => $default_text ) + $options;
			}

			return $options;
		}

		/**
		 * @param mixed string|array $class Extra class
		 */
		public function the_wrapper_class( $class = '' ) {
			$classes = array( 'page-title-bar' );

			if ( ! empty( $class ) ) {
				if ( is_array( $class ) ) {
					$classes[] = implode( ' ', $class );
				} else {
					$classes[] = $class;
				}
			}

			$type = Unicamp_Global::instance()->get_title_bar_type();

			$classes[] = "page-title-bar-{$type}";

			echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
		}

		public function render() {
			$type = Unicamp_Global::instance()->get_title_bar_type();

			if ( 'none' === $type ) {
				return;
			}

			unicamp_load_template( 'title-bar/title-bar', $type );
		}

		public function render_title() {
			$title     = '';
			$title_tag = 'h1';

			if ( is_post_type_archive() ) {
				$title = sprintf( esc_html__( 'Archives: %s', 'unicamp' ), post_type_archive_title( '', false ) );
			} elseif ( is_home() ) {
				$title = Unicamp::setting( 'title_bar_home_title' ) . single_tag_title( '', false );
			} elseif ( is_tag() ) {
				$title = Unicamp::setting( 'title_bar_archive_tag_title' ) . single_tag_title( '', false );
			} elseif ( is_author() ) {
				$title = Unicamp::setting( 'title_bar_archive_author_title' ) . '<span class="vcard">' . get_the_author() . '</span>';
			} elseif ( is_year() ) {
				$title = Unicamp::setting( 'title_bar_archive_year_title' ) . get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'unicamp' ) );
			} elseif ( is_month() ) {
				$title = Unicamp::setting( 'title_bar_archive_month_title' ) . get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'unicamp' ) );
			} elseif ( is_day() ) {
				$title = Unicamp::setting( 'title_bar_archive_day_title' ) . get_the_date( esc_html_x( 'F j, Y', 'daily archives date format', 'unicamp' ) );
			} elseif ( is_search() ) {
				$title = Unicamp::setting( 'title_bar_search_title' ) . '"' . get_search_query() . '"';
			} elseif ( is_category() || is_tax() ) {
				$title = Unicamp::setting( 'title_bar_archive_category_title' ) . single_cat_title( '', false );
			} elseif ( is_singular() ) {
				$title = Unicamp_Helper::get_post_meta( 'page_title_bar_custom_heading', '' );

				if ( '' === $title ) {
					$post_type = get_post_type();
					switch ( $post_type ) {
						case 'post' :
							$title = Unicamp::setting( 'blog_single_title_bar_title' );
							break;
					}
				}

				if ( '' === $title ) {
					$title = get_the_title();
				} else {
					$title_tag = 'h2';
				}
			} else {
				$title = get_the_title();
			}

			$title = apply_filters( 'unicamp_title_bar_heading_text', $title );
			?>

			<?php printf( '<%s class="heading">', $title_tag ); ?>
			<?php echo wp_kses( $title, array(
				'span' => [
					'class' => [],
				],
				'br'   => [],
			) ); ?>
			<?php printf( '</%s>', $title_tag ); ?>

			<?php
		}
	}

	Unicamp_Title_Bar::instance()->initialize();
}
