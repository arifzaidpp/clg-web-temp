(
	function( $ ) {
		'use strict';

		var $body = $( 'body' );

		$( document ).ready( function() {
			$( document.body ).on( 'submit', '#tutor-ask-question-form, #tutor-reply-question-form', function( evt ) {
				evt.preventDefault();
				var $form = $( this );
				var $button = $form.find( 'button[type="submit"]' );

				$.ajax( {
					url: $unicamp.ajaxurl,
					type: 'POST',
					data: $form.serialize(),
					dataType: 'json',
					beforeSend: function() {
						$button.addClass( 'updating-icon' );
						$form.find( '.form-response-messages' ).text( '' ).removeClass( 'success error' ).hide();
					},
					success: function( response ) {
						if ( response.success ) {
							$form.find( '.form-response-messages' ).addClass( 'success' ).text( response.data.message ).show();
							location.reload();
						} else {
							$form.find( '.form-response-messages' ).addClass( 'error' ).text( response.data.message ).show();
						}
					},
					complete: function() {
						$button.removeClass( 'updating-icon' );
					}
				} );

				return false;
			} );

			$( document.body ).on( 'click', '.btn-add-an-answer', function( evt ) {
				var questionID = $( this ).data( 'question-id' );
				var $replyModal = $( $( this ).data( 'unicamp-target' ) );

				if ( $replyModal.length > 0 ) {
					$replyModal.find( 'input[name="question_id"]' ).val( questionID );
				}
			} );

			/**
			 * Edition version of wishlist function.
			 */
			$( document ).on( 'click', '.unicamp-course-wishlist-btn', function( e ) {
				e.preventDefault();

				if ( $( 'body' ).hasClass( 'logged-in' ) ) {
					var $button = $( this );
					var courseId = $button.attr( 'data-course-id' );

					$.ajax( {
						url: $unicamp.ajaxurl,
						type: 'POST',
						data: {
							course_id: courseId,
							'action': 'tutor_course_add_to_wishlist'
						},
						beforeSend: function() {
							$button.children( '.button-content-wrapper' ).addClass( 'updating-icon' );
						},
						success: function( data ) {
							if ( data.success ) {
								var $buttonWrapper = $button.parent( '.tm-button-wrapper' );

								if ( data.data.status === 'added' ) {
									$buttonWrapper.attr( 'aria-label', $unicampCourseWishlist.addedText );
									$buttonWrapper.addClass( 'added' );

									$button.addClass( 'has-wish-listed' );
									$button.find( '.button-text' ).text( $unicampCourseWishlist.addedText );
								} else {
									$buttonWrapper.attr( 'aria-label', $unicampCourseWishlist.addText );
									$buttonWrapper.removeClass( 'added' );

									$button.removeClass( 'has-wish-listed' );
									$button.find( '.button-text' ).text( $unicampCourseWishlist.addText );
								}
							} else {
								window.location = data.data.redirect_to;
							}
						},
						complete: function() {
							$button.children( '.button-content-wrapper' ).removeClass( 'updating-icon' );
						}
					} );
				}
			} );

			/**
			 * Edition version of edit review form in single.
			 */
			$( document ).on( 'click', '.custom_tutor_submit_review_btn', function( e ) {
				e.preventDefault();
				var $that = $( this );
				var rating = $that.closest( 'form' ).find( 'input[name="tutor_rating_gen_input"]' ).val();
				var review = $that.closest( 'form' ).find( 'textarea[name="review"]' ).val();
				review = review.trim();

				var course_id = $( 'input[name="tutor_course_id"]' ).val();
				var data = {
					course_id: course_id,
					rating: rating,
					review: review,
					action: 'unicamp_place_rating'
				};

				if ( review ) {
					$.ajax( {
						url: _tutorobject.ajaxurl,
						type: 'POST',
						data: data,
						beforeSend: function() {
							$that.addClass( 'updating-icon' );
						},
						success: function( data ) {
							var review_id = data.data.review_id;
							var review = data.data.review;
							$( '.tutor-review-' + review_id + ' .review-content' ).html( review );
							location.reload();
						}
					} );
				}
			} );

			$( '.btn-toggle-lesson-sidebar' ).on( 'click', function() {
				$body.toggleClass( 'lesson-sidebar-on' );
			} );

			$( document ).on( 'click', '.tutor-single-lesson-a', function() {
				$body.removeClass( 'lesson-sidebar-on' );
			} );

			$( document ).on( 'click', function( evt ) {
				if ( evt.target.id === 'tutor-lesson-sidebar' ) {
					return;
				}

				if ( $( evt.target ).closest( '#tutor-lesson-sidebar' ).length ) {
					return;
				}

				if ( $( evt.target ).closest( '.btn-toggle-lesson-sidebar' ).length ) {
					return;
				}

				if ( window.innerWidth >= 992 ) {
					return;
				}

				$body.removeClass( 'lesson-sidebar-on' );
			} );

			$( '.dashboard-header-toggle-menu' ).on( 'click', function() {
				$body.toggleClass( 'dashboard-nav-opened' );
			} );

			$( '#tutor-dashboard-left-menu' ).on( 'click', function( evt ) {
				if ( evt.target.id === 'dashboard-nav-wrapper' ) {
					return;
				}

				if ( $( evt.target ).closest( '#dashboard-nav-wrapper' ).length ) {
					return;
				}

				$body.removeClass( 'dashboard-nav-opened' );
			} );

			handleDashboardMenuBar();

			$( window ).on( 'resize', function() {
				handleDashboardMenuBar();
			} );
		} );

		function handleDashboardMenuBar() {
			var wWidth = window.innerWidth;

			if ( wWidth >= 1200 ) {
				$body.addClass( 'dashboard-nav-fixed' );
				// Fix when resize from mobile to desktop.
				$body.removeClass( 'dashboard-nav-opened' );
			} else {
				$body.removeClass( 'dashboard-nav-fixed' );
			}

			var wHeight = window.innerHeight;
			var navHeaderHeight = $( '.dashboard-nav-header' ).outerHeight();
			var bottomSpacing = 50;
			var navContentHeight = wHeight - navHeaderHeight - bottomSpacing;

			$( '.dashboard-nav-content-inner' ).height( navContentHeight ).perfectScrollbar( { suppressScrollX: true } );
		}
	}( jQuery )
);
