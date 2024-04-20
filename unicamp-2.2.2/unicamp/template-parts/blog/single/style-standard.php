<?php
/**
 * Template part for displaying single post for standard style.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Unicamp
 * @since   1.0
 */

defined( 'ABSPATH' ) || exit;

$sidebar_status = Unicamp_Global::instance()->get_sidebar_status();
?>
<?php if ( 'none' === $sidebar_status ) : ?>
	<div
		class="entry-header <?php echo '1' === Unicamp::setting( 'single_post_feature_enable' ) ? 'featured-on' : 'featured-off'; ?>">
		<?php Unicamp_Post::instance()->entry_categories(); ?>

		<?php if ( '1' === Unicamp::setting( 'single_post_title_enable' ) ) : ?>
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php endif; ?>

		<?php unicamp_load_template( 'blog/single/meta' ); ?>

		<div class="entry-header-featured">
			<?php Unicamp_Post::instance()->entry_feature(); ?>
		</div>
	</div>
<?php endif; ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-wrapper' ); ?>>

	<h2 class="screen-reader-text"><?php echo esc_html( get_the_title() ); ?></h2>

	<?php if ( 'none' !== $sidebar_status ) : ?>
		<div
			class="entry-header <?php echo '1' === Unicamp::setting( 'single_post_feature_enable' ) ? 'featured-on' : 'featured-off'; ?>">
			<div class="entry-header-featured">
				<?php Unicamp_Post::instance()->entry_feature(); ?>
			</div>

			<?php Unicamp_Post::instance()->entry_categories(); ?>

			<?php if ( '1' === Unicamp::setting( 'single_post_title_enable' ) ) : ?>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php endif; ?>

			<?php unicamp_load_template( 'blog/single/meta' ); ?>
		</div>
	<?php endif; ?>

	<div class="entry-content">
		<?php
		the_content( sprintf( /* translators: %s: Name of current post. */
			wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'unicamp' ), array( 'span' => array( 'class' => array() ) ) ), the_title( '<span class="screen-reader-text">"', '"</span>', false ) ) );

		Unicamp_Templates::page_links();
		?>
	</div>

	<div class="entry-footer">
		<div class="row row-xs-center">
			<div class="col-md-6">
				<?php Unicamp_Post::instance()->entry_tags(); ?>
			</div>
			<div class="col-md-6">
				<?php Unicamp_Post::instance()->entry_share(); ?>
			</div>
		</div>
	</div>

	<?php
	$author_desc = get_the_author_meta( 'description' );
	if ( '1' === Unicamp::setting( 'single_post_author_box_enable' ) && ! empty( $author_desc ) ) {
		Unicamp_Templates::post_author();
	}

	if ( '1' === Unicamp::setting( 'single_post_pagination_enable' ) ) {
		Unicamp_Post::instance()->nav_page_links();
	}

	if ( Unicamp::setting( 'single_post_related_enable' ) ) {
		unicamp_load_template( 'blog/single/related' );
	}

	// If comments are open or we have at least one comment, load up the comment template.
	if ( '1' === Unicamp::setting( 'single_post_comment_enable' ) && ( comments_open() || get_comments_number() ) ) :
		comments_template();
	endif;
	?>
</article>
