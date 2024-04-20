<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

add_thickbox();

if ( isset( $_GET['ic_nonce'] ) && wp_verify_nonce( $_GET['ic_nonce'], 'deactivation' ) ) {
	delete_option( 'insight_core_purchase_code' );
	delete_option( 'insight_core_purchase_info' );
}

if ( isset( $_POST['purchase-code'] ) ) {
	$purchased_code = sanitize_key( $_POST['purchase-code'] );
	$purchase_info  = InsightCore::check_purchase_code( $purchased_code );

	if ( ! empty( $purchase_info ) && ! empty( $purchase_info['verify'] ) ) {
		update_option( 'insight_core_purchase_code', $purchased_code );
		update_option( 'insight_core_purchase_info', $purchase_info );
	}
}

if ( isset( $_GET['tgmpa-deactivate'] ) && 'deactivate-plugin' == $_GET['tgmpa-deactivate'] ) {
	$plugins = TGM_Plugin_Activation::$instance->plugins;
	check_admin_referer( 'tgmpa-deactivate', 'tgmpa-deactivate-nonce' );

	$plugin_to_deactivate = isset( $_GET['plugin'] ) ? sanitize_text_field( $_GET['plugin'] ) : '';

	foreach ( $plugins as $plugin ) {
		if ( $plugin['slug'] == $plugin_to_deactivate ) {
			deactivate_plugins( $plugin['file_path'] );
		}
	}
}

if ( isset( $_GET['tgmpa-activate'] ) && 'activate-plugin' == $_GET['tgmpa-activate'] ) {
	check_admin_referer( 'tgmpa-activate', 'tgmpa-activate-nonce' );
	$plugins = TGM_Plugin_Activation::$instance->plugins;

	$plugin_to_activate = isset( $_GET['plugin'] ) ? sanitize_text_field( $_GET['plugin'] ) : '';

	foreach ( $plugins as $plugin ) {
		if ( $plugin['slug'] == $plugin_to_activate ) {
			activate_plugin( $plugin['file_path'] );
		}
	}
}

$tgm_plugins          = array();
$tgm_plugins_action   = array();
$tgm_plugins_required = 0;
$plugins              = TGM_Plugin_Activation::$instance->plugins;
$tgm_plugins          = apply_filters( 'insight_core_tgm_plugins', $tgm_plugins );

