<?php
/**
 * Template for displaying continue lesson button.
 *
 * @author        ThemeMove
 * @package       Unicamp/TutorLMS/Templates
 * @theme-since   2.4.0
 * @theme-version 2.4.0
 */

defined( 'ABSPATH' ) || exit;

global $wp_query;

if ( 'lesson' === $wp_query->query['post_type'] ) {
	return;
}

$lesson_url = tutor_utils()->get_course_first_lesson();

if ( empty( $lesson_url ) ) {
	return;
}
?>
<a href="<?php echo esc_url( $lesson_url ); ?>" class="tutor-button tutor-success">
	<?php esc_html_e( 'Continue to lesson', 'unicamp' ); ?>
</a>
