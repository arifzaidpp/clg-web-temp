<?php
/**
 * @package       TutorLMS/Templates
 * @version       1.4.3
 *
 * @theme-since   1.0.0
 * @theme-version 2.7.2
 */

defined( 'ABSPATH' ) || exit;

$current_user_id = get_current_user_id();
! isset( $active_tab ) ? $active_tab = 'my-courses' : 0;

// Map required course status according to page.
$status_map = array(
	'my-courses'                 => 'publish',
	'my-courses/draft-courses'   => 'draft',
	'my-courses/pending-courses' => 'pending',
);

// Set currently required course status fo current tab.
$status = isset( $status_map[ $active_tab ] ) ? $status_map[ $active_tab ] : 'publish';

// Get counts for course tabs.
$count_map = array(
	'publish' => tutor_utils()->get_courses_by_instructor( $current_user_id, 'publish', 0, 0, true ),
	'pending' => tutor_utils()->get_courses_by_instructor( $current_user_id, 'pending', 0, 0, true ),
	'draft'   => tutor_utils()->get_courses_by_instructor( $current_user_id, 'draft', 0, 0, true ),
);

$course_archive_arg = isset( $GLOBALS['tutor_course_archive_arg'] ) ? $GLOBALS['tutor_course_archive_arg']['column_per_row'] : null;
$courseCols         = $course_archive_arg === null ? tutor_utils()->get_option( 'courses_col_per_row', 4 ) : $course_archive_arg;
$per_page           = tutor_utils()->get_option( 'courses_per_page', 10 );
$paged              = ( isset( $_GET['current_page'] ) && is_numeric( $_GET['current_page'] ) && $_GET['current_page'] >= 1 ) ? $_GET['current_page'] : 1;
$offset             = $per_page * ( $paged - 1 );

$results = tutor_utils()->get_courses_by_instructor( $current_user_id, $status, $offset, $per_page );
?>

<h3><?php esc_html_e( 'My Courses', 'unicamp' ); ?></h3>

<div class="tutor-mb-32">
	<ul class="tutor-nav">
		<li class="tutor-nav-item">
			<a class="tutor-nav-link<?php echo $active_tab == 'my-courses' ? ' is-active' : ''; ?>"
			   href="<?php echo esc_url( tutor_utils()->get_tutor_dashboard_page_permalink( 'my-courses' ) ); ?>">
				<?php esc_html_e( 'Publish', 'unicamp' ); ?><?php echo "(" . $count_map['publish'] . ")"; ?>
			</a>
		</li>
		<li class="tutor-nav-item">
			<a class="tutor-nav-link<?php echo $active_tab == 'my-courses/pending-courses' ? ' is-active' : ''; ?>"
			   href="<?php echo esc_url( tutor_utils()->get_tutor_dashboard_page_permalink( 'my-courses/pending-courses' ) ); ?>">
				<?php esc_html_e( 'Pending', 'unicamp' ); ?><?php echo "(" . $count_map['pending'] . ")"; ?>
			</a>
		</li>
		<li class="tutor-nav-item">
			<a class="tutor-nav-link<?php echo $active_tab == 'my-courses/draft-courses' ? ' is-active' : ''; ?>"
			   href="<?php echo esc_url( tutor_utils()->get_tutor_dashboard_page_permalink( 'my-courses/draft-courses' ) ); ?>">
				<?php esc_html_e( 'Draft', 'unicamp' ); ?><?php echo "(" . $count_map['draft'] . ")"; ?>
			</a>
		</li>
	</ul>
</div>

