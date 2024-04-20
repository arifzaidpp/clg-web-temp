<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_BP_Layout' ) ) {
	class Unicamp_BP_Layout extends Unicamp_BP {

		private static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'body_class', [ $this, 'body_class' ] );

			// Register widget areas.
			add_action( 'widgets_init', [ $this, 'register_sidebars' ], 11 );

			add_filter( 'unicamp_top_bar_type', [ $this, 'change_top_bar_type' ] );

			add_filter( 'unicamp_header_type', [ $this, 'change_header_type' ] );
			add_filter( 'unicamp_header_overlay', [ $this, 'change_header_overlay' ] );
			add_filter( 'unicamp_header_skin', [ $this, 'change_header_skin' ] );

			add_filter( 'unicamp_title_bar_type', [ $this, 'change_title_bar' ], 99 );

			add_filter( 'unicamp_sidebar_1', [ $this, 'change_sidebar_1' ], 99 );
			add_filter( 'unicamp_sidebar_position', [ $this, 'change_sidebar_position' ], 99 );
			add_filter( 'unicamp_one_sidebar_offset', [ $this, 'change_sidebar_offset' ], 99 );
		}

		public function body_class( $classes ) {
			if ( bp_is_active() ) {
				$site_background = Unicamp::setting( 'buddypress_body_background' );
				if ( ! empty( $site_background ) ) {
					$classes[] = 'site-background-' . $site_background;
				}

				$site_layout = Unicamp::setting( 'buddypress_site_layout' );
				if ( 'small' === $site_layout ) {
					$classes[] = 'site-content-small';
				}
			}

			return $classes;
		}

		public function change_top_bar_type( $type ) {
			if ( bp_is_active() ) {
				$new_type = Unicamp::setting( 'buddypress_page_top_bar_type' );

				if ( '' !== $new_type ) {
					return $new_type;
				}
			}

			return $type;
		}

		public function change_header_type( $type ) {
			if ( bp_is_active() ) {
				$new_type = Unicamp::setting( 'buddypress_page_header_type' );

				if ( '' !== $new_type ) {
					return $new_type;
				}
			}

			return $type;
		}

		public function change_header_overlay( $value ) {
			if ( bp_is_active() ) {
				$new_value = Unicamp::setting( 'buddypress_page_header_overlay' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function change_header_skin( $value ) {
			if ( bp_is_active() ) {
				$new_value = Unicamp::setting( 'buddypress_page_header_skin' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function change_title_bar( $type ) {
			/**
			 * Disable title bar for group pages
			 */
			if ( bp_is_groups_component() ) {
				return 'none';
			}

			/**
			 * Disable title bar for member pages
			 */
			if ( bp_is_user() || bp_is_members_component() ) {
				return 'none';
			}

			/**
			 * Disable title bar for activity page
			 */
			if ( bp_is_activity_directory() ) {
				return 'none';
			}

			return $type;
		}

		public function register_sidebars() {
			$default_args = Unicamp_Sidebar::instance()->get_default_sidebar_args();

			register_sidebar( array_merge( $default_args, [
				'id'          => 'activity_top_sidebar',
				'name'        => esc_html__( 'Activity Directory Top Sidebar', 'unicamp' ),
				'description' => esc_html__( 'Widgets in this area will shown on News feed page.', 'unicamp' ),
			] ) );

			register_sidebar( array_merge( $default_args, [
				'id'          => 'activity_sidebar',
				'name'        => esc_html__( 'Activity Directory Sidebar', 'unicamp' ),
				'description' => esc_html__( 'Widgets in this area will shown on News feed page.', 'unicamp' ),
			] ) );

			register_sidebar( array_merge( $default_args, [
				'id'          => 'groups_sidebar',
				'name'        => esc_html__( 'Groups Directory Sidebar', 'unicamp' ),
				'description' => esc_html__( 'Widgets in this area will shown on Groups page.', 'unicamp' ),
			] ) );

			/*register_sidebar( array_merge( $default_args, [
				'id'          => 'group_single_sidebar',
				'name'        => esc_html__( 'Groups Single Directory Sidebar', 'unicamp' ),
				'description' => esc_html__( 'Widgets in this area will shown on individual group page.', 'unicamp' ),
			] ) );*/

			register_sidebar( array_merge( $default_args, [
				'id'          => 'member_single_sidebar',
				'name'        => esc_html__( 'Members Single Profile Sidebar', 'unicamp' ),
				'description' => esc_html__( 'Widgets in this area will shown on individual member profiles.', 'unicamp' ),
			] ) );
		}

		public function change_sidebar_1( $type ) {
			if ( bp_is_groups_directory() ) {
				return 'groups_sidebar';
			}

			if ( bp_is_group() ) {
				return 'groups_sidebar';
			}

			if ( bp_is_user() || bp_is_members_component() ) {
				return 'member_single_sidebar';
			}

			if ( bp_is_activity_component() ) {
				return 'activity_sidebar';
			}

			return $type;
		}

		public function change_sidebar_position( $position ) {
			if ( bp_is_groups_component() ) {
				return 'left';
			}

			if ( bp_is_user() || bp_is_members_component() ) {
				return 'left';
			}

			if ( bp_is_activity_component() ) {
				return 'left';
			}

			return $position;
		}

		public function change_sidebar_offset( $offset ) {
			if ( bp_is_groups_component() ) {
				return 0;
			}

			if ( bp_is_user() || bp_is_members_component() ) {
				return 0;
			}

			if ( bp_is_activity_component() ) {
				return 0;
			}

			return $offset;
		}
	}

	Unicamp_BP_Layout::instance()->initialize();
}
