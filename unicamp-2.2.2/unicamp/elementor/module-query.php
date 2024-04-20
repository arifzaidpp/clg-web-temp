<?php

namespace Unicamp_Elementor;

use Elementor\Plugin;
use Elementor\TemplateLibrary\Source_Local;

defined( 'ABSPATH' ) || exit;

class Module_Query_Base {

	const AUTOCOMPLETE_CONTROL_ID = 'autocomplete';
	const AUTOCOMPLETE_ERROR_CODE = 'QueryControlAutocomplete';
	const GET_TITLES_ERROR_CODE   = 'QueryControlGetTitles';

	// Supported objects for query:
	const QUERY_OBJECT_POST             = 'post';
	const QUERY_OBJECT_TAX              = 'tax';
	const QUERY_OBJECT_AUTHOR           = 'author';
	const QUERY_OBJECT_USER             = 'user';
	const QUERY_OBJECT_LIBRARY_TEMPLATE = 'library_template';
	const QUERY_OBJECT_ATTACHMENT       = 'attachment';

	private static $supported_objects_for_query = [
		self::QUERY_OBJECT_POST,
		self::QUERY_OBJECT_TAX,
		self::QUERY_OBJECT_AUTHOR,
		self::QUERY_OBJECT_USER,
		self::QUERY_OBJECT_LIBRARY_TEMPLATE,
		self::QUERY_OBJECT_ATTACHMENT,
	];

	protected $post_type;
	protected $query_args;
	protected $widget_settings;

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function initialize() {
		add_action( 'elementor/ajax/register_actions', [ $this, 'register_ajax_actions' ] );
	}

	/**
	 * @param \Elementor\Core\Common\Modules\Ajax\Module $ajax_manager
	 */
	public function register_ajax_actions( $ajax_manager ) {
		$ajax_manager->register_ajax_action( 'unicamp_elementor_autocomplete_search', [
			$this,
			'autocomplete_search',
		] );
		$ajax_manager->register_ajax_action( 'unicamp_elementor_autocomplete_render', [
			$this,
			'autocomplete_render',
		] );
	}

	public function autocomplete_search( array $data ) {
		$query_data = $this->autocomplete_query_data( $data );
		if ( is_wp_error( $query_data ) ) {
			/** @var \WP_Error $query_data */
			throw new \Exception( $query_data->get_error_code() . ':' . $query_data->get_error_message() );
		}

		$results    = [];
		$display    = $query_data['display'];
		$query_args = $query_data['query'];

		switch ( $query_data['object'] ) {
			case self::QUERY_OBJECT_TAX:
				$by_field = ! empty( $query_data['by_field'] ) ? $query_data['by_field'] : 'term_taxonomy_id';
				$terms    = get_terms( $query_args );
				if ( empty( $terms ) || is_wp_error( $terms ) ) {
					break;
				}
				foreach ( $terms as $term ) {
					$results[] = [
						'id'   => $term->{$by_field},
						'text' => $this->get_term_name( $term, $display, $data ),
					];
				}
				break;
			case self::QUERY_OBJECT_ATTACHMENT:
			case self::QUERY_OBJECT_POST:
				$query = new \WP_Query( $query_args );

				foreach ( $query->posts as $post ) {
					$text      = $this->format_post_for_display( $post, $display, $data );
					$results[] = [
						'id'   => $post->ID,
						'text' => $text,
					];
				}
				break;
			case self::QUERY_OBJECT_LIBRARY_TEMPLATE:
				$query = new \WP_Query( $query_args );

				foreach ( $query->posts as $post ) {
					$document = Plugin::$instance->documents->get( $post->ID );
					if ( $document ) {
						$text      = esc_html( $post->post_title ) . ' (' . $document->get_post_type_title() . ')';
						$results[] = [
							'id'   => $post->ID,
							'text' => $text,
						];
					}
				}
				break;
			case self::QUERY_OBJECT_USER:
			case self::QUERY_OBJECT_AUTHOR:
				$user_query = new \WP_User_Query( $query_args );

				foreach ( $user_query->get_results() as $user ) {
					$results[] = [
						'id'   => $user->ID,
						'text' => $this->format_user_for_display( $user, $display, $data ),
					];
				}
				break;
			default:
				$results = apply_filters( 'unicamp_elementor_autocomplete_get_' . $query_data['filter_type'], $results, $data );
				break;
		}

		return [
			'results' => $results,
		];
	}