foreach ( $plugins as $plugin ) {
	$tgm_plugins_action[ $plugin['slug'] ] = InsightCore::plugin_action( $plugin );
}
?>
<div class="wrap insight-core-wrap">
	<?php
	$insight_core_info = InsightCore::$info;
	include_once( INSIGHT_CORE_INC_DIR . '/pages-header.php' );
	if ( InsightCore::is_theme_support() ) {
		?>
		<div class="insight-core-body">
			<div class="box green box-purchase">
				<div class="box-header">Purchase Code</div>

				<?php
				$purchase_info = get_option( 'insight_core_purchase_info' );
				?>

				<?php if ( ! empty( $purchase_info ) && ! empty( $purchase_info['verify'] ) ) { ?>
					<div class="box-body has-purchased">
						<table class="wp-list-table widefat striped table-purchased-info">
							<?php if ( ! empty( $purchase_info['item_name'] ) ) : ?>
								<tr>
									<th>Item name</th>
									<td><?php echo esc_html( $purchase_info['item_name'] ); ?></td>
								</tr>
							<?php endif; ?>

							<?php if ( ! empty( $purchase_info['created_at'] ) ) : ?>
								<tr>
									<th>Create at</th>
									<td><?php echo esc_html( $purchase_info['created_at'] ); ?></td>
								</tr>
							<?php endif; ?>

							<?php if ( ! empty( $purchase_info['buyer'] ) ) : ?>
								<tr>
									<th>Buyer</th>
									<td><?php echo esc_html( $purchase_info['buyer'] ); ?></td>
								</tr>
							<?php endif; ?>

							<?php if ( ! empty( $purchase_info['licence'] ) ) : ?>
								<tr>
									<th>Licence</th>
									<td><?php echo esc_html( $purchase_info['licence'] ); ?></td>
								</tr>
							<?php endif; ?>

							<?php if ( ! empty( $purchase_info['supported_until'] ) ) : ?>
								<tr>
									<th>Support until</th>
									<td><?php echo esc_html( $purchase_info['supported_until'] ); ?></td>
								</tr>
							<?php endif; ?>

							<tr>
								<th>
									<a href="<?php print wp_nonce_url( admin_url( 'admin.php?page=insight-core' ), 'deactivation', 'ic_nonce' ); ?>"
									   onclick="return confirm('Are you sure?');">Deactivation</a>
								</th>
								<td></td>
							</tr>
						</table>
					</div>
				<?php } else { ?>
					<div class="box-body">
						<form action="" method="post">
							<span class="purchase-icon"><i class="pe-7s-unlock"></i></span>
							<input name="purchase-code" type="text" placeholder="Purchase code" required/>
							<input type="submit" class="button action" value="Submit"/>
						</form>
						<div class="purchase-desc">
							Show us your ThemeForest purchase code to get the automatic update.
						</div>
					</div>
				<?php } ?>
			</div>

			<div class="box orange box-update">
				<div class="box-header">Update</div>
				<div class="box-body">
					<div class="update-info">
						<div class="update-icon">
							<i class="pe-7s-cloud-download"></i>
						</div>
						<div class="update-text">
							<span>Installed Version</span>
							<?php echo INSIGHT_CORE_THEME_VERSION; ?>
						</div>
					</div>
					<?php
					$purchased_code           = InsightCore::instance()->get_purchased_code();
					$latest_version           = InsightCore::instance()->get_latest_theme_version();
					$latest_version_available = empty( $latest_version ) ? INSIGHT_CORE_THEME_VERSION : $latest_version;
					?>
					<div class="update-info">
						<div class="update-icon">
							<i class="pe-7s-bell"></i>
						</div>
						<div class="update-text">
							<span>Latest Available Version</span>
							<?php echo esc_html( $latest_version_available ); ?>
						</div>
					</div>
					<div class="update-text">
						<?php if ( version_compare( $latest_version_available, INSIGHT_CORE_THEME_VERSION, '>' ) ): ?>
							<p>The latest version of this theme<br/>
								is available, update today!</p>

							<?php if ( empty( $purchased_code ) ): ?>
								<p><strong>Please enter your purchase code<br/>
										to update the theme.</strong></p>
							<?php endif; ?>
						<?php else: ?>
							Your theme is up to date!
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $purchased_code ) ) : ?>
						<div class="update-action">
							<form action="" method="post" class="ic-re-check-update-form">
								<?php wp_nonce_field( 'check_update', 'insight_core_nonce' ); ?>
								<input type="hidden" name="insight_core_action" value="check_update"/>

								<?php if ( version_compare( $latest_version_available, INSIGHT_CORE_THEME_VERSION, '>' ) && current_user_can( 'update_themes' ) ): ?>
									<?php
									printf( '<a href="%1$s" class="btn">Update Now</a>',
										wp_nonce_url( self_admin_url( 'update.php?action=upgrade-theme&theme=' ) . INSIGHT_CORE_THEME_SLUG, 'upgrade-theme_' . INSIGHT_CORE_THEME_SLUG )
									);
									?>
								<?php endif; ?>

								<button type="submit" class="button action btn"
								        value="Check Update"><?php esc_html_e( 'Check again', 'insight-core' ); ?></button>
								<?php
								$last_check_timestamp = get_option( 'insight_core_last_check_update_time' );
								$date_format          = get_option( 'date_format' );
								$time_format          = get_option( 'time_format' );
								?>
								<?php if ( ! empty( $last_check_timestamp ) ) : ?>
									<?php
									$last_check_time = date_i18n( $date_format, $last_check_timestamp ) . ' at ' . date_i18n( $time_format, $last_check_timestamp );
									?>
									<p class="last-check-update-time"><?php echo 'Last checked on ' . $last_check_time ?></p>
								<?php endif; ?>
							</form>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="box blue box-support">
				<div class="box-support-img">&nbsp;</div>
				<div class="box-header">
					Support
				</div>
				<div class="box-body">
					<table>
						<tr>
							<td>
								<i class="pe-7s-note2"></i>
							</td>
							<td>
								<a href="<?php echo esc_url( $insight_core_info['docs'] ); ?>"
								   target="_blank"><span>Online Documentation</span></a>
								Detailed instruction to get<br/>
								the right way with our theme
							</td>
						</tr>
						<tr>
							<td>
								<i class="pe-7s-comment"></i>
							</td>
							<td>
								<a href="<?php echo esc_url( $insight_core_info['faqs'] ); ?>"
								   target="_blank"><span>FAQs</span></a>
								Check it before you ask for support.
							</td>
						</tr>
						<tr>
							<td>
								<i class="pe-7s-users"></i>
							</td>
							<td>
								<a href="<?php echo esc_url( $insight_core_info['support'] ); ?>"
								   target="_blank"><span>Human support</span></a>
								Our WordPress gurus'd love to help you to shot issues one by one.
							</td>
						</tr>
					</table>
					<div class="support-action">
						<a href="<?php echo esc_url( $insight_core_info['support'] ); ?>" target="_blank"
						   class="btn">Support Centre</a> <a
							href="<?php echo esc_url( $insight_core_info['faqs'] ); ?>" target="_blank"
							class="btn">FAQs</a>
					</div>
				</div>
			</div>
			<div class="box box-step red2">
				<div class="box-header">
					<span class="num">1</span>
					Install Required Plugins
				</div>
				<?php if ( count( $tgm_plugins ) > 0 ) { ?>
					<div class="box-body">
						<table class="wp-list-table widefat striped plugins">
							<thead>
							<tr>
								<th>Plugin</th>
								<th>Version</th>
								<th>Type</th>
								<th>Action</th>
							</tr>
							</thead>
							<tbody>
							<?php
							foreach ( $tgm_plugins as $tgm_plugin ) {
								?>
								<tr>
									<td>
										<?php
										if ( isset( $tgm_plugin['required'] ) && ( $tgm_plugin['required'] == true ) ) {
											if ( ! TGM_Plugin_Activation::$instance->is_plugin_active( $tgm_plugin['slug'] ) ) {
												echo '<span>' . esc_html( $tgm_plugin['name'] ) . '</span>';
												$tgm_plugins_required++;
											} else {
												echo '<span class="actived">' . esc_html( $tgm_plugin['name'] ) . '</span>';
											}
										} else {
											//echo TGM_Plugin_Activation::$instance->get_info_link( $tgm_plugin['slug'] );
											echo esc_html( $tgm_plugin['name'] );
										}
										?>
									</td>
									<td><?php echo( isset( $tgm_plugin['version'] ) ? esc_html( $tgm_plugin['version'] ) : '' ); ?></td>
									<td><?php echo( isset( $tgm_plugin['required'] ) && ( $tgm_plugin['required'] == true ) ? esc_html__( 'Required', 'insight-core' ) : esc_html__( 'Recommended', 'insight-core' ) ); ?></td>
									<td>
										<?php echo wp_kses( $tgm_plugins_action[ $tgm_plugin['slug'] ], [
											'a' => [
												'href'  => array(),
												'title' => array(),
												'class' => array(),
												'id' => array(),
											],
										] ); ?>
									</td>
								</tr>
								<?php
							}
							?>
							</tbody>
						</table>
					</div>
					<div class="box-footer">
						<?php echo( $tgm_plugins_required > 0 ? '<span style="color: #dc433f">Please install and active all required plugins (' . $tgm_plugins_required . ')</span>' : '<span style="color: #6fbcae">All required plugins are activated. Now you can import the demo data.</span>' ); ?>
					</div>
				<?php } else { ?>
					<div class="box-body">
						This theme doesn't require any plugins.
					</div>
				<?php } ?>
			</div>
			<div class="box box-step blue2">
				<div class="box-header">
					<span class="num">2</span>
					Import Demos
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
				<div class="box-footer">
					<?php
					if ( get_option( 'insight_core_import' ) != false ) {
						echo 'You\'ve imported demo data ' . sprintf( _n( '%s time', '%s times', get_option( 'insight_core_import' ), 'insight-core' ), get_option( 'insight_core_import' ) ) . '.';
					}
					if ( $tgm_plugins_required > 0 ) {
						echo '<a class="btn" href="javascript:alert(\'Please install and active all required plugins first!\');"><i class="fa fa-download" aria-hidden="true"></i>&nbsp; Start Import Demos</a>';
					} else {
						echo '<a class="btn" href="' . admin_url( "admin.php?page=insight-core-import" ) . '"><i class="fa fa-download" aria-hidden="true"></i>&nbsp; Start Import Demos</a>';
					}
					?>
				</div>
			</div>
			<div class="box box-changelogs">
				<div class="box-header">
					<span class="icon"><i class="pe-7s-note2"></i></span>
					<?php esc_html_e( 'Changelog', 'insight-core' ); ?>
				</div>
				<div class="box-body">
					<div id="changelogs-content">
						<a href="https://changelog.thememove.com" target="_blank"
						   class="btn"><?php esc_html_e( 'View Changelogs', 'insight-core' ); ?></a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	include_once( INSIGHT_CORE_INC_DIR . '/pages-footer.php' );
	?>
</div>
