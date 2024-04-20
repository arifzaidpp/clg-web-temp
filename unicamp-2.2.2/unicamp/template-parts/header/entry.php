<?php
$type = Unicamp_Global::instance()->get_header_type();

if ( 'none' === $type ) {
	return;
}

if ( ! unicamp_has_elementor_template( 'header' ) ) {
	unicamp_load_template( 'header/header', $type );
}
