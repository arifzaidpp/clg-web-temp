<?php

class InsightCore_Breadcrumb {
	/**
	 * @param array $args
	 *
	 * @return mixed string|void
	 */
	public function breadcrumb( $args = array() ) {
		$default = apply_filters( 'insight_core_breadcrumb_default', [
			'type'             => 'list',
			'home_label'       => esc_html__( 'Home', 'insight-core' ),
			'search_label'     => esc_html__( 'Search Result of "%s"', 'insight-core' ),
			'404_label'        => esc_html__( '404 Not Found', 'insight-core' ),
			'category_label'   => _x( '%s', 'category label', 'insight-core' ),
			'tag_label'        => _x( '%s', 'tag label', 'insight-core' ),
			'taxonomy_label'   => _x( '%s', 'taxonomy label', 'insight-core' ),
			'author_label'     => _x( '%s', 'author label', 'insight-core' ),
			'attachment_label' => _x( '%s', 'attachment label', 'insight-core' ),
			'year_label'       => _x( '%s', 'year label', 'insight-core' ),
			'month_label'      => _x( '%s', 'month label', 'insight-core' ),
			'day_label'        => _x( '%s', 'day label', 'insight-core' ),
			'post_type_label'  => _x( '%s', 'post type label', 'insight-core' ),
			'joint_string'     => esc_html__( ' &gt; ', 'insight-core' ),
			'navi_element'     => '',
			'elm_class'        => 'insight_core_breadcrumb',
			'elm_id'           => '',
			'li_class'         => '',
			'class_prefix'     => '',
			'current_class'    => 'current',
			'indent'           => 0,
			'echo'             => true,
		] );
		$args    = wp_parse_args( $args, $default );

		$elm            = in_array( $args['navi_element'], array( 'nav', 'div', '' ) ) ? $args['navi_element'] : 'div';
		$args['elm_id'] = is_array( $args['elm_id'] ) ? $default['elm_id'] : $args['elm_id'];
		$args['elm_id'] = preg_replace( '/[^\w_-]+/', '', $args['elm_id'] );
		$args['elm_id'] = preg_replace( '/^[\d_-]+/', '', $args['elm_id'] );

		$args['class_prefix'] = is_array( $args['class_prefix'] ) ? $default['class_prefix'] : $args['class_prefix'];
		$args['class_prefix'] = preg_replace( '/[^\w_-]+/', '', $args['class_prefix'] );
		$args['class_prefix'] = preg_replace( '/^[\d_-]+/', '', $args['class_prefix'] );

		$args['elm_class']     = $this->sanitize_attr_classes( $args['elm_class'], $args['class_prefix'] );
		$args['li_class']      = $this->sanitize_attr_classes( $args['li_class'], $args['class_prefix'] );
		$args['current_class'] = $this->sanitize_attr_classes( $args['current_class'], $args['class_prefix'] );
		$args['current_class'] = $args['current_class'] ? $args['current_class'] : $args['class_prefix'] . $default['current_class'];
		$args['echo']          = $this->uniform_boolean( $args['echo'], $default['echo'] );

		$tabs           = str_repeat( "\t", (int) $args['indent'] );
		$breadcrumb_arr = $this->get_breadcrumb_array( $args );

		$elm_attrs = '';
		if ( $args['elm_id'] ) {
			$elm_attrs = ' id="' . $args['elm_id'] . '"';
		}
		if ( $args['elm_class'] ) {
			$elm_attrs .= ' class="' . $args['elm_class'] . '"';
		}

		$output   = '';
		$elm_tabs = '';

		if ( $elm ) {
			$elm_tabs = "\t";
			$output   = $tabs . '<' . $elm;
			if ( $elm_attrs ) {
				$output .= $elm_attrs . ">\n";
			}
		}

		$loop_count  = 1;
		$total_items = count( $breadcrumb_arr );

		if ( 'string' === $args['type'] ) {
			$output = array();

			foreach ( $breadcrumb_arr as $ancestor ) {
				if ( $loop_count === $total_items ) {
					$output[] = '<strong class="' . $args['current_class'] . '">' . $ancestor['title'] . '</strong>';
				} else {
					$output[] = '<a href="' . $ancestor['link'] . '">' . $ancestor['title'] . '</a>';
				}
				$loop_count++;
			}
			$output = $elm_tabs . $tabs . implode( esc_html( $args['joint_string'] ), $output );
			$output = apply_filters( 'insight_core_breadcrumb_after', $output, $args );
		} else {
			$output .= $elm_tabs . $tabs . '<ul';
			if ( ! $elm && $elm_attrs ) {
				$output .= $elm_attrs;
			}
			$output .= ">\n";

			$output = apply_filters( 'insight_core_breadcrumb_before', $output, $args );

			foreach ( $breadcrumb_arr as $ancestor ) {
				$classes = array(
					$args['class_prefix'] . 'level-' . $loop_count,
				);

				$classes[] = 1 === $loop_count ? $args['class_prefix'] . 'top' : $args['class_prefix'] . 'sub';

				if ( ! empty( $ancestor['id'] ) ) {
					$classes[] = $args['class_prefix'] . 'item-' . $ancestor['id'];
				}

				if ( ! empty( $args['li_class'] ) ) {
					$classes[] = $args['li_class'];
				}

				$title = $ancestor['title'];

				if ( ! empty( $ancestor['id'] ) && 'current' === $ancestor['id'] ) {
					$classes[] = $args['class_prefix'] . 'tail';
					$classes[] = $args['current_class'];
					$title     = '<span>' . $title . '</span>';
				} else {
					if ( ! empty( $ancestor['link'] ) ) {
						$title = '<a href="' . esc_url( $ancestor['link'] ) . '">' . $title . '</a>';
					}
				}

				$output .= $elm_tabs . $tabs . '<li class="' . implode( ' ', $classes ) . '">' . $title . '</li>' . "\n";

				$loop_count++;
			}
			$output = apply_filters( 'insight_core_breadcrumb_after', $output, $args );

			$output .= $elm_tabs . $tabs . '</ul>' . "\n";
		}

		if ( $elm ) {
			$output .= $tabs . '</' . $elm . ">\n";
		}

		$output = apply_filters( 'insight_core_breadcrumb', $output, $args );

		if ( $args['echo'] ) {
			echo $output;
		} else {
			return $output;
		}
	}

