<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_BP_Groups' ) ) {
	class Unicamp_BP_Groups extends Unicamp_BP {

		private static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'bp_core_default_avatar', [ $this, 'groups_default_avatar' ], 10, 4 );
			add_filter( 'bp_core_avatar_default_thumb', [ $this, 'groups_default_avatar' ], 10, 4 );

			/**
			 * Priority 4 to make sure this function run after BP global setup & before canonical_stack.
			 */
			add_action( 'bp_init', [ $this, 'change_default_tab' ], 4 );

			/**
			 * Remove home tab.
			 */
			//add_action( 'bp_setup_nav', [ $this, 'remove_home_tab' ], 999 );
		}

		/**
		 * Change default Members landing tab.
		 */
		public function change_default_tab() {
			// Skip if set in wp-config.
			if ( defined( 'BP_GROUPS_DEFAULT_EXTENSION' ) ) {
				return;
			}

			if ( bp_is_active( 'activity' ) ) {
				define( 'BP_GROUPS_DEFAULT_EXTENSION', bp_get_activity_slug() );
			} else {
				define( 'BP_GROUPS_DEFAULT_EXTENSION', 'members' );
			}
		}

		/**
		 * @note this removal is not working.
		 * use css to hide it for temple.
		 * Remove Groups home tab.
		 */
		public function remove_home_tab() {
			global $bp;

			//bp_core_remove_nav_item( 'home', 'groups' );;
			$parent_slug = isset( $bp->bp_options_nav[ $bp->groups->current_group->slug ] ) ? $bp->groups->current_group->slug : $bp->groups->slug;
			bp_core_remove_nav_item( $parent_slug . '/home', 'groups' );
		}

		/**
		 * Change group default avatar.
		 *
		 * @see   bp_groups_default_avatar()
		 *
		 * Use the mystery group avatar for groups.
		 *
		 * @since 2.6.0
		 *
		 * @param string $avatar Current avatar src.
		 * @param array  $params Avatar params.
		 *
		 * @return string
		 */
		public function groups_default_avatar( $avatar, $params ) {
			if ( isset( $params['object'] ) && 'group' === $params['object'] ) {
				if ( isset( $params['type'] ) && 'thumb' === $params['type'] ) {
					$file = 'mystery-group-50.jpg';
				} else {
					$file = 'mystery-group.jpg';
				}

				$avatar = UNICAMP_BP_ASSETS_URI . "/images/$file";
			}

			return $avatar;
		}
	}

	Unicamp_BP_Groups::instance()->initialize();
}
