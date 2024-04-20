<?php
/**
 * Course Loop Grid Start
 *
 * @since   1.0.0
 * @author  ThemeMove
 * @url https://thememove.com
 *
 * @package Unicamp/TutorLMS/Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$style = 'grid-01';

$wrapper_classes = [
	'unicamp-main-post',
	'unicamp-grid-wrapper',
	'unicamp-courses',
	'style-' . $style,
	'unicamp-animation-zoom-in'
];

$grid_classes = [ 'unicamp-grid' ];

$lg_columns = 4;
$md_columns = 2;
$sm_columns = 1;

$grid_classes[] = "grid-lg-{$lg_columns} grid-md-{$md_columns} grid-sm-{$sm_columns}";

$grid_options = [
	'type'          => 'grid',
	'columns'       => $lg_columns,
	'columnsTablet' => $md_columns,
	'columnsMobile' => $sm_columns,
	'gutter'        => 30,
];

$tooltip_options = [
	'placement'  => 'e',
	'popupClass' => 'course-quick-view-popup',
];
?>
<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
     data-grid="<?php echo esc_attr( wp_json_encode( $grid_options ) ); ?>"
	<?php if ( ! empty( $tooltip_options ) ) : ?>
		data-power-tip="<?php echo esc_attr( wp_json_encode( $tooltip_options ) ); ?>"
	<?php endif; ?>
>
	<div class="<?php echo esc_attr( implode( ' ', $grid_classes ) ); ?>">
		<div class="grid-sizer"></div>
