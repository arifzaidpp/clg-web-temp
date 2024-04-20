<?php
if ( ! isset( $settings ) ) {
	$settings = array();
}

global $unicamp_course;

while ( $unicamp_query->have_posts() ) : $unicamp_query->the_post(); ?>
	<?php
	/***
	 * Setup course object.
	 */
	$unicamp_course = new Unicamp_Course();

	tutor_load_template( 'loop.loop-before-content' );
	tutor_load_template( 'loop.custom.content-course-' . $settings['style'] );
	tutor_load_template( 'loop.loop-after-content' );
	?>
<?php endwhile; ?>
