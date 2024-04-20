<?php
defined( 'ABSPATH' ) || exit;

/**
 * Custom template tags for this theme.
 */
class Unicamp_Templates {

	public static function pre_loader() {
		if ( Unicamp::setting( 'pre_loader_enable' ) !== '1' ) {
			return;
		}

		// Don't render template in Elementor editor mode.
		if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			return;
		}

		$style = Unicamp::setting( 'pre_loader_style' );

		if ( $style === 'random' ) {
			$style = array_rand( Unicamp_Helper::$preloader_style );
		}
		?>

		<div id="page-preloader" class="page-loading clearfix">
			<div class="page-load-inner">
				<div class="preloader-wrap">
					<div class="wrap-2">
						<div class="inner">
							<?php unicamp_load_template( 'preloader/style', $style ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
	}

	public static function slider( $template_position ) {
		$slider          = Unicamp_Global::instance()->get_slider_alias();
		$slider_position = Unicamp_Global::instance()->get_slider_position();

		if ( ! function_exists( 'rev_slider_shortcode' ) || $slider === '' || $slider_position !== $template_position ) {
			return;
		}

		?>
		<div id="page-slider" class="page-slider">
			<?php echo do_shortcode( '[rev_slider ' . $slider . ']' ); ?>
		</div>
		<?php
	}

	public static function paging_nav( $query = false, $args = array() ) {
		global $wp_query, $wp_rewrite;
		if ( $query === false ) {
			$query = $wp_query;
		}

		// Don't print empty markup if there's only one page.
		if ( $query->max_num_pages < 2 ) {
			return;
		}

		$defaults = [
			'wrapper_class' => '',
		];

		$args = wp_parse_args( $args, $defaults );

		$wrapper_classes = 'archive-pagination-wrap ';
		$wrapper_classes .= $args['wrapper_class'];

		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}

		$page_num_link = html_entity_decode( get_pagenum_link() );
		$query_args    = array();
		$url_parts     = explode( '?', $page_num_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$page_num_link = esc_url( remove_query_arg( array_keys( $query_args ), $page_num_link ) );
		$page_num_link = trailingslashit( $page_num_link ) . '%_%';

		$format = '';
		if ( $wp_rewrite->using_index_permalinks() && ! strpos( $page_num_link, 'index.php' ) ) {
			$format = 'index.php/';
		}
		if ( $wp_rewrite->using_permalinks() ) {
			$format .= user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' );
		} else {
			$format .= '?paged=%#%';
		}

		// Set up paginated links.

		$args  = array(
			'base'      => $page_num_link,
			'format'    => $format,
			'total'     => $query->max_num_pages,
			'current'   => max( 1, $paged ),
			'mid_size'  => 1,
			'add_args'  => array_map( 'urlencode', $query_args ),
			'prev_text' => self::get_pagination_prev_text(),
			'next_text' => self::get_pagination_next_text(),
			'type'      => 'array',
		);
		$pages = paginate_links( $args );

		if ( is_array( $pages ) ) {
			echo '<div class="' . esc_attr( $wrapper_classes ) . '">';
			echo '<ul class="page-pagination">';
			foreach ( $pages as $page ) {
				printf( '<li>%s</li>', $page );
			}
			echo '</ul>';
			echo '</div>';
		}
	}

	public static function render_paginate_links( $args = array() ) {
		$defaults = array(
			'prev_text' => Unicamp_Templates::get_pagination_prev_text(),
			'next_text' => Unicamp_Templates::get_pagination_next_text(),
			'type'      => 'array',
		);

		$args = wp_parse_args( $args, $defaults );

		$pages = paginate_links( $args );

		if ( is_array( $pages ) ) {
			echo '<ul class="page-pagination">';
			foreach ( $pages as $page ) {
				printf( '<li>%s</li>', $page );
			}
			echo '</ul>';
		}
	}

	public static function get_pagination_prev_text() {
		return '<span class="fal fa-angle-double-left"></span>';
	}

	public static function get_pagination_next_text() {
		return '<span class="fal fa-angle-double-right"></span>';
	}

