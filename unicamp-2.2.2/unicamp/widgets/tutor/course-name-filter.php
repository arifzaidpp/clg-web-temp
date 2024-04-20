<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_WP_Widget_Course_Name_Filter' ) ) {
	class Unicamp_WP_Widget_Course_Name_Filter extends Unicamp_Course_Layered_Nav_Base {

		public function __construct() {
			$this->widget_id          = 'unicamp-wp-widget-course-name-filter';
			$this->widget_cssclass    = 'unicamp-wp-widget-course-name-filter';
			$this->widget_name        = esc_html__( '[Unicamp] Course Name Filter', 'unicamp' );
			$this->widget_description = esc_html__( 'Show form to narrow courses by course name.', 'unicamp' );
			$this->settings           = array(
				'title' => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Search', 'unicamp' ),
					'label' => esc_html__( 'Title', 'unicamp' ),
				),
			);

			parent::__construct();

			wp_register_script( 'unicamp-course-search-form', UNICAMP_THEME_ASSETS_URI . '/js/tutor/course-search-form.js', [ 'jquery' ], UNICAMP_THEME_VERSION, true );

			if ( is_customize_preview() ) {
				wp_enqueue_script( 'unicamp-course-search-form' );
			}
		}

		public function widget( $args, $instance ) {
			wp_enqueue_script( 'unicamp-course-search-form' );

			$this->widget_start( $args, $instance );

			$this->search_form( $instance );

			$this->widget_end( $args );
		}

		protected function search_form( $instance ) {
			$filter_by_key = 'filter_name';
			$base_link     = Unicamp_Tutor::instance()->get_course_listing_page_url();
			$base_link     = remove_query_arg( $filter_by_key, $base_link );

			$current_value = isset( $_GET[ $filter_by_key ] ) ? Unicamp_Helper::data_clean( $_GET[ $filter_by_key ] ) : '';
			?>
			<form role="search" method="get" class="search-form"
			      action="<?php echo esc_url( $base_link ); ?>">
				<label class="screen-reader-text"
				       for="course-search-field"><?php esc_html_e( 'Search for:', 'unicamp' ); ?></label>
				<input type="search"
				       id="course-search-field"
				       class="search-field"
				       placeholder="<?php echo esc_attr_x( 'Find your course', 'placeholder', 'unicamp' ); ?>"
				       value="<?php echo esc_attr( $current_value ); ?>"
				       name="<?php echo esc_attr( $filter_by_key ); ?>"/>
				<button type="submit" class="search-submit">
					<span class="search-btn-icon far fa-search"></span>
					<span class="search-btn-text">
						<?php echo esc_html_x( 'Search', 'submit button', 'unicamp' ); ?>
					</span>
				</button>
			</form>
			<?php
		}
	}
}

