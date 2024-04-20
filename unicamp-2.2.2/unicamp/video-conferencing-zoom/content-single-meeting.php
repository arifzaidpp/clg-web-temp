<?php
/**
 * The template for displaying product content in the single-meeting.php template
 *
 * This template can be overridden by copying it to unicamp-child/video-conferencing-zoom/single-meetings.php.
 *
 * @author     Deepen.
 * @created_on 11/19/19
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hook: vczoom_before_single_meeting.
 */
do_action( 'vczoom_before_single_meeting' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.

	return;
}

/**
 *  Hook: vczoom_before_content
 */
do_action( 'vczoom_before_content' );
?>

<div class="dpn-zvc-single-content-wrapper dpn-zvc-single-content-wrapper-<?php echo get_the_id(); ?>"
     id="dpn-zvc-single-content-wrapper-<?php echo get_the_id(); ?>">
	<div class="entry-hero-content">
		<?php vczapi_get_template( 'fragments/category.php', true ); ?>

		<div class="row entry-title-wrap">
			<div class="col-lg-8 entry-hero-content-left">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</div>
			<div class="col-lg-4 entry-hero-content-right">
				<?php
				/**
				 * @Hook: unicamp_single_zoom_meeting_hero_content_right
				 *
				 * @see video_conference_zoom_countdown_timer - 10
				 */
				do_action( 'unicamp_single_zoom_meeting_hero_content_right' );
				?>
			</div>
		</div>
		<?php
		/**
		 * @Hook: unicamp_single_zoom_meeting_hero_content_bottom
		 *
		 * @see video_conference_zoom_featured_image - 10
		 */
		do_action( 'unicamp_single_zoom_meeting_hero_content_bottom' );
		?>
	</div>
	<div class="row">
		<div class="col-md-8">
			<?php
			/**
			 * @Hook: vczoom_single_content_left
			 *
			 * @see video_conference_zoom_featured_image - 10
			 * @see video_conference_zoom_main_content   - 20
			 */
			do_action( 'vczoom_single_content_left' );
			?>
		</div>
		<div class="col-md-4">
			<div class="dpn-zvc-sidebar-wrapper">
				<?php
				/**
				 * @Hook: vczoom_single_content_right
				 *
				 * @see video_conference_zoom_countdown_timer - 10
				 * @see video_conference_zoom_meeting_details - 20
				 * @see video_conference_zoom_meeting_join    - 30
				 *
				 */
				do_action( 'vczoom_single_content_right' );
				?>
			</div>
		</div>
	</div>
</div>

<?php
/**
 *  Hook: vczoom_after_content
 */
do_action( 'vczoom_after_content' );

/**
 * Hook: video_conference_zoom_before_single_meeting.
 */
do_action( 'video_conference_zoom_after_single_meeting' );
?>
