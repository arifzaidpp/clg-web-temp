<?php
/**
 * Template for displaying course content
 *
 * @since   v.1.0.0
 *
 * @author  Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

do_action( 'tutor_course/single/before/content' );

global $post;
?>

<div class="tutor-single-course-segment tutor-course-content-wrap">
	<?php if ( ! empty( get_the_content() ) ) { ?>
		<h4 class="tutor-segment-title"><?php esc_html_e( 'About This Course', 'unicamp' ); ?></h4>
	<?php } ?>

	<div class="tutor-course-content-content">
		<?php the_content(); ?>
		<?php tutor_course_tags_html(); ?>
	</div>
</div>

<?php do_action( 'tutor_course/single/after/content' ); ?>
