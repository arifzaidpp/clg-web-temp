<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Tutor_Sidebar' ) ) {
	class Unicamp_Tutor_Sidebar {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			// Register widget areas.
			add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
		}

		public function register_sidebars() {
			$default_args = Unicamp_Sidebar::instance()->get_default_sidebar_args();

			register_sidebar( array_merge( $default_args, [
				'id'          => 'course_top_filters',
				'name'        => esc_html__( 'Course Listing Top Filters', 'unicamp' ),
				'description' => esc_html__( 'This sidebar displays above course list on course listing page.', 'unicamp' ),
			] ) );

			register_sidebar( array_merge( $default_args, [
				'id'          => 'course_sidebar',
				'name'        => esc_html__( 'Course Listing Sidebar', 'unicamp' ),
				'description' => esc_html__( 'Add widgets displays on course listing page.', 'unicamp' ),
			] ) );

			register_sidebar( array_merge( $default_args, [
				'id'          => 'course_sidebar_filters',
				'name'        => esc_html__( 'Course Listing Sidebar Filters', 'unicamp' ),
				'description' => esc_html__( 'This sidebar displays below Sidebar 1 on course listing page.', 'unicamp' ),
			] ) );

			register_sidebar( array_merge( $default_args, [
				'id'          => 'course_category_listing_sidebar',
				'name'        => esc_html__( 'Course Category Listing Sidebar', 'unicamp' ),
				'description' => esc_html__( 'Add widgets displays on course category listing page.', 'unicamp' ),
			] ) );

			register_sidebar( array_merge( $default_args, [
				'id'          => 'course_category_listing_sidebar_filters',
				'name'        => esc_html__( 'Course Category Listing Sidebar Filters', 'unicamp' ),
				'description' => esc_html__( 'This sidebar displays below Sidebar 1 on course category listing page.', 'unicamp' ),
			] ) );

			register_sidebar( array_merge( $default_args, [
				'id'          => 'course_single_sidebar',
				'name'        => esc_html__( 'Single Course Sidebar', 'unicamp' ),
				'description' => esc_html__( 'Add widgets displays on single course pages.', 'unicamp' ),
			] ) );
		}
	}
}
