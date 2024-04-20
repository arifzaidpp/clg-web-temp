<div class="wrap insight-core-wrap">
	<?php
	$insight_core_info = InsightCore::$info;
	InsightCore::update_option_count( 'insight_core_view_export' );
	include_once( INSIGHT_CORE_INC_DIR . '/pages-header.php' );
	?>
	<div class="insight-core-body">
		<div class="box">
			<div class="box-header">
				<span class="icon"><i class="fa fa-download"></i></span>
				Export
			</div>
			<div class="box-body">
				<table class="table">
					<tbody>
					<tr valign="middle">
						<td>
							Content
						</td>
						<td>
							<form method="post" action="">
								<input type="hidden" name="export_option" value="content"/>
								<input type="submit" value="<?php echo esc_html__( 'Export', 'insight-core' ); ?>"
								       name="export"
								       class="btn"/>
							</form>
						</td>
					</tr>
					<tr valign="middle">
						<td>
							Sidebars
						</td>
						<td>
							<form method="post" action="">
								<input type="hidden" name="export_option" value="sidebars"/>
								<input type="submit" value="<?php echo esc_html__( 'Export', 'insight-core' ); ?>"
								       name="export"
								       class="btn"/>
							</form>
						</td>
					</tr>
					<tr valign="middle">
						<td>
							Widgets
						</td>
						<td>
							<form method="post" action="">
								<input type="hidden" name="export_option" value="widgets"/>
								<input type="submit" value="<?php echo esc_html__( 'Export', 'insight-core' ); ?>"
								       name="export"
								       class="btn"/>
							</form>
						</td>
					</tr>
					<tr valign="middle">
						<td>
							Menus
						</td>
						<td>
							<form method="post" action="">
								<input type="hidden" name="export_option" value="menus"/>
								<input type="submit" value="<?php echo esc_html__( 'Export', 'insight-core' ); ?>"
								       name="export"
								       class="btn"/>
							</form>
						</td>
					</tr>
					<tr valign="middle">
						<td>
							Page Options
						</td>
						<td>
							<form method="post" action="">
								<input type="hidden" name="export_option" value="page_options"/>
								<input type="submit" value="<?php echo esc_html__( 'Export', 'insight-core' ); ?>"
								       name="export" class="btn"/>
							</form>
						</td>
					</tr>
					<tr valign="middle">
						<td>
							Customizer Options
						</td>
						<td>
							<form method="post" action="">
								<input type="hidden" name="export_option" value="customizer_options"/>
								<input type="submit" value="<?php echo esc_html__( 'Export', 'insight-core' ); ?>"
								       name="export" class="btn"/>
							</form>
						</td>
					</tr>
					<?php if ( class_exists( 'WooCommerce' ) ) { ?>
						<tr valign="middle">
							<td>
								WooCommerce Images Sizes
							</td>
							<td>
								<form method="post" action="">
									<input type="hidden" name="export_option" value="woocommerce_image_sizes"/>
									<input type="submit" value="<?php echo esc_html__( 'Export', 'insight-core' ); ?>"
									       name="export"
									       class="btn"/>
								</form>
							</td>
						</tr>
						<tr valign="middle">
							<td>
								WooCommerce Settings
							</td>
							<td>
								<form method="post" action="">
									<input type="hidden" name="export_option" value="woocommerce_settings"/>
									<input type="submit" value="<?php echo esc_html__( 'Export', 'insight-core' ); ?>"
									       name="export"
									       class="btn"/>
								</form>
							</td>
						</tr>
						<tr valign="middle">
							<td>
								WooCommerce Attributes
							</td>
							<td>
								<form method="post" action="">
									<input type="hidden" name="export_option" value="woocommerce_attributes"/>
									<input type="submit" value="<?php echo esc_html__( 'Export', 'insight-core' ); ?>"
									       name="export"
									       class="btn"/>
								</form>
							</td>
						</tr>
					<?php } ?>
					<?php if ( defined( 'ELEMENTOR_VERSION' ) ) { ?>
						<tr valign="middle">
							<td>
								Elementor
							</td>
							<td>
								<form method="post" action="">
									<input type="hidden" name="export_option" value="elementor"/>
									<input type="submit" value="<?php echo esc_html__( 'Export', 'insight-core' ); ?>"
									       name="export"
									       class="btn"/>
								</form>
							</td>
						</tr>
					<?php } ?>
					<?php if ( class_exists( 'LearnPress' ) ) { ?>
						<tr valign="middle">
							<td>
								LearnPress Settings
							</td>
							<td>
								<form method="post" action="">
									<input type="hidden" name="export_option" value="learnpress_settings"/>
									<input type="submit" value="<?php echo esc_html__( 'Export', 'insight-core' ); ?>"
									       name="export"
									       class="btn"/>
								</form>
							</td>
						</tr>
						<tr valign="middle">
							<td>
								LearnPress Data
							</td>
							<td>
								<form method="post" action="">
									<input type="hidden" name="export_option" value="learnpress_data"/>
									<input type="submit" value="<?php echo esc_html__( 'Export', 'insight-core' ); ?>"
									       name="export"
									       class="btn"/>
								</form>
							</td>
						</tr>
					<?php } ?>
					<?php if ( class_exists( 'ElfsightInstagramFeedPlugin' ) ) { ?>
						<tr valign="middle">
							<td>
								Elfsight Instagram Feed Plugin
							</td>
							<td>
								<form method="post" action="">
									<input type="hidden" name="export_option" value="elfsight_instagram"/>
									<input type="submit" value="<?php echo esc_html__( 'Export', 'insight-core' ); ?>"
									       name="export"
									       class="btn"/>
								</form>
							</td>
						</tr>
					<?php } ?>
					<?php if ( function_exists( 'tutor' ) ) { ?>
						<tr valign="middle">
							<td>
								Tutor LMS Settings
							</td>
							<td>
								<form method="post" action="">
									<input type="hidden" name="export_option" value="tutor_settings"/>
									<input type="submit" value="<?php echo esc_html__( 'Export', 'insight-core' ); ?>"
									       name="export"
									       class="btn"/>
								</form>
							</td>
						</tr>
						<tr valign="middle">
							<td>
								Tutor LMS Data
							</td>
							<td>
								<form method="post" action="">
									<input type="hidden" name="export_option" value="tutor_data"/>
									<input type="submit" value="<?php echo esc_html__( 'Export', 'insight-core' ); ?>"
									       name="export"
									       class="btn"/>
								</form>
							</td>
						</tr>
					<?php } ?>
					<tr valign="middle">
						<td>
							Revolution Slider
						</td>
						<td>
							<form method="post" action="">
								<input type="hidden" name="export_option" value="rev_sliders"/>
								<input type="submit" value="<?php echo esc_html__( 'Export', 'insight-core' ); ?>"
								       name="export" class="btn"/>
							</form>
						</td>
					</tr>
					<tr valign="middle">
						<td>
							Media Package
						</td>
						<td>
							<form method="post" action="">
								<input type="hidden" name="export_option" value="media_package"/>
								<input type="text" name="demo" value="insightcore01"/>
								<input type="submit" value="<?php echo esc_html__( 'Export', 'insight-core' ); ?>"
								       name="export" class="btn"/>
							</form>
						</td>
					</tr>
					<tr valign="middle">
						<td>
							Media Package (placeholder)
						</td>
						<td>
							<form method="post" action="">
								<input type="hidden" name="export_option" value="media_package_placeholder"/>
								<input type="text" name="demo_placeholder" value="insightcore01"/>
								<input type="submit" value="<?php echo esc_html__( 'Export', 'insight-core' ); ?>"
								       name="export" class="btn"/>
							</form>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php
	include_once( INSIGHT_CORE_INC_DIR . '/pages-footer.php' );
	?>
</div>
