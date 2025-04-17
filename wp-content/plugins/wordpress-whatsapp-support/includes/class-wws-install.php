<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Plugin install class
 */
class WWS_Install {

	/**
	 * Plugin install.
	 */
	public static function install() {

		do_action( 'wws_before_install' );

		self::create_tables();
		self::register_options();

		do_action( 'wws_after_install' );

		// update current version.
		update_option( 'wws_version', WWS_PLUGIN_VER );
	}

	/**
	 * Add plugin default setting while plugin activation.
	 */
	public static function register_options() {
		foreach( self::default_options() as $name => $value ) {
			add_option( $name, $value );
		}
	}

	/**
	 * Get the all default plugin options.
	 *
	 * @return array
	 */
	public static function default_options() {
		return array(
			'wws_layout'                                => '1',
			'wws_layout_background_color'               => '#22c15e',
			'wws_layout_text_color'                     => '#ffffff',
			'wws_gradient_status'                       => 'yes',
			'wws_support_person_image'                  => '',
			'wws_trigger_button_only_icon'              => 'no',
			'wws_about_support_text'                    => 'Our customer support team is here to answer your questions. Ask us anything!',
			'wws_welcome_message'                       => '👋 Hi, how can I help?',
			'wws_input_placeholder_text'                => 'Reply to WeCreativez...',
			'wws_number_placeholder_text'               => 'Enter your WhatsApp Number',
			'wws_number_masking'                        => '(999) 999-9999',
			'wws_predefined_text'                       => '{br}'.PHP_EOL .'Page Title: {title}{br}'.PHP_EOL .'Page URL: {url}',
			'wws_trigger_button_text'                   => 'Hi, how can I help?',
			'wws_support_person_available_text'         => 'Available',
			'wws_support_person_not_available_text'     => 'Away',
			'wws_contact_number'                        => '911234567890',
			'wws_group_id'                              => 'XYZ12345678',
			'wws_scroll_length'                         => '',
			'wws_rtl_status'                            => 'no',
			'wws_x_axis_offset'                         => 12,
			'wws_y_axis_offset'                         => 12,
			'wws_display_on_desktop'                    => 'yes',
			'wws_desktop_location'                      => 'br',
			'wws_display_on_mobile'                     => 'yes',
			'wws_mobile_location'                       => 'br',
			'wws_auto_popup'                            => 'yes',
			'wws_auto_popup_time'                       => '5',
			'wws_custom_css'                            => '',
			'wws_filter_by_everywhere'                  => 'yes',
			'wws_filter_by_front_page'                  => 'yes',
			'wws_filter_by_page_id_include'             => array(),
			'wws_filter_by_page_id_exclude'             => array(),
			'wws_filter_by_url_exclude'                 => '',
			'wws_filter_by_schedule'                    => array(
				'mon' => array(
					'status'    => 'yes',
					'start'     => '00:00:00',
					'end'       => '23:59:59'
				),
				'tue' => array(
					'status'    => 'yes',
					'start'     => '00:00:00',
					'end'       => '23:59:59'
				),
				'wed' => array(
					'status'    => 'yes',
					'start'     => '00:00:00',
					'end'       => '23:59:59'
				),
				'thu' => array(
					'status'    => 'yes',
					'start'     => '00:00:00',
					'end'       => '23:59:59'
				),
				'fri' => array(
					'status'    => 'yes',
					'start'     => '00:00:00',
					'end'       => '23:59:59'
				),
				'sat' => array(
					'status'    => 'yes',
					'start'     => '00:00:00',
					'end'       => '23:59:59'
				),
				'sun' => array(
					'status'    => 'yes',
					'start'     => '00:00:00',
					'end'       => '23:59:59'
				)
			),
			'wws_multi_support_person_randomize'        => 'no',
			'wws_multi_support_person_hide_unavailable' => 'no',
			'wws_product_query_status'                  => 'yes',
			'wws_product_query_button_location'         => 'woocommerce_before_add_to_cart_form',
			'wws_product_query_button_background_color' => '#22c15e',
			'wws_product_query_button_text_color'       => '#ffffff',
			'wws_product_query_button_label'            => 'Need Help? Contact Us via WhatsApp',
			'wws_product_query_support_number'                => '911234567890',
			'wws_product_query_support_person_name'           => 'Maya',
			'wws_product_query_support_person_title'          => 'Pre-sale Questions',
			'wws_product_query_support_person_image'          => WWS_PLUGIN_URL . 'assets/img/user.svg',
			'wws_product_query_support_pre_message'           => 'Hi, I need help with {title} {url}',
			'wws_product_query_exclude_by_products'           => array(),
			'wws_product_query_exclude_by_categories'         => array(),
			'wws_gdpr_status'                           => 'no',
			'wws_gdpr_message'                          => 'I agree with the {policy_url}',
			'wws_gdpr_privacy_page'                     => get_option( 'page_on_front' ),
			'wws_fb_click_tracking_status'              => 'no',
			'wws_fb_click_tracking_event_name'          => 'Chat started',
			'wws_fb_click_tracking_event_label'         => 'Support',
			'wws_ga_click_tracking_status'              => 'no',
			'wws_ga_click_tracking_event_name'          => 'Button Clicked',
			'wws_ga_click_tracking_event_category'      => 'WordPress Support Using WhatsApp',
			'wws_ga_click_tracking_event_label'         => 'Support',
			'wws_multi_support_persons'                 => array(),
			'wws_debug_status'                          => 'no',
			'wws_delete_all'                            => 'no',
		);
	}

