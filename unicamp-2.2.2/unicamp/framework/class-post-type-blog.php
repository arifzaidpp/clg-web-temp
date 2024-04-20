<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Post' ) ) {
	class Unicamp_Post {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			unicamp_require_once( UNICAMP_FRAMEWORK_DIR . '/blog/category-color.php' );

			add_action( 'wp_ajax_post_infinite_load', [ $this, 'infinite_load' ] );
			add_action( 'wp_ajax_nopriv_post_infinite_load', [ $this, 'infinite_load' ] );

			add_filter( 'post_class', [ $this, 'post_class' ] );

			add_filter( 'body_class', [ $this, 'body_class' ] );

			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );

			/**
			 * Special layout for home posts page.
			 */
			add_action( 'pre_get_posts', [ $this, 'change_post_query' ], 999 );

			// Different style for sidebar.
			add_filter( 'unicamp_page_sidebar_class', [ $this, 'sidebar_class' ] );

			// Custom sidebar width.
			add_filter( 'unicamp_one_sidebar_width', [ $this, 'one_sidebar_width' ] );

			// Custom sidebar offset.
			add_filter( 'unicamp_one_sidebar_offset', [ $this, 'one_sidebar_offset' ] );
		}

		public function frontend_scripts() {
			if ( $this->is_archive() ) {
				wp_enqueue_script( 'unicamp-grid-layout' );
			}
		}

		/**
		 * Adds custom classes to the array of body classes.
		 *
		 * @param array $classes Classes for the body element.
		 *
		 * @return array
		 */
		public function body_class( $classes ) {
			$blog_archive_style = Unicamp::setting( 'blog_archive_style' );
			$special_layout_on  = Unicamp::setting( 'blog_archive_special_layout' );

			if ( Unicamp_Post::instance()->is_archive() ) {
				$classes[] = 'blog-archive';

				if ( '1' !== $special_layout_on || ( '1' === $special_layout_on && ! is_home() ) ) {
					$classes[] = "blog-archive-style-{$blog_archive_style}";
				}

				$site_background = Unicamp::setting( 'blog_archive_body_background' );
				if ( ! empty( $site_background ) ) {
					$classes[] = 'site-background-' . $site_background;
				}

				$site_layout = Unicamp::setting( 'blog_archive_site_layout' );
				if ( 'small' === $site_layout ) {
					$classes[] = 'site-content-small';
				}
			}

			if ( is_home() && '1' === $special_layout_on ) {
				$classes[] = 'home-blog-special-layout';
			}

			return $classes;
		}

		/**
		 * @param WP_Query $query
		 */
		public function change_post_query( $query ) {
			if ( ! $query->is_main_query() || is_admin() || ! $this->is_archive() ) {
				return;
			}

			// Numbers per page.
			$numbers = Unicamp::setting( 'blog_archive_posts_per_page', 12 );

			$layout_preset = isset( $_GET['blog_archive_preset'] ) ? Unicamp_Helper::data_clean( $_GET['blog_archive_preset'] ) : false;

			// Hard set post per page. because override preset settings run after init hook.
			if ( $layout_preset ) {
				switch ( $layout_preset ) {
					case 'list-01':
					case 'list-02':
						$numbers = 6;
						break;
				}
			}

			// Change post per page.
			$query->set( 'posts_per_page', apply_filters( 'unicamp_loop_blog_per_page', $numbers ) );
		}

		public function one_sidebar_width( $width ) {
			if ( $this->is_archive() ) {
				$new_width = Unicamp::setting( 'blog_archive_single_sidebar_width' );
			}

			// Use isset instead of empty avoid skip value 0.
			if ( isset( $new_width ) && '' !== $new_width ) {
				return $new_width;
			}

			return $width;
		}

		public function one_sidebar_offset( $offset ) {
			if ( $this->is_archive() ) {
				$new_offset = Unicamp::setting( 'blog_archive_single_sidebar_offset' );
			}

			// Use isset instead of empty avoid skip value 0.
			if ( isset( $new_offset ) && '' !== $new_offset ) {
				return $new_offset;
			}

			return $offset;
		}

		public function sidebar_class( $class ) {
			if ( $this->is_archive() ) {
				$sidebar_style = Unicamp::setting( 'blog_archive_page_sidebar_style' );

				if ( ! empty( $sidebar_style ) ) {
					$class[] = 'style-' . $sidebar_style;
				}
			}

			return $class;
		}

		public function post_class( $classes ) {
			if ( ! has_post_thumbnail() ) {
				$classes[] = 'post-no-thumbnail';
			}

			return $classes;
		}

		public function infinite_load() {
			$source     = isset( $_POST['source'] ) ? $_POST['source'] : '';
			$query_vars = $_POST['query_vars'];

			if ( 'custom_query' === $source ) {
				$query_vars = Unicamp_Helper::build_extra_terms_query( $query_vars, $query_vars['extra_tax_query'] );
			}

			$unicamp_query = new WP_Query( $query_vars );

			$settings = isset( $_POST['settings'] ) ? $_POST['settings'] : array();

			$response = array(
				'max_num_pages' => $unicamp_query->max_num_pages,
				'found_posts'   => $unicamp_query->found_posts,
				'count'         => $unicamp_query->post_count,
			);

			ob_start();

			if ( $unicamp_query->have_posts() ) :
				set_query_var( 'unicamp_query', $unicamp_query );
				set_query_var( 'settings', $settings );

				get_template_part( 'loop/widgets/blog/style', $settings['layout'] );

				wp_reset_postdata();
			endif;

			$template = ob_get_contents();
			ob_clean();

			$response['template'] = $template;

			echo json_encode( $response );

			wp_die();
		}

		public function get_post_type() {
			return 'post';
		}

		/**
		 * Check if current page is category or tag pages
		 */
		function is_taxonomy() {
			return is_category() || is_tag();
		}

		public function is_archive() {
			return $this->is_taxonomy() || is_home() || is_author() || is_date() || is_post_type_archive( $this->get_post_type() );
		}

		function get_related_posts( $args ) {
			$defaults = array(
				'post_id'      => '',
				'number_posts' => 3,
			);
			$args     = wp_parse_args( $args, $defaults );
			if ( $args['number_posts'] <= 0 || $args['post_id'] === '' ) {
				return false;
			}

			$categories = get_the_category( $args['post_id'] );

			if ( ! $categories ) {
				return false;
			}

			foreach ( $categories as $category ) {
				if ( $category->parent === 0 ) {
					$term_ids[] = $category->term_id;
				} else {
					$term_ids[] = $category->parent;
					$term_ids[] = $category->term_id;
				}
			}

			// Remove duplicate values from the array.
			$unique_array = array_unique( $term_ids );

			$query_args = array(
				'post_type'      => $this->get_post_type(),
				'orderby'        => 'date',
				'order'          => 'DESC',
				'posts_per_page' => $args['number_posts'],
				'post__not_in'   => array( $args['post_id'] ),
				'no_found_rows'  => true,
				'tax_query'      => array(
					array(
						'taxonomy'         => 'category',
						'terms'            => $unique_array,
						'include_children' => false,
					),
				),
			);

			$query = new WP_Query( $query_args );

			wp_reset_postdata();

			return $query;
		}

		function get_the_post_meta( $name = '', $default = '' ) {
			$post_meta = get_post_meta( get_the_ID(), 'insight_post_options', true );

			if ( ! empty( $post_meta ) ) {
				$post_options = maybe_unserialize( $post_meta );

				if ( $post_options !== false && isset( $post_options[ $name ] ) ) {
					return $post_options[ $name ];
				}
			}

			return $default;
		}

		function get_the_post_format() {
			$format = '';
			if ( get_post_format() !== false ) {
				$format = get_post_format();
			}

			return $format;
		}

		/**
		 * @param array $args = [
		 *                    string $classes Custom css class
		 *                    string $separator Separator between links
		 *                    boolean $show_links
		 *                    int $number Number of links to show
		 *                    ]
		 */
		function the_categories( $args = array() ) {
			if ( ! has_category() ) {
				return;
			}

			$defaults = array(
				'classes'    => 'post-categories',
				'separator'  => '',
				'show_links' => true,
				'number'     => -1,
			);
			$args     = wp_parse_args( $args, $defaults );
			?>
			<div class="<?php echo esc_attr( $args['classes'] ); ?>">
				<?php
				$categories = get_the_category();
				$loop_count = 0;
				foreach ( $categories as $category ) {
					if ( $loop_count > 0 ) {
						echo "{$args['separator']}";
					}

					$term_color = get_term_meta( $category->term_id, '_category_color', true );

					if ( ! empty( $term_color ) ) {
						$term_color = '#' . $term_color;
						$cat_shape  = '<span class="cat-shape" style="background: ' . $term_color . '"></span>';
					} else {
						$cat_shape = '<span class="cat-shape"></span>';
					}

					$cat_html = $cat_shape . '<span class="cat-name">' . $category->name . '</span>';

					if ( true === $args['show_links'] ) {
						printf( '<a href="%1$s" rel="category tag">%2$s</a>', esc_url( get_category_link( $category->term_id ) ), $cat_html );
					} else {
						echo "{$cat_html}";
					}

					$loop_count++;

					if ( $args['number'] > 0 && $loop_count >= $args['number'] ) {
						break;
					}
				}
				?>
			</div>
			<?php
		}

		function nav_page_links() {
			$thumbnail_size = '370x120';
			?>
			<div class="blog-nav-links">
				<div class="nav-list">
					<div class="nav-item prev">
						<div class="inner">
							<?php
							$prev_post      = get_previous_post();
							$prev_thumbnail = '';
							$class          = 'hover-bg';

							if ( ! empty( $prev_post ) ) {
								$prev_thumbnail = Unicamp_Image::get_the_post_thumbnail_url( [
									'post_id' => $prev_post->ID,
									'size'    => $thumbnail_size,
								] );

								if ( ! empty( $prev_thumbnail ) ) {
									$class          .= ' has-thumbnail';
									$prev_thumbnail = 'style="background-image: url(' . $prev_thumbnail . ');"';
								}
							}

							previous_post_link( '%link', '<div class="' . esc_attr( $class ) . '" ' . $prev_thumbnail . '></div><h6>%title</h6>' );
							?>
						</div>
					</div>

					<div class="nav-item next">
						<div class="inner">
							<?php
							$next_post      = get_next_post();
							$next_thumbnail = '';
							$class          = 'hover-bg';

							if ( ! empty( $next_post ) ) {
								$next_thumbnail = Unicamp_Image::get_the_post_thumbnail_url( array(
									'post_id' => $next_post->ID,
									'size'    => $thumbnail_size,
								) );

								if ( ! empty( $next_thumbnail ) ) {
									$class          .= ' has-thumbnail';
									$next_thumbnail = 'style="background-image: url(' . $next_thumbnail . ');"';
								}
							}

							next_post_link( '%link', '<div class="' . esc_attr( $class ) . '" ' . $next_thumbnail . '></div><h6>%title</h6>' );
							?>
						</div>
					</div>
				</div>
			</div>

			<?php
		}

		function meta_author_template() {
			?>
			<div class="post-meta-author">
				<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>">
					<span class="meta-value"><?php the_author(); ?></span>
				</a>
			</div>
			<?php
		}

		function meta_date_template() {
			?>
			<div class="post-date">
				<span class="meta-value"><?php echo get_the_date( 'M d, Y' ); ?></span>
			</div>
			<?php
		}

		function meta_view_count_template() {
			if ( function_exists( 'the_views' ) ) : ?>
				<div class="post-view">
					<span class="meta-value"><?php the_views(); ?></span>
				</div>
			<?php
			endif;
		}

		function meta_comment_count_template() {
			$comment_count = get_comments_number();
			?>
			<div class="post-comments">
				<span class="meta-value">
					<?php printf(
						_n( '%s comment', '%s comments', $comment_count, 'unicamp' ),
						number_format_i18n( $comment_count ) );
					?>
				</span>
			</div>
			<?php
		}

		function entry_meta_comment_count_template() {
			?>
			<div class="post-comments">
				<a href="#comments" class="smooth-scroll-link">
					<?php
					$comment_count = get_comments_number();
					printf( esc_html( _n( '%s comment', '%s comments', $comment_count, 'unicamp' ) ), number_format_i18n( $comment_count ) );
					?>
				</a>
			</div>
			<?php
		}

		function entry_categories() {
			if ( '1' !== Unicamp::setting( 'single_post_categories_enable' ) || ! has_category() ) {
				return;
			}
			?>
			<div class="entry-post-categories">
				<?php
				$categories = get_the_category();
				foreach ( $categories as $category ) {
					$link = get_term_link( $category );

					$term_color = get_term_meta( $category->term_id, '_category_color', true );

					if ( ! empty( $term_color ) ) {
						$term_color = '#' . $term_color;
						$cat_shape  = '<span class="cat-shape" style="background: ' . $term_color . '"></span>';
					} else {
						$cat_shape = '<span class="cat-shape"></span>';
					}

					$cat_html = $cat_shape . '<span class="cat-name">' . $category->name . '</span>';

					printf( '<a href="%1$s" rel="category tag">%2$s</a>', esc_url( $link ), $cat_html );
				}
				?>
			</div>
			<?php
		}

		function entry_tags() {
			if ( '1' !== Unicamp::setting( 'single_post_tags_enable' ) || ! has_tag() ) {
				return;
			}
			?>
			<div class="entry-post-tags">
				<div class="tagcloud">
					<?php
					$terms = get_the_tags();

					if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
						foreach ( $terms as $term ) {
							$link = get_term_link( $term );
							printf( '<a href="%1$s" rel="tag"><span class="tag-shape">#</span><span class="tag-name">%2$s</span></a>', esc_url( $link ), $term->name );
						}
					}
					?>
				</div>
			</div>
			<?php
		}

		function entry_feature() {
			if ( '1' !== Unicamp::setting( 'single_post_feature_enable' ) ) {
				return;
			}

			$post_format    = $this->get_the_post_format();
			$thumbnail_size = '770x400';

			$sidebar_status = Unicamp_Global::instance()->get_sidebar_status();

			if ( 'none' === $sidebar_status ) {
				$thumbnail_size = '1170x600';
			}

			switch ( $post_format ) {
				case 'gallery':
					$this->entry_feature_gallery( $thumbnail_size );
					break;
				case 'audio':
					$this->entry_feature_audio();
					break;
				case 'video':
					$this->entry_feature_video( $thumbnail_size );
					break;
				case 'quote':
					$this->entry_feature_quote();
					break;
				case 'link':
					$this->entry_feature_link();
					break;
				default:
					$this->entry_feature_standard( $thumbnail_size );
					break;
			}
		}

		private function entry_feature_standard( $size ) {
			if ( ! has_post_thumbnail() ) {
				return;
			}
			?>
			<div class="entry-post-feature post-thumbnail">
				<?php Unicamp_Image::the_post_thumbnail( [ 'size' => $size, ] ); ?>
			</div>
			<?php
		}

		private function entry_feature_gallery( $size ) {
			$gallery = $this->get_the_post_meta( 'post_gallery' );
			if ( empty( $gallery ) ) {
				return;
			}
			?>
			<div class="entry-post-feature post-gallery tm-swiper tm-slider" data-nav="1" data-loop="1"
			     data-lg-gutter="30">
				<div class="swiper-inner">
					<div class="swiper-container">
						<div class="swiper-wrapper">
							<?php foreach ( $gallery as $image ) { ?>
								<div class="swiper-slide">
									<?php Unicamp_Image::the_attachment_by_id( array(
										'id'   => $image['id'],
										'size' => $size,
									) ); ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		private function entry_feature_audio() {
			$audio = Unicamp_Post::instance()->get_the_post_meta( 'post_audio' );
			if ( empty( $audio ) ) {
				return;
			}

			if ( strrpos( $audio, '.mp3' ) !== false ) {
				echo do_shortcode( '[audio mp3="' . $audio . '"][/audio]' );
			} else {
				?>
				<div class="entry-post-feature post-audio">
					<?php if ( wp_oembed_get( $audio ) ) { ?>
						<?php echo Unicamp_Helper::w3c_iframe( wp_oembed_get( $audio ) ); ?>
					<?php } ?>
				</div>
				<?php
			}
		}

		private function entry_feature_video( $size ) {
			$video = $this->get_the_post_meta( 'post_video' );
			if ( empty( $video ) ) {
				return;
			}
			?>
			<div class="entry-post-feature post-video tm-popup-video type-poster unicamp-animation-zoom-in">
				<a href="<?php echo esc_url( $video ); ?>" class="video-link unicamp-box link-secret">
					<div class="video-poster">
						<div class="unicamp-image">
							<?php if ( has_post_thumbnail() ) { ?>
								<?php Unicamp_Image::the_post_thumbnail( [ 'size' => $size, ] ); ?>
							<?php } ?>
						</div>
						<div class="video-overlay"></div>

						<div class="video-button">
							<div class="video-play video-play-icon">
								<span class="icon"></span>
							</div>
						</div>
					</div>
				</a>
			</div>
			<?php
		}

		private function entry_feature_quote() {
			$text = $this->get_the_post_meta( 'post_quote_text' );
			if ( empty( $text ) ) {
				return;
			}
			$name = $this->get_the_post_meta( 'post_quote_name' );
			$url  = $this->get_the_post_meta( 'post_quote_url' );
			?>
			<div class="entry-post-feature post-quote">
				<div class="post-quote-content">
					<span class="quote-icon fas fa-quote-right"></span>
					<h3 class="post-quote-text"><?php echo esc_html( '&ldquo;' . $text . '&rdquo;' ); ?></h3>
					<?php if ( ! empty( $name ) ) { ?>
						<?php $name = "- $name"; ?>
						<h6 class="post-quote-name">
							<?php if ( ! empty( $url ) ) { ?>
								<a href="<?php echo esc_url( $url ); ?>"
								   target="_blank"><?php echo esc_html( $name ); ?></a>
							<?php } else { ?>
								<?php echo esc_html( $name ); ?>
							<?php } ?>
						</h6>
					<?php } ?>
				</div>
			</div>
			<?php
		}

		private function entry_feature_link() {
			$link = $this->get_the_post_meta( 'post_link' );
			if ( empty( $link ) ) {
				return;
			}
			?>
			<div class="entry-post-feature post-link">
				<a href="<?php echo esc_url( $link ); ?>" target="_blank"><?php echo esc_html( $link ); ?></a>
			</div>
			<?php
		}

		function entry_share( $args = array() ) {
			if ( '1' !== Unicamp::setting( 'single_post_share_enable' ) || ! class_exists( 'InsightCore' ) ) {
				return;
			}

			$social_sharing = Unicamp::setting( 'social_sharing_item_enable' );
			if ( empty( $social_sharing ) ) {
				return;
			}
			?>
			<div class="entry-post-share">
				<div class="post-share style-01">
					<div class="share-label heading">
						<?php esc_html_e( 'Share this post', 'unicamp' ); ?>
					</div>
					<div class="share-media">
						<span class="share-icon fas fa-share-alt"></span>

						<div class="share-list">
							<?php Unicamp_Templates::get_sharing_list( $args ); ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		function loop_share( $args = array() ) {
			if ( ! class_exists( 'InsightCore' ) ) {
				return;
			}

			$social_sharing = Unicamp::setting( 'social_sharing_item_enable' );
			if ( empty( $social_sharing ) ) {
				return;
			}
			?>
			<div class="post-share style-01">
				<div class="share-label">
					<?php esc_html_e( 'Share this post', 'unicamp' ); ?>
				</div>
				<div class="share-media">
					<span class="share-icon fas fa-share-alt"></span>

					<div class="share-list">
						<?php Unicamp_Templates::get_sharing_list( $args ); ?>
					</div>
				</div>
			</div>
			<?php
		}
	}

	Unicamp_Post::instance()->initialize();
}
