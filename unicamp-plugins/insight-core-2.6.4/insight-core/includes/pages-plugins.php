<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
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
$tgm_plugins          = apply_filters( 'insight_core_compatible_plugins', $tgm_plugins );

$required_plugins     = apply_filters( 'insight_core_tgm_plugins', [] );

$all_plugins = array_merge( $required_plugins, $tgm_plugins );

foreach ( $plugins as $plugin ) {
	$tgm_plugins_action[ $plugin['slug'] ] = InsightCore::plugin_action( $plugin );
}
?>

<div class="wrap insight-core-wrap">
	<?php
	$insight_core_info = InsightCore::$info;
	include_once( INSIGHT_CORE_INC_DIR . '/pages-header.php' );
	add_thickbox();
	if ( InsightCore::is_theme_support() ) {
		?>
		<div class="insight-core-body">
			<?php if ( count( $all_plugins ) > 0 ) { ?>
				<div class="box-body">
					<div class="box-content">
						<div class="box-content__wrap">
							<?php foreach ( $all_plugins as $tgm_plugin ) : ?>
								<?php
								$plugin_type_classes = 'is-plugin-card__type';

								if ( isset( $tgm_plugin['required'] ) && ( $tgm_plugin['required'] == true ) ) {
									$plugin_type_classes .= ' required';
								} elseif ( isset( $tgm_plugin['compatible'] ) && ( $tgm_plugin['compatible'] == true ) ) {
									$plugin_type_classes .= ' compatible';
								}

								$logo_url = insight_core_get_plugin_thumbnail( $tgm_plugin['slug'] );
								?>
								<div class="is-plugin-card">
									<div class="is-plugin-card__info">

										<?php if ( $logo_url ) : ?>
											<div class="is-plugin-card__logo">
												<img src="<?php echo esc_url( $logo_url ); ?>" alt="">
											</div>
										<?php endif; ?>

										<h4 class="is-plugin-card__name"><?php echo esc_html( $tgm_plugin['name'] ); ?></h4>

										<?php if ( ! empty( $tgm_plugin['description'] ) ) : ?>
											<div class="is-plugin-card__description">
												<?php echo wp_kses( $tgm_plugin['description'], array(
													'a'      => array(
														'href'  => array(),
														'title' => array(),
													),
													'br'     => array(),
													'em'     => array(),
													'strong' => array(),
												) ); ?>
											</div>
										<?php endif; ?>

										<span class="<?php echo esc_attr( $plugin_type_classes ) ?>">
											<?php
											if ( isset( $tgm_plugin['required'] ) && ( $tgm_plugin['required'] == true ) ) {
												$text = esc_html__( 'Required', 'insight-core' );
											} elseif ( isset( $tgm_plugin['compatible'] ) && ( $tgm_plugin['compatible'] == true ) ) {
												$text = esc_html__( 'Compatible', 'insight-core' );
											} else {
												$text = esc_html__( 'Recommended', 'insight-core' );
											}
											echo $text;
											?>
										</span>
									</div>
									<div class="is-plugin-card__footer">
										<div class="is-plugin-card__action">
											<?php if ( isset( $tgm_plugins_action[ $tgm_plugin['slug'] ] ) ): ?>
												<?php echo wp_kses( $tgm_plugins_action[ $tgm_plugin['slug'] ], [
													'a' => [
														'href'  => array(),
														'title' => array(),
														'class' => array(),
														'id'    => array(),
													],
												] ); ?>
											<?php endif; ?>
										</div>
										<div
											class="is-plugin-card__version"><?php echo( isset( $tgm_plugin['version'] ) ? esc_html( $tgm_plugin['version'] ) : '' ); ?></div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>

			<?php } else { ?>
				<div class="box-body">
					This theme doesn't require any plugins.
				</div>
			<?php } ?>
		</div>
		<?php
	}
	include_once( INSIGHT_CORE_INC_DIR . '/pages-footer.php' );
	?>
</div>
