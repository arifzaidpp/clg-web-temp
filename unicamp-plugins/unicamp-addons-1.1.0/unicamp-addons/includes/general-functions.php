<?php

if ( ! function_exists( 'unicamp_addons_placeholder_img_src' ) ) {
	function unicamp_addons_placeholder_img_src() {
		$src = UNICAMP_ADDONS_ASSETS_URI . '/images/placeholder.jpg';

		return apply_filters( 'unicamp_addons_placeholder_img_src', $src );
	}
}

/**
 * Get full list of course visibility term ids.
 *
 * @return int[]
 */
function unicamp_addons_get_course_visibility_term_ids() {
	if ( ! taxonomy_exists( 'course-visibility' ) ) {
		_doing_it_wrong( __FUNCTION__, 'unicamp_addons_get_course_visibility_term_ids should not be called before taxonomies are registered.', '3.1' );

		return array();
	}

	return array_map(
		'absint',
		wp_parse_args(
			wp_list_pluck(
				get_terms(
					array(
						'taxonomy'   => 'course-visibility',
						'hide_empty' => false,
					)
				),
				'term_taxonomy_id',
				'name'
			),
			array(
				'featured' => 0,
				'rated-1'  => 0,
				'rated-2'  => 0,
				'rated-3'  => 0,
				'rated-4'  => 0,
				'rated-5'  => 0,
			)
		)
	);
}
