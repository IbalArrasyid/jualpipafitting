<?php

// Preventing to direct access
defined( 'ABSPATH' ) OR die( 'Direct access not acceptable!' );

/**
 * Add plugin menus
 * @author WeCreativez
 * @since 1.2
 */
class WWS_Admin_Init {
    
    public $settings_api;

	public function __construct() {
		$this->settings_api = new WWS_Admin_Settings_API;

		add_action( 'admin_init', array( $this, 'init_settings' ), 20 );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}

	public function init_settings() {
		$sections = apply_filters(
			'wws_admin_setting_sections',
			array(
				array( 'id' => 'wws_appearance_settings' ),
				array( 'id' => 'wws_basic_settings' ),
				array( 'id' => 'wws_manage_support_persons_settings' ),
				array( 'id' => 'wws_widget_text_settings' ),
				array( 'id' => 'wws_developer_settings' ),
				array( 'id' => 'wws_product_query_settings' ),
				array( 'id' => 'wws_gdpr_settings' ),
				array( 'id' => 'wws_fb_analytics_settings' ),
				array( 'id' => 'wws_ga_analytics_settings' ),
			)
		);

		$fields = apply_filters( 'wws_admin_setting_fields', array(
			'wws_appearance_settings'             => include_once WWS_PLUGIN_PATH . 'includes/admin/views/admin-appearance-settings.php',
			'wws_basic_settings'                  => include_once WWS_PLUGIN_PATH . 'includes/admin/views/admin-basic-settings.php',
			'wws_manage_support_persons_settings' => include_once WWS_PLUGIN_PATH . 'includes/admin/views/admin-manage-support-persons.php',
			'wws_widget_text_settings'            => include_once WWS_PLUGIN_PATH . 'includes/admin/views/admin-widget-text-settings.php',
			'wws_developer_settings'              => include_once WWS_PLUGIN_PATH . 'includes/admin/views/admin-developer-settings.php',
			'wws_product_query_settings'          => include_once WWS_PLUGIN_PATH . 'includes/admin/views/admin-product-query.php',
			'wws_gdpr_settings'                   => include_once WWS_PLUGIN_PATH . 'includes/admin/views/admin-gdpr-setting-page.php',
			'wws_fb_analytics_settings'           => include_once WWS_PLUGIN_PATH . 'includes/admin/views/admin-fb-analytics-settings.php',
			'wws_ga_analytics_settings'           => include_once WWS_PLUGIN_PATH . 'includes/admin/views/admin-ga-analytics-settings.php',
		) );

		$this->settings_api->set_sections( $sections );
		$this->settings_api->set_fields( $fields );
		$this->settings_api->admin_init();
	}

