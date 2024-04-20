<?php
/**
 * Template part for displaying read more button on loop.
 *
 * Override this template by copying it to unicamp-child/wp-events-manager/loop/read-more.php
 *
 * @author        ThemeMove
 * @package       Unicamp/WP-Events-Manager/Template
 * @version       1.0.0
 */

defined( 'ABSPATH' ) || exit;

Unicamp_Templates::render_button( [
	'link'          => [
		'url' => get_the_permalink(),
	],
	'text'          => esc_html__( 'Get ticket', 'unicamp' ),
	'size'          => 'xs',
	'wrapper_class' => 'event-read-more',
] );
