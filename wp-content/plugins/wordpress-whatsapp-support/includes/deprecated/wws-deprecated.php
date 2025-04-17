<?php

// Preventing to direct access
defined( 'ABSPATH' ) OR die( 'Direct access not acceptable!' );

/**
 * Defined Plugin ABSPATH
 *
 * @since 1.0
 * @deprecated 1.8.5 Use WWS_PLUGIN_PATH
 */
if ( ! defined( 'WWS_ABSPATH' ) ) {
	define( 'WWS_ABSPATH', plugin_dir_path( WWS_PLUGIN_FILE ) );
}

/**
 * Defined Plugin URL
 *
 * @since 1.0
 * @deprecated 1.8.5 Use WWS_PLUGIN_URL
 */
if ( ! defined( 'WWS_URL' ) ) {
	define( 'WWS_URL', plugin_dir_url( WWS_PLUGIN_FILE ) );
}

/**
 * Defined plugin version
 *
 * @since 1.0
 * @deprecated 1.8.5 Use WWS_PLUGIN_VER
 */
if ( ! defined( 'WWS_VER' ) ) {
	define( 'WWS_VER', WWS_PLUGIN_VER );
}

/**
 * Get the current product url
 * @since 1.0
 * @deprecated 1.8 Use {url}
 *
 */
function wws_deprecated_shortcode_get_product_link() {
	return get_the_permalink( get_the_ID() );
}
add_shortcode( 'wws_product_url', 'wws_deprecated_shortcode_get_product_link' );


/**
 * Get the current product title
 * @since 1.0
 * @deprecated 1.8 Use {title}
 *
 */
function wws_deprecated_shortcode_get_product_title() {
	return get_the_title( get_the_ID() );
}
add_shortcode( 'wws_product_title', 'wws_deprecated_shortcode_get_product_title' );


/**
 * Get the policy page slug for GDPR
 * @since 1.0
 * @deprecated 1.8 Use {title}
 *
 */
function wws_deprecated_shortcode_get_gdpr_link() {

	$gdpr_option = get_option( 'wws_gdpr_settings', array() );

	$gdpr_page_slug         = $gdpr_option ['gdpr_privacy_page'];
	$gdpr_page_data         = get_page_by_path( $gdpr_page_slug );
	$gdpr_page_title        = get_the_title( $gdpr_page_data );
	$gdpr_page_permalink    = site_url( '/'.$gdpr_page_slug.'/' );

	return "<a href='{$gdpr_page_permalink}' target='_blank'>$gdpr_page_title</a>";
}
add_shortcode( 'wws_gdpr_link', 'wws_deprecated_shortcode_get_gdpr_link' );
