jQuery( document ).ready( function( $ ) {
	'use strict';

	var $body = $( 'body' );
	var wWidth = window.innerWidth;

	/**
	 * Waiting for headroom init.
	 */
	$( window ).on( 'load', function() {
		initStickyElement();
	} );

	$( document ).on( 'UnicampCourseTabsChange', reInitStickyKit );

	// BuddyPress ajax loaded.
	$body.on( 'bp_ajax_request', function() {
		reInitStickyKit();
	} );

	$( window ).resize( function() {
		wWidth = window.innerWidth;
		initStickyElement();
	} );

	function initStickyElement() {
		var $parent = $( '.tm-sticky-parent' );

		$parent.each( function() {
			var $stickyColumns = $( this ).find( '.tm-sticky-column' );

			if ( wWidth < 992 ) {
				$stickyColumns.trigger( 'sticky_kit:detach' );
			} else {
				var $smallestColumn;
				var smallestHeight = null;

				$stickyColumns.each( function() {
					var thisArea = $( this ).outerHeight();
					if ( smallestHeight === null || thisArea < smallestHeight ) {
						$smallestColumn = $( this );
						smallestHeight = thisArea;
					}
				} );

				var $pageHeader = $( '#page-header' );
				var _offset = $pageHeader.find( '#page-header-inner' ).outerHeight();

				/**
				 * Header sticky smaller than normal header.
				 */
				if ( $pageHeader.hasClass( 'headroom' ) ) {
					$pageHeader.addClass( 'headroom--not-top' );
					_offset = $pageHeader.find( '#page-header-inner' ).outerHeight();
					$pageHeader.removeClass( 'headroom--not-top' );
				}

				if ( isNaN( _offset ) || _offset < 0 ) {
					_offset = 80;
				}

				if ( $body.hasClass( 'admin-bar' ) ) {
					_offset += 32;
				}

				/**
				 * Spacing header with content.
				 */
				_offset += 30;

				$smallestColumn.stick_in_parent( {
					parent: $parent,
					'offset_top': _offset,
				} );
			}
		} );
	}

	/**
	 * Re init sticky kit when single course tab changed.
	 */
	function reInitStickyKit() {
		$( '.tm-sticky-column' ).trigger( 'sticky_kit:detach' );
		initStickyElement();
	}
} );
