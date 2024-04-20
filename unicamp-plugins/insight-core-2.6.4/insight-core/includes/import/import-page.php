<div class="wrap insight-core-wrap" id="insight-import-page">

	<?php
	$insight_core_info = InsightCore::$info;
	InsightCore::update_option_count( 'insight_core_view_import' );
	include_once( INSIGHT_CORE_INC_DIR . '/pages-header.php' );

	$valid              = true;
	$mess               = array();
	$delete_exist_posts = apply_filters( 'insight_core_import_delete_exist_posts', false );

	$regenerate_thumbnails = apply_filters( 'insight_core_import_generate_thumb', false );

	if ( function_exists( 'phpversion' ) ) :
		$php_version = esc_html( phpversion() );
		if ( version_compare( $php_version, '5.6', '<' ) ) :
			$valid  = false;
			$mess[] = esc_html__( 'Insight Core requires PHP version 5.6 or greater. Please contact your hosting provider to upgrade PHP version.', 'insight-core' );
		endif;
	endif;

	if ( ! function_exists( 'fsockopen' ) && ! function_exists( 'curl_init' ) ) :
		$valid  = false;
		$mess[] = esc_html_e( 'Your server does not have fsockopen or cURL enabled. Please contact your hosting provider to enable it.', 'insight-core' );
	endif;

	if ( ! class_exists( 'DOMDocument' ) ) :
		$valid  = false;
		$mess[] = sprintf( __( 'Your server does not have <a href="%s">the DOM extension</a> class enabled. Please contact your hosting provider to enable it.', 'insight-core' ), 'http://php.net/manual/en/intro.dom.php' );
	endif;

	if ( ! class_exists( 'XMLReader' ) ) :
		$valid  = false;
		$mess[] = sprintf( __( 'Your server does not have <a href="%s">the XMLReader extension</a> class enabled. Please contact your hosting provider to enable it.', 'insight-core' ), 'http://php.net/manual/en/intro.xmlreader.php' );
	endif;

	$time_limit = ini_get( 'max_execution_time' );
	if ( $time_limit != 0 && $time_limit < 180 ) :
		$valid  = false;
		$mess[] = sprintf( __( 'Your server does not meet the importer requirements. The PHP max execution time currently is %s. We recommend setting PHP max execution time to at least 180. See: <a href="%s" target="_blank">Increasing max execution to PHP</a>.
<p>If you are on shared hosting you can try adding following this line to wp-config.php : <strong>set_time_limit(300);</strong> <br />( <strong>Notice:</strong> addding this before line: /* That\'s all, stop editing! Happy blogging. */)</p>  
<p>If you are unsure of how to make these changes, you should contact your hosting provider and ask them to increase your maximum execution time.</p>', 'insight-core' ), $time_limit, 'https://wordpress.org/support/article/common-wordpress-errors/' );
	endif;
	if ( ! $valid ) {
		?>
		<div class="insight-core-body">
			<div class="box red">
				<div class="box-header">
					<span class="icon"><i class="fa fa-exclamation-circle"></i></span> Ooops!
				</div>
				<div class="box-body">
					<?php
					if ( count( $mess ) > 0 ) {
						echo '<ul>';
						foreach ( $mess as $ms ) {
							echo '<li>' . $ms . '</li>';
						}
						echo '</ul>';
					}
					?>
				</div>
			</div>
		</div>
	<?php } ?>

	<div class="insight-core-body">
		<?php
		if ( ! empty( $_POST['import_sample_data'] ) ) { // WPCS: CSRF OK. ?>

			<div class="box" id="import-working">
				<div class="box-header">
					<span class="icon"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></span>
					<?php echo esc_html__( 'The importer is working', 'insight-core' ); ?>
				</div>
				<div class="box-body">
					<div id="error-import-msg"></div>
					<span
						id="import-status"><?php esc_html_e( 'Preparing to connect to server', 'insight-core' ); ?>
						...</span>
					<div class="progress" style="height:35px;">
						<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar"
						     aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"
						     id="insight-import-progressbar"
						     style="width: 0%;height:35px;line-height: 35px;">
							0% Complete
						</div>
					</div>
					<div>
						<span style="color: darkred">
							<?php esc_html_e( 'Please do not navigate away while importing. It may take up to 10 minutes to download resources.', 'insight-core' ) ?>
						</span>
					</div>
				</div>
			</div>
			<script type="text/javascript">

				var docTitle = document.title;
				var importing = true;
				var el = document.getElementById( 'insight-import-progressbar' );

				function progress_status( is ) {

					if ( is == 'dl' ) {
						el.innerHTML = 'Downloading...';
						el.className = el.className.replace( /\bprogress-bar-info\b/, 'progress-bar-success progress-bar-full' );
					} else {
						var perc = parseInt( is * 100 ) + '%';
						el.style.width = perc;

						if ( perc != '100%' ) {
							el.innerHTML = perc + ' Complete';
						} else {
							el.innerHTML = 'Initializing...';
							el.className = el.className.replace( /\bprogress-bar-info\b/, 'progress-bar-success' );
						}
					}
					document.title = el.innerHTML + '  - ' + docTitle;
				}

				function text_status( t ) {
					document.getElementById( 'import-status' ).innerHTML = t;
				}

				function is_error( msg ) {
					document.getElementById( 'error-import-msg' ).innerHTML += '<div class="notice notice-error">' + msg + '</div>';
					document.getElementById( 'error-import-msg' ).style.display = 'inline-block';
					text_status( '' );
					importing = false;
				}

				window.onbeforeunload = function( evt ) {
					if ( true == importing ) {
						if ( ! evt ) {
							evt = window.event;
						}

						evt.cancelBubble = true;
						evt.returnValue = '<?php esc_html_e( 'The importer is running. Please don\'t navigate away from this page.', 'insight-core' )?>';

						if ( evt.stopPropagation ) {
							evt.stopPropagation();
							evt.preventDefault();
						}
					}
				};

			</script>

		<?php include_once( INSIGHT_IMPORT_PATH . INSIGHT_CORE_DS . 'run.importer.php' ); ?>
			<script type="text/javascript">
				document.getElementById( 'import-working' ).style.display = 'none';
				document.title = '<?php echo esc_html__( 'Import has completed', 'insight-core' ) ?> ';
			</script>

			<div class="box" id="import-working">
				<div class="box-header">
					<span class="icon"><i class="fa fa-check"></i></span>
					<?php echo esc_html__( 'Import has completed', 'insight-core' ); ?>
				</div>
				<div class="box-body">
					<div class="success-message">
						<div class="content">
							<span id="total-time"></span>
							<p>
								<?php esc_html_e( 'Import is successful! Now customization is as easy as pie. Enjoy it!', 'insight-core' ) ?>
							</p>
							<?php if ( ! $regenerate_thumbnails ) : ?>
								<?php
								$plugin_link = sprintf(
									'<a href="%1$s" class="thickbox" title="%2$s">%3$s</a>',
									esc_url( INSIGHT_CORE_SITE_URI . '/wp-admin/plugin-install.php?tab=plugin-information&plugin=regenerate-thumbnails&TB_iframe=true&width=800&height=550' ),
									'Install Regenerate Thumbnails',
									'"Regenerate Thumbnails"'
								);

								printf(
									'The website is almost complete! There is only the last step. %1$s Please install plugin %2$s to regenerate all thumbnail sizes for images.',
									'</br>',
									$plugin_link
								);
								?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<script type="text/javascript">importing = false;</script>
		<?php

		} else {

		add_thickbox();

		?>

			<div class="box">
				<div class="box-header">
					<span class="icon"><i class="fa fa-lightbulb-o"></i></span>
					Import Notice
				</div>
				<div class="box-body">
					<?php esc_html_e( 'Our demo data import lets you have the whole data package in minutes, delivering all kinds of essential things quickly and simply. You may not have enough time for a coffee as the import is too fast!', 'insight-core' ) ?>
					<br/>
					<br/>
					<i>
						<?php esc_html_e( 'Notice: Before import, Make sure your website data is empty (posts, pages, menus...etc...)', 'insight-core' ); ?>
						</br>
						<?php esc_html_e( 'We suggest you use the plugin', 'insight-core' ); ?>
						<a href="<?php echo esc_url( INSIGHT_CORE_SITE_URI ); ?>/wp-admin/plugin-install.php?tab=plugin-information&plugin=wordpress-reset&TB_iframe=true&width=800&height=550"
						   class="thickbox" title="Install Wordpress Reset">"Wordpress Reset"</a>
						<?php esc_html_e( 'to reset your website before import.', 'insight-core' ); ?>
					</i>
				</div>
			</div>

			<?php if ( $delete_exist_posts ) : ?>
			<div class="box orange">
				<div class="box-header">
					<span class="icon"><i class="fa fa-trash"></i></span>
					Import Notice
				</div>
				<div class="box-body">
					<form action="" method="POST" class="form-delete-exist-posts" id="form-delete-exist-posts">
						<div class="importer-help-box">
							<p class="importer-help">To make Importer working perfectly. It needs to delete all
								exist posts (Posts/Pages added by plugins). We high recommended do this for new websites
								after install recommended plugins.</p>

							<?php wp_nonce_field( 'delete_exist_posts', 'nonce_delete_exist_posts' ); ?>
							<input type="hidden" name="action" value="insight_core_delete_exist_posts"/>
							<button type="submit" class="btn" id="btn-delete-exist-posts">Delete Posts</button>
						</div>
					</form>
				</div>
			</div>
		<?php endif; ?>

			<div class="import-form-wrap">
			<?php

			$count = count( $demos );

		foreach ( $demos as $demo_id => $demo ) {

			$option   = INSIGHT_CORE_THEME_SLUG . '_' . $demo_id . '_imported';
			$imported = get_option( $option );

			$classes = array( 'insight-demo-source' );

			if ( $imported ) {
				$classes[] = 'imported';
			}

			if ( $count > 1 ) {
				$classes[] = 'box-50';
			}

			?>
			<form class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
			      id="<?php echo esc_attr( $demo_id ); ?>" method="post" action=""
			      onsubmit="doSubmit(this);">
				<div class="box">
					<div class="box-header">
						<span class="icon"><i class="fa fa-paint-brush"></i></span>

						<?php echo esc_html( $demo['name'] ); ?>

						<?php if ( $count == 1 ) { ?>
							<span
								class="imported-count"><?php echo( $imported ? esc_html( ' ( imported ', 'insight-core' ) . sprintf( _n( '%s time', '%s times', $imported, 'insight-core' ), $imported ) . ' )' : '' ) ?></span>
						<?php } ?>

						<input type="submit" id="submitbtn-<?php echo esc_attr( $demo_id ) ?>"
						       class="insight-demo-source-install btn"
						       value="<?php echo esc_attr( 'Import this demo', 'insight-core' ); ?>"/>
					</div>
					<div class="box-body">
						<div class="insight-demo-source-screenshot">
							<img src="<?php echo esc_url( $demo['screenshot'] ); ?>"
							     alt="<?php echo esc_attr( $demo['name'] ); ?>">
							<?php if ( $count > 1 ) { ?>
								<span
									class="imported-count"><?php echo( $imported ? esc_html( ' ( imported ', 'insight-core' ) . sprintf( _n( '%s time', '%s times', $imported, 'insight-core' ), $imported ) . ' )' : '' ) ?></span>
							<?php } ?>
						</div>
						<?php if ( ! empty( $demo['description'] ) ) : ?>
							<div class="insight-demo-source-description">
								<?php echo $demo['description']; ?>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $demo['preview_url'] ) ) : ?>
							<a class="button btn-preview-import-demo" href="<?php echo esc_url( $demo['preview_url'] ); ?>"
							   target="_blank">View Demo</a>
						<?php endif; ?>
						<div>
							<input type="hidden" value="1" name="import_sample_data"/>
							<input type="hidden" value="<?php echo esc_attr( $demo_id ) ?>" name="demo"/>
						</div>
					</div>
				</div>
			</form>
		<?php } ?>
			</div>

			<?php if ( sizeof( $dummies ) > 0 ) { ?>
			<div class="box insight-dummy-container">
				<div class="box-header">
					<span class="icon"><i class="fa fa-download"></i></span>
					Import Dummy
				</div>
				<div class="box-body">
					<?php esc_html_e( 'You can import pages optionally. This way is suitable if you want to get new homepages after updating.', 'insight-core' ) ?>

					<form action="#" method="post" id="dummy-form">
						<div id="dummy-response"></div>

						<table>
							<tr>
								<td>
									<label
										for="dummy-select"><?php esc_html_e( 'Choose page to import', 'insight-core' ) ?></label>

									<select name="dummy" id="dummy-select">
										<option value=""><?php esc_html_e( '-- Select --' ); ?></option>
										<?php foreach ( $dummies as $dummy_id => $dummy ) {
											$option   = INSIGHT_CORE_THEME_SLUG . '_' . $dummy_id . '_imported';
											$imported = get_option( $option );
											?>
											<option value="<?php echo esc_attr( $dummy_id ); ?>"
											        data-screenshot="<?php echo esc_attr( $dummy['screenshot'] ); ?>"
												<?php echo $imported ? ( 'data-imported-count="' . $imported . '"' ) : ''; ?>><?php esc_html_e( $dummy['name'] ); ?></option>
										<?php } ?>
									</select>
								</td>
								<td>
									<div class="page-preview">
										<?php $r_dummies = reset( $dummies ); ?>

										<img src="<?php echo esc_attr( $r_dummies['screenshot'] ); ?>" alt=""/>
									</div>

									<input type="submit" name="dummy-submit" id="dummy-submit"
									       class="button button-primary" disabled="disabled"
									       value="<?php echo esc_attr( 'Import', 'insight-core' ); ?>">

									<div class="progress">
										<div class="progress-bar progress-bar-success progress-bar-striped active"
										     role="progressbar"
										     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
										     id="insight-dummy-progressbar"
										     style="width: 0%;">
										</div>
									</div>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		<?php } ?>
			<?php
		}
		?>
	</div>

	<?php if ( isset( $style ) && ! emtpy( $style['link_color'] ) ): ?>
		<style type="text/css">
			#insight-import-page .import-notice a, #insight-import-page .footer a:not(.button) {
				color: <?php echo esc_attr($style['link_color']); ?>
			}
		</style>
	<?php endif; ?>

	<script type="text/javascript">
		function doSubmit( form ) {
			var id = form.id;
			var btn = document.getElementById( 'submitbtn-' + id );

			btn.className += ' disable';
			btn.disable = true;
			btn.value = 'Importing...';
		}

		function showSystemRequirements() {
			var sys = document.getElementById( 'system-requirements' );

			if ( sys.style.display == 'inline-block' ) {
				sys.style.display = 'none';
			} else {
				sys.style.display = 'inline-block';
			}
		}
		<?php
		if (isset( $time_elapsed_secs )) { ?>
		document.getElementById( 'total-time' ).innerHTML = '<?php echo sprintf( esc_html__( 'Total time: %s', 'insight-core' ), $time_elapsed_secs ); ?>';
		<?php } ?>
	</script>
	<?php
	include_once( INSIGHT_CORE_INC_DIR . '/pages-footer.php' );
	?>
</div>
