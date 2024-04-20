(
	function( $ ) {
		'use strict';

		var UnicampTypedHeadlineHandler = function( $scope, $ ) {
			var $element = $scope.find( '.tm-typed-headline' );
			var $animateText = $element.find( '.animate-text' );

			if ( $animateText.length > 0 ) {
				var strings = $animateText.data( 'typed' );

				// If container has text then plugin skip first item. so we need add extra item to make it work normally.
				if ( '' !== $animateText.text() ) {
					strings.unshift( 'placeholder' );
				}

				var typed = new Typed( $animateText[ 0 ], {
					strings: strings,
					loop: true,
					smartBackspace: false, // Fix animation on loop end.
					//shuffle: true,
					//fadeOut: true,
					typeSpeed: 50,
					backDelay: 2000
				} );
			}
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-typed-headline.default', UnicampTypedHeadlineHandler );
		} );
	}
)( jQuery );
