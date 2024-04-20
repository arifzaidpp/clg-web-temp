<?php
/**
 * @package       TutorLMS/Templates
 * @version       1.4.3
 *
 * @theme-since   1.0.0
 * @theme-version 2.0.3
 */

defined( 'ABSPATH' ) || exit;

get_tutor_header( true );
do_action( 'tutor_load_template_before', 'dashboard.create-course', null );

$course_id = (int) isset( $_GET['course_ID'] ) ? sanitize_text_field( $_GET['course_ID'] ) : 0;

$post = get_post( $course_id );

if ( ! $course_id || tutor()->course_post_type != get_post_type( $post ) ) {
	return;
}
setup_postdata( $post );

$can_publish_course = (bool) tutor_utils()->get_option( 'instructor_can_publish_course' ) || current_user_can( 'administrator' );

$course_slug      = $post->post_name;
$course_permalink = get_the_permalink();

if ( ! tutor_utils()->is_instructor( get_current_user_id(), true ) || ! tutor_utils()->can_user_edit_course( get_current_user_id(), $course_id ) ) {
	$args = array(
		'headline'    => __( 'Permission Denied', 'unicamp' ),
		'message'     => __( 'You don\'t have the right to edit this course', 'unicamp' ),
		'description' => __( 'Please make sure you are logged in to correct account', 'unicamp' ),
		'button'      => array(
			'url'  => get_permalink( $course_id ),
			'text' => __( 'View Course', 'unicamp' ),
		),
	);

	tutor_load_template( 'permission-denied', $args );

	return;
}
?>

