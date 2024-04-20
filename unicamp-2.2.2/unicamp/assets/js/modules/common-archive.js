/**
 * Functions for archive pages.
 */
(
	function( $ ) {
		'use strict';

		$( document ).ready( function() {
			initArchiveTopFilters();
			initArchiveLayoutSwitcher();
		} );

		function initArchiveTopFilters() {
			var $filterBtn = $( '#btn-toggle-archive-top-filters' );
			var $filterWidgets = $( '#archive-top-filter-widgets' );

			if ( $filterBtn.length <= 0 || $filterWidgets.length <= 0 ) {
				return;
			}

			$filterWidgets.removeClass( 'filters-opened' ).stop().hide();
			$filterWidgets.find( '.widget' ).children().not( '.widget-title' ).wrap( '<div class="widget-content" />' );

			$filterWidgets.find( '.filter-swatch' ).removeClass( 'hint--bounce hint--top' );

			$filterBtn.on( 'click', function( e ) {
				e.preventDefault();

				if ( $( this ).hasClass( 'active' ) ) {
					$( this ).removeClass( 'active' );
					$filterWidgets.removeClass( 'filters-opened' ).stop().slideUp( 350 );
				} else {
					$( this ).addClass( 'active' );
					$filterWidgets.addClass( 'filters-opened' ).stop().slideDown( 350 );
				}

				setTimeout( function() {
					$filterWidgets.find( '.widget' ).children( '.widget-content' ).perfectScrollbar( { suppressScrollX: true } );
				}, 500 );
			} );
		}

		function initArchiveLayoutSwitcher() {
			var $layoutSwitcher = $( '#archive-layout-switcher' );

			$( 'input[type=radio]', $layoutSwitcher ).on( 'change', function() {
				$( this ).parent( '.switcher-item' ).siblings().removeClass( 'loading' );
				$( this ).parent().addClass( 'loading' );

				$( this ).closest( 'form' ).submit();
			} );

			$layoutSwitcher.on( 'submit', function( e ) {
				e.preventDefault();

				var data = $( this ).serialize();

				$.ajax( {
					url: $unicamp.ajaxurl,
					type: 'POST',
					data: data,
					dataType: 'json',
					success: function( results ) {
						location.reload();
					},
					error: function( MLHttpRequest, textStatus, errorThrown ) {
						console.log( errorThrown );
					},
				} );

				return false;
			} );
		}

	}( jQuery )
);
