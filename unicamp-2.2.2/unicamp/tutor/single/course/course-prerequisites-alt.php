<?php
/**
 * Custom template by Unicamp.
 * Unicamp use course-prerequisites-alt.php instead of course-prerequisites.php.
 * Because now there is no way to move it below top lead info section.
 *
 * @theme-since   1.0.0
 * @theme-version 2.4.2
 */

defined( 'ABSPATH' ) || exit;

global $post;

$course_prerequisites_ids = maybe_unserialize( get_post_meta( get_the_ID(), '_tutor_course_prerequisites_ids', true ) );

if ( ! is_array( $course_prerequisites_ids ) || empty( $course_prerequisites_ids ) ) {
	return;
}

$courses = Unicamp_Tutor::instance()->get_courses_by_ids( $course_prerequisites_ids );
?>
<div class="tutor-single-course-segment tutor-course-prerequisites-wrap">
	<h4 class="tutor-segment-title"><?php esc_html_e( 'Course Prerequisites', 'unicamp' ); ?></h4>
	<div class="course-prerequisites-lists-wrap">
		<ul class="prerequisites-course-lists">
			<li class="prerequisites-warning">
				<span class="far fa-exclamation-triangle"></span>
				<?php esc_html_e( 'Please note that this course has the following prerequisites which must be completed before it can be accessed', 'unicamp' ); ?>
			</li>
			<?php foreach ( $courses as $post ) : setup_postdata( $post ); ?>
				<li>
					<a href="<?php the_permalink(); ?>" class="prerequisites-course-item">
						<?php if ( has_post_thumbnail() ) : ?>
							<span class="prerequisites-course-feature-image">
		                        <?php Unicamp_Image::the_post_thumbnail( [ 'size' => '70x50' ] ); ?>
	                        </span>
						<?php endif; ?>
						<span class="prerequisites-course-title">
                            <?php the_title(); ?>
                        </span>
						<?php if ( tutor_utils()->is_completed_course( get_the_ID() ) ) : ?>
							<div class="is-complete-prerequisites-course"><i class="tutor-icon-mark"></i></div>
						<?php endif; ?>
					</a>
				</li>
			<?php endforeach; ?>
			<?php wp_reset_postdata(); ?>
		</ul>
	</div>
</div>
