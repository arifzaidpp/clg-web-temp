<?php

/**
 * A customizer control for rendering the export/import form.
 * ==============
 */
require_once( ABSPATH . 'wp-includes/class-wp-customize-control.php' );

class Insight_IO_Control extends WP_Customize_Control {

	/**
	 * Renders the control content.
	 *
	 * @access protected
	 * @return void
	 */
	protected function render_content() {
		?>
		<span class="customize-control-title">
			<?php esc_html_e( 'Export', 'insight-core' ); ?>
	    </span>
		<span class="description customize-control-description">
	        <?php esc_html_e( 'Click the button below to export the customization settings for this theme.', 'insight-core' ); ?>
	    </span>
		<a type="button" class="button"
		   href="<?php echo get_site_url() . '/wp-admin/options.php?page=insight_export_customizer_options'; ?>"><?php esc_html_e( 'Export', 'insight-core' ); ?></a>
		<span class="customize-control-title">
		    <hr style="margin: 20px 0px 10px;"/>
			<?php esc_html_e( 'Import', 'insight-core' ); ?>
        </span>
		<span class="description customize-control-description">
	        <?php esc_html_e( 'Upload a file to import customization settings for this theme.', 'insight-core' ); ?>
	    </span>
		<a type="button" class="button" id="import-btn"><?php esc_html_e( 'Import', 'insight-core' ); ?></a>
		<form id="import-form" style="display: none;">
			<input type="file" id="import-file" name="import-file"/>
			<input type="hidden" name="action" value="insight_customizer_options_import"/>
			<?php wp_nonce_field( 'customizer_import', 'insight_customizer_import' ); ?>
		</form>
		<script type="text/javascript">
			jQuery( function( $ ) {
				$( '#import-btn' ).on( 'click', function( evt ) {
					evt.preventDefault();

					if ( confirm( 'Do you want to import customizer options?' ) ) {
						$( '#import-file' ).on( 'change.insight', function() {
							$( this ).off( 'change.insight' );

							$.ajax( {
								url: ajaxurl,
								type: 'POST',
								data: new FormData( $( '#import-form' )[ 0 ] ),
								cache: false,
								contentType: false,
								processData: false,
								dataType: 'json',
								success: function( response ) {
									alert( response.data.messages );

									if ( response.success ) {
										location.reload();
									}
								}
							} );
						} );

						$( '#import-file' ).trigger( 'click' );
					}
				} );
			} );
		</script>
		<?php
	}
}
