<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the selected support type.
 *
 * @since 2.4.0
 *
 * @return string|bool Support type `single`, `multiple`, and `group`, false otherwise.
 */
function wws_get_support_type() {
	$layout = get_option( 'wws_layout' );

	if ( in_array( $layout, array( 1, 2, 3, 4, 7 ) ) ) {
		return 'single';
	} else if ( in_array( $layout, array( 6, 8 ) ) ) {
		return 'multiple';
	} else if ( in_array( $layout, array( 5 ) ) ) {
		return 'group';
	}

	return false;
}

/**
 * Clean shortcode input.
 *
 * @since 2.4.3
 *
 * @param string $string String to clean.
 * @return string Cleaned string.
 */
function wws_clean_shortcode_input( $string ) {
	$string = str_replace( '[', '&#91;', $string );
	$string = str_replace( ']', '&#93;', $string );

	$string = apply_filters( __FUNCTION__, $string );

	return $string;
}