	public function autocomplete_render( $request ) {
		$query_data = $this->get_titles_query_data( $request );
		if ( is_wp_error( $query_data ) ) {
			return [];
		}
		$display    = $query_data['display'];
		$query_args = $query_data['query'];

		$results = [];

		switch ( $query_data['object'] ) {
			case self::QUERY_OBJECT_TAX :
				$by_field = ! empty( $query_data['by_field'] ) ? $query_data['by_field'] : 'term_taxonomy_id';
				$terms    = get_terms( $query_args );

				if ( empty( $terms ) || is_wp_error( $terms ) ) {
					break;
				}
				foreach ( $terms as $term ) {
					$results[ $term->{$by_field} ] = $this->get_term_name( $term, $display, $request, 'get_value_titles' );
				}
				break;
			case self::QUERY_OBJECT_ATTACHMENT:
			case self::QUERY_OBJECT_POST:
				$query = new \WP_Query( $query_args );

				foreach ( $query->posts as $post ) {
					$results[ $post->ID ] = $this->format_post_for_display( $post, $display, $request, 'get_value_titles' );
				}
				break;
			case self::QUERY_OBJECT_LIBRARY_TEMPLATE:
				$query = new \WP_Query( $query_args );

				foreach ( $query->posts as $post ) {
					$document = Plugin::$instance->documents->get( $post->ID );
					if ( $document ) {
						$results[ $post->ID ] = esc_html( $post->post_title ) . ' (' . $document->get_post_type_title() . ')';
					}
				}
				break;
			case self::QUERY_OBJECT_AUTHOR:
			case self::QUERY_OBJECT_USER:
				$user_query = new \WP_User_Query( $query_args );

				foreach ( $user_query->get_results() as $user ) {
					$results[ $user->ID ] = $this->format_user_for_display( $user, $display, $request, 'get_value_titles' );
				}
				break;
			default:
				$results = apply_filters( "unicamp_elementor_autocomplete_get_value_titles/{$query_data['filter_type']}", $results, $request );
		}

		return $results;
	}

	private function get_term_name( $term, $display, $request, $filter_name = 'get_autocomplete' ) {
		global $wp_taxonomies;
		$term_name = $this->get_term_name_with_parents( $term );
		switch ( $display ) {
			case 'detailed':
				$text = $wp_taxonomies[ $term->taxonomy ]->labels->name . ': ' . $term_name;
				break;
			case 'minimal':
				$text = $term_name;
				break;
			default:
				$text = apply_filters( "unicamp_elementor_query_{$filter_name}_display_{$display}", $term_name, $request );
				break;
		}

		return $text;
	}

	/**
	 * get_term_name_with_parents
	 *
	 * @param \WP_Term $term
	 * @param int      $max
	 *
	 * @return string
	 */
	private function get_term_name_with_parents( \WP_Term $term, $max = 3 ) {
		if ( 0 === $term->parent ) {
			return $term->name;
		}
		$separator = is_rtl() ? ' < ' : ' > ';
		$test_term = $term;
		$names     = [];
		while ( $test_term->parent > 0 ) {
			$test_term = get_term( $test_term->parent );
			if ( ! $test_term ) {
				break;
			}
			$names[] = $test_term->name;
		}

		$names = array_reverse( $names );
		if ( count( $names ) < ( $max ) ) {
			return implode( $separator, $names ) . $separator . $term->name;
		}

		$name_string = '';
		for ( $i = 0; $i < ( $max - 1 ); $i++ ) {
			$name_string .= $names[ $i ] . $separator;
		}

		return $name_string . '...' . $separator . $term->name;
	}

