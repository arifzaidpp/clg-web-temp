(
	function( $ ) {
		'use strict';

		var UnicampImageHotspotHandler = function( $scope, $ ) {
			var $element = $scope.find( '.ihotspot_hastooltop' );

			$element.each( function() {
				$( this ).data( 'powertip', function() {
					var htmlThis = $( this ).parents( '.ihotspot_tooltop_html' ).attr( 'data-html' );
					return htmlThis;
				} );
				var thisPlace = $( this ).parents( '.ihotspot_tooltop_html' ).data( 'placement' );
				$( this ).powerTip( {
					placement: thisPlace,
					popupClass: 'unicamp-powertip-image-hotspot',
					smartPlacement: true,
					mouseOnToPopup: true,
				} ).on( {
					powerTipClose: function() {
						$( '#powerTip' ).html( '' );
					}
				} );

				$( 'body' ).on( 'click', '.close_ihp', function() {
					$.powerTip.hide();
				} );
			} )
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-image-hotspot.default', UnicampImageHotspotHandler );
		} );
	}
)( jQuery );
