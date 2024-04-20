<?php
/**
 * Category menu on header
 *
 * @package Unicamp
 * @since   1.3.1
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$show_all_links = true;

$default_args = [
	'taxonomy'     => Unicamp_Tutor::instance()->get_tax_category(),
	'orderby'      => 'name',
	'show_count'   => 0,
	'hierarchical' => 0,
	'title_li'     => '',
	'hide_empty'   => 0,
];

$top_args = wp_parse_args( [
	'parent' => 0,
], $default_args );

$categories = get_categories( $top_args );

if ( empty( $categories ) ) {
	return;
}

$menu_class = 'header-category-dropdown';
$item_class = 'cat-item';
?>
<div class="header-category-menu">
	<a href="#" class="header-icon category-menu-toggle">
		<div class="category-toggle-icon">
			<?php echo \Unicamp_Helper::get_file_contents( UNICAMP_THEME_SVG_DIR . '/icon-grid-dots.svg' ); ?>
		</div>
		<div class="category-toggle-text">
			<?php esc_html_e( 'Category', 'unicamp' ); ?>
		</div>
	</a>

	<nav class="header-category-dropdown-wrap">
		<ul class="<?php echo esc_attr( $menu_class ); ?>">
			<?php foreach ( $categories as $category ) : ?>
				<?php
				$has_children = false;
				$sub_args     = wp_parse_args( [
					'parent' => $category->term_id,
				], $default_args );

				$sub_categories = get_categories( $sub_args );

				if ( ! empty( $sub_categories ) || $category->count > 0 ) {
					$has_children = true;
				}
				?>
				<li class="cat-item">
					<a href="<?php echo esc_url( get_term_link( $category ) ); ?>">
						<?php echo esc_html( $category->name ); ?>
						<?php if ( $has_children ): ?>
							<span class="toggle-sub-menu"></span>
						<?php endif; ?>
					</a>

					<?php if ( ! empty( $sub_categories ) ) : ?>
						<ul class="children sub-categories">
							<?php if ( $show_all_links ): ?>
								<li class="cat-item">
									<a href="<?php echo esc_url( get_term_link( $category ) ); ?>">
										<?php printf( esc_html__( 'All %s', 'unicamp' ), $category->name ); ?>
									</a>
								</li>
							<?php endif; ?>

							<?php foreach ( $sub_categories as $sub_category ) : ?>
								<?php
								$has_children       = false;
								$current_item_class = $item_class;

								if ( $sub_category->count > 0 ) {
									$has_children       = true;
									$current_item_class .= ' has-children';
								}
								?>

								<li data-id="<?php echo esc_attr( $sub_category->term_id ); ?>"
								    class="<?php echo esc_attr( $current_item_class ); ?>">
									<a href="<?php echo esc_url( get_term_link( $sub_category ) ); ?>"><?php echo esc_html( $sub_category->name ); ?>
										<?php if ( $has_children ): ?>
											<span class="toggle-sub-menu"></span>
										<?php endif; ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</li>

			<?php endforeach; ?>
		</ul>
	</nav>
</div>
