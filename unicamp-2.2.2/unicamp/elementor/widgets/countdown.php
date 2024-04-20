<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Utils;

defined( 'ABSPATH' ) || exit;

class Widget_Countdown extends Base {

	public function get_name() {
		return 'tm-countdown';
	}

	public function get_title() {
		return esc_html__( 'Countdown', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-countdown';
	}

	public function get_keywords() {
		return [ 'countdown' ];
	}

	public function get_script_depends() {
		return [ 'unicamp-widget-countdown' ];
	}

	protected function register_controls() {
		$this->add_countdown_section();
	}

	private function add_countdown_section() {
		$this->start_controls_section( 'countdown_section', [
			'label' => esc_html__( 'Countdown', 'unicamp' ),
		] );

		$this->add_control( 'style', [
			'label'       => esc_html__( 'Style', 'unicamp' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => array(
				'01' => esc_html__( 'Style 01', 'unicamp' ),
			),
			'default'     => '01',
			'render_type' => 'template',
		] );

		$this->add_control( 'countdown_type', [
			'label'   => esc_html__( 'Type', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'due_date' => esc_html__( 'Due Date', 'unicamp' ),
				'daily'    => esc_html__( 'Daily', 'unicamp' ),
			],
			'default' => 'due_date',
		] );

		$this->add_control( 'due_date', [
			'label'       => esc_html__( 'Due Date', 'unicamp' ),
			'type'        => Controls_Manager::DATE_TIME,
			'default'     => gmdate( 'Y-m-d H:i', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
			/* translators: %s: Time zone. */
			'description' => sprintf( __( 'Date set according to your timezone: %s.', 'unicamp' ), Utils::get_timezone_string() ),
			'condition'   => [
				'countdown_type' => 'due_date',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$days_text    = esc_html__( 'Days', 'unicamp' );
		$hours_text   = esc_html__( 'Hours', 'unicamp' );
		$minutes_text = esc_html__( 'Minutes', 'unicamp' );
		$seconds_text = esc_html__( 'Seconds', 'unicamp' );

		if ( defined( 'UNICAMP_DEMO_SITE' ) && true === UNICAMP_DEMO_SITE ) {
			$datetime = $this->get_sample_countdown_date();
		} else {
			switch ( $settings['countdown_type'] ) {
				case 'due_date':
					$due_date = $settings['due_date'];
					// Handle timezone ( we need to set GMT time )
					$gmt      = get_gmt_from_date( $due_date . ':00' );
					$datetime = date( 'm/d/Y H:i:s', strtotime( $gmt ) );
					break;
				case 'daily':
					$now      = strtotime( current_time( 'm/d/Y H:i:s' ) );
					$endOfDay = strtotime( "tomorrow", $now ) - 1;

					$datetime = date( 'm/d/Y H:i:s', $endOfDay );
					break;
			}
		}

		$this->add_render_attribute( 'countdown', [
			'class'             => 'countdown',
			'data-date'         => $datetime,
			'data-days-text'    => $days_text,
			'data-hours-text'   => $hours_text,
			'data-minutes-text' => $minutes_text,
			'data-seconds-text' => $seconds_text,
		] );

		$this->add_render_attribute( 'countdown-wrap', [
			'class' => 'unicamp-countdown unicamp-box',
		] );
		?>
		<div <?php $this->print_render_attribute_string( 'countdown-wrap' ); ?>>
			<div class="countdown-wrap">
				<div <?php $this->print_render_attribute_string( 'countdown' ); ?>></div>
			</div>
		</div>
		<?php
	}

	private function get_sample_countdown_date() {
		$date = date( 'm/d/Y H:i:s', strtotime( '+1 month', strtotime( date( 'm/d/Y H:i:s' ) ) ) );

		return $date;
	}
}
