(
	function( $ ) {
		'use strict';

		$( document ).ready( function() {
			Unicamp_Event_Frontend.init();

			var $eventCountdown = $( '#entry-event-countdown' );
			if ( $eventCountdown.length > 0 ) {
				var clock = $eventCountdown;

				var clockSettings = clock.data(),
				    valueDate     = clockSettings.time,
				    daysText      = clockSettings.daysText ? clockSettings.daysText : $unicamp.countdownDaysText,
				    hoursText     = clockSettings.hoursText ? clockSettings.hoursText : $unicamp.countdownHoursText,
				    minutesText   = clockSettings.minutesText ? clockSettings.minutesText : $unicamp.countdownMinutesText,
				    secondsText   = clockSettings.secondsText ? clockSettings.secondsText : $unicamp.countdownSecondsText;

				clock.countdown( valueDate, function( event ) {
					var timeTemplate = '<div class="countdown-section hour"><span class="countdown-amount">%H</span><span class="countdown-period">' + hoursText + '</span></div>' +
					                   '<div class="countdown-section minute"><span class="countdown-amount">%M</span><span class="countdown-period">' + minutesText + '</span></div>' +
					                   '<div class="countdown-section second"><span class="countdown-amount">%S</span><span class="countdown-period">' + secondsText + '</span></div>';

					if ( event.offset.totalDays > 0 ) {
						timeTemplate = '<div class="countdown-section day"><span class="countdown-amount">%D</span><span class="countdown-period">' + daysText + '</span></div>' + timeTemplate;
					}

					timeTemplate = '<div class="countdown-row">' + timeTemplate + '</div>'; // wrapper.

					$( this ).html( event.strftime( timeTemplate ) );
				} );
			}
		} );

		var Unicamp_Event_Frontend = {
			init: function() {
				var $doc = $( document );

				$doc.on( 'click', '.event-load-booking-form', this.load_form_register );

				/**
				 * load register form
				 */
				$doc.on( 'submit', 'form.event_register:not(.active)', this.book_event_form );

				/**
				 * Sanitize form field
				 */
				//this.sanitize_form_field();

				$doc.on( 'submit', '#event-lightbox .event-auth-form', this.ajax_login );
			},
			load_form_register: function( e ) {
				e.preventDefault();

				var $button = $( this ),
				    eventID = $button.attr( 'data-event' );

				$.ajax( {
					url: WPEMS.ajaxurl,
					type: 'POST',
					dataType: 'html',
					//async: false,
					data: {
						event_id: eventID,
						nonce: WPEMS.register_button,
						action: 'load_form_register'
					},
					beforeSend: function() {
						$button.addClass( 'updating-icon' );
					},
					success: function( html ) {
						Unicamp_Event_Frontend.lightbox( html );
					},
					complete: function() {
						$button.removeClass( 'updating-icon' );
					}
				} );
			},
			/**
			 * Ajax action register form
			 * @returns boolean
			 */
			book_event_form: function( e ) {
				e.preventDefault();
				var _self    = $( this ),
				    _data    = _self.serializeArray(),
				    button   = _self.find( 'button[type="submit"]' ),
				    _notices = _self.find( '.tp-event-notice' );

				$.ajax( {
					url: WPEMS.ajaxurl,
					type: 'POST',
					data: _data,
					dataType: 'json',
					beforeSend: function() {
						_notices.slideUp().remove();
						button.addClass( 'event-register-loading' );
						_self.addClass( 'active' );
					}
				} ).done( function( res ) {
					button.removeClass( 'event-register-loading' );
					if ( typeof res.status === 'undefined' ) {
						Unicamp_Event_Frontend.set_message( _self, WPEMS.something_wrong );
						return;
					}

					if ( res.status === true && typeof res.url !== 'undefined' && res.url !== '' ) {
						window.location.href = res.url;
					}

					if ( res.status === true && res.url == '' && typeof res.event !== 'undefined' ) {
						$.magnificPopup.close();
						$( '.woocommerce-message' ).hide();
						setTimeout( function() {
							$( '.entry-register, .event_register_foot' ).append(
								'<div class="woocommerce-message">' +
								WPEMS.woo_cart_url +
								'<p>' + '“' + res.event + '”' + WPEMS.add_to_cart + '</p>' +
								'</div>'
							)
						}, 100 );
					}

					if ( typeof res.message !== 'undefined' ) {
						Unicamp_Event_Frontend.set_message( _self, res.message );
						return;
					}

				} ).fail( function() {
					button.removeClass( 'event-register-loading' );
					Unicamp_Event_Frontend.set_message( _self, WPEMS.something_wrong );
					return;
				} ).always( function() {
					_self.removeClass( 'active' );
				} );
				// button.removeClass('event-register-loading');
				return false;
			},
			set_message: function( form, message ) {
				var html = '<div class="tp-event-notice error">';
				html += '<div class="event_auth_register_message_error">' + message + '</div>';
				html += '</div>';
				form.find( '.event_register_foot' ).append( html );
			},
			/**
			 * sanitize form field
			 * @returns null
			 */
			sanitize_form_field: function() {
				var _form_fields = $( '.form-row.form-required' );

				for ( var i = 0; i < _form_fields.length; i ++ ) {
					var field = $( _form_fields[ i ] ),
					    input = field.find( 'input' );

					input.on( 'blur', function( e ) {
						e.preventDefault();
						var _this     = $( this ),
						    _form_row = _this.parents( '.form-row:first' );
						if ( ! _form_row.hasClass( 'form-required' ) ) {
							return;
						}

						if ( _this.val() == '' ) {
							_form_row.removeClass( 'validated' ).addClass( 'has-error' );
						} else {
							_form_row.removeClass( 'has-error' ).addClass( 'validated' );
						}
					} );
				}
			},
			lightbox: function( content ) {
				var html = [];
				html.push( '<div id="event-lightbox">' );
				html.push( content );
				html.push( '</div>' );

				$.magnificPopup.open( {
					items: {
						type: 'inline',
						src: $( html.join( '' ) )
					},
					mainClass: 'event-lightbox-wrap',
					callbacks: {
						open: function() {
							var lightbox = $( '#event-lightbox' );

							lightbox.addClass( 'event-fade' );
							var timeout = setTimeout( function() {
								lightbox.addClass( 'event-in' );
								clearTimeout( timeout );
								Unicamp_Event_Frontend.sanitize_form_field();
							}, 100 );
						},
						close: function() {
							var lightbox = $( '#event-lightbox' );
							lightbox.remove();
						}
					}
				} );
			},
			ajax_login: function( e ) {
				e.preventDefault();

				var _this     = $( this ),
				    _button   = _this.find( '#wp-submit' ),
				    _lightbox = $( '#event-lightbox' ),
				    _data     = _this.serializeArray();

				$.ajax( {
					url: WPEMS.ajaxurl,
					type: 'POST',
					data: _data,
					async: false,
					beforeSend: function() {
//                    setTimeout( function(){
						_lightbox.find( '.tp-event-notice' ).slideUp().remove();
//                    } );
						_button.addClass( 'event-register-loading' );
					}
				} ).always( function() {
					_button.find( '.event-icon-spinner2' ).remove();
				} ).done( function( res ) {
					if ( typeof res.notices !== 'undefined' ) {
						_this.before( res.notices );
					}

					if ( typeof res.status !== 'undefined' && res.status === true ) {
						if ( typeof res.redirect !== 'undefined' && res.redirect ) {
							window.location.href = res.redirect;
						} else {
							window.location.reload();
						}
					}
				} ).fail( function( jqXHR, textStatus, errorThrown ) {
					var html = '<ul class="tp-event-notice error">';
					html += '<li>' + jqXHR + '</li>';
					html += '</ul>';
					_this.before( res.notices );
				} );

				return false;
			}
		};
	}
)( jQuery );
