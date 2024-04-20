(
	function( $ ) {
		'use strict';

		var UnicampStudentStoryListHandler = function( $scope, $ ) {
			var list = $scope.find( '.student-story-list' );
			var imager = $scope.find( '.student-story-image-list' );

			list.on( 'mouseenter', '.post-item', function() {
				handlerHoverType( $( this ) );
			} );

			var firstPost = list.children( '.post-item' ).first();

			handlerHoverType( firstPost );

			function handlerHoverType( post ) {
				if ( post.hasClass( 'active' ) ) {
					return;
				}

				var id = post.data( 'id' );
				var currentImage = imager.find( '.post-' + id );

				post.siblings( '.post-item' ).removeClass( 'active' );
				post.addClass( 'active' );

				currentImage.siblings().removeClass( 'active' );
				currentImage.addClass( 'active' );
			}
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-student-story-list.default', UnicampStudentStoryListHandler );
		} );
	}
)( jQuery );
