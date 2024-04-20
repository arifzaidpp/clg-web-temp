<?php
/**
 * Show all enrolled courses of my student.
 *
 * @author  ThemeMove
 * @package Unicamp/TutorLMS/Templates
 * @since   2.7.4
 * @version 2.7.4
 */

defined( 'ABSPATH' ) || exit;

$student_id = (int) sanitize_text_field( $_GET['student_id'] );
$student    = get_user_by( 'ID', $student_id );

if ( ! $student instanceof WP_User ) {
	return;
}

$enrolled_courses = Unicamp_Tutor::instance()->get_enrolled_courses_by_my_student( $student->ID );
?>
<div>
	<?php $my_student_page = tutor_utils()->get_tutor_dashboard_page_permalink( 'my-students' ); ?>
	<a class="prev-btn"
	   href="<?php echo esc_url( $my_student_page ); ?>"><span>&leftarrow;</span><?php esc_html_e( 'Back to My Student List', 'unicamp' ); ?>
	</a>
</div>
<h3><?php echo esc_html( sprintf( __( 'My courses get enrolled by %s', 'unicamp' ), $student->display_name ) ); ?></h3>

<?php if ( ! empty( $enrolled_courses ) ) : ?>
	<div class="dashboard-my-student-courses-table dashboard-table-wrapper dashboard-table-responsive">
		<div class="dashboard-table-container">
			<table class="dashboard-table">
				<thead>
				<tr>
					<th class="col-course-info"><?php esc_html_e( 'Course', 'unicamp' ); ?></th>
					<th class="col-total-lessons"><?php esc_html_e( 'Total Lessons', 'unicamp' ); ?></th>
					<th class="col-completed-lessons"><?php esc_html_e( 'Completed Lessons', 'unicamp' ); ?></th>
				</tr>
				</thead>

				<?php
				global $post;
				?>
				<?php foreach ( $enrolled_courses as $post ): ?>
					<?php
					setup_postdata( $post );

					$total_lessons     = tutor_utils()->get_lesson_count_by_course();
					$completed_lessons = tutor_utils()->get_completed_lesson_count_by_course( 0, $student->ID );
					?>
					<tr>
						<td class="td-course-info">
							<a class="course-info" href="<?php the_permalink(); ?>">
								<div class="course-thumbnail">
									<?php Unicamp_Image::the_post_thumbnail( [
										'size' => '80x80',
									] ); ?>
								</div>
								<h6 class="course-title"><?php the_title(); ?></h6>
							</a>
						</td>
						<td class="td-total-lessons">
							<div
								class="heading col-heading-mobile"><?php esc_html_e( 'Total Lessons', 'unicamp' ); ?></div>
							<span><?php echo esc_html( $total_lessons ); ?></span>
						</td>
						<td class="td-completed-lessons">
							<div
								class="heading col-heading-mobile"><?php esc_html_e( 'Completed Lessons', 'unicamp' ); ?></div>
							<span><?php echo esc_html( $completed_lessons ); ?></span>
						</td>
					</tr>
				<?php endforeach; ?>
				<?php wp_reset_postdata(); ?>
			</table>
		</div>
	</div>
<?php else : ?>
	<div class="dashboard-no-content-found">
		<?php esc_html_e( 'This student have not get enroll any your courses yet.', 'unicamp' ); ?>
	</div>
<?php endif; ?>
