<?php
defined( 'ABSPATH' ) || exit;

/**
 * Filter the single support person contact number by post.
 *
 * @since 1.9.4
 *
 * @param string $support_contact_number
 * @return string
 */
function wws_support_contact_number_by_post( $support_contact_number ) {
	$new_support_contact_number = get_post_meta( get_the_ID(), '_wws_support_contact_number', true );

	if ( $new_support_contact_number ) {
		return $new_support_contact_number; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	return $support_contact_number; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_filter( 'wws_support_contact_number', 'wws_support_contact_number_by_post', 20, 1 );

/**
 * Filter the about us text by post.
 *
 * @since 1.9.4
 *
 * @param string $about_support_text
 * @return string
 */
function wws_about_support_text_by_post( $about_support_text ) {
	$new_about_support_text = get_post_meta( get_the_ID(), '_wws_about_support_text', true );

	if ( $new_about_support_text ) {
		return $new_about_support_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	return $about_support_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_filter( 'wws_about_support_text', 'wws_about_support_text_by_post', 20, 1 );

/**
 * Filter the trigger button text by post.
 *
 * @since 1.9.4
 *
 * @param string $trigger_button_text
 * @return string
 */
function wws_trigger_button_text_by_post( $trigger_button_text ) {
	$new_trigger_button_text = get_post_meta( get_the_ID(), '_wws_trigger_button_text', true );

	if ( $new_trigger_button_text ) {
		return $new_trigger_button_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	return $trigger_button_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_filter( 'wws_trigger_button_text', 'wws_trigger_button_text_by_post', 20, 1 );

/**
 * Filter the predefined text text by post.
 *
 * @since 1.9.6
 *
 * @param string $wws_predefined_text
 * @return string
 */
function wws_predefined_text_by_post( $wws_predefined_text ) {
	$new_wws_predefined_text = get_post_meta( get_the_ID(), '_wws_predefined_text', true );

	if ( $new_wws_predefined_text ) {
		return $new_wws_predefined_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	return $wws_predefined_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_filter( 'wws_predefined_text', 'wws_predefined_text_by_post', 20, 1 );
