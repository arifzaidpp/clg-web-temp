<?php
/**
 * @package       TutorLMS/Templates
 * @version       1.4.3
 *
 * @theme-since   1.0.0
 * @theme-version 3.0.0
 */

$isLoggedIn = is_user_logged_in();
$rating     = $isLoggedIn ? tutor_utils()->get_course_rating_by_user() : '';

if ( $isLoggedIn && ( ! empty( $rating->rating ) || ! empty( $rating->review ) ) ) {
	$heading = __( 'Edit review', 'unicamp' );
} else {
	$heading = __( 'Write a review', 'unicamp' );
}

$button_args = [
	'link'        => [
		'url' => '#',
	],
	'text'        => $heading,
	'extra_class' => 'btn-write-course-review',
];

if ( ! $isLoggedIn ) {
	$button_args['extra_class'] .= ' open-popup-login';
} else {
	$button_args['attributes'] = [
		'data-unicamp-toggle' => 'modal',
		'data-unicamp-target' => '#modal-course-review-add',
	];
}
?>

<div class="tutor-course-review-form-wrap">
	<h4 class="tutor-segment-title"><?php echo esc_html( $heading ); ?></h4>

	<?php Unicamp_Templates::render_button( $button_args ); ?>
</div>

<?php if ( $isLoggedIn ) : ?>
	<div class="unicamp-modal modal-course-review-add" id="modal-course-review-add">
		<div class="modal-overlay"></div>
		<div class="modal-content">
			<div class="button-close-modal"></div>
			<div class="modal-content-wrap">
				<div class="modal-content-inner">
					<div class="modal-content-header">
						<h3 class="modal-title"><?php esc_html_e( 'Write a review', 'unicamp' ); ?></h3>
					</div>

					<div class="modal-content-body">
						<form method="post" class="tutor-write-review-form">
							<input type="hidden" name="tutor_course_id" value="<?php echo get_the_ID(); ?>">
							<div class="tutor-write-review-box tutor-star-rating-container">
								<div class="tutor-ratings tutor-ratings-lg tutor-ratings-selectable"
								     tutor-ratings-selectable>
									<?php tutor_utils()->star_rating_generator( tutor_utils()->get_rating_value( $rating->rating ) ); ?>
								</div>
								<div class="tutor-form-group">
									<textarea name="review"
									          placeholder="<?php esc_attr_e( 'Write a review', 'unicamp' ); ?>"><?php echo stripslashes( $rating->review ); ?></textarea>
								</div>

								<div class="form-response-messages"></div>

								<div class="tutor-form-group">
									<div class="button-group">
										<?php Unicamp_Templates::render_button( [
											'link'        => [
												'url' => 'javascript:void(0);',
											],
											'text'        => esc_html__( 'Cancel', 'unicamp' ),
											'extra_class' => 'button-grey',
											'attributes'  => [
												'data-unicamp-toggle'  => 'modal',
												'data-unicamp-target'  => '#modal-course-review-add',
												'data-unicamp-dismiss' => '1',
											],
										] ); ?>
										<div class="tm-button-wrapper">
											<button type="submit"
											        class="custom_tutor_submit_review_btn tutor-button tutor-success"><?php esc_html_e( 'Submit Review', 'unicamp' ); ?></button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
