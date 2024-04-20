<?php
/**
 * Email Notification
 *
 * @package TutorPro
 * @subpackage Addons\TutorEmail
 * @author Themeum <support@themeum.com>
 * @link https://themeum.com
 * @since 1.0.0
 */

namespace TUTOR_EMAIL;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use TUTOR\Input;
use Tutor\Models\CourseModel;
use TUTOR_CERT\Certificate;

class EmailNotification {

	private $queue_table;
	public $email_logo;
	public $email_options;
	public $default_mail_data;

	public function __construct() {

		global $wpdb;
		$this->queue_table = $wpdb->tutor_email_queue;

		add_action( 'tutor_quiz/attempt_ended', array( $this, 'quiz_finished_send_email_to_student' ), 10, 1 );
		add_action( 'tutor_finish_quiz_attempt', array( $this, 'quiz_finished_send_email_to_student' ), 10, 1 );

		/**
		 * Lesson, quiz & assignment mail handler
		 *
		 * @since v2.0.4
		 */
		add_action( 'tutor/lesson/created', array( $this, 'tutor_create_or_update_lesson' ), 10, 1 );
		add_action( 'tutor_assignment_created', array( $this, 'tutor_create_or_update_lesson' ), 10, 1 );
		add_action( 'tutor_initial_quiz_created', array( $this, 'tutor_create_or_update_lesson' ), 10, 1 );

		add_action( 'tutor_quiz/attempt_ended', array( $this, 'quiz_finished_send_email_to_instructor' ), 10, 1 );
		add_action( 'tutor_finish_quiz_attempt', array( $this, 'quiz_finished_send_email_to_instructor' ), 10, 1 );

		add_action( 'tutor_course_complete_after', array( $this, 'course_complete_email_to_student' ), 10, 1 );
		add_action( 'tutor_course_complete_after', array( $this, 'course_complete_email_to_teacher' ), 10, 1 );
		// add_action('tutor/course/enrol_status_change/after', array($this, 'course_enroll_email'), 10, 2);
		add_action( 'tutor_after_enrolled', array( $this, 'course_enroll_email_to_teacher' ), 10, 3 );
		add_action( 'tutor_after_enrolled', array( $this, 'course_enroll_email_to_student' ), 10, 3 );
		add_action( 'tutor_after_add_question', array( $this, 'tutor_after_add_question' ), 10, 2 );
		add_action( 'tutor_lesson_completed_email_after', array( $this, 'tutor_lesson_completed_email_after' ), 10, 1 );

		/**
		 * @since 1.6.9
		 */
		add_action( 'tutor_add_new_instructor_after', array( $this, 'tutor_new_instructor_signup' ), 10, 2 );
		// adding hook for instructor register
		add_action( 'tutor_new_instructor_after', array( $this, 'tutor_new_instructor_signup' ), 10, 2 );

		add_action( 'tutor_after_student_signup', array( $this, 'tutor_new_student_signup' ), 10, 2 );
		add_action( 'draft_to_pending', array( $this, 'tutor_course_pending' ), 10, 3 );
		add_action( 'auto-draft_to_pending', array( $this, 'tutor_course_pending' ), 10, 3 );
		add_action( 'draft_to_publish', array( $this, 'tutor_course_published' ), 10, 3 );
		add_action( 'auto-draft_to_publish', array( $this, 'tutor_course_published' ), 10, 3 );
		add_action( 'pending_to_publish', array( $this, 'tutor_course_published' ), 10, 3 );
		add_action( 'save_post_' . tutor()->course_post_type, array( $this, 'tutor_course_updated' ), 10, 3 );
		add_action( 'wp_ajax_save_email_template', array( $this, 'save_email_template' ) );
		add_action( 'wp_ajax_send_test_email_ajax', array( $this, 'send_test_email_ajax' ) );
		add_action( 'wp_ajax_import_bulk_student', array( $this, 'import_bulk_student' ) );
		// add_filter( 'tutor_localize_data', array( $this, 'email_current_template_data' ) );

		/**
		 * Send mail to instructor if their course accepted or rejected
		 *
		 * @since 1.9.8
		 */
		add_action( 'save_post_' . tutor()->course_post_type, array( $this, 'tutor_course_update_notification' ), 20, 3 );
		add_action( 'tutor_assignment/after/submitted', array( $this, 'tutor_assignment_after_submitted' ), 10, 3 );
		add_action( 'tutor_assignment/evaluate/after', array( $this, 'tutor_after_assignment_evaluate' ), 10, 3 );

		add_action( 'tutor_enrollment/after/delete', array( $this, 'tutor_student_remove_from_course' ), 10, 3 );
		add_action( 'tutor_enrollment/after/cancel', array( $this, 'tutor_student_remove_from_course' ), 10, 3 );
		add_action( 'tutor_enrollment/after/expired', array( $this, 'tutor_enrollment_after_expired' ), 10, 3 ); // @since 1.8.1

		add_action( 'tutor_announcements/after/save', array( $this, 'tutor_announcements_notify_students' ), 10, 3 );

		add_action( 'tutor_after_asked_question', array( $this, 'tutor_after_asked_question' ), 10, 1 );
		add_action( 'tutor_quiz/attempt/submitted/feedback', array( $this, 'feedback_submitted_for_quiz_attempt' ), 10, 3 );
		add_action( 'tutor_course_complete_after', array( $this, 'tutor_course_complete_after' ), 10, 3 );

		/**
		 * @since 1.7.4
		 */
		add_action( 'tutor_after_approved_instructor', array( $this, 'instructor_application_approved' ), 10 );
		add_action( 'tutor_after_rejected_instructor', array( $this, 'instructor_application_rejected' ), 10 );
		add_action( 'tutor_after_approved_withdraw', array( $this, 'withdrawal_request_approved' ), 10 );
		add_action( 'tutor_after_rejected_withdraw', array( $this, 'withdrawal_request_rejected' ), 10 );
		add_action( 'tutor_insert_withdraw_after', array( $this, 'withdrawal_request_placed' ), 10 );

		add_action( 'tutor-pro/content-drip/new_lesson_published', array( $this, 'new_lqa_published' ), 10 );
		add_action( 'tutor-pro/content-drip/new_quiz_published', array( $this, 'new_lqa_published' ), 10 );
		add_action( 'tutor-pro/content-drip/new_assignment_published', array( $this, 'new_lqa_published' ), 10 );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_email_scripts' ) );

		/**
		 * @since 1.8.7
		 * Cron register/deregister
		 */
		add_filter( 'cron_schedules', array( $this, 'tutor_cron_schedules' ) );
		add_action( 'tutor_email_scheduler_cron', array( $this, 'run_scheduler' ) );
		add_action( 'tutor_addon_before_disable_tutor-pro/addons/tutor-email/tutor-email.php', array( $this, 'deregister_scheduler' ) );
		register_deactivation_hook( TUTOR_PRO_FILE, array( $this, 'deregister_scheduler' ) );

		add_action(
			'init',
			function() {
				$is_os_native = isset( $_GET['tutor_cron'] ) && $_GET['tutor_cron'] == '1';

				if ( $is_os_native ) {
					$this->run_scheduler( true );
					exit;
				}
			}
		);

		add_action(
			'tutor_option_save_after',
			function() {
				// Set schedule again based on new interval setting
				$this->register_scheduler( true );
			}
		);
		// Register scheduler as normal procedure
		$this->register_scheduler();

		// assign email variables
		add_action( 'init', array( $this, 'save_recipient_data' ) );

		$this->email_logo        = esc_url( TUTOR_EMAIL()->url . 'assets/images/tutor-logo.png' );
		$this->email_options     = get_option( 'email_template_data' );
		$this->default_mail_data = ( new EmailData() )->get_recipients();

		add_action(
			'init',
			function() {
				do_action( 'tutor_email_scheduler_cron' );
			}
		);
	}

	/**
	 * Send email after course content created or updated
	 * Supported contents are: lesson, assignment & quiz
	 *
	 * @param int $course_content_id  could be lesson, assignment or quiz id
	 *
	 * @return void
	 */
	public function tutor_create_or_update_lesson( int $course_content_id ): void {
		$is_enabled_lesson_mail     = (bool) tutor_utils()->get_option( 'email_to_students.new_lesson_published' );
		$is_enabled_quiz_mail       = (bool) tutor_utils()->get_option( 'email_to_students.new_quiz_published' );
		$is_enabled_assignment_mail = (bool) tutor_utils()->get_option( 'email_to_students.new_assignment_published' );

		if ( ! $is_enabled_lesson_mail && ! $is_enabled_quiz_mail && ! $is_enabled_assignment_mail ) {
			return;
		}
		$content_type  = get_post_type( $course_content_id );
		$template_name = '';
		$option_data   = '';
		$email_heading = '';
		$subject       = '';
		$footer_text   = '';
		$message       = '';
		if ( 'lesson' === $content_type ) {
			$template_name = 'to_student_new_lesson_published';
			$option_data   = $this->email_options['email_to_students']['new_lesson_published'];
			$email_heading = $this->default_mail_data['email_to_students']['new_lesson_published']['heading'];
			$subject       = $this->default_mail_data['email_to_students']['new_lesson_published']['subject'];
			$footer_text   = $this->default_mail_data['email_to_students']['new_lesson_published']['footer_text'];
			$message       = $this->default_mail_data['email_to_students']['new_lesson_published']['message'];
		}
		if ( 'tutor_quiz' === $content_type ) {
			$template_name = 'to_student_new_quiz_published';
			$option_data   = $this->email_options['email_to_students']['new_quiz_published'];
			$email_heading = $this->default_mail_data['email_to_students']['new_quiz_published']['heading'];
			$subject       = $this->default_mail_data['email_to_students']['new_quiz_published']['subject'];
			$footer_text   = $this->default_mail_data['email_to_students']['new_quiz_published']['footer_text'];
			$message       = $this->default_mail_data['email_to_students']['new_quiz_published']['message'];
		}
		if ( 'tutor_assignments' === $content_type ) {
			$template_name = 'to_student_new_assignment_published';
			$option_data   = $this->email_options['email_to_students']['new_assignment_published'];
			$email_heading = $this->default_mail_data['email_to_students']['new_assignment_published']['heading'];
			$subject       = $this->default_mail_data['email_to_students']['new_assignment_published']['subject'];
			$footer_text   = $this->default_mail_data['email_to_students']['new_assignment_published']['footer_text'];
			$message       = $this->default_mail_data['email_to_students']['new_assignment_published']['message'];
		}

		if ( isset( $option_data['heading'] ) && '' != $option_data['heading'] ) {
			$email_heading = $option_data['heading'];
		}

		if ( isset( $option_data['subject'] ) && '' != $option_data['subject'] ) {
			$subject = $option_data['subject'];
		}

		if ( isset( $option_data['footer_text'] ) && '' != $option_data['footer_text'] ) {
			$footer_text = $option_data['footer_text'];
		}

		$topic_id  = (int) wp_get_post_parent_id( $course_content_id );
		$course_id = (int) wp_get_post_parent_id( $topic_id );
		if ( ! $course_id ) {
			return;
		}

		$students = tutor_utils()->get_students_data_by_course_id( $course_id, 'ID', true );
		if ( $is_enabled_lesson_mail && 'lesson' === $content_type ) {
			$this->course_content_mail_to_student( $students, $course_id, $course_content_id, $subject, $email_heading, $message, $footer_text, $template_name, $option_data );
		}
		if ( $is_enabled_quiz_mail && 'tutor_quiz' === $content_type ) {
			$this->course_content_mail_to_student( $students, $course_id, $course_content_id, $subject, $email_heading, $message, $footer_text, $template_name, $option_data );
		}
		if ( $is_enabled_assignment_mail && 'tutor_assignments' === $content_type ) {
			$this->course_content_mail_to_student( $students, $course_id, $course_content_id, $subject, $email_heading, $message, $footer_text, $template_name, $option_data );
		}
		return;
	}

