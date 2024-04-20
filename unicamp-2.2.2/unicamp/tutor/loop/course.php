<?php
/**
 * A single course loop
 *
 * @since   v.1.0.0
 * @author  themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

$style = Unicamp_Tutor::instance()->get_course_archive_style();

tutor_load_template( 'loop.custom.content-course-' . $style );
