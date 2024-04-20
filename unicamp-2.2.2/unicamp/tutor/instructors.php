<?php
/**
 * Template for displaying instructors
 *
 * @author  ThemeMove
 * @package Unicamp/TutorLMS/Templates
 * @since   1.0.0
 * @version 1.2.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

$limit        = tutor_utils()->get_option( 'instructors_per_page', 12 );
$current_page = max( 1, tutils()->array_get( 'current_page', $_GET ) );
$offset       = ( $current_page - 1 ) * $limit;

$instructors       = tutor_utils()->get_instructors( $offset, $limit, '', '', '', '', 'approved' );
$total_instructors = Unicamp_Tutor::instance()->get_total_instructors( '', 'approved' );

$total_pages = ceil( $total_instructors / $limit );
?>
	<div id="page-content" class="page-content">
		<div class="container">
			<div class="row">

				<?php Unicamp_Sidebar::instance()->render( 'left' ); ?>

				<div id="page-main-content" class="page-main-content">

					<div class="archive-filter-bars row row-xs-center">
						<div class="archive-filter-bar archive-filter-bar-left col-md-6">
							<div class="archive-result-count">
								<?php
								$result_count_html = sprintf( _n( '%s instructor', '%s instructors', $total_instructors, 'unicamp' ), '<span class="count">' . number_format_i18n( $total_instructors ) . '</span>' );
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
								<?php do_action( 'unicamp_archive_instructor_filter_bar_right_before' ); ?>

								<?php do_action( 'unicamp_archive_instructor_filter_bar_right_after' ); ?>
							</div>
						</div>
					</div>

					<?php if ( ! empty( $instructors ) ) : ?>
						<?php
						$wrapper_classes = [
							'unicamp-main-post',
							'unicamp-grid-wrapper',
							'unicamp-instructors',
						];

						$grid_classes = [ 'unicamp-grid' ];

						$lg_columns = 4;
						$md_columns = 2;
						$sm_columns = 1;

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
						>
							<div class="<?php echo esc_attr( implode( ' ', $grid_classes ) ); ?>">
								<div class="grid-sizer"></div>

								<?php foreach ( $instructors as $instructor ): ?>
									<?php
									$profile_url       = tutor_utils()->profile_url( $instructor->ID );
									$instructor_rating = tutor_utils()->get_instructor_ratings( $instructor->ID );
									$job_title         = get_user_meta( $instructor->ID, '_tutor_profile_job_title', true );
									$total_students    = (int) get_user_meta( $instructor->ID, '_tutor_total_students', true );
									$total_courses     = Unicamp_Tutor::instance()->get_total_courses_by_instructor( $instructor->ID );
									?>
									<div class="grid-item">
										<a href="<?php echo esc_url( $profile_url ); ?>"
										   class="loop-instructor-wrapper">
											<div class="loop-instructor-header">
												<div class="loop-instructor-avatar">
													<?php echo unicamp_get_avatar( $instructor->ID, 150 ); ?>
												</div>
												<div class="loop-instructor-info">
													<h6 class="loop-instructor-name"><?php echo esc_html( $instructor->display_name ); ?></h6>

													<?php
													$instructor_job_classes = 'loop-instructor-job';
													if ( empty( $job_title ) ) {
														$instructor_job_classes .= ' no-job-title';
													}
													?>
													<div class="<?php echo esc_attr( $instructor_job_classes ); ?>">
														<?php echo esc_html( $job_title ); ?>
													</div>

													<div class="loop-instructor-rating">
														<?php if ( $instructor_rating->rating_count > 0 ): ?>
															<?php Unicamp_Templates::render_rating( $instructor_rating->rating_avg ); ?>
															<div class="loop-instructor-rating-average">
																<?php echo '<span class="rating-average">' . Unicamp_Helper::number_format_nice_float( $instructor_rating->rating_avg ) . '</span>/<span class="rating-max-rank">5</span>'; ?>
															</div>
														<?php else: ?>
															<?php Unicamp_Templates::render_rating( $instructor_rating->rating_avg, [
																'style' => '02',
															] ); ?>
														<?php endif; ?>
													</div>
												</div>
											</div>
											<div class="loop-instructor-footer">
												<div class="row-flex">
													<div class="col-grow">
														<div class="loop-instructor-meta">
															<span class="meta-icon far fa-file-alt"></span>
															<span class="meta-value">
																<?php echo esc_html( sprintf(
																	_n( '%s course', '%s courses', $total_courses, 'unicamp' ),
																	number_format_i18n( $total_courses )
																) ); ?>
															</span>
														</div>
													</div>
													<div class="col-shrink">
														<div class="instructor-loop-meta">
															<span class="meta-icon far fa-user"></span>
															<span class="meta-value">
																<?php echo esc_html( sprintf(
																	_n( '%s student', '%s students', $total_students, 'unicamp' ),
																	number_format_i18n( $total_students )
																) ); ?>
															</span>
														</div>
													</div>
												</div>
											</div>
										</a>
									</div>
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
					<?php endif; ?>
				</div>

				<?php Unicamp_Sidebar::instance()->render( 'right' ); ?>

			</div>
		</div>
	</div>
<?php
get_footer();
