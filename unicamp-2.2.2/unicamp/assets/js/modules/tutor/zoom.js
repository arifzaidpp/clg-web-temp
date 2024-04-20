(
	function( $ ) {
		'use strict';
		$( document ).ready( function() {
			$( '.tutor-zoom-meeting-countdown' ).each( function() {
				var date_time = $( this ).data( 'timer' );
				var timezone = $( this ).data( 'timezone' );
				var new_date = moment.tz( date_time, timezone );

				var daysText    = $unicamp.countdownDaysText,
				    hoursText   = $unicamp.countdownHoursText,
				    minutesText = $unicamp.countdownMinutesText,
				    secondsText = $unicamp.countdownSecondsText;

				$( this ).countdown( new_date.toDate(), function( event ) {
					var timeTemplate = '<div class="countdown-section hour"><span class="countdown-amount">%H</span><span class="countdown-period">' + hoursText + '</span></div>' +
					                   '<div class="countdown-section minute"><span class="countdown-amount">%M</span><span class="countdown-period">' + minutesText + '</span></div>' +
					                   '<div class="countdown-section second"><span class="countdown-amount">%S</span><span class="countdown-period">' + secondsText + '</span></div>';

					timeTemplate = '<div class="countdown-section day"><span class="countdown-amount">%D</span><span class="countdown-period">' + daysText + '</span></div>' + timeTemplate;

					timeTemplate = '<div class="countdown-row">' + timeTemplate + '</div>'; // wrapper.

					$( this ).html( event.strftime( timeTemplate ) );
				} );
			} );

			$( '.tutor-zoom-lesson-countdown' ).each( function() {
				var date_time = $( this ).data( 'timer' );
				var timezone = $( this ).data( 'timezone' );
				var new_date = moment.tz( date_time, timezone );

				var daysText    = $unicamp.countdownDaysText,
				    hoursText   = $unicamp.countdownHoursText,
				    minutesText = $unicamp.countdownMinutesText,
				    secondsText = $unicamp.countdownSecondsText;

				$( this ).countdown( new_date.toDate(), function( event ) {
					var timeTemplate = '';

					if ( event.offset.totalDays > 0 ) {
						timeTemplate += '<div class="countdown-item day"><span class="countdown-line-amount">%D</span><span class="countdown-line-period">' + daysText + '</span></div>';
					}

					if ( event.offset.totalHours > 0 ) {
						timeTemplate += '<div class="countdown-item hour"><span class="countdown-line-amount">%H</span><span class="countdown-line-period">' + hoursText + '</span></div>';
					}

					timeTemplate += '<div class="countdown-item minute"><span class="countdown-line-amount">%M</span><span class="countdown-line-period">' + minutesText + '</span></div>' +
					                '<div class="countdown-item second"><span class="countdown-line-amount">%S</span><span class="countdown-line-period">' + secondsText + '</span></div>';

					$( this ).html( event.strftime( timeTemplate ) );
				} );
			} );

			$( '.tutor-zoom-meeting-detail' ).on( 'click', 'i.tutor-icon-copy', function( e ) {
				e.stopPropagation();
				var $icon = $( this );
				var $temp = $( "<input>" );
				$( "body" ).append( $temp );
				$temp.val( $icon.parent().find( 'span' ).text() ).select();
				document.execCommand( "copy" );
				$temp.remove();

				$icon.parent().append( '<span class="tutor-copied-msg tutor-icon-mark"> Copied</span>' ).fadeIn( 1000 );
				setTimeout( function() {
					$icon.parent().find( '.tutor-copied-msg' ).fadeOut( 1000 );
				}, 1000 );
			} );
		} );
	}
)( jQuery );