	/**
	 * Add plugin setting menu on WordPress admin menu
	 * @since 1.2
	 */
	public function add_admin_menu() {
		$submenu_pages = array(
			'support_persons'    => array(
				'page_title' => __( 'Support Persons', 'wc-wws' ),
				'menu_title' => __( 'Support Persons', 'wc-wws' ),
				'capability' => 'manage_options',
				'menu_slug'  => 'wc-whatsapp-support-persons',
				'function'   => array( $this, 'admin_support_persons_page' ),
				'position'   => 18,
			),
			'analytics'           => array(
				'page_title' => __( 'Analytics', 'wc-wws' ),
				'menu_title' => __( 'Analytics', 'wc-wws' ),
				'capability' => 'manage_options',
				'menu_slug'  => 'wc-whatsapp-support-analytics',
				'function'   => array( $this, 'admin_analytics_page' ),
				'position'   => 20,
			),
			'fb_and_ga_analytics' => array(
				'page_title' => __( 'FB & GA Analytics', 'wc-wws' ),
				'menu_title' => __( 'FB & GA Analytics', 'wc-wws' ),
				'capability' => 'manage_options',
				'menu_slug'  => 'wc-whatsapp-support-fb-ga-analytics',
				'function'   => array( $this, 'admin_fb_ga_analytics_page' ),
				'position'   => 30,
			),
			'gdpr_settings'       => array(
				'page_title' => __( 'GDPR Setting', 'wc-wws' ),
				'menu_title' => __( 'GDPR Setting', 'wc-wws' ),
				'capability' => 'manage_options',
				'menu_slug'  => 'wc-whatsapp-support-gdpr-setting',
				'function'   => array( $this, 'admin_gdpr_setting_page' ),
				'position'   => 40,
			),
			'plugin_support'      => array(
				'page_title' => __( 'Plugin Support', 'wc-wws' ),
				'menu_title' => __( 'Plugin Support', 'wc-wws' ),
				'capability' => 'manage_options',
				'menu_slug'  => 'wc-whatsapp-support_plugin-support',
				'function'   => array( $this, 'admin_plugin_support_page' ),
				'position'   => 50,
			),
			'developer'           => array(
				'page_title' => __( 'Developer Settings', 'wc-wws' ),
				'menu_title' => null,
				'capability' => 'manage_options',
				'menu_slug'  => 'wc-whatsapp-support_developer-settings',
				'function'   => array( $this, 'admin_developer_settings_page' ),
				'position'   => 60,
			),
		);

		// Hide 'Support Persons' menu if multiple support persons layout not selected.
		if ( 'multiple' !== wws_get_support_type() ) {
			unset( $submenu_pages['support_persons'] );
		}

		/**
		 * Admin submenu pages filter.
		 *
		 * @since 2.0.9
		 *
		 * @param array $submenu_pages Submenu pages.
		 * @return array
		 */
		$submenu_pages = apply_filters( 'wws_admin_submenu_pages', $submenu_pages );

		add_menu_page(
			esc_html__( 'WhatsApp Support', 'wc-wws' ),
			esc_html__( 'WhatsApp Support', 'wc-wws' ),
			'manage_options',
			'wc-whatsapp-support',
			array( $this, 'admin_setting_page' ),
			'dashicons-format-chat',
			NULL
		);

		foreach ( $submenu_pages as $submenu_page ) {
			add_submenu_page(
				'wc-whatsapp-support',
				esc_html( $submenu_page['page_title'] ),
				esc_html( $submenu_page['menu_title'] ),
				esc_html( $submenu_page['capability'] ),
				esc_html( $submenu_page['menu_slug'] ),
				$submenu_page['function'],
				absint( $submenu_page['position'] )
			);
		}
	}

	// Admin general setting page.
	public function admin_setting_page() {
		require_once WWS_PLUGIN_PATH . 'includes/admin/views/admin-settings-page.php';
	}

	// Admin analytics page
	public function admin_analytics_page() {
		$total_clicks            = WWS_Analytics::get_total_clicks();
		$total_clicks_by_mobile  = WWS_Analytics::get_total_clicks_by_mobile();
		$total_clicks_by_desktop = WWS_Analytics::get_total_clicks_by_desktop();
		$current_month_analytics = WWS_Analytics::get_current_month_chart_data();

		require_once WWS_PLUGIN_PATH . 'includes/admin/views/admin-analytics-page.php';
	}

	public function admin_fb_ga_analytics_page() {
		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Facebook and Google Analytics', 'wc-wws' ) . '</h1>';
		settings_errors();
		echo '<hr>';
		$this->settings_api->show_form( 'wws_fb_analytics_settings' );
		$this->settings_api->show_form( 'wws_ga_analytics_settings' );
		echo '</div>';
	}

	// Admin GDPR setting page
	public function admin_gdpr_setting_page() {
		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'GDPR Settings', 'wc-wws' ) . '</h1>';
		settings_errors();
		echo '<hr>';
		$this->settings_api->show_form( 'wws_gdpr_settings' );
		echo '</div>';
	}

	// Admin plugin support page
	public function admin_plugin_support_page() {
		require_once WWS_PLUGIN_PATH . 'includes/admin/views/admin-plugin-support.php';
	}

	public function admin_developer_settings_page() {
		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Developer Settings', 'wc-wws' ) . '</h1>';
		settings_errors();
		echo '<hr>';
		$this->settings_api->show_form( 'wws_developer_settings' );
		echo '</div>';
	}

	public function admin_support_persons_page() {
		if ( isset( $_GET['tab'] ) ) {
			if ( 'edit' === $_GET['tab'] && isset( $_GET['id'] ) ) {
				require_once WWS_PLUGIN_PATH . 'includes/admin/views/support-person/admin-support-person-edit.php';
			} else {
				require_once WWS_PLUGIN_PATH . 'includes/admin/views/support-person/admin-support-person-add.php';
			}
		} else {
			require_once WWS_PLUGIN_PATH . 'includes/admin/class-wws-admin-support-persons-list.php';
			require_once WWS_PLUGIN_PATH . 'includes/admin/views/support-person/admin-support-person-list.php';
		}

	}
} // End of WWS_Admin_Init class

// Init the class
$wws_admin_init = new WWS_Admin_Init;
