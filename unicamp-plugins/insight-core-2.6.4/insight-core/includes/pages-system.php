<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
InsightCore::update_option_count( 'insight_core_view_system' );
?>
<div class="wrap insight-core-wrap">
	<?php
	$insight_core_info = InsightCore::$info;
	include_once( INSIGHT_CORE_INC_DIR . '/pages-header.php' );
	add_thickbox();
	function insight_core_let_to_num( $size ) {
		$l   = substr( $size, -1 );
		$ret = substr( $size, 0, -1 );
		switch ( strtoupper( $l ) ) {
			case 'P':
				$ret *= 1024;
			case 'T':
				$ret *= 1024;
			case 'G':
				$ret *= 1024;
			case 'M':
				$ret *= 1024;
			case 'K':
				$ret *= 1024;
		}

		return $ret;
	}

	?>
	<div class="insight-core-body">
		<div class="box green box-purchase">
			<div class="box-header">
				<span class="icon"><i class="fa fa-lightbulb-o"></i></span> WordPress Environment
			</div>
			<div class="box-body">
				<table class="wp-list-table widefat striped system" cellspacing="0">
					<tbody>
					<tr>
						<td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'The URL of your site\'s homepage.', 'insight-core' ) . '">[?]</a>'; ?></td>
						<td class="title"><?php _e( 'Home URL', 'insight-core' ); ?></td>
						<td><?php form_option( 'home' ); ?></td>
					</tr>
					<tr>
						<td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'The root URL of your site.', 'insight-core' ) . '">[?]</a>'; ?></td>
						<td class="title"><?php _e( 'Site URL', 'insight-core' ); ?></td>
						<td><?php form_option( 'siteurl' ); ?></td>
					</tr>
					<tr>
						<td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'The version of WordPress installed on your site.', 'insight-core' ) . '">[?]</a>'; ?></td>
						<td class="title"><?php _e( 'WP Version', 'insight-core' ); ?></td>
						<td><?php bloginfo( 'version' ); ?></td>
					</tr>
					<tr>
						<td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'Whether or not you have WordPress Multisite enabled.', 'insight-core' ) . '">[?]</a>'; ?></td>
						<td class="title"><?php _e( 'WP Multisite', 'insight-core' ); ?></td>
						<td><?php if ( is_multisite() ) {
								echo '&#10004;';
							} else {
								echo '&ndash;';
							} ?></td>
					</tr>
					<tr>
						<td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'The maximum amount of memory (RAM) that your site can use at one time.', 'insight-core' ) . '">[?]</a>'; ?></td>
						<td class="title"><?php _e( 'WP Memory Limit', 'insight-core' ); ?></td>
						<td><?php
							$memory = insight_core_let_to_num( WP_MEMORY_LIMIT );

							if ( function_exists( 'memory_get_usage' ) ) {
								$server_memory = insight_core_let_to_num( @ini_get( 'memory_limit' ) );
								$memory        = max( $memory, $server_memory );
							}

							if ( $memory < 134217728 ) {
								echo '<mark class="error">' . sprintf( __( '%s - We recommend setting memory to at least 128MB. See: <a href="%s" target="_blank">Increasing memory allocated to PHP</a>', 'insight-core' ), size_format( $memory ), 'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ) . '</mark>';
							} else {
								echo '<mark class="yes">' . size_format( $memory ) . '</mark>';
							}
							?></td>
					</tr>
					<tr>
						<td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'Displays whether or not WordPress is in Debug Mode.', 'insight-core' ) . '">[?]</a>'; ?></td>
						<td class="title"><?php _e( 'WP Debug Mode', 'insight-core' ); ?></td>
						<td><?php if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
								echo '<mark class="yes">&#10004;</mark>';
							} else {
								echo '&ndash;';
							} ?></td>
					</tr>
					<tr>
						<td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'The current language used by WordPress. Default = English', 'insight-core' ) . '">[?]</a>'; ?></td>
						<td class="title"><?php _e( 'Language', 'insight-core' ); ?></td>
						<td><?php echo get_locale() ?></td>
					</tr>
					<tr>
						<td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'The current theme name', 'insight-core' ) . '">[?]</a>'; ?></td>
						<td class="title"><?php _e( 'Theme Name', 'insight-core' ); ?></td>
						<td><?php echo INSIGHT_CORE_THEME_NAME; ?></td>
					</tr>
					<tr>
						<td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'The current theme version', 'insight-core' ) . '">[?]</a>'; ?></td>
						<td class="title"><?php _e( 'Theme Version', 'insight-core' ); ?></td>
						<td><?php echo INSIGHT_CORE_THEME_VERSION; ?></td>
					</tr>
					<tr>
						<td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'Installed plugins', 'insight-core' ) . '">[?]</a>'; ?></td>
						<td class="title"><?php _e( 'Activated Plugins', 'insight-core' ); ?></td>
						<td>
							<?php
							$all_plugins = get_plugins();
							foreach ( $all_plugins as $key => $val ) {
								if ( is_plugin_active( $key ) ) {
									echo esc_html( sprintf( '%1$s %2$s, ', $val['Name'], $val['Version'] ) );
								}
							}
							?>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="box green box-purchase">
			<div class="box-header">
				<span class="icon"><i class="fa fa-lightbulb-o"></i></span> Server Environment
			</div>
			<div class="box-body">
				<table class="wp-list-table widefat striped system" cellspacing="0">
					<tbody>
					<tr>
						<td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'Information about the web server that is currently hosting your site.', 'insight-core' ) . '">[?]</a>'; ?></td>
						<td class="title"><?php _e( 'Server Info', 'insight-core' ); ?></td>
						<td><?php echo esc_html( $_SERVER['SERVER_SOFTWARE'] ); ?></td>
					</tr>
					<tr>
						<td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'The version of PHP installed on your hosting server.', 'insight-core' ) . '">[?]</a>'; ?></td>
						<td class="title"><?php _e( 'PHP Version', 'insight-core' ); ?></td>
						<td><?php if ( function_exists( 'phpversion' ) ) {
								$php_version = phpversion();

								if ( version_compare( $php_version, '5.6', '<' ) ) {
									echo '<mark class="error">' . esc_html__( 'Insight Core requires PHP version 5.6 or greater. Please contact your hosting provider to upgrade PHP version.', 'insight-core' ) . '</mark>';
								} else {
									echo esc_html( $php_version );
								}
							}
							?></td>
					</tr>
					<?php if ( function_exists( 'ini_get' ) ) : ?>
						<tr>
							<td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'The largest filesize that can be contained in one post.', 'insight-core' ) . '">[?]</a>'; ?></td>
							<td class="title"><?php _e( 'PHP Post Max Size', 'insight-core' ); ?></td>
							<td><?php echo size_format( insight_core_let_to_num( ini_get( 'post_max_size' ) ) ); ?></td>
						</tr>
						<tr>
							<td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups)', 'insight-core' ) . '">[?]</a>'; ?></td>
							<td class="title"><?php _e( 'PHP Time Limit', 'insight-core' ); ?></td>
							<td><?php
								$time_limit = ini_get( 'max_execution_time' );

								if ( $time_limit > 0 && $time_limit < 180 ) {
									echo '<mark class="error">' . sprintf( __( '%s - We recommend setting max execution time to at least 180. See: <a href="%s" target="_blank">Increasing max execution to PHP</a>', 'insight-core' ), $time_limit, 'http://codex.wordpress.org/Common_WordPress_Errors#Maximum_execution_time_exceeded' ) . '</mark>';
								} else {
									echo '<mark class="yes">' . $time_limit . '</mark>';
								}
								?></td>
						</tr>
						<tr>
							<td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'insight-core' ) . '">[?]</a>'; ?></td>
							<td class="title"><?php _e( 'PHP Max Input Vars', 'insight-core' ); ?></td>
							<td><?php
								$max_input_vars = ini_get( 'max_input_vars' );

								if ( $max_input_vars < 5000 ) {
									echo '<mark class="error">' . sprintf( __( '%s - Max input vars limitation will truncate POST data such as menus. Required >= 5000', 'insight-core' ), $max_input_vars ) . '</mark>';
								} else {
									echo '<mark class="yes">' . $max_input_vars . '</mark>';
								}
								?></td>
						</tr>
					<?php endif; ?>
					<tr>
						<td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'The version of MySQL installed on your hosting server.', 'insight-core' ) . '">[?]</a>'; ?></td>
						<td class="title"><?php _e( 'MySQL Version', 'insight-core' ); ?></td>
						<td>
							<?php
							global $wpdb;
							echo esc_html( $wpdb->db_version() );
							?>
						</td>
					</tr>
					<tr>
						<td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'The largest filesize that can be uploaded to your WordPress installation.', 'insight-core' ) . '">[?]</a>'; ?></td>
						<td class="title"><?php _e( 'Max Upload Size', 'insight-core' ); ?></td>
						<td><?php echo size_format( wp_max_upload_size() ); ?></td>
					</tr>
					<tr>
						<td class="help"><?php echo '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'The default timezone for your server.', 'insight-core' ) . '">[?]</a>'; ?></td>
						<td class="title"><?php _e( 'Default Timezone is UTC', 'insight-core' ); ?></td>
						<td><?php
							$default_timezone = date_default_timezone_get();
							if ( 'UTC' !== $default_timezone ) {
								echo '<mark class="error">&#10005; ' . sprintf( __( 'Default timezone is %s - it should be UTC', 'insight-core' ), $default_timezone ) . '</mark>';
							} else {
								echo '<mark class="yes">&#10004;</mark>';
							} ?>
						</td>
					</tr>
					<?php
					$checks = array();
					// fsockopen/cURL
					$checks['fsockopen_curl']['name'] = 'fsockopen/cURL';
					$checks['fsockopen_curl']['help'] = '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'Plugins may use it when communicating with remote services.', 'insight-core' ) . '">[?]</a>';
					if ( function_exists( 'fsockopen' ) || function_exists( 'curl_init' ) ) {
						$checks['fsockopen_curl']['success'] = true;
					} else {
						$checks['fsockopen_curl']['success'] = false;
						$checks['fsockopen_curl']['note']    = __( 'Your server does not have fsockopen or cURL enabled. Please contact your hosting provider to enable it.', 'insight-core' ) . '</mark>';
					}
					// DOMDocument
					$checks['dom_document']['name'] = 'DOMDocument';
					$checks['dom_document']['help'] = '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'WordPress Importer use DOMDocument.', 'insight-core' ) . '">[?]</a>';
					if ( class_exists( 'DOMDocument' ) ) {
						$checks['dom_document']['success'] = true;
					} else {
						$checks['dom_document']['success'] = false;
						$checks['dom_document']['note']    = sprintf( __( 'Your server does not have <a href="%s">the DOM extension</a> class enabled. Please contact your hosting provider to enable it.', 'insight-core' ), 'http://php.net/manual/en/intro.dom.php' ) . '</mark>';
					}
					// XMLReader
					$checks['xml_reader']['name'] = 'XMLReader';
					$checks['xml_reader']['help'] = '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'WordPress Importer use XMLReader.', 'insight-core' ) . '">[?]</a>';
					if ( class_exists( 'XMLReader' ) ) {
						$checks['xml_reader']['success'] = true;
					} else {
						$checks['xml_reader']['success'] = false;
						$checks['xml_reader']['note']    = sprintf( __( 'Your server does not have <a href="%s">the XMLReader extension</a> class enabled. Please contact your hosting provider to enable it.', 'insight-core' ), 'http://php.net/manual/en/intro.xmlreader.php' ) . '</mark>';
					}
					// WP Remote Get Check
					$checks['wp_remote_get']['name'] = __( 'Remote Get', 'insight-core' );
					$checks['wp_remote_get']['help'] = '<a href="#" class="hint--right" aria-label="' . esc_attr__( 'Retrieve the raw response from the HTTP request using the GET method.', 'insight-core' ) . '">[?]</a>';
					$response                        = wp_remote_get( INSIGHT_CORE_PATH . '/assets/test.txt' );
					if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
						$checks['wp_remote_get']['success'] = true;
					} else {
						$checks['wp_remote_get']['note'] = __( ' WordPress function <a href="https://codex.wordpress.org/Function_Reference/wp_remote_get">wp_remote_get()</a> test failed. Please contact your hosting provider to enable it.', 'insight-core' );
						if ( is_wp_error( $response ) ) {
							$checks['wp_remote_get']['note'] .= ' ' . sprintf( __( 'Error: %s', 'insight-core' ), sanitize_text_field( $response->get_error_message() ) );
						} else {
							$checks['wp_remote_get']['note'] .= ' ' . sprintf( __( 'Status code: %s', 'insight-core' ), sanitize_text_field( $response['response']['code'] ) );
						}
						$checks['wp_remote_get']['success'] = false;
					}
					foreach ( $checks as $check ) {
						$mark = ! empty( $check['success'] ) ? 'yes' : 'error';
						?>
						<tr>
							<td class="help"><?php echo isset( $check['help'] ) ? $check['help'] : ''; ?></td>
							<td class="title"><?php echo esc_html( $check['name'] ); ?></td>
							<td>
								<mark class="<?php echo esc_attr( $mark ); ?>">
									<?php echo ! empty( $check['success'] ) ? '&#10004' : '&#10005'; ?><?php echo ! empty( $check['note'] ) ? wp_kses_data( $check['note'] ) : ''; ?>
								</mark>
							</td>
						</tr>
						<?php
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php
	include_once( INSIGHT_CORE_INC_DIR . '/pages-footer.php' );
	?>
</div>
