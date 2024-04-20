(
	function( $ ) {
		'use strict';

		var UnicampTabsHandler = function( $scope, $ ) {
			var $tabs = $scope.find( '.unicamp-tabs' );

			$tabs.UnicampTabPanel();
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-tabs.default', UnicampTabsHandler );
		} );
	}
)( jQuery );