	private function get_breadcrumb_array( $args ) {
		global $post;

		$breadcrumb_arr = array(
			array(
				'id'    => 'home',
				'title' => $args['home_label'],
				// WPML add slash for then need to remove before add to avoid double slash.
				'link'  => rtrim( get_bloginfo( 'url' ), '/' ) . '/',
			),
		);

		// Add archive link if needed.
		$breadcrumb_arr = $this->add_posts_page_array( $breadcrumb_arr );

		if ( is_404() ) {
			$breadcrumb_arr[] = array(
				'id'    => 'current',
				'title' => $args['404_label'],
				'link'  => false,
			);
		} elseif ( is_search() ) {
			$breadcrumb_arr[] = array(
				'id'    => 'current',
				'title' => sprintf( $args['search_label'], get_search_query() ),
				'link'  => false,
			);
		} elseif ( is_tax() ) {
			$taxonomy  = get_query_var( 'taxonomy' );
			$term_slug = get_queried_object()->slug;
			$term      = get_term_by( 'slug', $term_slug, $taxonomy );
			if ( $term instanceof WP_Term ) {
				if ( is_taxonomy_hierarchical( $taxonomy ) && $term->parent !== 0 ) {
					$ancestors = array_reverse( get_ancestors( $term->term_id, $taxonomy ) );
					foreach ( $ancestors as $ancestor_id ) {
						$ancestor = get_term( $ancestor_id, $taxonomy );
						if ( ! empty( $ancestor ) && ! is_wp_error( $ancestor ) ) {
							$breadcrumb_arr[] = array(
								'id'    => 'term_ancestor',
								'title' => $ancestor->name,
								'link'  => get_term_link( $ancestor ),
							);
						}
					}
				}
				$breadcrumb_arr[] = array(
					'id'    => 'current',
					'title' => sprintf( $args['taxonomy_label'], $term->name ),
					'link'  => get_term_link( $term ),
				);
			}
		} elseif ( is_attachment() ) {
			if ( $post->post_parent ) {
				$parent_post = get_post( $post->post_parent );
				if ( $parent_post instanceof WP_Post ) {
					$singular_breadcrumb_arr = $this->get_singular_breadcrumb_array( $parent_post, $args );
					$breadcrumb_arr          = array_merge( $breadcrumb_arr, $singular_breadcrumb_arr );

					$breadcrumb_arr[] = array(
						'id'    => 'post_ancestor',
						'title' => $parent_post->post_title,
						'link'  => get_permalink( $parent_post->ID ),
					);
				}
			}
			$breadcrumb_arr[] = array(
				'id'    => 'current',
				'title' => sprintf( $args['attachment_label'], $post->post_title ),
				'link'  => get_permalink( $post->ID ),
			);
		} elseif ( is_singular() && ! is_front_page() ) {
			$singular_breadcrumb_arr = $this->get_singular_breadcrumb_array( $post, $args );
			$breadcrumb_arr          = array_merge( $breadcrumb_arr, $singular_breadcrumb_arr );
			$breadcrumb_arr[]        = array(
				'id'    => 'current',
				'title' => $post->post_title,
				'link'  => get_permalink( $post->ID ),
			);
		} elseif ( is_category() ) {
			global $cat;

			$category = get_category( $cat );
			if ( $category->parent !== 0 ) {
				$ancestors = array_reverse( get_ancestors( $category->term_id, 'category' ) );
				foreach ( $ancestors as $ancestor_id ) {
					$ancestor = get_category( $ancestor_id );
					if ( ! empty( $ancestor ) && ! is_wp_error( $ancestor ) ) {
						$breadcrumb_arr[] = array(
							'id'    => 'term_ancestor',
							'title' => $ancestor->name,
							'link'  => get_category_link( $ancestor->term_id ),
						);
					}
				}
			}
			$breadcrumb_arr[] = array(
				'id'    => 'current',
				'title' => sprintf( $args['category_label'], $category->name ),
				'link'  => get_category_link( $cat ),
			);
		} elseif ( is_tag() ) {
			global $tag_id;
			$tag              = get_tag( $tag_id );
			$breadcrumb_arr[] = array(
				'id'    => 'current',
				'title' => sprintf( $args['tag_label'], $tag->name ),
				'link'  => get_tag_link( $tag_id ),
			);
		} elseif ( is_author() ) {
			$author           = get_query_var( 'author' );
			$breadcrumb_arr[] = array(
				'id'    => 'current',
				'title' => sprintf( $args['author_label'], get_the_author_meta( 'display_name', get_query_var( 'author' ) ) ),
				'link'  => get_author_posts_url( $author ),
			);
		} elseif ( is_day() ) {
			if ( $m = get_query_var( 'm' ) ) {
				$year  = substr( $m, 0, 4 );
				$month = substr( $m, 4, 2 );
				$day   = substr( $m, 6, 2 );
			} else {
				$year  = get_query_var( 'year' );
				$month = get_query_var( 'monthnum' );
				$day   = get_query_var( 'day' );
			}
			$month_title      = $this->get_month_title( $month );
			$breadcrumb_arr[] = array(
				'id'    => 'year',
				'title' => sprintf( $args['year_label'], $year ),
				'link'  => get_year_link( $year ),
			);
			$breadcrumb_arr[] = array(
				'id'    => 'month',
				'title' => sprintf( $args['month_label'], $month_title ),
				'link'  => get_month_link( $year, $month ),
			);
			$breadcrumb_arr[] = array(
				'id'    => 'day',
				'title' => sprintf( $args['day_label'], $day ),
				'link'  => get_day_link( $year, $month, $day ),
			);
		} elseif ( is_month() ) {
			if ( $m = get_query_var( 'm' ) ) {
				$year  = substr( $m, 0, 4 );
				$month = substr( $m, 4, 2 );
			} else {
				$year  = get_query_var( 'year' );
				$month = get_query_var( 'monthnum' );
			}
			$month_title      = $this->get_month_title( $month );
			$breadcrumb_arr[] = array(
				'id'    => 'year',
				'title' => sprintf( $args['year_label'], $year ),
				'link'  => get_year_link( $year ),
			);
			$breadcrumb_arr[] = array(
				'id'    => 'month',
				'title' => sprintf( $args['month_label'], $month_title ),
				'link'  => get_month_link( $year, $month ),
			);
		} elseif ( is_year() ) {
			if ( $m = get_query_var( 'm' ) ) {
				$year = substr( $m, 0, 4 );
			} else {
				$year = get_query_var( 'year' );
			}
			$breadcrumb_arr[] = array(
				'id'    => 'year',
				'title' => sprintf( $args['year_label'], $year ),
				'link'  => get_year_link( $year ),
			);
		} elseif ( is_post_type_archive() ) {
			$post_type = get_post_type_object( get_query_var( 'post_type' ) );
			if ( $post_type instanceof WP_Post_Type ) {
				$breadcrumb_arr[] = array(
					'id'    => 'current',
					'title' => sprintf( $args['post_type_label'], $post_type->label ),
					'link'  => get_post_type_archive_link( $post_type->name ),
				);
			}
		}

		return apply_filters( 'insight_core_breadcrumb_arr', $breadcrumb_arr, $args );
	}

