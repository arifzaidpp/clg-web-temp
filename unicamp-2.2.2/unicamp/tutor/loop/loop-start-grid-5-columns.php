<?php
/**
 * Course Loop Start Grid 5 columns
 *
 * @since   v.1.0.0
 * @author  themeum
 * @url https://themeum.com
 *
 * @package Unicamp/TutorLMS/Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$style = 'grid-02';

$wrapper_classes = [
	'unicamp-main-post',
	'unicamp-grid-wrapper',
	'unicamp-courses',
	'unicamp-animation-zoom-in',
	'style-' . $style,
];

$grid_classes = [ 'unicamp-grid' ];

$lg_columns = 5;
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
