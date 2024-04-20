<?php
/**
 * Template for displaying Popular Topics section on category page.
 *
 * @since   1.0.0
 *
 * @author  ThemeMove
 * @url https://thememove.com
 *
 * @package Unicamp/TutorLMS/Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var WP_Query $featured_courses
 */
$featured_courses = Unicamp_Tutor::instance()->get_featured_courses_by_current_tax();

if ( empty( $featured_courses ) ) {
	return;
}
?>
<div class="course-cat-section featured-courses-slider">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h3 class="archive-section-heading"><?php echo esc_html__( 'Featured Courses', 'unicamp' ); ?></h3>
				<div class="course-cat-section-content">
					<div class="tm-swiper tm-slider v-stretch bullets-v-align-below nav-style-01 pagination-style-01 unicamp-courses style-carousel-01 unicamp-animation-zoom-in"
					     data-lg-items="auto"
					     data-lg-gutter="30"
					     data-sm-gutter="15"
					     data-nav="1"
					     data-pagination="1"
					>
						<div class="swiper-inner">
							<div class="swiper-container">
								<div class="swiper-wrapper">
									<?php
									global $unicamp_course;
									$unicamp_course_clone = $unicamp_course;
									?>

									<?php while ( $featured_courses->have_posts() ) : $featured_courses->the_post(); ?>
										<?php
										/**
										 * Setup course object.
										 */
										$unicamp_course = new Unicamp_Course();
										?>

										<?php tutor_load_template( 'loop.custom.loop-before-slide-content' ); ?>
										<?php tutor_load_template( 'loop.custom.content-course-carousel-01' ); ?>
										<?php tutor_load_template( 'loop.custom.loop-after-slide-content' ); ?>

									<?php endwhile; ?>
									<?php wp_reset_postdata(); ?>

									<?php
									/**
									 * Reset course object.
									 */
									$unicamp_course = $unicamp_course_clone;
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
