<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

$enable_show_reviews_wrote = tutor_utils()->get_option( 'students_own_review_show_at_profile' );
if ( ! $enable_show_reviews_wrote ) {
	return;
}

$profile_user_id = $get_user->ID;

$reviews = tutor_utils()->get_reviews_by_user( $profile_user_id );
?>
<h3><?php echo esc_html( sprintf( __( 'Reviews wrote by %s ', 'unicamp' ), $get_user->display_name ) ); ?></h3>

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
					</div>
					<div class="individual-star-rating-wrap">
						<?php Unicamp_Templates::render_rating( $review->rating, [ 'style' => '03' ] ); ?>
						<p class="review-meta"><?php echo sprintf( esc_html__( '%s ago', 'unicamp' ), human_time_diff( strtotime( $review->comment_date ) ) ); ?></p>
					</div>

					<div
						class="review-content"><?php echo wpautop( stripslashes( $review->comment_content ) ); ?></div>
				</div>

			</div>
		<?php endforeach; ?>
	</div>
<?php else : ?>
	<div class="dashboard-no-content-found">
		<?php esc_html_e( 'Sorry, but you are looking for something that isn\'t here.', 'unicamp' ); ?>
	</div>
<?php endif; ?>
