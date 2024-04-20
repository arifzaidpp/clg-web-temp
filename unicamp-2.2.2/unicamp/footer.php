<?php
/**
 * The template for displaying the footer.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Unicamp
 * @since   1.0
 */

?>
</div><!-- /.content-wrapper -->

<?php Unicamp_THA::instance()->footer_before(); ?>

<?php unicamp_load_template( 'footer/entry' ); ?>

<?php Unicamp_THA::instance()->footer_after(); ?>

</div><!-- /.site -->

<?php wp_footer(); ?>
</body>
</html>
