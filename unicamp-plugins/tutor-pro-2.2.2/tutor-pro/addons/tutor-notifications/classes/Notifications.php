<?php
/**
 * handles registering all notifications
 *
 * @package tutor
 *
 * @since 1.9.10
 */

namespace TUTOR_NOTIFICATIONS;

defined( 'ABSPATH' ) || exit;

/**
 * Notifications class
 */
class Notifications {

    /**
     * Construct
     */
    public function __construct() {
        add_action( 'tutor_after_approved_instructor', array( $this, 'instructor_approval' ) );
        add_action( 'tutor_after_rejected_instructor', array( $this, 'instructor_rejected' ) );

        add_action( 'tutor_new_instructor_after', array( $this, 'new_instructor_application' ) );

		add_action( 'tutor_assignment/evaluate/after', array( $this, 'tutor_after_assignment_evaluated' ), 10, 3);
        add_action( 'tutor_announcements/after/save', array( $this, 'tutor_announcements_notify_students' ), 10, 3 );
        add_action( 'tutor_after_answer_to_question', array( $this, 'tutor_after_answer_to_question' ) );
        add_action( 'tutor_quiz/attempt/submitted/feedback', array( $this, 'feedback_submitted_for_quiz_attempt' ) );

		add_action( 'tutor_after_enrolled', array( $this, 'tutor_student_course_enrolled' ), 10, 3 );
		add_action( 'tutor_enrollment/after/cancel', array( $this, 'tutor_student_remove_from_course' ), 10, 1 );
    }

    /**
     * Instructor Approval
     */
    public function instructor_approval( $instructor_id ) {
        global $wpdb;

        $notification_enabled = tutor_utils()->get_option( 'tutor_notifications_to_instructors.instructor_application_accepted' );

        if ( ! $notification_enabled ) return;

        $tablename = $wpdb->prefix . 'tutor_notifications';

        $user_data    = get_userdata( $instructor_id );
        $display_name = $user_data->display_name;

        $message_type   = 'Instructorship';
        $message_status = 'UNREAD';
        $message_title  = __( 'Instructorship', 'tutor-pro' );

        $translated_string1 = _x( 'Congratulations', 'instructorship-approved-text', 'tutor-pro' );
        $translated_string2 = _x( 'your application to be an instructor has been approved.', 'instructorship-approved-text', 'tutor-pro' );

        $message_content  = '<span class="tutor-color-secondary">';
        $message_content .= $translated_string1;
        $message_content .= '</span> ' . ucfirst( $display_name ) . ', <span class="tutor-color-secondary">';
        $message_content .= $translated_string2;
        $message_content .= '</span>';

        $data = array(
            'type'        => $message_type,
            'title'       => $message_title,
            'content'     => $message_content,
            'status'      => $message_status,
            'receiver_id' => (int) $instructor_id,
            'post_id'     => null,
            'topic_url'   => null
        );
        $format = array( '%s', '%s', '%s', '%s', '%d', '%d', '%s' );

        if ( ! is_wp_error( $data ) ) {
            $notification_created = $wpdb->insert( $tablename, $data, $format );
        }
    }

    /**
     * Instructor Rejected
     */
    public function instructor_rejected( $instructor_id ) {
        global $wpdb;

        $notification_enabled = tutor_utils()->get_option( 'tutor_notifications_to_instructors.instructor_application_rejected' );

        if ( ! $notification_enabled ) return;

        $tablename = $wpdb->prefix . 'tutor_notifications';

        $user_data    = get_userdata( $instructor_id );
        $display_name = $user_data->display_name;

        $message_type   = 'Instructorship';
        $message_status = 'UNREAD';
        $message_title  = __( 'Instructorship', 'tutor-pro' );

        $translated_string = _x( 'your instructorship application has been declined.', 'instructorship-rejected-text', 'tutor-pro' );

        $message_content  = ucfirst( $display_name ) . ', <span class="tutor-color-secondary">';
        $message_content .= $translated_string;
        $message_content .= '</span>';

        $data = array(
            'type'        => $message_type,
            'title'       => $message_title,
            'content'     => $message_content,
            'status'      => $message_status,
            'receiver_id' => (int) $instructor_id,
            'post_id'     => null,
            'topic_url'   => null
        );
        $format = array( '%s', '%s', '%s', '%s', '%d', '%d', '%s' );

        if ( ! is_wp_error( $data ) ) {
            $notification_created = $wpdb->insert( $tablename, $data, $format );
        }
    }

