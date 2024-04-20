(
	function( $ ) {
		'use strict';

		var UnicampGridDataHandler = function( $scope, $ ) {
			var $element = $scope.find( '.unicamp-grid-wrapper' );
			$element.find( '.unicamp-widget-nice-select' ).UnicampNiceSelect();
			$element.UnicampGridLayout().UnicampGridQuery();
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-blog.default', UnicampGridDataHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-product.default', UnicampGridDataHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-course.default', UnicampGridDataHandler );
		} );
	}
)( jQuery );
