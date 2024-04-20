<?php
defined( 'ABSPATH' ) || exit;

/**
 * Date & time helper functions.
 */
if ( ! class_exists( 'Unicamp_Datetime' ) ) {
	class Unicamp_Datetime {
		public static function convertToHoursMinutes( $time, $args = [] ) {
			$defaults = [
				'format'              => '%dh:%02dm',
				'only_minutes_format' => '%02dm',
			];

			$args = wp_parse_args( $args, $defaults );

			if ( $time < 1 ) {
				return;
			}

			$hours   = floor( $time / 60 );
			$minutes = ( $time % 60 );

			if ( $hours > 0 ) {
				$result = sprintf( $args['format'], $hours, $minutes );
			} else {
				$result = sprintf( $args['only_minutes_format'], $minutes );
			}

			return $result;
		}

		/**
		 * Convert datetime with preset format used for Countdown.
		 *
		 * @param string $datetime Datetime string to convert
		 * @param string $tz       Timezone string to convert
		 *
		 * @return string
		 */
		public static function convertCountdownDate( $datetime, $tz = '' ) {
			$timezone = ! empty( $tz ) ? $tz : "America/Los_Angeles";
			$tz       = new DateTimeZone( $timezone );
			$date     = new DateTime( $datetime );
			$date->setTimezone( $tz );

			$locale = get_locale();

			if ( ! empty( $locale ) ) {
				setlocale( LC_TIME, $locale );
			}

			return $date->format( 'm/d/Y H:i:s' );
		}
	}
}