	/**
	 * Course content mail trigger method
	 * send mail for new created or updated lesson | quiz | assignment
	 *
	 * @since v2.0.4
	 *
	 * @param array  $students
	 * @param int    $course_id
	 * @param int    $course_content_id
	 * @param string $subject
	 * @param string $email_heading
	 * @param string $message
	 * @param string $footer_text
	 * @param string $template_name
	 * @param array  $option_data
	 *
	 * @return void
	 */
	public function course_content_mail_to_student( $students, $course_id, $course_content_id, $subject, $email_heading, $message, $footer_text, $template_name, $option_data ): void {

		$email_heading = str_replace( '{course_name}', get_the_title( $course_id ), $email_heading );
		$subject       = str_replace( '{course_name}', get_the_title( $course_id ), $subject );

		if ( is_array( $students ) && count( $students ) ) {
			foreach ( $students as $key => $student ) {

				$student_name = tutor_utils()->get_user_name( get_userdata( $student->ID ) );
				$site_url     = get_bloginfo( 'url' );
				$site_name    = get_bloginfo( 'name' );
				$header       = 'Content-Type: ' . $this->get_content_type() . "\r\n";

				$replacable['{dashboard_url}'] = tutor_utils()->tutor_dashboard_url();

				$replacable['{course_url}']           = get_the_permalink( $course_id );
				$replacable['{testing_email_notice}'] = '';
				$replacable['{user_name}']            = $student_name;
				$replacable['{course_name}']          = get_the_title( $course_id );
				$replacable['{lesson_title}']         = get_the_title( $course_content_id );
				$replacable['{quiz_title}']           = get_the_title( $course_content_id );
				$replacable['{assignment_title}']     = get_the_title( $course_content_id );
				$replacable['{site_url}']             = $site_url;
				$replacable['{site_name}']            = $site_name;
				$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
				$replacable['{email_heading}']        = $email_heading;
				$replacable['{footer_text}']          = $footer_text;
				$replacable['{email_message}']        = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
				ob_start();
				$this->tutor_load_email_template( $template_name, $replacable );
				$email_tpl = apply_filters( 'tutor_email_course_content', ob_get_clean(), $course_content_id );
				$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

				$header = 'Content-Type: ' . $this->get_content_type() . "\r\n";
				/**
				 * Note: Force queue set true, as number of students
				 * could be a lot more than expected.
				 */
				$this->send( $student->user_email, $subject, $message, $header, array(), true );
			}
		}
		return;
	}

	public function import_bulk_student() {
		tutor_utils()->checking_nonce();
		$data_to_import = json_decode( stripslashes( $_POST['bulk_user'] ), true );

		foreach ( $data_to_import as $data_import ) {
			$users = array();

			$data = array(
				'user_login' => isset( $data_import['username'] ) ? sanitize_text_field( $data_import['username'] ) : '',
				'user_email' => isset( $data_import['email'] ) ? sanitize_text_field( $data_import['email'] ) : '',
				'user_pass'  => isset( $data_import['username'] ) ? sanitize_text_field( $data_import['username'] ) : '',
			);

			$user_id = wp_insert_user( $data );

			if ( ! is_wp_error( $user_id ) ) {

				$users[] = $user_id;

			}
		}
		wp_send_json( $users );
	}

	/**
	 * Ready the e-mail message
	 * unslash and trim (") from front and end of the message
	 *
	 * @param string $message
	 * @return string
	 */
	public function prepare_message( $message ) {
		return wp_unslash( json_decode( $message ) );
	}

	/**
	 * Save if email template data not found.
	 *
	 * @return void
	 */
	public function save_recipient_data() {
		$option_data    = get_option( 'email_template_data' );
		$recipient_data = ( new EmailData() )->get_recipients();
		if ( isset( $option_data ) && empty( $option_data ) ) {
			update_option( 'email_template_data', $recipient_data );
		}
	}

	public function enqueue_email_scripts() {
		if ( get_request( 'tab_page' ) === 'email_notification' ) {
			wp_enqueue_script( 'tutor-pro-email-template', tutor_pro()->url . 'addons/tutor-email/assets/js/email-template.js', array( 'jquery' ), TUTOR_PRO_VERSION, true );
			wp_enqueue_style( 'tutor-pro-email-styles', tutor_pro()->url . 'addons/tutor-email/assets/css/email-manage.css', array(), true, null );
		}

	}

	public function email_current_template_data( $localize_data ) {
		$email_data['get_email_data'] = ( new EmailData() )->get_recipients();
		return $email_data;
	}

	/**
	 * Save e-mail template
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function save_email_template() {
		tutor_utils()->checking_nonce();
		$to                  = Input::post( 'to' );
		$key                 = Input::post( 'key' );
		$subject             = Input::post( 'email-subject' );
		$heading             = Input::post( 'email-heading' );
		$email_footer_text   = Input::post( 'email-footer-text', null, Input::TYPE_TEXTAREA );
		$email_block_heading = Input::post( 'email-block-heading', null, Input::TYPE_TEXTAREA );
		$email_block_content = Input::post( 'email-block-content', null, Input::TYPE_TEXTAREA );
		$email_before_button = Input::post( 'email-before-button' );

		$tutor_email_options = array();
		$tutor_email_options = get_option( 'email_template_data' );
		$tutor_options       = get_option( 'tutor_option' );
		$message             = json_encode( Input::post( 'email-additional-message', '', Input::TYPE_KSES_POST ) );

		$email_option_data = ! empty( $tutor_email_options ) ? $tutor_email_options : array();

		$tutor_options[ $to ][ $key ] = $_POST['tutor_option'][ $to ][ $key ];

		$email_request[ $to ][ $key ] = array(
			'subject'       => $subject,
			'heading'       => $heading,
			'message'       => $message,
			'footer_text'   => $email_footer_text,
			'block_heading' => $email_block_heading,
			'block_content' => $email_block_content,
			'before_button' => wp_kses_post( wp_unslash( $email_before_button ) ),
		);

		if ( ! empty( $email_option_data ) ) {

			foreach ( $email_option_data as $key => $email_data ) {
				if ( isset( $email_request[ $key ] ) ) {
					$email_output[ $key ] = array_merge( $email_option_data[ $key ], $email_request[ $key ] );
				} else {
					$email_output[ $key ] = $email_option_data[ $key ];
				}
			}

			$tutor_email_options = ( ! array_key_exists( $to, $email_output ) ) ? array_merge( $email_output, $email_request ) : $email_output;

		} else {
			$tutor_email_options = array_merge( $email_option_data, $email_request );
		}

		update_option( 'email_template_data', $tutor_email_options );
		update_option( 'tutor_option', $tutor_options );

		wp_send_json_success( $message );
	}

	/**
	 * Load email template
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function tutor_load_email_template( $template, $pro = true, $extra = array() ) {
		extract( $extra );
		include tutor_get_template( 'email.' . $template, $pro );
	}

	public static function tutor_load_email_preview( $template, $pro = true ) {
		include tutor_get_template( 'email.' . $template, $pro );
	}

	/**
	 * @param $to
	 * @param $subject
	 * @param $message
	 * @param $headers
	 * @param array   $attachments
	 *
	 * @return bool
	 *
	 *
	 * Send E-Mail Notification for Tutor Event
	 */

	public function send( $to, $subject, $message, $headers, $attachments = array(), $force_enqueue = false ) {
		$message = apply_filters( 'tutor_mail_content', $message );
		$this->enqueue_email( $to, $subject, $message, $headers, $attachments, $force_enqueue );
	}

	/**
	 * Get the from name for outgoing emails from tutor
	 *
	 * @return string
	 */
	public function get_from_name() {
		$email_from_name = tutor_utils()->get_option( 'email_from_name' );
		$from_name       = apply_filters( 'tutor_email_from_name', $email_from_name );
		return wp_specialchars_decode( esc_html( $from_name ), ENT_QUOTES );
	}

	/**
	 * Get the from name for outgoing emails from tutor
	 *
	 * @return string
	 */
	public function get_from_address() {
		$email_from_address = tutor_utils()->get_option( 'email_from_address' );
		$from_address       = apply_filters( 'tutor_email_from_address', $email_from_address );
		return sanitize_email( $from_address );
	}

	/**
	 * @return string
	 *
	 * Get content type
	 */
	public function get_content_type() {
		return apply_filters( 'tutor_email_content_type', 'text/html' );
	}

	public function get_message( $message = '', $search = array(), $replace = array() ) {
		$email_footer_text = tutor_utils()->get_option( 'email_footer_text' );
		$email_footer_text = str_replace( '{site_name}', get_bloginfo( 'name' ), $email_footer_text );
		$message           = str_replace( $search, $replace, $message );
		if ( $email_footer_text ) {
			$message .= '<div class="tutor-email-footer-content">' . wp_unslash( json_decode( $email_footer_text ) ) . '</div>';
		}
		return $message;
	}

	/**
	 * Function to replace and return
	 *
	 * @param  mixed $message .
	 * @param  mixed $search .
	 * @param  mixed $replace .
	 * @return void
	 */
	public function get_replaced_text( $message = '', $search = array(), $replace = array() ) {
		return str_replace( $search, $replace, $message );
	}

	public function _generate_email( $string ) {
		$username = strtolower( str_replace( array( ' ', '_' ), '', $string ) );
		return esc_attr( $username . '@' . parse_url( home_url() )['host'] );
	}

	public function _generate_username( $string ) {
		return strtolower( str_replace( array( ' ', '.', '_', '-' ), '', $string ) );
	}