	/**
	 * get post name with parents
	 *
	 * @param \WP_Post $post
	 * @param int      $max
	 *
	 * @return string
	 */
	private function get_post_name_with_parents( $post, $max = 3 ) {
		if ( 0 === $post->post_parent ) {
			return $post->post_title;
		}
		$separator = is_rtl() ? ' < ' : ' > ';
		$test_post = $post;
		$names     = [];
		while ( $test_post->post_parent > 0 ) {
			$test_post = get_post( $test_post->post_parent );
			if ( ! $test_post ) {
				break;
			}
			$names[] = $test_post->post_title;
		}

		$names = array_reverse( $names );
		if ( count( $names ) < ( $max ) ) {
			return implode( $separator, $names ) . $separator . $post->post_title;
		}

		$name_string = '';
		for ( $i = 0; $i < ( $max - 1 ); $i++ ) {
			$name_string .= $names[ $i ] . $separator;
		}

		return $name_string . '...' . $separator . $post->post_title;
	}

	/**
	 * 'autocomplete' => [
	 *    'object' => 'post|tax|user|library_template|attachment|js', // required
	 *    'display' => 'minimal(default)|detailed|custom_filter_name',
	 *    'by_field' => 'term_taxonomy_id(default)|term_id', // relevant only if `object` is tax|cpt_tax
	 *    'query' => [
	 *        'post_type' => 'any|post|page|custom-post-type', // can be an array for multiple post types.
	 *                                                         // 'any' should not be used if 'object' is 'tax' or 'cpt_tax'.
	 *         ...
	 *    ],
	 * ],
	 *
	 * 'object' (required):    the queried object.
	 *      supported values:
	 *      'post'              : will use WP_Query(), if query['post_type'] is empty or missing, will default to 'any'.
	 *      'tax'               : will use get_terms().
	 *                            When 'post_type' is provided, will first use get_object_taxonomies() to build 'taxonomy'
	 *                            args then invoke get_terms().
	 *                            When both 'taxonomy' and 'post_type' are provided, 'post_type' is ignored.
	 *      'cpt_tax'           : Used in frontend only, will be replaced to 'tax' by js.
	 *                            Will use get_object_taxonomies() to build 'taxonomy' args then use get_terms().
	 *      'user'              : will use WP_User_Query() with the args defined in 'query'.
	 *      'author'            : will use WP_User_Query() with pre-defined args.
	 *      'library_template'  : will use WP_Query() with post_type = Source_Local::CPT.
	 *      'attachment'        : will use WP_Query() with post_type = attachment.
	 *      'js'                : Query data is populated by JavaScript.
	 *                            By the time the data is sent to the server,
	 *                            the 'object' value should be replaced with one of the other valid 'object' values and
	 *                            the Query array populated accordingly.
	 *      user_defined        : will invoke apply_filters() using the user_defined value as filter name,
	 *                            `elementor/query/[get_value_titles|get_autocomplete]/{user_defined}`.
	 *
	 * 'display':   output format
	 *      supported values:
	 *      'minimal' (default) : name only
	 *      'detailed'          : for Post & Taxonomies -> `[Taxonomy|Post-Type] : [parent] ... [parent] > name`
	 *                            for Users & Authors -> `name [email]`
	 *      user_defined        : will invoke apply_filters using the user_defined value as filter name,
	 *                            `elementor/query/[get_value_titles|get_autocomplete]/display/{user_defined}`
	 *
	 * `by_field`:  value of 'id' field in taxonomy query. Relevant only if `object` is tax|cpt_tax
	 *      supported values:
	 *      'term_taxonomy_id'(default)
	 *      'term_id'
	 *
	 * 'query': array of args to be passed "as-is" to the relevant query function (see 'object').
	 *
	 **
	 *
	 * @param array $data
	 *
	 * @return array | \WP_Error
	 */
	private function autocomplete_query_data( $data ) {
		if ( empty( $data['autocomplete'] ) || empty( $data['q'] ) || empty( $data['autocomplete']['object'] ) ) {
			return new \WP_Error( self::AUTOCOMPLETE_ERROR_CODE, 'Empty or incomplete data' );
		}

		$autocomplete = $data['autocomplete'];

		if ( in_array( $autocomplete['object'], self::$supported_objects_for_query, true ) ) {
			$method_name = 'autocomplete_query_for_' . $autocomplete['object'];
			if ( empty( $autocomplete['display'] ) ) {
				$autocomplete['display'] = 'minimal';
				$data['autocomplete']    = $autocomplete;
			}
			$query = $this->$method_name( $data );
			if ( is_wp_error( $query ) ) {
				return $query;
			}
			$autocomplete['query'] = $query;
		}

		return $autocomplete;
	}

