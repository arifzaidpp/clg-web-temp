<?php
/**
 * Template for displaying single course
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

get_header();

$layout = Unicamp::setting( 'single_course_layout' );

tutor_load_template( "content-single-course-{$layout}" );

$jsonData                                 = array();
$jsonData['post_id']                      = get_the_ID();
$jsonData['best_watch_time']              = 0;
$jsonData['autoload_next_course_content'] = (bool) get_tutor_option( 'autoload_next_course_content' );
?>
	<input type="hidden" id="tutor_video_tracking_information"
	       value="<?php echo esc_attr( json_encode( $jsonData ) ); ?>">
<?php
get_footer();
