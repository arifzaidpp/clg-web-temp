<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Kungfu_Framework_Testimonial' ) ) {
	class Kungfu_Framework_Testimonial {
		function __construct() {
			add_action( 'init', array( $this, 'register_post_types' ), 1 );
		}

		function register_post_types() {

			$slug = apply_filters( 'insight_core_testimonial_slug', 'testimonial' );

			$labels = array(
				'name'               => _x( 'Testimonials', 'post type general name', 'insight-core' ),
				'singular_name'      => __( 'Testimonial Item', 'insight-core' ),
				'view_item'          => __( 'View Testimonials', 'insight-core' ),
				'add_new_item'       => __( 'Add New Testimonial', 'insight-core' ),
				'add_new'            => _x( 'Add New', 'testimonial', 'insight-core' ),
				'new_item'           => __( 'Add New Testimonial Item', 'insight-core' ),
				'edit_item'          => __( 'Edit Testimonial Item', 'insight-core' ),
				'update_item'        => __( 'Update Testimonial', 'insight-core' ),
				'all_items'          => __( 'All Testimonials', 'insight-core' ),
				'parent_item_colon'  => __( 'Parent Testimonial Item:', 'insight-core' ),
				'search_items'       => __( 'Search Testimonial', 'insight-core' ),
				'not_found'          => __( 'No testimonial items found', 'insight-core' ),
				'not_found_in_trash' => __( 'No testimonial items found in trash', 'insight-core' ),
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
				'testimonial',
				array(
					'labels'             => $labels,
					'supports'           => $supports,
					'public'             => false,
					'has_archive'        => false,
					'can_export'         => true,
					'show_ui'            => true,
					'show_in_menu'       => true,
					'publicly_queryable' => false,
					'rewrite'            => false,
					'menu_icon'          => ( version_compare( $GLOBALS['wp_version'], '3.8', '>=' ) ) ? 'dashicons-format-quote' : false,
					'capability_type' => 'post',
					'capabilities'    => array(
						'edit_post'           => 'edit_ic_testimonial',
						'read_post'           => 'read_ic_testimonial',
						'delete_post'         => 'delete_ic_testimonial',
						'delete_posts'        => 'delete_ic_testimonials',
						'edit_posts'          => 'edit_ic_testimonials',
						'edit_others_posts'   => 'edit_others_ic_testimonials',
						'delete_others_posts' => 'delete_other_ic_testimonials',
						'publish_posts'       => 'publish_ic_testimonials',
						'read_private_posts'  => 'read_private_ic_testimonials',
						'create_posts'        => 'edit_ic_testimonials',
					),
					'map_meta_cap'    => true,
				)
			);

			register_taxonomy(
				'testimonial_category',
				'testimonial',
				array(
					'hierarchical'      => false,
					'label'             => __( 'Categories', 'insight-core' ),
					'query_var'         => false,
					'rewrite'           => false,
					'show_admin_column' => true,
				)
			);
		}
	}

	new Kungfu_Framework_Testimonial;
}