	private function autocomplete_query_for_post( $data ) {
		if ( ! isset( $data['autocomplete']['query'] ) ) {
			return new \WP_Error( self::AUTOCOMPLETE_ERROR_CODE, 'Missing autocomplete[`query`] data' );
		}
		$query = $data['autocomplete']['query'];
		if ( empty( $query['post_type'] ) ) {
			$query['post_type'] = 'any';
		}
		$query['posts_per_page'] = -1;
		$query['s']              = $data['q'];

		return $query;
	}

	private function autocomplete_query_for_library_template( $data ) {
		$query = $data['autocomplete']['query'];

		$query['post_type'] = Source_Local::CPT;
		$query['orderby']   = 'meta_value';
		$query['order']     = 'ASC';

		if ( empty( $query['posts_per_page'] ) ) {
			$query['posts_per_page'] = -1;
		}
		$query['s'] = $data['q'];

		return $query;
	}

	private function autocomplete_query_for_attachment( $data ) {
		$query = $this->autocomplete_query_for_post( $data );
		if ( is_wp_error( $query ) ) {
			return $query;
		}
		$query['post_type']   = 'attachment';
		$query['post_status'] = 'inherit';

		return $query;
	}

	private function autocomplete_query_for_tax( $data ) {
		$query = $data['autocomplete']['query'];

		if ( empty( $query['taxonomy'] ) && ! empty( $query['post_type'] ) ) {
			$query['taxonomy'] = get_object_taxonomies( $query['post_type'] );
		}
		$query['search']     = $data['q'];
		$query['hide_empty'] = false;

		return $query;
	}

	private function autocomplete_query_for_author( $data ) {
		$query = $this->autocomplete_query_for_user( $data );
		if ( is_wp_error( $query ) ) {
			return $query;
		}
		$query['who'] = 'authors';

		return $query;
	}

	private function autocomplete_query_for_user( $data ) {
		$query = $data['autocomplete']['query'];
		if ( ! empty( $query ) ) {
			return $query;
		}

		$query = [
			'fields'         => [
				'ID',
				'display_name',
			],
			'search'         => '*' . $data['q'] . '*',
			'search_columns' => [
				'user_login',
				'user_nicename',
			],
		];
		if ( 'detailed' === $data['autocomplete']['display'] ) {
			$query['fields'][] = 'user_email';
		}

		return $query;
	}

	private function get_titles_query_data( $data ) {
		if ( empty( $data['get_titles'] ) || empty( $data['id'] ) || empty( $data['get_titles']['object'] ) ) {
			return new \WP_Error( self::GET_TITLES_ERROR_CODE, 'Empty or incomplete data' );
		}

		$get_titles = $data['get_titles'];
		if ( empty( $get_titles['query'] ) ) {
			$get_titles['query'] = [];
		}

		if ( in_array( $get_titles['object'], self::$supported_objects_for_query, true ) ) {
			$method_name = 'get_titles_query_for_' . $get_titles['object'];
			$query       = $this->$method_name( $data );
			if ( is_wp_error( $query ) ) {
				return $query;
			}
			$get_titles['query'] = $query;
		}

		if ( empty( $get_titles['display'] ) ) {
			$get_titles['display'] = 'minimal';
		}

		return $get_titles;
	}

