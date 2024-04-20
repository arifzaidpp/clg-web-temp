<?php
/**
 * Template for category page without term param.
 *
 * @author  ThemeMove
 * @package Unicamp/TutorLMS/Templates
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

$limit        = tutor_utils()->get_option( 'category_per_page', 12 );
$current_page = max( 1, tutils()->array_get( 'current_page', $_GET ) );
$offset       = ( $current_page - 1 ) * $limit;
$total        = wp_count_terms( [
	'taxonomy'   => Unicamp_Tutor::instance()->get_tax_category(),
	'hide_empty' => true,
] );

$total = intval( $total );

$total_pages = ceil( $total / $limit );

$categories = Unicamp_Tutor::instance()->get_course_categories( $offset, $limit );
?>
	<div class="page-content">

		<?php tutor_load_template( 'global.course-form-filter' ); ?>

		<?php if ( have_posts() ) : ?>
			<div class="container">
				<div class="row">

					<?php Unicamp_Sidebar::instance()->render( 'left' ); ?>

					<div class="page-main-content">
						<?php if ( $categories ) : ?>
							<?php
							$wrapper_classes = [
								'unicamp-main-post',
								'unicamp-grid-wrapper',
								'unicamp-course-category-listing',
								'unicamp-animation-zoom-in',
							];

							$grid_style = Unicamp::setting( 'course_category_listing_grid_style' );

							$wrapper_classes[] = 'style-grid-' . $grid_style;

							$grid_classes = [ 'unicamp-grid' ];
							$lg_columns   = intval( Unicamp::setting( 'course_category_listing_lg_columns' ) );
							$md_columns   = Unicamp::setting( 'course_category_listing_md_columns' );
							$sm_columns   = Unicamp::setting( 'course_category_listing_sm_columns' );

							if ( 'none' !== Unicamp_Global::instance()->get_sidebar_status() ) {
								$lg_columns--;
							}

							$grid_classes[] = "grid-lg-{$lg_columns} grid-md-{$md_columns} grid-sm-{$sm_columns}";

							$grid_options = [
								'type'          => 'grid',
								'columns'       => $lg_columns,
								'columnsTablet' => $md_columns,
								'columnsMobile' => $sm_columns,
								'gutter'        => 30,
							];
							?>
							<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
							     data-grid="<?php echo esc_attr( wp_json_encode( $grid_options ) ); ?>"
								<?php if ( ! empty( $tooltip_options ) ) : ?>
									data-power-tip="<?php echo esc_attr( wp_json_encode( $tooltip_options ) ); ?>"
								<?php endif; ?>
							>
								<div class="<?php echo esc_attr( implode( ' ', $grid_classes ) ); ?>">
									<div class="grid-sizer"></div>

									<?php foreach ( $categories as $category ) : ?>
										<?php tutor_load_template( 'content-course-category-grid-' . $grid_style, compact( 'category' ) ); ?>
									<?php endforeach; ?>

								</div>

								<?php if ( $total_pages > 1 ) : ?>
									<div class="unicamp-grid-pagination">
										<?php
										Unicamp_Templates::render_paginate_links( [
											'format'  => '?current_page=%#%',
											'current' => $current_page,
											'total'   => $total_pages,
										] );
										?>
									</div>
								<?php endif; ?>
							</div>
						<?php else : ?>
							<?php esc_html_e( 'No course category available.', 'unicamp' ); ?>
						<?php endif; ?>
					</div>

					<?php Unicamp_Sidebar::instance()->render( 'right' ); ?>

				</div>
			</div>
		<?php else : ?>
		<div class="container">
			<div class="row">
				<div class="page-main-content">
					<?php
					/**
					 * No course found
					 */
					tutor_load_template( 'course-none' );
					?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php get_footer();
