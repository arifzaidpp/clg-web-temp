(
	function( $ ) {
		'use strict';

		$.fn.UnicampTabPanel = function() {
			var $el;

			function reLayoutComponentsInTab( currentTab ) {
				var $sliders = currentTab.find( '.swiper-container' );

				if ( $sliders.length > 0 ) {
					$sliders.each( function() {
						if ( $( this )[ 0 ].swiper ) {
							$( this )[ 0 ].swiper.update();
						}
					} );
				}

				var $grids = currentTab.find( '.unicamp-grid-wrapper' );

				if ( $grids.length > 0 ) {
					$grids.UnicampGridLayout( 'calculateMasonrySize' );
				}
			}

			return this.each( function() {
				var $el = $( this );
				var $navTabs = $( this ).children( '.unicamp-nav-tabs' );
				var $contentTabs = $( this ).children( '.unicamp-tab-content' );
				var currentTabIndex = 0;
				var $currentTab = $contentTabs.children().eq( currentTabIndex );

				$el.children( '.unicamp-nav-tabs' ).on( 'click', 'a', function( e ) {
					e.preventDefault();

					var parent = $( this ).parent( 'li' );
					if ( parent.hasClass( 'active' ) ) {
						return;
					}

					parent.siblings().removeClass( 'active' );
					parent.addClass( 'active' );

					currentTabIndex = parent.index();

					$contentTabs.children().removeClass( 'active' );
					$currentTab = $contentTabs.children().eq( currentTabIndex );

					$currentTab.addClass( 'active' );

					$( document ).trigger( 'UnicampTabChange', [ $el, $currentTab ] );
				} );

				$el.on( 'click', '.tab-mobile-heading', function( e ) {
					e.preventDefault();

					var parent = $( this ).parent( '.tab-panel' );
					if ( parent.hasClass( 'active' ) ) {
						return;
					}

					parent.siblings().removeClass( 'active' );
					parent.addClass( 'active' );

					$currentTab = parent;

					currentTabIndex = parent.index();

					$navTabs.children().removeClass( 'active' );
					$navTabs.children().eq( currentTabIndex ).addClass( 'active' );

					$( document ).trigger( 'UnicampTabChange', [ $el, $currentTab ] );
				} );

				$( document ).on( 'UnicampTabChange', function( e, tabPanel, currentTab ) {
					reLayoutComponentsInTab( currentTab );
				} );

				$navTabs.children().eq( currentTabIndex ).addClass( 'active' );
				$currentTab.addClass( 'active' );
				/**
				 * Fix components in tabs broken layout,
				 * because it init before tab panel.
				 */
				reLayoutComponentsInTab( $currentTab );
			} );
		};
	}( jQuery )
);

jQuery( document ).ready( function( $ ) {
	'use strict';

	$( '.unicamp-tabpanel' ).UnicampTabPanel();
} );
