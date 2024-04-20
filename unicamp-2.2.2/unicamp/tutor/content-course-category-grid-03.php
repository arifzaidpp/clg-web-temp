<?php
/**
 * The template for displaying course category thumbnails within loops
 *
 * @author  ThemeMove
 * @package Unicamp/TutorLMS/Templates
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$icon_id       = get_term_meta( $category->term_id, 'icon_id', true );
$category_link = get_term_link( $category );
?>
<div class="grid-item">
	<a href="<?php echo esc_url( $category_link ); ?>" class="unicamp-box">
		<?php if ( ! empty( $icon_id ) ) : ?>
			<div class="category-icon">
				<?php
				$icon_info = Unicamp_Image::get_attachment_info( $icon_id );

				if ( ! empty( $icon_info ) ) {
					if ( 'image/svg+xml' === $icon_info['type'] ) {
						$icon_path = wp_get_original_image_path( $icon_id );

						echo Unicamp_Helper::get_file_contents( $icon_path );
					}
				}
				?>
			</div>
		<?php endif; ?>
		<div class="category-caption">
			<h4 class="category-name"><?php echo esc_html( $category->name ); ?></h4>

			<?php if ( ! empty( $category->description ) ): ?>
				<div class="category-description"><?php echo esc_html( $category->description ); ?></div>
			<?php endif; ?>
		</div>
	</a>
</div>
