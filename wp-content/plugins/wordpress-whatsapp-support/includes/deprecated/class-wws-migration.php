<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * This class is responsiable for the plugin migration with old version.
 *
 * @since 1.8.7
 */
class WWS_Migration {

	protected $db_plugin_version = '';

	public function __construct() {
		add_action( 'wws_before_install', array( $this, 'migration_options_1_8_7' ), 1 );
		add_action( 'wws_before_install', array( $this, 'migration_options_2_4_0' ), 2 );
	}

	public function migration_options_1_8_7() {

		/**
		 * Previous installed version is then 1.8.7 if wws_version is not set.
		 *
		 * wws_version introduced in version 1.8.7
		 *
		 **/
		if ( get_option( 'wws_version' ) ) {
			return;
		}

		/**
		 * If older version was never installed then no need to run migration.
		 */
		if ( ! get_option( 'sk_wws_setting' ) ) {
			return;
		}

		// Main settings migration
		$sk_wws_setting = get_option( 'sk_wws_setting', array() );
		foreach( $sk_wws_setting as $name => $value ) {
			if ( $name === 'ui_layout' ) {
				$this->add_option( 'wws_layout', $value );
			}
			if ( $name === 'ui_layout_bg_color' ) {
				$this->add_option( 'wws_layout_background_color', $value );
			}
			if ( $name === 'ui_layout_text_color' ) {
				$this->add_option( 'wws_layout_text_color', $value );
			}
			if ( $name === 'ui_layout_gradient' ) {
				$this->add_option( 'wws_gradient_status', ( $value == '0') ? 'no' : 'yes' );
			}
			if ( $name === 'ui_support_person_img' ) {
				$this->add_option( 'wws_support_person_image', $value );
			}
			if ( $name === 'ul_trigger_btn_only_icon' ) {
				$this->add_option( 'wws_trigger_button_only_icon', ( $value == '0') ? 'no' : 'yes' );
			}
			if ( $name === 'text_about_support' ) {
				$this->add_option( 'wws_about_support_text', $value );
			}
			if ( $name === 'text_welcome_msg' ) {
				$this->add_option( 'wws_welcome_message', $value );
			}
			if ( $name === 'text_input_placeholder' ) {
				$this->add_option( 'wws_input_placeholder_text', $value );
			}
			if ( $name === 'text_number_placeholder' ) {
				$this->add_option( 'wws_number_placeholder_text', $value );
			}
			if ( $name === 'text_predefined_text' ) {
				$this->add_option( 'wws_predefined_text', $value );
			}
			if ( $name === 'text_trigger_btn' ) {
				$this->add_option( 'wws_trigger_button_text', $value );
			}
			if ( $name === 'wws_contact_number' ) {
				$this->add_option( 'wws_contact_number', $value );
			}
			if ( $name === 'wws_group_id' ) {
				$this->add_option( 'wws_group_id', $value );
			}
			if ( $name === 'wws_scroll_length' ) {
				$this->add_option( 'wws_scroll_length', $value );
			}
			if ( $name === 'wws_rtl' ) {
				$this->add_option( 'wws_rtl_status', ( $value == '0') ? 'no' : 'yes' );
			}
			if ( $name === 'wws_x_axis_offset' ) {
				$this->add_option( 'wws_x_axis_offset', $value );
			}
			if ( $name === 'wws_y_axis_offset' ) {
				$this->add_option( 'wws_y_axis_offset', $value );
			}
			if ( $name === 'wws_display_on_desktop' ) {
				$this->add_option( 'wws_display_on_desktop', ( $value == '0') ? 'no' : 'yes' );
			}
			if ( $name === 'wws_desktop_location' ) {
				$this->add_option( 'wws_desktop_location', $value );
			}
			if ( $name === 'wws_display_on_mobile' ) {
				$this->add_option( 'wws_display_on_mobile', ( $value == '0') ? 'no' : 'yes' );
			}
			if ( $name === 'wws_mobile_location' ) {
				$this->add_option( 'wws_mobile_location', $value );
			}
			if ( $name === 'wws_auto_popup' ) {
				$this->add_option( 'wws_auto_popup', ( $value == '0') ? 'no' : 'yes' );
			}
			if ( $name === 'wws_auto_popup_time' ) {
				$this->add_option( 'wws_auto_popup_time', $value );
			}
			if ( $name === 'wws_custom_css' ) {
				$this->add_option( 'wws_custom_css', $value );
			}
			if ( $name === 'wws_filter_by_page' ) {
				$wws_filter_by_page = get_option( 'sk_wws_setting', array() );

				if ( isset( $wws_filter_by_page['wws_filter_by_page']['by_slugs'] )  ) {
					$slug_to_id = array();
					$slugs      = $wws_filter_by_page['wws_filter_by_page']['by_slugs'];

					foreach( $slugs as $slug ) {
						$slug_to_id[] = (string)$this->get_id_by_slug( $slug );
					}

					$this->add_option( 'wws_filter_by_page_id_include', $slug_to_id );
				}
				if ( isset( $wws_filter_by_page['wws_filter_by_page']['by_slugs_exclude'] ) ) {
					$slug_to_id = array();
					$slugs      = $wws_filter_by_page['wws_filter_by_page']['by_slugs_exclude'];

					foreach( $slugs as $slug ) {
						$slug_to_id[] = (string)$this->get_id_by_slug( $slug );
					}

					$this->add_option( 'wws_filter_by_page_id_exclude', $slug_to_id );
				}
				if ( isset( $wws_filter_by_page['wws_filter_by_page']['by_front_page'] ) ) {
					$this->add_option( 'wws_filter_by_front_page', ( $wws_filter_by_page['wws_filter_by_page']['by_front_page'] == '0') ? 'no' : 'yes' );
				}
				if ( isset( $wws_filter_by_page['wws_filter_by_page']['by_everywhere'] ) ) {
					$this->add_option( 'wws_filter_by_everywhere', ( $wws_filter_by_page['wws_filter_by_page']['by_everywhere'] == '0') ? 'no' : 'yes' );
				}
			}
			if ( $name === 'wws_disable_by_url' ) {
				$this->add_option( 'wws_filter_by_url_exclude', $value );
			}
			if ( $name === 'wws_schedule' ) {
				$wws_schedule       = get_option( 'sk_wws_setting', array() );

				if ( isset( $wws_schedule['wws_schedule'] ) ) {
					$wws_new_schedule   = array(
						'mon'  => array(
							'status'    => ( $wws_schedule['wws_schedule']['mon']['is_enable'] == '0' ) ? 'no' : 'yes',
							'start'     => $wws_schedule['wws_schedule']['mon']['start'],
							'end'       => $wws_schedule['wws_schedule']['mon']['end'],
						),
						'tue'  => array(
							'status'    => ( $wws_schedule['wws_schedule']['tue']['is_enable'] == '0' ) ? 'no' : 'yes',
							'start'     => $wws_schedule['wws_schedule']['tue']['start'],
							'end'       => $wws_schedule['wws_schedule']['tue']['end'],
						),
						'wed'  => array(
							'status'    => ( $wws_schedule['wws_schedule']['wed']['is_enable'] == '0' ) ? 'no' : 'yes',
							'start'     => $wws_schedule['wws_schedule']['wed']['start'],
							'end'       => $wws_schedule['wws_schedule']['wed']['end'],
						),
						'thu'  => array(
							'status'    => ( $wws_schedule['wws_schedule']['thu']['is_enable'] == '0' ) ? 'no' : 'yes',
							'start'     => $wws_schedule['wws_schedule']['thu']['start'],
							'end'       => $wws_schedule['wws_schedule']['thu']['end'],
						),
						'fri'  => array(
							'status'    => ( $wws_schedule['wws_schedule']['fri']['is_enable'] == '0' ) ? 'no' : 'yes',
							'start'     => $wws_schedule['wws_schedule']['fri']['start'],
							'end'       => $wws_schedule['wws_schedule']['fri']['end'],
						),
						'sat'  => array(
							'status'    => ( $wws_schedule['wws_schedule']['sat']['is_enable'] == '0' ) ? 'no' : 'yes',
							'start'     => $wws_schedule['wws_schedule']['sat']['start'],
							'end'       => $wws_schedule['wws_schedule']['sat']['end'],
						),
						'sun'  => array(
							'status'    => ( $wws_schedule['wws_schedule']['sun']['is_enable'] == '0' ) ? 'no' : 'yes',
							'start'     => $wws_schedule['wws_schedule']['sun']['start'],
							'end'       => $wws_schedule['wws_schedule']['sun']['end'],
						),
					);
					$this->add_option( 'wws_filter_by_schedule', $wws_new_schedule );
				}
			}
		}

		// Product query settings migration
		$wws_product_query = get_option( 'wws_product_query', array() );
		foreach( $wws_product_query as $name => $value ) {
			if ( $name === 'wws_product_query' ) {
				$this->add_option( 'wws_product_query_status', ( $value == '0') ? 'no' : 'yes' );
			}
			if ( $name === 'btn_location' ) {
				$this->add_option( 'wws_product_query_button_location', $value );
			}
			if ( $name === 'btn_bg_color' ) {
				$this->add_option( 'wws_product_query_button_background_color', $value );
			}
			if ( $name === 'btn_text_color' ) {
				$this->add_option( 'wws_product_query_button_text_color', $value );
			}
			if ( $name === 'btn_label' ) {
				$this->add_option( 'wws_product_query_button_label', $value );
			}
			if ( $name === 'support_number' ) {
				$this->add_option( 'wws_product_query_support_number', $value );
			}
			if ( $name === 'support_person_name' ) {
				$this->add_option( 'wws_product_query_support_person_name', $value );
			}
			if ( $name === 'support_person_title' ) {
				$this->add_option( 'wws_product_query_support_person_title', $value );
			}
			if ( $name === 'support_person_img' ) {
				$this->add_option( 'wws_product_query_support_person_image', $value );
			}
			if ( $name === 'support_pre_message' ) {
				$this->add_option( 'wws_product_query_support_pre_message', $value );
			}
			if ( $name === 'exclude_by_products' ) {
				$this->add_option( 'wws_product_query_exclude_by_products', $value );
			}
			if ( $name === 'exclude_by_categories' ) {
				$this->add_option( 'wws_product_query_exclude_by_categories', $value );
			}

		}

		// GDPR settings migration
		$wws_gdpr_settings = get_option( 'wws_gdpr_settings', array() );
		foreach( $wws_gdpr_settings as $name => $value ) {
			if ( $name === 'gdpr_status' ) {
				$this->add_option( 'wws_gdpr_status', ( $value == '0') ? 'no' : 'yes' );
			}
			if ( $name === 'gdpr_msg' ) {
				$this->add_option( 'wws_gdpr_message', $value );
			}
			if ( $name === 'gdpr_privacy_page' ) {
				$this->add_option( 'wws_gdpr_privacy_page', (string)$this->get_id_by_slug( $value ) );
			}
		}

		// FB & GA analytics settings migration
		$wws_fb_ga_analytics_settings = get_option( 'wws_fb_ga_analytics_settings', array() );
		foreach( $wws_fb_ga_analytics_settings as $name => $value ) {
			if ( $name === 'fb_click_tracking_status' ) {
				$this->add_option( 'wws_fb_click_tracking_status', ( $value == '0') ? 'no' : 'yes' );
			}
			if ( $name === 'fb_click_tracking_event_name' ) {
				$this->add_option( 'wws_fb_click_tracking_event_name', $value );
			}
			if ( $name === 'fb_click_tracking_event_label' ) {
				$this->add_option( 'wws_fb_click_tracking_event_label', $value );
			}
			if ( $name === 'ga_click_tracking_status' ) {
				$this->add_option( 'wws_ga_click_tracking_status', ( $value == '0') ? 'no' : 'yes' );
			}
			if ( $name === 'ga_click_tracking_event_name' ) {
				$this->add_option( 'wws_ga_click_tracking_event_name', $value );
			}
			if ( $name === 'ga_click_tracking_event_category' ) {
				$this->add_option( 'wws_ga_click_tracking_event_category', $value );
			}
			if ( $name === 'ga_click_tracking_event_label' ) {
				$this->add_option( 'wws_ga_click_tracking_event_label', $value );
			}
		}

		// Developer settings migration
		$developer_settings = get_option( 'wws_developer_settings', array() );
		foreach( $developer_settings as $name => $value ) {
			if ( $name === 'is_developer' ) {
				$this->add_option( 'wws_debug_status', ( $value == '0') ? 'no' : 'yes' );
			}
			if ( $name === 'delete_setting' ) {
				$this->add_option( 'wws_delete_all', ( $value == '0') ? 'no' : 'yes' );
			}
		}

		// Multi support person migration
		$wws_multi_support_persons = get_option( 'sk_wws_multi_account' );
		$this->add_option( 'wws_multi_support_persons', $wws_multi_support_persons );

		// Delete older version settings.
		delete_option( 'sk_wws_setting' );
		delete_option( 'wws_product_query' );
		delete_option( 'wws_gdpr_settings' );
		delete_option( 'wws_fb_ga_analytics_settings' );
		delete_option( 'wws_developer_settings' );
		delete_option( 'sk_wws_multi_account' );
	}

