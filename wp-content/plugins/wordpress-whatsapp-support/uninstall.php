<?php
/**
 * Runs on Uninstall.
 */

// Check that we should be doing this
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit; // Exit if accessed directly
}

// Delete all plugin settings, tables, etc.
if ( 'yes' === get_option( 'wws_delete_all' ) ) :
	global $wpdb;

	// Load install class
	require_once 'includes/class-wws-install.php';

	// Delete plugin options
	foreach ( WWS_Install::default_options() as $name => $value ) {
		if ( get_option( $name ) ) {
			delete_option( $name );
		}
	}

	// Delete tables.
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wws_analytics" );

endif;
