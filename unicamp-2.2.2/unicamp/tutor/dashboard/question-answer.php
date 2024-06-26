<?php
/**
 * @package       TutorLMS/Templates
 * @version       1.6.4
 *
 * @theme-since   1.0.0
 * @theme-version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

use TUTOR\Instructor;

if ( isset( $_GET['question_id'] ) ) {
	tutor_load_template_from_custom_path( tutor()->path . '/views/qna/qna-single.php', array(
		'question_id' => $_GET['question_id'],
		'context'     => 'frontend-dashboard-qna-single',
	) );

	return;
}

if ( isset( $_GET['view_as'] ) && in_array( $_GET['view_as'], array( 'student', 'instructor' ) ) ) {
	update_user_meta( get_current_user_id(), 'tutor_qa_view_as', $_GET['view_as'] );
}

$is_instructor     = tutor_utils()->is_instructor( null, true );
$view_option       = get_user_meta( get_current_user_id(), 'tutor_qa_view_as', true );
$view_as           = $is_instructor ? ( $view_option ? $view_option : 'instructor' ) : 'student';
$as_instructor_url = add_query_arg( array( 'view_as' => 'instructor' ), tutor()->current_url );
$as_student_url    = add_query_arg( array( 'view_as' => 'student' ), tutor()->current_url );
$qna_tabs          = \Tutor\Q_and_A::tabs_key_value( $view_as == 'student' ? get_current_user_id() : null );
$active_tab        = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'all';

$per_page     = tutor_utils()->get_option( 'pagination_per_page', 10 );
$current_page = max( 1, tutor_utils()->avalue_dot( 'current_page', $_GET ) );
$offset       = ( $current_page - 1 ) * $per_page;

$q_status    = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : null;
$asker_id    = $view_as == 'instructor' ? null : get_current_user_id();
$total_items = tutor_utils()->get_qa_questions( $offset, $per_page, '', null, null, $asker_id, $q_status, true );
$questions   = tutor_utils()->get_qa_questions( $offset, $per_page, '', null, null, $asker_id, $q_status );
?>
<h3><?php esc_html_e( 'Question & Answer', 'unicamp' ); ?></h3>

<div class="dashboard-content-box">
	<?php if ( $is_instructor ) : ?>
		<div class="tutor-col-auto">
			<label
				class="tutor-form-toggle tutor-dashboard-qna-vew-as tutor-d-flex tutor-justify-content-end current-view-<?php echo $view_as == 'instructor' ? 'instructor' : 'student'; ?>">
				<input type="checkbox"
				       class="tutor-form-toggle-input" <?php echo $view_as == 'instructor' ? 'checked="checked"' : ''; ?>
				       data-as_instructor_url="<?php echo esc_url( $as_instructor_url ); ?>"
				       data-as_student_url="<?php echo esc_url( $as_student_url ); ?>" disabled="disabled"/>
				<span
					class="tutor-form-toggle-label tutor-form-toggle-<?php echo $view_as == 'student' ? 'checked' : 'unchecked'; ?>"><?php esc_html_e( 'Student', 'unicamp' ); ?></span>
				<span class="tutor-form-toggle-control"></span>
				<span
					class="tutor-form-toggle-label tutor-form-toggle-<?php echo $view_as == 'instructor' ? 'checked' : 'unchecked'; ?>"><?php esc_html_e( 'Instructor', 'unicamp' ); ?></span>
			</label>
		</div>
	<?php endif; ?>

	<div
		class="tutor-qna-filter <?php echo $is_instructor ? 'tutor-mt-24' : '' ?>" <?php if ( ! $is_instructor ) { ?> style="justify-content: flex-end;" <?php } ?> >
		<span class="tutor-fs-7 tutor-fw-normal tutor-color-black-60"><?php _e( 'Sort By', 'tutor' ); ?>:</span>
		<select class="tutor-form-select tutor-select-redirector">
			<?php
			foreach ( $qna_tabs as $tab ) {
				echo '<option value="' . $tab['url'] . '" ' . ( $active_tab == $tab['key'] ? 'selected="selected"' : '' ) . '>
                            ' . $tab['title'] . '(' . $tab['value'] . ')' . '
                        </option>';
			}
			?>
		</select>
	</div>
</div>

<div class="tutor-ui-table-wrapper">
	<?php
	tutor_load_template( 'dashboard.qna.qna-table', [
		'data' => [
			'qna_list'       => $questions,
			'context'        => 'frontend-dashboard-qna-table-' . $view_as,
			'view_as'        => $view_as,
			'qna_pagination' => array(
				'base'        => '?current_page=%#%',
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'paged'       => $current_page,
			),
		],
	] );
	?>
</div>
