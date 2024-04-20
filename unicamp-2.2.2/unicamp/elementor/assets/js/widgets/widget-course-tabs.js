(
	function( $ ) {
		'use strict';

		var UnicampCourseTabs = function( $scope, $ ) {
			var $element = $scope.find( '.unicamp-tabpanel' );

			elementorFrontend.waypoint( $element, function() {
				var navTabs = $element.children( '.unicamp-tab-content' );
				var activeTab = navTabs.children( '.active' );

				if ( ! activeTab.hasClass( 'ajax-loaded' ) ) {
					loadCourseData( activeTab );

					activeTab.addClass( 'ajax-loaded' );
				}
			} );

			$( document ).on( 'UnicampTabChange', function( e, tabPanel, currentTab ) {
				if ( ! currentTab.hasClass( 'ajax-loaded' ) ) {
					loadCourseData( currentTab );

					currentTab.addClass( 'ajax-loaded' );
				} else {
					// Fixed layout broken after window resize & tab change.
					var $component = currentTab.find( '.tm-tab-course-element' );
					var layout = currentTab.data( 'layout' );

					if ( 'grid' === layout ) {
						$component.UnicampGridLayout( 'calculateMasonrySize' );
					} else {
						var swiper = $component.children( '.swiper-inner' ).children( '.swiper-container' )[ 0 ].swiper;
						swiper.update();
					}
				}
			} );

			function loadCourseData( currentTab ) {
				var $component = currentTab.find( '.tm-tab-course-element' );
				var layout = currentTab.data( 'layout' );
				if ( 'grid' === layout ) {
					$component.UnicampGridLayout();
				} else {
					$component.UnicampSwiper();
				}

				var query = currentTab.data( 'query' );
				query.action = 'get_course_tabs';

				$.ajax( {
					url: $unicamp.ajaxurl,
					type: 'POST',
					data: query,
					dataType: 'json',
					success: function( response ) {
						if ( ! response.success ) {
							$component.remove();
							currentTab.find( '.unicamp-grid-response-messages' ).html( response.template );
						} else {
							if ( 'grid' === layout ) {
								var $grid = $component.children( '.unicamp-grid' );
								$grid.children( '.grid-item' ).remove();
								$component.UnicampGridLayout( 'update', $( response.template ) );
							} else {
								var swiper = $component.children( '.swiper-inner' ).children( '.swiper-container' )[ 0 ].swiper;
								swiper.removeAllSlides();
								swiper.appendSlide( response.template );
								swiper.update();
							}
						}
					}
				} );
			}
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-course-tabs.default', UnicampCourseTabs );
		} );
	}
)( jQuery );
