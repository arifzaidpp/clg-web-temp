<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

$filtering_enable = '1' === Unicamp::setting( 'course_archive_filtering' ) && is_active_sidebar( 'course_top_filters' ) ? true : false;
?>
<div class="archive-filter-bars row row-xs-center">
	<div class="archive-filter-bar archive-filter-bar-left col-md-6">
		<div class="archive-result-count">
			<?php
			global $wp_query;
			$result_count = $wp_query->found_posts;

			$result_count_html = sprintf( _n( '%s course', '%s courses', $result_count, 'unicamp' ), '<span class="count">' . number_format_i18n( $result_count ) . '</span>' );
			printf(
				wp_kses(
					__( 'We found %s available for you', 'unicamp' ),
					array( 'span' => [ 'class' => [] ] )
				),
				$result_count_html
			);
			?>
		</div>
	</div>

	<div class="archive-filter-bar archive-filter-bar-right col-md-6">
		<div class="inner">
			<?php do_action( 'unicamp_before_course_filter_bar_right' ); ?>

			<?php if ( '1' === Unicamp::setting( 'course_archive_sorting' ) ) : ?>
				<div class="archive-orderby-select">
					<?php
					$sorting_options = Unicamp_Tutor::instance()->get_course_sorting_options();

					$select_settings = [
						'fieldLabel' => esc_html__( 'Sort by:', 'unicamp' ),
					];

					$filter_name   = 'orderby';
					$base_link     = Unicamp_Tutor::instance()->get_course_listing_page_url();
					$base_link     = remove_query_arg( $filter_name, $base_link );
					$sort_selected = isset( $_GET[ $filter_name ] ) ? Unicamp_Helper::data_clean( $_GET[ $filter_name ] ) : Unicamp_Tutor::instance()->get_course_default_sort_option();
					?>
					<select class="unicamp-nice-select" name="orderby" id="course-sorting-select"
					        data-select="<?php echo esc_attr( wp_json_encode( $select_settings ) ); ?>">
						<?php foreach ( $sorting_options as $option_key => $option_name ) : ?>
							<?php
							$option_is_set = $option_key === $sort_selected;

							$link = $base_link;

							if ( ! $option_is_set ) {
								$link = add_query_arg( array(
									$filter_name => $option_key,
								), $link );
							}
							?>
							<option
								value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $sort_selected, $option_key ); ?>
								data-url="<?php echo esc_url( $link ) ?>"
							>
								<?php echo esc_html( $option_name ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>

			<?php if ( $filtering_enable ) : ?>
				<?php
				Unicamp_Templates::render_button( [
					'wrapper_class' => 'btn-toggle-course-filters',
					'extra_class'   => 'btn-toggle-archive-top-filters',
					'text'          => esc_html__( 'Filters', 'unicamp' ),
					'link'          => [
						'url' => 'javascript:void(0)',
					],
					'icon'          => 'far fa-filter',
					'id'            => 'btn-toggle-archive-top-filters',
				] );
				?>
			<?php endif; ?>

			<?php do_action( 'unicamp_after_course_filter_bar_right' ); ?>
		</div>
	</div>

	<?php if ( $filtering_enable ) : ?>
		<div id="archive-top-filter-widgets" class="col-md-12 archive-top-filter-widgets">
			<div class="inner">
				<div class="archive-top-filter-content">
					<?php dynamic_sidebar( 'course_top_filters' ); ?>
				</div>

				<?php
				$has_filters = isset( $_GET['filtering'] ) ? true : false;

				if ( $has_filters ) {
					$reset_link = Unicamp_Tutor::instance()->get_course_listing_base_url();

					Unicamp_Templates::render_button( [
						'wrapper_class' => 'btn-reset-filters',
						'extra_class'   => 'button-white',
						'text'          => esc_html__( 'Clear filters', 'unicamp' ),
						'link'          => [
							'url' => $reset_link,
						],
						'icon'          => 'far fa-times',
						'size'          => 'xs',
					] );
				}
				?>
			</div>
		</div>
	<?php endif; ?>

</div>
