<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Image_Hotspot' ) ) {
	class Unicamp_Image_Hotspot {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 15 );

			add_shortcode( 'tm_image_hotspot', [ $this, 'image_hotspot_template' ] );

			add_shortcode( 'tm_image_hotspot_content', [ $this, 'image_hotspot_content_template' ] );
		}

		public function frontend_scripts() {
			wp_dequeue_style( 'ihotspot' );
			wp_dequeue_script( 'ihotspot-js' );
		}

		public function image_hotspot_template( $atts ) {
			$atts = shortcode_atts( array(
				'id' => '',
			), $atts, 'tm_image_hotspot' );

			$idPost = intval( $atts['id'] );

			$image_hotspot = get_post( $idPost );

			if ( ! $image_hotspot instanceof WP_Post ) {
				return;
			}

			if ( 'publish' !== $image_hotspot->post_status ) {
				return;
			}

			$data_post = get_post_meta( $idPost, 'hotspot_content', true );

			if ( ! $data_post ) {
				$data_post = maybe_unserialize( get_post_field( 'post_content', $idPost ) );
			}

			$maps_images      = ( isset( $data_post['maps_images'] ) ) ? $data_post['maps_images'] : '';
			$data_points      = ( isset( $data_post['data_points'] ) ) ? $data_post['data_points'] : '';
			$pins_image       = ( isset( $data_post['pins_image'] ) ) ? $data_post['pins_image'] : '';
			$pins_image_hover = ( isset( $data_post['pins_image_hover'] ) ) ? $data_post['pins_image_hover'] : '';
			$pins_more_option = wp_parse_args( $data_post['pins_more_option'], array(
				'position'          => 'center_center',
				'custom_top'        => 0,
				'custom_left'       => 0,
				'custom_hover_top'  => 0,
				'custom_hover_left' => 0,
				'pins_animation'    => 'none',
			) );

			if ( empty( $maps_images ) ) {
				return;
			}

			ob_start();
			?>
			<div class="wrap_svl_center">
				<div class="wrap_svl_center_box">
					<div class="wrap_svl" id="<?php echo 'body_drag_' . $idPost; ?>">
						<div class="images_wrap">
							<img src="<?php echo esc_url( $maps_images ); ?>" alt="Hotspot image"/>
						</div>
						<?php if ( is_array( $data_points ) ) : ?>
							<?php foreach ( $data_points as $point ) : ?>
								<?php
								$pins_image       = isset( $data_post['pins_image'] ) ? $data_post['pins_image'] : '';
								$pins_image_hover = isset( $data_post['pins_image_hover'] ) ? $data_post['pins_image_hover'] : '';

								$linkpins                = isset( $point['linkpins'] ) ? $point['linkpins'] : '';
								$link_target             = isset( $point['link_target'] ) ? $point['link_target'] : '_self';
								$pins_image_custom       = isset( $point['pins_image_custom'] ) ? $point['pins_image_custom'] : '';
								$pins_image_hover_custom = isset( $point['pins_image_hover_custom'] ) ? $point['pins_image_hover_custom'] : '';
								$placement               = ! empty( $point['placement'] ) ? $point['placement'] : 'n';
								$pins_id                 = ! empty( $point['pins_id'] ) ? $point['pins_id'] : '';

								$pins_class = 'drag_element tips';

								if ( ! empty( $point['pins_class'] ) ) {
									$pins_class .= ' ' . $point['pins_class'];
								}

								if ( ! empty( $pins_more_option['pins_animation'] ) && $pins_more_option['pins_animation'] !== 'none' ) {
									$pins_class .= ' pins-animation-' . $pins_more_option['pins_animation'];
								}

								if ( $pins_image_custom ) {
									$pins_image = $pins_image_custom;
								}

								if ( $pins_image_hover_custom ) {
									$pins_image_hover = $pins_image_hover_custom;
								}

								$noTooltip = true;
								ob_start();
								?>
								<?php if ( ! empty( $point['content'] ) ) { ?>
									<?php $noTooltip = false; ?>
									<div class="box_view_html">
										<span class="close_ihp">
											<svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
											     viewBox="0 0 1000 1000"
											     enable-background="new 0 0 1000 1000" xml:space="preserve">
													<path
														d="M153.7,153.7C57.9,249.5,10,365.3,10,499c0,135.7,47.9,251.5,143.7,347.3l0,0C249.5,942.1,363.3,990,499,990c135.7,0,251.5-47.9,347.3-143.7C942.1,750.5,990,634.7,990,499c0-135.7-47.9-249.5-143.7-345.3l0,0C750.5,57.9,634.7,10,499,10C365.3,10,249.5,57.9,153.7,153.7z M209.6,211.6l2-2C289.4,129.7,387.2,89.8,499,89.8c113.8,0,209.6,39.9,291.4,121.8c79.8,77.8,119.8,175.6,119.8,287.4c0,113.8-39.9,209.6-119.8,291.4C708.6,870.3,612.8,910.2,499,910.2c-111.8,0-209.6-39.9-287.4-119.8C129.8,708.6,89.8,612.8,89.8,499C89.8,387.2,129.8,289.4,209.6,211.6z"/><path
													d="M293.4,331.3c0,12,4,22,12,29.9L443.1,497L305.4,632.7c-8,8-12,18-12,29.9c0,10,4,18,12,26c8,8,18,12,28,12c12,0,20-4,27.9-10L499,552.9l135.7,137.7c8,6,16,10,28,10c12,0,21.9-4,27.9-10c8-8,12-18,12-28c0-12-4-21.9-12-29.9L554.9,497l135.7-135.7c8-8,12-18,12-27.9c0-12-4-22-12-29.9c-6-8-16-12-25.9-12c-12,0-21.9,4-29.9,12L499,441.1L363.3,303.4c-8-8-18-12-29.9-12c-10,0-20,4-28,12C297.4,311.4,293.4,321.4,293.4,331.3z"/>
											</svg>
										</span>
										<?php echo do_shortcode( $point['content'] ); ?>
									</div>
								<?php } ?>
								<?php
								$view_html = ob_get_clean();

								$pin_wrap_classes = 'point_style ihotspot_tooltop_html';
								if ( $pins_image_hover ) {
									$pin_wrap_classes .= ' has-hover';
								}

								$pin_wrap_style = "top: {$point['top']}%; left: {$point['left']}%";
								?>
								<div class="<?php echo esc_attr( $pins_class ); ?>"
									<?php echo ! empty( $pins_id ) ? 'id="' . $pins_id . '"' : '' ?>
									 style="<?php echo esc_attr( $pin_wrap_style ); ?>"
								>
									<div class="<?php echo esc_attr( $pin_wrap_classes ); ?>"
									     data-placement="<?php echo esc_attr( $placement ); ?>"
									     data-html="<?php echo esc_attr( $view_html ) ?>">
										<?php if ( $linkpins ) { ?>
								<a href="<?php echo esc_url($linkpins); ?>"
									<?php if ($link_target) : ?>
										target="<?php echo esc_attr($link_target); ?>"
									<?php endif; ?>
							<?php } ?>
										<?php
										$pin_style = "top: {$pins_more_option['custom_top']}px; left: {$pins_more_option['custom_left']}px;";
										?>
										<img src="<?php echo esc_url( $pins_image ); ?>"
										     class="pins_image <?php if ( ! $noTooltip ): ?>ihotspot_hastooltop<?php endif; ?>"
										     style="<?php echo esc_attr( $pin_style ); ?>">
										<?php if ( $pins_image_hover ): ?>
											<?php
											$pin_hover_style = "top: {$pins_more_option['custom_hover_top']}px; left: {$pins_more_option['custom_hover_left']}px;";
											?>
											<img src="<?php echo esc_url( $pins_image_hover ); ?>"
											     class="pins_image_hover <?php if ( ! $noTooltip ): ?>ihotspot_hastooltop<?php endif; ?>"
											     style="<?php echo esc_attr( $pin_hover_style ); ?>">
										<?php endif; ?>

										<?php if ( $linkpins ) : ?>
											</a>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php
			return ob_get_clean();
		}

		public function image_hotspot_content_template( $atts ) {
			$atts = shortcode_atts( array(
				'heading' => '',
				'text'    => '',
			), $atts, 'tm_image_hotspot_content' );

			extract( $atts );

			ob_start();
			?>
			<div class="tm-image-hotspot-content">
				<?php if ( $heading !== '' ) : ?>
					<h6 class="heading"><?php echo esc_html( $heading ); ?></h6>
				<?php endif; ?>

				<?php if ( $text !== '' ) : ?>
					<?php echo '<div class="text">' . $text . '</div>'; ?>
				<?php endif; ?>
			</div>

			<?php
			$output = ob_get_clean();

			return $output;
		}
	}

	Unicamp_Image_Hotspot::instance()->initialize();
}
