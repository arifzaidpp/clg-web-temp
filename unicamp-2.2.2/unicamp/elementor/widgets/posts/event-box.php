<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || exit;

class Widget_Event_Box extends Posts_Base {

	public function get_name() {
		return 'tm-event-box';
	}

	public function get_title() {
		return esc_html__( 'Event List Box', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-post-list';
	}

	public function get_keywords() {
		return [ 'event', 'events', 'box' ];
	}

	protected function get_post_type() {
		return 'tp_event';
	}

	protected function get_post_category() {
		return 'tp_event_category';
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_box_styling_section();

		$this->add_style_section();

		parent::register_controls();

		$this->update_controls();
	}

	public function update_controls() {
		$this->update_control( 'query_number', [
			'default' => 3,
		] );
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Content', 'unicamp' ),
		] );

		$this->add_control( 'box_title', [
			'label'   => esc_html__( 'Box Title', 'unicamp' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'Upcoming Events', 'unicamp' ),
		] );

		$this->add_control( 'show_archive_link', [
			'label'     => esc_html__( 'Show Archive Link', 'unicamp' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'label_on'  => esc_html__( 'Show', 'unicamp' ),
			'label_off' => esc_html__( 'Hide', 'unicamp' ),
		] );

		$this->add_control( 'show_location', [
			'label'     => esc_html__( 'Show Location', 'unicamp' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => esc_html__( 'Show', 'unicamp' ),
			'label_off' => esc_html__( 'Hide', 'unicamp' ),
		] );

		$this->end_controls_section();
	}

	private function add_box_styling_section() {
		$this->start_controls_section( 'box_style_section', [
			'label' => esc_html__( 'Box', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_shadow',
			'selector' => '{{WRAPPER}} .unicamp-box',
		] );

		$this->end_controls_section();
	}

	private function add_style_section() {
		$this->start_controls_section( 'style_section', [
			'label' => esc_html__( 'Style', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'title_style_heading', [
			'label'     => esc_html__( 'Title', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'title_margin', [
			'label'      => esc_html__( 'Margin', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .post-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => esc_html__( 'Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .post-title',
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .post-title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'title_hover_color', [
			'label'     => esc_html__( 'Hover Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .post-title:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'date_style_heading', [
			'label'     => esc_html__( 'Date', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'date_typography',
			'label'    => esc_html__( 'Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .event-date',
		] );

		$this->add_control( 'date_color', [
			'label'     => esc_html__( 'Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .event-date-time' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'date_day_typography',
			'label'    => esc_html__( 'Day Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .event-date .event-date--day',
		] );

		$this->add_control( 'location_style_heading', [
			'label'     => esc_html__( 'Location', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'location_typography',
			'label'    => esc_html__( 'Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .event-location',
		] );

		$this->add_control( 'location_color', [
			'label'     => esc_html__( 'Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .event-location' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->query_posts();
		/**
		 * @var $query \WP_Query
		 */
		$query = $this->get_query();

		$this->add_render_attribute( 'wrapper', 'class', [
			'unicamp-event-box unicamp-box',
		] );

		$archive_link = get_post_type_archive_link( $this->get_post_type() );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<div class="box-header">
				<?php if ( ! empty( $settings['box_title'] ) ) : ?>
					<h4 class="box-title"><?php echo esc_html( $settings['box_title'] ); ?></h4>
				<?php endif; ?>

				<?php if ( ! empty( $settings['show_archive_link'] ) && 'yes' === $settings['show_archive_link'] && ! empty( $archive_link ) ) : ?>
					<?php
					\Unicamp_Templates::render_button( [
						'link'       => [
							'url' => $archive_link,
						],
						'text'       => esc_html__( 'View all', 'unicamp' ),
						'icon'       => 'fas fa-long-arrow-right',
						'icon_align' => 'right',
						'style'      => 'text',
					] );
					?>
				<?php endif; ?>
			</div>
			<div class="box-body">
				<?php if ( $query->have_posts() ) : ?>
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<div class="post-item">
							<div class="event-caption">
								<div class="left-box">
									<?php
									$date_start = get_post_meta( get_the_ID(), 'tp_event_date_start', true );
									if ( empty( $date_start ) ) {
										$date_start = time();
									}
									$date_start = strtotime( $date_start );
									$day        = wp_date( 'd', $date_start );
									$month      = wp_date( 'M', $date_start );
									?>
									<div class="event-date">
										<div class="event-date--day"><?php echo esc_html( $day ); ?></div>
										<div class="event-date--month"><?php echo esc_html( $month ); ?></div>
									</div>
								</div>

								<div class="right-box">
									<h3 class="post-title post-title-2-rows title-has-link">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h3>
									<div class="event-meta">
										<?php
										$time_start = wpems_event_start( get_option( 'time_format' ) );
										$time_end   = wpems_event_end( get_option( 'time_format' ) );
										?>
										<div
											class="event-time"><?php echo esc_html( $time_start . ' - ' . $time_end ); ?></div>

										<?php if ( ! empty( $settings['show_location'] ) && 'yes' === $settings['show_location'] ): ?>
											<?php $location = get_post_meta( get_the_ID(), \Unicamp_Event::POST_META_SHORT_LOCATION, true ); ?>
											<?php if ( $location ): ?>
												<div class="event-location">
													<span class="far fa-map-marker-alt"></span>
													<?php echo esc_html( $location ); ?>
												</div>
											<?php endif; ?>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php endif; ?>
			</div>
			<div class="box-footer">
				<?php if ( ! empty( $settings['show_archive_link'] ) && 'yes' === $settings['show_archive_link'] && ! empty( $archive_link ) ) : ?>
					<a class="btn-view-more" href="<?php echo esc_url( $archive_link ); ?>">
						<span class="fal fa-angle-double-down"></span>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
