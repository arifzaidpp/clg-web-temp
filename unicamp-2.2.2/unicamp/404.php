<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link    https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Unicamp
 * @since   1.0
 */

get_header( 'blank' );

$image = Unicamp::setting( 'error404_page_image' );
$title = Unicamp::setting( 'error404_page_title' );
$text  = Unicamp::setting( 'error404_page_text' );
?>
	<?php
	$branding_args = [
		'reverse_scheme' => true,
	];
	?>
	<?php unicamp_load_template( 'branding', null, $branding_args ); ?>

	<div class="page-404-content">
		<div class="container">
			<div class="row row-xs-center full-height">
				<div class="col-md-12">

					<?php if ( $image !== '' ): ?>
						<div class="error-image">
							<img src="<?php echo esc_url( $image ); ?>"
							     alt="<?php esc_attr_e( 'Not Found Image', 'unicamp' ); ?>"/>
						</div>
					<?php endif; ?>

					<?php if ( $title !== '' ): ?>
						<h3 class="error-404-title">
							<?php echo wp_kses( $title, 'unicamp-default' ); ?>
						</h3>
					<?php endif; ?>

					<?php if ( $text !== '' ): ?>
						<div class="error-404-text">
							<?php echo wp_kses( $text, 'unicamp-default' ); ?>
						</div>
					<?php endif; ?>

					<div class="error-buttons">
						<?php
						Unicamp_Templates::render_button( [
							'text' => esc_html__( 'Go back to homepage', 'unicamp' ),
							'link' => [
								'url' => esc_url( home_url( '/' ) ),
							],
							'icon' => 'fal fa-home',
							'id'   => 'btn-return-home',
						] );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php get_footer( 'blank' );
