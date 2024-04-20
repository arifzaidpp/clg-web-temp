<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to unicamp-child/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

defined( 'ABSPATH' ) || exit;
?>
<li itemprop="review" itemscope itemtype="https://schema.org/Review" <?php comment_class( 'comment' ); ?>
    id="li-comment-<?php comment_ID() ?>">

	<div id="comment-<?php comment_ID(); ?>" class="comment_container">

		<div class="comment-author">
			<?php
			/**
			 * The woocommerce_review_before hook
			 *
			 * @hooked woocommerce_review_display_gravatar - 10
			 */
			do_action( 'woocommerce_review_before', $comment );
			?>
		</div>

		<div class="comment-content">
			<?php
			/**
			 * The woocommerce_review_meta hook.
			 *
			 * @hooked woocommerce_review_display_meta - 10
			 */
			do_action( 'woocommerce_review_meta', $comment );

			do_action( 'woocommerce_review_before_comment_text', $comment );

			/**
			 * The woocommerce_review_comment_text hook
			 *
			 * @hooked woocommerce_review_display_comment_text - 10
			 */
			do_action( 'woocommerce_review_comment_text', $comment );

			do_action( 'woocommerce_review_after_comment_text', $comment );

			/**
			 * The woocommerce_review_before_comment_meta hook.
			 *
			 * @hooked woocommerce_review_display_rating - 10
			 */
			//do_action( 'woocommerce_review_before_comment_meta', $comment );
			?>

			<div class="comment-footer">
				<time class="woocommerce-review__published-date comment-datetime"
				      datetime="<?php echo get_comment_date( 'c' ); ?>">
					<?php
					printf( esc_html__( '%s at %s', 'unicamp' ), get_comment_date(), get_comment_time() );
					?>
				</time>

				<div class="comment-actions">
					<?php comment_reply_link( array_merge( $args, array(
						'depth'      => $depth,
						'max_depth'  => $args['max_depth'],
						'reply_text' => esc_html__( 'Reply', 'unicamp' ),
					) ) ); ?>
					<?php edit_comment_link( esc_html__( 'Edit', 'unicamp' ) ); ?>
				</div>
			</div>
		</div>
	</div>
