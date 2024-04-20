jQuery( document ).ready( function( $ ) {
	'use strict';

	bpTableHandler();
	headerNotifications();

	function bpTableHandler() {
		$( '.standard-form' ).find( 'table' ).wrap( '<div class="bp-tables-wrapper"></div>' );
	}

	function headerNotifications() {
		var $notification = $( '#header-notifications' );

		if ( $notification.length > 0 ) {
			$( document ).on( 'click', function( evt ) {
				if ( evt.target.id === 'header-notification-list' ) {
					return;
				}

				if ( $( evt.target ).closest( '#header-notification-list' ).length ) {
					return;
				}

				$notification.removeClass( 'open' );
			} );

			$notification.on( 'click', '.header-notifications-open', function( e ) {
				e.preventDefault();
				e.stopPropagation();

				$notification.toggleClass( 'open' );

				if ( ! $notification.hasClass( 'loaded' ) ) {
					var placeholderItemTemplate = '<li class="notification-placeholder"><div class="notification-avatar"></div><div class="notification-content"></div></li>';
					var placeholderTemplate = '';

					for ( var i = 5; i > 0; i -- ) {
						placeholderTemplate += placeholderItemTemplate;
					}

					$notification.find( '.notification-list' ).html( placeholderTemplate );

					$.get( $unicamp.ajaxurl,
						{
							action: 'unicamp_get_header_notifications'
						},
						function( response, status, e ) {
							if ( response.success && typeof response.data !== 'undefined' && typeof response.data.contents !== 'undefined' ) {
								$notification.find( '.notification-list' ).html( response.data.contents );
								$notification.addClass( 'loaded' );
							}
						}
					);
				}
			} );
		}
	}
} );
