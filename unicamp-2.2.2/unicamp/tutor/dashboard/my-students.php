<?php
/**
 * Show all students of the instructor.
 *
 * @author  ThemeMove
 * @package Unicamp/TutorLMS/Templates
 * @since   2.7.4
 * @version 2.7.4
 */

defined( 'ABSPATH' ) || exit;

$limit        = 20;
$current_page = max( 1, tutils()->array_get( 'current_page', $_GET ) );
$offset       = ( $current_page - 1 ) * $limit;

$my_students    = Unicamp_Tutor::instance()->get_students_by_instructor( 0, $offset, $limit );
$total_students = Unicamp_Tutor::instance()->get_total_students_by_instructor( get_current_user_id() );

$total_pages = ceil( $total_students / $limit );
?>
<h3><?php esc_html_e( 'My Students', 'unicamp' ); ?></h3>

<?php if ( ! empty( $my_students ) ) : ?>
	<div class="dashboard-my-students-table dashboard-table-wrapper dashboard-table-responsive">
		<div class="dashboard-table-container">
			<table class="dashboard-table">
				<tr>
					<th class="col-student-info"><?php esc_html_e( 'Student', 'unicamp' ); ?></th>
					<th class="col-student-actions"><?php esc_html_e( 'Actions', 'unicamp' ); ?></th>
				</tr>

				<?php foreach ( $my_students as $student ): ?>
					<?php
					$profile_url             = tutor_utils()->profile_url( $student->ID );
					$enrolled_courses_action = tutor_utils()->get_tutor_dashboard_page_permalink( 'my-students/enrolled-courses/?student_id=' . $student->ID );
					?>
					<tr>
						<td class="td-student-info">
							<div class="student-info">
								<div class="student-avatar">
									<?php echo unicamp_get_avatar( $student->ID, 70 ); ?>
								</div>
								<h6 class="student-name"><?php echo esc_html( $student->display_name ); ?></h6>
							</div>
						</td>
						<td class="td-student-actions">
							<a href="<?php echo esc_url( $enrolled_courses_action ); ?>"
							   class="student-enrolled-courses-link"><i
									class="fal fa-book-open"></i><?php esc_html_e( 'Enrolled Courses', 'unicamp' ) ?>
							</a>
							<a href="<?php echo esc_url( $profile_url ); ?>" class="student-profile-link"><i
									class="fal fa-eye"></i><?php esc_html_e( 'Profile', 'unicamp' ) ?></a>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>

	<?php if ( $total_pages > 1 ) : ?>
		<div class="unicamp-grid-pagination">
			<?php
			Unicamp_Templates::render_paginate_links( [
				'format'  => '?current_page=%#%',
				'current' => $current_page,
				'total'   => $total_pages,
			] );
			?>
		</div>
	<?php endif; ?>
<?php else : ?>
	<div class="dashboard-no-content-found">
		<?php esc_html_e( 'You do not have any students yet.', 'unicamp' ); ?>
	</div>
<?php endif; ?>
