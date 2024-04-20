<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Kungfu_Service' ) ) {
	class Kungfu_Service {

		function __construct() {
			add_action( 'init', array( $this, 'register_post_types' ), 1 );
		}

		function register_post_types() {
			$labels = array(
				'name'                  => _x( 'Services', 'post type general name', 'insight-core' ),
				'singular_name'         => __( 'Service', 'insight-core' ),
				'all_items'             => __( 'All Services', 'insight-core' ),
				'menu_name'             => _x( 'Services', 'Admin menu name', 'insight-core' ),
				'add_new'               => __( 'Add New', 'insight-core' ),
				'add_new_item'          => __( 'Add new service', 'insight-core' ),
				'edit'                  => __( 'Edit', 'insight-core' ),
				'edit_item'             => __( 'Edit service', 'insight-core' ),
				'new_item'              => __( 'New service', 'insight-core' ),
				'view'                  => __( 'View service', 'insight-core' ),
				'view_item'             => __( 'View service', 'insight-core' ),
				'search_items'          => __( 'Search services', 'insight-core' ),
				'not_found'             => __( 'No services found', 'insight-core' ),
				'not_found_in_trash'    => __( 'No services found in trash', 'insight-core' ),
				'parent'                => __( 'Parent service', 'insight-core' ),
				'featured_image'        => __( 'Service image', 'insight-core' ),
				'set_featured_image'    => __( 'Set service image', 'insight-core' ),
				'remove_featured_image' => __( 'Remove service image', 'insight-core' ),
				'use_featured_image'    => __( 'Use as service image', 'insight-core' ),
				'uploaded_to_this_item' => __( 'Uploaded to this service', 'insight-core' ),
				'filter_items_list'     => __( 'Filter services', 'insight-core' ),
				'items_list_navigation' => __( 'Services navigation', 'insight-core' ),
				'items_list'            => __( 'Service list', 'insight-core' ),
			);

			$supports = array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'comments',
				'author',
				'revisions',
				'custom-fields',
			);

			register_post_type(
				'service',
				apply_filters( 'insight_core_register_post_type_service', array(
					'labels'      => $labels,
					'supports'    => $supports,
					'public'      => true,
					'has_archive' => true,
					'rewrite'     => array(
						'slug' => apply_filters( 'insight_core_service_slug', 'service' ),
					),
					'can_export'  => true,
					'menu_icon'   => ( version_compare( $GLOBALS['wp_version'], '3.8', '>=' ) ) ? 'dashicons-portfolio' : false,
					'capability_type' => 'post',
					'capabilities'    => array(
						'edit_post'           => 'edit_ic_service',
						'read_post'           => 'read_ic_service',
						'delete_post'         => 'delete_ic_service',
						'delete_posts'        => 'delete_ic_services',
						'edit_posts'          => 'edit_ic_services',
						'edit_others_posts'   => 'edit_others_ic_services',
						'delete_others_posts' => 'delete_other_ic_services',
						'publish_posts'       => 'publish_ic_services',
						'read_private_posts'  => 'read_private_ic_services',
						'create_posts'        => 'edit_ic_services',
					),
					'map_meta_cap'    => true,
				) )
			);

			register_taxonomy(
				'service_category',
				'service',
				apply_filters( 'insight_core_taxonomy_args_service_category', array(
					'hierarchical'      => true,
					'label'             => __( 'Categories', 'insight-core' ),
					'labels'            => array(
						'name'              => _x( 'Service Categories', 'taxonomy general name', 'insight-core' ),
						'singular_name'     => _x( 'Category', 'taxonomy singular name', 'insight-core' ),
						'menu_name'         => _x( 'Categories', 'Admin menu name', 'insight-core' ),
						'search_items'      => __( 'Search categories', 'insight-core' ),
						'all_items'         => __( 'All categories', 'insight-core' ),
						'parent_item'       => __( 'Parent category', 'insight-core' ),
						'parent_item_colon' => __( 'Parent category:', 'insight-core' ),
						'edit_item'         => __( 'Edit category', 'insight-core' ),
						'update_item'       => __( 'Update category', 'insight-core' ),
						'add_new_item'      => __( 'Add new category', 'insight-core' ),
						'new_item_name'     => __( 'New category name', 'insight-core' ),
						'not_found'         => __( 'No categories found', 'insight-core' ),
					),
					'show_ui'           => true,
					'query_var'         => true,
					'rewrite'           => array( 'slug' => apply_filters( 'insight_core_service_category_slug', 'service-category' ) ),
					'show_admin_column' => true,
				) )
			);
		}
	}

	new Kungfu_Service;
}