	public static function page_links() {
		wp_link_pages( array(
			'before'           => '<div class="page-links">',
			'after'            => '</div>',
			'link_before'      => '<span>',
			'link_after'       => '</span>',
			'nextpagelink'     => esc_html__( 'Next', 'unicamp' ),
			'previouspagelink' => esc_html__( 'Prev', 'unicamp' ),
		) );
	}

	public static function comment_navigation( $args = array() ) {
		// Are there comments to navigate through?
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
			$defaults = array(
				'container_id'    => '',
				'container_class' => 'navigation comment-navigation',
			);
			$args     = wp_parse_args( $args, $defaults );
			?>
			<nav id="<?php echo esc_attr( $args['container_id'] ); ?>"
			     class="<?php echo esc_attr( $args['container_class'] ); ?>">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'unicamp' ); ?></h2>

				<div class="comment-nav-links">
					<?php paginate_comments_links( array(
						'prev_text' => esc_html__( 'Prev', 'unicamp' ),
						'next_text' => esc_html__( 'Next', 'unicamp' ),
						'type'      => 'list',
					) ); ?>
				</div>
			</nav>
			<?php
		}
		?>
		<?php
	}

	public static function comment_template( $comment, $args, $depth ) {

		$GLOBALS['comment'] = $comment;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment-wrap">
			<div class="comment-author vcard">
				<?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
			</div>
			<div class="comment-content">
				<div class="meta">
					<?php
					printf( '<h6 class="fn">%s</h6>', get_comment_author_link() );
					?>
				</div>
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-messages"><?php esc_html_e( 'Your comment is awaiting moderation.', 'unicamp' ) ?></em>
					<br/>
				<?php endif; ?>
				<div class="comment-text"><?php comment_text(); ?></div>

				<div class="comment-footer">
					<div class="comment-datetime">
						<?php
						printf( esc_html__( '%s at %s', 'unicamp' ), get_comment_date(), get_comment_time() );
						?>
					</div>

					<div class="comment-actions">
						<?php comment_reply_link( array_merge( $args, array(
							'depth'      => $depth,
							'max_depth'  => $args['max_depth'],
							'reply_text' => esc_html__( 'Reply', 'unicamp' ),
						) ) ); ?>
						<?php edit_comment_link( '' . esc_html__( 'Edit', 'unicamp' ) ); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public static function comment_form() {
		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$aria_req  = '';
		if ( $req ) {
			$aria_req = " aria-required='true'";
		}

		$fields = array(
			'author' => '<div class="row"><div class="col-sm-6 comment-form-author"><input id="author" placeholder="' . esc_attr__( 'Your Name *', 'unicamp' ) . '" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" ' . $aria_req . '/></div>',
			'email'  => '<div class="col-sm-6 comment-form-email"><input id="email" placeholder="' . esc_attr__( 'Your Email *', 'unicamp' ) . '" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" ' . $aria_req . '/></div></div>',
		);

		$comment_field = '<div class="row"><div class="col-md-12 comment-form-comment"><textarea id="comment" placeholder="' . esc_attr__( 'Your Comment', 'unicamp' ) . '" name="comment" aria-required="true"></textarea></div></div>';

		$comments_args = array(
			'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title box-title-with-separator">',
			'label_submit'        => esc_html__( 'Submit', 'unicamp' ),
			'title_reply'         => esc_html__( 'Leave your thought here', 'unicamp' ),
			'comment_notes_after' => '',
			'fields'              => apply_filters( 'comment_form_default_fields', $fields ),
			'comment_field'       => $comment_field,
		);
		comment_form( $comments_args );
	}

	/**
	 * @param int|float $rating Rating average.
	 * @param array     $args
	 *
	 * @return string HTML template
	 */
	public static function render_rating( $rating = 5, $args = array() ) {
		$default = [
			'style'         => '01',
			'wrapper_class' => '',
			'echo'          => true,
		];

		$args = wp_parse_args( $args, $default );

		$el_classes = 'tm-star-rating style-' . $args['style'];
		if ( ! empty( $args['wrapper_class'] ) ) {
			$el_classes .= " {$args['wrapper_class']}";
		}

		$full_stars = intval( $rating );
		$template   = '';

		$template .= str_repeat( '<span class="tm-star-full"></span>', $full_stars );

		$half_star = floatval( $rating ) - $full_stars;

		if ( $half_star != 0 ) {
			$template .= '<span class="tm-star-half"></span>';
		}

		$empty_stars = intval( 5 - $rating );
		$template    .= str_repeat( '<span class="tm-star-empty"></span>', $empty_stars );

		$template = '<div class="' . esc_attr( $el_classes ) . '">' . $template . '</div>';

		if ( true === $args['echo'] ) {
			echo '' . $template;
		} else {
			return $template;
		}
	}

	public static function post_author() {
		?>
		<div class="entry-author">
			<div class="author-info">
				<div class="author-avatar">
					<?php echo get_avatar( get_the_author_meta( 'email' ), '100' ); ?>

					<?php echo self::get_author_socials_html(); ?>
				</div>
				<div class="author-description">
					<h5 class="author-name"><?php the_author(); ?></h5>

					<div class="author-biographical-info">
						<?php the_author_meta( 'description' ); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public static function get_author_socials_html( $user_id = false ) {
		$socials = Unicamp_Helper::get_user_social_networks_support();

		if ( empty( $socials ) ) {
			return '';
		}

		ob_start();
		foreach ( $socials as $social_name => $social ) {
			$_social_url = get_the_author_meta( $social_name, $user_id );

			if ( empty( $_social_url ) ) {
				continue;
			}

			$link_classes = 'hint--bounce hint--top hint--primary';
			?>
			<a class="<?php echo esc_attr( $link_classes ); ?>"
			   aria-label="<?php echo esc_attr( $social['label'] ); ?>"
			   href="<?php echo esc_url( $_social_url ); ?>" target="_blank">
				<i class="<?php echo esc_attr( $social['icon_classes'] ); ?>"></i>
			</a>
			<?php
		}
		$socials_html = ob_get_clean();

		if ( '' !== $socials_html ) {
			$socials_html = '<div class="author-social-networks"><div class="inner">' . $socials_html . '</div></div>';
		}

		return $socials_html;
	}

	public static function get_user_socials_html( $user_id = false ) {
		$socials = Unicamp_Helper::get_user_social_networks_support();

		if ( empty( $socials ) ) {
			return '';
		}

		if ( false === $user_id && is_user_logged_in() ) {
			$user_id = get_current_user_id();
		}

		if ( false === $user_id ) {
			return '';
		}

		ob_start();

		foreach ( $socials as $social_name => $social ) {
			$_social_url = get_user_meta( $user_id, $social_name, true );

			if ( empty( $_social_url ) ) {
				continue;
			}

			$link_classes = 'hint--bounce hint--top hint--primary';
			?>
			<a class="<?php echo esc_attr( $link_classes ); ?>"
			   aria-label="<?php echo esc_attr( $social['label'] ); ?>"
			   href="<?php echo esc_url( $_social_url ); ?>" target="_blank" rel="nofollow">
				<i class="<?php echo esc_attr( $social['icon_classes'] ); ?>"></i>
			</a>
			<?php
		}
		$socials_html = ob_get_clean();

		if ( '' !== $socials_html ) {
			$socials_html = '<div class="user-social-networks"><div class="inner">' . $socials_html . '</div></div>';
		}

		return $socials_html;
	}

	public static function get_author_meta_phone_number( $user_id = false ) {
		$phone_number = get_the_author_meta( 'phone_number', $user_id );

		return $phone_number;
	}

	public static function get_author_meta_phone_number_template( $user_id = false ) {
		$phone_number = self::get_author_meta_phone_number( $user_id );

		if ( empty( $phone_number ) ) {
			return;
		}
		?>
		<div class="author-phone-number">
			<?php echo esc_html( $phone_number ); ?>
		</div>
		<?php
	}

	public static function get_author_meta_email( $user_id = false ) {
		$email = get_the_author_meta( 'email', $user_id );

		return $email;
	}

	public static function get_author_meta_email_template( $user_id = false ) {
		$email = self::get_author_meta_email( $user_id );

		if ( empty( $email ) ) {
			return;
		}
		?>
		<div class="author-email">
			<?php echo esc_html( $email ); ?>
		</div>
		<?php
	}

	public static function get_sharing_list( $args = array() ) {
		$defaults       = array(
			'style'            => 'icons',
			'target'           => '_blank',
			'tooltip_enable'   => true,
			'tooltip_skin'     => 'primary',
			'tooltip_position' => 'top',
			'brand_color'      => false,
		);
		$args           = wp_parse_args( $args, $defaults );
		$social_sharing = Unicamp::setting( 'social_sharing_item_enable' );
		if ( ! empty( $social_sharing ) ) {
			$social_sharing_order = Unicamp::setting( 'social_sharing_order' );

			$link_classes = '';

			if ( $args['tooltip_enable'] === true ) {
				$link_classes .= " hint--bounce hint--{$args['tooltip_position']} hint--{$args['tooltip_skin']}";
			}

			if ( $args['brand_color'] === true ) {
				$link_classes .= " brand-color";
			}

			foreach ( $social_sharing_order as $social ) {
				if ( in_array( $social, $social_sharing, true ) ) {
					if ( $social === 'facebook' ) {
						$facebook_url = 'https://m.facebook.com/sharer.php?u=' . rawurlencode( get_permalink() );
						?>
						<a class="<?php echo esc_attr( $link_classes . ' facebook' ); ?>"
						   target="<?php echo esc_attr( $args['target'] ); ?>"
						   aria-label="<?php esc_attr_e( 'Facebook', 'unicamp' ); ?>"
						   href="<?php echo esc_url( $facebook_url ); ?>">
							<?php if ( $args['style'] === 'text' ) : ?>
								<span><?php esc_html_e( 'Facebook', 'unicamp' ); ?></span>
							<?php else: ?>
								<i class="fab fa-facebook-f"></i>
							<?php endif; ?>
						</a>
						<?php
					} elseif ( $social === 'twitter' ) {
						?>
						<a class="<?php echo esc_attr( $link_classes . ' twitter' ); ?>"
						   target="<?php echo esc_attr( $args['target'] ); ?>"
						   aria-label="<?php esc_attr_e( 'Twitter', 'unicamp' ); ?>"
						   href="https://twitter.com/share?text=<?php echo rawurlencode( html_entity_decode( get_the_title(), ENT_COMPAT, 'UTF-8' ) ); ?>&url=<?php echo rawurlencode( get_permalink() ); ?>">
							<?php if ( $args['style'] === 'text' ) : ?>
								<span><?php esc_html_e( 'Twitter', 'unicamp' ); ?></span>
							<?php else: ?>
								<i class="fab fa-twitter"></i>
							<?php endif; ?>
						</a>
						<?php
					} elseif ( $social === 'tumblr' ) {
						?>
						<a class="<?php echo esc_attr( $link_classes . ' tumblr' ); ?>"
						   target="<?php echo esc_attr( $args['target'] ); ?>"
						   aria-label="<?php esc_attr_e( 'Tumblr', 'unicamp' ); ?>"
						   href="https://www.tumblr.com/share/link?url=<?php echo rawurlencode( get_permalink() ); ?>&amp;name=<?php echo rawurlencode( get_the_title() ); ?>">
							<?php if ( $args['style'] === 'text' ) : ?>
								<span><?php esc_html_e( 'Tumblr', 'unicamp' ); ?></span>
							<?php else: ?>
								<i class="fab fa-tumblr-square"></i>
							<?php endif; ?>
						</a>
						<?php

					} elseif ( $social === 'linkedin' ) {
						?>
						<a class="<?php echo esc_attr( $link_classes . ' linkedin' ); ?>"
						   target="<?php echo esc_attr( $args['target'] ); ?>"
						   aria-label="<?php esc_attr_e( 'Linkedin', 'unicamp' ); ?>"
						   href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo rawurlencode( get_permalink() ); ?>&amp;title=<?php echo rawurlencode( get_the_title() ); ?>">
							<?php if ( $args['style'] === 'text' ) : ?>
								<span><?php esc_html_e( 'Linkedin', 'unicamp' ); ?></span>
							<?php else: ?>
								<i class="fab fa-linkedin"></i>
							<?php endif; ?>
						</a>
						<?php
					} elseif ( $social === 'email' ) {
						?>
						<a class="<?php echo esc_attr( $link_classes . ' email' ); ?>"
						   target="<?php echo esc_attr( $args['target'] ); ?>"
						   aria-label="<?php esc_attr_e( 'Email', 'unicamp' ); ?>"
						   href="mailto:?subject=<?php echo rawurlencode( get_the_title() ); ?>&amp;body=<?php echo rawurlencode( get_permalink() ); ?>">
							<?php if ( $args['style'] === 'text' ) : ?>
								<span><?php esc_html_e( 'Email', 'unicamp' ); ?></span>
							<?php else: ?>
								<i class="fas fa-envelope"></i>
							<?php endif; ?>
						</a>
						<?php
					}
				}
			}
		}
	}

	public static function social_icons( $args = array() ) {
		$defaults    = array(
			'link_classes'     => '',
			'display'          => 'icon',
			'tooltip_enable'   => true,
			'tooltip_position' => 'top',
			'tooltip_skin'     => '',
		);
		$args        = wp_parse_args( $args, $defaults );
		$social_link = Unicamp::setting( 'social_link' );

		if ( ! empty( $social_link ) ) {
			$social_link_target = Unicamp::setting( 'social_link_target' );

			$args['link_classes'] .= ' social-link';
			if ( $args['tooltip_enable'] ) {
				$args['link_classes'] .= ' hint--bounce';
				$args['link_classes'] .= " hint--{$args['tooltip_position']}";

				if ( $args['tooltip_skin'] !== '' ) {
					$args['link_classes'] .= " hint--{$args['tooltip_skin']}";
				}
			}

			foreach ( $social_link as $key => $row_values ) {
				?>
				<a class="<?php echo esc_attr( $args['link_classes'] ); ?>"
					<?php if ( $args['tooltip_enable'] ) : ?>
						aria-label="<?php echo esc_attr( $row_values['tooltip'] ); ?>"
					<?php endif; ?>
                   href="<?php echo esc_url( $row_values['link_url'] ); ?>"
					<?php if ( $social_link_target === '1' ) : ?>
						target="_blank"
					<?php endif; ?>
                   rel="nofollow"
				>
					<?php if ( in_array( $args['display'], array( 'icon', 'icon_text' ), true ) ) : ?>
						<i class="social-icon <?php echo esc_attr( $row_values['icon_class'] ); ?>"></i>
					<?php endif; ?>
					<?php if ( in_array( $args['display'], array( 'text', 'icon_text' ), true ) ) : ?>
						<span class="social-text"><?php echo esc_html( $row_values['tooltip'] ); ?></span>
					<?php endif; ?>
				</a>
				<?php
			}
		}
	}

	public static function string_limit_words( $string, $word_limit ) {
		$words = explode( ' ', $string, $word_limit + 1 );
		if ( count( $words ) > $word_limit ) {
			array_pop( $words );
		}

		return implode( ' ', $words );
	}

	public static function string_limit_characters( $string, $limit ) {
		$string = substr( $string, 0, $limit );
		$string = substr( $string, 0, strripos( $string, " " ) );

		return $string;
	}

	public static function get_excerpt( $args = array() ) {
		$defaults = array(
			'post'  => null,
			'limit' => 55,
			'after' => '&hellip;',
			'type'  => 'word',
		);
		$args     = wp_parse_args( $args, $defaults );

		$excerpt = '';

		if ( $args['type'] === 'word' ) {
			$excerpt = self::string_limit_words( get_the_excerpt( $args['post'] ), $args['limit'] );
		} elseif ( $args['type'] === 'character' ) {
			$excerpt = self::string_limit_characters( get_the_excerpt( $args['post'] ), $args['limit'] );
		}

		if ( $excerpt !== '' && $excerpt !== '&nbsp;' ) {
			$excerpt = sprintf( '<p>%s %s</p>', $excerpt, $args['after'] );

			return $excerpt;
		}
	}

	public static function excerpt( $args = array() ) {
		echo self::get_excerpt( $args );
	}

	public static function image_placeholder( $width, $height ) {
		echo '<img src="https://via.placeholder.com/' . $width . 'x' . $height . '?text=' . esc_attr__( 'No+Image', 'unicamp' ) . '" alt="' . esc_attr__( 'Thumbnail', 'unicamp' ) . '"/>';
	}

	public static function render_button( $args ) {
		$defaults = [
			'wrapper'            => true,
			'wrapper_class'      => '',
			'text'               => '',
			'link'               => [
				'url'         => '',
				'is_external' => false,
				'nofollow'    => false,
			],
			'style'              => 'flat',
			'size'               => 'nm',
			'full_wide'          => false,
			'icon'               => '',
			'icon_align'         => 'left',
			'extra_class'        => '',
			'class'              => 'tm-button',
			'id'                 => '',
			'wrapper_attributes' => [],
			'attributes'         => [],
			'echo'               => true,
		];

		$args = wp_parse_args( $args, $defaults );
		extract( $args );

		$button_attrs = wp_parse_args( [], $attributes );

		$button_classes   = [ $class ];
		$button_classes[] = 'style-' . $style;
		$button_classes[] = 'tm-button-' . $size;

		if ( $full_wide ) {
			$button_classes[] = 'tm-button-full-wide';
		}

		if ( ! empty( $extra_class ) ) {
			$button_classes[] = $extra_class;
		}

		if ( ! empty( $icon ) ) {
			$button_classes[] = 'icon-' . $icon_align;
		}

		$button_attrs['class'] = implode( ' ', $button_classes );

		if ( ! empty( $id ) ) {
			$button_attrs['id'] = $id;
		}

		$button_tag = 'div';

		if ( ! empty( $link['url'] ) ) {
			$button_tag = 'a';

			$button_attrs['href'] = $link['url'];

			if ( ! empty( $link['is_external'] ) ) {
				$button_attrs['target'] = '_blank';
			}

			if ( ! empty( $link['nofollow'] ) ) {
				$button_attrs['rel'] = 'nofollow';
			}
		}

		$attributes_str = '';

		if ( ! empty( $button_attrs ) ) {
			foreach ( $button_attrs as $attribute => $value ) {
				$attributes_str .= ' ' . $attribute . '="' . esc_attr( $value ) . '"';
			}
		}

		$wrapper_classes = 'tm-button-wrapper';
		if ( ! empty( $wrapper_class ) ) {
			$wrapper_classes .= " $wrapper_class";
		}

		$wrapper_attributes_str = '';
		if ( ! empty( $wrapper_attributes ) ) {
			foreach ( $wrapper_attributes as $attribute => $value ) {
				$wrapper_attributes_str .= ' ' . $attribute . '="' . esc_attr( $value ) . '"';
			}
		}

		ob_start();
		?>
		<?php printf( '<%1$s %2$s>', $button_tag, $attributes_str ); ?>
		<div class="button-content-wrapper">

			<?php if ( ! empty( $icon ) && 'left' === $icon_align ): ?>
				<span class="button-icon"><i class="<?php echo esc_attr( $icon ); ?>"></i></span>
			<?php endif; ?>

			<?php if ( ! empty( $text ) ): ?>
				<span class="button-text"><?php echo esc_html( $text ); ?></span>
			<?php endif; ?>

			<?php if ( ! empty( $icon ) && 'right' === $icon_align ): ?>
				<span class="button-icon"><i class="<?php echo esc_attr( $icon ); ?>"></i></span>
			<?php endif; ?>
		</div>
		<?php printf( '</%1$s>', $button_tag ); ?>
		<?php
		$output = ob_get_clean();

		if ( $wrapper ) {
			$output = sprintf( '<div class="%1$s" %2$s>%3$s</div>', esc_attr( $wrapper_classes ), $wrapper_attributes_str, $output );
		}

		if ( $echo ) {
			echo '' . $output;
		}

		return $output;
	}
}
