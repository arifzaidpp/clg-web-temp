<?php

class InsightCore_Share {
	public static function get_buttons( $social_items, $social_order, $args = array() ) {
		$defaults = array(
			'target' => '_blank',
		);
		$args     = wp_parse_args( $args, $defaults );
		if ( ! empty( $social_items ) ) {
			foreach ( $social_order as $social ) {
				if ( in_array( $social, $social_items, true ) ) {
					if ( $social === 'facebook' ) {
						if ( ! wp_is_mobile() ) {
							$facebook_url = 'http://www.facebook.com/sharer.php?m2w&s=100&p&#91;url&#93;=' . rawurlencode( get_permalink() ) . '&p&#91;images&#93;&#91;0&#93;=' . wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ) . '&p&#91;title&#93;=' . rawurlencode( get_the_title() );
						} else {
							$facebook_url = 'https://m.facebook.com/sharer.php?u=' . rawurlencode( get_permalink() );
						}
						?>
						<a class="hint--top facebook" target="<?php echo esc_attr( $args['target'] ); ?>"
						   aria-label="<?php echo esc_attr__( 'Facebook', 'insight-core' ) ?>"
						   href="<?php echo esc_url( $facebook_url ); ?>">
							<i class="ion-social-facebook"></i>
						</a>
						<?php
					} elseif ( $social === 'twitter' ) {
						?>
						<a class="hint--top twitter" target="<?php echo esc_attr( $args['target'] ); ?>"
						   aria-label="<?php echo esc_attr__( 'Twitter', 'insight-core' ) ?>"
						   href="https://twitter.com/share?text=<?php echo rawurlencode( html_entity_decode( get_the_title(), ENT_COMPAT, 'UTF-8' ) ); ?>&url=<?php echo rawurlencode( get_permalink() ); ?>">
							<i class="ion-social-twitter"></i>
						</a>
						<?php
					} elseif ( $social === 'google_plus' ) {
						?>
						<a class="hint--top google-plus"
						   target="<?php echo esc_attr( $args['target'] ); ?>"
						   aria-label="<?php echo esc_attr__( 'Google+', 'insight-core' ) ?>"
						   href="https://plus.google.com/share?url=<?php echo rawurlencode( get_permalink() ); ?>">
							<i class="ion-social-googleplus"></i>
						</a>
						<?php
					} elseif ( $social === 'tumblr' ) {
						?>
						<a class="hint--top tumblr" target="<?php echo esc_attr( $args['target'] ); ?>"
						   aria-label="<?php echo esc_attr__( 'Tumblr', 'insight-core' ) ?>"
						   href="http://www.tumblr.com/share/link?url=<?php echo rawurlencode( get_permalink() ); ?>&amp;name=<?php echo rawurlencode( get_the_title() ); ?>">
							<i class="ion-social-tumblr"></i>
						</a>
						<?php
					} elseif ( $social === 'linkedin' ) {
						?>
						<a class="hint--top linkedin" target="<?php echo esc_attr( $args['target'] ); ?>"
						   aria-label="<?php echo esc_attr__( 'Linkedin', 'insight-core' ) ?>"
						   href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo rawurlencode( get_permalink() ); ?>&amp;title=<?php echo rawurlencode( get_the_title() ); ?>">
							<i class="ion-social-linkedin"></i>
						</a>
						<?php
					} elseif ( $social === 'email' ) {
						?>
						<a class="hint--top email" target="<?php echo esc_attr( $args['target'] ); ?>"
						   aria-label="<?php echo esc_attr__( 'Email', 'insight-core' ) ?>"
						   href="mailto:?subject=<?php echo rawurlencode( get_the_title() ); ?>&amp;body=<?php echo rawurlencode( get_permalink() ); ?>">
							<i class="ion-android-mail"></i>
						</a>
						<?php
					}
				}
			}
		}
	}
}