	public function migration_options_2_4_0() {
		if ( version_compare( $this->get_db_plugin_version(), '2.4.0', '<' ) ) {
			$support_persons    = array();
			$db_support_persons = get_option( 'wws_multi_support_persons' );

			if ( ! $db_support_persons || ! is_array( $db_support_persons ) ) {
				return;
			}

			$support_persons_id = get_option( 'wws_multi_support_persons_auto_increment' );

			if ( ! $support_persons_id ) {
				$support_persons_id = 1;
			}

			foreach ( $db_support_persons as $db_support_person ) {
				if ( isset( $db_support_person['id'] ) ) {
					continue;
				}

				$support_persons[ $support_persons_id ]       = $db_support_person;
				$support_persons[ $support_persons_id ]['id'] = absint( $support_persons_id );

				foreach ( array( 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun' ) as $day ) {
					$start_time = "{$db_support_person['start_hours']}:{$db_support_person['start_minutes']}:00";
					$end_time   = "{$db_support_person['end_hours']}:{$db_support_person['end_minutes']}:59";

					if ( in_array( $day, $db_support_person['days'] ) ) {
						$support_persons[ $support_persons_id ]['schedule'][ $day ]['availability'] = 'yes';
					}

					$support_persons[ $support_persons_id ]['schedule'][ $day ]['timing'][0]['start'] = $start_time;
					$support_persons[ $support_persons_id ]['schedule'][ $day ]['timing'][0]['end']   = $end_time;
				}

				$support_persons_id++;
			}

			update_option( 'wws_multi_support_persons', $support_persons );
			update_option( 'wws_multi_support_persons_auto_increment', $support_persons_id );
		}
	}

	/**
	 * Add option only if not exists.
	 *
	 * @param string $option
	 * @param mixed $value
	 * @return boolean
	 */
	public function add_option( $option, $value ) {
		if ( get_option( $option ) === false ) {
			update_option( $option, $value );
		}
	}

	public function get_id_by_slug( $page_slug ) {
		$page = get_page_by_path( $page_slug );
		if ( $page ) {
			return $page->ID;
		} else {
			return null;
		}
	}

	/**
	 * Get previous installed plugin version.
	 *
	 * @since 2.4.0
	 *
	 * @return string Previous installed plugin version.
	 */
	protected function get_db_plugin_version() {
		return get_option( 'wws_version' );
	}

} // End of the class WWS_Migration

$wws_migration = new WWS_Migration;
