<?php

// Preventing to direct access
defined( 'ABSPATH' ) OR die( 'Direct access not acceptable!' );

class WWS_Admin_Notifications {

	public function __construct() {
		add_action( 'admin_notices', array( $this, 'plugin_update_notification' ) );
		add_action( 'wws_admin_notifications', array( $this, 'save_settings_notifications' ), 20 );
		add_action( 'wws_admin_notifications', array( $this, 'review_notification' ), 25 );
	}

	public function save_settings_notifications() {
		if ( isset( $_POST['wws_layout'] ) ) {
			?>
				<div class="notice notice-success is-dismissible">
					<p><?php wp_kses_post( printf( __( 'Settings saved. If you changed the layout then please go to <a href="%s">Manage Support Persons</a> to add or modify the support persons.', 'wc-wws' ), admin_url( 'admin.php?page=wc-whatsapp-support&tab=manage_support_persons' ) ) ) ?></p>
				</div>
			<?php
		}
	}

	public function review_notification() {
		if ( 'yes' !== get_option( 'wws_admin_plugin_review' ) ) {
			require_once WWS_PLUGIN_PATH . 'includes/admin/views/notifications/admin-review.php';
		}
	}

	public function plugin_update_notification() {
		if ( 'yes' !== get_option( 'wws_plugin_update_notice_status' ) ) {
			require_once WWS_PLUGIN_PATH . 'includes/admin/views/notifications/view-admin-update-plugin-notice.php';
		}
	}

} // end of class WWS_Admin_Notifications

$wws_admin_notifications = new WWS_Admin_Notifications;
