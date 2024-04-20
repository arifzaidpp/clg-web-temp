<?php

if ( ! defined( 'INSIGHT_FOOTER_POST_TYPE' ) ) {
	define( 'INSIGHT_FOOTER_POST_TYPE', 'ic_footer' );
}

if ( ! class_exists( 'Insight_Core_Footer' ) ) {

	class Insight_Core_Footer {

		function __construct() {
			add_action( 'init', array(
				$this,
				'register_post_types'
			), 1 );
		}

		/**
		 * Register Footer Post Type
		 */
		function register_post_types() {

			$labels = array(
				'name'               => _x( 'Footers', 'Post Type General Name', 'insight-core' ),
				'singular_name'      => _x( 'Footer', 'Post Type Singular Name', 'insight-core' ),
				'menu_name'          => esc_html__( 'Footer', 'insight-core' ),
				'name_admin_bar'     => esc_html__( 'Footer', 'insight-core' ),
				'parent_item_colon'  => esc_html__( 'Parent Footer:', 'insight-core' ),
				'all_items'          => esc_html__( 'All Footers', 'insight-core' ),
				'add_new_item'       => esc_html__( 'Add New Footer', 'insight-core' ),
				'add_new'            => esc_html__( 'Add New', 'insight-core' ),
				'new_item'           => esc_html__( 'New Footer', 'insight-core' ),
				'edit_item'          => esc_html__( 'Edit Footer', 'insight-core' ),
				'update_item'        => esc_html__( 'Update Footer', 'insight-core' ),
				'view_item'          => esc_html__( 'View Footer', 'insight-core' ),
				'search_items'       => esc_html__( 'Search Footer', 'insight-core' ),
				'not_found'          => esc_html__( 'Not found', 'insight-core' ),
				'not_found_in_trash' => esc_html__( 'Not found in Trash', 'insight-core' ),
			);

			$args = array(
				'label'               => esc_html__( 'Footers', 'insight-core' ),
				'description'         => esc_html__( 'Insight Footer', 'insight-core' ),
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
				'publicly_queryable'  => false,
				'rewrite'             => false,
				'capability_type'     => 'page',
			);

			register_post_type( INSIGHT_FOOTER_POST_TYPE, $args );
		}
	}

	new Insight_Core_Footer();
}