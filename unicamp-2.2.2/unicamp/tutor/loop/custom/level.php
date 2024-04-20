<?php
/**
 * Course Loop Level
 *
 * @package       Unicamp/TutorLMS/Templates
 * @theme-since   2.0.0
 * @theme-version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

global $unicamp_course;

$level = $unicamp_course->get_level();

if ( empty( $level ) ) {
	return;
}

$wrapper_class = 'course-loop-badge-level ' . $level;
?>
<div class="<?php echo esc_attr( $wrapper_class ); ?>">
	<span class="badge-text"><?php echo esc_html( $unicamp_course->get_level_label() ); ?></span>
</div>
