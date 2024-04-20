<?php
/**
 * Course Loop Start
 *
 * @since   v.1.0.0
 * @author  themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

$style = Unicamp_Tutor::instance()->get_course_archive_style();

$wrapper_classes = [
	'unicamp-main-post',
	'unicamp-grid-wrapper',
	'unicamp-courses',
	'unicamp-animation-zoom-in',
	'style-' . $style,
];

$grid_classes = [ 'unicamp-grid' ];

if ( in_array( $style, [ 'list', 'list-02' ] ) ) {
	$grid_classes[] = 'grid-sm-1';

	$grid_options = [
		'type'    => 'grid',
		'columns' => 1,
		'gutter'  => 30,
	];

	$tooltip_options = [
		'placement'  => 'n',
		'popupClass' => 'course-quick-view-popup',
	];
} else {
	$lg_columns = Unicamp::setting( 'course_archive_lg_columns', 4 );
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
}

?>
<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
     data-grid="<?php echo esc_attr( wp_json_encode( $grid_options ) ); ?>"
	<?php if ( ! empty( $tooltip_options ) ) : ?>
		data-power-tip="<?php echo esc_attr( wp_json_encode( $tooltip_options ) ); ?>"
	<?php endif; ?>
>
	<div class="<?php echo esc_attr( implode( ' ', $grid_classes ) ); ?>">
		<div class="grid-sizer"></div>
