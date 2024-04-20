(
	function( $ ) {
		'use strict';

		var TestimonialCarousel3DHandler = function( $scope, $ ) {
			var $element = $scope.find( '.carousel-vertical-3d' );

			$element.UnicampCarousel3D();
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-testimonial-carousel-3d.default', TestimonialCarousel3DHandler );
		} );
	}
)( jQuery );