	public static function create_tables() {
		global $wpdb;

		$wws_analytics_table = $wpdb->prefix.'wws_analytics';

		if($wpdb->get_var("SHOW TABLES LIKE '$wws_analytics_table'") != $wws_analytics_table) {

			//table not in database. Create new table
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $wws_analytics_table (
				id BIGINT NOT NULL AUTO_INCREMENT ,
				visitor_ip VARCHAR(32) NOT NULL ,
				message LONGTEXT NOT NULL ,
				device_type VARCHAR(32) NOT NULL ,
				os VARCHAR(32) NOT NULL,
				browser VARCHAR(32) NOT NULL ,
				date VARCHAR(32) NOT NULL ,
				PRIMARY KEY (id)) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

		}

		if ( $wpdb->get_var( "SHOW COLUMNS FROM $wws_analytics_table LIKE 'referral'" ) != 'referral' ) {
			$wpdb->query( "ALTER TABLE $wws_analytics_table ADD referral LONGTEXT NOT NULL AFTER message" );
		}

		// Add Number column in the analytics table.
		// @since 1.7
		if ( $wpdb->get_var( "SHOW COLUMNS FROM $wws_analytics_table LIKE 'number'" ) != 'number' ) {
			$wpdb->query( "ALTER TABLE $wws_analytics_table ADD number LONGTEXT NOT NULL AFTER visitor_ip" );
		}

		/**
		 * Add through column in the analytics table.
		 *
		 * @since 1.9.7
		 *
		 */
		 if ( $wpdb->get_var( "SHOW COLUMNS FROM $wws_analytics_table LIKE 'through'" ) != 'through' ) {
			$wpdb->query( "ALTER TABLE $wws_analytics_table ADD through VARCHAR(64) NOT NULL AFTER message" );
		}

		/**
		 * Add timestamp column in the analytics table.
		 *
		 * @since 2.0.0
		 *
		 */
		if ( $wpdb->get_var( "SHOW COLUMNS FROM $wws_analytics_table LIKE 'timestamp'" ) != 'timestamp' ) {
			$wpdb->query( "ALTER TABLE $wws_analytics_table ADD timestamp VARCHAR(32) NOT NULL AFTER date" );
		}
	}

	/**
	 * Get plugin options saved by the admin.
	 *
	 * @return array
	 */
	public static function admin_options() {
		$settings = array();

		foreach( self::default_options() as $name => $value ) {
			if ( ! get_option( $name ) ) {
				continue;
			}

			$settings[$name] = get_option( $name );
		}

		return $settings;
	}

	/**
	 * Array merge recursive.
	 *
	 * @param array $args
	 * @param array $defaults
	 *
	 * @return array
	 */
	public static function parse_args_r( &$args, $defaults ) {
		$a = (array) $args;
		$b = (array) $defaults;
		$result = $b;
		foreach ( $a as $k => &$v ) {
			if ( is_array( $v ) && isset( $result[ $k ] ) ) {
				$result[ $k ] = self::parse_args_r( $v, $result[ $k ] );
			} else {
				$result[ $k ] = $v;
			}
		}
		return $result;
	}

}
