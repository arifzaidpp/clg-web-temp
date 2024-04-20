<?php
/**
 * Event Loop Start
 *
 * @author  ThemeMove
 * @package Unicamp/WP-Events-Manager/Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$style = Unicamp::setting( 'event_archive_style' );

$wrapper_classes = [
	'unicamp-main-post',
	'unicamp-grid-wrapper',
	'unicamp-event',
	'unicamp-animation-zoom-in',
	'style-' . $style,
];

$grid_classes = [ 'unicamp-grid' ];

$is_grid = false;

if ( in_array( $style, [ 'list', 'list-02' ] ) ) {
	$grid_classes[] = 'grid-sm-1';
} else {
	$is_grid = true;

	$lg_columns = Unicamp::setting( 'event_archive_lg_columns', 4 );
	$md_columns = 2;
	$sm_columns = 1;

	if ( 'none' !== Unicamp_Global::instance()->get_sidebar_status() ) {
		$lg_columns--;
	}

	$grid_classes[] = "grid-lg-{$lg_columns} grid-md-{$md_columns} grid-sm-{$sm_columns}";

	$grid_options = [
		'type'          => 'grid',
		'columns'       => $lg_columns,
		'columnsTablet' => $md_columns,
		'columnsMobile' => $sm_columns,
		'gutter'        => 30,
	];

	if ( 'grid-03' === $style ) {
		$grid_options['gutter'] = 10;
	}
}

?>
<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
	<?php if ( $is_grid ): ?>
		data-grid="<?php echo esc_attr( wp_json_encode( $grid_options ) ); ?>"
	<?php endif; ?>
>
	<div class="<?php echo esc_attr( implode( ' ', $grid_classes ) ); ?>">
		<div class="grid-sizer"></div>
