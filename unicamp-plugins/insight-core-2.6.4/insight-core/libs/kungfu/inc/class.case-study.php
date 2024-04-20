<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Kungfu_Case_Study' ) ) {
	class Kungfu_Case_Study {

		function __construct() {
			add_action( 'init', array( $this, 'register_post_types' ), 1 );
		}

		function register_post_types() {

			$labels = array(
				'name'                  => _x( 'Case Studies', 'post type general name', 'insight-core' ),
				'singular_name'         => __( 'Case Study', 'insight-core' ),
				'all_items'             => __( 'All Case Studies', 'insight-core' ),
				'menu_name'             => _x( 'Case Studies', 'Admin menu name', 'insight-core' ),
				'add_new'               => __( 'Add New', 'insight-core' ),
				'add_new_item'          => __( 'Add new case study', 'insight-core' ),
				'edit'                  => __( 'Edit', 'insight-core' ),
				'edit_item'             => __( 'Edit case study', 'insight-core' ),
				'new_item'              => __( 'New case study', 'insight-core' ),
				'view'                  => __( 'View case study', 'insight-core' ),
				'view_item'             => __( 'View case study', 'insight-core' ),
				'search_items'          => __( 'Search case studies', 'insight-core' ),
				'not_found'             => __( 'No case studies found', 'insight-core' ),
				'not_found_in_trash'    => __( 'No case studies found in trash', 'insight-core' ),
				'parent'                => __( 'Parent case study', 'insight-core' ),
				'featured_image'        => __( 'Case Study image', 'insight-core' ),
				'set_featured_image'    => __( 'Set case study image', 'insight-core' ),
				'remove_featured_image' => __( 'Remove case study image', 'insight-core' ),
				'use_featured_image'    => __( 'Use as case study image', 'insight-core' ),
				'uploaded_to_this_item' => __( 'Uploaded to this case study', 'insight-core' ),
				'filter_items_list'     => __( 'Filter case studies', 'insight-core' ),
				'items_list_navigation' => __( 'Case studies navigation', 'insight-core' ),
				'items_list'            => __( 'Case study list', 'insight-core' ),
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
				'case_study',
				apply_filters( 'insight_core_register_post_type_case_study', array(
					'labels'      => $labels,
					'supports'    => $supports,
					'public'      => true,
					'has_archive' => true,
					'rewrite'     => array(
						'slug'       => apply_filters( 'insight_core_case_study_slug', 'case_study' ),
						'with_front' => apply_filters( 'insight_core_case_study_with_front', false ),
					),
					'can_export'  => true,
					'menu_icon'   => ( version_compare( $GLOBALS['wp_version'], '3.8', '>=' ) ) ? 'dashicons-portfolio' : false,
				) )
			);

			register_taxonomy(
				'case_study_category',
				'case_study',
				apply_filters( 'insight_core_taxonomy_args_case_study_category', array(
					'hierarchical'      => true,
					'label'             => __( 'Categories', 'insight-core' ),
					'labels'            => array(
						'name'              => _x( 'Case Study Categories', 'taxonomy general name', 'insight-core' ),
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
					'rewrite'           => array(
						'slug'       => apply_filters( 'insight_core_case_study_category_slug', 'case-study-category' ),
						'with_front' => apply_filters( 'insight_core_case_study_category_with_front', false ),
					),
					'show_admin_column' => true,
				) )
			);

			register_taxonomy( 'case_study_tags', 'case_study', apply_filters( 'insight_core_taxonomy_args_case_study_tags', array(
				'hierarchical'      => false,
				'label'             => __( 'Tags', 'insight-core' ),
				'labels'            => array(
					'name' => _x( 'Case Study Tags', 'taxonomy general name', 'insight-core' ),
				),
				'query_var'         => true,
				'rewrite'           => array(
					'slug'       => apply_filters( 'insight_core_case_study_tag_slug', 'case-study-tag' ),
					'with_front' => apply_filters( 'insight_core_case_study_tag_with_front', false ),
				),
				'show_admin_column' => true,
			) ) );
		}
	}

	new Kungfu_Case_Study;
}
