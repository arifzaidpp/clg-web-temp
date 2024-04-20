<?php
/**
 * Template for displaying single lesson
 *
 * @since         v.1.0.0
 *
 * @author        Themeum
 * @url https://themeum.com
 *
 * @package       TutorLMS/Templates
 * @version       1.4.3
 *
 * @theme-since   2.0.2
 * @theme-version 2.8.0
 */

defined( 'ABSPATH' ) || exit;

get_tutor_header();

global $post;
$currentPost = $post;

$enable_spotlight_mode = tutor_utils()->get_option( 'enable_spotlight_mode' );

$course_id = Unicamp_Tutor::instance()->get_course_id_by_lessons_id( get_the_ID() );
?>
	<div class="page-content">
		<?php do_action( 'tutor_lesson/single/before/wrap' ); ?>

		<?php
		$enable_spotlight_mode = tutor_utils()->get_option( 'enable_spotlight_mode' );
		$wrapper_class         = 'tutor-single-lesson-wrap';

		if ( $enable_spotlight_mode ) {
			$wrapper_class .= ' tutor-spotlight-mode';
		}
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>">
			<div class="tutor-lesson-sidebar-wrap">

				<div class="tutor-lesson-sidebar-inner">
					<a href="#" class="btn-toggle-lesson-sidebar"><i class="far fa-exchange"></i></a>
					<div id="tutor-lesson-sidebar" class="tutor-lesson-sidebar">

						<!-- Start: Sidebar -->
						<?php do_action( 'tutor_lesson/single/before/lesson_sidebar' ); ?>

						<div class="tutor-sidebar-tabs-wrap">
							<div class="tutor-tabs-btn-group">
								<a href="#tutor-lesson-sidebar-tab-content" class="active"> <i
										class="tutor-icon-education"></i>
									<span> <?php esc_html_e( 'Lesson List', 'unicamp' ); ?></span></a>
							</div>

							<div class="tutor-sidebar-tabs-content">

								<div id="tutor-lesson-sidebar-tab-content" class="tutor-lesson-sidebar-tab-item">
									<?php
									$topics = tutor_utils()->get_topics( $course_id );
									if ( $topics->have_posts() ) {
										while ( $topics->have_posts() ) {
											$topics->the_post();
											$topic_id      = get_the_ID();
											$topic_summery = get_the_content();
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

													if ( $lessons->have_posts() ) :
														while ( $lessons->have_posts() ) : $lessons->the_post(); ?>
															<?php
															global $post;

															$video = tutor_utils()->get_video();
															$video = Unicamp_Tutor::instance()->get_video_info( $video, get_the_ID() );

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

															<div
																class="tutor-course-lesson <?php echo ( $currentPost->ID === get_the_ID() ) ? 'active' : ''; ?>">
																<h5>
																	<?php
																	$lesson_title = "<i class='$lesson_icon'></i>";

																	$lesson_title .= get_the_title();
																	$lesson_title .= $play_time ? "<span class='tutor-lesson-duration'>" . tutor_utils()->get_optimized_duration( $play_time ) . "</span>" : '';
																	echo apply_filters( 'tutor_course/contents/lesson/title', $lesson_title, get_the_ID() );
																	?>
																</h5>
															</div>

														<?php
														endwhile;
														$lessons->reset_postdata();
													endif;
													?>

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

							</div>

						</div>

						<?php do_action( 'tutor_lesson/single/after/lesson_sidebar' ); ?>
						<!-- END: Sidebar -->
					</div>
				</div>
			</div>
			<div id="tutor-single-entry-content"
			     class="tutor-lesson-content tutor-single-entry-content tutor-single-entry-content-<?php the_ID(); ?>">
				<?php
				$jsonData                                 = array();
				$jsonData['post_id']                      = get_the_ID();
				$jsonData['best_watch_time']              = 0;
				$jsonData['autoload_next_course_content'] = (bool) get_tutor_option( 'autoload_next_course_content' );
				?>

				<?php do_action( 'tutor_lesson/single/before/content' ); ?>

				<div class="container">
					<div class="row">
						<div class="col-md-12">

							<div class="tutor-single-page-top-bar">
								<div class="tutor-topbar-item tutor-top-bar-course-link">
									<a href="<?php echo esc_url( get_the_permalink( $course_id ) ); ?>"
									   class="tutor-topbar-home-btn">
										<i class="far fa-home"></i><?php esc_html_e( 'Go to course home', 'unicamp' ); ?>
									</a>
								</div>
								<div class="tutor-topbar-item tutor-topbar-content-title-wrap">
									<?php
									$video = tutor_utils()->get_video();
									$video = Unicamp_Tutor::instance()->get_video_info( $video, get_the_ID() );

									$play_time = false;
									if ( $video ) {
										$play_time = $video->playtime;
									}

									$lesson_type      = $play_time ? 'video' : 'document';
									$lesson_type_icon = 'video' === $lesson_type ? 'far fa-play-circle' : 'far fa-file-alt';
									?>
									<span class="lesson-type-icon">
										<i class="<?php echo esc_attr( $lesson_type_icon ); ?>"></i>
									</span>
									<?php the_title(); ?>
								</div>
							</div>

							<div class="tutor-lesson-content-area">
								<input type="hidden" id="tutor_video_tracking_information"
								       value="<?php echo esc_attr( json_encode( $jsonData ) ); ?>">
								<?php tutor_lesson_video(); ?>
								<?php the_content(); ?>
								<?php get_tutor_posts_attachments(); ?>
								<?php tutor_next_previous_pagination(); ?>
							</div>

						</div>
					</div>
				</div>

				<?php do_action( 'tutor_lesson/single/after/content' ); ?>
			</div>
		</div>
		<?php do_action( 'tutor_lesson/single/after/wrap' ); ?>
	</div>
<?php
get_tutor_footer();
