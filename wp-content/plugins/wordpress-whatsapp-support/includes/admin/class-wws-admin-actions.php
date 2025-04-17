<?php
// Preventing to direct access
defined( 'ABSPATH' ) OR die( 'Direct access not acceptable!' );

class WWS_Admin_Actions {

	public function __construct() {
		add_action( 'admin_init', array( $this, 'reset_settings' ) );
		add_action( 'admin_init', array( $this, 'admin_plugin_review' ) );
		add_action( 'admin_init', array( $this, 'dismiss_plugin_update_notice' ) );
		add_action( 'admin_init', array( $this, 'export_analytics_csv' ) );
	}

	/**
	 * Reset plugin setting to default.
	 */
	public function reset_settings() {
		if ( isset( $_GET['wws_action'] ) && 'wws_reset_settings' === $_GET['wws_action'] && wp_verify_nonce( $_GET['_wpnonce'] ) ) {
			require_once WWS_PLUGIN_PATH . 'includes/class-wws-install.php';

			foreach ( WWS_Install::default_options() as $name => $value ) {
				update_option( $name, $value );
			}

			wp_safe_redirect( wp_get_referer() );
		}
	}

	/**
	 * Admin plugin review
	 */
	public function admin_plugin_review() {
		if ( isset( $_GET['wws_action'] ) && 'wws_admin_plugin_reviewed' === $_GET['wws_action'] && wp_verify_nonce( $_GET['_wpnonce'] ) ) {
			update_option( 'wws_admin_plugin_review', 'yes' );
			wp_redirect( wp_get_referer() );
		}
	}

	/**
	 * Dismiss admin plugin update notice.
	 */
	public function dismiss_plugin_update_notice() {
		if ( isset( $_GET['wws_action'] ) && 'dismiss_plugin_update_notice' === $_GET['wws_action'] && wp_verify_nonce( $_GET['_wpnonce'] ) ) {
			update_option( 'wws_plugin_update_notice_status', 'yes' );
			wp_redirect( wp_get_referer() );
		}
	}


	/**
	 * Export analytics CSV
	 *
	 * @global $wpdb    WordPress database connection
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function export_analytics_csv() {
		global $wpdb;

		if ( isset( $_GET['wws_action'] ) && 'export_analytics_csv' === $_GET['wws_action'] && wp_verify_nonce( $_GET['_wpnonce'] ) ) {
			$analytics = WWS_Analytics::get_complete_analytics( 'ASC' );
			$file_name = "wws-analytics-" . current_time( 'Y-m-d_H-i-s' ) . ".csv";

			array_unshift( $analytics, array_keys( current( $analytics ) ) );

			// file creation
			$file = fopen( $file_name, "w" );

			foreach ( $analytics as $line ){
				fputcsv( $file, $line );
			}

			fclose( $file );

			// download
			header( "Content-Description: File Transfer" );
			header( "Content-Disposition: attachment; filename=" . $file_name );
			header( "Content-Type: application/csv; " );

			readfile( $file_name );

			// deleting file
			unlink( $file_name );
			exit;
		}
	}

}

$wws_admin_actions = new WWS_Admin_Actions;
