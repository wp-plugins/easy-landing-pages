<?php

/**
 * Shortcode for our KOL embeded form
 * @param $set_attributes
 */
function kol_embeded_form_shortcode( $set_attributes ) {
	// Get our attributes
	$attributes = shortcode_atts( array( 'id' => null, 'height' => 800 ), $set_attributes );

	// Short circuit if we do not have an id
	if( is_null( $attributes[ 'id' ] ) ) {
		return;
	}

	// Buffer our output
	ob_start();
	// Include our template
	include KICKOFFLABS_TEMPLATES . 'default-embeded-form.php';
	// Return the buffer
	return ob_get_clean();
}
add_shortcode( 'kol_embeded_form', 'kol_embeded_form_shortcode' );