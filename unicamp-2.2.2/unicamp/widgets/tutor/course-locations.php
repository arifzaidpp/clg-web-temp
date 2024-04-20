<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_WP_Widget_Course_Locations' ) ) {
	class Unicamp_WP_Widget_Course_Locations extends Unicamp_WP_Widget_Base {

		/**
		 * Category ancestors.
		 *
		 * @var array
		 */
		public $cat_ancestors;

		/**
		 * Current Category.
		 *
		 * @var bool
		 */
		public $current_cat;

		public function __construct() {
			$this->widget_id          = 'unicamp-wp-widget-course-locations';
			$this->widget_cssclass    = 'unicamp-wp-widget-course-locations';
			$this->widget_name        = esc_html__( '[Unicamp] Course Locations', 'unicamp' );
			$this->widget_description = esc_html__( 'A list or dropdown of course locations.', 'unicamp' );
			$this->settings           = array(
				'title'              => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Course locations', 'unicamp' ),
					'label' => esc_html__( 'Title', 'unicamp' ),
				),
				'orderby'            => array(
					'type'    => 'select',
					'std'     => 'name',
					'label'   => esc_html__( 'Order by', 'unicamp' ),
					'options' => array(
						'order' => esc_html__( 'Category order', 'unicamp' ),
						'name'  => esc_html__( 'Name', 'unicamp' ),
					),
				),
				'style'              => array(
					'type'    => 'select',
					'std'     => 'list',
					'label'   => esc_html__( 'Style', 'unicamp' ),
					'options' => array(
						'list'       => esc_html__( 'List', 'unicamp' ),
						'check-list' => esc_html__( 'Check List', 'unicamp' ),
						'dropdown'   => esc_html__( 'Dropdown', 'unicamp' ),
					),
				),
				'count'              => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Show course counts', 'unicamp' ),
				),
				'hierarchical'       => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => esc_html__( 'Show hierarchy', 'unicamp' ),
				),
				'show_children_only' => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Only show children of the current category', 'unicamp' ),
				),
				'hide_empty'         => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Hide empty locations', 'unicamp' ),
				),
				'max_depth'          => array(
					'type'  => 'text',
					'std'   => '',
					'label' => esc_html__( 'Maximum depth', 'unicamp' ),
					'desc'  => esc_html__( 'For eg: Input 1 to show only top locations.', 'unicamp' ),
				),
			);

			parent::__construct();
		}

		public function widget( $args, $instance ) {
			global $wp_query, $post;

			$count              = $this->get_value( $instance, 'count' );
			$hierarchical       = $this->get_value( $instance, 'hierarchical' );
			$show_children_only = $this->get_value( $instance, 'show_children_only' );
			$style              = $this->get_value( $instance, 'style' );
			$dropdown           = 'dropdown' === $style ? true : false;
			$orderby            = $this->get_value( $instance, 'orderby' );
			$hide_empty         = $this->get_value( $instance, 'hide_empty' );
			$max_depth          = absint( $this->get_value( $instance, 'max_depth' ) );

			$dropdown_args = array(
				'hide_empty' => $hide_empty,
			);
			$list_args     = array(
				'show_count'   => $count,
				'hierarchical' => $hierarchical,
				'taxonomy'     => 'course-location',
				'hide_empty'   => $hide_empty,
			);

			$list_args['menu_order'] = false;
			$dropdown_args['depth']  = $max_depth;
			$list_args['depth']      = $max_depth;

			if ( 'order' === $orderby ) {
				$list_args['orderby']      = 'meta_value_num';
				$dropdown_args['orderby']  = 'meta_value_num';
				$list_args['meta_key']     = 'order';
				$dropdown_args['meta_key'] = 'order';
			}

			$this->current_cat   = false;
			$this->cat_ancestors = array();

			if ( is_tax( 'course-location' ) ) {
				$this->current_cat   = $wp_query->queried_object;
				$this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'course-location' );

			} elseif ( is_singular( 'courses' ) ) {
				$terms = wp_get_post_terms( $post->ID, 'course-location', [
					'orderby' => 'parent',
					'order'   => 'DESC',
				] );

				if ( $terms ) {
					$main_term           = apply_filters( 'unicamp_course_locations_widget_main_term', $terms[0], $terms );
					$this->current_cat   = $main_term;
					$this->cat_ancestors = get_ancestors( $main_term->term_id, 'course-location' );
				}
			}

			// Show Siblings and Children Only.
			if ( $show_children_only && $this->current_cat ) {
				if ( $hierarchical ) {
					$include = array_merge(
						$this->cat_ancestors,
						array( $this->current_cat->term_id ),
						get_terms( [
							'taxonomy'     => 'course-location',
							'fields'       => 'ids',
							'parent'       => 0,
							'hierarchical' => true,
							'hide_empty'   => false,
						] ),
						get_terms( [
							'taxonomy'     => 'course-location',
							'fields'       => 'ids',
							'parent'       => $this->current_cat->term_id,
							'hierarchical' => true,
							'hide_empty'   => false,
						] )
					);
					// Gather siblings of ancestors.
					if ( $this->cat_ancestors ) {
						foreach ( $this->cat_ancestors as $ancestor ) {
							$include = array_merge(
								$include,
								get_terms( [
									'taxonomy'     => 'course-location',
									'fields'       => 'ids',
									'parent'       => $ancestor,
									'hierarchical' => false,
									'hide_empty'   => false,
								] )
							);
						}
					}
				} else {
					// Direct children.
					$include = get_terms( [
						'taxonomy'     => 'course-location',
						'fields'       => 'ids',
						'parent'       => $this->current_cat->term_id,
						'hierarchical' => true,
						'hide_empty'   => false,
					] );
				}

				$list_args['include']     = implode( ',', $include );
				$dropdown_args['include'] = $list_args['include'];

				if ( empty( $include ) ) {
					return;
				}
			} elseif ( $show_children_only ) {
				$dropdown_args['depth']        = 1;
				$dropdown_args['child_of']     = 0;
				$dropdown_args['hierarchical'] = 1;
				$list_args['depth']            = 1;
				$list_args['child_of']         = 0;
				$list_args['hierarchical']     = 1;
			}

			$this->widget_start( $args, $instance );

			$wrapper_classes = 'unicamp-widget-term-list style-' . $style;

			echo '<div class="' . esc_attr( $wrapper_classes ) . '">';

			if ( $dropdown ) {
				Unicamp_Tutor::instance()->course_dropdown_locations( wp_parse_args(
					$dropdown_args,
					array(
						'show_count'         => $count,
						'hierarchical'       => $hierarchical,
						'show_uncategorized' => 0,
						'selected'           => $this->current_cat ? $this->current_cat->slug : '',
					)
				) );

				wp_enqueue_script( 'selectWoo' );
				wp_enqueue_style( 'select2' );

				wc_enqueue_js(
					"
				jQuery( '.dropdown-course-location' ).change( function() {
					if ( jQuery(this).val() != '' ) {
						var this_page = '';
						var home_url  = '" . esc_js( home_url( '/' ) ) . "';
						if ( home_url.indexOf( '?' ) > 0 ) {
							this_page = home_url + '&course-location=' + jQuery(this).val();
						} else {
							this_page = home_url + '?course-location=' + jQuery(this).val();
						}
						location.href = this_page;
					} else {
						location.href = '" . esc_js( tutor_utils()->course_archive_page_url() ) . "';
					}
				});

				if ( jQuery().selectWoo ) {
					var tutor_course_location_select = function() {
						jQuery( '.dropdown-course-location' ).selectWoo( {
							placeholder: '" . esc_js( __( 'Select a location', 'unicamp' ) ) . "',
							minimumResultsForSearch: 5,
							width: '100%',
							allowClear: true,
							language: {
								noResults: function() {
									return '" . esc_js( _x( 'No matches found', 'enhanced select', 'unicamp' ) ) . "';
								}
							}
						} );
					};
					tutor_course_location_select();
				}
			"
				);
			} else {
				require_once UNICAMP_FRAMEWORK_DIR . '/tutor/class-course-taxonomy-term-list-walker.php';

				$list_args['walker']                     = new Unicamp_Course_Taxonomy_Term_List_Walker( 'course-location' );
				$list_args['title_li']                   = '';
				$list_args['pad_counts']                 = 1;
				$list_args['show_option_none']           = esc_html__( 'No locations exist.', 'unicamp' );
				$list_args['current_category']           = ( $this->current_cat ) ? $this->current_cat->term_id : '';
				$list_args['current_category_ancestors'] = $this->cat_ancestors;
				$list_args['max_depth']                  = $max_depth;

				echo '<ul class="course-locations">';

				wp_list_categories( apply_filters( 'unicamp_course_locations_widget_args', $list_args ) );

				echo '</ul>';
			}

			echo '</div>';

			$this->widget_end( $args );
		}
	}
}
