<?php
/**
 * Template for displaying course tags
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

do_action( 'tutor_course/single/before/tags' );

$course_tags = get_tutor_course_tags();
if ( is_array( $course_tags ) && count( $course_tags ) ) { ?>
	<div class="tutor-course-tags-wrap">
		<div class="course-tags-title">
			<span class="tutor-segment-title-icon heading-color"><i class="fal fa-tags"></i></span>
			<h4 class="tutor-segment-title"><?php esc_html_e( 'Tags', 'unicamp' ); ?></h4>
		</div>
		<div class="tutor-course-tags">
			<?php
			$separator  = esc_html( _x( ', ', 'tag separator', 'unicamp' ) );
			$loop_count = 0;
			foreach ( $course_tags as $course_tag ) {
				if ( $loop_count > 0 ) {
					echo '' . $separator;
				}

				$tag_link = get_term_link( $course_tag->term_id );
				echo "<a href='$tag_link'>$course_tag->name</a>";

				$loop_count++;
			}
			?>
		</div>
	</div>
	<?php
}

do_action( 'tutor_course/single/after/tags' ); ?>