    /**
     * New Instructor Application
     */
    public function new_instructor_application( $instructor_id ) {
        global $wpdb;

        $notification_enabled = tutor_utils()->get_option( 'tutor_notifications_to_admin.instructor_application_received' );

        if ( ! $notification_enabled ) return;

        $tablename       = $wpdb->prefix . 'tutor_notifications';
        $admin_users     = get_users( array( 'role__in' => array( 'administrator' ) ) );
        $user_data       = get_userdata( $instructor_id );
        $display_name    = $user_data->display_name;

        $message_type    = 'Instructorship';
        $message_status  = 'UNREAD';
        $message_title   = __( 'Instructorship', 'tutor-pro' );

        $admin_records = array();
        foreach ( $admin_users as $admin ) {
            $data = array(
                'type'        => $message_type,
                'title'       => $message_title,
                'status'      => $message_status,
                'receiver_id' => (int) $admin->ID,
                'post_id'     => null,
                'topic_url'   => null
            );

            $translated_string1 = _x( 'you have received a new application from', 'instructor-application-received', 'tutor-pro' );
            $translated_string2 = _x( 'for Instructorship.', 'instructor-application-received', 'tutor-pro' );

            $message_content  = ucfirst( $admin->display_name ) . ', <span class="tutor-color-secondary">';
            $message_content .= $translated_string1;
            $message_content .= '</span> ' . ucfirst( $display_name ) . ' <span class="tutor-color-secondary">';
            $message_content .= $translated_string2;
            $message_content .= '</span>';

            $data['content'] = $message_content;

            array_push( $admin_records, $data );
        }

        $format = array( '%s', '%s', '%s', '%s', '%d', '%d', '%s' );

        foreach ( $admin_records as $admin_record ) {
            if ( ! is_wp_error( $data ) ) {
                $notification_created = $wpdb->insert( $tablename, $admin_record, $format );
            }
        }
    }

    /**
     * Assignment Graded
     */
    public function tutor_after_assignment_evaluated( $assignment_submission_id ) {
        global $wpdb;

        $notification_enabled = tutor_utils()->get_option( 'tutor_notifications_to_students.assignment_graded' );

        if ( ! $notification_enabled ) return;

        $tablename            = $wpdb->prefix . 'tutor_notifications';

        $submitted_assignment = tutor_utils()->get_assignment_submit_info( $assignment_submission_id );
		$assignment_name      = get_the_title( $submitted_assignment->comment_post_ID );
		$assignment_comment   = get_comment_meta( $assignment_submission_id, 'instructor_note', true );
        $assignment_url       = get_permalink( $submitted_assignment->comment_post_ID );

        $user_data            = get_userdata( $submitted_assignment->user_id );
        $display_name         = $user_data->display_name;

        $message_type         = 'Assignments';
        $message_status       = 'UNREAD';
        $message_title        = __( 'Assignments', 'tutor-pro' );

        $translated_string1         = _x( 'Hi', 'grades-submitted-text', 'tutor-pro' );
        $translated_string2 = _x( 'your', 'grades-submitted-text', 'tutor-pro' );
        $translated_string3 = _x( 'has been graded. Check it out.', 'grades-submitted-text', 'tutor-pro' );

        $message_content  = '<span class="tutor-color-secondary">';
        $message_content .= $translated_string1;
        $message_content .= '</span> ' . ucfirst( $display_name ) . ', <span class="tutor-color-secondary">';
        $message_content .= $translated_string2;
        $message_content .= '</span> ' . $assignment_name . ' <span class="tutor-color-secondary">';
        $message_content .= $translated_string3;
        $message_content .= '</span>';

        $data = array(
            'type'        => $message_type,
            'title'       => $message_title,
            'content'     => $message_content,
            'status'      => $message_status,
            'receiver_id' => (int) $submitted_assignment->user_id,
            'post_id'     => (int) $submitted_assignment->comment_post_ID,
            'topic_url'   => $assignment_url
        );
        $format = array( '%s', '%s', '%s', '%s', '%d', '%d', '%s' );

        if ( ! is_wp_error( $data ) ) {
            $notification_created = $wpdb->insert( $tablename, $data, $format );
        }
    }

