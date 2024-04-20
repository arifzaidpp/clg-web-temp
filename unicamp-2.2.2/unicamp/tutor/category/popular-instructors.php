<?php
/**
 * Template for displaying Popular Topics section on category page.
 *
 * @author  ThemeMove
 * @url https://thememove.com
 *
 * @package Unicamp/TutorLMS/Templates
 * @since   1.0.0
 * @version 2.6.0
 */

defined( 'ABSPATH' ) || exit;

$popular_instructors = Unicamp_Tutor::instance()->get_popular_instructors_by_current_tax();

if ( empty( $popular_instructors ) ) {
	return;
}
?>
<div class="course-cat-section popular-instructors">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h3 class="archive-section-heading"><?php echo esc_html__( 'Popular Instructors', 'unicamp' ); ?></h3>
				<div class="course-cat-section-content">
					<div class="tm-swiper tm-slider v-stretch bullets-v-align-below nav-style-01 pagination-style-01 "
					     data-lg-items="4"
					     data-md-items="2"
					     data-sm-items="1"
					     data-lg-gutter="30"
					     data-sm-gutter="15"
					     data-nav="1"
					     data-pagination="1"
					>
						<div class="swiper-inner">
							<div class="swiper-container">
								<div class="swiper-wrapper">
									<?php foreach ( $popular_instructors as $instructor ) : ?>
										<?php
										$total_students    = (int) $instructor->tutor_profile_total_students;
										$total_courses     = Unicamp_Tutor::instance()->get_total_courses_by_instructor( $instructor->ID );
										$profile_url       = tutor_utils()->profile_url( $instructor->ID );
										$instructor_rating = tutor_utils()->get_instructor_ratings( $instructor->ID );
										?>
										<div class="swiper-slide">
											<a href="<?php echo esc_url( $profile_url ); ?>"
											   class="popular-instructor-wrapper">
												<div class="popular-instructor-header">
													<div class="popular-instructor-avatar">
														<?php echo unicamp_get_avatar( $instructor->ID, 150 ); ?>
													</div>
													<div class="popular-instructor-info">
														<h6 class="popular-instructor-name"><?php echo esc_html( $instructor->display_name ); ?></h6>

														<?php if ( ! empty( $instructor->tutor_profile_job_title ) ): ?>
															<div class="popular-instructor-job">
																<?php echo esc_html( $instructor->tutor_profile_job_title ); ?>
															</div>
														<?php endif; ?>

														<?php if ( $instructor_rating->rating_count > 0 ): ?>
															<div class="popular-instructor-rating">
																<?php Unicamp_Templates::render_rating( $instructor_rating->rating_avg ); ?>
																<div class="popular-instructor-rating-average">
																	<?php echo '<span class="rating-average">' . Unicamp_Helper::number_format_nice_float( $instructor_rating->rating_avg ) . '</span>/<span class="rating-max-rank">5</span>'; ?>
																</div>
															</div>
														<?php endif; ?>
													</div>
												</div>
												<div class="popular-instructor-footer">
													<div class="row-flex">
														<div class="col-grow">
															<div class="popular-instructor-meta">
																<span class="meta-icon far fa-file-alt"></span>
																<span class="meta-value">
																<?php
																echo esc_html( sprintf(
																	_n( '%s course', '%s courses', $total_courses, 'unicamp' ),
																	number_format_i18n( $total_courses )
																) );
																?>
															</span>
															</div>
														</div>
														<div class="col-shrink">
															<div class="instructor-loop-meta">
																<span class="meta-icon far fa-user"></span>
																<span class="meta-value">
																<?php
																echo esc_html( sprintf(
																	_n( '%s student', '%s students', $total_students, 'unicamp' ),
																	number_format_i18n( $total_students )
																) );
																?>
															</span>
															</div>
														</div>
													</div>
												</div>
											</a>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
