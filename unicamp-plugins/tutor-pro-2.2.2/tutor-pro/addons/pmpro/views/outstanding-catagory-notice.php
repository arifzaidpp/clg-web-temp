<?php
if ( ! count( $outstanding ) ) {
	return;
}
?>

<style>
	
</style>

<div id="tutor-pmpro-outstanding-categories">
	<div>
		<div>
			<img src="<?php echo TUTOR_PMPRO()->url; ?>assets/images/info.svg"/>
		</div>
		<div>
			<h3><?php _e( 'Tutor course categories not used in any level', 'tutor-pro' ); ?></h3>
			<p><?php _e( 'Some course categories from Tutor LMS are not in any category. Make sure you have them in a category if you want to monetize them. Otherwise, they will be free to access.', 'tutor-pro' ); ?></p>
			
			<div class="tutor-outstanding-cat-holder">
				<?php
				foreach ( $outstanding as $cat ) {
					echo '<span>' . $cat->name . '</span>';
				}
				?>
			</div>
		</div>
	</div>
</div>
