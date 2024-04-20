

(function( $ ) {
	'use strict';

	var updateThemesBannerNotice;

	$( document ).ready( function() {
		initSliders();
		initThemesBannerSliders();
		themesBannerNotice();
	});

	updateThemesBannerNotice = function( visible ) {
		$.post( ajaxurl, {
			action: 'tm_themes_banner_notice',
			visible: visible,
			nonce: $( '#themes_banner_nonce' ).val()
		});
	};

	function initSliders() {
		$( '.tm-components-recommended-carousel' ).slick( {
			dots: false,
			arrows: true
		} );
	}

	function initThemesBannerSliders() {
		$( '.tm-featured-themes-banner__wrapper' ).slick( {
			dots: false,
			arrows: true
		} );
	};

	function themesBannerNotice() {
		var $banner = $( '.tm-featured-themes-banner' ),
			$closeButton = $( '.tm-featured-themes-banner__close', $banner );

		$closeButton.on( 'click', function(e) {
			e.preventDefault();
			$banner.addClass( 'hidden' );
			updateThemesBannerNotice(0);
		} );	
	}

})( jQuery );