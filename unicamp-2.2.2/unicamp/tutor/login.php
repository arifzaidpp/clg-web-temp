<?php
/**
 * Display single login
 *
 * @since   v.1.0.0
 * @author  themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

	<div class="page-content">
		<div class="container">
			<div class="row">
				<div class="page-main-content">
					<?php do_action( 'tutor/template/login/before/wrap' ); ?>

					<h2><?php esc_html_e( 'Please Sign-In to view this section', 'unicamp' ); ?></h2>

					<!--<div class="tutor-template-segment tutor-login-wrap user-form-wrap">
						<h4 class="form-title login-title"><?php /*esc_html_e( 'Login', 'unicamp' ); */?></h4>

						<div class="tutor-template-login-form">
							<?php /*tutor_load_template( 'global.login' ); */?>
						</div>
					</div>-->

					<?php do_action( 'tutor/template/login/after/wrap' ); ?>
				</div>
			</div>
		</div>
	</div>
<?php
get_footer();