<?php do_action( 'tutor/dashboard_course_builder_before' ); ?>

	<form action="" id="tutor-frontend-course-builder" class="tutor-frontend-course-builder" method="post"
	      enctype="multipart/form-data">

		<?php wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); ?>

		<?php if ( $post->post_status === 'draft' ) : ?>
			<input name="original_publish" type="hidden" id="original_publish" value="Publish">
		<?php endif; ?>

		<header id="page-header" class="page-header header-dark header-sticky-dark-logo">
			<div class="page-header-place-holder"></div>
			<div id="page-header-inner" class="page-header-inner" data-sticky="1">
				<div class="container">
					<div class="header-wrap">
						<div class="header-left">
							<?php
							$branding_args = [
								'reverse_scheme' => true,
							];
							?>
							<?php unicamp_load_template( 'branding', null, $branding_args ); ?>

							<?php if ( 'draft' === $post->post_status || 'auto-draft' === $post->post_status ) : ?>
								<?php
								Unicamp_Templates::render_button( [
									'link'        => [
										'url' => '#',
									],
									'text'        => esc_html__( 'Save', 'unicamp' ),
									'icon'        => 'fal fa-save',
									'style'       => 'thick-border',
									'extra_class' => 'tutor-btn bordered-btn tutor-dashboard-builder-draft-btn',
									'id'          => 'tutor-course-save-draft',
									'attributes'         => [
										'name' => 'course_submit_btn',
										'value' => 'save_course_as_draft'
									],
								] );
								?>
							<?php endif; ?>
						</div>

						<div class="header-right">
							<div class="header-content-inner">
								<div id="header-right-inner" class="header-right-inner">
									<div class="header-right-inner-content">
										<?php Unicamp_Templates::render_button( [
											'link'        => [
												'url'         => get_the_permalink( $course_id ),
												'is_external' => true,
											],
											'text'        => esc_html__( 'Preview', 'unicamp' ),
											'icon'        => 'fal fa-glasses',
											'extra_class' => 'button-grey',
										] ); ?>

										<div class="form-submit">
											<?php if ( $can_publish_course ) : ?>
												<button class="tutor-button" type="submit"
												        name="course_submit_btn"
												        value="publish_course"><?php esc_html_e( 'Publish Course', 'unicamp' ); ?></button>
											<?php else: ?>
												<button class="tutor-button" type="submit"
												        name="course_submit_btn"
												        value="submit_for_review"><?php esc_html_e( 'Submit for Review', 'unicamp' ); ?></button>
											<?php endif; ?>
										</div>

										<div class="return-dashboard-link">
											<a href="<?php echo tutor_utils()->tutor_dashboard_url(); ?>"> <?php esc_html_e( 'Exit', 'unicamp' ); ?></a>
										</div>
									</div>
								</div>

								<?php Unicamp_Header::instance()->print_more_tools_button(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>

		<div class="tutor-frontend-course-builder-section tm-sticky-parent">
			<div class="container">
				<div class="row">
					<div class="col-lg-8">
						<input type="hidden" value="tutor_add_course_builder" name="tutor_action"/>
						<input type="hidden" name="course_ID" id="course_ID" value="<?php echo get_the_ID(); ?>">
						<input type="hidden" name="post_ID" id="post_ID" value="<?php echo get_the_ID(); ?>">
						<div class="tutor-dashboard-course-builder-wrap tm-sticky-column">

							<!--since 1.8.0 alert message -->
							<?php
							$instructor_can_publish = tutils()->get_option( 'instructor_can_publish_course' );
							?>
							<?php if ( current_user_can( 'tutor_instructor' ) && ! current_user_can( 'administrator' ) ) : ?>
								<?php if ( isset( $_COOKIE['course_submit_for_review'] ) && ! $instructor_can_publish ) : ?>
									<div class="tutor-alert tutor-alert-info">
										<?php esc_html_e( 'Your course has been submitted to the admin. It will be published once it has been reviewed by the admins.', 'unicamp' ); ?>
									</div>
								<?php endif; ?>
							<?php endif; ?>
							<!--alert message end -->

							<?php do_action( 'tutor/dashboard_course_builder_form_field_before' ); ?>

							<div class="tutor-course-builder-section tutor-course-builder-info">
								<div class="tutor-course-builder-section-title">
									<span class="tutor-fs-5 tutor-fw-bold tutor-color-secondary">
										<i class="color-text-brand tutor-icon-angle-up tutor-fs-5" area-hidden="true"></i>
										<span><?php esc_html_e('Course Info', 'unicamp'); ?></span>
									</span>
								</div>

								<div class="tutor-course-builder-section-content">
									<div class="tutor-frontend-builder-item-scope">
										<div class="tutor-form-group">
											<label><?php esc_html_e( 'Course Title', 'unicamp' ); ?></label>
											<input type="text" name="title"
											       value="<?php echo esc_attr( get_the_title() ); ?>"
											       placeholder="<?php esc_attr_e( 'ex. Learn photoshop CS6 from scratch', 'unicamp' ); ?>">
										</div>
									</div> <!--.tutor-frontend-builder-item-scope-->

									<div class="tutor-frontend-builder-item-scope">
										<div class="tutor-form-group">
											<label> <?php esc_html_e( 'Description', 'unicamp' ); ?></label>
											<?php
											$editor_settings = array(
												'media_buttons' => false,
												'quicktags'     => false,
												'editor_height' => 150,
												'textarea_name' => 'content',
											);
											wp_editor( $post->post_content, 'course_description', $editor_settings );
											?>
										</div>
									</div>  <!--.tutor-frontend-builder-item-scope-->

									<?php do_action( 'tutor/frontend_course_edit/after/description', $post ); ?>

									<div class="tutor-frontend-builder-item-scope">
										<div class="tutor-form-group">
											<label>
												<?php esc_html_e( 'Choose a category', 'unicamp' ); ?>
											</label>
											<div class="tutor-form-field-course-categories">
												<?php //echo tutor_course_categories_checkbox($course_id);
												echo tutor_course_categories_dropdown( $course_id, array( 'classes' => 'tutor_select2' ) );
												?>
											</div>
										</div>
									</div>

									<div class="tutor-frontend-builder-item-scope">
										<div class="tutor-form-group">
											<label>
												<?php esc_html_e( 'Choose a tag', 'unicamp' ); ?>
											</label>
											<div class="tutor-form-field-course-tags">
												<?php //echo tutor_course_tags_checkbox($course_id);
												echo tutor_course_tags_dropdown( $course_id, array( 'classes' => 'tutor_select2' ) );
												?>
											</div>
										</div>
									</div>

									<?php
									$monetize_by = tutils()->get_option( 'monetize_by' );
									if ( $monetize_by === 'wc' || $monetize_by === 'edd' ) {
										$course_price    = tutor_utils()->get_raw_course_price( get_the_ID() );
										$currency_symbol = tutor_utils()->currency_symbol();

										$_tutor_course_price_type = tutils()->price_type();
										?>
										<div
											class="tutor-frontend-builder-item-scope tutor-frontend-builder-course-price">
											<div class="tutor-form-group">
												<label><?php esc_html_e( 'Course Price', 'unicamp' ); ?></label>
												<div class="tutor-row tutor-align-items-center">
													<div class="tutor-col-auto">
														<label for="tutor_course_price_type_pro"
														       class="tutor-styled-radio">
															<input id="tutor_course_price_type_pro" type="radio"
															       name="tutor_course_price_type"
															       value="paid" <?php $_tutor_course_price_type ? checked( $_tutor_course_price_type, 'paid' ) : checked( 'true', 'true' ); ?> >
															<span></span>
															<div class="tutor-form-group">
															<span
																class="tutor-input-prepand"><?php echo esc_html( $currency_symbol ); ?></span>
																<input type="text" name="course_price"
																       value="<?php echo esc_attr( $course_price->regular_price ); ?>"
																       placeholder="<?php esc_attr_e( 'Set course price', 'unicamp' ); ?>">
															</div>
														</label>
													</div>
													<div class="tutor-col-auto">
														<label class="tutor-styled-radio">
															<input type="radio" name="tutor_course_price_type"
															       value="free" <?php checked( $_tutor_course_price_type, 'free' ); ?> >
															<span><?php esc_html_e( 'Free', 'unicamp' ); ?></span>
														</label>
													</div>
												</div>
											</div>
										</div> <!--.tutor-frontend-builder-item-scope-->
									<?php } ?>

									<div class="tutor-frontend-builder-item-scope">
										<div class="tutor-form-group">
											<label>
												<?php esc_html_e( 'Course Thumbnail', 'unicamp' ); ?>
											</label>
											<div
												class="tutor-form-field tutor-form-field-course-thumbnail tutor-thumbnail-wrap">
												<?php
												tutor_load_template_from_custom_path(
													tutor()->path . '/views/fragments/thumbnail-uploader.php',
													array(
														'media_id'    => get_post_thumbnail_id($course_id),
														'input_name'  => 'tutor_course_thumbnail_id',
														'placeholder' => tutor()->url . '/assets/images/thumbnail-placeholder.svg',
														'borderless'  => true,
														'background'  => '#E3E6EB',
														'border'      => '#E3E6EB',
													),
													false
												);
												?>
											</div>
										</div>
									</div>
								</div>
							</div>

							<?php do_action( 'tutor/dashboard_course_builder_form_field_after', $post ); ?>

							<div class="tutor-form-row tutor-form-submit">
								<div class="tutor-form-col-12">
									<div class="tutor-form-group">
										<div class="tutor-form-field tutor-course-builder-btn-group">
											<button type="submit" class="tutor-button btn-save-as-draft"
											        name="course_submit_btn"
											        value="save_course_as_draft"><?php esc_html_e( 'Save course as draft', 'unicamp' ); ?></button>
											<?php if ( $can_publish_course ) : ?>
												<button class="tutor-button tutor-success" type="submit"
												        name="course_submit_btn"
												        value="publish_course"><?php esc_html_e( 'Publish Course', 'unicamp' ); ?></button>
											<?php else : ?>
												<button class="tutor-button tutor-success" type="submit"
												        name="course_submit_btn"
												        value="submit_for_review"><?php esc_html_e( 'Submit for Review', 'unicamp' ); ?></button>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="tutor-course-builder-upload-tips tm-sticky-column">
							<h3 class="tutor-course-builder-tips-title">
								<i class="far fa-lightbulb-on"></i><?php esc_html_e( 'Course Upload Tips', 'unicamp' ); ?>
							</h3>
							<ul>
								<li><?php esc_html_e( 'Set the Course Price option or make it free.', 'unicamp' ); ?></li>
								<li><?php esc_html_e( 'Standard size for course thumbnail is 700x430.', 'unicamp' ); ?></li>
								<li><?php esc_html_e( 'Video section controls the course overview video.', 'unicamp' ); ?></li>
								<li><?php esc_html_e( 'Course Builder is where you create & organize a course.', 'unicamp' ); ?></li>
								<li><?php esc_html_e( 'Add Topics in the Course Builder section to create lessons, quizzes, and assignments.', 'unicamp' ); ?></li>
								<li><?php esc_html_e( 'Prerequisites refers to the fundamental courses to complete before taking this particular course.', 'unicamp' ); ?></li>
								<li><?php esc_html_e( 'Information from the Additional Data section shows up on the course single page.', 'unicamp' ); ?></li>
								<li><?php esc_html_e( 'Make Announcements to notify any important notes to all enrolled students at once.', 'unicamp' ); ?></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>

<?php do_action( 'tutor/dashboard_course_builder_after' ); ?>

<?php do_action( 'tutor_load_template_after', 'dashboard.create-course', null ); ?>

<?php
get_tutor_footer( true );
