<?php
/**
 * Template for displaying single course
 *
 * @since         v.1.0.0
 *
 * @author        Themeum
 * @url https://themeum.com
 *
 * @package       TutorLMS/Templates
 * @version       1.4.3
 *
 * @theme-since   1.0.0
 * @theme-version 2.4.0
 */

defined( 'ABSPATH' ) || exit;

global $unicamp_course;
$topics    = $unicamp_course->get_topics();
$course_id = get_the_ID();
?>

<?php do_action( 'tutor_course/single/before/topics' ); ?>

<?php if ( $topics->have_posts() ) { ?>
	<div class="tutor-single-course-segment tutor-course-topics-wrap">
		<div class="tutor-course-topics-header">
			<div class="tutor-course-topics-header-left">
				<h4 class="tutor-segment-title"><?php esc_html_e( 'Curriculum', 'unicamp' ); ?></h4>
			</div>
			<div class="tutor-course-topics-header-right">
				<?php
				$tutor_lesson_count = $unicamp_course->get_lesson_count();

				if ( $tutor_lesson_count ) {
					echo '<span class="topics-total-lessons">' . sprintf( _n( '%s Lesson', '%s Lessons', $tutor_lesson_count, 'unicamp' ), $tutor_lesson_count ) . '</span>';
				}

				$tutor_course_duration = Unicamp_Tutor::instance()->get_course_duration_short_text( $course_id );
				if ( $tutor_course_duration ) {
					echo '<span class="topics-total-duration">' . $tutor_course_duration . '</span>';
				}
				?>
			</div>
		</div>
		<div class="tutor-course-topics-contents">
			<?php

			$index = 0;

			if ( $topics->have_posts() ) :
				while ( $topics->have_posts() ) :
					$topics->the_post();
					$topic_summery = get_the_content();
					$index++;

					$topic_wrap_class = 'tutor-course-topic tutor-topics-in-single-lesson';
					$title_wrap_class = 'tutor-course-title';

					if ( $index == 1 ) {
						$topic_wrap_class .= ' tutor-active';
					}

					if ( ! empty( $topic_summery ) ) {
						$title_wrap_class .= ' has-summery';
					}
					?>
					<div class="<?php echo esc_attr( $topic_wrap_class ); ?>">
						<div class="<?php echo esc_attr( $title_wrap_class ); ?>">
							<h4>
								<i class="tutor-icon-plus"></i>
								<?php the_title(); ?>
								<?php if ( $topic_summery ) : ?>
									<?php echo "<span class='topic-toggle-description toogle-informaiton-icon'><i class='fas fa-info'></i></span>"; ?>
								<?php endif; ?>
							</h4>
						</div>
						<?php if ( ! empty( $topic_summery ) ) : ?>
							<?php echo '<div class="tutor-topics-summery">' . $topic_summery . '</div>'; ?>
						<?php endif; ?>

						<div class="tutor-course-lessons"
							<?php if ( $index > 1 ): ?>
								style="display: none;"
							<?php endif; ?>
						>
							<?php
							$lessons = tutor_utils()->get_course_contents_by_topic( get_the_ID(), -1 );

							if ( $lessons->have_posts() ) :
								while ( $lessons->have_posts() ) :
									$lessons->the_post();
									global $post;

									$video = tutor_utils()->get_video_info();

									$play_time = false;
									if ( $video ) {
										$play_time = $video->playtime;
									}

									$lesson_icon = $play_time ? 'far fa-play-circle' : 'far fa-file-alt';

									if ( $post->post_type === 'tutor_quiz' ) {
										$lesson_icon = 'far fa-question-circle';
									}
									if ( $post->post_type === 'tutor_assignments' ) {
										$lesson_icon = 'far fa-file-edit';
									}
									?>

									<div class="tutor-course-lesson">
										<h5>
											<?php
											$lesson_title = '';
											if ( has_post_thumbnail() ) {
												$thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
												$lesson_title  .= "<i style='background:url({$thumbnail_url})' class='tutor-lesson-thumbnail-icon $lesson_icon'></i>";
											} else {
												$lesson_title .= "<i class='$lesson_icon'></i>";
											}

											$countdown = '';
											if ( $post->post_type === 'tutor_zoom_meeting' ) {
												$lesson_title = '<i class="far fa-users"></i>';

												$zoom_meeting = tutor_zoom_meeting_data( $post->ID );
												$countdown    = '<div class="tutor-zoom-lesson-countdown tutor-lesson-duration" data-timer="' . $zoom_meeting->countdown_date . '" data-timezone="' . $zoom_meeting->timezone . '"></div>';
											}

											if ( $unicamp_course->is_enrolled() || ( get_post_meta( $course_id, '_tutor_is_public_course', true ) === 'yes' && ! tutor_utils()->is_course_purchasable( $course_id ) ) ) {
												$lesson_title .= "<a href='" . get_the_permalink() . "'> " . get_the_title() . " </a>";

												$lesson_title .= $play_time ? "<span class='tutor-lesson-duration'>$play_time</span>" : '';

												if ( $countdown ) {
													if ( $zoom_meeting->is_expired ) {
														$lesson_title .= '<span class="tutor-zoom-label">' . __( 'Expired', 'unicamp' ) . '</span>';
													} else if ( $zoom_meeting->is_started ) {
														$lesson_title .= '<span class="tutor-zoom-label tutor-zoom-live-label">' . __( 'Live', 'unicamp' ) . '</span>';
													}
													$lesson_title .= $countdown;
												}

												echo '' . $lesson_title;
											} else {
												$lesson_title .= get_the_title();
												$lesson_title .= $play_time ? "<span class='tutor-lesson-duration'>$play_time</span>" : '';

												/**
												 * Can't remove plugin filter.
												 * Then used theme hook instead of.
												 */
												//echo apply_filters( 'tutor_course/contents/lesson/title', $lesson_title, get_the_ID() );
												echo apply_filters( 'unicamp/tutor_course/contents/lesson/title', $lesson_title, get_the_ID() );
											}

											?>
										</h5>
									</div>

								<?php
								endwhile;
								$lessons->reset_postdata();
							endif;
							?>
						</div>
					</div>
				<?php
				endwhile;
				$topics->reset_postdata();
			endif;
			?>
		</div>
	</div>
<?php } ?>

<?php do_action( 'tutor_course/single/after/topics' ); ?>
