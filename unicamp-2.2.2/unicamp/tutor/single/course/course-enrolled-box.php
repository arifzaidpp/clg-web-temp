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

global $unicamp_course;

$preview_box_classes = 'tutor-price-preview-box';

if ( '01' === Unicamp::setting( 'single_course_layout' ) ) {
	if ( $unicamp_course->has_video() || has_post_thumbnail() ) {
		$preview_box_classes .= ' box-has-media';
	}
}
?>

<div class="<?php echo esc_attr( $preview_box_classes ); ?>">

	<?php if ( '01' === Unicamp::setting( 'single_course_layout' ) ) : ?>
		<div class="tutor-price-box-thumbnail">
			<?php
			if ( $unicamp_course->has_video() ) {
				tutor_course_video();
			} else {
				Unicamp_Image::the_post_thumbnail( [ 'size' => '340x200' ] );
			}
			?>
		</div>

		<?php do_action( 'tutor_course/single/enroll_box/after_thumbnail' ); ?>
	<?php endif; ?>

	<?php tutor_load_template( 'single.course.custom.enroll-box-lead-info' ); ?>

	<?php tutor_load_template( 'single.course.custom.enrolled-action-buttons' ); ?>

	<div class="tutor-course-enrolled-wrap">
		<p>
			<i class="far fa-badge-check"></i>
			<?php
			$enrolled = $unicamp_course->get_enrolled();

			echo sprintf( esc_html__( 'You have been enrolled on %s.', 'unicamp' ), '<span>' . wp_date( get_option( 'date_format' ), strtotime( $enrolled->post_date )
				) . '</span>' );
			?>
		</p>
		<?php do_action( 'tutor_enrolled_box_after' ) ?>

	</div>

</div> <!-- tutor-price-preview-box -->

