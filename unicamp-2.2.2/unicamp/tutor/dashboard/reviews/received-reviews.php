<?php
/**
 * Reviews received
 *
 * @author        Themeum
 * @url https://themeum.com
 * @package       TutorLMS/Templates
 * @since         v.1.2.13
 * @version       1.4.3
 *
 * @theme-since   1.0.0
 * @theme-version 2.6.0
 */

defined( 'ABSPATH' ) || exit;

// Pagination Variable.
$per_page     = tutils()->get_option( 'pagination_per_page', 20 );
$current_page = max( 1, tutor_utils()->avalue_dot( 'current_page', $_GET ) );
$offset       = ( $current_page - 1 ) * $per_page;

$reviews = tutor_utils()->get_reviews_by_instructor( get_current_user_id(), $offset, $per_page );
?>
<h3><?php esc_html_e( 'Reviews', 'unicamp' ); ?></h3>

<div class="tutor-dashboard-content-inner">
	<?php if ( current_user_can( tutor()->instructor_role ) ) : ?>
		<div class="tutor-dashboard-inline-links">
			<ul>
				<li>
					<a href="<?php echo tutor_utils()->get_tutor_dashboard_page_permalink( 'reviews' ); ?>"> <?php esc_html_e( 'Given', 'unicamp' ); ?></a>
				</li>
				<li class="active">
					<a href="<?php echo tutor_utils()->get_tutor_dashboard_page_permalink( 'reviews/received-reviews' ); ?>"> <?php esc_html_e( 'Received', 'unicamp' ); ?></a>
				</li>
			</ul>
		</div>
	<?php endif; ?>

	<?php if ( $reviews->count ) : ?>
		<div class="tutor-dashboard-reviews-wrap">
			<p class="tutor-dashboard-pagination-results-stats">
				<?php
				echo sprintf( esc_html__( 'Showing results %d to %d out of %d', 'unicamp' ), $offset + 1, min( $reviews->count, $offset + 1 + tutor_utils()->count( $reviews->results ) ), $reviews->count );
				?>
			</p>
			<div class="tutor-dashboard-reviews">
				<?php
				foreach ( $reviews->results as $review ) :
					$profile_url = tutor_utils()->profile_url( $review->user_id );
					?>
					<div class="tutor-dashboard-single-review <?php echo 'tutor-review-' . $review->comment_ID; ?>">
						<div class="tutor-dashboard-review-header">
							<div class="review-avatar">
								<a href="<?php echo esc_url( $profile_url ); ?>">
									<?php echo unicamp_get_avatar( $review->user_id, 70 ); ?>
								</a>
							</div>
							<div class="tutor-review-user-info">
								<h4 class="review-name">
									<a href="<?php echo esc_url( $profile_url ); ?>"><?php echo esc_html( $review->display_name ); ?> </a>
								</h4>
								<p class="review-date">
									<?php echo sprintf( esc_html__( '%s ago', 'unicamp' ), human_time_diff( strtotime( $review->comment_date ) ) ); ?>
								</p>
							</div>
						</div>
						<div class="individual-dashboard-review-body">
							<h3 class="tutor-dashboard-review-title">
								<span><?php esc_html_e( 'Course: ', 'unicamp' ); ?></span>
								<a href="<?php echo esc_url( get_the_permalink( $review->comment_post_ID ) ); ?>"><?php echo get_the_title( $review->comment_post_ID ); ?></a>
							</h3>
							<?php Unicamp_Templates::render_rating( $review->rating, [
								'style'         => '03',
								'wrapper_class' => 'individual-star-rating-wrap',
							] ); ?>
							<div
								class="review-content"><?php echo wpautop( stripslashes( $review->comment_content ) ); ?></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php else : ?>
		<div class="dashboard-no-content-found">
			<?php esc_html_e( 'You haven\'t received any reviews yet.', 'unicamp' ); ?>
		</div>
	<?php endif; ?>
</div>

<?php if ( $reviews->count ) : ?>
	<div class="tutor-pagination">
		<?php
		Unicamp_Templates::render_paginate_links( [
			'format'  => '?current_page=%#%',
			'current' => $current_page,
			'total'   => ceil( $reviews->count / $per_page ),
		] )
		?>
	</div>
<?php endif; ?>
