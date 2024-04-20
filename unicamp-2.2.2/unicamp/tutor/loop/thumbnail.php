<?php

/**
 * Display loop thumbnail
 *
 * @since   v.1.0.0
 * @author  themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="course-thumbnail unicamp-image">
	<a href="<?php the_permalink(); ?>">
		<?php Unicamp_Image::the_post_thumbnail( [ 'size' => '480x304' ] ); ?>
	</a>
</div>
