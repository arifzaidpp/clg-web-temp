<?php
/**
 * Template for displaying Popular Topics section on category page.
 *
 * @since   1.0.0
 *
 * @author  ThemeMove
 * @url https://thememove.com
 *
 * @package Unicamp/TutorLMS/Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$popular_topics = Unicamp_Tutor::instance()->get_popular_topics_by_current_tax();

if ( empty( $popular_topics ) ) {
	return;
}
?>
<div class="course-cat-section popular-topics">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h3 class="archive-section-heading"><?php echo esc_html__( 'Popular Topics', 'unicamp' ); ?></h3>
				<div class="course-cat-section-content">
					<div class="course-popular-topic-list">
						<?php foreach ( $popular_topics as $topic ) : ?>
							<?php
							$topic_link = get_term_link( $topic );
							?>
							<a href="<?php echo esc_url( $topic_link ); ?>"
							   class="popular-topic-link"><?php echo esc_html( $topic->name ); ?></a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
