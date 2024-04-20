<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Tutor_Shortcode' ) ) {
	class Unicamp_Tutor_Shortcode extends Unicamp_Tutor {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_ajax_courses_infinite_load', [ $this, 'course_infinite_load' ] );
			add_action( 'wp_ajax_nopriv_courses_infinite_load', [ $this, 'course_infinite_load' ] );


			add_action( 'wp_ajax_get_course_tabs', [ $this, 'get_courses' ] );
			add_action( 'wp_ajax_nopriv_get_course_tabs', [ $this, 'get_courses' ] );
		}

		public function course_infinite_load() {
			$source     = isset( $_POST['source'] ) ? $_POST['source'] : '';
			$query_vars = $_POST['query_vars'];

			if ( 'custom_query' === $source ) {
				$query_vars = Unicamp_Helper::build_extra_terms_query( $query_vars, $query_vars['extra_tax_query'] );
			}

			$unicamp_query = new WP_Query( $query_vars );

			$response = array(
				'max_num_pages' => $unicamp_query->max_num_pages,
				'found_posts'   => $unicamp_query->found_posts,
				'count'         => $unicamp_query->post_count,
			);

			ob_start();

			if ( $unicamp_query->have_posts() ) :
				global $post;
				global $unicamp_course;
				$unicamp_course_clone = $unicamp_course;

				while ( $unicamp_query->have_posts() ) : $unicamp_query->the_post();
					setup_postdata( $post );

					/**
					 * Setup course object.
					 */
					$unicamp_course = new Unicamp_Course();

					tutor_load_template( 'loop.loop-before-content' );
					tutor_load_template( 'loop.custom.content-course-grid-02' );
					tutor_load_template( 'loop.loop-after-content' );
					?>
				<?php endwhile; ?>
				<?php
				wp_reset_postdata();

				/**
				 * Reset course object.
				 */
				$unicamp_course = $unicamp_course_clone;
			endif;

			$template = ob_get_contents();
			ob_clean();

			$template = preg_replace( '~>\s+<~', '><', $template );

			$response['template'] = $template;

			echo json_encode( $response );

			wp_die();
		}

		public function get_courses() {
			$source = isset( $_POST['source'] ) ? $_POST['source'] : 'latest';
			$number = isset( $_POST['number'] ) ? $_POST['number'] : 10;
			$layout = isset( $_POST['layout'] ) ? $_POST['layout'] : 'grid';

			/**
			 * Important Note:
			 * Used wpdb instead WP_Query because WP_Query auto appended logged author id & their post IDs.
			 * This happening only on admin_ajax
			 */

			global $wpdb;

			$course_post_type = $this->get_course_type();

			$sql_select   = "SELECT {$wpdb->posts}.* FROM {$wpdb->posts}";
			$sql_join     = '';
			$sql_group_by = '';
			$sql_orderby  = " ORDER BY {$wpdb->posts}.post_date DESC";
			$sql_where    = " WHERE {$wpdb->posts}.post_type = '{$course_post_type}' AND {$wpdb->posts}.post_status = 'publish' ";
			$sql_limit    = " LIMIT 0, {$number}";

			switch ( $source ) {
				case 'trending' :
					$sql_join     = " INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )";
					$sql_where    .= " AND ( {$wpdb->postmeta}.meta_key = 'views')";
					$sql_orderby  = " ORDER BY {$wpdb->postmeta}.meta_value+0 DESC";
					$sql_group_by = " GROUP BY {$wpdb->posts}.ID";
					break;
				case 'popular' :
					$sql_join     = " INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )";
					$sql_where    .= " AND ( {$wpdb->postmeta}.meta_key = '_course_total_enrolls')";
					$sql_orderby  = " ORDER BY {$wpdb->postmeta}.meta_value+0 DESC";
					$sql_group_by = " GROUP BY {$wpdb->posts}.ID";
					break;
				case 'featured' :
					$tax_query     = new WP_Tax_Query( [
						'relation' => 'AND',
						array(
							'taxonomy' => 'course-visibility',
							'field'    => 'slug',
							'terms'    => [ 'featured' ],
						),
					] );
					$tax_query_sql = $tax_query->get_sql( $wpdb->posts, 'ID' );

					$sql_join     = $tax_query_sql['join'];
					$sql_where    .= $tax_query_sql['where'];
					$sql_group_by = " GROUP BY {$wpdb->posts}.ID";
					break;
				case 'by_category' :
					$tax_query     = new WP_Tax_Query( [
						'tax_query' => [
							'relation' => 'AND',
							array(
								'taxonomy' => $this->get_tax_category(),
								'terms'    => $_POST['term_id'],
							),
						],
					] );
					$tax_query_sql = $tax_query->get_sql( $wpdb->posts, 'ID' );

					$sql_join     = $tax_query_sql['join'];
					$sql_where    .= $tax_query_sql['where'];
					$sql_group_by = " GROUP BY {$wpdb->posts}.ID";
					break;
			}

			$sql           = "{$sql_select} {$sql_join} {$sql_where} {$sql_group_by} {$sql_orderby} {$sql_limit}";
			$query_results = $wpdb->get_results( $sql, OBJECT );

			$success = false;

			if ( is_array( $query_results ) && count( $query_results ) ) :
				global $post;
				global $unicamp_course;
				$unicamp_course_clone = $unicamp_course;

				ob_start();

				foreach ( $query_results as $post ) :
					setup_postdata( $post );

					/**
					 * Setup course object.
					 */
					$unicamp_course = new Unicamp_Course();

					if ( 'grid' === $layout ) {
						tutor_load_template( 'loop.loop-before-content' );
						tutor_load_template( 'loop.custom.content-course-grid-02' );
						tutor_load_template( 'loop.loop-after-content' );
					} else {
						tutor_load_template( 'loop.custom.loop-before-slide-content' );
						tutor_load_template( 'loop.custom.content-course-carousel-02' );
						tutor_load_template( 'loop.custom.loop-after-slide-content' );
					}
					?>
				<?php endforeach; ?>
				<?php
				wp_reset_postdata();

				/**
				 * Reset course object.
				 */
				$unicamp_course = $unicamp_course_clone;

				$template = ob_get_clean();
				$template = preg_replace( '~>\s+<~', '><', $template );
				$success  = true;
			else :
				$template = esc_html__( 'Sorry, we can not find any courses for this search.', 'unicamp' );
				$success  = false;
			endif;

			$response = [
				'success'  => $success,
				'template' => $template,
			];

			echo json_encode( $response );

			wp_die();
		}
	}
}
