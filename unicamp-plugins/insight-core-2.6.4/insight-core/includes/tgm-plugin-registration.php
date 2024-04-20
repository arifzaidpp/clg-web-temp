<?php
if ( ! function_exists( 'insight_core_register_plugins' ) ) :
	function insight_core_register_plugins() {
		$recommended_plugins = apply_filters( 'insight_core_tgm_plugins', array() );
		$compatible_plugins  = apply_filters( 'insight_core_compatible_plugins', array() );

		$plugins = array_merge( $recommended_plugins, $compatible_plugins );

		$config = array(
			'id'           => 'tgmpa',
			// Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',
			// Default absolute path to pre-packaged plugins.
			'menu'         => 'tgmpa-install-plugins',
			// Menu slug.
			'parent_slug'  => 'insight-core',
			// Parent menu slug.
			'capability'   => 'edit_theme_options',
			// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => false,
			// Show admin notices or not.
			'dismissable'  => false,
			// If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',
			// If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => true,
			// Automatically activate plugins after installation or not.
			'message'      => '',
			// Message to output right before the plugins table.
			'strings'      => array(
				'page_title'                      => esc_html__( 'Install Required Plugins', 'insight-core' ),
				'menu_title'                      => esc_html__( 'Plugins', 'insight-core' ),
				'installing'                      => __( 'Installing Plugin: %s', 'insight-core' ),
				// %s = plugin name.
				'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'insight-core' ),
				'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'insight-core' ),
				// %1$s = plugin name(s).
				'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'insight-core' ),
				// %1$s = plugin name(s).
				'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %1$s plugin.', 'Sorry, but you do not have the correct permissions to install the %1$s plugins.', 'insight-core' ),
				// %1$s = plugin name(s).
				'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'insight-core' ),
				// %1$s = plugin name(s).
				'notice_ask_to_update_maybe'      => _n_noop( 'There is an update available for: %1$s.', 'There are updates available for the following plugins: %1$s.', 'insight-core' ),
				// %1$s = plugin name(s).
				'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %1$s plugin.', 'Sorry, but you do not have the correct permissions to update the %1$s plugins.', 'insight-core' ),
				// %1$s = plugin name(s).
				'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'insight-core' ),
				// %1$s = plugin name(s).
				'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'insight-core' ),
				// %1$s = plugin name(s).
				'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %1$s plugin.', 'Sorry, but you do not have the correct permissions to activate the %1$s plugins.', 'insight-core' ),
				// %1$s = plugin name(s).
				'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'insight-core' ),
				'update_link'                     => _n_noop( 'Begin updating plugin', 'Begin updating plugins', 'insight-core' ),
				'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'insight-core' ),
				'return'                          => esc_html__( 'Return to Required Plugins Installer', 'insight-core' ),
				'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'insight-core' ),
				'activated_successfully'          => esc_html__( 'The following plugin was activated successfully:', 'insight-core' ),
				'plugin_already_active'           => esc_html__( 'No action taken. Plugin %1$s was already active.', 'insight-core' ),
				// %1$s = plugin name(s).
				'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'insight-core' ),
				// %1$s = plugin name(s).
				'complete'                        => esc_html__( 'All plugins installed and activated successfully. %1$s', 'insight-core' ),
				// %s = dashboard link.
				'contact_admin'                   => esc_html__( 'Please contact the administrator of this site for help.', 'insight-core' ),
				'nag_type'                        => 'updated',
				// Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
			)
		);

		tgmpa( $plugins, $config );
	}

	add_action( 'tgmpa_register', 'insight_core_register_plugins', 11, 1 );
endif;

function insight_core_get_plugin_thumbnail( $slug ) {
	switch ( $slug ):
		case 'insight-core':
		case 'insight-swatches':
		case 'insight-product-brands':
		case 'tm-addons-for-elementor':
			$thumbnail_key = 'insight';
			break;
		default:
			$thumbnail_key = $slug;
			break;
	endswitch;

	return ! empty( $thumbnail_key ) ? INSIGHT_CORE_PATH . "/assets/images/plugins/{$thumbnail_key}.png" : '';
}
