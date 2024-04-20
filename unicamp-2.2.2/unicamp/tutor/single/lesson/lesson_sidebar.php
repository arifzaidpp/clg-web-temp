<?php
/**
 * Display Topics and Lesson lists for learn
 *
 * @author        themeum
 * @url https://themeum.com
 *
 * @package       TutorLMS/Templates
 * @since         1.0.0
 * @version       1.7.1
 *
 * @theme-since   1.0.0
 * @theme-version 3.2.1
 */

defined( 'ABSPATH' ) || exit;

global $post;

$currentPost = $post;

$course_id = Unicamp_Tutor::instance()->get_course_id_by_lessons_id( $post->ID );

$disable_qa_for_this_course = get_post_meta( $course_id, '_tutor_disable_qa', true );
$enable_q_and_a_on_course   = tutor_utils()->get_option( 'enable_q_and_a_on_course' ) && $disable_qa_for_this_course != 'yes';
?>
<div class="tutor-lesson-sidebar-inner">
	<a href="#" class="btn-toggle-lesson-sidebar"><i class="far fa-exchange"></i></a>
	<div id="tutor-lesson-sidebar" class="tutor-lesson-sidebar">

		<?php do_action( 'tutor_lesson/single/before/lesson_sidebar' ); ?>

		<div class="tutor-sidebar-tabs-wrap">
			<div class="tutor-tabs-btn-group">
				<a href="#tutor-lesson-sidebar-tab-content"
					<?php if ( $enable_q_and_a_on_course ): ?>
						class="active"
					<?php endif; ?>
				>
					<i class="far fa-book-open"></i>
					<span> <?php esc_html_e( 'Lesson List', 'unicamp' ); ?></span></a>
				<?php if ( $enable_q_and_a_on_course ) { ?>
					<a href="#tutor-lesson-sidebar-qa-tab-content">
						<i class="far fa-question-circle"></i>
						<span><?php esc_html_e( 'Browse Q&A', 'unicamp' ); ?></span>
					</a>
				<?php } ?>
			</div>

			<div class="tutor-sidebar-tabs-content">

				<div id="tutor-lesson-sidebar-tab-content" class="tutor-lesson-sidebar-tab-item">
					<?php
					$topics = tutor_utils()->get_topics( $course_id );
					if ( $topics->have_posts() ) {
						while ( $topics->have_posts() ) {
							$topics->the_post();
							$topic_id        = get_the_ID();
							$topic_summery   = get_the_content();
							$lessons         = tutor_utils()->get_course_contents_by_topic( $topic_id, -1 );
							$is_topic_active = ! empty( array_filter( $lessons->posts, function( $content ) use ( $currentPost ) {
								return $content->ID == $currentPost->ID;
							} ) );

							$topic_title_class = 'tutor-accordion-item-header tutor-topics-title';

							if ( $is_topic_active ) {
								$topic_title_class .= ' is-active';
							}
							?>

							<div
								class="tutor-topics-in-single-lesson tutor-course-topic tutor-course-topic-<?php echo esc_attr( $topic_id ); ?>">
								<div class="<?php echo esc_attr( $topic_title_class ); ?>"
								     tutor-course-single-topic-toggler>
									<h3>
										<span class="topic-title">
											<?php the_title(); ?>
										</span>
										<?php if ( ! empty( $topic_summery ) ) : ?>
											<div class="tooltip-wrap">
												<span class="toggle-information-icon">
													<i class='fas fa-info'></i>
												</span>
												<span class="tooltip-txt tooltip-bottom">
													<?php echo $topic_summery; ?>
												</span>
											</div>
										<?php endif; ?>
									</h3>
									<button class="tutor-single-lesson-topic-toggle-alt">
										<i class="tutor-icon-plus"></i>
									</button>
								</div>

								<div class="tutor-accordion-item-body tutor-lessons-under-topic"
								     style="<?php echo $is_topic_active ? ' display: block;' : 'display: none;'; ?>">
									<?php
									do_action( 'tutor/lesson_list/before/topic', $topic_id );

									if ( $lessons->have_posts() ) {
										while ( $lessons->have_posts() ) : $lessons->the_post(); ?>
											<?php if ( $post->post_type === 'tutor_quiz' ) : ?>
												<?php
												$quiz = $post;

												$wrapper_class = 'tutor-single-lesson-items quiz-single-item';
												$wrapper_class .= ' quiz-single-item-' . $quiz->ID;
												if ( $currentPost->ID === get_the_ID() ) {
													$wrapper_class .= ' active';
												}

												$is_pass = true;

												$attempt = tutils()->get_quiz_attempt( $quiz->ID );
												if ( $attempt ) {
													$passing_grade     = tutor_utils()->get_quiz_option( $quiz->ID, 'passing_grade', 0 );
													$earned_percentage = $attempt->earned_marks > 0 ? ( number_format( ( $attempt->earned_marks * 100 ) / $attempt->total_marks ) ) : 0;

													if ( $earned_percentage < $passing_grade ) {
														$is_pass = false;
													}
												} else {
													$is_pass = false;
												}
												?>
												<div class="<?php echo esc_attr( $wrapper_class ); ?>"
												     data-quiz-id="<?php echo esc_attr( $quiz->ID ); ?>">
													<a href="<?php echo esc_url( get_permalink( $quiz->ID ) ); ?>"
													   class="sidebar-single-quiz-a"
													   data-quiz-id="<?php echo esc_attr( $quiz->ID ); ?>">
														<i class="far fa-question-circle"></i>
														<span
															class="lesson_title"><?php echo esc_html( $quiz->post_title ); ?></span>
														<span class="tutor-lesson-right-icons">
                                                    <?php
                                                    do_action( 'tutor/lesson_list/right_icon_area', $post );

                                                    $time_limit = tutor_utils()->get_quiz_option( $quiz->ID, 'time_limit.time_value' );
                                                    if ( $time_limit ) {
	                                                    $time_type = tutor_utils()->get_quiz_option( $quiz->ID, 'time_limit.time_type' );
	                                                    echo "<span class='quiz-time-limit'>{$time_limit} {$time_type}</span>";
                                                    }
                                                    ?>

                                                    <?php
                                                    $lesson_complete_icon = $is_pass ? 'tutor-icon-mark tutor-done' : '';
                                                    echo "<i class='tutor-lesson-complete $lesson_complete_icon'></i>";
                                                    ?>
                                                    </span>
													</a>
												</div>
											<?php elseif ( $post->post_type === 'tutor_assignments' ) : ?>
												<?php
												/**
												 * Assignments
												 *
												 * @since this block v.1.3.3
												 */
												$wrapper_class = 'tutor-single-lesson-items assignments-single-item';
												$wrapper_class .= ' assignment-single-item-' . $post->ID;
												if ( $currentPost->ID === get_the_ID() ) {
													$wrapper_class .= ' active';
												}
												?>
												<div class="<?php echo esc_attr( $wrapper_class ); ?>"
												     data-assignment-id="<?php echo esc_attr( $post->ID ); ?>">
													<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>"
													   class="sidebar-single-assignment-a"
													   data-assignment-id="<?php echo esc_attr( $post->ID ); ?>">
														<i class="far fa-file-edit"></i>
														<span
															class="lesson_title"> <?php echo esc_html( $post->post_title ); ?> </span>
														<span class="tutor-lesson-right-icons">
                                                    <?php do_action( 'tutor/lesson_list/right_icon_area', $post ); ?>
                                                </span>
													</a>
												</div>
											<?php elseif ( $post->post_type === 'tutor_zoom_meeting' ) : ?>
												<?php
												/**
												 * Zoom Meeting
												 *
												 * @since this block v.1.7.1
												 */
												?>
												<div
													class="tutor-single-lesson-items zoom-meeting-single-item zoom-meeting-single-item-<?php echo $post->ID; ?> <?php echo ( $currentPost->ID === get_the_ID() ) ? 'active' : ''; ?>"
													data-assignment-id="<?php echo $post->ID; ?>">
													<a href="<?php echo get_permalink( $post->ID ); ?>"
													   class="sidebar-single-zoom-meeting-a">
														<i class="zoom-icon far fa-users"></i>
														<span
															class="lesson_title"><?php echo esc_html( $post->post_title ); ?></span>
														<span class="tutor-lesson-right-icons">
	                                                        <?php do_action( 'tutor/lesson_list/right_icon_area', $post ); ?>
	                                                    </span>
													</a>
												</div>
											<?php else : ?>
												<?php
												/**
												 * Lesson
												 */

												$video = tutor_utils()->get_video();
												$video = Unicamp_Tutor::instance()->get_video_info( $video, get_the_ID() );

												$play_time = false;
												if ( $video ) {
													$play_time = $video->playtime;
												}
												$is_completed_lesson = tutor_utils()->is_completed_lesson();

												$wrapper_class = 'tutor-single-lesson-items';
												if ( $currentPost->ID === get_the_ID() ) {
													$wrapper_class .= ' active';
												}
												?>
												<div class="<?php echo esc_attr( $wrapper_class ); ?>">
													<a href="<?php the_permalink(); ?>" class="tutor-single-lesson-a"
													   data-lesson-id="<?php echo esc_attr( get_the_ID() ); ?>">

														<?php
														$tutor_lesson_type_icon = $play_time ? 'video' : 'document';
														?>
														<?php if ( 'video' === $tutor_lesson_type_icon ) : ?>
															<i class="far fa-play-circle"></i>
														<?php else : ?>
															<i class="far fa-file-alt"></i>
														<?php endif; ?>
														<span class="lesson_title"><?php the_title(); ?></span>
														<span class="tutor-lesson-right-icons">
                                                        <?php
                                                        do_action( 'tutor/lesson_list/right_icon_area', $post );
                                                        if ( $play_time ) {
	                                                        echo "<i class='tutor-play-duration'>$play_time</i>";
                                                        }
                                                        $lesson_complete_icon = $is_completed_lesson ? 'tutor-icon-mark tutor-done' : '';
                                                        echo "<i class='tutor-lesson-complete $lesson_complete_icon'></i>";
                                                        ?>
                                                    </span>
													</a>
												</div>
											<?php endif; ?>
										<?php endwhile; ?>
										<?php $lessons->reset_postdata(); ?>
									<?php } ?>

									<?php do_action( 'tutor/lesson_list/after/topic', $topic_id ); ?>
								</div>
							</div>

							<?php
						}
						$topics->reset_postdata();
						wp_reset_postdata();
					}
					?>
				</div>

				<div id="tutor-lesson-sidebar-qa-tab-content" class="tutor-lesson-sidebar-tab-item"
				     style="display: none;">
					<?php
					tutor_lesson_sidebar_question_and_answer();
					?>
				</div>

			</div>

		</div>

		<?php do_action( 'tutor_lesson/single/after/lesson_sidebar' ); ?>

	</div>
</div>