	private function get_singular_breadcrumb_array( $post, $args ) {
		$breadcrumb_arr = array();
		$post_type      = get_post_type_object( $post->post_type );

		$breadcrumb_arr = apply_filters( 'insight_core_breadcrumb_single_before', $breadcrumb_arr, $post, $args );

		if ( $post_type && $post_type->has_archive ) {
			$breadcrumb_arr[] = array(
				'id'    => 'archive',
				'title' => sprintf( $args['post_type_label'], $post_type->label ),
				'link'  => get_post_type_archive_link( $post_type->name ),
			);
		}

		if ( is_post_type_hierarchical( $post_type->name ) ) {
			$ancestors = array_reverse( get_post_ancestors( $post ) );
			if ( count( $ancestors ) ) {
				$ancestor_posts = get_posts( 'post_type=' . $post_type->name . '&include=' . implode( ',', $ancestors ) );
				foreach ( $ancestors as $ancestor ) {
					foreach ( $ancestor_posts as $ancestor_post ) {
						if ( $ancestor == $ancestor_post->ID ) {
							$breadcrumb_arr[] = array(
								'id'    => 'post_ancestor',
								'title' => apply_filters( 'the_title', $ancestor_post->post_title, $ancestor_post->ID ),
								'link'  => get_permalink( $ancestor_post->ID ),
							);
						}
					}
				}
			}
		} else {
			$post_type_taxonomies = get_object_taxonomies( $post_type->name, false );
			if ( is_array( $post_type_taxonomies ) && count( $post_type_taxonomies ) ) {
				foreach ( $post_type_taxonomies as $tax_slug => $taxonomy ) {
					if ( ! $taxonomy->hierarchical || ! $taxonomy->show_in_nav_menus ) {
						continue;
					}

					$post_terms = get_the_terms( $post->ID, $tax_slug );

					/**
					 * Get selected primary term.
					 */
					$primary_post_term_id = 0;
					if ( class_exists( 'WPSEO_Primary_Term' ) ) { // Yoast SEO plugin.
						$yoast_seo_primary_term = new WPSEO_Primary_Term( $tax_slug, $post->ID );
						$primary_post_term_id   = $yoast_seo_primary_term->get_primary_term();
					} elseif ( class_exists( '\RankMath\Helper' ) ) { // Rank Math plugin.
						$primary_post_term_id = absint( \RankMath\Helper::get_post_meta( "primary_{$tax_slug}", $post->ID ) );
					}

					if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
						$post_term = array_shift( $post_terms );

						// Make sure the term is deepest term.
						if ( $post_term->term_id !== $primary_post_term_id && count( $post_terms ) > 1 ) {
							$post_term = $this->get_deepest_term( $post_term, $post_terms, $primary_post_term_id );
						}

						if ( $post_term->parent !== 0 ) {
							$ancestors = array_reverse( get_ancestors( $post_term->term_id, $tax_slug ) );
							foreach ( $ancestors as $ancestor_id ) {
								$ancestor = get_term( $ancestor_id, $tax_slug );

								if ( ! empty( $ancestor ) && ! is_wp_error( $ancestor ) ) {
									$breadcrumb_arr[] = array(
										'id'    => 'term_ancestor',
										'title' => $ancestor->name,
										'link'  => get_term_link( $ancestor ),
									);
								}
							}
						}

						$breadcrumb_arr[] = array(
							'id'    => 'post_term',
							'title' => $post_term->name,
							'link'  => get_term_link( $post_term ),
						);
						break;
					}
				}
			}
		}

