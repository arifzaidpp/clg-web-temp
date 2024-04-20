<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_WP_Widget_Events' ) ) {
	class Unicamp_WP_Widget_Events extends Unicamp_WP_Widget_Base {

		public function __construct() {
			$this->widget_id          = 'unicamp-wp-widget-events';
			$this->widget_cssclass    = 'unicamp-wp-widget-events';
			$this->widget_name        = esc_html__( '[Unicamp] Events', 'unicamp' );
			$this->widget_description = esc_html__( 'Get list events.', 'unicamp' );
			$this->settings           = array(
				'title'           => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Recent Events', 'unicamp' ),
					'label' => esc_html__( 'Title', 'unicamp' ),
				),
				'filter_by'       => array(
					'type'    => 'select',
					'std'     => 'recent',
					'label'   => esc_html__( 'Filter By', 'unicamp' ),
					'options' => [],
				),
				'num'             => array(
					'type'  => 'number',
					'step'  => 1,
					'min'   => 1,
					'max'   => 40,
					'std'   => 3,
					'label' => esc_html__( 'Number', 'unicamp' ),
				),
				'show_thumbnail'  => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => esc_html__( 'Show Thumbnail', 'unicamp' ),
				),
				'show_price'      => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Show Price', 'unicamp' ),
				),
				'show_categories' => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Show Categories', 'unicamp' ),
				),
				'show_date'       => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => esc_html__( 'Show Date', 'unicamp' ),
				),
				'show_time'       => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => esc_html__( 'Show Time', 'unicamp' ),
				),
			);

			parent::__construct();
		}

		public function set_form_settings() {
			$filter_by_options = array(
				'recent'  => esc_html__( 'Recent Events', 'unicamp' ),
				'related' => esc_html__( 'Related Events', 'unicamp' ),
				'popular' => esc_html__( 'Popular Events', 'unicamp' ),
			);
			$terms             = get_terms( [
				'taxonomy'   => 'tp_event_category',
				'parent'     => 0,
				'hide_empty' => false,
			] );

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$filter_by_options[ $term->term_id ] = esc_html__( 'Category: ', 'unicamp' ) . $term->name;
				}
			}

			$this->settings['filter_by']['options'] = $filter_by_options;
		}

		public function widget( $args, $instance ) {
			$filter_by       = $this->get_value( $instance, 'filter_by' );
			$num             = $this->get_value( $instance, 'num' );
			$show_thumbnail  = $this->get_value( $instance, 'show_thumbnail' );
			$show_price      = $this->get_value( $instance, 'show_price' );
			$show_categories = $this->get_value( $instance, 'show_categories' );
			$show_date       = $this->get_value( $instance, 'show_date' );
			$show_time       = $this->get_value( $instance, 'show_time' );

			if ( 'related' === $filter_by && ! Unicamp_Event::instance()->is_single() ) {
				return;
			}

			$query_args = [
				'post_type'      => Unicamp_Event::instance()->get_event_type(),
				'posts_per_page' => $num,
				'no_found_rows'  => true,
				'post_status'    => 'publish',
			];

			if ( 'recent' === $filter_by ) {
				$query_args = wp_parse_args( $query_args, [
					'orderby' => 'date',
					'order'   => 'DESC',
				] );
			} elseif ( 'popular' === $filter_by ) {
				$query_args = wp_parse_args( $query_args, [
					'meta_key' => 'views',
					'orderby'  => 'meta_value_num',
					'order'    => 'DESC',
				] );
			} elseif ( 'related' === $filter_by ) {
				$current_event = get_the_ID();

				$related_by = [
					Unicamp_Event::instance()->get_tax_category(),
					Unicamp_Event::instance()->get_tax_tag(),
				];

				$query_args['tax_query'] = [];

				foreach ( $related_by as $tax ) {
					$terms = get_the_terms( $current_event, $tax );
					if ( $terms && ! is_wp_error( $terms ) ) {
						$term_ids = array();
						foreach ( $terms as $term ) {
							$term_ids[] = $term->term_id;
						}
						$query_args['tax_query'][] = array(
							'terms'    => $term_ids,
							'taxonomy' => $tax,
						);
					}
				}
				if ( count( $query_args['tax_query'] ) > 1 ) {
					$query_args['tax_query']['relation'] = 'OR';
				}

				$query_args = wp_parse_args( $query_args, [
					'orderby'      => 'date',
					'order'        => 'DESC',
					'post__not_in' => [ $current_event ],
				] );
			} else {
				$query_args = wp_parse_args( $query_args, [
					'tax_query' => array(
						array(
							'taxonomy' => Unicamp_Event::instance()->get_tax_category(),
							'field'    => 'id',
							'terms'    => $filter_by,
						),
					),
				] );
			}

			$query = new WP_Query( $query_args );

			if ( $query->have_posts() ) :
				$this->widget_start( $args, $instance );
				?>
				<div class="unicamp-animation-zoom-in">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<?php
						$event = new WPEMS_Event( get_the_ID() );

						$classes = array( 'event-item unicamp-box' );
						?>
						<div <?php post_class( implode( ' ', $classes ) ); ?> >
							<?php if ( $show_thumbnail ) : ?>
								<div class="event-thumbnail unicamp-image">
									<a href="<?php the_permalink(); ?>">
										<?php if ( has_post_thumbnail() ) { ?>
											<?php Unicamp_Image::the_post_thumbnail( array( 'size' => '100x70' ) ); ?>
											<?php
										} else {
											Unicamp_Templates::image_placeholder( 100, 70 );
										}
										?>

										<?php if ( $show_date ) : ?>
											<?php
											$date_start = get_post_meta( get_the_ID(), 'tp_event_date_start', true );
											$date_start = ! empty( $date_start ) ? strtotime( $date_start ) : time();

											$date_string = wp_date( 'M d', $date_start );
											?>
											<div class="event-date">
												<span><?php echo esc_html( $date_string ); ?></span>
											</div>
										<?php endif; ?>
									</a>
								</div>
							<?php endif; ?>
							<div class="event-info">
								<?php if ( $show_categories ) : ?>
									<?php Unicamp_Event::instance()->event_loop_category( [ 'number' => 2 ] ); ?>
								<?php endif; ?>

								<?php if ( $show_price ): ?>
									<div class="event-price price">
										<?php printf( '%s', $event->is_free() ? esc_html__( 'Free', 'unicamp' ) : wpems_format_price( $event->get_price() ) ); ?>
									</div>
								<?php endif; ?>

								<h5 class="event-title post-title-2-rows">
									<a href="<?php the_permalink(); ?>" class="link-in-title"><?php the_title(); ?></a>
								</h5>

								<?php if ( $show_time ) : ?>
									<?php
									$time_start = wpems_event_start( get_option( 'time_format' ) );
									$time_end   = wpems_event_end( get_option( 'time_format' ) );
									?>
									<div
										class="event-time"><?php echo esc_html( $time_start . ' - ' . $time_end ); ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
				<?php
				$this->widget_end( $args );
			endif;
		}
	}
}
