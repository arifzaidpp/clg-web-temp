<?php
/**
 * The template for displaying faq content in the single-faq.php template
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @package Unicamp
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<li class="faq-post">
	<h3 class="post-title title-has-link">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h3>

	<div class="faq-excerpt">
		<?php Unicamp_Templates::excerpt( array(
			'limit' => 50,
			'type'  => 'word',
		) ); ?>
	</div>
	<?php Unicamp_Templates::render_button( [
		'text'          => esc_html__( 'Read Article', 'unicamp' ),
		'link'          => [
			'url' => get_the_permalink(),
		],
		'icon'          => 'fal fa-long-arrow-right',
		'icon_align'    => 'right',
		'style'         => 'text',
		'size'          => 'sm',
		'wrapper_class' => 'faq-read-more-btn',
	] ); ?>
</li>

