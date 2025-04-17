<?php

// Preventing to direct access
defined( 'ABSPATH' ) OR die( 'Direct access not acceptable!' );

final class WWS_Init {

	/**
	 * Initialize.
	 */
	public function init() {

		/**
		 * Hook: wws_before_init.
		 */
		do_action( 'wws_before_init' );

		// Set up localisation.
		$this->load_plugin_textdomain();
		$this->init_hooks();
		$this->includes();

		/**
		 * Hook: wws_after_init.
		 */
		do_action( 'wws_after_init' );
	}

	public function init_hooks() {
		// Plugin page setting link on "Install plugin page"
		add_filter( 'plugin_action_links_'.plugin_basename( WWS_PLUGIN_FILE ), array( $this, 'plugin_page_settings_link' ) );
		add_action( 'init', array( $this, 'upgrader_process_complete' ), 10 );
	}

	/**
	* Includes plugin files.
	*/
	public function includes() {
		// Deprecated Functions, Classes, Shortcodes etc
		require_once WWS_PLUGIN_PATH . 'includes/deprecated/wws-deprecated.php';

		// functions and helpers
		require_once WWS_PLUGIN_PATH . 'includes/wws-functions.php';
		require_once WWS_PLUGIN_PATH . 'includes/helpers/helper-wws-functions.php';

		// Classes
		// Common Classes
		require_once WWS_PLUGIN_PATH . 'includes/class-wws-enqueue-scripts.php';
		require_once WWS_PLUGIN_PATH . 'includes/class-wws-icons.php';

		// Admin classes
		if ( is_admin() ) {

			require_once WWS_PLUGIN_PATH . 'includes/admin/wws-admin-sanitize-functions.php';
			require_once WWS_PLUGIN_PATH . 'includes/admin/class-wws-admin-settings-api.php';
			require_once WWS_PLUGIN_PATH . 'includes/admin/class-wws-admin-init.php';
			require_once WWS_PLUGIN_PATH . 'includes/admin/class-wws-admin-save-settings.php';
			require_once WWS_PLUGIN_PATH . 'includes/admin/class-wws-admin-actions.php';
			require_once WWS_PLUGIN_PATH . 'includes/admin/class-wws-admin-ajax.php';
			require_once WWS_PLUGIN_PATH . 'includes/admin/class-wws-admin-enqueue-scripts.php';
			require_once WWS_PLUGIN_PATH . 'includes/admin/class-wws-admin-visual-composer.php';
			require_once WWS_PLUGIN_PATH . 'includes/admin/class-wws-admin-notifications.php';
			require_once WWS_PLUGIN_PATH . 'includes/admin/class-wws-admin-plugin-updater.php';
			require_once WWS_PLUGIN_PATH . 'includes/admin/class-wws-admin-metabox.php';
			require_once WWS_PLUGIN_PATH . 'includes/admin/class-wws-admin-dasboard-widget.php';
			require_once WWS_PLUGIN_PATH . 'includes/admin/class-wws-admin-analytics-table.php';
		}

		require_once WWS_PLUGIN_PATH . 'includes/class-wws-public-ajax.php';

		// Public
		require_once WWS_PLUGIN_PATH . 'includes/class-wws-support-person.php';
		require_once WWS_PLUGIN_PATH . 'includes/class-wws-analytics.php';
		require_once WWS_PLUGIN_PATH . 'includes/class-wws-widget.php';
		require_once WWS_PLUGIN_PATH . 'includes/class-wws-gdpr-compliance.php';
		require_once WWS_PLUGIN_PATH . 'includes/class-wws-button-generator.php';
		require_once WWS_PLUGIN_PATH . 'includes/class-wws-qr-generator.php';
		require_once WWS_PLUGIN_PATH . 'includes/class-wws-product-query.php';
		require_once WWS_PLUGIN_PATH . 'includes/wws-hook-filters.php';

		require_once WWS_PLUGIN_PATH . 'includes/wws-support-persons-functions.php';

		// Debug class
		require_once WWS_PLUGIN_PATH . 'includes/class-wws-debug.php';

		/**
		 * Dokan Multivendor Compatibility.
		 *
		 * @since 2.0
		 * @link https://wedevs.com/dokan/
		 *
		 */
		if ( class_exists( 'WeDevs_Dokan' ) ) {
			require_once WWS_PLUGIN_PATH . 'includes/compatibility/class-wws-dokan.php';
		}
	}

	/**
	 * Load Localisation files.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wc-wws', false, plugin_basename( dirname( WWS_PLUGIN_FILE ) ) . '/languages' );
	}

	/**
	 * Adding a Settings link to plugin
	 */
	public function plugin_page_settings_link( $links ) {
		$links[] = '<a href="' .
			admin_url( 'admin.php?page=wc-whatsapp-support' ) .
			'">' . esc_html__( 'Settings', 'wc-wws' ) . '</a>';
		return $links;
	}

	/**
	 * This function runs when plugin update.
	 * @since 1.8.8
	 */
	public function upgrader_process_complete() {
		if ( get_option( 'wws_version' ) < WWS_PLUGIN_VER ) {
			wws_plugin_install();
			// update current version.
			update_option( 'wws_version', WWS_PLUGIN_VER );

			update_option( 'wws_plugin_update_notice_status', 'no' );
		}
	}

}
