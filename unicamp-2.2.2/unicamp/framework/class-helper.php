<?php
defined( 'ABSPATH' ) || exit;

/**
 * Helper functions
 */
if ( ! class_exists( 'Unicamp_Helper' ) ) {
	class Unicamp_Helper {

		static $preloader_style = array();

		function __construct() {
			self::$preloader_style = array(
				'rotating-plane'  => esc_attr__( 'Rotating Plane', 'unicamp' ),
				'double-bounce'   => esc_attr__( 'Double Bounce', 'unicamp' ),
				'three-bounce'    => esc_attr__( 'Three Bounce', 'unicamp' ),
				'wave'            => esc_attr__( 'Wave', 'unicamp' ),
				'wandering-cubes' => esc_attr__( 'Wandering Cubes', 'unicamp' ),
				'pulse'           => esc_attr__( 'Pulse', 'unicamp' ),
				'chasing-dots'    => esc_attr__( 'Chasing dots', 'unicamp' ),
				'circle'          => esc_attr__( 'Circle', 'unicamp' ),
				'cube-grid'       => esc_attr__( 'Cube Grid', 'unicamp' ),
				'fading-circle'   => esc_attr__( 'Fading Circle', 'unicamp' ),
				'folding-cube'    => esc_attr__( 'Folding Cube', 'unicamp' ),
				'gif-image'       => esc_attr__( 'Gif Image', 'unicamp' ),
			);
		}

		public static function array_insert( &$array, $position, $insert ) {
			if ( is_int( $position ) ) {
				array_splice( $array, $position, 0, $insert );
			} else {
				$pos = array_search( $position, array_keys( $array ) );

				$array = array_merge(
					array_slice( $array, 0, $pos ),
					$insert,
					array_slice( $array, $pos )
				);
			}
		}

		public static function e( $var = '' ) {
			echo "{$var}";
		}

		public static function get_preloader_list() {
			$list = self::$preloader_style + array( 'random' => esc_attr__( 'Random', 'unicamp' ) );

			return $list;
		}

		public static function get_post_meta( $name, $default = false ) {
			global $unicamp_page_options;

			if ( $unicamp_page_options != false && isset( $unicamp_page_options[ $name ] ) ) {
				return $unicamp_page_options[ $name ];
			}

			return $default;
		}

		public static function get_the_post_meta( $options, $name, $default = false ) {
			if ( $options != false && isset( $options[ $name ] ) ) {
				return $options[ $name ];
			}

			return $default;
		}

		/**
		 * @return array
		 */
		public static function get_list_revslider() {
			global $wpdb;
			$revsliders = array(
				'' => esc_attr__( 'Select a slider', 'unicamp' ),
			);

			if ( function_exists( 'rev_slider_shortcode' ) ) {

				$table_name = $wpdb->prefix . 'revslider_sliders';
				$query      = $wpdb->prepare( "SELECT * FROM $table_name WHERE type != %s ORDER BY title ASC", 'template' );
				$results    = $wpdb->get_results( $query );
				if ( ! empty( $results ) ) {
					foreach ( $results as $result ) {
						$revsliders[ $result->alias ] = $result->title;
					}
				}
			}

			return $revsliders;
		}

		/**
		 * @param bool $default_option
		 *
		 * @return array
		 */
		public static function get_registered_sidebars( $default_option = false, $empty_option = true ) {
			global $wp_registered_sidebars;
			$sidebars = array();
			if ( $empty_option === true ) {
				$sidebars['none'] = esc_html__( 'No Sidebar', 'unicamp' );
			}
			if ( $default_option === true ) {
				$sidebars['default'] = esc_html__( 'Default', 'unicamp' );
			}
			foreach ( $wp_registered_sidebars as $sidebar ) {
				$sidebars[ $sidebar['id'] ] = $sidebar['name'];
			}

			return $sidebars;
		}

		/**
		 * Get list sidebar positions
		 *
		 * @return array
		 */
		public static function get_list_sidebar_positions( $default = false ) {
			$positions = array(
				'left'  => esc_html__( 'Left', 'unicamp' ),
				'right' => esc_html__( 'Right', 'unicamp' ),
			);


			if ( $default === true ) {
				$positions['default'] = esc_html__( 'Default', 'unicamp' );
			}

			return $positions;
		}

		/**
		 * Get content of file
		 *
		 * @param string $path
		 *
		 * @return mixed
		 */
		static function get_file_contents( $path = '' ) {
			$content = '';
			if ( $path !== '' ) {
				global $wp_filesystem;

				require_once ABSPATH . '/wp-admin/includes/file.php';
				WP_Filesystem();

				$path = str_replace( '/', UNICAMP_DS, $path );

				if ( file_exists( $path ) ) {
					$content = $wp_filesystem->get_contents( $path );
				}
			}

			return $content;
		}

		public static function placeholder_avatar_src() {
			$src = UNICAMP_THEME_IMAGE_URI . '/avatar-placeholder.jpg';

			return apply_filters( 'unicamp_placeholder_avatar_src', $src );
		}

		public static function strposa( $haystack, $needle, $offset = 0 ) {
			if ( ! is_array( $needle ) ) {
				$needle = array( $needle );
			}
			foreach ( $needle as $query ) {
				if ( strpos( $haystack, $query, $offset ) !== false ) {
					return true;
				} // stop on first true result
			}

			return false;
		}

		public static function w3c_iframe( $iframe ) {
			$iframe = str_replace( 'frameborder="0"', '', $iframe );
			$iframe = str_replace( 'frameborder="no"', '', $iframe );
			$iframe = str_replace( 'scrolling="no"', '', $iframe );
			$iframe = str_replace( 'gesture="media"', '', $iframe );
			$iframe = str_replace( 'allow="encrypted-media"', '', $iframe );

			return $iframe;
		}

		public static function get_md_media_query() {
			return '@media (max-width: 1199px)';
		}

		public static function get_sm_media_query() {
			return '@media (max-width: 991px)';
		}

		public static function get_xs_media_query() {
			return '@media (max-width: 767px)';
		}

		public static function get_body_font() {
			$font = Unicamp::setting( 'typography_body' );

			if ( isset( $font['font-family'] ) ) {
				return "{$font['font-family']} Helvetica, Arial, sans-serif";
			}

			return 'Helvetica, Arial, sans-serif';
		}

		/**
		 * Check search page has results
		 *
		 * @return boolean true if has any results
		 */
		public static function is_search_has_results() {
			if ( is_search() ) {
				global $wp_query;
				$result = ( 0 != $wp_query->found_posts ) ? true : false;

				return $result;
			}

			return 0 != $GLOBALS['wp_query']->found_posts;
		}

		public static function get_button_typography_css_selector() {
			$default_selectors = '
				button,
				input[type="button"],
				input[type="reset"],
				input[type="submit"],
				.wp-block-button__link,
				.rev-btn,
				.tm-button,
				.button,
				.wc-forward,
				.elementor-button
			';

			$selectors = apply_filters( 'unicamp_customize_output_button_typography_selectors', [ $default_selectors ] );

			return $selectors;
		}

		public static function get_button_css_selector() {
			$default_selectors = '
				button,
				input[type="button"],
				input[type="reset"],
				input[type="submit"],
				.wp-block-button__link,
				.button,
				.wc-forward,
				.button.button-alt:hover,
				.elementor-button
				';

			$selectors = apply_filters( 'unicamp_customize_output_button_selectors', [ $default_selectors ] );

			return $selectors;
		}

		public static function get_button_hover_css_selector() {
			$default_selectors = '
				button:hover,
				input[type="button"]:hover,
				input[type="reset"]:hover,
				input[type="submit"]:hover,
				.wp-block-button__link:hover,
				.button:hover,
				.button:focus,
				.button-alt,
				.wc-forward:hover,
				.wc-forward:focus,
				.elementor-button:hover
				';

			$selectors = apply_filters( 'unicamp_customize_output_button_hover_selectors', [ $default_selectors ] );

			return $selectors;
		}

		public static function is_page_template( $template_file ) {
			$template_full = 'templates/' . $template_file;

			return is_page_template( $template_full );
		}

		public static function calculate_percentage( $value1, $value2 ) {
			$percent = ( $value1 > 0 ) ? ( $value1 * 100 ) / $value2 : 0;

			return round( $percent );
		}

		/**
		 * Format number as float 2 decimals.
		 * Then trim last char 0
		 * For eg:
		 * 0 => 0
		 * 4.50 => 4.5
		 * 4.75 => 4.7.5
		 * 5.00 => 5.0
		 *
		 * @param $number Number to convert.
		 *
		 * @return string Formatted number.
		 */
		public static function number_format_nice_float( $number ) {
			// Not type.
			if ( 0 == $number ) {
				return $number;
			}

			$number = number_format( (float) $number, 2 );

			if ( 4 === strlen( $number ) && '0' === substr( $number, -1 ) ) {
				$number = substr_replace( $number, '', -1 );
			}

			return $number;
		}

		/**
		 * @see wc_clean() Function clone from woocommerce.
		 *
		 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
		 * Non-scalar values are ignored.
		 *
		 * @param string|array $var Data to sanitize.
		 *
		 * @return string|array
		 */
		public static function data_clean( $var ) {
			if ( is_array( $var ) ) {
				return array_map( 'data_clean', $var );
			} else {
				return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
			}
		}

		public static function is_elementor_editor() {
			if ( ! class_exists( '\Elementor\Plugin' ) || ( class_exists( '\Elementor\Plugin' ) && ! \Elementor\Plugin::$instance->preview->is_preview_mode() ) ) {
				return false;
			}

			return true;
		}

		public static function bool_to_str( $check ) {
			return $check ? 'true' : 'false';
		}

		public static function is_demo_site() {
			if ( defined( 'UNICAMP_DEMO_SITE' ) && true === UNICAMP_DEMO_SITE ) {
				return true;
			}

			return false;
		}

		public static function get_failed_demo_code() {
			return [
				'success'  => false,
				'messages' => __( 'This featured is disabled. Please <a href="https://live-unicamp.thememove.com" target="_blank" rel="nofollow">Try It</a> on live site.', 'unicamp' ),
			];
		}

		public static function get_user_social_networks_support() {
			$user_socials = array(
				'twitter'   => array(
					'label'        => esc_html__( 'Twitter', 'unicamp' ),
					'icon_classes' => 'fab fa-twitter',
					'placeholder'  => 'https://twitter.com/username',
				),
				'facebook'  => array(
					'label'        => esc_html__( 'Facebook', 'unicamp' ),
					'icon_classes' => 'fab fa-facebook-f',
					'placeholder'  => 'https://facebook.com/username',
				),
				'instagram' => array(
					'label'        => esc_html__( 'Instagram', 'unicamp' ),
					'icon_classes' => 'fab fa-instagram',
					'placeholder'  => 'https://instagram.com/username',
				),
				'linkedin'  => array(
					'label'        => esc_html__( 'Linkedin', 'unicamp' ),
					'icon_classes' => 'fab fa-linkedin',
					'placeholder'  => 'https://linkedin.com/username',
				),
				'pinterest' => array(
					'label'        => esc_html__( 'Pinterest', 'unicamp' ),
					'icon_classes' => 'fab fa-pinterest',
					'placeholder'  => 'https://pinterest.com/username',
				),
				'youtube'   => array(
					'label'        => esc_html__( 'Youtube', 'unicamp' ),
					'icon_classes' => 'fab fa-youtube',
					'placeholder'  => 'https://youtube.com',
				),
				'github'    => array(
					'label'        => esc_html__( 'Github', 'unicamp' ),
					'icon_classes' => 'fab fa-github',
					'placeholder'  => 'https://github.com/username',
				),
			);

			$user_socials = apply_filters( 'unicamp_user_social_networks', $user_socials );

			return $user_socials;
		}

		public static function get_site_background_options() {
			$options = [
				''     => esc_html__( 'Default', 'unicamp' ),
				'grey' => esc_html__( 'Grey', 'unicamp' ),
			];

			return $options;
		}

		public static function get_site_layout_options() {
			$options = [
				''      => esc_attr__( 'Wide', 'unicamp' ),
				'small' => esc_attr__( 'Small', 'unicamp' ),
			];

			return $options;
		}

		public static function build_extra_terms_query( $query_args, $taxonomies ) {
			if ( empty( $taxonomies ) ) {
				return $query_args;
			}

			$terms       = explode( ', ', $taxonomies );
			$tax_queries = array(); // List of taxonomies.

			if ( ! isset( $query_args['tax_query'] ) ) {
				$query_args['tax_query'] = array();

				foreach ( $terms as $term ) {
					$tmp       = explode( ':', $term );
					$taxonomy  = $tmp[0];
					$term_slug = $tmp[1];
					if ( ! isset( $tax_queries[ $taxonomy ] ) ) {
						$tax_queries[ $taxonomy ] = array(
							'taxonomy' => $taxonomy,
							'field'    => 'slug',
							'terms'    => array( $term_slug ),
						);
					} else {
						$tax_queries[ $taxonomy ]['terms'][] = $term_slug;
					}
				}
				$query_args['tax_query']             = array_values( $tax_queries );
				$query_args['tax_query']['relation'] = 'AND';
			} else {
				foreach ( $terms as $term ) {
					$tmp       = explode( ':', $term );
					$taxonomy  = $tmp[0];
					$term_slug = $tmp[1];

					foreach ( $query_args['tax_query'] as $key => $query ) {
						if ( is_array( $query ) ) {
							if ( $query['taxonomy'] == $taxonomy ) {
								$query_args['tax_query'][ $key ]['terms'][] = $term_slug;
								break;
							} else {
								$query_args['tax_query'][] = array(
									'taxonomy' => $taxonomy,
									'field'    => 'slug',
									'terms'    => array( $term_slug ),
								);
							}
						}
					}
				}
			}

			return $query_args;
		}
	}

	new Unicamp_Helper();
}
