(
	function( $ ) {
		'use strict';

		var $body = $( 'body' );

		var FullPageHandler = function( $scope, $ ) {
			var $element = $scope.find( '.tm-full-page' );
			var fullpageNumbers = $( '#full-page-numbers' );
			var fullpageShare = $( '#full-page-sharing' );
			var $header = $( '#page-header' );
			var $footer = $( '#page-footer-wrapper' );

			$footer.addClass( 'section' ).addClass( 'fp-auto-height' ).attr( 'data-anchor', 'section-footer' );
			$footer.appendTo( $element );

			if ( typeof $.fn.fullpage.destroy === 'function' ) {
				$.fn.fullpage.destroy();
			}

			var fp_settings = $element.data( 'fp-settings' );

			$element.fullpage( {
				navigation: fp_settings.dots,
				navigationPosition: fp_settings.dots_align,
				lazyLoading: true,
				scrollBar: false,
				css3: true,
				scrollingSpeed: 900,
				scrollOverflow: true,
				scrollOverflowReset: true,
				scrollOverflowOptions: {
					click: true
				},
				verticalCentered: true,

				afterLoad: function( anchor, slide ) {
					var $currentSection = $element.children( '.active' );

					var sectionSkin = $currentSection.data( 'skin' ) ? $currentSection.data( 'skin' ) : '';

					if ( '' !== sectionSkin ) {
						$body.attr( 'data-fp-section-skin', sectionSkin );
						$header.removeClass( 'header-light header-dark' ).addClass( 'header-' + sectionSkin );
					}

					// Slide Status.
					if ( ! $currentSection.hasClass( 'page-footer-wrapper' ) ) {
						var number = slide.index + 1;
						fullpageNumbers.find( '.current' ).text( number );

						var sectionTitle = $currentSection.data( 'tooltip' ) ? $currentSection.data( 'tooltip' ) : '';
						fullpageNumbers.find( '.title' ).text( sectionTitle )
					}

					// Slide Sharing.
					if ( fp_settings.sharing && fp_settings.sharing === 'yes' ) {
						if ( 'dark' === sectionSkin ) {
							fullpageShare.find( 'a' ).removeClass( 'hint--white' ).addClass( 'hint--primary' );
						} else if ( 'light' === sectionSkin ) {
							fullpageShare.find( 'a' ).removeClass( 'hint--primary' ).addClass( 'hint--white' );
						}
					}
				}
			} );
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-full-page.default', FullPageHandler );
		} );

	}
)( jQuery );
