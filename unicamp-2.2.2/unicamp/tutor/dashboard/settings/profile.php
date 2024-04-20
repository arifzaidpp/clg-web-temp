<?php
/**
 * @package       TutorLMS/Templates
 * @version       1.7.5
 *
 * @theme-since   1.0.0
 * @theme-version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

$user = wp_get_current_user();

$profile_placeholder = Unicamp_Helper::placeholder_avatar_src();
$profile_photo_src   = $profile_placeholder;
$profile_photo_id    = get_user_meta( $user->ID, '_tutor_profile_photo', true );
if ( $profile_photo_id ) {
	$url = wp_get_attachment_image_url( $profile_photo_id, 'full' );
	! empty( $url ) ? $profile_photo_src = $url : 0;
}

$cover_placeholder = tutor()->url . 'assets/images/cover-photo.jpg';
$cover_photo_src   = $cover_placeholder;
$cover_photo_id    = get_user_meta( $user->ID, '_tutor_cover_photo', true );
if ( $cover_photo_id ) {
	$url = wp_get_attachment_image_url( $cover_photo_id, 'full' );
	! empty( $url ) ? $cover_photo_src = $url : 0;
}

$public_display                     = array();
$public_display['display_nickname'] = $user->nickname;
$public_display['display_username'] = $user->user_login;

if ( ! empty( $user->first_name ) ) {
	$public_display['display_firstname'] = $user->first_name;
}

if ( ! empty( $user->last_name ) ) {
	$public_display['display_lastname'] = $user->last_name;
}

if ( ! empty( $user->first_name ) && ! empty( $user->last_name ) ) {
	$public_display['display_firstlast'] = $user->first_name . ' ' . $user->last_name;
	$public_display['display_lastfirst'] = $user->last_name . ' ' . $user->first_name;
}

if ( ! in_array( $user->display_name, $public_display ) ) { // Only add this if it isn't duplicated elsewhere
	$public_display = array( 'display_displayname' => $user->display_name ) + $public_display;
}

$public_display = array_map( 'trim', $public_display );
$public_display = array_unique( $public_display );
$max_filesize   = floatval( ini_get( 'upload_max_filesize' ) ) * ( 1024 * 1024 );
?>
<div class="tutor-dashboard-content-inner">

	<?php do_action( 'tutor_profile_edit_form_before' ); ?>

	<form action="" method="post" enctype="multipart/form-data"
	      class="dashboard-settings-form dashboard-settings-profile-form">
		<?php wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); ?>
		<input type="hidden" value="tutor_profile_edit" name="tutor_action"/>

		<?php
		$errors = apply_filters( 'tutor_profile_edit_validation_errors', array() );
		if ( is_array( $errors ) && count( $errors ) ) {
			echo '<div class="tutor-alert-warning tutor-mb-10"><ul class="tutor-required-fields">';
			foreach ( $errors as $error_key => $error_value ) {
				echo "<li>{$error_value}</li>";
			}
			echo '</ul></div>';
		}
		?>

		<?php do_action( 'tutor_profile_edit_input_before' ); ?>

		<div class="row">
			<div class="col-md-12 col-lg-6">
				<div class="dashboard-content-box">
					<h4 class="dashboard-content-box-title"><?php esc_html_e( 'Contact information', 'unicamp' ); ?></h4>
					<p><?php esc_html_e( 'Provide your details below to create your account profile', 'unicamp' ) ?></p>

					<div class="row">
						<div class="col-md-6">
							<div class="tutor-form-group">
								<label for="tutor_profile_first_name">
									<?php esc_html_e( 'First Name', 'unicamp' ); ?>
								</label>
								<input type="text" id="tutor_profile_first_name" name="first_name"
								       value="<?php echo esc_attr( $user->first_name ); ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="tutor-form-group">
								<label for="tutor_profile_last_name">
									<?php esc_html_e( 'Last Name', 'unicamp' ); ?>
								</label>
								<input type="text" id="tutor_profile_last_name" name="last_name"
								       value="<?php echo esc_attr( $user->last_name ); ?>">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="tutor-form-group">
								<label for="tutor_profile_job_title">
									<?php esc_html_e( 'Job Title', 'unicamp' ); ?>
								</label>
								<input type="text" id="tutor_profile_job_title" name="tutor_profile_job_title"
								       value="<?php echo esc_attr( get_user_meta( $user->ID, '_tutor_profile_job_title', true ) ); ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="tutor-form-group">
								<label for="tutor_profile_phone_number">
									<?php esc_html_e( 'Phone Number', 'unicamp' ); ?>
								</label>
								<input type="text" id="tutor_profile_phone_number" name="phone_number"
								       value="<?php echo esc_attr( get_user_meta( $user->ID, 'phone_number', true ) ); ?>">
							</div>
						</div>
					</div>

					<div class="tutor-form-group">
						<label for="tutor_profile_bio">
							<?php esc_html_e( 'Bio', 'unicamp' ); ?>
						</label>
						<?php
						/**
						 * Allows instructors add HTML tags.
						 */
						$bio = get_user_meta( $user->ID, '_tutor_profile_bio', true );
						?>
						<?php if ( current_user_can( tutor()->instructor_role ) ) : ?>
							<?php
							$editor_settings = array(
								'teeny'         => true,
								'media_buttons' => false,
								'quicktags'     => false,
								'editor_height' => 100,
								'textarea_name' => 'tutor_profile_bio',
							);
							wp_editor( wp_kses_post( $bio ), 'tutor_profile_bio', $editor_settings );
							?>
						<?php else : ?>
							<textarea name="tutor_profile_bio"
							          id="tutor_profile_bio"><?php echo strip_tags( $bio ); ?></textarea>
						<?php endif; ?>
					</div>

					<?php do_action( 'tutor_profile_edit_before_social_media', $user ); ?>

					<?php
					$tutor_user_social_icons = tutor_utils()->tutor_user_social_icons();
					$social_loop_count       = 0;
					$social_count            = count( $tutor_user_social_icons );

					foreach ( $tutor_user_social_icons as $key => $social_icon ) :
						?>
						<?php if ( $social_loop_count % 2 === 0 ): ?>
						<div class="row">
					<?php endif; ?>
						<div class="col-md-6">
							<div class="tutor-form-group">
								<label
									for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $social_icon['label'] ); ?></label>
								<input type="text" id="<?php echo esc_attr( $key ); ?>"
								       name="<?php echo esc_attr( $key ); ?>"
								       value="<?php echo esc_attr( get_user_meta( $user->ID, $key, true ) ); ?>">
							</div>
						</div>
						<?php $social_loop_count++; ?>
						<?php if ( $social_loop_count % 2 === 0 || $social_loop_count === $social_count ): ?>
						</div>
					<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="col-md-12 col-lg-6">
				<div class="dashboard-content-box">
					<div class="row">
						<div class="col-md-12">
							<h4 class="dashboard-content-box-title"><?php esc_html_e( 'Photo', 'unicamp' ); ?></h4>
							<p><?php esc_html_e( 'Upload your profile photo.', 'unicamp' ); ?></p>
							<div id="tutor_profile_cover_photo_editor">
								<input id="tutor_photo_dialogue_box" type="file" accept=".png,.jpg,.jpeg"/>
								<div id="tutor_cover_area" data-fallback="<?php echo $cover_placeholder; ?>"
								     style="background-image:url(<?php echo $cover_photo_src; ?>)">
						            <span class="tutor_cover_deleter">
						                <i class="far fa-trash-alt"></i>
						            </span>
									<div class="tutor_overlay">
										<button class="tutor_cover_uploader" type="button">
											<i class="far fa-camera"></i>&nbsp;
											<span>
                                                <?php echo $profile_photo_id ? esc_html__( 'Update Cover Photo', 'unicamp' ) : esc_html__( 'Upload Cover Photo', 'unicamp' ); ?>
                                            </span>
										</button>
									</div>
								</div>
								<div id="tutor_photo_meta_area">
									<img src="<?php echo tutor()->url . '/assets/images/'; ?>info-icon.svg"/>
									<span><?php echo sprintf( esc_html__( 'Profile Photo Size: %1$s pixels,', 'unicamp' ), '<strong>200x200</strong>' ); ?></span>
									<span><?php echo sprintf( esc_html__( 'Cover Photo Size: %1$s pixels,', 'unicamp' ), '<strong>700x430</strong>' ); ?></span>
									<span class="loader-area"><?php esc_html_e( 'Saving&hellip;', 'unicamp' ); ?></span>
								</div>
								<div id="tutor_profile_area" data-fallback="<?php echo $profile_placeholder; ?>"
								     style="background-image:url(<?php echo $profile_photo_src; ?>)">
									<div class="tutor_overlay">
										<i class="far fa-camera"></i>
									</div>
								</div>
								<div id="tutor_pp_option">
									<div class="up-arrow">
										<i></i>
									</div>
									<span class="tutor_pp_uploader">
						                <i class="far fa-upload"></i> <?php esc_html_e( 'Upload Photo', 'unicamp' ); ?>
						            </span>
									<span class="tutor_pp_deleter">
						                <i class="far fa-trash-alt"></i> <?php esc_html_e( 'Delete', 'unicamp' ); ?>
						            </span>
								</div>
							</div>
						</div>

						<div class="col-md-12">
							<div class="tutor-form-group">
								<div class="tutor-form-group">
									<label
										for="display_name"><?php esc_html_e( 'Display name publicly as', 'unicamp' ); ?></label>
									<select name="display_name" id="display_name">
										<?php foreach ( $public_display as $id => $item ) : ?>
											<option <?php selected( $user->display_name, $item ); ?>><?php echo esc_html( $item ); ?></option>
										<?php endforeach; ?>
									</select>
									<p class="form-description">
										<?php esc_html_e( 'The display name is shown in all public fields, such as the author name, instructor name, student name, and name that will be printed on the certificate.', 'unicamp' ); ?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="tutor-form-group tutor-profile-form-btn-wrap form-submit-wrap">
			<button type="submit" name="tutor_register_student_btn" value="register"
			        class="tutor-button tutor-profile-settings-save"><?php esc_html_e( 'Update Profile', 'unicamp' ); ?></button>
		</div>

		<?php do_action( 'tutor_profile_edit_input_after' ); ?>

	</form>

	<?php do_action( 'tutor_profile_edit_form_after' ); ?>

</div>
