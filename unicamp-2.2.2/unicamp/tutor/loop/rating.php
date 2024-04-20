<?php
/**
 * A single course loop rating
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
<div class="course-loop-rating">
	<?php Unicamp_Templates::render_rating( $course_rating->rating_avg ); ?>
	<span class="rating-count"><?php echo '(' . $course_rating->rating_count . ')'; ?></span>
</div>
<?php endif; ?>
