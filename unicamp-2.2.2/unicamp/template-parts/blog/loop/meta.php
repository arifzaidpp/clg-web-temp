<?php
/**
 * The template for displaying loop post meta.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Unicamp
 * @since   1.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="post-meta">
	<div class="inner">
		<?php Unicamp_Post::instance()->meta_author_template(); ?>
		<?php Unicamp_Post::instance()->meta_date_template(); ?>
	</div>
</div>