		return $breadcrumb_arr;
	}

	/**
	 * Check given term is the deepest term in given terms.
	 *
	 * @param WP_Term $post_term
	 * @param array   $post_terms
	 * @param int     $primary_post_term_id Primary term selected by Rank Math plugin.
	 *
	 * @return mixed
	 */
	private function get_deepest_term( &$post_term, &$post_terms, $primary_post_term_id ) {
		$is_deepest = true;
		foreach ( $post_terms as $key => $term ) {
			if ( $term->term_id === $primary_post_term_id ) {
				$post_term = $term;

				return $post_term;
			}

			if ( $term->parent === $post_term->term_id ) {
				$post_term = $term;
				unset( $post_terms[ $key ] );
				$is_deepest = false;
			}
		}

		if ( $is_deepest ) {
			return $post_term;
		}

		return $this->get_deepest_term( $post_term, $post_terms, $primary_post_term_id );
	}

	private function add_posts_page_array( $breadcrumb_arr ) {
		if ( is_page() || is_front_page() ) {
			return $breadcrumb_arr;
		} elseif ( is_category() ) {
			$tax = get_taxonomy( 'category' );
			if ( count( $tax->object_type ) !== 1 || 'post' !== $tax->object_type[0] ) {
				return $breadcrumb_arr;
			}
		} elseif ( is_tag() ) {
			$tax = get_taxonomy( 'post_tag' );
			if ( count( $tax->object_type ) !== 1 || 'post' !== $tax->object_type[0] ) {
				return $breadcrumb_arr;
			}
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( get_query_var( 'taxonomy' ) );
			if ( count( $tax->object_type ) !== 1 || 'post' !== $tax->object_type[0] ) {
				$post_type = get_post_type_object( $tax->object_type[0] );
				if ( $post_type && $post_type->has_archive ) {
					$breadcrumb_arr[] = array(
						'id'    => 'archive',
						'title' => $post_type->label,
						'link'  => get_post_type_archive_link( $post_type->name ),
					);
				}

				return $breadcrumb_arr;
			}
		} elseif ( is_home() && ! get_query_var( 'pagename' ) ) {
			return $breadcrumb_arr;
		} else {
			$post_type = get_query_var( 'post_type' ) ? get_query_var( 'post_type' ) : 'post';
			if ( $post_type != 'post' ) {
				return $breadcrumb_arr;
			}
		}
		if ( get_option( 'show_on_front' ) === 'page' && $posts_page_id = get_option( 'page_for_posts' ) ) {
			$posts_page = get_post( $posts_page_id );
			if ( $posts_page instanceof WP_Post ) {
				$breadcrumb_arr[] = array(
					'id'    => 'posts_page',
					'title' => $posts_page->post_title,
					'link'  => get_permalink( $posts_page->ID ),
				);
			}
		}

		return $breadcrumb_arr;
	}

	private function sanitize_attr_classes( $classes, $prefix = '' ) {
		if ( ! is_array( $classes ) ) {
			$classes = preg_replace( '/[^\s\w_-]+/', '', $classes );
			$classes = preg_split( '/[\s]+/', $classes );
		}

		foreach ( $classes as $key => $class ) {
			if ( is_array( $class ) ) {
				unset( $classes[ $key ] );
			} else {
				$class = preg_replace( '/[^\w_-]+/', '', $class );
				$class = preg_replace( '/^[\d_-]+/', '', $class );
				if ( $class ) {
					$classes[ $key ] = $prefix . $class;
				}
			}
		}
		$classes = implode( ' ', $classes );

		return $classes;
	}

	private function uniform_boolean( $arg, $default = true ) {
		if ( is_numeric( $arg ) ) {
			$arg = (int) $arg;
		}
		if ( is_string( $arg ) ) {
			$arg = strtolower( $arg );
			if ( $arg == 'false' ) {
				$arg = false;
			} elseif ( $arg == 'true' ) {
				$arg = true;
			} else {
				$arg = $default;
			}
		}

		return $arg;
	}

	private function get_month_title( $monthnum = 0 ) {
		global $wp_locale;
		$monthnum    = (int) $monthnum;
		$date_format = get_option( 'date_format' );
		if ( in_array( $date_format, array(
			'DATE_COOKIE',
			'DATE_RFC822',
			'DATE_RFC850',
			'DATE_RFC1036',
			'DATE_RFC1123',
			'DATE_RFC2822',
			'DATE_RSS',
		) ) ) {
			$month_format = 'M';
		} elseif ( in_array( $date_format, array( 'DATE_ATOM', 'DATE_ISO8601', 'DATE_RFC3339', 'DATE_W3C' ) ) ) {
			$month_format = 'm';
		} else {
			preg_match( '/(^|[^\\\\]+)(F|m|M|n)/', str_replace( '\\\\', '', get_option( 'date_format' ) ), $m );
			if ( isset( $m[2] ) ) {
				$month_format = $m[2];
			} else {
				$month_format = 'F';
			}
		}

		switch ( $month_format ) {
			case 'F' :
				$month = $wp_locale->get_month( $monthnum );
				break;
			case 'M' :
				$month = $wp_locale->get_month_abbrev( $wp_locale->get_month( $monthnum ) );
				break;
			default :
				$month = $monthnum;
		}

		return $month;
	}
}

if ( ! function_exists( 'insight_core_breadcrumb' ) ) {
	function insight_core_breadcrumb( $args = array() ) {
		$insight_core_breadcrumb = new InsightCore_Breadcrumb();

		return $insight_core_breadcrumb->breadcrumb( $args );
	}
}
