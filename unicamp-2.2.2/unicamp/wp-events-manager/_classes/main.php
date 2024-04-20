<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Event' ) ) {
	class Unicamp_Event {

		protected static $instance = null;

		const    POST_TYPE                = 'tp_event';
		const    TAXONOMY_CATEGORY        = 'tp_event_category';
		const    TAXONOMY_TAGS            = 'tp_event_tag';
		const    TAXONOMY_SPEAKER         = 'event-speaker';
		const    POST_META_STATUS         = 'tp_event_status';
		const    POST_META_LOCATION       = 'tp_event_location';
		const    POST_META_SHORT_LOCATION = 'tp_event_short_location';
		const    POST_META_ORGANISER      = 'tp_event_organiser';

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function define_constants() {
			define( 'UNICAMP_EVENT_MANAGER_DIR', get_template_directory() . UNICAMP_DS . 'wp-events-manager' );
			define( 'UNICAMP_EVENT_MANAGER_CORE_DIR', UNICAMP_EVENT_MANAGER_DIR . UNICAMP_DS . '_classes' );
			define( 'UNICAMP_EVENT_MANAGER_WIDGETS_DIR', UNICAMP_EVENT_MANAGER_CORE_DIR . UNICAMP_DS . 'widgets' );
		}

		public function initialize() {
			// Do nothing if plugin not activated.
			if ( ! $this->is_activated() ) {
				return;
			}

			$this->define_constants();

			unicamp_require_once( UNICAMP_EVENT_MANAGER_CORE_DIR . '/template.php' );
			unicamp_require_once( UNICAMP_EVENT_MANAGER_CORE_DIR . '/query.php' );
			unicamp_require_once( UNICAMP_EVENT_MANAGER_CORE_DIR . '/enqueue.php' );
			unicamp_require_once( UNICAMP_EVENT_MANAGER_CORE_DIR . '/sidebar.php' );
			unicamp_require_once( UNICAMP_EVENT_MANAGER_CORE_DIR . '/archive-event.php' );
			unicamp_require_once( UNICAMP_EVENT_MANAGER_CORE_DIR . '/single-event.php' );
			unicamp_require_once( UNICAMP_EVENT_MANAGER_CORE_DIR . '/meta-box.php' );
			unicamp_require_once( UNICAMP_EVENT_MANAGER_CORE_DIR . '/widgets.php' );

			add_filter( 'tp_event_price_format', [ $this, 'add_wrapper_decimals_separator' ], 10, 3 );

			add_filter( 'thimpress_event_l18n', [ $this, 'change_countdown_title' ] );
		}

		public function get_event_type() {
			return self::POST_TYPE;
		}

		public function get_tax_category() {
			return self::TAXONOMY_CATEGORY;
		}

		public function get_tax_tag() {
			return self::TAXONOMY_TAGS;
		}

		public function get_tax_speaker() {
			return self::TAXONOMY_SPEAKER;
		}

		/**
		 * Check The Events Calendar plugin activated.
		 *
		 * @return boolean true if plugin activated
		 */
		public function is_activated() {
			if ( class_exists( 'WPEMS' ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Check if current page is category or tag pages
		 */
		public function is_taxonomy() {
			$taxonomies = get_object_taxonomies( self::POST_TYPE );

			return empty( $taxonomies ) ? false : is_tax( $taxonomies );
		}

		/**
		 * Check if current page is archive pages
		 */
		public function is_archive() {
			return $this->is_taxonomy() || is_post_type_archive( self::POST_TYPE );
		}

		public function is_single() {
			return is_singular( self::POST_TYPE );
		}

		public function get_filtering_type_options() {
			return [
				''          => esc_html__( 'All', 'unicamp' ),
				'happening' => esc_html__( 'Happening', 'unicamp' ),
				'upcoming'  => esc_html__( 'Upcoming', 'unicamp' ),
				'expired'   => esc_html__( 'Expired', 'unicamp' ),
			];
		}

		public function get_selected_type_option() {
			$type = isset( $_GET['filter_type'] ) ? Unicamp_Helper::data_clean( $_GET['filter_type'] ) : '';

			return $type;
		}

		public function add_wrapper_decimals_separator( $price_format, $price, $with_currency ) {
			$price_decimals_separator = wpems_get_option( 'currency_separator', ',' );

			if ( ! empty( $price_decimals_separator ) ) {
				$price_format = str_replace( $price_decimals_separator, '<span class="decimals-separator">' . $price_decimals_separator, $price_format );
				$price_format .= '</span>';
			}

			return $price_format;
		}

		public function change_countdown_title( $js_vars ) {
			$js_vars['l18n']['labels'] = [
				esc_html__( 'Years', 'unicamp' ),
				esc_html__( 'Months', 'unicamp' ),
				esc_html__( 'Weeks', 'unicamp' ),
				esc_html__( 'Days', 'unicamp' ),
				esc_html__( 'Hours', 'unicamp' ),
				esc_html__( 'Mins', 'unicamp' ),
				esc_html__( 'Secs', 'unicamp' ),
			];

			$js_vars['l18n']['labels1'] = [
				esc_html__( 'Year', 'unicamp' ),
				esc_html__( 'Month', 'unicamp' ),
				esc_html__( 'Week', 'unicamp' ),
				esc_html__( 'Day', 'unicamp' ),
				esc_html__( 'Hour', 'unicamp' ),
				esc_html__( 'Min', 'unicamp' ),
				esc_html__( 'Sec', 'unicamp' ),
			];

			return $js_vars;
		}

		public function get_the_speakers() {
			$terms = get_the_terms( get_the_ID(), self::TAXONOMY_SPEAKER );

			if ( empty( $terms ) || is_wp_error( $terms ) ) {
				return false;
			}

			return $terms;
		}

		/**
		 * @param array $args = [
		 *                    string $classes Custom css class
		 *                    string $separator Separator between links
		 *                    boolean $show_links
		 *                    int $number Number of links to show
		 *                    ]
		 */
		function event_loop_category( $args = array() ) {
			$terms = get_the_terms( get_the_ID(), $this->get_tax_category() );

			if ( empty( $terms ) || is_wp_error( $terms ) ) {
				return;
			}

			$defaults = array(
				'classes'    => 'event-category',
				'separator'  => '',
				'show_links' => true,
				'number'     => -1,
			);
			$args     = wp_parse_args( $args, $defaults );
			?>
			<div class="<?php echo esc_attr( $args['classes'] ); ?>">
				<?php
				$loop_count = 0;
				foreach ( $terms as $category ) {
					if ( $loop_count > 0 ) {
						echo "{$args['separator']}";
					}

					$cat_html = '<span class="cat-name">' . $category->name . '</span>';

					if ( true === $args['show_links'] ) {
						printf( '<a href="%1$s" rel="category tag">%2$s</a>', esc_url( get_term_link( $category->term_id ) ), $cat_html );
					} else {
						echo "{$cat_html}";
					}

					$loop_count++;

					if ( $args['number'] > 0 && $loop_count >= $args['number'] ) {
						break;
					}
				}
				?>
			</div>
			<?php
		}

		public function entry_categories( $args = array() ) {
			$terms = get_the_terms( get_the_ID(), $this->get_tax_category() );

			if ( empty( $terms ) || is_wp_error( $terms ) ) {
				return;
			}

			$defaults = array(
				'classes'   => 'entry-event-categories',
				'separator' => ' / ',
			);
			$args     = wp_parse_args( $args, $defaults );
			?>
			<div class="<?php echo esc_attr( $args['classes'] ); ?>">
				<?php
				$loop_count = 0;

				foreach ( $terms as $term ) {
					if ( $loop_count > 0 ) {
						echo "{$args['separator']}";
					}

					$link = get_term_link( $term );

					$cat_html = '<span class="cat-name">' . $term->name . '</span>';

					printf( '<a href="%1$s" rel="category tag">%2$s</a>', esc_url( $link ), $cat_html );

					$loop_count++;
				}
				?>
			</div>
			<?php
		}
	}

	Unicamp_Event::instance()->initialize();
}
