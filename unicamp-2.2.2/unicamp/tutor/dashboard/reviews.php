<?php
/**
 * My Own reviews
 *
 * @since   v.1.1.2
 *
 * @author  Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

$reviews = tutor_utils()->get_reviews_by_user();
?>
<h3><?php esc_html_e( 'Reviews', 'unicamp' ); ?></h3>

<div class="tutor-dashboard-content-inner">
	<?php if ( current_user_can( tutor()->instructor_role ) ) : ?>
		<div class="tutor-dashboard-inline-links">
			<ul>
				<li class="active">
					<a href="<?php echo tutor_utils()->get_tutor_dashboard_page_permalink( 'reviews' ); ?>"> <?php esc_html_e( 'Given', 'unicamp' ); ?></a>
				</li>
				<li>
					<a href="<?php echo tutor_utils()->get_tutor_dashboard_page_permalink( 'reviews/received-reviews' ); ?>"> <?php esc_html_e( 'Received', 'unicamp' ); ?></a>
				</li>
			</ul>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $reviews ) ) : ?>
		<div class="dashboard-given-reviews">
			<?php
			foreach ( $reviews as $review ) :
				$profile_url = tutor_utils()->profile_url( $review->user_id );

				?>
				<div class="dashboard-given-review <?php echo 'tutor-review-' . $review->comment_ID; ?>">
					<div class="review-header">
						<div class="review-course-thumbnail">
							<?php
							Unicamp_Image::the_post_thumbnail( [
								'post_id' => $review->comment_post_ID,
								'size'    => '150x92',
							] );
							?>
						</div>
					</div>
					<div class="review-body">
						<div class="review-course-title-wrap">
							<h3 class="review-course-title">
								<span><?php esc_html_e( 'Course: ', 'unicamp' ); ?></span>
								<a href="<?php echo esc_url( get_the_permalink( $review->comment_post_ID ) ); ?>"><?php echo esc_html( get_the_title( $review->comment_post_ID ) ); ?></a>
							</h3>
							<div class="review-links">
								<a href="javascript:void(0);" class="open-tutor-edit-review-modal"
								   data-review-id="<?php echo esc_attr( $review->comment_ID ); ?>"
								   data-unicamp-toggle="modal"
								   data-unicamp-target="#modal-course-review-edit-<?php echo esc_attr( $review->comment_ID ); ?>"
								>
									<i class="far fa-pencil"></i>
									<span><?php esc_html_e( 'Edit Feedback', 'unicamp' ); ?></span>
								</a>
							</div>
						</div>
						<div class="individual-star-rating-wrap">
							<?php Unicamp_Templates::render_rating( $review->rating, [ 'style' => '03' ] ); ?>
							<p class="review-meta"><?php echo sprintf( esc_html__( '%s ago', 'unicamp' ), human_time_diff( strtotime( $review->comment_date ) ) ); ?></p>
						</div>

						<div
							class="review-content"><?php echo wpautop( stripslashes( $review->comment_content ) ); ?></div>
					</div>

				</div>

				<div class="unicamp-modal modal-course-review-edit"
				     id="modal-course-review-edit-<?php echo esc_attr( $review->comment_ID ); ?>">
					<div class="modal-overlay"></div>
					<div class="modal-content">
						<div class="button-close-modal"></div>
						<div class="modal-content-wrap">
							<div class="modal-content-inner">
								<div class="modal-content-header">
									<h3 class="modal-title"><?php esc_html_e( 'Edit Review', 'unicamp' ); ?></h3>
								</div>

								<div class="modal-content-body">
									<form method="post" class="tutor_update_review_form">
										<input type="hidden" name="course_id"
										       value="<?php echo esc_attr( $review->comment_post_ID ); ?>"/>
										<input type="hidden" name="review_id"
										       value="<?php echo esc_attr( $review->comment_ID ); ?>"/>
										<div class="tutor-write-review-box tutor-star-rating-container">
											<div class="tutor-form-group">
												<div class="tutor-ratings tutor-ratings-lg tutor-ratings-selectable"
												     tutor-ratings-selectable>
													<?php
													tutor_utils()->star_rating_generator( tutor_utils()->get_rating_value( $review->rating ) );
													?>
												</div>
											</div>
											<div class="tutor-form-group">
												<textarea name="review"
												          placeholder="<?php esc_attr_e( 'Write a review', 'unicamp' ); ?>"><?php echo stripslashes( esc_textarea( $review->comment_content ) ); ?></textarea>
											</div>
											<div class="tutor-form-group">
												<button type="submit" class="tutor-button tutor-button-primary">
													<?php esc_html_e( 'Update Review', 'unicamp' ); ?>
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>

			<?php endforeach; ?>
		</div>

	<?php else: ?>
		<div class="dashboard-no-content-found">
			<?php esc_html_e( 'You haven\'t given any reviews yet.', 'unicamp' ); ?>
		</div>
	<?php endif; ?>

</div>
