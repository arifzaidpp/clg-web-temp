(
	function( $ ) {
		'use strict';

		$( window ).on( 'elementor:init', function() {
			var ControlAutocompleteItemView = elementor.modules.controls.Select2.extend( {
				cache: null,
				isTitlesReceived: false,

				/*getSelect2Placeholder: function getSelect2Placeholder() {
					return {
						id: '',
						text: elementor.translate( 'all' )
					};
				},*/

				getQueryData: function getQueryData() {
					// Use a clone to keep model data unchanged:
					var autocomplete = elementorCommon.helpers.cloneObject( this.model.get( 'autocomplete' ) );

					if ( _.isEmpty( autocomplete.query ) ) {
						autocomplete.query = {};
					} // Specific for Group_Control_Query

					if ( 'cpt_tax' === autocomplete.object ) {
						autocomplete.object = 'tax';

						if ( _.isEmpty( autocomplete.query ) || _.isEmpty( autocomplete.query.post_type ) ) {
							autocomplete.query.post_type = this.elementSettingsModel.attributes[ 'post_type' ];
						}
					}

					return {
						autocomplete: autocomplete
					};
				},

				getSelect2DefaultOptions: function getSelect2DefaultOptions() {
					var self = this;

					return jQuery.extend( elementor.modules.controls.Select2.prototype.getSelect2DefaultOptions.apply( this, arguments ), {
						ajax: {
							transport: function transport( params, success, failure ) {
								var data   = {},
								    action = 'unicamp_elementor_autocomplete_search';

								var data = self.getQueryData();
								var autocomplete = self.model.get( 'autocomplete' );

								data.q = params.data.q;
								data.autocomplete = autocomplete;

								return elementorCommon.ajax.addRequest( action, {
									data: data,
									success: success,
									error: failure
								} );
							},
							data: function data( params ) {
								return {
									q: params.term,
									page: params.page
								};
							},
							cache: true
						},
						escapeMarkup: function escapeMarkup( markup ) {
							return markup;
						},
						minimumInputLength: 1
					} );
				},

				getValueTitles: function getValueTitles() {
					var self           = this,
					    data           = {},
					    autocomplete   = self.model.get( 'autocomplete' ),
					    action         = 'unicamp_elementor_autocomplete_render',
					    ids            = this.getControlValue(),
					    filterTypeName = 'autocomplete',
					    filterType     = {};

					filterType = this.model.get( filterTypeName ).object;
					data.get_titles = self.getQueryData().autocomplete;
					data.unique_id = '' + self.cid + filterType;
					data.autocomplete = autocomplete;

					if ( ! ids ) {
						return;
					}

					if ( ! _.isArray( ids ) ) {
						ids = [ ids ];
					}

					elementorCommon.ajax.loadObjects( {
						action: action,
						ids: ids,
						data: data,
						before: function before() {
							self.addControlSpinner();
						},
						success: function success( ajaxData ) {
							self.isTitlesReceived = true;
							self.model.set( 'options', ajaxData );
							self.render();
						}
					} );
				},

				addControlSpinner: function addControlSpinner() {
					this.ui.select.prop( 'disabled', true );
					this.$el.find( '.elementor-control-title' ).after( '<span class="elementor-control-spinner">&nbsp;<i class="eicon-spinner eicon-animation-spin"></i>&nbsp;</span>' );
				},

				onReady: function onReady() {
					if ( ! this.isTitlesReceived ) {
						this.getValueTitles();
					}
				}
			} );

			elementor.addControlView( 'autocomplete', ControlAutocompleteItemView );
		} );
	}
)( jQuery );
