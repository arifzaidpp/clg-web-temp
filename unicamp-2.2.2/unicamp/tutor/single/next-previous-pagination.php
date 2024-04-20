<?php

/**
 * Single next previous pagination
 *
 *
 * @author  Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.7
 */

?>

<div class="tutor-next-previous-pagination-wrap">
	<div class="tutor-previous-link-wrap">
		<?php if ( $previous_id ) : ?>
			<?php
			Unicamp_Templates::render_button( [
				'link'        => [
					'url' => get_the_permalink( $previous_id ),
				],
				'text'        => esc_html__( 'Previous', 'unicamp' ),
				'icon'        => 'far fa-angle-left',
				'extra_class' => 'button-grey tutor-previous-link tutor-previous-link-' . $previous_id,
				'wrapper'     => false,
			] );
			?>
		<?php endif; ?>
	</div>

	<div class="tutor-next-link-wrap">
		<?php if ( $next_id ) : ?>
			<?php
			Unicamp_Templates::render_button( [
				'link'        => [
					'url' => get_the_permalink( $next_id ),
				],
				'text'        => esc_html__( 'Next', 'unicamp' ),
				'icon'        => 'far fa-angle-right',
				'icon_align'  => 'right',
				'extra_class' => 'button-grey tutor-next-link tutor-next-link-' . $next_id,
				'wrapper'     => false,
			] );
			?>
		<?php endif; ?>
	</div>
</div>
