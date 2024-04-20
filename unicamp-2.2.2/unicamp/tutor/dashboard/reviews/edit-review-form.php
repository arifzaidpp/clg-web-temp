<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;
?>

<form method="post" id="custom_tutor_update_review_form">
	<div class="tutor-write-review-box tutor-star-rating-container">
		<div class="tutor-form-group">
			<div class="tutor-ratings tutor-ratings-lg tutor-ratings-selectable" tutor-ratings-selectable>
				<?php
				tutor_utils()->star_rating_generator( tutor_utils()->get_rating_value( $rating->rating ) );
				?>
			</div>
		</div>
		<div class="tutor-form-group">
			<textarea name="review"
			          placeholder="<?php esc_attr_e( 'Write a review', 'unicamp' ); ?>"><?php echo stripslashes( $rating->review ); ?></textarea>
		</div>
		<div class="tutor-form-group">
			<button type="submit"
			        class="tutor-button tutor-success"><?php esc_html_e( 'Update Review', 'unicamp' ); ?></button>
		</div>
	</div>
</form>
