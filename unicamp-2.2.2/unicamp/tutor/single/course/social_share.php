<?php
/**
 * Template for displaying social share
 *
 * @since         v.1.0.4
 *
 * @author        Themeum
 * @url https://themeum.com
 *
 * @package       TutorLMS/Templates
 * @version       1.4.3
 *
 * @theme-since   1.0.0
 * @theme-version 3.0.0
 */

defined( 'ABSPATH' ) || exit;

$tutor_social_share_icons = tutor_utils()->tutor_social_share_icons();
if ( ! tutor_utils()->count( $tutor_social_share_icons ) ) {
	return;
}

$share_config = array(
	'title' => get_the_title(),
	'text'  => get_the_excerpt(),
	'image' => get_tutor_course_thumbnail( 'post-thumbnail', true ),
);
?>

<div class="tutor-social-share-wrap"
     data-social-share-config="<?php echo esc_attr( wp_json_encode( $share_config ) ); ?>">
	<?php
	foreach ( $tutor_social_share_icons as $icon ) {
		echo "<a class='tutor_share {$icon['share_class']}'> {$icon['icon_html']} </a>";
	}
	?>
</div>
