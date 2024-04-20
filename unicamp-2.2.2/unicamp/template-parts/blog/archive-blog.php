<?php
/**
 * Template part for displaying blog content in home.php, archive.php.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Unicamp
 * @since   1.0
 */

defined( 'ABSPATH' ) || exit;

$style   = Unicamp::setting( 'blog_archive_style', 'grid' );
$classes = [
	'unicamp-main-post',
	'unicamp-grid-wrapper',
	'unicamp-blog',
	'unicamp-animation-zoom-in',
	"unicamp-blog-" . $style,
];

$lg_columns = $md_columns = $sm_columns = 1;

$sidebar_status = Unicamp_Global::instance()->get_sidebar_status();
$is_grid        = false;

// Handle Columns
switch ( $style ) {
	case 'grid':
		$is_grid = true;

		$lg_columns = 4;
		$md_columns = 2;
		$sm_columns = 1;
		break;
}

if ( 'none' !== $sidebar_status && $is_grid ) {
	$lg_columns--;
}

$grid_options = [
	'type'          => ( '1' === Unicamp::setting( 'blog_archive_masonry' ) ) ? 'masonry' : 'grid',
	'columns'       => $lg_columns,
	'columnsTablet' => $md_columns,
	'columnsMobile' => $sm_columns,
	'gutter'        => 30,
];

$template_part = $style;

if ( $is_grid ) {
	$caption_style = Unicamp::setting( 'blog_archive_grid_caption_style' );
	$classes[]     = 'unicamp-blog-caption-style-' . $caption_style;

	$template_part = 'grid-' . $caption_style;
}

if ( have_posts() ) : ?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
		<?php if ( $is_grid ) : ?>
			data-grid="<?php echo esc_attr( wp_json_encode( $grid_options ) ); ?>"
		<?php endif; ?>
	>
		<div class="unicamp-grid">
			<div class="grid-sizer"></div>

			<?php while ( have_posts() ) : the_post();
				$classes = array( 'grid-item', 'post-item' );
				?>
				<div <?php post_class( implode( ' ', $classes ) ); ?>>
					<?php unicamp_load_template( 'blog/content-blog', $template_part ); ?>
				</div>
			<?php endwhile; ?>
		</div>

		<div class="unicamp-grid-pagination">
			<?php Unicamp_Templates::paging_nav(); ?>
		</div>
	</div>

<?php else : ?>
	<?php unicamp_load_template( 'global/content-none' ); ?>
<?php endif; ?>
