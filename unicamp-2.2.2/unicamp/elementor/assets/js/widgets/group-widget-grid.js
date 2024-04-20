(
	function( $ ) {
		'use strict';

		var UnicampGridHandler = function( $scope, $ ) {
			var $element = $scope.find( '.unicamp-grid-wrapper' );

			$element.UnicampGridLayout();
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-image-gallery.default', UnicampGridHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-testimonial-grid.default', UnicampGridHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-product-categories.default', UnicampGridHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-course-category-cards.default', UnicampGridHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-course-languages.default', UnicampGridHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-faq-cards.default', UnicampGridHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-event.default', UnicampGridHandler );
		} );
	}
)( jQuery );