    /**
     * Announcement Notifications
     */
    public function tutor_announcements_notify_students( $announcement_id, $announcement, $action_type ) {
        global $wpdb;

        $notification_enabled = tutor_utils()->get_option( 'tutor_notifications_to_students.new_announcement_posted' );
		if ( ! isset( $_POST['tutor_notify_all_students'] ) || ! $_POST['tutor_notify_all_students'] || ! $notification_enabled ){
            return;
        }

        $tablename        = $wpdb->prefix . 'tutor_notifications';
        $student_ids      = tutor_utils()->get_students_data_by_course_id( $announcement->post_parent, 'ID' );
		$course_name      = get_the_title( $announcement->post_parent );
        $author           = get_userdata( $announcement->post_author );
        $author_name      = $author->display_name;

        $message_type     = 'Announcements';
        $message_status   = 'UNREAD';
        $message_title    = __( 'Announcements', 'tutor-pro' );

        $translated_string1 = $action_type === 'create' ? _x( 'A new announcement has been posted by', 'announcement-text', 'tutor-pro' ) : _x( 'An announcement has been updated by', 'announcement-text', 'tutor-pro' );
        $translated_string2 = _x( 'of', 'announcement-text', 'tutor-pro' );

        $message_content  = '<span class="tutor-color-secondary">';
        $message_content .= $translated_string1;
        $message_content .= '</span> ' . ucfirst( $author_name ) . ' <span class="tutor-color-secondary">';
        $message_content .= $translated_string2;
        $message_content .= '</span> ' . $course_name;

        $announcement_url = get_permalink( $announcement->post_parent );

        $announcement_records = array();

        // Loop through $student_ids to send announcements for each of them.
        foreach ( $student_ids as $key => $value ) {
            $data = array(
                'type'        => $message_type,
                'title'       => $message_title,
                'content'     => $message_content,
                'status'      => $message_status,
                'receiver_id' => (int) $value,
                'post_id'     => (int) $announcement->post_parent,
                'topic_url'   => $announcement_url,
            );

            array_push( $announcement_records, $data );
        }

        $format = array( '%s', '%s', '%s', '%s', '%d', '%d', '%s' );

        // Insert announcement for every student enrolled in the course in question.
        foreach ( $announcement_records as $announcement ) {
            if ( ! is_wp_error( $announcement ) ) {
                $wpdb->insert( $tablename, $announcement, $format );
            }
        }
    }

