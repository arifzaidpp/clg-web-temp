<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;

global $post, $product;

$is_quick_view = apply_filters( 'unicamp_content_quick_view', false );

$wrapper_classes = 'woo-single-gallery';

$main_image_size  = apply_filters( 'unicamp_product_main_image_size', '570x658' );
$thumb_image_size = apply_filters( 'unicamp_product_thumb_image_size', '126x145' );

if ( true === $is_quick_view ) {
	$feature_style = 'slider';
	$main_image_size = '470x540';
} else {
	$feature_style = Unicamp_Woo::instance()->get_single_product_style();
}

$classes = "feature-style-$feature_style";

$open_gallery = apply_filters( 'woocommerce_single_product_open_gallery', true );
if ( $open_gallery ) {
	$classes .= ' unicamp-light-gallery';
}

$main_slider_slides_html   = '';
$thumbs_slider_slides_html = '';

$attachment_ids = $product->get_gallery_image_ids();

if ( has_post_thumbnail() ) {
	$thumbnail_id = (int) get_post_thumbnail_id();
	array_unshift( $attachment_ids, $thumbnail_id );
}
?>
<?php if ( ! empty( $attachment_ids ) ) {
	$number_attachments = count( $attachment_ids );
	?>
	<div class="<?php echo esc_attr( $classes ); ?>">
		<?php if ( $feature_style === 'single' ) { ?>
			<?php
			$attachment_id   = $attachment_ids[0];
			$main_image_html = Unicamp_Image::get_attachment_by_id( array(
				'id'   => $attachment_id,
				'size' => $main_image_size,
			) );

			echo '' . $main_image_html;
			?>
		<?php } elseif ( $feature_style === 'slider' ) {
			if ( $number_attachments > 1 ) {
				$wrapper_classes .= ' has-thumbs-slider';
			}
			?>
			<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
				<?php
				foreach ( $attachment_ids as $attachment_id ) {
					$props = wc_get_product_attachment_props( $attachment_id, $post );

					if ( ! $props['url'] ) {
						continue;
					}

					$main_slider_slide_image_classes = array( 'zoom swiper-slide' );

					if ( isset( $thumbnail_id ) && $attachment_id == $thumbnail_id ) {
						$main_slider_slide_image_classes[] = 'product-main-image';
					}

					$attributes_string = 'class="' . esc_attr( implode( ' ', $main_slider_slide_image_classes ) ) . '"';

					if ( $open_gallery ) {
						$sub_html = '';

						if ( ! empty( $props['title'] ) ) {
							$sub_html .= "<h4>{$props['title']}</h4>";
						}

						if ( ! empty( $props['caption'] ) ) {
							$sub_html .= "<p>{$props['caption']}</p>";
						}

						if ( ! empty( $sub_html ) ) {
							$attributes_string .= ' data-sub-html="' . $sub_html . '"';
						}

						$attributes_string .= ' data-src="' . $props['url'] . '"';
					}

					$attributes_string .= ' data-image-id="' . $attachment_id . '"';

					$main_image_html         = Unicamp_Image::get_attachment_by_id( array(
						'id'   => $attachment_id,
						'size' => $main_image_size,
					) );
					$main_slider_slides_html .= sprintf( '<div %s>%s</div>', $attributes_string, $main_image_html );

					$thumbs_image_html         = Unicamp_Image::get_attachment_by_id( [
						'id'   => $attachment_id,
						'size' => $thumb_image_size,
					] );
					$thumbs_slider_slides_html .= '<div class="swiper-slide">' . $thumbs_image_html . '</div>';
				}
				?>
				<div class="tm-swiper unicamp-main-swiper"
				     data-lg-items="1"
				     data-lg-gutter="30"
				     data-speed="1000"
				     data-effect="slide"
				     data-auto-height="1"
				>
					<div class="swiper-inner">
						<div class="swiper-container">
							<div class="swiper-wrapper">
								<?php echo '' . $main_slider_slides_html; ?>
							</div>
						</div>
					</div>
				</div>

				<?php if ( $number_attachments > 1 ) { ?>
					<div class="tm-swiper unicamp-thumbs-swiper"
					     data-lg-items="4"
					     data-lg-gutter="10"
					     data-speed="1000"
					     data-effect="slide"
					     data-slide-to-clicked-slide="1"
					     data-freemode="1"
					>
						<div class="swiper-inner">
							<div class="swiper-container">
								<div class="swiper-wrapper">
									<?php echo '' . $thumbs_slider_slides_html; ?>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		<?php } else { ?>
			<?php
			foreach ( $attachment_ids as $attachment_id ) {
				$props = wc_get_product_attachment_props( $attachment_id, $post );

				if ( ! $props['url'] ) {
					continue;
				}

				$classes     = array( 'zoom' );
				$image_class = implode( ' ', $classes );

				$sub_html = '';

				if ( $props['title'] !== '' ) {
					$sub_html .= "<h4>{$props['title']}</h4>";
				}

				if ( $props['caption'] !== '' ) {
					$sub_html .= "<p>{$props['caption']}</p>";
				}

				$image = Unicamp_Image::get_attachment_by_id( array(
					'id'     => $attachment_id,
					'size'   => 'custom',
					'width'  => 845,
					'height' => 9999,
					'crop'   => false,
				) );

				$_link = $props['url'];

				if ( $open_gallery === false ) {
					$_link = get_the_permalink();
				}

				echo sprintf( '<a href="%s" class="%s" data-sub-html="%s">%s</a>', esc_url( $_link ), esc_attr( $image_class ), $sub_html, $image );
			}
			?>
		<?php } ?>
	</div>
<?php } else {
	echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), esc_attr__( 'Placeholder', 'unicamp' ) ), $post->ID );
}
?>
