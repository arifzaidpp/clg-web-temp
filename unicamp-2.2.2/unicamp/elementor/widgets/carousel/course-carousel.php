<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Course_Carousel extends Posts_Carousel_Base {

	public function get_name() {
		return 'tm-course-carousel';
	}

	public function get_title() {
		return esc_html__( 'Course Carousel', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-posts-carousel';
	}

	public function get_keywords() {
		return [ 'course', 'carousel' ];
	}

	protected function get_post_type() {
		return \Unicamp_Tutor::instance()->get_course_type();
	}

	protected function get_query_author_object() {
		return Module_Query_Base::QUERY_OBJECT_USER;
	}

	protected function register_controls() {
		$this->add_layout_section();

		parent::register_controls();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'unicamp' ),
		] );

		$this->add_control( 'style', [
			'label'   => esc_html__( 'Style', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'01' => esc_html__( '01', 'unicamp' ),
				'02' => esc_html__( '02', 'unicamp' ),
				'03' => esc_html__( '03', 'unicamp' ),
			],
			'default' => '03',
		] );

		$this->add_control( 'hover_effect', [
			'label'        => esc_html__( 'Hover Effect', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''         => esc_html__( 'None', 'unicamp' ),
				'zoom-in'  => esc_html__( 'Zoom In', 'unicamp' ),
				'zoom-out' => esc_html__( 'Zoom Out', 'unicamp' ),
			],
			'default'      => '',
			'prefix_class' => 'unicamp-animation-',
		] );

		/*$this->add_control( 'thumbnail_default_size', [
			'label'        => esc_html__( 'Use Default Thumbnail Size', 'unicamp' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '1',
			'return_value' => '1',
			'separator'    => 'before',
		] );

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'thumbnail',
				'default'   => 'full',
				'condition' => [
					'thumbnail_default_size!' => '1',
				],
			]
		);*/

		$this->end_controls_section();
	}

	protected function before_slider() {
		/**
		 * Add more attrs to slider.
		 */
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( self::SLIDER_KEY, 'class', 'unicamp-courses style-carousel-' . $settings['style'] );
	}

	protected function print_slides( array $settings ) {
		$settings = $this->get_settings_for_display();
		$this->query_posts();
		/**
		 * @var $query \WP_Query
		 */
		$query = $this->get_query();
		?>
		<?php if ( $query->have_posts() ) : ?>

			<?php
			global $unicamp_course;
			$unicamp_course_clone = $unicamp_course;
			?>

			<?php while ( $query->have_posts() ) : $query->the_post(); ?><?php
				/**
				 * Setup course object.
				 */
				$unicamp_course = new \Unicamp_Course();
				?>
				<?php $this->print_slide( $settings ); ?>
			<?php endwhile; ?>

			<?php $this->after_loop(); ?>

			<?php wp_reset_postdata(); ?>
			<?php
			/**
			 * Reset course object.
			 */
			$unicamp_course = $unicamp_course_clone;
			?>

		<?php endif;
	}

	protected function print_slide( array $settings ) {
		$style = $settings['style'];
		?>
		<?php tutor_load_template( 'loop.custom.loop-before-slide-content' ); ?>
		<?php tutor_load_template( 'loop.custom.content-course-carousel-' . $style ); ?>
		<?php tutor_load_template( 'loop.custom.loop-after-slide-content' ); ?>
		<?php
	}
}