    /**
     * After Answering Questions
     * @param int $answer_id
     * @return void
     */
    public function tutor_after_answer_to_question( $answer_id ) {
        global $wpdb;

        $notification_enabled = tutor_utils()->get_option( 'tutor_notifications_to_students.after_question_answered' );

        if ( ! $notification_enabled ) return;

        $tablename       = $wpdb->prefix . 'tutor_notifications';
        $answer          = tutor_utils()->get_qa_answer_by_answer_id( $answer_id );
		$course_name     = get_the_title( $answer->comment_post_ID );
        $comment_author  = 'tutor_q_and_a' === get_comment_type( $answer_id ) ? get_comment_author( $answer_id ) : 0;
		$question_author = $answer->question_by;

        $message_type    = 'Q&A';
        $message_status  = 'UNREAD';
        $message_title   = __( 'Q&A', 'tutor-pro' );

        $translated_string1 = _x( 'A new answer has been posted by', 'qa-answer-posted', 'tutor-pro' );
        $translated_string2 = _x( 'in', 'qa-answer-posted', 'tutor-pro' );
        $translated_string3 = _x( '\'s Q&A.', 'qa-answer-posted', 'tutor-pro' );
        
        $message_content  = '<span class="tutor-color-secondary">';
        $message_content .= $translated_string1;
        $message_content .= '</span> ' . ucfirst( $comment_author ) . ' <span class="tutor-color-secondary">';
        $message_content .= $translated_string2;
        $message_content .= '</span> ' . $course_name;
        $message_content .= $translated_string3;

        $qa_url          = tutor_utils()->tutor_dashboard_url( 'question-answer?question_id=' . $answer->question_id );

        $data = array(
            'type'        => $message_type,
            'title'       => $message_title,
            'content'     => $message_content,
            'status'      => $message_status,
            'receiver_id' => (int) $question_author,
            'post_id'     => (int) $answer->comment_post_ID,
            'topic_url'   => $qa_url
        );
        $format = array( '%s', '%s', '%s', '%s', '%d', '%d', '%s' );

        if ( ! is_wp_error( $data ) ) {
            $wpdb->insert( $tablename, $data, $format );
        }
    }

    /**
     * Feedback submitted for quizzes
     */
    public function feedback_submitted_for_quiz_attempt( $attempt_id ) {
        global $wpdb;

        $notification_enabled = tutor_utils()->get_option( 'tutor_notifications_to_students.feedback_submitted_for_quiz' );

        if ( ! $notification_enabled ) return;

        $tablename       = $wpdb->prefix . 'tutor_notifications';

        $attempt         = tutor_utils()->get_attempt( $attempt_id );
		$quiz_title      = get_post_field( 'post_title', $attempt->quiz_id );
		$course          = get_post( $attempt->course_id );
		$feedback        = get_post_meta( $attempt_id, 'instructor_feedback', true );

        $message_type    = 'Quiz';
        $message_status  = 'UNREAD';
        $message_title   = __( 'Quiz', 'tutor-pro' );
        $message_content = sprintf( _x( '<span class="tutor-color-secondary">Your quiz result for</span> %s <span class="tutor-color-secondary">of</span> %s <span class="tutor-color-secondary">has been published.</span>', 'quiz-attempt-text', 'tutor-pro' ), $quiz_title, $course->post_title );

        $translated_string1 = _x( 'Your quiz result for', 'quiz-attempt-text', 'tutor-pro' );
        $translated_string2 = _x( 'of', 'quiz-attempt-text', 'tutor-pro' );
        $translated_string3 = _x( 'has been published.', 'quiz-attempt-text', 'tutor-pro' );
        
        $message_content  = '<span class="tutor-color-secondary">';
        $message_content .= $translated_string1;
        $message_content .= '</span> ' . $quiz_title . ' <span class="tutor-color-secondary">';
        $message_content .= $translated_string2;
        $message_content .= '</span> ' . $course->post_title . ' <span class="tutor-color-secondary">';
        $message_content .= $translated_string3;
        $message_content .= '</span>';

        $quiz_url        = tutor_utils()->get_tutor_dashboard_page_permalink( 'my-quiz-attempts' );

        $data = array(
            'type'        => $message_type,
            'title'       => $message_title,
            'content'     => $message_content,
            'status'      => $message_status,
            'receiver_id' => (int) $attempt->user_id,
            'post_id'     => (int) $course->ID,
            'topic_url'   => $quiz_url
        );
        $format = array( '%s', '%s', '%s', '%s', '%d', '%d', '%s' );

        if ( ! is_wp_error( $data ) ) {
            $notification_created = $wpdb->insert( $tablename, $data, $format );
        }
    }

