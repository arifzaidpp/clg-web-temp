<?php

namespace Unicamp\Zoom_Meeting;

defined( 'ABSPATH' ) || exit;

class Shortcode extends Utils {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_shortcode( 'tm_zoom_meeting', [ $this, 'shortcode_zoom_meeting' ] );
	}

	public function shortcode_zoom_meeting( $atts ) {
		wp_enqueue_script( 'video-conferencing-with-zoom-api-moment' );
		wp_enqueue_script( 'video-conferencing-with-zoom-api-moment-locales' );
		wp_enqueue_script( 'video-conferencing-with-zoom-api-moment-timezone' );
		wp_enqueue_script( 'video-conferencing-with-zoom-api' );
		wp_enqueue_script( 'unicamp-zoom-meeting-countdown' );

		extract( shortcode_atts( array(
			'meeting_id' => 'javascript:void(0);',
		), $atts ) );

		unset( $GLOBALS['vanity_uri'] );
		unset( $GLOBALS['zoom_meetings'] );

		ob_start();

		if ( empty( $meeting_id ) ) {
			echo '<h4 class="no-meeting-id"><strong style="color:red;">' . esc_html__( 'ERROR: ', 'unicamp' ) . '</strong>' . esc_html__( 'No meeting id set in the shortcode', 'unicamp' ) . '</h4>';

			return false;
		}

		$zoom_states = get_option( 'zoom_api_meeting_options' );
		if ( isset( $zoom_states[ $meeting_id ]['state'] ) && $zoom_states[ $meeting_id ]['state'] === "ended" ) {
			echo '<h3>' . esc_html__( 'This meeting has been ended by host.', 'unicamp' ) . '</h3>';

			return;
		}

		$vanity_uri               = get_option( 'zoom_vanity_url' );
		$meeting                  = $this->fetch_meeting( $meeting_id );
		$GLOBALS['vanity_uri']    = $vanity_uri;
		$GLOBALS['zoom_meetings'] = $meeting;
		if ( ! empty( $meeting ) && ! empty( $meeting->code ) ) {
			?>
			<p class="dpn-error dpn-mtg-not-found"><?php echo esc_html( $meeting->message ); ?></p>
			<?php
		} else {
			if ( $meeting ) {
				//Get Template
				vczapi_get_template( 'shortcode/tm-zoom-meeting.php', true, false );
			} else {
				printf( esc_html__( 'Please try again ! Some error occured while trying to fetch meeting with id:  %d', 'unicamp' ), $meeting_id );
			}
		}

		return ob_get_clean();
	}
}

Shortcode::instance()->initialize();