<div class="tutor-dashboard-content-inner unicamp-animation-zoom-in">
	<?php if ( is_array( $results ) && count( $results ) ): ?>
		<?php
		global $post;
		$default_thumbnail_src = tutor()->url . 'assets/images/placeholder.svg';

		?>
		<?php foreach ( $results as $post ): ?>
			<?php
			setup_postdata( $post );

			$course_rating    = tutor_utils()->get_course_rating();
			$avg_rating       = $course_rating->rating_avg;
			$rating_count     = $course_rating->rating_count;
			$id_string_delete = 'tutor_my_courses_delete_' . $post->ID;
			$row_id           = 'tutor-dashboard-my-course-' . $post->ID;
			?>
			<div id="<?php echo $row_id ?>"
			     class="unicamp-box tutor-mycourse-wrap tutor-mycourse-<?php the_ID(); ?>">
				<div class="tutor-mycourse-thumbnail unicamp-image">
					<a href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php Unicamp_Image::the_post_thumbnail( [
								'size' => '480x295',
								'alt'  => get_the_title(),
							] ); ?>
						<?php else: ?>
							<?php echo Unicamp_Image::build_img_tag( [
								'src' => $default_thumbnail_src,
								'alt' => get_the_title(),
							] ) ?>
						<?php endif; ?>
					</a>
				</div>
				<div class="tutor-mycourse-content">
					<div class="tutor-mycourse-rating">

						<?php Unicamp_Templates::render_rating( $avg_rating, [
							'style' => '03',
						] ); ?>
						<span class="rating-count"><?php echo "({$rating_count})"; ?></span>
					</div>
					<h3 class="course-title"><a href="<?php the_permalink(); ?>"
					                            class="link-in-title"><?php the_title(); ?></a></h3>
					<div class="tutor-meta tutor-course-metadata">
						<?php
						$total_lessons     = tutor_utils()->get_lesson_count_by_course();
						$completed_lessons = tutor_utils()->get_completed_lesson_count_by_course();

						$course_duration = get_tutor_course_duration_context();
						$course_students = tutor_utils()->count_enrolled_users_by_course();
						?>
						<ul class="course-meta">
							<?php if ( ! empty( $course_duration ) ) : ?>
								<li class="course-meta-duration">
									<span class="meta-label"><?php esc_html_e( 'Duration:', 'unicamp' ); ?></span>
									<span class="meta-value"><?php echo '' . $course_duration; ?></span>
								</li>
							<?php endif; ?>
							<li class="course-meta-total-enrolled">
								<span class="meta-label"><?php esc_html_e( 'Students:', 'unicamp' ); ?></span>
								<span class="meta-value"><?php echo esc_html( $course_students ); ?></span>
							</li>
						</ul>
					</div>
					<div class="mycourse-footer">
						<div class="tutor-mycourses-stats">
							<?php echo tutor_utils()->tutor_price( tutor_utils()->get_course_price() ); ?>
							<div class="course-actions">
								<a href="<?php echo esc_url( tutor_utils()->course_edit_link( $post->ID ) ); ?>"
								   class="tutor-mycourse-edit edit">
									<i class="fal fa-pencil-alt"></i><?php esc_html_e( 'Edit', 'unicamp' ); ?>
								</a>
								<a href="#" data-tutor-modal-target="<?php echo $id_string_delete; ?>"
								   class="tutor-dashboard-element-delete-btn tutor-iconic-btn tutor-mycourse-delete">
									<i class="fal fa-trash-alt"
									   area-hidden="true"></i><?php esc_html_e( 'Delete', 'unicamp' ) ?>
								</a>

								<?php do_action( 'tutor_course_dashboard_actions_after', $post->ID ); ?>
							</div>
						</div>
					</div>


					<!-- Delete prompt modal -->
					<div id="<?php echo $id_string_delete; ?>" class="tutor-modal modal-delete-my-course">
						<div class="tutor-modal-overlay"></div>
						<div class="tutor-modal-window">
							<div class="tutor-modal-content tutor-modal-content-white">
								<button class="tutor-iconic-btn tutor-modal-close-o" data-tutor-modal-close>
									<span class="tutor-icon-times" area-hidden="true"></span>
								</button>

								<div class="tutor-modal-body tutor-text-center">
									<div class="tutor-mt-48">
										<img class="tutor-d-inline-block"
										     src="<?php echo tutor()->url; ?>assets/images/icon-trash.svg"/>
									</div>

									<div
										class="tutor-fs-3 tutor-fw-medium tutor-color-black tutor-mb-12"><?php esc_html_e( 'Delete This Course?', 'unicamp' ); ?></div>
									<div
										class="tutor-fs-6 tutor-color-muted"><?php esc_html_e( 'Are you sure you want to delete this course permanently from the site? Please confirm your choice.', 'unicamp' ); ?></div>

									<div class="tutor-d-flex tutor-justify-center tutor-my-48">
										<button data-tutor-modal-close class="tutor-btn tutor-btn-outline-primary">
											<?php esc_html_e( 'Cancel', 'unicamp' ); ?>
										</button>
										<button class="tutor-btn tutor-btn-primary tutor-list-ajax-action tutor-ml-20"
										        data-request_data='{"course_id":<?php echo $post->ID; ?>,"action":"tutor_delete_dashboard_course"}'
										        data-delete_element_id="<?php echo $row_id; ?>">
											<?php esc_html_e( 'Yes, Delete This', 'unicamp' ); ?>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>

			</div>
		<?php endforeach; ?>
		<?php wp_reset_postdata(); ?>
	<?php else : ?>
		<div class="dashboard-no-content-found">
			<?php esc_html_e( 'You do not have any courses yet.', 'unicamp' ); ?>
		</div>
	<?php endif; ?>

	<div class="tutor-mt-20">
		<?php
		if ( $count_map[ $status ] > $per_page ) {
			$pagination_data = array(
				'total_items' => $count_map[ $status ],
				'per_page'    => $per_page,
				'paged'       => $paged,
			);

			tutor_load_template_from_custom_path(
				tutor()->path . 'templates/dashboard/elements/pagination.php',
				$pagination_data
			);
		}
		?>
	</div>
</div>
