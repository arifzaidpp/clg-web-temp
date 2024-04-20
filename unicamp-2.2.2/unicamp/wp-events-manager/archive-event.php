<?php
/**
 * The Template for displaying archive events page.
 *
 * Override this template by copying it to unicamp-child/wp-events-manager/archive-event.php
 *
 * @author        ThimPress, leehld
 * @package       WP-Events-Manager/Template
 * @version       2.1.7
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div id="page-content" class="page-content">

	<?php wpems_get_template( 'global/filtering-form.php' ); ?>

	<div class="container">
		<div class="row">

			<?php Unicamp_Sidebar::instance()->render( 'left' ); ?>

			<div class="page-main-content">

				<?php
				/**
				 * tp_event_before_main_content hook
				 *
				 * @hooked tp_event_output_content_wrapper - 10 (outputs opening divs for the content)
				 * @hooked tp_event_breadcrumb - 20
				 */
				do_action( 'tp_event_before_main_content' );
				?>

				<?php
				/**
				 * tp_event_archive_description hook
				 *
				 * @hooked tp_event_taxonomy_archive_description - 10
				 * @hooked tp_event_room_archive_description - 10
				 */
				do_action( 'tp_event_archive_description' );
				?>

				<div class="archive-filter-bars row row-xs-center">
					<div class="archive-filter-bar archive-filter-bar-left col-md-6">
						<div class="archive-result-count">
							<?php
							global $wp_query;
							$result_count = $wp_query->found_posts;

							$result_count_html = sprintf( _n( '%s event', '%s events', $result_count, 'unicamp' ), '<span class="count">' . number_format_i18n( $result_count ) . '</span>' );
							printf(
								wp_kses(
									__( 'We found %s available for you', 'unicamp' ),
									array( 'span' => [ 'class' => [] ] )
								),
								$result_count_html
							);
							?>
						</div>
					</div>

					<div class="archive-filter-bar archive-filter-bar-right col-md-6">
						<div class="inner">
							<form id="archive-form-filtering" class="archive-form-filtering event-form-filtering"
							      method="get">
								<?php
								$options         = Unicamp_Event::instance()->get_filtering_type_options();
								$selected        = Unicamp_Event::instance()->get_selected_type_option();
								$select_settings = [
									'fieldLabel' => esc_html__( 'Event Type:', 'unicamp' ),
								];
								?>
								<select class="unicamp-nice-select event-type" name="filter_type"
								        data-select="<?php echo esc_attr( wp_json_encode( $select_settings ) ); ?>">
									<?php foreach ( $options as $value => $text ) : ?>
										<option
											value="<?php echo esc_attr( $value ); ?>" <?php selected( $selected, $value ); ?> >
											<?php echo esc_html( $text ); ?>
										</option>
									<?php endforeach; ?>
								</select>
								<input type="hidden" name="paged" value="1">
							</form>
						</div>
					</div>
				</div>

				<?php if ( have_posts() ) : ?>
					<?php
					/**
					 * tp_event_before_event_loop hook
					 *
					 * @hooked tp_event_result_count - 20
					 * @hooked tp_event_catalog_ordering - 30
					 */
					do_action( 'tp_event_before_event_loop' );
					?>

					<?php wpems_get_template( 'loop/loop-start.php' ); ?>

					<?php $event_style = Unicamp::setting( 'event_archive_style' ); ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php wpems_get_template_part( 'content-event', $event_style ); ?>
					<?php endwhile; ?>

					<?php wpems_get_template( 'loop/loop-end.php' ); ?>

					<?php
					/**
					 * tp_event_after_event_loop hook
					 *
					 * @hooked tp_event_pagination - 10
					 */
					do_action( 'tp_event_after_event_loop' );
					?>
				<?php else: ?>
					<?php unicamp_load_template( 'global/content-none' ); ?>
				<?php endif; ?>

				<?php
				/**
				 * tp_event_after_main_content hook
				 *
				 * @hooked tp_event_output_content_wrapper_end - 10 (outputs closing divs for the content)
				 */
				do_action( 'tp_event_after_main_content' );
				?>
			</div>

			<?php Unicamp_Sidebar::instance()->render( 'right' ); ?>

		</div>
	</div>
</div>
<?php get_footer(); ?>
