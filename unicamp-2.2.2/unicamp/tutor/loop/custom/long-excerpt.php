<?php
/**
 * Course loop long excerpt
 *
 * @since   1.0.0
 * @author  ThemeMove
 * @url https://thememove.com
 *
 * @package Unicamp/TutorLMS/Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="course-loop-excerpt course-loop-excerpt-collapse-2-rows">
	<?php
	Unicamp_Templates::excerpt( array(
		'limit' => 22,
		'type'  => 'word',
	) );
	?>
</div>
