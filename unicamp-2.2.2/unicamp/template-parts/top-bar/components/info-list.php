<?php
/**
 * Icon list on top bar
 *
 * @package Unicamp
 * @since   1.3.1
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$info_list = $args['info_list'];
?>
<div class="top-bar-info">
	<ul class="info-list">
		<?php
		foreach ( $info_list as $item ) {
			$url  = isset( $item['url'] ) ? $item['url'] : '';
			$icon = isset( $item['icon_class'] ) ? $item['icon_class'] : '';
			$text = isset( $item['text'] ) ? $item['text'] : '';

			$link_class = 'info-link';

			if ( ! empty( $item['link_class'] ) ) {
				$link_class .= ' ' . $item['link_class'];
			}
			?>
			<li class="info-item">
				<?php if ( $url !== '' ) : ?>
				<a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $link_class ); ?>">
					<?php endif; ?>

					<?php if ( $icon !== '' ) : ?>
						<i class="info-icon <?php echo esc_attr( $icon ); ?>"></i>
					<?php endif; ?>

					<?php echo '<span class="info-text">' . $text . '</span>'; ?>

					<?php if ( $url !== '' ) : ?>
				</a>
			<?php endif; ?>
			</li>
		<?php } ?>
	</ul>
</div>