	private function get_titles_query_for_post( $data ) {
		$query = $data['get_titles']['query'];
		if ( empty( $query['post_type'] ) ) {
			$query['post_type'] = 'any';
		}
		$query['posts_per_page'] = -1;
		$query['post__in']       = (array) $data['id'];

		return $query;
	}

	private function get_titles_query_for_attachment( $data ) {
		$query                = $this->get_titles_query_for_post( $data );
		$query['post_type']   = 'attachment';
		$query['post_status'] = 'inherit';

		return $query;
	}

	private function get_titles_query_for_tax( $data ) {
		$by_field = empty( $data['get_titles']['by_field'] ) ? 'term_taxonomy_id' : $data['get_titles']['by_field'];

		return [
			$by_field    => (array) $data['id'],
			'hide_empty' => false,
		];
	}

	private function get_titles_query_for_library_template( $data ) {
		$query = $data['get_titles']['query'];

		$query['post_type'] = Source_Local::CPT;
		$query['orderby']   = 'meta_value';
		$query['order']     = 'ASC';

		if ( empty( $query['posts_per_page'] ) ) {
			$query['posts_per_page'] = -1;
		}

		return $query;
	}

	private function get_titles_query_for_author( $data ) {
		$query                        = $this->get_titles_query_for_user( $data );
		$query['who']                 = 'authors';
		$query['has_published_posts'] = true;

		return $query;
	}

	private function get_titles_query_for_user( $data ) {
		$query = $data['get_titles']['query'];
		if ( ! empty( $query ) ) {
			return $query;
		}
		$query = [
			'fields'  => [
				'ID',
				'display_name',
			],
			'include' => (array) $data['id'],
		];
		if ( 'detailed' === $data['get_titles']['display'] ) {
			$query['fields'][] = 'user_email';
		}

		return $query;
	}

	/**
	 * @param \WP_Post $post
	 * @param string   $display
	 * @param array    $data
	 * @param string   $filter_name
	 *
	 * @return mixed|string
	 */
	private function format_post_for_display( $post, $display, $data, $filter_name = 'get_autocomplete' ) {
		$text          = '';
		$post_type_obj = get_post_type_object( $post->post_type );
		switch ( $display ) {
			case 'minimal':
				$text = ( $post_type_obj->hierarchical ) ? $this->get_post_name_with_parents( $post ) : $post->post_title;
				break;
			case 'detailed':
				$text = $post_type_obj->labels->name . ': ' . ( $post_type_obj->hierarchical ) ? $this->get_post_name_with_parents( $post ) : $post->post_title;
				break;
		}

		return esc_html( $text );
	}

	/**
	 * @param \WP_User $user
	 * @param string   $display
	 * @param array    $data
	 * @param string   $filter_name
	 *
	 * @return string $text
	 */
	private function format_user_for_display( $user, $display, $data, $filter_name = 'get_autocomplete' ) {
		$text = '';

		switch ( $display ) {
			case 'minimal':
				$text = $user->display_name;
				break;
			case 'detailed':
				$text = sprintf( '%s (%s)', $user->display_name, $user->user_email );
				break;
		}

		return $text;
	}

	/**\
	 * @param string $control_name
	 *
	 * @return mixed|null
	 */
	private function get_widget_settings( $control_name ) {
		return isset( $this->widget_settings[ $control_name ] ) ? $this->widget_settings[ $control_name ] : null;
	}

	public function get_query( array $settings, $post_type = 'post' ) {
		$this->widget_settings = $settings;
		$this->post_type       = $post_type;
		$this->build_query_args( $settings );

		if ( 'current_query' === $settings['query_source'] ) {
			global $wp_query;
			$query = $wp_query;
		} else {
			$query_args = $this->get_query_args();

			$query = new \WP_Query( $query_args );
		}

		return $query;
	}

	public function get_query_args() {
		return $this->query_args;
	}

