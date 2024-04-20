(
	function( $ ) {
		'use strict';

		var CountdownHandler = function( $scope, $ ) {
			console.log( 'run' );
			var $countdown = $scope.find( '.countdown' );
			var countSettings = $countdown.data();
			var daysText = countSettings.daysText;
			var hoursText = countSettings.hoursText;
			var minutesText = countSettings.minutesText;
			var secondsText = countSettings.secondsText;

			$countdown.countdown( countSettings.date, function( event ) {
				var templateStr = '<div class="countdown-clock">'
				                  + '<div class="days"><span class="number">%D</span><span class="text">' + daysText + '</span></div>'
				                  + '<span class="clock-divider days"></span>'
				                  + '<div class="hours"><span class="number">%H</span><span class="text">' + hoursText + '</span></div>'
				                  + '<span class="clock-divider hours"></span>'
				                  + '<div class="minutes"><span class="number">%M</span><span class="text">' + minutesText + '</span></div>'
				                  + '<span class="clock-divider minutes"></span>'
				                  + '<div class="seconds"><span class="number">%S</span><span class="text">' + secondsText + '</span></div>'
				                  + '</div>';
				$( this ).html( event.strftime( templateStr ) );
			} );
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-countdown.default', CountdownHandler );
		} );
	}
)( jQuery );
