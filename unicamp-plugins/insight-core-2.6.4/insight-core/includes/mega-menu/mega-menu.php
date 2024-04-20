<?php

if ( ! defined( 'INSIGHT_MEGA_MENU_POST_TYPE' ) ) {
	define( 'INSIGHT_MEGA_MENU_POST_TYPE', 'ic_mega_menu' );
}

require_once( trailingslashit( dirname( __FILE__ ) ) . 'class-walker-nav-menu.php' );

if ( ! class_exists( 'Insight_Mega_Menu' ) ) {

	class Insight_Mega_Menu {

		function __construct() {
			$this->insight_mega_menu_hooks();
		}

		function insight_mega_menu_hooks() {

			add_action( 'init', array(
				$this,
				'insight_core_register_megamenu',
			) );
			add_action( 'wp_head', array(
				$this,
				'insight_mega_menu_generate_vc_custom_css',
			), 999 );
			add_action( 'admin_footer', array(
				$this,
				'insight_mega_menu_remove_vc_frontend_editor',
			) );
			add_action( 'admin_bar_menu', array(
				$this,
				'insight_mega_menu_remove_wp_bar_view',
			), 999 );

			add_filter( 'post_row_actions', array(
				$this,
				'insight_mega_menu_remove_row_actions',
			), 999, 2 );

		}

		/**
		 * Register Mega_Menu Post Type
		 */
		function insight_core_register_megamenu() {

			$labels = array(
				'name'               => _x( 'Mega Menus', 'Post Type General Name', 'insight-core' ),
				'singular_name'      => _x( 'Mega Menu', 'Post Type Singular Name', 'insight-core' ),
				'menu_name'          => esc_html__( 'Mega Menu', 'insight-core' ),
				'name_admin_bar'     => esc_html__( 'Mega Menu', 'insight-core' ),
				'parent_item_colon'  => esc_html__( 'Parent Menu:', 'insight-core' ),
				'all_items'          => esc_html__( 'All Menus', 'insight-core' ),
				'add_new_item'       => esc_html__( 'Add New Menu', 'insight-core' ),
				'add_new'            => esc_html__( 'Add New', 'insight-core' ),
				'new_item'           => esc_html__( 'New Menu', 'insight-core' ),
				'edit_item'          => esc_html__( 'Edit Menu', 'insight-core' ),
				'update_item'        => esc_html__( 'Update Menu', 'insight-core' ),
				'view_item'          => esc_html__( 'View Menu', 'insight-core' ),
				'search_items'       => esc_html__( 'Search Menu', 'insight-core' ),
				'not_found'          => esc_html__( 'Not found', 'insight-core' ),
				'not_found_in_trash' => esc_html__( 'Not found in Trash', 'insight-core' ),
			);

			$args = array(
				'label'               => esc_html__( 'Mega Menus', 'insight-core' ),
				'description'         => esc_html__( 'Insight Mega Menu', 'insight-core' ),
				'labels'              => $labels,
				'supports'            => array(
					'title',
					'editor',
					'revisions',
				),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 20,
				'menu_icon'           => 'dashicons-list-view',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'rewrite'             => false,
				'capability_type'     => 'page',
			);

			register_post_type( INSIGHT_MEGA_MENU_POST_TYPE, $args );

		}

		/**
		 * Generate VC custom CSS
		 */
		function insight_mega_menu_generate_vc_custom_css() {
			if ( ! defined( 'WPB_VC_VERSION' ) ) {
				return;
			}

			$locations = get_nav_menu_locations();

			foreach ( $locations as $location ) {

				$menu = wp_get_nav_menu_object( $location );

				if ( is_object( $menu ) ) {

					$nav_items     = wp_get_nav_menu_items( $menu->term_id );
					$mega_menu_ids = array();

					foreach ( (array) $nav_items as $nav_item ) {
						if ( INSIGHT_MEGA_MENU_POST_TYPE == $nav_item->object ) {
							$mega_menu_ids[] = $nav_item->object_id;
						}
					}

					if ( ! empty( $mega_menu_ids ) ) {
						$post_custom_css_array       = array();
						$shortcodes_custom_css_array = array();

						foreach ( $mega_menu_ids as $mega_menu_id ) {
							$post_custom_css = get_post_meta( $mega_menu_id, '_wpb_post_custom_css', true );
							if ( ! empty( $post_custom_css ) ) {
								$post_custom_css_array[] = $post_custom_css;
							}

							$shortcodes_custom_css = get_post_meta( $mega_menu_id, '_wpb_shortcodes_custom_css', true );
							if ( ! empty( $shortcodes_custom_css ) ) {
								$shortcodes_custom_css_array[] = $shortcodes_custom_css;
							}
						}

						if ( ! empty( $post_custom_css_array ) ) {
							echo '<style type="text/css" data-type="vc_custom-css">';
							echo implode( '', $shortcodes_custom_css_array );
							echo '</style>';
						}

						if ( ! empty( $shortcodes_custom_css_array ) ) {
							echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
							echo implode( '', $shortcodes_custom_css_array );
							echo '</style>';
						}
					}
				}
			}
		}

		/**
		 * Remove VC Frontend editor
		 */
		function insight_mega_menu_remove_vc_frontend_editor() {
			?>
			<style type="text/css">
				.post-type-ic_mega_menu .wpb_switch-to-front-composer {
					display: none;
				}
			</style>
			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					setTimeout( function() {
						$( '.post-type-ic_mega_menu .wpb_switch-to-front-composer' ).remove();
						$( '.post-type-ic_mega_menu .wpb_switch-to-composer' ).next( '.vc_spacer' ).remove();
					}, 50 );
				} );
			</script>
			<?php
		}

		/**
		 * Remove wp_bar view
		 *
		 * @param $wp_admin_bar
		 */
		function insight_mega_menu_remove_wp_bar_view( $wp_admin_bar ) {
			if ( get_post_type() == INSIGHT_MEGA_MENU_POST_TYPE ) {
				$wp_admin_bar->remove_node( 'view' );
			}
		}

		/**
		 * Remove row actions
		 *
		 * @param $actions
		 *
		 * @return mixed
		 */
		function insight_mega_menu_remove_row_actions( $actions ) {

			if ( get_post_type() == INSIGHT_MEGA_MENU_POST_TYPE ) {
				unset ( $actions['inline hide-if-no-js'] );
				unset ( $actions['view'] );
				unset ( $actions['edit_vc'] );
			}

			return $actions;
		}
	}

	new Insight_Mega_Menu();
}