	private function build_query_args( $settings ) {
		if ( 'current_query' === $settings['query_source'] ) {
			global $wp_query;

			$this->query_args = $wp_query->query_vars;

			$this->query_args['nopaging']    = false;
			$this->query_args['post_status'] = 'publish';

			$this->query_args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		} else {
			$number  = ! empty( $settings['query_number'] ) ? $settings['query_number'] : get_option( 'posts_per_page' );
			$orderby = ! empty( $settings['query_orderby'] ) ? $settings['query_orderby'] : 'date';
			$order   = ! empty( $settings['query_order'] ) ? $settings['query_order'] : 'DESC';

			$number = intval( $number );

			if ( -1 === $number ) {
				// Use big number instead of -1 to make query offset working properly.
				$number = 9999;
			}

			$this->query_args = array(
				'post_type'      => $this->post_type,
				'posts_per_page' => $number,
				'orderby'        => $orderby,
				'order'          => $order,
				'post_status'    => 'publish',
			);

			if ( ! empty( $settings['query_offset'] ) ) {
				$this->query_args['offset'] = $settings['query_offset'];
			}

			if ( in_array( $settings['query_orderby'], [ 'meta_value', 'meta_value_num' ], true ) ) {
				$this->query_args['meta_key'] = $settings['query_sort_meta_key'];
			}

			$this->set_terms_args();
			$this->set_author_args();

			if ( get_query_var( 'paged' ) ) {
				$this->query_args['paged'] = get_query_var( 'paged' );
			} elseif ( get_query_var( 'page' ) ) {
				$this->query_args['paged'] = get_query_var( 'page' );
			} else {
				$this->query_args['paged'] = 1;
			}
		}
	}

	/**
	 * @param string $key
	 * @param mixed  $value
	 */
	private function set_query_arg( $key, $value ) {
		if ( ! isset( $this->query_args[ $key ] ) ) {
			$this->query_args[ $key ] = $value;
		}
	}

	private function build_terms_query( $control_id, $exclude = false ) {
		$settings_terms = $this->get_widget_settings( $control_id );

		if ( empty( $settings_terms ) ) {
			return;
		}

		$terms = [];

		// Switch to term_id in order to get all term children (sub-categories):
		foreach ( $settings_terms as $id ) {
			$term_data = get_term_by( 'term_taxonomy_id', $id );

			if ( false !== $term_data ) {
				$taxonomy             = $term_data->taxonomy;
				$terms[ $taxonomy ][] = $term_data->slug;
			}
		}

		$this->insert_tax_query( $terms, $exclude );
	}

	private function insert_tax_query( $terms, $exclude ) {
		$tax_query = [];
		foreach ( $terms as $taxonomy => $ids ) {
			$query = [
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $ids,
			];

			if ( $exclude ) {
				$query['operator'] = 'NOT IN';
			}

			$tax_query[] = $query;
		}

		if ( empty( $tax_query ) ) {
			return;
		}

		if ( empty( $this->query_args['tax_query'] ) ) {
			$this->query_args['tax_query'] = $tax_query;
		} else {
			$this->query_args['tax_query']['relation'] = 'AND';
			$this->query_args['tax_query'][]           = $tax_query;
		}
	}

	private function set_terms_args() {
		$this->build_terms_query( 'query_include_term_ids' );
		$this->build_terms_query( 'query_exclude_term_ids', true );
	}

	private function set_author_args() {
		$include_authors = $this->get_widget_settings( 'query_include_authors' );
		if ( ! empty( $include_authors ) ) {
			$this->set_query_arg( 'author__in', $include_authors );
		}

		$exclude_authors = $this->get_widget_settings( 'query_exclude_authors' );
		if ( ! empty( $exclude_authors ) ) {
			//exclude only if not explicitly included
			if ( empty( $this->query_args['author__in'] ) ) {
				$this->set_query_arg( 'author__not_in', $exclude_authors );
			}
		}
	}
}

Module_Query_Base::instance()->initialize();
