(
	function( $ ) {
		'use strict';

		var UnicampCounterHandler = function( $scope, $ ) {
			var $element = $scope.find( '.tm-counter' );

			elementorFrontend.waypoint( $element, function() {
				var settings = $element.data( 'counter' );

				var $number = $element.find( '.counter-number' );

				$number.countTo( {
					from: settings.from,
					to: settings.to,
					speed: settings.duration,
					refreshInterval: 50,
					formatter: function( value, options ) {
						return format( value.toFixed( options.decimals ), settings.separator );
					},
				} );
			} );

			function format( x, sep, grp ) {
				var sx            = (
					'' + x
				).split( '.' ), s = '', i, j;
				sep || (
					sep = ''
				); // default separator.
				grp || grp === 0 || (
					grp = 3
				); // default grouping
				i = sx[ 0 ].length;
				while ( i > grp ) {
					j = i - grp;
					s = sep + sx[ 0 ].slice( j, i ) + s;
					i = j;
				}
				s = sx[ 0 ].slice( 0, i ) + s;
				sx[ 0 ] = s;
				return sx.join( '.' );
			}
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-counter.default', UnicampCounterHandler );
		} );
	}
)( jQuery );
