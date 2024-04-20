<?php
$reverse_scheme = null;
if ( isset($args) && isset( $args['reverse_scheme'] ) ) {
	$reverse_scheme = $args['reverse_scheme'];
}
?>
<div <?php Unicamp::branding_class(); ?>>
	<div class="branding-logo-wrap">
		<?php Unicamp::branding_logo( $reverse_scheme ); ?>
	</div>
</div>
