<?php

/**
 * Template for editing email template
 *
 * @since v.2.0
 *
 * @author Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Certificate
 * @version 2.0
 */

use TUTOR_EMAIL\EmailNotification;

$email_back_url    = add_query_arg(
	array(
		'page'     => 'tutor_settings',
		'tab_page' => 'email_notification',
	),
	admin_url('admin.php')
);
$arrow_left        = esc_url(TUTOR_EMAIL()->url . 'assets/images/arrow-left.svg');
$get_request       = isset($_GET) ? $_GET : null;
$recipient         = $active_tab_data['edit_email_data'];
$recipient_data    = $recipient['mail'];
$email_template    = $recipient_data['template'];
$field_key         = $get_request['to'] . '.' . $get_request['edit'];
$field_name        = "tutor_option[{$get_request['to']}][{$get_request['edit']}]";
$email_option_data = $this->get($field_key, 'off');
$option_data       = isset(get_option('email_template_data')[$get_request['to']][$get_request['edit']]) ? get_option('email_template_data')[$get_request['to']][$get_request['edit']] : null;

$label             = isset($option_data['label']) ? $option_data['label'] : null;
$subject           = isset($option_data['subject']) ? $option_data['subject'] : null;
$heading           = isset($option_data['heading']) ? $option_data['heading'] : null;
$message           = isset($option_data['message']) ? $option_data['message'] : null;
$before_button     = isset($option_data['before_button']) ? $option_data['before_button'] : null;
$footer            = isset($option_data['footer_text']) ? $option_data['footer_text'] : null;
$block_heading     = isset($option_data['block_heading']) ? $option_data['block_heading'] : null;
$block_content     = isset($option_data['block_content']) ? $option_data['block_content'] : null;

