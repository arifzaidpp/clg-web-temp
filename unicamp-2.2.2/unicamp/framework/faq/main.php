<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_FAQ' ) ) {
	class Unicamp_FAQ {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			require_once UNICAMP_FAQ_DIR . '/template.php';
			require_once UNICAMP_FAQ_DIR . '/query.php';
			require_once UNICAMP_FAQ_DIR . '/sidebar.php';
			require_once UNICAMP_FAQ_DIR . '/archive-faq.php';
			require_once UNICAMP_FAQ_DIR . '/single-faq.php';

			Unicamp_FAQ_Template::instance()->initialize();
			Unicamp_FAQ_Query::instance()->initialize();
			Unicamp_FAQ_Sidebar::instance()->initialize();
			Unicamp_FAQ_Archive::instance()->initialize();
			Unicamp_FAQ_Single::instance()->initialize();
		}

		public function get_post_type() {
			return 'faq';
		}

		public function get_tax_group() {
			return 'faq-group';
		}

		/**
		 * Check if current page is category or tag pages
		 */
		public function is_taxonomy() {
			$taxonomies = get_object_taxonomies( $this->get_post_type() );

			return empty( $taxonomies ) ? false : is_tax( $taxonomies );
		}

		/**
		 * Check if current page is archive pages
		 */
		public function is_archive() {
			return $this->is_taxonomy() || is_post_type_archive( $this->get_post_type() );
		}

		public function is_single() {
			return is_singular( $this->get_post_type() );
		}

		/**
		 * Get all group of current faq.
		 *
		 * @return false|WP_Error|WP_Term[]
		 */
		public function get_the_groups() {
			$terms = get_the_terms( get_the_ID(), $this->get_tax_group() );

			return empty( $terms ) || is_wp_error( $terms ) ? false : $terms;
		}

		/**
		 * Get the first group of current faq post.
		 */
		public function get_the_group() {
			$terms = $this->get_the_groups();

			if ( $terms ) {
				return $terms[0];
			}

			return false;
		}

		/**
		 * @param array $args
		 *
		 * Render first group of current faq post.
		 */
		function the_group( $args = array() ) {
			$term = $this->get_the_group();

			if ( ! $term ) {
				return;
			}

			$defaults = array(
				'classes'    => 'faq-group',
				'show_links' => true,
			);
			$args     = wp_parse_args( $args, $defaults );
			?>
			<div class="<?php echo esc_attr( $args['classes'] ); ?>">
				<?php
				if ( $args['show_links'] ) {
					$link = get_term_link( $term );
					printf( '<a href="%1$s" rel="category tag"><span>%2$s</span></a>', $link, $term->name );
				} else {
					echo "<span>{$term->name}</span>";
				}
				?>
			</div>
			<?php
		}

		/**
		 * Dropdown groups.
		 *
		 * @param array $args Args to control display of dropdown.
		 */
		public function faq_dropdown_groups( $args = array() ) {
			global $wp_query;

			$tax_name = $this->get_tax_group();

			$args = wp_parse_args(
				$args,
				array(
					'pad_counts'         => 1,
					'show_count'         => 1,
					'hierarchical'       => 1,
					'hide_empty'         => 1,
					'show_uncategorized' => 1,
					'orderby'            => 'name',
					'selected'           => isset( $wp_query->query_vars[ $tax_name ] ) ? $wp_query->query_vars[ $tax_name ] : '',
					'show_option_none'   => esc_html__( 'Select a group', 'unicamp' ),
					'option_none_value'  => '',
					'value_field'        => 'slug',
					'taxonomy'           => $tax_name,
					'name'               => $tax_name,
					'class'              => 'dropdown-faq-group',
				)
			);

			if ( 'order' === $args['orderby'] ) {
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = 'order'; // phpcs:ignore
			}

			wp_dropdown_categories( $args );
		}
	}

	Unicamp_FAQ::instance()->initialize();
}