    /**
     * Course enrolled
     */
    public function tutor_student_course_enrolled( $course_id, $user_id, $enrollment_id ) {
        global $wpdb;

        $notification_enabled = tutor_utils()->get_option( 'tutor_notifications_to_students.course_enrolled' );

        if ( ! $notification_enabled ) return;

        $tablename       = $wpdb->prefix . 'tutor_notifications';

        $user_data       = get_userdata( $user_id );
        $display_name    = $user_data->display_name;
        $course          = tutor_utils()->get_course_by_enrol_id( $enrollment_id );
        $course_title    = $course->post_title;
        $course_url      = get_permalink( $course_id );

        $message_type    = 'Enrollments';
        $message_status  = 'UNREAD';
        $message_title   = __( 'Enrollment', 'tutor-pro' );

        $translated_string = _x( 'Congratulations, you have been successfully enrolled in', 'got-enrolled-text', 'tutor-pro' );

        $message_content  = '<span class="tutor-color-secondary">';
        $message_content .= $translated_string;
        $message_content .= '</span> ' . $course_title;

        $data = array(
            'type'        => $message_type,
            'title'       => $message_title,
            'content'     => $message_content,
            'status'      => $message_status,
            'receiver_id' => (int) $user_id,
            'post_id'     => (int) $course_id,
            'topic_url'   => $course_url
        );
        $format = array( '%s', '%s', '%s', '%s', '%d', '%d', '%s' );

        if ( ! is_wp_error( $data ) ) {
            $notification_created = $wpdb->insert( $tablename, $data, $format );
        }
    }

    /**
     * Enrollment Cancelled
     */
    public function tutor_student_remove_from_course( $enrollment_id ) {
        global $wpdb;

        $notification_enabled = tutor_utils()->get_option( 'tutor_notifications_to_students.remove_from_course' );

        if ( ! $notification_enabled ) return;

        $tablename = $wpdb->prefix . 'tutor_notifications';
        $course    = tutor_utils()->get_enrolment_by_enrol_id( $enrollment_id );

        if ( ! $course ) return;

        $display_name    = $course->display_name;
        $course_title    = $course->course_title;
        $course_url      = get_permalink( $course->course_id );

        $message_type    = 'Enrollments';
        $message_status  = 'UNREAD';
        $message_title   = __( 'Enrollment', 'tutor-pro' );
        $message_content = sprintf( _x( '%s, <span class="tutor-color-secondary">your enrollment request for</span> %s <span class="tutor-color-secondary">has been declined.</span>', 'enrollment-cancelled-text', 'tutor-pro' ), ucfirst( $display_name ), $course_title );

        $translated_string1 = _x( 'your enrollment request for', 'enrollment-cancelled-text', 'tutor-pro' );
        $translated_string2 = _x( 'has been declined.', 'enrollment-cancelled-text', 'tutor-pro' );
        
        $message_content  = ucfirst( $display_name ) . ', <span class="tutor-color-secondary">';
        $message_content .= $translated_string1;
        $message_content .= '</span> ' . $course_title . ' <span class="tutor-color-secondary">';
        $message_content .= $translated_string2;
        $message_content .= '</span>';

        $data = array(
            'type'        => $message_type,
            'title'       => $message_title,
            'content'     => $message_content,
            'status'      => $message_status,
            'receiver_id' => (int) $course->ID,
            'post_id'     => (int) $course->course_id,
            'topic_url'   => $course_url
        );
        $format = array( '%s', '%s', '%s', '%s', '%d', '%d', '%s' );

        if ( ! is_wp_error( $data ) ) {
            $notification_created = $wpdb->insert( $tablename, $data, $format );
        }
    }
}
