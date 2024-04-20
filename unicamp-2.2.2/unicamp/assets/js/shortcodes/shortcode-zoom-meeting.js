jQuery( document ).ready( function( $ ) {
	'use strict';

	initMeetingCountdown();

	function initMeetingCountdown() {
		$( '.tm-zoom-meeting' ).each( function() {
			var clock = $( this ).find( '.zoom-countdown' );

			var clockSettings = clock.data(),
			    valueDate     = clockSettings.date,
			    daysText      = clockSettings.daysText,
			    hoursText     = clockSettings.hoursText,
			    minutesText   = clockSettings.minutesText,
			    secondsText   = clockSettings.secondsText;

			clock.countdown( valueDate, function( event ) {
				$( this )
					.html( event.strftime( '' + '<div class="countdown-content">' + '<div class="day"><span class="number">%d</span><span class="text">' + daysText + '</span></div>' + '<div class="hour"><span class="number">%H</span><span class="text">' + hoursText + '</span></div>' + '<div class="minute"><span class="number">%M</span><span class="text">' + minutesText + '</span></div>' + '<div class="second"><span class="number">%S</span><span class="text">' + secondsText + '</span></div>' + '</div>' ) );
			} );
		} );
	}
} );