?>
<section class="tutor-backend-settings-page email-manage-page" style="margin-left: -20px;">
	<header class="header-wrapper tutor-px-32 tutor-py-24">
		<div class="tutor-mb-16">
			<a href="<?php echo esc_url($email_back_url); ?>" class="prev-page">
				<img src="<?php echo esc_url($arrow_left); ?>" alt="arrow-left">
				<span class="tutor-fs-7 tutor-pl-2">Back</span>
			</a>
		</div>
		<div class="header-main tutor-d-flex flex-wrap  tutor-align-center tutor-justify-between">
			<div class="header-left">
				<h4 class="tutor-color-black tutor-d-flex tutor-align-center tutor-fs-4 tutor-fw-medium tutor-mt-4">
					<span class="email_template_title"><?php echo esc_attr(ucwords($recipient_data['label'])); ?></span>
					<label class="tutor-form-toggle tutor-ml-20">
						<input type="hidden" class="tutor-form-toggle-input" id="email_option_data" name="<?php echo esc_attr($field_name); ?>" value="<?php echo esc_attr($email_option_data); ?>">
						<input type="checkbox" class="tutor-form-toggle-input" <?php checked($email_option_data, 'on'); ?>>
						<span class="tutor-form-toggle-control"></span>
					</label>
				</h4>
				<span class="subtitle tutor-mt-8 tutor-fs-6 d-inline-flex tutor-pl-0">
					<?php esc_html_e(ucfirst(str_replace('_', ' ', $get_request['to']))); ?>
				</span>
			</div>

			<div class="header-right tutor-d-inline-flex">
				<button class="tutor-btn tutor-btn-outline-primary" id="tutor-btn-test-mail" data-mailto="<?php echo esc_attr($get_request['to']); ?>">
					<span class="tutor-icon-paper-plane tutor-mr-8" area-hidden="true"></span>
					<span><?php _e("Send a Test Mail", "tutor-pro"); ?></span>
				</button>
				<button class="tutor-btn tutor-btn-primary tutor-ml-0 tutor-ml-lg-16" form="tutor-email-template-form" id="tutor-btn-save" action-tutor-email-template-save>Save Changes</button>
			</div>
		</div>
	</header>

	<main class="main-content-wrapper">
		<div class="main-content content-left">
			<header class="tutor-mb-8">
				<div class="tutor-fs-5 tutor-fw-medium tutor-color-black tutor-mb-2 tutor-d-flex  tutor-align-center">
					<span> <?php _e('Template Content', 'tutor-pro'); ?> </span>
					<div class="tooltip-wrap tooltip-icon">
						<span class="tooltip-txt tooltip-right">Edit your email template content</span>
					</div>
				</div>
			</header>

			<div class="content-form">
				<form method="POST" id="tutor-email-template-form">
					<input type="hidden" name="to" value="<?php echo esc_attr($recipient['to']); ?>">
					<input type="hidden" name="key" value="<?php echo esc_attr($recipient['key']); ?>">
					<input type="hidden" name="action" value="save_email_template">

					<div class="tutor-option-field-input field-group tutor-mb-16">
						<label class="tutor-form-label tutor-d-flex tutor-align-center">
							<span><?php echo _e('Subject', 'tutor-pro'); ?></span>
							<div class="tooltip-wrap tooltip-icon">
								<span class="tooltip-txt tooltip-right"><?php _e('Edit the subject of your email', 'tutor-pro'); ?></span>
							</div>
						</label>
						<input type="text" name="email-subject" value="<?php echo esc_html($subject); ?>" class="tutor-form-control" placeholder="<?php _e('Add the Email Subject', 'tutor-pro'); ?>" required>
					</div>

					<div class="tutor-option-field-input field-group tutor-mb-16">
						<label class="tutor-form-label tutor-d-flex tutor-align-center">
							<span>Email heading </span>
							<div class="tooltip-wrap tooltip-icon">
								<span class="tooltip-txt tooltip-right"><?php _e('Edit the Email Heading of your email', 'tutor-pro'); ?></span>
							</div>
						</label>
						<input type="text" name="email-heading" class="tutor-form-control" placeholder="<?php _e('Add an Email Heading', 'tutor-pro'); ?>" value="<?php echo esc_html($heading); ?>" required>
					</div>

					<div class="tutor-option-field-input field-group tutor-mb-16">
						<label class="tutor-form-label tutor-d-flex  tutor-align-start">
							<span> Additional Content </span>
							<div class="tooltip-wrap tooltip-icon">
								<span class="tooltip-txt tooltip-right"><?php _e('Edit additional content of your email', 'tutor-pro'); ?></span>
							</div>
						</label>

						<?php
						// $content   = html_entity_decode( tutor_utils()->clean_html_content( json_decode( $message ) ) );
						$content   = json_decode($message);
						$editor_id = 'email-additional-message';

						$args = array(
							'tinymce'       => array(
								'toolbar1' => 'bold, alignleft, aligncenter, alignright, separator, link, unlink, undo, redo, wp_adv, wp_help',
								'toolbar2' => 'italic, underline, separator, pastetext, removeformat, charmap, outdent, indent',
								'toolbar3' => '',
							),
							'media_buttons' => false,
							'quicktags'     => true,
							'elementpath'   => false,
							'wpautop'       => false,
							'statusbar'     => false,
							'editor_height' => 130
						);
						wp_editor($content, $editor_id, $args);
						?>
					</div>

					<?php if (isset($recipient_data['before_button']) && null !== $recipient_data['before_button']) : ?>
						<div class="tutor-option-field-input field-group tutor-mb-16">
							<label class="tutor-form-label tutor-d-flex tutor-align-center">
								<span><?php echo __('Email Before Button', 'tutor-pro'); ?></span>
								<div class="tooltip-wrap tooltip-icon">
									<span class="tooltip-txt tooltip-right"><?php _e('Add a CTA text to appear on top of your Action Button', 'tutor-pro'); ?></span>
								</div>
							</label>
							<textarea style="height: 100px; resize:none;" class="tutor-form-control" name="email-before-button" placeholder="Before button text."><?php echo esc_html($before_button); ?></textarea>
						</div>
					<?php endif; ?>

					<?php if (isset($recipient_data['footer_text']) && null !== $recipient_data['footer_text']) : ?>
						<div class="tutor-option-field-input field-group tutor-mb-16">
							<label class="tutor-form-label tutor-d-flex tutor-align-center">
								<span><?php echo __('Email Footer Text', 'tutor-pro'); ?></span>
								<div class="tooltip-wrap tooltip-icon">
									<span class="tooltip-txt tooltip-right">Text to appear below the main email content. Available placeholders:
										{site_title}, {site_address}, {site_url}, {order_date}, {order_number}</span>
								</div>
							</label>
							<input type="text" name="email-footer-text" class="tutor-form-control" placeholder="footer text of email" value="<?php echo esc_html($footer); ?>">
						</div>
					<?php endif; ?>


				</form>
			</div>
		</div>

		<div class="main-content content-right">
			<header class="tutor-d-flex tutor-align-center tutor-justify-center flex-wrap tutor-mb-16">
				<div class="tutor-fs-5 tutor-fw-medium tutor-color-black"><?php _e("Template Preview", "tutor-pro"); ?></div>
			</header>

			<div class="template-preview" data-email_template="<?php echo esc_attr($email_template); ?>">
				<div class="loading-spinner" area-hidden="true"></div>
				<?php
				EmailNotification::tutor_load_email_preview($email_template);
				echo '<div class="tutor-email-footer-content">' . html_entity_decode(wp_unslash(tutor_utils()->clean_html_content(json_decode($this->get('email_footer_text'))))) . '</div>';
				?>
			</div>
		</div>
	</main>


</section>

<style>
	body.tutor-backend-tutor_settings #wpbody-content {
		margin-top: 0;
	}

	input:disabled {
		background: rgba(255, 255, 255, .5) !important;
		border-color: rgba(220, 220, 222, .75) !important;
		box-shadow: inset 0 1px 2px rgb(0 0 0 / 4%) !important;
		color: rgba(44, 51, 56, .5) !important;
	}

	#wpbody-content {
		padding-bottom: 0;
	}
</style>