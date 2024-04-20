jQuery( document ).ready( function( $ ) {
	'use strict';

	var $contentProtectedAlert = $( '#unicamp-content-protected-box' );
	var delayTime = 3000;

	/**
	 * Prevent right click.
	 */
	$( document ).on( 'contextmenu', function() {
		$contentProtectedAlert.show().delay( delayTime ).fadeOut();
		return false;
	} );

	$( window ).on( 'keydown', function( event ) {
		/**
		 * Prevent open chrome dev tools on Win OS.
		 */
		// Prevent F12.
		if ( event.keyCode == 123 ) {
			$contentProtectedAlert.show().delay( delayTime ).fadeOut();
			return false;
		}

		/**
		 * CTRL + SHIFT + I
		 * CTRL + SHIFT + J
		 * CTRL + SHIFT + C
		 */
		if ( event.ctrlKey && event.shiftKey && (
			event.keyCode == 67 ||
			event.keyCode == 73 ||
			event.keyCode == 74
		) ) {
			$contentProtectedAlert.show().delay( delayTime ).fadeOut();
			return false;
		}

		/**
		 * Prevent open chrome dev tools on Mac OS.
		 */

		/**
		 * COMMAND + OPTION + I
		 * COMMAND + OPTION + J
		 * COMMAND + OPTION + C
		 */
		if ( event.metaKey && event.altKey && (
			event.keyCode == 67 ||
			event.keyCode == 73 ||
			event.keyCode == 74
		) ) {
			$contentProtectedAlert.show().delay( delayTime ).fadeOut();
			return false;
		}

		// COMMAND + SHIFT + C
		if ( event.metaKey && event.shiftKey && event.keyCode == 67 ) {
			$contentProtectedAlert.show().delay( delayTime ).fadeOut();
			return false;
		}
	} );
} );
