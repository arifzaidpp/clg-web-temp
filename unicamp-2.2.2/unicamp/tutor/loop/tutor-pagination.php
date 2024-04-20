<?php
/**
 * A single course loop pagination
 *
 * @since   v.1.0.0
 * @author  themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php do_action( 'tutor_course/archive/pagination/before' ); ?>

<?php Unicamp_Templates::paging_nav(); ?>

<?php do_action( 'tutor_course/archive/pagination/after' ); ?>
