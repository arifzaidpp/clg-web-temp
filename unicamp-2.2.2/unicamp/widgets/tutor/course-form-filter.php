<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_WP_Widget_Course_Form_Filter' ) ) {
	class Unicamp_WP_Widget_Course_Form_Filter extends Unicamp_Course_Layered_Nav_Base {

		public function __construct() {
			$this->widget_id          = 'unicamp-wp-widget-course-form-filter';
			$this->widget_cssclass    = 'unicamp-wp-widget-course-form-filter';
			$this->widget_name        = esc_html__( '[Unicamp] Course Form Filter', 'unicamp' );
			$this->widget_description = esc_html__( 'Display a form to filter courses with some criteria.', 'unicamp' );
			$this->settings           = array(
				'title' => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Find courses', 'unicamp' ),
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

			$base_link = Unicamp_Tutor::instance()->get_course_listing_page_url();

			$current_category = isset( $_GET['filter_course-category'] ) ? Unicamp_Helper::data_clean( wp_unslash( $_GET['filter_course-category'] ) ) : '';
			$current_location = isset( $_GET['filter_course-location'] ) ? Unicamp_Helper::data_clean( wp_unslash( $_GET['filter_course-location'] ) ) : '';
			$current_level    = isset( $_GET['level'] ) ? Unicamp_Helper::data_clean( wp_unslash( $_GET['level'] ) ) : '';
			$current_name     = isset( $_GET['filter_name'] ) ? Unicamp_Helper::data_clean( $_GET['filter_name'] ) : '';

			$category_dropdown_args = array(
				'taxonomy'        => Unicamp_Tutor::instance()->get_tax_category(),
				'orderby'         => 'name',
				'show_count'      => 0,
				'hierarchical'    => 1,
				'show_option_all' => esc_html__( 'All categories', 'unicamp' ),
				'selected'        => $current_category,
				'name'            => 'filter_course-category',
				'class'           => 'form-input form-input-select',
			);

			$location_dropdown_args = array(
				'taxonomy'        => Unicamp_Tutor::instance()->get_tax_location(),
				'orderby'         => 'name',
				'show_count'      => 0,
				'hierarchical'    => 1,
				'show_option_all' => esc_html__( 'Select location', 'unicamp' ),
				'selected'        => $current_location,
				'name'            => 'filter_course-location',
				'class'           => 'form-input form-input-select',
			);

			$levels = tutor_utils()->course_levels();

			$this->widget_start( $args, $instance );
			?>
			<form method="get" action="<?php echo esc_url( $base_link ); ?>" class="course-form-filter-form">
				<div class="form-group filter-course-category">
					<?php wp_dropdown_categories( $category_dropdown_args ); ?>
				</div>

				<div class="form-group filter-course-location">
					<?php wp_dropdown_categories( $location_dropdown_args ); ?>
				</div>

				<?php if ( ! empty( $levels ) ) : ?>
					<div class="form-group filter-course-level">
						<select name="level" class="form-input form-input-select">
							<option
								value="" <?php selected( '', $current_level ); ?>><?php esc_html_e( 'Select a level', 'unicamp' ); ?></option>
							<?php foreach ( $levels as $value => $label ) : ?>
								<option
									value="<?php echo esc_attr( $value ) ?>" <?php selected( $value, $current_level ); ?>>
									<?php echo esc_html( $label ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>

				<div class="form-group filter-keyword form-has-icon">
					<input type="search"
					       class="form-input form-input-text"
					       placeholder="<?php echo esc_attr_x( 'Find your course', 'placeholder', 'unicamp' ); ?>"
					       value="<?php echo esc_attr( $current_name ); ?>" name="filter_name"/>
					<span class="form-icon">
						<i class="far fa-search"></i>
					</span>
				</div>

				<div class="form-submit">
					<button type="submit" class="search-submit">
						<span class="search-btn-text">
							<?php echo esc_html_x( 'Search', 'submit button', 'unicamp' ); ?>
						</span>
					</button>
				</div>
			</form>
			<?php
			$this->widget_end( $args );
		}
	}
}
