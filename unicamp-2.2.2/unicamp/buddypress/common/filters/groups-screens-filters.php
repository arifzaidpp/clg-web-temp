<?php
/**
 * BP Nouveau Groups screens filters
 *
 * @since   3.0.0
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;

$options_output = unicamp_bp_nouveau_get_filter_options();
if ( '' === $options_output ) {
	return;
}
?>
<div id="comp-filters" class="component-filters clearfix">
	<div id="<?php bp_nouveau_filter_container_id(); ?>" class="last filter">
		<label for="<?php bp_nouveau_filter_id(); ?>" class="bp-screen-reader-text">
			<span><?php bp_nouveau_filter_label(); ?></span>
		</label>
		<div class="select-wrap">
			<select id="<?php bp_nouveau_filter_id(); ?>" data-bp-filter="<?php bp_nouveau_filter_component(); ?>">
				<?php echo '' . $options_output; ?>
			</select>
			<span class="select-arrow" aria-hidden="true"></span>
		</div>
	</div>
</div>
