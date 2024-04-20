'use strict';

(
	function( $ ) {

		window.insight_core = {
			init: function() {
				this.initDummyJS();
			},

			/**
			 * Init Import Dummy JS
			 */
			initDummyJS: function() {
				this.interval = 0;
				this.fake2Timeout;
				this.noticeTimeout;
				this.errorTimeout;
				this.intervalClerer;

				this.$select = $( '#dummy-select' );
				this.$submit = $( '#dummy-submit' );
				this.$form = $( '#dummy-form' );
				this.$response = $( '#dummy-response' );
				this.$progressBar = $( '#dummy-form .progress' );

				this.selectDummy();
				this.importAction();
			},

			selectDummy: function() {
				var self = this;

				self.$select.on( 'change', function() {
					var previewSrc = self.$select.find( ':selected' ).attr( 'data-screenshot' );
					var importedCount = parseInt( self.$select.find( ':selected' ).attr( 'data-imported-count' ) );
					var imported = importedCount > 0;
					var $pagePreview = $( '.page-preview', self.$form );
					var $img = $( 'img', $pagePreview );

					self.clearResponseArea();

					if ( 'undefined' != typeof previewSrc ) {
						$img.attr( 'src', previewSrc );
					}

					if ( 'undefined' != typeof imported && true === imported ) {
						$pagePreview.addClass( 'imported' ).find( 'span' ).remove();
						$img.after( '<span class="imported-count">(imported ' + importedCount + ' ' + (
								importedCount > 1 ? 'times' : 'time'
							) + ')</span>' );
					} else {
						$pagePreview.removeClass( 'imported' );
						$pagePreview.find( 'span' ).remove();
					}

					if ( '' == self.$select.val() ) {
						self.$submit.attr( 'disabled', 'disabled' );
					} else {
						self.$submit.removeAttr( 'disabled' );
					}
				} );
			},

			importAction: function() {
				var self = this;

				self.$form.on( 'submit', function( e ) {
					e.preventDefault();

					$( this ).addClass( 'loading' );

					clearInterval( self.intervalClerer );

					self.initialLoading( 30, 50, 70 );

					self.clearResponseArea();

					self.$response.fadeOut( 200, function() {
						$( this ).html( '' );
					} );

					var data = $( this ).serialize(), selected = self.$select.find( ':selected' );

					data += "&action=import_dummy";

					self.importAjax( data );
				} );
			},

			importAjax: function( data ) {
				var self = this;

				$.ajax( {
					url: ic_vars.ajax_url,
					data: data,
					dataType: 'json',
					timeout: 10000,
					success: function( response ) {

						if ( ! response ) {
							self.$response.html( '<div class="import-warning">Empty AJAX response, please try again.</div>' )
							    .fadeIn();
						} else if ( response.status == 'success' ) {
							self.$response.html( '<div class="import-success">' + response.message + '</div>' )
							    .fadeIn();

							var importedCount = self.$select.find( ':selected' ).attr( 'data-imported-count' );

							if ( 'undefined' == typeof importedCount ) {
								importedCount = 1;
							} else {
								importedCount ++;
							}

							self.$select.find( ':selected' ).attr( 'data-imported-count', importedCount );

							$( '.page-preview', self.$form ).addClass( 'imported' ).find( 'span' ).remove();
							$( '.page-preview img', self.$form )
								.after( '<span class="imported-count">(imported ' + importedCount + ' ' + (
										importedCount > 1 ? 'times' : 'time'
									) + ')</span>' );

						} else if ( response.status == 'fail' ) {
							self.$response.html( '<div class="import-error">' + response.message + '</div>' )
							    .fadeIn();
						} else {
							self.$response.html( '<div class="">' + response + '</div>' ).fadeIn();
						}
					},
					error: function( response ) {
						self.$response.html( '<div class="import-warning">Import AJAX problem. Please try import data manually.</div>' )
						    .fadeIn();
						console.log( response );
						console.log( 'Import ajax ERROR' );
					},
					complete: function() {

						self.clearInitialLoading();

						self.$form.removeClass( 'loading' );

						self.updateProgress( self.$progressBar, 100, 0 );

						self.$progressBar.parent().find( '.import-notice' ).remove();

						self.intervalClerer = setTimeout( function() {
							self.destroyProgressBar( 200 );
						}, 2000 );
					},
				} );
			},

			initialLoading: function( fake1progress, fake2progress, noticeProgress ) {
				var self = this;

				self.destroyProgressBar( 0 );

				self.updateProgress( self.$progressBar, fake1progress, 200 );

				self.fake2Timeout = setTimeout( function() {
					self.updateProgress( self.$progressBar, fake2progress, 100 );
				}, 25000 );

				self.noticeTimeout = setTimeout( function() {
					self.updateProgress( self.$progressBar, noticeProgress, 100 );
					self.$progressBar.after( '<p class="import-notice small">Please wait, theme needs much time to download all attachments</p>' );
				}, 60000 );

				self.errorTimeout = setTimeout( function() {
					self.$progressBar.parent().find( '.import-notice' ).remove();
					self.$progressBar.after( '<p class="import-error small">Something wrong with import. Please try to import data manually</p>' );
				}, 3100000 );
			},

			clearInitialLoading: function() {
				clearTimeout( this.fake2Timeout );
				clearTimeout( this.noticeTimeout );
				clearTimeout( this.errorTimeout );
			},

			destroyProgressBar: function( hide ) {
				this.$progressBar.hide( hide ).find( 'div' ).attr( 'aria-valuenow', 0 ).width( 0 );
			},

			updateProgress: function( el, to, interval ) {
				el.show();

				clearInterval( this.interval );

				var from = el.find( 'div' ).attr( 'aria-valuenow' ), i = from;

				if ( interval == 0 ) {
					el.find( 'div' )
					  .attr( 'aria-valuenow', 100 )
					  .width( el.find( 'div' ).attr( 'aria-valuenow' ) + '%' );
				} else {
					this.interval = setInterval( function() {
						if ( i == to ) {
							clearInterval( this.interval );
						} else {
							i ++;
							el.find( 'div' )
							  .attr( 'aria-valuenow', i )
							  .width( el.find( 'div' ).attr( 'aria-valuenow' ) + '%' );
						}
					}, interval );
				}
			},

			clearResponseArea: function() {
				this.$response.fadeOut( 200, function() {
					$( this ).html( '' );
				} );
			}

		}

	}
)( jQuery );

jQuery( document ).ready( function() {
	insight_core.init();
} );
