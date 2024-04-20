<?php
/**
 * A single course loop rating with detail average.
 *
 * @since   v.1.0.0
 * @author  themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

global $unicamp_course;

$course_rating = $unicamp_course->get_rating();
$rating_count  = intval( $course_rating->rating_count );
?>
<?php if ( $rating_count > 0 ) : ?>
	<div class="course-loop-rating-average">
		<?php Unicamp_Templates::render_rating( $course_rating->rating_avg ); ?>
		<div
			class="course-rating-average"><?php echo '<span class="rating-average">' . Unicamp_Helper::number_format_nice_float( $course_rating->rating_avg ) . '</span><span class="rating-total">/5</span>'; ?></div>
		<span class="rating-count">
			<?php echo '(' . sprintf( _n( '%s rating', '%s ratings', $rating_count, 'unicamp' ), $rating_count ) . ')'; ?>
		</span>
	</div>
<?php endif; ?>
