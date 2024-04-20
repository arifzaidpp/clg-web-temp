<?php

namespace Unicamp\Zoom_Meeting;

defined( 'ABSPATH' ) || exit;

class Utils {

	private static $instance = null;

	protected $is_archive = null;
	protected $is_single  = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function define_constants() {
		define( 'UNICAMP_ZOOM_MEETING_DIR', get_template_directory() . UNICAMP_DS . 'video-conferencing-zoom' );
		define( 'UNICAMP_ZOOM_MEETING_CORE_DIR', UNICAMP_ZOOM_MEETING_DIR . UNICAMP_DS . '_classes' );
	}

	public function initialize() {
		// Do nothing if plugin not activated.
		if ( ! $this->is_activated() ) {
			return;
		}

		$this->define_constants();

		require_once UNICAMP_ZOOM_MEETING_CORE_DIR . '/customizer.php';
		require_once UNICAMP_ZOOM_MEETING_CORE_DIR . '/sidebar.php';
		require_once UNICAMP_ZOOM_MEETING_CORE_DIR . '/shortcode.php';
		require_once UNICAMP_ZOOM_MEETING_CORE_DIR . '/template-archive.php';
		require_once UNICAMP_ZOOM_MEETING_CORE_DIR . '/template-single.php';

		add_filter( 'unicamp_customize_output_button_typography_selectors', [
			$this,
			'customize_output_button_typography_selectors',
		] );

		add_filter( 'unicamp_customize_output_button_selectors', [
			$this,
			'customize_output_button_selectors',
		] );

		add_filter( 'unicamp_customize_output_button_hover_selectors', [
			$this,
			'customize_output_button_hover_selectors',
		] );

		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 100 );
	}

	/**
	 * Check plugin activated.
	 *
	 * @return boolean true if plugin activated
	 */
	public function is_activated() {
		if ( defined( 'ZVC_PLUGIN_SLUG' ) ) {
			return true;
		}

		return false;
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
		if ( null === $this->is_archive ) {
			return $this->is_archive = $this->is_taxonomy() || is_post_type_archive( $this->get_post_type() );
		}

		return $this->is_archive;
	}

	public function is_single() {
		if ( null === $this->is_single ) {
			return $this->is_single = is_singular( $this->get_post_type() );
		}

		return $this->is_single;
	}

	public function get_post_type() {
		return 'zoom-meetings';
	}

	public function get_tax_category() {
		return 'zoom-meeting';
	}

	/**
	 * Get all categories.
	 *
	 * @return false|\WP_Error|\WP_Term[]
	 */
	public function get_the_categories() {
		$terms = get_the_terms( get_the_ID(), $this->get_tax_category() );

		return empty( $terms ) || is_wp_error( $terms ) ? false : $terms;
	}

	/**
	 * Get the first group of current faq post.
	 */
	public function get_the_category() {
		$terms = $this->get_the_categories();

		if ( $terms ) {
			return $terms[0];
		}

		return false;
	}

	/**
	 * @param array $args
	 *
	 * Render first category of current zoom meeting post.
	 */
	public function the_category( $args = array() ) {
		$term = $this->get_the_category();

		if ( ! $term ) {
			return;
		}

		$defaults = array(
			'classes'    => 'post-category',
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

	public function frontend_scripts() {
		$min = \Unicamp_Enqueue::instance()->get_min_suffix();

		wp_register_style( 'unicamp-zoom-meetings', UNICAMP_THEME_URI . "/video-conferencing-zoom$min.css", null, null );

		wp_enqueue_style( 'unicamp-zoom-meetings' );

		if ( $this->is_archive() ) {
			wp_enqueue_script( 'unicamp-grid-layout' );
		}
	}

	public function customize_output_button_typography_selectors( $selectors ) {
		$new_selectors = [ '.dpn-zvc-single-content-wrapper .dpn-zvc-sidebar-wrapper .dpn-zvc-sidebar-box .join-links .btn' ];

		$final_selectors = array_merge( $selectors, $new_selectors );

		return $final_selectors;
	}

	public function customize_output_button_selectors( $selectors ) {
		$new_selectors = [ '.dpn-zvc-single-content-wrapper .dpn-zvc-sidebar-wrapper .dpn-zvc-sidebar-box .join-links .btn' ];

		$final_selectors = array_merge( $selectors, $new_selectors );

		return $final_selectors;
	}

	public function customize_output_button_hover_selectors( $selectors ) {
		$new_selectors = [ '.dpn-zvc-single-content-wrapper .dpn-zvc-sidebar-wrapper .dpn-zvc-sidebar-box .join-links .btn:hover' ];

		$final_selectors = array_merge( $selectors, $new_selectors );

		return $final_selectors;
	}

	public function enqueue_scripts() {
		wp_register_script( 'unicamp-zoom-meeting-countdown', UNICAMP_THEME_ASSETS_URI . '/js/shortcodes/shortcode-zoom-meeting.js', [
			'jquery',
			'countdown',
		], '1.0.0', true );
	}

	/**
	 * @see \Zoom_Video_Conferencing_Shorcodes::fetch_meeting()
	 * @see \Zoom_Video_Conferencing_Api::instance() zoom_conference()
	 * Get Meeting INFO
	 *
	 * @param $meeting_id
	 *
	 * @return bool|mixed|null
	 */
	public function fetch_meeting( $meeting_id ) {
		$transient_name = "zoom-us-fetch-meeting-id-{$meeting_id}";

		$meeting = get_transient( $transient_name );

		if ( false === $meeting ) {
			$meeting = json_decode( zoom_conference()->getMeetingInfo( $meeting_id ) );

			if ( ! empty( $meeting->error ) ) {
				return false;
			}

			set_transient( $transient_name, $meeting, apply_filters( 'unicamp_zoom_us_fetch_meeting_cache_time', DAY_IN_SECONDS * 1 ) );
		}

		return $meeting;
	}

	/**
	 * @see    video_conference_zoom_shortcode_join_link()
	 * Generate join links
	 *
	 * @param $zoom_meetings
	 */
	public function zoom_shortcode_join_link( $zoom_meetings ) {
		if ( empty( $zoom_meetings ) ) {
			echo "<p>" . esc_html__( 'Meeting is not defined. Try updating this meeting', 'unicamp' ) . "</p>";

			return;
		}

		$now               = new \DateTime( 'now -1 hour', new \DateTimeZone( $zoom_meetings->timezone ) );
		$closest_occurence = false;
		if ( ! empty( $zoom_meetings->type ) && $zoom_meetings->type === 8 && ! empty( $zoom_meetings->occurrences ) ) {
			foreach ( $zoom_meetings->occurrences as $occurrence ) {
				if ( $occurrence->status === "available" ) {
					$start_date = new \DateTime( $occurrence->start_time, new \DateTimeZone( $zoom_meetings->timezone ) );
					if ( $start_date >= $now ) {
						$closest_occurence = $occurrence->start_time;
						break;
					}
				}
			}
		} else if ( empty( $zoom_meetings->occurrences ) ) {
			$zoom_meetings->start_time = false;
		} else if ( ! empty( $zoom_meetings->type ) && $zoom_meetings->type === 3 ) {
			$zoom_meetings->start_time = false;
		}

		$start_time = ! empty( $closest_occurence ) ? $closest_occurence : $zoom_meetings->start_time;
		$start_time = new \DateTime( $start_time, new \DateTimeZone( $zoom_meetings->timezone ) );
		$start_time->setTimezone( new \DateTimeZone( $zoom_meetings->timezone ) );
		if ( $now <= $start_time ) {
			unset( $GLOBALS['meetings'] );

			if ( ! empty( $zoom_meetings->password ) ) {
				$browser_join = vczapi_get_browser_join_shortcode( $zoom_meetings->id, $zoom_meetings->password, true );
			} else {
				$browser_join = vczapi_get_browser_join_shortcode( $zoom_meetings->id, false, true );
			}

			$join_url            = ! empty( $zoom_meetings->encrypted_password ) ? vczapi_get_pwd_embedded_join_link( $zoom_meetings->join_url, $zoom_meetings->encrypted_password ) : $zoom_meetings->join_url;
			$GLOBALS['meetings'] = array(
				'join_uri'    => apply_filters( 'vczoom_join_meeting_via_app_shortcode', $join_url, $zoom_meetings ),
				'browser_url' => apply_filters( 'vczoom_join_meeting_via_browser_disable', $browser_join ),
			);
			vczapi_get_template( 'shortcode/tm-join-links.php', true, false );
		}
	}
}

Utils::instance()->initialize();
