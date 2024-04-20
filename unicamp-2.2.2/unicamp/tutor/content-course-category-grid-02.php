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

$thumbnail_id  = get_term_meta( $category->term_id, 'thumbnail_id', true );
$category_link = get_term_link( $category );
?>
<div class="grid-item">
	<a href="<?php echo esc_url( $category_link ); ?>" class="unicamp-box">
		<?php if ( ! empty( $thumbnail_id ) ) : ?>
			<div class="category-thumbnail unicamp-image">
				<?php Unicamp_Image::the_attachment_by_id( [
					'id'   => $thumbnail_id,
					'size' => '480x285',
				] ); ?>
			</div>
		<?php endif; ?>
		<div class="category-caption">
			<h4 class="category-name"><?php echo esc_html( $category->name ); ?></h4>

			<?php Unicamp_Tutor::instance()->get_course_category_types_html( $category->term_id ); ?>

			<?php /*if ( ! empty( $category->description ) ): */?><!--
				<div class="category-description"><?php /*echo esc_html( $category->description ); */?></div>
			--><?php /*endif; */?>
		</div>
	</a>
</div>