	public function send_test_email_ajax() {
		$header = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		// $email_data     = ( new EmailData() )->get_recipients();
		$email_data     = get_option( 'email_template_data' );
		$recipient_data = $email_data[ get_request( 'email_to' ) ][ get_request( 'email_key' ) ];
		$tempData       = $email_data[ get_request( 'email_to' ) ][ get_request( 'email_key' ) ];

		$user_id                = get_current_user_id();
		$site_url               = get_bloginfo( 'url' );
		$current_user           = get_userdata( $user_id );
		$student_name           = __( 'Sample Student Name', 'tutor-pro' );
		$instructor_name        = __( 'Sample Instructor Name', 'tutor-pro' );
		$instructor_description = __( 'Sample Instructor Description', 'tutor-pro' );
		$email_template         = get_request( 'email_template' );
		$testing_email          = ! empty( get_request( 'testing_email' ) ) ? get_request( 'testing_email' ) : $current_user->user_email;
		$notice_icon            = TUTOR_EMAIL()->url . 'assets/images/warning.png';
		$tutor_url              = 'https://www.themeum.com/product/tutor-lms';
		$approved_url           = sprintf( admin_url( 'admin.php?page=%s&action=%s' ), 'tutor_withdraw_requests', 'approved' );
		$rejected_url           = sprintf( admin_url( 'admin.php?page=%s&action=%s' ), 'tutor_withdraw_requests', 'rejected' );
		$get_subject            = wp_kses_post( $recipient_data['subject'] );
		$course_title           = __( 'Sample Course Title', 'tutor-pro' );
		$lesson_title           = __( 'Sample Lesson Title', 'tutor-pro' );
		$quiz_title             = __( 'Sample Quiz Title?', 'tutor-pro' );
		$assignment_name        = __( 'Sample Assignment Name', 'tutor-pro' );
		$total_amount           = 100;
		$earned_amount          = 80;
		$instructor_avatar      = get_avatar_url( wp_get_current_user()->ID );
		$lorem_date             = the_time( 'l, F jS, Y' );
		$announcement_title     = 'Sample announcement title';
		$announcement_content   = 'Sample announcement content.';
		$assignment_content     = 'Sample assignment content.';
		$lorem_content_sm       = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam et fermentum dui. Ut orci quam, ornare sed lorem sed, hendrerit auctor dolor?';

		$replacable['{earned_marks}']           = 8;
		$replacable['{total_marks}']            = 10;
		$replacable['{attempt_result}']         = '<span class="tutor-badge-label label-success">Pass</span>';
		$replacable['{email_heading}']          = isset( $recipient_data['heading'] ) ? $recipient_data['heading'] : '';
		$replacable['{email_message}']          = isset( $recipient_data['message'] ) ? $this->prepare_message( $recipient_data['message'] ) : '';
		$replacable['{footer_text}']            = isset( $recipient_data['footer_text'] ) ? $recipient_data['footer_text'] : '';
		$replacable['{student_name}']           = $student_name;
		$replacable['{student_username}']       = $this->_generate_username( $student_name );
		$replacable['{user_name}']              = tutor_utils()->get_user_name( $current_user );
		$replacable['{admin_name}']             = $current_user->display_name;
		$replacable['{admin_user}']             = $current_user->display_name;
		$replacable['{student_email}']          = $current_user->user_email;
		$replacable['{site_url}']               = $site_url;
		$replacable['{tutor_url}']              = $tutor_url;
		$replacable['{site_name}']              = get_bloginfo( 'name' );
		$replacable['{course_url}']             = $site_url;
		$replacable['{profile_url}']            = $site_url;
		$replacable['{student_url}']            = get_author_posts_url( $user_id );
		$replacable['{course_title}']           = $course_title;
		$replacable['{course_name}']            = $course_title;
		$replacable['{total_amount}']           = $total_amount;
		$replacable['{earned_amount}']          = $earned_amount;
		$replacable['{lesson_title}']           = $lesson_title;
		$replacable['{lesson_name}']            = $lesson_title;
		$replacable['{quiz_name}']              = $quiz_title;
		$replacable['{quiz_title}']             = $quiz_title;
		$replacable['{question}']               = $lorem_content_sm;
		$replacable['{enroll_time}']            = isset( $recipient_data['enroll_time'] ) ? $recipient_data['enroll_time'] : '';
		$replacable['{instructor_username}']    = $instructor_name;
		$replacable['{instructor_avatar}']      = $instructor_avatar;
		$replacable['{instructor_description}'] = $instructor_description;
		$replacable['{logo}']                   = TUTOR_EMAIL()->url . 'assets/images/tutor-logo.png';
		$replacable['{answer_by}']              = $student_name;
		$replacable['{answer_date}']            = $lorem_date;
		$replacable['{before_button}']          = isset( $recipient_data['before_button'] ) ? $recipient_data['before_button'] : '';
		$replacable['{username}']               = $current_user->user_nicename;
		$replacable['{instructor_email}']       = $this->_generate_email( $instructor_name );
		$replacable['{student_email}']          = $this->_generate_email( $student_name );
		$replacable['{instructor_name}']        = $instructor_name;
		$replacable['{block_heading}']          = isset( $recipient_data['block_heading'] ) ? $recipient_data['block_heading'] : '';
		$replacable['{block_content}']          = isset( $recipient_data['block_content'] ) ? $recipient_data['block_content'] : '';
		$replacable['{withdraw_amount}']        = isset( $recipient_data['withdraw_amount'] ) ? $recipient_data['withdraw_amount'] : '';
		$replacable['{assignment_name}']        = $assignment_name;
		$replacable['{assignment_score}']       = isset( $recipient_data['assignment_score'] ) ? $recipient_data['assignment_score'] : '';
		$replacable['{assignment_max_mark}']    = isset( $recipient_data['assignment_max_mark'] ) ? $recipient_data['assignment_max_mark'] : '';
		$replacable['{testing_email_notice}']   = '<div class="tutor-email-warning"><img src="' . $notice_icon . '" alt="notice"><span><span class="no-res">This is a</span> test mail</span></div>';
		$replacable['{approved_url}']           = $approved_url;
		$replacable['{rejected_url}']           = $rejected_url;
		$replacable['{announcement_title}']     = $announcement_title;
		$replacable['{announcement_content}']   = $announcement_content;
		$replacable['{announcement_date}']      = $lorem_date;
		$replacable['{author_fullname}']        = $student_name;
		$replacable['{assignment_comment}']     = $assignment_content;
		$replacable['{attempt_url}']            = 'javascript:void(0)';
		$subject                                = $this->get_replaced_text( $get_subject, array_keys( $replacable ), array_values( $replacable ) );
		// die($get_subject);

		ob_start();
		$this->tutor_load_email_template( $email_template );
		$email_tpl = apply_filters( 'tutor_email_tpl/testing_emails', ob_get_clean() );
		$message   = $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) );
		// die($message);
		$this->send( $testing_email, $subject, $message, $header );
	}


	/**
	 * Function to send course_complete_email_to_student
	 *
	 * @param  int $course_id is related to course .
	 * @return string
	 */
	public function course_complete_email_to_student( $course_id ) {
		$course_completed_to_student = tutor_utils()->get_option( 'email_to_students.completed_course' );

		if ( ! $course_completed_to_student ) {
			return;
		}

		$user_id                = get_current_user_id();
		$course                 = get_post( $course_id );
		$student                = get_userdata( $user_id );
		$teacher                = get_userdata( $course->post_author );
		$instructor_avatar      = get_avatar_url( $teacher->ID );
		$instructor_email       = $teacher->user_email;
		$instructor_description = $teacher->user_description;
		$completion_time        = tutor_utils()->is_completed_course( $course_id );
		$completion_time        = $completion_time ? $completion_time : tutor_time();
		$completion_time_format = date_i18n( get_option( 'date_format' ), $completion_time ) . ' ' . date_i18n( get_option( 'time_format' ), $completion_time );
		$site_url               = get_bloginfo( 'url' );
		$site_name              = get_bloginfo( 'name' );
		$option_data            = $this->email_options['email_to_students']['completed_course'];
		$header                 = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header                 = apply_filters( 'student_course_completed_email_header', $header, $course_id );
		$certificate_url        = ( new Certificate( true ) )->get_certificate( $course_id );

		$replacable['{testing_email_notice}']   = '';
		$replacable['{instructor_username}']    = $teacher->display_name;
		$replacable['{user_name}']              = tutor_utils()->get_user_name( $student );
		$replacable['{course_name}']            = $course->post_title;
		$replacable['{completion_time}']        = $completion_time_format;
		$replacable['{course_url}']             = get_the_permalink( $course_id );
		$replacable['{site_url}']               = $site_url;
		$replacable['{site_name}']              = $site_name;
		$replacable['{instructor_avatar}']      = $instructor_avatar;
		$replacable['{instructor_email}']       = $instructor_email;
		$replacable['{instructor_description}'] = $instructor_description;
		$replacable['{logo}']                   = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']          = $option_data['heading'];
		$replacable['{before_button}']          = $option_data['before_button'];
		$replacable['{footer_text}']            = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}']          = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                                = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template(
			'to_student_course_completed',
			true,
			array(
				'course_id'       => $course_id,
				'certificate_url' => $certificate_url,
			)
		);
		$email_tpl = apply_filters( 'tutor_email_tpl/course_completed', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$this->send( $student->user_email, $subject, $message, $header );

	}


	public function course_complete_email_to_teacher( $course_id ) {
		$course_completed_to_teacher = tutor_utils()->get_option( 'email_to_teachers.a_student_completed_course' );

		if ( ! $course_completed_to_teacher ) {
			return;
		}

		$user_id                = get_current_user_id();
		$student                = get_userdata( $user_id );
		$course                 = get_post( $course_id );
		$teacher                = get_userdata( $course->post_author );
		$completion_time        = tutor_utils()->is_completed_course( $course_id );
		$completion_time        = $completion_time ? $completion_time : tutor_time();
		$completion_time_format = date_i18n( get_option( 'date_format' ), $completion_time ) . ' ' . date_i18n( get_option( 'time_format' ), $completion_time );
		$site_url               = get_bloginfo( 'url' );
		$site_name              = get_bloginfo( 'name' );
		$option_data            = $this->email_options['email_to_teachers']['a_student_completed_course'];
		$header                 = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header                 = apply_filters( 'student_course_completed_email_header', $header, $course_id );
		$dashboard_url          = tutor_utils()->tutor_dashboard_url();
		$student_report_url     = $dashboard_url . 'analytics/student-details?student_id=' . $user_id;

		$replacable['{testing_email_notice}'] = '';
		$replacable['{user_name}']            = tutor_utils()->get_user_name( $teacher );
		$replacable['{student_name}']         = $student->display_name;
		$replacable['{student_username}']     = $student->display_name;
		$replacable['{student_email}']        = $student->user_email;
		$replacable['{course_name}']          = $course->post_title;
		$replacable['{completion_time}']      = $completion_time_format;
		$replacable['{course_url}']           = get_the_permalink( $course_id );
		$replacable['{site_url}']             = $site_url;
		$replacable['{student_report_url}']   = $student_report_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{before_button}']        = $this->get_replaced_text( $option_data['before_button'], array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}']        = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                              = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_instructor_course_completed' );
		$email_tpl = apply_filters( 'tutor_email_tpl/course_completed', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$this->send( $teacher->user_email, $subject, $message, $header );

	}


	/**
	 * Send the quiz to Student
	 *
	 * @param $attempt_id
	 */

	public function quiz_finished_send_email_to_student( $attempt_id ) {
		$quiz_completed = tutor_utils()->get_option( 'email_to_students.quiz_completed' );
		if ( ! $quiz_completed ) {
			return;
		}

		$attempt = tutor_utils()->get_attempt( $attempt_id );

		$earned_percentage = $attempt->earned_marks > 0 ? ( number_format( ( $attempt->earned_marks * 100 ) / $attempt->total_marks ) ) : 0;
		$passing_grade     = (int) tutor_utils()->get_quiz_option( $attempt->quiz_id, 'passing_grade', 0 );
		$attempt_result    = $earned_percentage >= $passing_grade ?
														'<span class="tutor-badge-label label-success">' . esc_attr( 'Pass' ) . '</span>' :
														'<span class="tutor-badge-label label-danger">' . esc_attr( 'Fail' ) . '</span>';

		// pr( $attempt );
		// die;
		$attempt_info           = tutor_utils()->quiz_attempt_info( $attempt_id );
		$submission_time        = tutor_utils()->avalue_dot( 'submission_time', $attempt_info );
		$submission_time        = $submission_time ? $submission_time : tutor_time();
		$quiz_id                = tutor_utils()->avalue_dot( 'comment_post_ID', $attempt );
		$quiz_name              = get_the_title( $quiz_id );
		$course                 = CourseModel::get_course_by_quiz( $quiz_id );
		$course_id              = tutor_utils()->avalue_dot( 'ID', $course );
		$course_title           = get_the_title( $course_id );
		$submission_time_format = date_i18n( get_option( 'date_format' ), $submission_time ) . ' ' . date_i18n( get_option( 'time_format' ), $submission_time );
		$quiz_url               = get_the_permalink( $quiz_id );
		$user                   = get_userdata( tutor_utils()->avalue_dot( 'user_id', $attempt ) );
		$site_url               = get_bloginfo( 'url' );
		$site_name              = get_bloginfo( 'name' );
		$option_data            = $this->email_options['email_to_students']['quiz_completed'];
		$header                 = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header                 = apply_filters( 'student_quiz_completed_email_header', $header, $attempt_id );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{user_name}']            = tutor_utils()->get_user_name( $user );
		$replacable['{total_marks}']          = $attempt->total_marks;
		$replacable['{earned_marks}']         = $attempt->earned_marks;
		$replacable['{attempt_result}']       = $attempt_result;
		$replacable['{attempt_url}']          = tutor_utils()->tutor_dashboard_url() . 'my-quiz-attempts/?view_quiz_attempt_id=' . $attempt_id;
		// $replacable['{attempt_url}']		  = $site_url . '/dashboard-page/my-quiz-attempts/?view_quiz_attempt_id=' . $attempt_id;
		$replacable['{quiz_name}']       = $quiz_name;
		$replacable['{course_name}']     = $course_title;
		$replacable['{submission_time}'] = $submission_time_format;
		$replacable['{quiz_url}']        = "<a href='{$quiz_url}'>{$quiz_url}</a>";
		$replacable['{site_url}']        = $site_url;
		$replacable['{site_name}']       = $site_name;
		$replacable['{logo}']            = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']   = $option_data['heading'];
		$replacable['{email_message}']   = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                         = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_student_quiz_completed' );
		$email_tpl = apply_filters( 'tutor_email_tpl/quiz_completed', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );
		// die( $message );
		$this->send( $user->user_email, $subject, $message, $header );

	}

	public function quiz_finished_send_email_to_instructor( $attempt_id ) {
		$isEnable = tutor_utils()->get_option( 'email_to_teachers.student_submitted_quiz' );
		if ( ! $isEnable ) {
			return;
		}

		$attempt = tutor_utils()->get_attempt( $attempt_id );
		$attempt = tutor_utils()->get_attempt( $attempt_id );

		$earned_percentage = $attempt->earned_marks > 0 ? ( number_format( ( $attempt->earned_marks * 100 ) / $attempt->total_marks ) ) : 0;
		$passing_grade     = (int) tutor_utils()->get_quiz_option( $attempt->quiz_id, 'passing_grade', 0 );
		$attempt_result    = $earned_percentage >= $passing_grade ?
														'<span class="tutor-badge-label label-success">' . esc_attr( 'Pass' ) . '</span>' :
														'<span class="tutor-badge-label label-danger">' . esc_attr( 'Fail' ) . '</span>';

		$attempt_info           = tutor_utils()->quiz_attempt_info( $attempt_id );
		$submission_time        = tutor_utils()->avalue_dot( 'submission_time', $attempt_info );
		$submission_time        = $submission_time ? $submission_time : tutor_time();
		$quiz_id                = tutor_utils()->avalue_dot( 'comment_post_ID', $attempt );
		$quiz_name              = get_the_title( $quiz_id );
		$course                 = CourseModel::get_course_by_quiz( $quiz_id );
		$course_id              = tutor_utils()->avalue_dot( 'ID', $course );
		$course_title           = get_the_title( $course_id );
		$submission_time_format = date_i18n( get_option( 'date_format' ), $submission_time ) . ' ' . date_i18n( get_option( 'time_format' ), $submission_time );
		$attempt_url            = tutor_utils()->get_tutor_dashboard_page_permalink( 'quiz-attempts/quiz-reviews/?attempt_id=' . $attempt_id );
		$user                   = get_userdata( tutor_utils()->avalue_dot( 'user_id', $attempt ) );
		$teacher                = get_userdata( $course->post_author );
		$site_url               = get_bloginfo( 'url' );
		$site_name              = get_bloginfo( 'name' );
		$option_data            = $this->email_options['email_to_teachers']['student_submitted_quiz'];
		$header                 = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header                 = apply_filters( 'student_quiz_completed_to_instructor_email_header', $header, $attempt_id );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{user_name}']            = tutor_utils()->get_user_name( $teacher );
		$replacable['{total_marks}']          = $attempt->total_marks;
		$replacable['{earned_marks}']         = $attempt->earned_marks;
		$replacable['{attempt_result}']       = $attempt_result;
		$replacable['{student_name}']         = $user->display_name;
		$replacable['{quiz_name}']            = $quiz_name;
		$replacable['{course_name}']          = $course_title;
		$replacable['{submission_time}']      = $submission_time_format;
		$replacable['{quiz_review_url}']      = "<a href='{$attempt_url}'>{$attempt_url}</a>";
		$replacable['{site_url}']             = $site_url;
		$replacable['{attempt_url}']          = $site_url . '/wp-admin/admin.php?page=tutor_quiz_attempts&view_quiz_attempt_id=' . $attempt_id;
		$replacable['{site_name}']            = $site_name;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}']        = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                              = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_instructor_quiz_completed' );
		$email_tpl = apply_filters( 'tutor_email_tpl/quiz_completed/to_instructor', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$this->send( $teacher->user_email, $subject, $message, $header );

	}

	/**
	 * @param $enrol_id
	 * @param $status_to
	 *
	 * E-Mail to teacher when success enrol.
	 */
	public function course_enroll_email_to_teacher( $course_id, $student_id, $enrol_id, $status_to = 'completed' ) {
		$enroll_notification = tutor_utils()->get_option( 'email_to_teachers.a_student_enrolled_in_course' );

		if ( ! $enroll_notification || $status_to !== 'completed' ) {
			return;
		}

		$student            = get_userdata( $student_id );
		$course             = tutor_utils()->get_course_by_enrol_id( $enrol_id );
		$teacher            = get_userdata( $course->post_author );
		$enroll_time        = tutor_time();
		$enroll_time_format = date_i18n( get_option( 'date_format' ), $enroll_time ) . ' ' . date_i18n( get_option( 'time_format' ), $enroll_time );
		$profile_url        = tutor_utils()->profile_url( $student_id, false );
		$amount_data        = tutor_utils()->get_earning_sum( $teacher->ID );

		$total_amount  = $amount_data->balance;
		$earned_amount = $amount_data->instructor_amount;

		$site_url    = get_bloginfo( 'url' );
		$site_name   = get_bloginfo( 'name' );
		$option_data = $this->email_options['email_to_teachers']['a_student_enrolled_in_course'];
		$header      = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header      = apply_filters( 'to_instructor_course_enrolled_email_header', $header, $course->ID );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{user_name}']            = tutor_utils()->get_user_name( $teacher );
		$replacable['{student_username}']     = $student->display_name;
		$replacable['{student_email}']        = $student->user_email;
		$replacable['{profile_url}']          = $profile_url;
		$replacable['{dashboard_url}']        = tutor_utils()->get_tutor_dashboard_page_permalink();
		$replacable['{course_name}']          = $course->post_title;
		$replacable['{total_amount}']         = $total_amount;
		$replacable['{earned_amount}']        = $earned_amount;
		$replacable['{enroll_time}']          = $enroll_time_format;
		$replacable['{course_url}']           = get_the_permalink( $course->ID );
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}']        = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                              = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_instructor_course_enrolled' );
		$email_tpl = apply_filters( 'tutor_email_tpl/to_teacher_course_enrolled', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$this->send( $teacher->user_email, $subject, $message, $header );

	}

	/**
	 * @param $enrol_id
	 * @param $status_to
	 *
	 * E-Mail to student when success enrol.
	 */
	public function course_enroll_email_to_student( $course_id, $student_id, $enrol_id, $status_to = 'completed' ) {
		$enroll_notification = tutor_utils()->get_option( 'email_to_students.course_enrolled' );

		if ( ! $enroll_notification || $status_to !== 'completed' ) {
			return;
		}

		$student = get_userdata( $student_id );
		// if student not found return.
		if ( false === $student ) {
			return;
		}

		$course             = tutor_utils()->get_course_by_enrol_id( $enrol_id );
		$enroll_time        = tutor_time();
		$enroll_time_format = date_i18n( get_option( 'date_format' ), $enroll_time ) . ' ' . date_i18n( get_option( 'time_format' ), $enroll_time );
		$course_start_url   = tutor_utils()->get_course_first_lesson( $course_id );
		$site_url           = get_bloginfo( 'url' );
		$site_name          = get_bloginfo( 'name' );
		$option_data        = $this->email_options['email_to_students']['course_enrolled'];
		$header             = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header             = apply_filters( 'student_course_enrolled_email_header', $header, $enrol_id );

		// pr($student);die;

		$replacable['{testing_email_notice}'] = '';
		$replacable['{user_name}']            = tutor_utils()->get_user_name( $student );
		$replacable['{course_name}']          = $course->post_title;
		$replacable['{enroll_time}']          = $enroll_time_format;
		$replacable['{course_url}']           = get_the_permalink( $course->ID );
		$replacable['{course_start_url}']     = $course_start_url;
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{email_message}']        = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                              = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_student_course_enrolled' );
		$email_tpl = apply_filters( 'tutor_email_tpl/student_course_enrolled', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$this->send( $student->user_email, $subject, $message, $header );

	}


	public function tutor_after_add_question( $course_id, $comment_id ) {
		$enroll_notification = tutor_utils()->get_option( 'email_to_teachers.a_student_placed_question' );
		if ( ! $enroll_notification ) {
			return;
		}

		$user_id            = get_current_user_id();
		$student            = get_userdata( $user_id );
		$course             = get_post( $course_id );
		$teacher            = get_userdata( $course->post_author );
		$get_comment        = tutor_utils()->get_qa_question( $comment_id );
		$question           = $get_comment->comment_content;
		$question_title     = substr( $get_comment->comment_content, 0, 40 );
		$enroll_time        = tutor_time();
		$enroll_time_format = date_i18n( get_option( 'date_format' ), $enroll_time ) . ' ' . date_i18n( get_option( 'time_format' ), $enroll_time );
		$site_url           = get_bloginfo( 'url' );
		$site_name          = get_bloginfo( 'name' );
		$user_id            = get_current_user_id();
		$student            = get_userdata( $user_id );
		$course             = get_post( $course_id );
		$teacher            = get_userdata( $course->post_author );
		$option_data        = $this->email_options['email_to_teachers']['a_student_placed_question'];
		$header             = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header             = apply_filters( 'to_teacher_asked_question_by_student_email_header', $header, $course_id );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{user_name}']            = tutor_utils()->get_user_name( $teacher );
		$replacable['{student_username}']     = $student->display_name;
		$replacable['{course_name}']          = $course->post_title;
		$replacable['{course_url}']           = get_the_permalink( $course_id );
		$replacable['{enroll_time}']          = $enroll_time_format;
		$replacable['{question_title}']       = $question_title;
		$replacable['{question}']             = wpautop( stripslashes( $question ) );
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}']        = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                              = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_instructor_asked_question_by_student' );
		$email_tpl = apply_filters( 'tutor_email_tpl/to_teacher_asked_question_by_student', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$this->send( $teacher->user_email, $subject, $message, $header );

	}


	public function tutor_lesson_completed_email_after( $lesson_id ) {
		$course_completed_to_teacher = tutor_utils()->get_option( 'email_to_teachers.a_student_completed_lesson' );

		if ( ! $course_completed_to_teacher ) {
			return;
		}

		$site_url               = get_bloginfo( 'url' );
		$site_name              = get_bloginfo( 'name' );
		$user_id                = get_current_user_id();
		$student                = get_userdata( $user_id );
		$course_id              = tutor_utils()->get_course_id_by( 'lesson', $lesson_id );
		$lesson                 = get_post( $lesson_id );
		$course                 = get_post( $course_id );
		$teacher                = get_userdata( $course->post_author );
		$completion_time        = tutor_time();
		$completion_time_format = date_i18n( get_option( 'date_format' ), $completion_time ) . ' ' . date_i18n( get_option( 'time_format' ), $completion_time );
		$option_data            = $this->email_options['email_to_teachers']['a_student_completed_lesson'];
		$header                 = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header                 = apply_filters( 'student_lesson_completed_email_header', $header, $lesson_id );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{user_name}']            = tutor_utils()->get_user_name( $teacher );
		$replacable['{student_name}']         = $student->display_name;
		$replacable['{student_email}']        = $student->user_email;
		$replacable['{course_name}']          = $course->post_title;
		$replacable['{lesson_name}']          = $lesson->post_title;
		$replacable['{completion_time}']      = $completion_time_format;
		$replacable['{lesson_url}']           = get_the_permalink( $lesson_id );
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = isset( $option_data['footer_text'] ) ? $option_data['footer_text'] : '';

		$replacable['{email_message}'] = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                       = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_instructor_lesson_completed' );
		$email_tpl = apply_filters( 'tutor_email_tpl/lesson_completed', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$this->send( $teacher->user_email, $subject, $message, $header );

	}

	/**
	 * After instructor successfully signup
	 *
	 * @since 1.6.9
	 */
	public function tutor_new_instructor_signup( $user_id ) {

		$new_instructor_signup = tutor_utils()->get_option( 'email_to_admin.new_instructor_signup' );

		if ( ! $new_instructor_signup ) {
			return;
		}
		$instructor_review_url = add_query_arg(
			array(
				'page'       => 'tutor-instructors',
				'action'     => 'review',
				'instructor' => $user_id,
			),
			admin_url( 'admin.php' )
		);

		$instructor_id      = tutor_utils()->get_user_id( $user_id );
		$instructor         = get_userdata( $instructor_id );
		$site_url           = get_bloginfo( 'url' );
		$site_name          = get_bloginfo( 'name' );
		$signup_time        = tutor_time();
		$signup_time_format = date_i18n( get_option( 'date_format' ), $signup_time ) . ' ' . date_i18n( get_option( 'time_format' ), $signup_time );
		$admin_users        = get_users( array( 'role__in' => array( 'administrator' ) ) );
		$option_data        = $this->email_options['email_to_admin']['new_instructor_signup'];
		$header             = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header             = apply_filters( 'instructor_signup_email_header', $header, $instructor_id );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{instructor_name}']      = $instructor->display_name;
		$replacable['{review_url}']           = $instructor_review_url;
		$replacable['{instructor_email}']     = $instructor->user_email;
		$replacable['{signup_time}']          = $signup_time_format;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );

		$replacable['{email_message}'] = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                       = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_admin_new_instructor_signup' );
		$email_tpl = apply_filters( 'tutor_email_tpl/new_instructor_signup', ob_get_clean() );

		foreach ( $admin_users as $admin_user ) {
			$replacable['{user_name}'] = tutor_utils()->get_user_name( $admin_user );
			$message                   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );
			$this->send( $admin_user->user_email, $subject, $message, $header );
		}

		$this->instructor_application_received( $instructor );

	}

	private function instructor_application_received( $instructor ) {

		$send_received = tutor_utils()->get_option( 'email_to_teachers.instructor_application_received' );

		if ( ! $send_received ) {
			return;
		}

		$site_url    = get_bloginfo( 'url' );
		$site_name   = get_bloginfo( 'name' );
		$option_data = $this->email_options['email_to_teachers']['instructor_application_received'];
		$header      = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header      = apply_filters( 'instructor_application_received_email_header', $header, $instructor->ID );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{user_name}']            = tutor_utils()->get_user_name( $instructor );
		$replacable['{instructor_username}']  = $instructor->display_name;
		$replacable['{instructor_email}']     = $instructor->user_email;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}']        = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                              = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_instructor_become_application_received' );
		$email_tpl = apply_filters( 'tutor_email_tpl/instructor_application_received', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );
		// die($message);
		$this->send( $instructor->user_email, $subject, $message, $header );
	}


	/**
	 * After student successfully signup
	 *
	 * @since 1.6.9
	 */
	public function tutor_new_student_signup( $user_id ) {
		$new_student_signup = tutor_utils()->get_option( 'email_to_admin.new_student_signup' );

		if ( ! $new_student_signup ) {
			return;
		}
		$student_id         = tutor_utils()->get_user_id( $user_id );
		$student            = get_userdata( $student_id );
		$site_url           = get_bloginfo( 'url' );
		$site_name          = get_bloginfo( 'name' );
		$signup_time        = tutor_time();
		$signup_time_format = date_i18n( get_option( 'date_format' ), $signup_time ) . ' ' . date_i18n( get_option( 'time_format' ), $signup_time );
		$admin_users        = get_users( array( 'role__in' => array( 'administrator' ) ) );
		$option_data        = $this->email_options['email_to_admin']['new_student_signup'];
		$header             = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header             = apply_filters( 'student_signup_email_header', $header, $student_id );
		$profile_url        = tutor_utils()->profile_url( $student_id, false );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{student_name}']         = $student->display_name;
		$replacable['{student_email}']        = $student->user_email;
		$replacable['{signup_time}']          = $signup_time_format;
		$replacable['{profile_url}']          = $profile_url;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );

		$replacable['{email_message}'] = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                       = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();

		$this->tutor_load_email_template( 'to_admin_new_student_signup' );
		$email_tpl = apply_filters( 'tutor_email_tpl/new_student_signup', ob_get_clean() );

		foreach ( $admin_users as $admin_user ) {
			$replacable['{user_name}'] = tutor_utils()->get_user_name( $admin_user );
			$message                   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );
			$this->send( $admin_user->user_email, $subject, $message, $header );
		}

	}

	/**
	 * After new course submit for review
	 *
	 * @since 1.6.9
	 */
	public function tutor_course_pending( $post ) {

		if ( $post->post_type !== tutor()->course_post_type ) {
			return true;
		}

		$new_course_submitted = tutor_utils()->get_option( 'email_to_admin.new_course_submitted' );

		if ( ! $new_course_submitted ) {
			return;
		}

		$site_url              = get_bloginfo( 'url' );
		$site_name             = get_bloginfo( 'name' );
		$submitted_time        = tutor_time();
		$submitted_time_format = date_i18n( get_option( 'date_format' ), $submitted_time ) . ' ' . date_i18n( get_option( 'time_format' ), $submitted_time );
		$instructor_name       = get_the_author_meta( 'display_name', $post->post_author );
		$admin_users           = get_users( array( 'role__in' => array( 'administrator' ) ) );
		$option_data           = $this->email_options['email_to_admin']['new_course_submitted'];
		$header                = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header                = apply_filters( 'course_updated_email_header', $header, $post->ID );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{course_name}']          = $post->post_title;
		$replacable['{course_url}']           = get_the_permalink( $post->ID );
		$replacable['{course_edit_url}']      = get_edit_post_link( $post->ID );
		$replacable['{instructor_name}']      = $instructor_name;
		$replacable['{submitted_time}']       = $submitted_time_format;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );

		$replacable['{email_message}'] = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                       = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_admin_new_course_submitted_for_review' );
		$email_tpl = apply_filters( 'tutor_email_tpl/new_course_submitted', ob_get_clean() );

		foreach ( $admin_users as $admin_user ) {
			$replacable['{user_name}'] = tutor_utils()->get_user_name( $admin_user );
			$message                   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );
			$this->send( $admin_user->user_email, $subject, $message, $header );
		}

	}

	/**
	 * After new course published
	 *
	 * @since 1.6.9
	 */
	public function tutor_course_published( $post ) {

		if ( $post->post_type !== tutor()->course_post_type ) {
			return true;
		}

		$new_course_published = tutor_utils()->get_option( 'email_to_admin.new_course_published' );

		if ( ! $new_course_published ) {
			return;
		}

		$site_url              = get_bloginfo( 'url' );
		$site_name             = get_bloginfo( 'name' );
		$published_time        = tutor_time();
		$published_time_format = date_i18n( get_option( 'date_format' ), $published_time ) . ' ' . date_i18n( get_option( 'time_format' ), $published_time );
		$instructor_name       = get_the_author_meta( 'display_name', $post->post_author );
		$admin_users           = get_users( array( 'role__in' => array( 'administrator' ) ) );
		$option_data           = $this->email_options['email_to_admin']['new_course_published'];
		$header                = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header                = apply_filters( 'course_updated_email_header', $header, $post->ID );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{course_name}']          = $post->post_title;
		$replacable['{course_url}']           = get_the_permalink( $post->ID );
		$replacable['{instructor_name}']      = $instructor_name;
		$replacable['{published_time}']       = $published_time_format;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );

		$replacable['{email_message}'] = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                       = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_admin_new_course_published' );
		$email_tpl = apply_filters( 'tutor_email_tpl/new_course_published', ob_get_clean() );

		foreach ( $admin_users as $admin_user ) {
			$replacable['{user_name}'] = tutor_utils()->get_user_name( $admin_user );
			$message                   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );
			$this->send( $admin_user->user_email, $subject, $message, $header );
		}

	}

	/**
	 * After course updated/edited
	 *
	 * @since 1.6.9
	 */
	public function tutor_course_updated( $course_id, $course, $update = false ) {
		$course_updated = tutor_utils()->get_option( 'email_to_admin.course_updated' );
		$tutor_ajax     = Input::post( 'tutor_ajax_action' );
		$auto_save      = $tutor_ajax == 'tutor_course_builder_draft_save';

		if ( ! $course_updated || ! $update || $course->post_status != 'publish' || $auto_save ) {
			return;
		}

		if ( 'Publish' === Input::post( 'original_publish' ) ) {
			return;
		}

		$site_url            = get_bloginfo( 'url' );
		$site_name           = get_bloginfo( 'name' );
		$updated_time        = tutor_time();
		$updated_time_format = date_i18n( get_option( 'date_format' ), $updated_time ) . ' ' . date_i18n( get_option( 'time_format' ), $updated_time );
		$instructor_name     = get_the_author_meta( 'display_name', $course->post_author );
		$admin_users         = get_users( array( 'role__in' => array( 'administrator' ) ) );
		$option_data         = $this->email_options['email_to_admin']['course_updated'];

		$header = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header = apply_filters( 'course_updated_email_header', $header, $course_id );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_title}']           = $site_name;
		$replacable['{course_name}']          = $course->post_title;
		$replacable['{course_url}']           = get_the_permalink( $course_id );
		$replacable['{instructor_name}']      = $instructor_name;
		$replacable['{updated_time}']         = $updated_time_format;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );

		$replacable['{email_message}'] = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                       = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_admin_course_updated' );
		$email_tpl = apply_filters( 'tutor_email_tpl/course_updated', ob_get_clean() );

		foreach ( $admin_users as $admin_user ) {
			$replacable['{user_name}'] = tutor_utils()->get_user_name( $admin_user );
			$message                   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );
			$this->send( $admin_user->user_email, $subject, $message, $header );
		}

	}

	/**
	 * After assignment submitted
	 *
	 * @since 1.6.9
	 */
	public function tutor_assignment_after_submitted( $assignment_submit_id ) {
		// get post id by comment
		$assignment_post_id = $this->get_comment_post_id_by_comment_id( $assignment_submit_id );

		// get assignment autor and course autor
		$authors = $this->get_assignment_and_course_authors( $assignment_post_id );

		$student_submitted_assignment = tutor_utils()->get_option( 'email_to_teachers.student_submitted_assignment' );

		if ( ! $student_submitted_assignment ) {
			return;
		}

		$submitted_assignment = tutor_utils()->get_assignment_submit_info( $assignment_submit_id );
		$student_name         = get_the_author_meta( 'display_name', $submitted_assignment->user_id );
		$course_name          = get_the_title( $submitted_assignment->comment_parent );
		$course_url           = get_the_permalink( $submitted_assignment->comment_parent );
		$author_id            = get_post_field( 'post_author', $submitted_assignment->comment_parent );

		$instructor_name = tutor_utils()->get_user_name( get_userdata( $author_id ) );
		$assignment_name = get_the_title( $submitted_assignment->comment_post_ID );
		$submitted_url   = tutor_utils()->get_tutor_dashboard_page_permalink( 'assignments/submitted' );
		$review_link     = esc_url( $submitted_url . '?assignment=' . $submitted_assignment->comment_post_ID );
		$site_url        = get_bloginfo( 'url' );
		$site_name       = get_bloginfo( 'name' );
		$option_data     = $this->email_options['email_to_teachers']['student_submitted_assignment'];
		$header          = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header          = apply_filters( 'student_submitted_assignment_email_header', $header, $assignment_submit_id );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{student_name}']         = $student_name;
		$replacable['{course_name}']          = $course_name;
		$replacable['{user_name}']            = $instructor_name;
		$replacable['{assignment_name}']      = $assignment_name;
		$replacable['{review_link}']          = $review_link;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{before_button}']        = $option_data['before_button'];
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}']        = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                              = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start(); // pending to add user_name
		$this->tutor_load_email_template( 'to_instructor_student_submitted_assignment' );
		$email_tpl = apply_filters( 'tutor_email_tpl/student_submitted_assignment', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$admin_emails = $author_emails = $to_emails = array();

		$admin_users = get_users( array( 'role__in' => array( 'administrator' ) ) );

		foreach ( $admin_users as $admin_user ) {
			$admin_emails[] = $admin_user->user_email;
		}

		foreach ( $authors as $author ) {
			$author_emails[] = $author;
		}

		$to_emails = array_unique( array_merge( $admin_emails, $author_emails ) );

		$this->send( $to_emails, $subject, $message, $header );
	}

	/**
	 * After assignment evaluate
	 *
	 * @since 1.6.9
	 */
	public function tutor_after_assignment_evaluate( $assignment_submit_id ) {

		$assignment_graded = tutor_utils()->get_option( 'email_to_students.assignment_graded' );

		if ( ! $assignment_graded ) {
			return;
		}

		$site_url             = get_bloginfo( 'url' );
		$site_name            = get_bloginfo( 'name' );
		$submitted_assignment = tutor_utils()->get_assignment_submit_info( $assignment_submit_id );
		$student_email        = get_the_author_meta( 'user_email', $submitted_assignment->user_id );
		$student_name         = tutor_utils()->get_user_name( get_userdata( $submitted_assignment->user_id ) );
		$course_name          = get_the_title( $submitted_assignment->comment_parent );
		$course_url           = get_the_permalink( $submitted_assignment->comment_parent );
		$assignment_max_mark  = tutor_utils()->get_assignment_option( $submitted_assignment->comment_post_ID, 'total_mark' );
		$assignment_name      = get_the_title( $submitted_assignment->comment_post_ID );
		$assignment_score     = get_comment_meta( $assignment_submit_id, 'assignment_mark', true );
		$assignment_comment   = get_comment_meta( $assignment_submit_id, 'instructor_note', true );
		$option_data          = $this->email_options['email_to_students']['assignment_graded'];
		$header               = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header               = apply_filters( 'assignment_evaluate_email_header', $header, $assignment_submit_id );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{course_name}']          = $course_name;
		$replacable['{course_url}']           = $course_url;
		$replacable['{user_name}']            = $student_name;
		$replacable['{assignment_name}']      = $assignment_name;
		$replacable['{assignment_max_mark}']  = $assignment_max_mark;
		$replacable['{assignment_score}']     = $assignment_score;
		$replacable['{assignment_comment}']   = $assignment_comment;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}']        = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                              = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_student_assignment_evaluate' );
		$email_tpl = apply_filters( 'tutor_email_tpl/assignment_evaluate', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$this->send( $student_email, $subject, $message, $header );

	}

	/**
	 * After remove student from course
	 *
	 * @since 1.6.9
	 */
	public function tutor_student_remove_from_course( $enrol_id ) {
		$remove_from_course = tutor_utils()->get_option( 'email_to_students.remove_from_course' );

		if ( ! $remove_from_course ) {
			return;
		}

		$enrolment = tutor_utils()->get_enrolment_by_enrol_id( $enrol_id );
		if ( ! $enrolment ) {
			return;
		}

		$site_url      = get_bloginfo( 'url' );
		$site_name     = get_bloginfo( 'name' );
		$course_name   = $enrolment->course_title;
		$course_url    = get_the_permalink( $enrolment->course_id );
		$student_email = $enrolment->user_email;
		$student_id    = $enrolment->ID;
		$option_data   = $this->email_options['email_to_students']['remove_from_course'];

		$header = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header = apply_filters( 'remove_from_course_email_header', $header, $enrol_id );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{user_name}']            = tutor_utils()->get_user_name( get_userdata( $student_id ) );
		$replacable['{course_name}']          = $course_name;
		$replacable['{course_url}']           = $course_url;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}']        = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                              = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_student_remove_from_course' );
		$email_tpl = apply_filters( 'tutor_email_tpl/remove_from_course', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$this->send( $student_email, $subject, $message, $header );

	}

	/**
	 * Enrollment After Expired
	 *
	 * @since 1.8.1
	 */
	public function tutor_enrollment_after_expired( $enrol_id ) {
		$enrollment_expired = tutor_utils()->get_option( 'email_to_students.enrollment_expired' );

		if ( ! $enrollment_expired ) {
			return;
		}

		$enrolment = tutor_utils()->get_enrolment_by_enrol_id( $enrol_id );
		if ( ! $enrolment ) {
			return;
		}

		$site_url      = get_bloginfo( 'url' );
		$site_name     = get_bloginfo( 'name' );
		$course_name   = $enrolment->course_title;
		$course_url    = get_the_permalink( $enrolment->course_id );
		$student_name  = tutor_utils()->get_user_name( get_userdata( $enrolment->ID ) );
		$student_email = $enrolment->user_email;
		$option_data   = $this->email_options['email_to_students']['enrollment_expired'];
		$header        = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header        = apply_filters( 'enrollment_expired_email_header', $header, $enrol_id );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{user_name}']            = $student_name;
		$replacable['{course_name}']          = $course_name;
		$replacable['{course_url}']           = $course_url;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = $this->get_replaced_text( isset( $option_data['footer_text'] ) ? $option_data['footer_text'] : '', array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}']        = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                              = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_student_enrollment_expired' );
		$email_tpl = apply_filters( 'tutor_email_tpl/to_student_enrollment_expired', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$this->send( $student_email, $subject, $message, $header );

	}

	/**
	 * After save new announcement
	 *
	 * @since 1.6.9
	 */
	public function tutor_announcements_notify_students( $announcement_id, $announcement, $action_type = '' ) {

		$new_announcement_posted = tutor_utils()->get_option( 'email_to_students.new_announcement_posted' );
		$announcement_updated    = tutor_utils()->get_option( 'email_to_students.announcement_updated' );

		if ( ! $new_announcement_posted && ! $announcement_updated ) {
			return;
		}

		$site_url             = get_bloginfo( 'url' );
		$site_name            = get_bloginfo( 'name' );
		$course_name          = get_the_title( $announcement->post_parent );
		$course_url           = get_the_permalink( $announcement->post_parent );
		$announcement_title   = $announcement->post_title;
		$announcement_content = $announcement->post_content;
		$announcement_author  = $announcement->post_author;
		$announcement_date    = $announcement->post_date;
		$author_fullname      = get_the_author_meta( 'display_name', $announcement_author );

		$option_data_create = $this->email_options['email_to_students']['new_announcement_posted'];
		$option_data_update = $this->email_options['email_to_students']['announcement_updated'];
		$header             = 'Content-Type: ' . $this->get_content_type() . "\r\n";

		$replacable['{author_fullname}']      = $author_fullname;
		$replacable['{testing_email_notice}'] = '';
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{course_name}']          = $course_name;
		$replacable['{course_url}']           = $course_url;
		$replacable['{announcement_title}']   = $announcement_title;
		$replacable['{announcement_content}'] = $announcement_content;
		$replacable['{announcement_date}']    = $announcement_date;

		$enrolled_students = tutor_utils()->get_students_all_data_by_course_id( $announcement->post_parent );
		// pr($enrolled_students);
		foreach ( $enrolled_students as $enrolled_student ) {
			$replacable['{user_name}'] = tutor_utils()->get_user_name( get_userdata( $enrolled_student->ID ) );

			if ( 'create' == $action_type ) {
				if ( ! $new_announcement_posted ) {
					return;
				}

				$replacable['{logo}']          = isset( $option_data_create['logo'] ) ? $option_data_create['logo'] : $this->email_logo;
				$replacable['{email_heading}'] = $this->get_replaced_text( $option_data_create['heading'], array_keys( $replacable ), array_values( $replacable ) );
				$replacable['{footer_text}']   = $option_data_create['footer_text'];
				$replacable['{email_message}'] = $this->get_replaced_text( $this->prepare_message( $option_data_create['message'] ), array_keys( $replacable ), array_values( $replacable ) );
				$subject                       = $this->get_replaced_text( $option_data_create['subject'], array_keys( $replacable ), array_values( $replacable ) );
				$template                      = 'to_student_new_announcement_posted';
			} elseif ( 'update' == $action_type ) {
				if ( ! $announcement_updated ) {
					return;
				}

				$replacable['{logo}']          = isset( $option_data_update['logo'] ) ? $option_data_update['logo'] : $this->email_logo;
				$replacable['{email_heading}'] = $this->get_replaced_text( $option_data_update['heading'], array_keys( $replacable ), array_values( $replacable ) );
				$replacable['{footer_text}']   = $option_data_update['footer_text'];
				$replacable['{email_message}'] = $this->get_replaced_text( $this->prepare_message( $option_data_update['message'] ), array_keys( $replacable ), array_values( $replacable ) );
				$subject                       = $this->get_replaced_text( $option_data_update['subject'], array_keys( $replacable ), array_values( $replacable ) );
				$template                      = 'to_student_announcement_updated';
			}

			ob_start();
			$this->tutor_load_email_template( $template );
			$email_tpl = apply_filters( 'tutor_email_tpl/' . $template, ob_get_clean() );
			$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );
			// die($message);

			$this->send( $enrolled_student->user_email, $subject, $message, $header );
		}

	}


	/**
	 * Send mail to student after question answered
	 *
	 * @since 2.1.8
	 *
	 * Send email to all who are connected to the question thread
	 *
	 * @param object $student_details  student info who opened the thread.
	 * @param array  $reply_details  reply details.
	 * @param object $question_details  question details.
	 * @param object $course  course details.
	 *
	 * @return void
	 */
	public function question_answered_by_instructor( object $student_details, array $reply_details, object $question_details, object $course ) {
		$after_question_answered = tutor_utils()->get_option( 'email_to_students.after_question_answered' );

		if ( ! $after_question_answered ) {
			return;
		}

		// Select all user who are connected with the thread.
		$users = self::get_thread_users( $question_details, $reply_details );

		$replier       = get_userdata( $reply_details['user_id'] );
		$replier_name  = isset( $reply_details['user_id'] ) ? tutor_utils()->display_name( $reply_details['user_id'] ) : '';
		$replier_email = $replier->user_email;

		// Remove replier from users list.
		if ( is_array( $users ) && count( $users ) ) {
			$users = array_filter(
				$users,
				function( $user ) use ( $replier_email ) {
					if ( $user->user_email !== $replier_email ) {
						return $user;
					}
				}
			);
		}

		$site_url    = get_bloginfo( 'url' );
		$site_name   = get_bloginfo( 'name' );
		$option_data = $this->email_options['email_to_students']['after_question_answered'];
		$header      = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header      = apply_filters( 'question_answered_email_header', $header, $reply_details );

		$subject = "{$replier_name} replied to this question";

		$is_replier_instructor = user_can( $reply_details['user_id'], tutor()->instructor_role );
		if ( $is_replier_instructor ) {
			$subject = 'The instructor has replied to this question';
		}

		$email_heading = 'Q&A message answered';

		// Send mail to all users who are on the reply thread.
		foreach ( $users as $user ) {
			$receiver_email = $user->user_email;
			$receiver_name  = tutor_utils()->display_name( $user->ID );
			// get instructor info.
			$replacable['{testing_email_notice}'] = '';
			$replacable['{answer}']               = $reply_details['comment_content'];
			$replacable['{answer_by}']            = $replier_name;
			$replacable['{user_name}']            = $receiver_name;

			$replacable['{answer_date}']       = $reply_details['comment_date'];
			$replacable['{question}']          = $question_details->comment_content;
			$replacable['{course_name}']       = $course->post_title;
			$replacable['{course_url}']        = get_the_permalink( $course->ID );
			$replacable['{site_url}']          = $site_url;
			$replacable['{site_name}']         = $site_name;
			$replacable['{logo}']              = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
			$replacable['{email_heading}']     = $email_heading;
			$replacable['{instructor_avatar}'] = get_avatar( $reply_details['user_id'] );
			$replacable['{before_button}']     = 'Please click on this link to reply to the question.';

			if ( isset( $option_data['before_button'] ) ) {
				$replacable['{before_button}'] = $option_data['before_button'];
			}

			if ( isset( $option_data['heading'] ) ) {
				$replacable['{email_heading}'] = $option_data['heading'];
			}

			if ( isset( $option_data['footer_text'] ) ) {
				$replacable['{footer_text}'] = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
			}

			if ( $is_replier_instructor && isset( $option_data['message'] ) ) {
				$replacable['{email_message}'] = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
			} else {
				$replacable['{email_message}'] = $replier_name . ' has replied the question on the course ';
			}

			if ( $is_replier_instructor && isset( $option_data['subject'] ) ) {
				$subject = $option_data['subject'];
			}

			ob_start();
			$this->tutor_load_email_template( 'to_student_question_answered' );
			$email_tpl = apply_filters( 'tutor_email_tpl/question_answered', ob_get_clean() );
			$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

			$this->send( $receiver_email, $subject, $message, $header );
		}
	}

	/**
	 * Send email to instrcutor after asked question
	 *
	 * send email to student if instructor reply
	 *
	 * @since v2.0.2
	 *
	 * @param array $question_details
	 *
	 * @return void
	 */
	public function tutor_after_asked_question( array $question_details ) {
		if ( ! is_array( $question_details ) ) {
			return;
		}

		$is_enabled_email_to_teacher = tutor_utils()->get_option( 'email_to_teachers.a_student_placed_question' );
		$is_enabled_email_to_student = tutor_utils()->get_option( 'email_to_students.after_question_answered' );

		if ( ! $is_enabled_email_to_teacher && ! $is_enabled_email_to_student ) {
			return;
		}

		$course      = get_post( $question_details['comment_post_ID'] );
		$course_name = get_the_title( $course->ID );

		// get instructor info.
		$instructor_data = get_userdata( $course->post_author );
		$instructor_name = tutor_utils()->get_user_name( $instructor_data );

		$course_url = get_the_permalink( $course->ID );

		$site_url    = get_bloginfo( 'url' );
		$site_name   = get_bloginfo( 'name' );
		$option_data = $this->email_options['email_to_teachers']['a_student_placed_question'];
		$header      = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header      = apply_filters( 'tutor_email_header_to_instructor_asked_question_by_student', $header );

		// get student info.
		$student_details = get_userdata( $question_details['user_id'] );
		$student_name    = tutor_utils()->get_user_name( $student_details );

		$subject = "New Question from $student_name on $course_name ";
		$to      = $instructor_data->user_email;

		// if has comment parent then it is reply thread hence send mail to student.
		if ( $question_details['comment_parent'] ) {
			$parent_question_details = get_comment( $question_details['comment_parent'] );
			if ( false === is_a( $parent_question_details, 'WP_Comment' ) ) {
				return;
			}

			// if student reply himself then don't need to send mail.
			if ( $parent_question_details->user_id === $question_details['user_id'] ) {
				return;
			}

			// mail to student.
			$student_details = get_userdata( $parent_question_details->user_id );
			$this->question_answered_by_instructor( $student_details, $question_details, $parent_question_details, $course );

			return;
		}

		// prepare placeholder value.
		$replacable['{testing_email_notice}'] = '';
		$replacable['{student_name}']         = $student_name;
		$replacable['{question_date}']        = $question_details['comment_date'];
		$replacable['{user_name}']            = $instructor_name;
		$replacable['{question_title}']       = $question_details['comment_content'];
		$replacable['{course_name}']          = $course_name;
		$replacable['{course_url}']           = $course_url;
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{student_avatar}']       = get_avatar( $question_details['user_id'] );

		if ( isset( $option_data['footer_text'] ) ) {
			$replacable['{footer_text}'] = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
		}

		if ( isset( $option_data['message'] ) ) {
			$replacable['{email_message}'] = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		}

		if ( isset( $option_data['subject'] ) ) {
			$subject = $subject;
		}

		ob_start();
		$this->tutor_load_email_template( 'to_instructor_asked_question_by_student' );
		$email_tpl = apply_filters( 'tutor_email_student_asked_question', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$this->send( $to, $subject, $message, $header );
	}

	/**
	 * After quiz attempts feedback
	 *
	 * @since 1.6.9
	 */
	public function feedback_submitted_for_quiz_attempt( $attempt_id ) {
		$feedback_submitted_for_quiz = tutor_utils()->get_option( 'email_to_students.feedback_submitted_for_quiz' );

		if ( ! $feedback_submitted_for_quiz ) {
			return;
		}

		$attempt             = tutor_utils()->get_attempt( $attempt_id );
		$quiz_title          = get_post_field( 'post_title', $attempt->quiz_id );
		$course              = get_post( $attempt->course_id );
		$instructor_name     = get_the_author_meta( 'display_name', $course->post_author );
		$instructor_feedback = get_post_meta( $attempt_id, 'instructor_feedback', true );
		$user_email          = get_the_author_meta( 'user_email', $attempt->user_id );
		$student_fullname    = tutor_utils()->get_user_name( get_userdata( $attempt->user_id ) );
		$site_url            = get_bloginfo( 'url' );
		$site_name           = get_bloginfo( 'name' );
		$option_data         = $this->email_options['email_to_students']['feedback_submitted_for_quiz'];
		$block_heading       = $option_data['block_heading'];
		$block_content       = $option_data['block_content'];
		$header              = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header              = apply_filters( 'feedback_submitted_for_quiz_email_header', $header, $attempt_id );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{quiz_name}']            = $quiz_title;
		$replacable['{total_marks}']          = $attempt->total_marks;
		$replacable['{earned_marks}']         = $attempt->earned_marks;
		$replacable['{course_name}']          = $course->post_title;
		$replacable['{instructor_name}']      = $instructor_name;
		$replacable['{user_name}']            = $student_fullname;
		$replacable['{instructor_feedback}']  = $instructor_feedback;
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{block_heading}']        = $block_heading;
		$replacable['{block_content}']        = $block_content;
		$replacable['{review_url}']           = tutor_utils()->tutor_dashboard_url() . 'my-quiz-attempts/?view_quiz_attempt_id=' . $attempt_id;
		// $replacable['{review_url}']        = $site_url . '/dashboard-page/my-quiz-attempts/?view_quiz_attempt_id=' . $attempt_id;
		$replacable['{logo}']          = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}'] = $option_data['heading'];
		$replacable['{footer_text}']   = $this->get_replaced_text( isset( $option_data['footer_text'] ) ? $option_data['footer_text'] : '', array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}'] = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                       = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_student_feedback_submitted_for_quiz' );
		$email_tpl = apply_filters( 'tutor_email_tpl/feedback_submitted_for_quiz', ob_get_clean() );
		$message   = $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) );

		$this->send( $user_email, $subject, $message, $header );

	}

	/**
	 * After course completed
	 *
	 * @since 1.6.9
	 */
	public function tutor_course_complete_after( $course_id ) {
		$rate_course_and_instructor = tutor_utils()->get_option( 'email_to_students.rate_course_and_instructor' );

		if ( ! $rate_course_and_instructor ) {
			return;
		}

		$site_url         = get_bloginfo( 'url' );
		$site_name        = get_bloginfo( 'name' );
		$course           = get_post( $course_id );
		$course_url       = get_the_permalink( $course_id );
		$instructor_url   = tutor_utils()->profile_url( $course->post_author, true );
		$user_id          = get_current_user_id();
		$user_email       = get_the_author_meta( 'user_email', $user_id );
		$student_fullname = tutor_utils()->get_user_name( get_userdata( $user_id ) );
		$option_data      = $this->email_options['email_to_students']['rate_course_and_instructor'];
		$header           = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header           = apply_filters( 'rate_course_and_instructor_email_header', $header, $course_id );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{user_name}']            = $student_fullname;
		$replacable['{course_name}']          = $course->post_title;
		$replacable['{course_url}']           = $course_url;
		$replacable['{instructor_url}']       = $instructor_url;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}']        = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                              = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_student_rate_course_and_instructor' );
		$email_tpl = apply_filters( 'tutor_email_tpl/rate_course_and_instructor', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$this->send( $user_email, $subject, $message, $header );

	}


	public function get_comment_post_id_by_comment_id( $comment_id ) {

		global $wpdb;
		$comment_table   = $wpdb->prefix . 'comments';
		$query           = $wpdb->get_row(
			$wpdb->prepare( "SELECT comment_post_ID FROM $comment_table WHERE comment_ID = %d", $comment_id )
		);
		$comment_post_ID = $query->comment_post_ID;

		return $comment_post_ID;
	}


	/*
	*require assignment post id
	return authors of assignment and course author's email (unique)
	*/
	public function get_assignment_and_course_authors( $assignment_post_id ) {
		// get course id of assignment
		$course_id = tutor_utils()->get_course_id_by( 'assignment', $assignment_post_id );

		$course_author     = $this->get_author_by_post_id( $course_id );
		$assignment_author = $this->get_author_by_post_id( $assignment_post_id );

		$authors = array();
		if ( $course_author !== false ) {
			$authors[] = $course_author->user_email;
		}
		if ( $assignment_author !== false ) {
			$authors[] = $assignment_author->user_email;
		}

		return array_unique( $authors );
	}


	public function get_author_by_post_id( $post_id ) {

		global $wpdb;
		$user_table = $wpdb->prefix . 'users';
		$post_table = $wpdb->prefix . 'posts';
		// get author for associate course
		$author = $wpdb->get_row(
			$wpdb->prepare( "SELECT u.ID,u.user_email FROM $user_table u JOIN $post_table p ON p.post_author = u.ID WHERE p.ID = %d", $post_id )
		);
		return $author ? $author : false;
	}

	public function instructor_application_approved( $instructor_id ) {

		$send_accepted = tutor_utils()->get_option( 'email_to_teachers.instructor_application_accepted' );
		if ( ! $send_accepted ) {
			return;
		}

		$user_info   = get_userdata( $instructor_id );
		$site_url    = get_bloginfo( 'url' );
		$site_name   = get_bloginfo( 'name' );
		$option_data = $this->email_options['email_to_teachers']['instructor_application_accepted'];
		$header      = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header      = apply_filters( 'instructor_application_approved_email_header', $header, $user_info->ID );

		$replacable['{dashboard_url}']        = tutor_utils()->get_tutor_dashboard_page_permalink();
		$replacable['{testing_email_notice}'] = '';
		$replacable['{instructor_username}']  = $user_info->display_name;
		$replacable['{user_name}']            = tutor_utils()->get_user_name( $user_info );
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}']        = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                              = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_instructor_become_application_approved' );
		$email_tpl = apply_filters( 'tutor_email_tpl/instructor_application_approved', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$this->send( $user_info->user_email, $subject, $message, $header );

	}

	public function instructor_application_rejected( $instructor_id ) {

		$send_rejected = tutor_utils()->get_option( 'email_to_teachers.instructor_application_rejected' );
		if ( ! $send_rejected ) {
			return;
		}

		$user_info   = get_userdata( $instructor_id );
		$site_url    = get_bloginfo( 'url' );
		$site_name   = get_bloginfo( 'name' );
		$option_data = $this->email_options['email_to_teachers']['instructor_application_rejected'];
		$header      = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header      = apply_filters( 'instructor_application_rejected_email_header', $header, $user_info->ID );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{instructor_username}']  = $user_info->display_name;
		$replacable['{user_name}']            = tutor_utils()->get_user_name( $user_info );
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}']        = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                              = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_instructor_become_application_rejected' );
		$email_tpl = apply_filters( 'tutor_email_tpl/instructor_application_rejected', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$this->send( $user_info->user_email, $subject, $message, $header );

	}


	private function get_instructor_by_witdrawal( $withdrawal_id ) {

		global $wpdb;

		$user_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM {$wpdb->prefix}tutor_withdraws WHERE withdraw_id = %d", $withdrawal_id ) );

		return get_userdata( $user_id );
	}

	public function withdrawal_request_approved( $withdrawal_id ) {

		$option_status = tutor_utils()->get_option( 'email_to_teachers.withdrawal_request_approved' );
		if ( ! $option_status ) {
			return;
		}

		$instructor = $this->get_instructor_by_witdrawal( $withdrawal_id );

		$withdrawal            = $this->get_witdrawal_by_id( $withdrawal_id );
		$withdraw_method       = maybe_unserialize( $withdrawal->method_data )['withdraw_method_name'];
		$approve_time          = $withdrawal->created_at;
		$withdraw_approve_time = date_i18n( get_option( 'date_format' ), $approve_time ) . ' ' . date_i18n( get_option( 'time_format' ), $approve_time );
		$withdraw_amount       = $withdrawal->amount;
		$currency              = get_option( 'woocommerce_currency' );

		$total_amount = tutor_utils()->get_earning_sum( $instructor->ID )->balance;

		$site_url    = get_bloginfo( 'url' );
		$site_name   = get_bloginfo( 'name' );
		$option_data = $this->email_options['email_to_teachers']['withdrawal_request_approved'];
		$header      = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header      = apply_filters( 'withdrawal_request_approved_email_header', $header, $withdrawal_id );

		$replacable['{testing_email_notice}']  = '';
		$replacable['{instructor_username}']   = $instructor->display_name;
		$replacable['{admin_user}']            = wp_get_current_user()->display_name;
		$replacable['{user_name}']             = tutor_utils()->get_user_name( $instructor );
		$replacable['{withdraw_amount}']       = $withdraw_amount . ' ' . $currency;
		$replacable['{withdraw_method_name}']  = $withdraw_method;
		$replacable['{withdraw_approve_time}'] = $withdraw_approve_time;
		$replacable['{total_amount}']          = $total_amount . ' ' . $currency;
		$replacable['{site_url}']              = $site_url;
		$replacable['{site_name}']             = $site_name;
		$replacable['{logo}']                  = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']         = $option_data['heading'];
		$replacable['{footer_text}']           = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}']         = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                               = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_instructor_withdrawal_request_approved' );
		$email_tpl = apply_filters( 'tutor_email_tpl/withdrawal_request_approved', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$this->send( $instructor->user_email, $subject, $message, $header );

	}

	public function withdrawal_request_rejected( $withdrawal_id ) {

		$instructor    = $this->get_instructor_by_witdrawal( $withdrawal_id );
		$option_status = tutor_utils()->get_option( 'email_to_teachers.withdrawal_request_rejected' );
		if ( ! $option_status ) {
			return;
		}

		$site_url             = get_bloginfo( 'url' );
		$site_name            = get_bloginfo( 'name' );
		$option_data          = $this->email_options['email_to_teachers']['withdrawal_request_rejected'];
		$withdrawal           = $this->get_witdrawal_by_id( $withdrawal_id );
		$withdraw_method      = maybe_unserialize( $withdrawal->method_data )['withdraw_method_name'];
		$reject_time          = $withdrawal->created_at;
		$withdraw_reject_time = date_i18n( get_option( 'date_format' ), $reject_time ) . ' ' . date_i18n( get_option( 'time_format' ), $reject_time );

		$header = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header = apply_filters( 'withdrawal_request_rejected_email_header', $header, $withdrawal_id );
		// $this->send( $instructor->user_email, 'subject', json_encode( $withdraw_method ), $header );
		// die;
		$replacable['{testing_email_notice}'] = '';
		$replacable['{admin_user}']           = wp_get_current_user()->display_name;
		$replacable['{instructor_username}']  = $instructor->display_name;
		$replacable['{withdraw_amount}']      = $withdrawal->amount;
		$replacable['{withdraw_method_name}'] = $withdraw_method;
		$replacable['{withdraw_reject_time}'] = $withdraw_reject_time;
		$replacable['{user_name}']            = tutor_utils()->get_user_name( $instructor );
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}']        = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                              = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_instructor_withdrawal_request_rejected' );
		$email_tpl = apply_filters( 'tutor_email_tpl/withdrawal_request_rejected', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$this->send( $instructor->user_email, $subject, $message, $header );

	}

	private function get_witdrawal_by_id( $withdrawal_id ) {

		global $wpdb;

		$withdraw_request = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}tutor_withdraws WHERE withdraw_id = %d", $withdrawal_id ) );

		return $withdraw_request;
	}

	public function withdrawal_request_placed( $withdrawal_id ) {

		$admin_withdrawal_request_status  = tutor_utils()->get_option( 'email_to_admin.new_withdrawal_request' );
		$teacher_withdrawl_request_status = tutor_utils()->get_option( 'email_to_teachers.withdrawal_request_received' );

		if ( ! $admin_withdrawal_request_status && ! $teacher_withdrawl_request_status ) {
			return;
		}

		$instructor      = $this->get_instructor_by_witdrawal( $withdrawal_id );
		$withdraw        = $this->get_witdrawal_by_id( $withdrawal_id );
		$withdraw_amount = $withdraw->amount;

		// $admin_email = get_option( 'admin_email' );
		$admin_users = get_users( array( 'role__in' => array( 'administrator' ) ) );

		$approved_url = add_query_arg(
			array(
				'page'        => 'tutor_withdraw_requests',
				'action'      => 'approve',
				'withdraw_id' => $withdrawal_id,
			),
			admin_url( 'admin.php' )
		);
		$rejected_url = add_query_arg(
			array(
				'page'        => 'tutor_withdraw_requests',
				'action'      => 'reject',
				'withdraw_id' => $withdrawal_id,
			),
			admin_url( 'admin.php' )
		);

		$subject  = __( 'New withdrawal request from ' . $instructor->display_name . ' for ' . $instructor->amount, 'tutor-pro' );
		$currency = get_option( 'woocommerce_currency' );

		$site_url    = get_bloginfo( 'url' );
		$site_name   = get_bloginfo( 'name' );
		$option_data = $this->email_options['email_to_admin']['new_withdrawal_request'];

		$header = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header = apply_filters( 'new_withdrawal_request_email_header', $header, $withdrawal_id );

		$replacable['{testing_email_notice}'] = '';
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{instructor_username}']  = $instructor->display_name;
		$replacable['{instructor_email}']     = $instructor->user_email;
		$replacable['{withdraw_amount}']      = $withdraw_amount . ' ' . $currency;
		$replacable['{approved_url}']         = $approved_url;
		$replacable['{rejected_url}']         = $rejected_url;
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}']        = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                              = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_admin_new_withdrawal_request' );
		$email_tpl = apply_filters( 'tutor_email_tpl/new_withdrawal_request', ob_get_clean() );

		foreach ( $admin_users as $admin_user ) {
			$replacable['{user_name}'] = tutor_utils()->get_user_name( $admin_user );
			$message                   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );
			$this->send( $admin_user->user_email, $subject, $message, $header );
		}

		if ( $teacher_withdrawl_request_status ) {
			$this->withdrawal_received_to_instructor( $instructor, $withdrawal_id );
			return;
		}
	}


	private function withdrawal_received_to_instructor( $instructor, $withdrawal_id ) {

		$option_status = tutor_utils()->get_option( 'email_to_teachers.withdrawal_request_received' );
		if ( ! $option_status ) {
			return;
		}

		$withdraw        = $this->get_witdrawal_by_id( $withdrawal_id );
		$withdraw_amount = $withdraw->amount;
		$currency        = get_option( 'woocommerce_currency' );

		$site_url        = get_bloginfo( 'url' );
		$site_name       = get_bloginfo( 'name' );
		$option_data     = $this->email_options['email_to_teachers']['withdrawal_request_received'];
		$header          = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header          = apply_filters( 'withdrawal_request_received_email_header', $header, $instructor->ID );
		$withdrawal      = $this->get_witdrawal_by_id( $withdrawal_id );
		$withdraw_method = maybe_unserialize( $withdrawal->method_data )['withdraw_method_name'];
		$reject_time     = $withdrawal->created_at;
		$withdraw_time   = date_i18n( get_option( 'date_format' ), $reject_time ) . ' ' . date_i18n( get_option( 'time_format' ), $reject_time );

		$total_amount = tutor_utils()->get_earning_sum()->balance;

		$replacable['{testing_email_notice}'] = '';
		$replacable['{instructor_username}']  = $instructor->display_name;
		$replacable['{user_name}']            = tutor_utils()->get_user_name( $instructor );
		$replacable['{total_amount}']         = $total_amount . ' ' . $currency;
		$replacable['{withdraw_amount}']      = $withdraw_amount . ' ' . $currency;
		$replacable['{withdraw_method}']      = $withdraw_method;
		$replacable['{withdraw_time}']        = $withdraw_time;
		$replacable['{site_url}']             = $site_url;
		$replacable['{site_name}']            = $site_name;
		$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']        = $option_data['heading'];
		$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}']        = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		$subject                              = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		ob_start();
		$this->tutor_load_email_template( 'to_instructor_withdrawal_request_received' );
		$email_tpl = apply_filters( 'tutor_email_tpl/withdrawal_request_received', ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$this->send( $instructor->user_email, $subject, $message, $header );

	}

	// lqa means lesson or quiz or assignment
	public function new_lqa_published( $lqa ) {
		// pr($lqa);die;
		// pending
		$lqa_type      = $lqa['lqa_type'];
		$option_status = tutor_utils()->get_option( 'email_to_students.new_' . $lqa_type . '_published' );
		if ( ! $option_status ) {
			return;
		}

		$site_url    = get_bloginfo( 'url' );
		$site_name   = get_bloginfo( 'name' );
		$option_data = $this->email_options['email_to_students'][ 'new_' . $lqa_type . '_published' ];
		$header      = 'Content-Type: ' . $this->get_content_type() . "\r\n";

		$replacable['{testing_email_notice}']      = '';
		$replacable['{student_username}']          = $lqa['student']->display_name;
		$replacable['{user_name}']                 = tutor_utils()->get_user_name( get_userdata( $lqa['student']->ID ) );
		$replacable[ '{' . $lqa_type . '_title}' ] = $lqa['lqa']->post_title;
		$replacable['{course_title}']              = $lqa['course']->post_title;
		$replacable['{site_url}']                  = $site_url;
		$replacable['{site_name}']                 = $site_name;
		$replacable['{logo}']                      = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
		$replacable['{email_heading}']             = $option_data['heading'];
		$replacable['{footer_text}']               = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
		$replacable['{email_message}']             = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
		// $subject                       = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

		$subject   = sprintf( __( 'New %s Published', 'tutor-pro' ), __( $lqa_type, 'tutor-pro' ) );
		$hook_name = 'new_' . strtolower( $lqa_type ) . '_published';

		ob_start();
		$this->tutor_load_email_template( 'to_student_new_' . $lqa['lqa_type'] . '_published' );
		$email_tpl = apply_filters( 'tutor_email_tpl/' . $hook_name, ob_get_clean() );
		$message   = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );

		$header = 'Content-Type: ' . $this->get_content_type() . "\r\n";
		$header = apply_filters( $hook_name . '_email_header', $header, $lqa['lqa']->ID );

		$this->send( $lqa['student']->user_email, $subject, $message, $header, array(), true );
	}

	private function enqueue_email( $to, $subject, $message, $headers, $attachments = array(), $force_enqueue = false ) {
		global $wpdb;

		$data = array(
			'mail_to' => $to,
			'subject' => $subject,
			'message' => $message,
			'headers' => serialize( $headers ),
		);

		if ( is_string( $to ) && ! $force_enqueue ) {
			// Send email instantly in case single recipient
			$this->send_mail( array( $data ) );
			return;
		}

		! is_array( $to ) ? $to = array( $to ) : 0;

		foreach ( $to as $email ) {
			$insert_data = array_merge( $data, array( 'mail_to' => $email ) );
			$wpdb->insert( $this->queue_table, $insert_data );
		}
	}

	private function send_mail( $mails ) {
		add_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
		add_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
		add_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );

		foreach ( $mails as $mail ) {
			$mail['headers'] = unserialize( $mail['headers'] );
			wp_mail( $mail['mail_to'], $mail['subject'], $mail['message'], $mail['headers'] );
		}

		remove_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
		remove_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
		remove_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );
	}

	public function tutor_cron_schedules( $schedules ) {

		$intervals = array( 300, 900, 1800, 3600 );

		foreach ( $intervals as $second ) {

			$hook = $second . 'second';

			if ( ! isset( $schedules[ $hook ] ) ) {
				$schedules[ $hook ] = array(
					'interval' => $second,
					'display'  => $second . ' ' . __( 'second', 'tutor-pro' ),
				);
			}
		}

		return $schedules;
	}

	public function deregister_scheduler() {
		wp_clear_scheduled_hook( 'tutor_email_scheduler_cron' );
	}

	public function register_scheduler( $override_old = false ) {

		$override_old ? $this->deregister_scheduler() : 0;

		$event_timestamp = wp_next_scheduled( 'tutor_email_scheduler_cron' );

		if ( $event_timestamp === false ) {

			// Register scheduler if not already
			$is_disabled = (bool) tutor_utils()->get_option( 'tutor_email_disable_wpcron' );
			$interval    = (int) tutor_utils()->get_option( 'tutor_email_cron_frequency' );
			( ! $interval || ! is_numeric( $interval ) || $interval <= 0 ) ? $interval = 900 : 0;

			if ( ! $is_disabled ) {
				wp_schedule_event( time(), $interval . 'second', 'tutor_email_scheduler_cron' );
			}
		}
	}

	public function run_scheduler() {

		$limit = tutor_utils()->get_option( 'tutor_bulk_email_limit', 10 );
		( ! $limit || ! is_numeric( $limit ) || $limit <= 0 ) ? $limit = 10 : 0;

		$is_os_native = isset( $_GET['tutor_cron'] ) && $_GET['tutor_cron'] == '1';

		global $wpdb;
		$mails      = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$this->queue_table} ORDER BY id ASC LIMIT %d", $limit ) );
		$mail_count = is_array( $mails ) ? count( $mails ) : 0;

		if ( ! $mail_count ) {
			$is_os_native ? exit( json_encode( array( 'call_again' => 'no' ) ) ) : 0;
			return;
		}

		$mails = array_map(
			function( $mail ) {
				return (array) $mail;
			},
			$mails
		);

		// Send mail
		$this->send_mail( $mails );

		// Delete from queue
		$ids = implode( ',', array_column( $mails, 'id' ) );
		$wpdb->query( "DELETE FROM {$this->queue_table} WHERE id IN ({$ids})" );

		if ( $is_os_native ) {
			$call = $mail_count >= $limit ? 'yes' : 'no';
			exit( json_encode( array( 'call_again' => $call ) ) );
		}
	}

	/**
	 * Send course update notification mail to instructor
	 *
	 * Event course publish | trash
	 *
	 * @since 1.9.8
	 */
	public function tutor_course_update_notification( $post_id, $post, $update ) {
		// check if author is tutor instructor
		$course                 = $post;
		$course_status          = $course->post_status;
		$is_enable_publish_mail = tutor_utils()->get_option( 'email_to_teachers.instructor_course_publish' );

		if ( 'Publish' === Input::post( 'original_publish' ) ) {
			return;
		}

		if ( tutor_utils()->is_instructor( $course->post_author ) && 'publish' === $course_status ) {
			// check if already publish mail sent
			$post_meta = get_post_meta( $post_id, 'tutor_instructor_course_publish', true );

			if ( ! $post_meta ) {
				$site_url        = get_bloginfo( 'url' );
				$site_name       = get_bloginfo( 'name' );
				$option_data     = $this->email_options['email_to_teachers']['instructor_course_publish'];
				$header          = 'Content-Type: ' . $this->get_content_type() . "\r\n";
				$header          = apply_filters( 'to_instructor_course_update_subject', $header, $course->ID );
				$instructor_name = tutor_utils()->get_user_name( get_userdata( $course->post_author ) );
				$course_url      = get_post_permalink( $course->ID );
				$course_edit_url = get_edit_post_link( $course->ID );
				$course_title    = $course->post_title;
				$author_email    = get_the_author_meta( 'user_email', $course->post_author );

				$replacable['{testing_email_notice}'] = '';
				$replacable['{course_name}']          = $course_title;
				$replacable['{site_url}']             = $site_url;
				$replacable['{site_name}']            = $site_name;
				$replacable['{user_name}']            = $instructor_name;
				$replacable['{course_url}']           = $course_url;
				$replacable['{course_edit_url}']      = $course_edit_url;
				$replacable['{logo}']                 = isset( $option_data['logo'] ) ? $option_data['logo'] : '';
				$replacable['{email_heading}']        = $option_data['heading'];
				$replacable['{footer_text}']          = $this->get_replaced_text( $option_data['footer_text'], array_keys( $replacable ), array_values( $replacable ) );
				$replacable['{email_message}']        = $this->get_replaced_text( $this->prepare_message( $option_data['message'] ), array_keys( $replacable ), array_values( $replacable ) );
				$subject                              = $this->get_replaced_text( $option_data['subject'], array_keys( $replacable ), array_values( $replacable ) );

				$message   = '';
				$email_tpl = '';
				if ( 'draft' === $course_status && $is_enable_publish_mail ) {
					ob_start();
					$this->tutor_load_email_template( 'to_instructor_course_rejected' );
					$email_tpl = apply_filters( 'to_instructor_course_rejected', ob_get_clean() );
				}

				if ( 'publish' === $course_status && $is_enable_publish_mail ) {
					ob_start();
					$this->tutor_load_email_template( 'to_instructor_course_accepted' );
					$email_tpl = apply_filters( 'to_instructor_course_accepted', ob_get_clean() );
				}

				if ( $is_enable_publish_mail ) {
					$message = html_entity_decode( $this->get_message( $email_tpl, array_keys( $replacable ), array_values( $replacable ) ) );
					$this->send( $author_email, $subject, $message, $header, array(), true );
				}
				update_post_meta( $post_id, 'tutor_instructor_course_publish', true );
			}
		}
	}

	/**
	 * Facilitate tutor_course_update_notification method
	 *
	 * @param required
	 *
	 * @return bool
	 *
	 * @since 1.9.8
	 */
	public function tutor_send_course_update_notification( $author_email, $template, $file_tpl_variable, $replace_data, $course, $subject ) {
		if ( '' !== $template ) {
			$to_emails = array( $author_email );
			ob_start();
			$this->tutor_load_email_template( $template );
			$email_tpl = apply_filters( 'tutor_email_tpl/to_instructor_course_update', ob_get_clean() );

			$message = $this->get_message( $email_tpl, $file_tpl_variable, $replace_data );

			$header = 'Content-Type: ' . $this->get_content_type() . "\r\n";
			$header = apply_filters( 'to_instructor_course_update_subject', $header, $course->ID );

			$this->send( array_unique( $to_emails ), $subject, $message, $header );
			return true;
		}
		return false;
	}

	/**
	 * Get connected users on the question thread
	 *
	 * @since 2.1.8
	 *
	 * @param object $question_details question object typically wp_comment obj.
	 * @param array  $reply_details it is also a comment in array format.
	 *
	 * @return wp::get_results
	 */
	public static function get_thread_users( object $question_details, array $reply_details ) {
		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT
				DISTINCT u.ID,u.user_email
			FROM {$wpdb->comments} AS c
			INNER JOIN {$wpdb->users} AS u
				ON u.ID = c.user_id
			WHERE c.comment_parent = %d
				OR c.comment_ID = %d
				AND c.comment_type = %s
			",
			$question_details->comment_ID,
			$question_details->comment_ID,
			'tutor_q_and_a'
		);
		return $wpdb->get_results( $query );
	}
}
