<?php
/**
 * The header.
 *
 * This is the template that displays all of the <head> section
 *
 * @link     https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package  Unicamp
 * @since    1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php html_class(); ?>>
<head>
	<?php Unicamp_THA::instance()->head_top(); ?>
	<meta charset="<?php echo esc_attr( get_bloginfo( 'charset', 'display' ) ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
		<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url', 'display' ) ); ?>">
	<?php endif; ?>
	<?php Unicamp_THA::instance()->head_bottom(); ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php Unicamp::body_attributes(); ?>>

<?php wp_body_open(); ?>

<?php Unicamp_Templates::pre_loader(); ?>

<div id="page" class="site">
	<div class="content-wrapper">
		<?php Unicamp_Templates::slider( 'above' ); ?>

		<?php unicamp_load_template( 'header/entry' ); ?>

		<?php Unicamp_Templates::slider( 'below' ); ?>
		<?php Unicamp_Title_Bar::instance()->render(); ?>
