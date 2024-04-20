<?php
$display      = $settings['display'];
$display_type = '';
$delay_time   = 3000;
$scroll       = 25;
$scroll_type  = '%';
$anchor       = '';

$shown = false;

if ( ! empty( $display ) ) {
	if ( isset( $display['type'] ) ) {
		$display_type = $display['type'];
		switch ( $display_type ) {
			case 'scroll':
				if ( $display['scroll'] ) {
					$scroll = $display['scroll'];
				}
				if ( $display['scroll_type'] ) {
					$scroll_type = $display['scroll_type'];
				}
				break;
			case 'anchor':
				if ( $display['anchor'] ) {
					$anchor = $display['anchor'];
				}
				break;
			case 'delay':
			default:
				if ( $display['delay'] ) {
					$delay_time = $display['delay'] * 1000;
				}
				break;
		}
	}
}

?>
<div class="insight-popup" id="insight-popup-<?php echo esc_attr__( $popup_id ); ?>"
     style="max-width:<?php echo esc_attr__( $settings['max_width'] ); ?>px">
	<div class="insight-popup-inner">
		<?php echo do_shortcode( $popup->post_content ); ?>
	</div>
	<?php if ( $settings['can_hide'] ) { ?>
		<div class="insight-popup-never-show">
			<label>
				<input type="checkbox" name="never_show"/>
				<span><?php echo esc_html__( 'Never see this message again', 'insight-core' ); ?></span>
			</label>
		</div>
	<?php } ?>
</div>
<script>
	jQuery( document ).ready( function( $ ) {

		var shown = false;

		var popupSetting = {
			items: {
				src: '#insight-popup-<?php echo esc_js( $popup_id );?>', type: 'inline'
			},
			removalDelay: 1000,
			closeMarkup: '<button title="%title%" type="button" class="mfp-close"></button>',
			callbacks: {
				beforeOpen: function() {
					<?php if ($settings['closing_animation'] || $settings['display_animation']) { ?>
					$( '.insight-popup#insight-popup-<?php echo esc_attr__( $popup_id ); ?>' )
					<?php if ($settings['closing_animation']) { ?>
						.removeClass( '<?php echo esc_js( $settings['closing_animation'] );?>' )<?php } ?>
						<?php if ($settings['display_animation']) { ?>
						.addClass( 'animated <?php echo esc_js( $settings['display_animation'] );?>' );<?php } ?>
					<?php } ?>
				}, beforeClose: function() {

					shown = true;

					<?php if ($settings['closing_animation'] || $settings['display_animation']) { ?>
					$( '.insight-popup#insight-popup-<?php echo esc_attr__( $popup_id ); ?>' )
					<?php if ($settings['display_animation']) { ?>
						.removeClass( '<?php echo esc_js( $settings['display_animation'] );?>' )<?php } ?>
						<?php if ($settings['closing_animation']) { ?>
						.addClass( '<?php echo esc_js( $settings['closing_animation'] );?>' );<?php } ?>
					<?php } ?>

					var never_show = $( '.insight-popup-never-show input[type="checkbox"]' ).prop( 'checked' );

					<?php if ($settings['close_hide'] ) { ?>
					never_show = true;
					<?php } ?>

					if ( true === never_show ) {
						Cookies( 'insight_popup_shown_<?php echo esc_js( $popup_id ); ?>', 'true', {
							expires: parseInt(<?php echo esc_js( $settings['expire'] )?>), path: '/'
						} )
					}
				}
			}
		};

		if ( Cookies && 'true' != Cookies( 'insight_popup_shown_<?php echo esc_js( $popup_id ); ?>' ) ) {

			<?php if ('delay' == $display_type && ! $shown) { ?>
			setTimeout( function() {
				$.magnificPopup.open( popupSetting );
			}, <?php echo esc_js( $delay_time )?>);
			<?php } ?>

			<?php if ('scroll' == $display_type && ! $shown ) { ?>
			$( window ).scroll( function( e ) {
				var scrollTop = $( window ).scrollTop();
				var docHeight = $( document ).height();
				var winHeight = $( window ).height();
				var scrollPercent = (
					                    scrollTop
				                    ) / (
					                    docHeight - winHeight
				                    );
				var scrollPercentRounded = Math.round( scrollPercent * 100 );

				if ( 25 == scrollPercentRounded && ! shown ) {
					$.magnificPopup.open( popupSetting );
				}
			} );
			<?php } ?>

			<?php if ('anchor' == $display_type && ! $shown ) { ?>
			$( '<?php echo esc_js( $anchor );?>' ).waypoint( function( direction ) {
				if ( ! shown ) {
					$.magnificPopup.open( popupSetting );
				}
			} );
			<?php } ?>
		}
	} );
</script>