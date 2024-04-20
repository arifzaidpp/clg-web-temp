<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Kungfu_Portfolio' ) ) {
	class Kungfu_Portfolio {

		function __construct() {
			add_action( 'init', array( $this, 'register_post_types' ), 1 );
		}

		function register_post_types() {

			$slug = apply_filters( 'insight_core_portfolio_slug', 'portfolio' );

			$labels = array(
				'name'                  => _x( 'Portfolios', 'post type general name', 'insight-core' ),
				'singular_name'         => __( 'Portfolio', 'insight-core' ),
				'all_items'             => __( 'All Portfolios', 'insight-core' ),
				'menu_name'             => _x( 'Portfolios', 'Admin menu name', 'insight-core' ),
				'add_new'               => __( 'Add New', 'insight-core' ),
				'add_new_item'          => __( 'Add new portfolio', 'insight-core' ),
				'edit'                  => __( 'Edit', 'insight-core' ),
				'edit_item'             => __( 'Edit portfolio', 'insight-core' ),
				'new_item'              => __( 'New portfolio', 'insight-core' ),
				'view'                  => __( 'View portfolio', 'insight-core' ),
				'view_item'             => __( 'View portfolio', 'insight-core' ),
				'search_items'          => __( 'Search portfolios', 'insight-core' ),
				'not_found'             => __( 'No portfolios found', 'insight-core' ),
				'not_found_in_trash'    => __( 'No portfolios found in trash', 'insight-core' ),
				'parent'                => __( 'Parent portfolio', 'insight-core' ),
				'filter_items_list'     => __( 'Filter portfolios', 'insight-core' ),
				'items_list_navigation' => __( 'Portfolios navigation', 'insight-core' ),
				'items_list'            => __( 'Portfolio list', 'insight-core' ),
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

			register_post_type( 'portfolio', array(
				'labels'          => $labels,
				'supports'        => $supports,
				'public'          => true,
				'has_archive'     => true,
				'rewrite'         => array(
					'slug' => $slug,
				),
				'can_export'      => true,
				'menu_icon'       => ( version_compare( $GLOBALS['wp_version'], '3.8', '>=' ) ) ? 'dashicons-portfolio' : false,
				'capability_type' => 'post',
				'capabilities'    => array(
					'edit_post'           => 'edit_ic_portfolio',
					'read_post'           => 'read_ic_portfolio',
					'delete_post'         => 'delete_ic_portfolio',
					'delete_posts'        => 'delete_ic_portfolios',
					'edit_posts'          => 'edit_ic_portfolios',
					'edit_others_posts'   => 'edit_others_ic_portfolios',
					'delete_others_posts' => 'delete_other_ic_portfolios',
					'publish_posts'       => 'publish_ic_portfolios',
					'read_private_posts'  => 'read_private_ic_portfolios',
					'create_posts'        => 'edit_ic_portfolios',
				),
				'map_meta_cap'    => true,
			) );

			//flush_rewrite_rules( false );

			register_taxonomy( 'portfolio_category', 'portfolio', apply_filters( 'insight_core_taxonomy_args_portfolio_category', array(
				'hierarchical'      => true,
				'label'             => __( 'Categories', 'insight-core' ),
				'labels'            => array(
					'name' => _x( 'Portfolio Categories', 'taxonomy general name', 'insight-core' ),
				),
				'query_var'         => true,
				'rewrite'           => array( 'slug' => apply_filters( 'insight_core_portfolio_category_slug', 'portfolio-category' ) ),
				'show_admin_column' => true,
			) ) );

			register_taxonomy( 'portfolio_tags', 'portfolio', apply_filters( 'insight_core_taxonomy_args_portfolio_tags', array(
				'hierarchical'      => false,
				'label'             => __( 'Tags', 'insight-core' ),
				'labels'            => array(
					'name' => _x( 'Portfolio Tags', 'taxonomy general name', 'insight-core' ),
				),
				'query_var'         => true,
				'rewrite'           => array( 'slug' => apply_filters( 'insight_core_portfolio_tags_slug', 'portfolio-tags' ) ),
				'show_admin_column' => true,
			) ) );
		}
	}

	new Kungfu_Portfolio;
}
