jQuery( document ).ready( function( $ ) {
	'use strict';

	paymentMethodChanged();

	function paymentMethodChanged() {
		var $orderReview = $( '#order_review' );

		$orderReview.on( 'click', 'input[name="payment_method"]', function() {
			var selectedClass = 'payment-selected';
			var parent = $( this ).parents( '.wc_payment_method' ).first();

			if ( ! parent.hasClass( selectedClass ) ) {
				parent.siblings().removeClass( selectedClass );
				parent.addClass( selectedClass );
			}
		} );
	}
} );
