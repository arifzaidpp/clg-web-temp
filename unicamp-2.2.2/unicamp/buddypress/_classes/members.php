<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_BP_Members' ) ) {
	class Unicamp_BP_Members extends Unicamp_BP {

		private static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			/**
			 * Priority 4 to make sure this function run after BP global setup & before canonical_stack.
			 */
			add_action( 'bp_init', [ $this, 'change_default_tab' ], 4 );

			/**
			 * Remove home tab.
			 */
			add_action( 'bp_setup_nav', [ $this, 'remove_home_tab' ], 999 );

			/**
			 * Change button text
			 */
			add_filter( 'bp_get_add_friend_button', [ $this, 'change_add_friend_button' ] );

			add_filter( 'bp_nouveau_get_members_buttons', [ $this, 'change_members_button' ], 99, 3 );

			add_action( 'bp_core_general_settings_after_save', [ $this, 'add_social_for_members' ] );
		}

		/**
		 * Sync user social networks.
		 */
		public function add_social_for_members() {
			$social_links = Unicamp_Helper::get_user_social_networks_support();
			$user_id      = get_current_user_id();

			if ( ! $user_id ) {
				return;
			}

			foreach ( $social_links as $key => $social ) {
				if ( isset( $_POST["{$key}"] ) ) {
					$social_value = esc_url( $_POST["{$key}"] );

					update_user_meta( $user_id, $key, $social_value );
				}
			}
		}

		/**
		 * Change default Members landing tab.
		 */
		public function change_default_tab() {
			// Skip if set in wp-config.
			if ( defined( 'BP_DEFAULT_COMPONENT' ) ) {
				return;
			}

			if ( bp_is_active( 'activity' ) ) {
				define( 'BP_DEFAULT_COMPONENT', bp_get_activity_slug() );
			} else {
				define( 'BP_DEFAULT_COMPONENT', 'profile' );
			}
		}

		/**
		 * Remove Members home tab.
		 */
		public function remove_home_tab() {
			bp_core_remove_nav_item( 'front', 'members' );
		}

		public function change_add_friend_button( $button ) {
			switch ( $button['id'] ) {
				case 'pending':
					$button['link_text'] = esc_html__( 'Cancel Request', 'unicamp' );
					break;
			}


			return $button;
		}

		public function change_members_button( $buttons, $user_id, $type ) {
			if ( 'profile' === $type ) {
				// Remove public message button on header of single user.
				if ( isset( $buttons['public_message'] ) ) {
					unset( $buttons['public_message'] );
				}

				// Change private message button text.
				if ( isset( $buttons['private_message'] ) ) {
					$buttons['private_message'] ['link_text'] = esc_html__( 'Messages', 'unicamp' );
				}
			}

			return $buttons;
		}
	}

	Unicamp_BP_Members::instance()->initialize();
}
