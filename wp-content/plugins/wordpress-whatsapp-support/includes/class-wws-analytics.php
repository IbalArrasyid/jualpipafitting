<?php

// Preventing to direct access
defined( 'ABSPATH' ) OR die( 'Direct access not acceptable!' );

if ( ! class_exists( 'WWS_Analytics' ) ) :

	/**
	 * Plugin public analytics report
	 * @package WeCreativez/Classes
	 * @since 1.4
	 */
	class WWS_Analytics {

		public function __construct() {

			add_action( "wp_ajax_wws_click_analytics", array( $this, 'click_analytics' ) );
			add_action( "wp_ajax_nopriv_wws_click_analytics", array( $this, 'click_analytics' ) );

			add_action( "wp_ajax_wws_analytics_deep_report", array( $this, 'analytics_deep_report' ) );
			add_action( "wp_ajax_nopriv_wws_analytics_deep_report", array( $this, 'analytics_deep_report' ) );

			add_action( 'admin_init', array( $this, 'delete_complete_analytics' ) );
			add_action( 'admin_init', array( $this, 'delete_analytics' ) );

		}


		public function delete_complete_analytics() {
			if ( ! isset( $_GET['wws_delete_complete_analytics'] ) || ! wp_verify_nonce( $_GET['_wpnonce'] ) ) {
				return;
			}

			if ( ! is_admin() ) {
				return;
			}

			$this->_delete_all_analytics();

			wp_redirect( admin_url( 'admin.php?page=wc-whatsapp-support-analytics' ) );


		}

		public function delete_analytics() {

			if ( ! isset( $_GET['wws_delete_analytics'] ) ) {
				return;
			}

			if ( ! is_admin() ) {
				return;
			}

			global $wpdb;

			$wws_analytics_table = $wpdb->prefix.'wws_analytics';

			$wpdb->delete( $wws_analytics_table, array( 'ID' => $_GET['wws_delete_analytics'] ), array( '%d' ) );

			wp_redirect( wp_get_referer() );
			exit;
		}


		public function click_analytics() {
			global $wpdb;
			check_ajax_referer( 'wws-security-token', 'security' );

			$click_analytics = apply_filters( 'wws_click_analytics', array(
				'visitor_ip'    => sanitize_text_field( $this->_get_current_ip() ),
				'number'        => ( isset( $_POST['number'] ) ? sanitize_text_field( $_POST['number'] ) : 'N/A' ),
				'message'       => ( isset( $_POST['message'] ) ? sanitize_text_field( $_POST['message'] ) : 'N/A' ),
				'through'       => ( isset( $_POST['through'] ) ? sanitize_text_field( $_POST['through'] ) : 'N/A' ),
				'referral'      => esc_url_raw( $this->_get_current_url() ),
				'device_type'   => ( wp_is_mobile() == true ? 'Mobile' : 'Desktop' ),
				'os'            => sanitize_text_field( $this->_getOS() ),
				'browser'       => sanitize_text_field( $this->getBrowser() ),
				'date'          => current_time('M d, y - H:i:s'),
				'timestamp'     => time(),
			) );

			do_action( 'wws_before_click_analytics', $click_analytics );

			$wpdb->insert(
				$wpdb->prefix.'wws_analytics',
				$click_analytics
			);

			/**
			 * Last inserted id.
			 */
			$last_id = $wpdb->insert_id;

			/**
			 * Hook: wws_after_click_analytics
			 *
			 * @since $last_id 2.0.9
			 */
			do_action( 'wws_after_click_analytics', $click_analytics, $last_id );

			wp_die();
		}

		public function analytics_deep_report() {

			if ( ! is_admin() ) {
				return;
			}

			$ip = $_GET['ip'];

			$ip_data = maybe_unserialize( file_get_contents( "http://ip-api.com/php/$ip" ) );

			$ip_city            = isset( $ip_data['city'] ) ? $ip_data['city'] : '';
			$ip_region          = isset( $ip_data['regionName'] ) ? $ip_data['regionName'] : '';
			$ip_country         = isset( $ip_data['country'] ) ? $ip_data['country'] : '';
			$ip_zip             = isset( $ip_data['zip'] ) ? $ip_data['zip'] : '';

			$ip_lon             = isset( $ip_data['lon'] ) ? $ip_data['lon'] : '';
			$ip_lat             = isset( $ip_data['lat'] ) ? $ip_data['lat'] : '';

			$ip_org             = isset( $ip_data['org'] ) ? $ip_data['org'] : '';
			$ip_as              = isset( $ip_data['as'] ) ? $ip_data['as'] : '';
			$ip_isp             = isset( $ip_data['isp'] ) ? $ip_data['isp'] : '';

			$ip_timezone    = isset( $ip_data['timezone'] ) ? $ip_data['timezone'] : '';


			require_once WWS_PLUGIN_PATH . 'includes/admin/views/analytics/admin-deep-analytics.php';

			wp_die();
		}


		protected function _get_current_url() {
			return wp_get_referer();
		}


		protected function _get_current_ip() {
			if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
				//check ip from share internet
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
				//to check ip is pass from proxy
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			return $ip;
		}


		protected function _getOS() {
				$os_platform  = "Unknown OS Platform";

				$os_array     = array(
					'/windows nt 10/i'      =>  'Windows 10',
					'/windows nt 6.3/i'     =>  'Windows 8.1',
					'/windows nt 6.2/i'     =>  'Windows 8',
					'/windows nt 6.1/i'     =>  'Windows 7',
					'/windows nt 6.0/i'     =>  'Windows Vista',
					'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
					'/windows nt 5.1/i'     =>  'Windows XP',
					'/windows xp/i'         =>  'Windows XP',
					'/windows nt 5.0/i'     =>  'Windows 2000',
					'/windows me/i'         =>  'Windows ME',
					'/win98/i'              =>  'Windows 98',
					'/win95/i'              =>  'Windows 95',
					'/win16/i'              =>  'Windows 3.11',
					'/macintosh|mac os x/i' =>  'Mac OS X',
					'/mac_powerpc/i'        =>  'Mac OS 9',
					'/linux/i'              =>  'Linux',
					'/ubuntu/i'             =>  'Ubuntu',
					'/iphone/i'             =>  'iPhone',
					'/ipod/i'               =>  'iPod',
					'/ipad/i'               =>  'iPad',
					'/android/i'            =>  'Android',
					'/blackberry/i'         =>  'BlackBerry',
					'/webos/i'              =>  'Mobile'
				);

				foreach ($os_array as $regex => $value)
					if (preg_match($regex, $_SERVER['HTTP_USER_AGENT']))
						$os_platform = $value;

				return $os_platform;
		}




		protected function getBrowser(){
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			if(strpos($user_agent, 'Maxthon') !== FALSE)
				return "Maxthon";
			elseif(strpos($user_agent, 'SeaMonkey') !== FALSE)
				return "SeaMonkey";
			elseif(strpos($user_agent, 'Vivaldi') !== FALSE)
				return "Vivaldi";
			elseif(strpos($user_agent, 'Arora') !== FALSE)
				return "Arora";
			elseif(strpos($user_agent, 'Avant Browser') !== FALSE)
				return "Avant Browser";
			elseif(strpos($user_agent, 'Beamrise') !== FALSE)
				return "Beamrise";
			elseif(strpos($user_agent, 'Epiphany') !== FALSE)
				return 'Epiphany';
			elseif(strpos($user_agent, 'Chromium') !== FALSE)
				return 'Chromium';
			elseif(strpos($user_agent, 'Iceweasel') !== FALSE)
				return 'Iceweasel';
			elseif(strpos($user_agent, 'Galeon') !== FALSE)
				return 'Galeon';
			elseif(strpos($user_agent, 'Edge') !== FALSE)
				return 'Microsoft Edge';
			elseif(strpos($user_agent, 'Trident') !== FALSE) //IE 11
				return 'Internet Explorer';
			elseif(strpos($user_agent, 'MSIE') !== FALSE)
				return 'Internet Explorer';
			elseif(strpos($user_agent, 'Opera Mini') !== FALSE)
				return "Opera Mini";
			elseif(strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR') !== FALSE)
				return "Opera";
			elseif(strpos($user_agent, 'Firefox') !== FALSE)
				return 'Mozilla Firefox';
			elseif(strpos($user_agent, 'Chrome') !== FALSE)
				return 'Google Chrome';
			elseif(strpos($user_agent, 'Safari') !== FALSE)
				return "Safari";
			elseif(strpos($user_agent, 'iTunes') !== FALSE)
				return 'iTunes';
			elseif(strpos($user_agent, 'Konqueror') !== FALSE)
				return 'Konqueror';
			elseif(strpos($user_agent, 'Dillo') !== FALSE)
				return 'Dillo';
			elseif(strpos($user_agent, 'Netscape') !== FALSE)
				return 'Netscape';
			elseif(strpos($user_agent, 'Midori') !== FALSE)
				return 'Midori';
			elseif(strpos($user_agent, 'ELinks') !== FALSE)
				return 'ELinks';
			elseif(strpos($user_agent, 'Links') !== FALSE)
				return 'Links';
			elseif(strpos($user_agent, 'Lynx') !== FALSE)
				return 'Lynx';
			elseif(strpos($user_agent, 'w3m') !== FALSE)
				return 'w3m';
			else
				return 'Unknown';
		}


		public static function get_complete_analytics( $order = 'DESC' ) {
			global $wpdb;
			$wws_analytics_table = $wpdb->prefix.'wws_analytics';
			return $wpdb->get_results( "SELECT * FROM {$wws_analytics_table} ORDER BY id $order", ARRAY_A );
		}

		public static function get_total_clicks() {
			global $wpdb;
			$wws_analytics_table = $wpdb->prefix.'wws_analytics';
			return count( $wpdb->get_results( "SELECT id FROM {$wws_analytics_table}", ARRAY_A ) );
		}

		public static function get_total_clicks_by_mobile() {
			global $wpdb;
			$wws_analytics_table = $wpdb->prefix.'wws_analytics';
			return count( $wpdb->get_results( "SELECT id FROM {$wws_analytics_table} WHERE device_type = 'Mobile'", ARRAY_A ) );
		}

		public static function get_total_clicks_by_desktop() {
			global $wpdb;
			$wws_analytics_table = $wpdb->prefix.'wws_analytics';
			return count( $wpdb->get_results( "SELECT id FROM {$wws_analytics_table} WHERE device_type = 'Desktop'", ARRAY_A ) );
		}

		/**
		 * Display top 10 IP address analytic.
		 *
		 * @global $wpdb    WordPress database connection.
		 *
		 * @since 2.0.0
		 * @return string
		 */
		public static function the_top_ten_ip_address() {
			global $wpdb;

			$html = '';

			$query = $wpdb->get_results(
				"SELECT COUNT( visitor_ip ) as count, visitor_ip
				FROM {$wpdb->prefix}wws_analytics
				GROUP BY visitor_ip
				ORDER BY count DESC
				LIMIT 10",
				ARRAY_A
			);

			if ( ! $query ) {
				return false;
			}

			$html .= '<table>';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th>' . esc_html__( 'IP Address', 'wc-wws' ) . '</th>';
			$html .= '<th>' . esc_html__( 'Clicks', 'wc-wws' ) . '</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';

			foreach ( $query as $q ) {
				$html .= '<tr>';
				$html .= '<td>' . esc_html( $q['visitor_ip'] ) . '</td>';
				$html .= '<td>' . esc_html( $q['count'] ) . '</td>';
				$html .= '</tr>';
			}

			$html .= '</tbody>';
			$html .= '</table>';

			echo $html;
		}
		/**
		 * Display top 10 referral links analytic.
		 *
		 * @global $wpdb    WordPress database connection.
		 *
		 * @since 2.0.0
		 * @return string
		 */
		public static function the_top_ten_referral_links() {
			global $wpdb;

			$html = '';

			$query = $wpdb->get_results(
				"SELECT COUNT( referral ) as count, referral
				FROM {$wpdb->prefix}wws_analytics
				WHERE
					referral != ''
				GROUP BY referral
				ORDER BY count DESC
				LIMIT 10",
				ARRAY_A
			);

			if ( ! $query ) {
				return false;
			}

			$html .= '<table>';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th>' . esc_html__( 'Referral Links', 'wc-wws' ) . '</th>';
			$html .= '<th>' . esc_html__( 'Clicks', 'wc-wws' ) . '</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';

			foreach ( $query as $q ) {
				$html .= '<tr>';
				$html .= '<td><a href="' . esc_url( $q['referral'] ) . '" target="_blank">' . esc_url( $q['referral'] ) . '</a></td>';
				$html .= '<td>' . esc_html( $q['count'] ) . '</td>';
				$html .= '</tr>';
			}

			$html .= '</tbody>';
			$html .= '</table>';

			echo $html;
		}

		/**
		 * Display top 10 operating systems analytic.
		 *
		 * @global $wpdb    WordPress database connection.
		 *
		 * @since 2.0.0
		 * @return string
		 */
		public static function the_top_ten_operating_systems() {
			global $wpdb;

			$html = '';

			$query = $wpdb->get_results(
				"SELECT COUNT( os ) as count, os
				FROM {$wpdb->prefix}wws_analytics
				GROUP BY os
				ORDER BY count DESC
				LIMIT 10",
				ARRAY_A
			);

			if ( ! $query ) {
				return false;
			}

			$html .= '<table>';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th>' . esc_html__( 'Operating Systems', 'wc-wws' ) . '</th>';
			$html .= '<th>' . esc_html__( 'Clicks', 'wc-wws' ) . '</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';

			foreach ( $query as $q ) {
				$html .= '<tr>';
				$html .= '<td>' . esc_html( $q['os'] ) . '</td>';
				$html .= '<td>' . esc_html( $q['count'] ) . '</td>';
				$html .= '</tr>';
			}

			$html .= '</tbody>';
			$html .= '</table>';

			echo $html;
		}

		/**
		 * Display top 10 browsers analytic.
		 *
		 * @global $wpdb    WordPress database connection.
		 *
		 * @since 2.0.0
		 * @return string
		 */
		public static function the_top_ten_browsers() {
			global $wpdb;

			$html = '';

			$query = $wpdb->get_results(
				"SELECT COUNT( browser ) as count, browser
				FROM {$wpdb->prefix}wws_analytics
				GROUP BY browser
				ORDER BY count DESC
				LIMIT 10",
				ARRAY_A
			);

			if ( ! $query ) {
				return false;
			}

			$html .= '<table>';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th>' . esc_html__( 'Browsers', 'wc-wws' ) . '</th>';
			$html .= '<th>' . esc_html__( 'Clicks', 'wc-wws' ) . '</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';

			foreach ( $query as $q ) {
				$html .= '<tr>';
				$html .= '<td>' . esc_html( $q['browser'] ) . '</td>';
				$html .= '<td>' . esc_html( $q['count'] ) . '</td>';
				$html .= '</tr>';
			}

			$html .= '</tbody>';
			$html .= '</table>';

			echo $html;
		}

		/**
		 * Get current month analytics in chart format.
		 *
		 * @global $wpdb    WordPress database connection.
		 *
		 * @since 2.0.0
		 * @return void
		 */
		public static function get_current_month_chart_data() {
			global $wpdb;

			// Numeric representation of a month, without leading zeros
			$current_month = current_time( 'n' );
			// A two digit representation of a year
			$current_year  = current_time( 'y' );
			// Calculate days in a month
			$days_in_month = wp_date( 't', mktime( 0, 0, 0, $current_month, 1, $current_year ) );

			$db_days      = array();
			$days         = array();
			$default_days = array(
				1  => 0, 2  => 0, 3  => 0, 4  => 0, 5  => 0,
				6  => 0, 7  => 0, 8  => 0, 9  => 0, 10 => 0,
				11 => 0, 12 => 0, 13 => 0, 14 => 0, 15 => 0,
				16 => 0, 17 => 0, 18 => 0, 19 => 0, 20 => 0,
				21 => 0, 22 => 0, 23 => 0, 24 => 0, 25 => 0,
				26 => 0, 27 => 0, 28 => 0,
			);

			if ( $days_in_month > 28 ) {
				$default_days[29] = 0;
			}
			if ( $days_in_month > 29 ) {
				$default_days[30] = 0;
			}
			if ( $days_in_month > 30 ) {
				$default_days[31] = 0;
			}

			$query = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT *
					FROM {$wpdb->prefix}wws_analytics
					WHERE
						timestamp != '' &&
						timestamp >= %d
					ORDER BY id ASC",
					strtotime( 'first day of ' . date( 'F Y') )
				),
				ARRAY_A
			);

			foreach ( $query as $q ) {
				$db_days[ date( 'j', $q['timestamp'] ) ][] = $q['timestamp'];
			}

			foreach( $db_days as $db_days_key => $db_days_value ) {
				$db_days[ $db_days_key ] = count( $db_days_value );
			}

			foreach ( $default_days as $dd_key => $dd_val ) {
				if ( isset( $db_days[ $dd_key ] ) ) {
					$days['days'][]   = $db_days[ $dd_key ];
				} else {
					$days['days'][] = $dd_val;
				}

				$days['labels'][] = sprintf( '%s %s, %s', current_time( 'M' ), $dd_key, $current_year );
			}

			return $days;
		}

		/**
		 * Get today click counts.
		 *
		 * @global $wpdb    WordPress database connection.
		 *
		 * @since 2.0.0
		 * @return void
		 */
		public static function get_today_click_count() {
			global $wpdb;

			$current_day = strtotime( 'today midnight' );

			$query = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT( id )
					FROM {$wpdb->prefix}wws_analytics
					WHERE timestamp >= %d",
					$current_day
				)
			);

			return $query ? $query : 0;
		}

		/**
		 * Get week ( Last seven days ) click count.
		 *
		 * @global $wpdb    WordPress database connection.
		 *
		 * @since 2.0.0
		 * @return void
		 */
		public static function get_last_week_click_count() {
			global $wpdb;

			$current_time    = current_time( 'U' );
			$last_week_time  = $current_time - ( 7 * 24 * 60 * 60 );

			$query = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT( id ) as count
					FROM {$wpdb->prefix}wws_analytics
					WHERE timestamp BETWEEN '%s' AND '%s'
					AND timestamp != ''",
					$last_week_time,
					$current_time
				)
			);

			return $query ? $query : 0;
		}

		/**
		 * Get month ( Last 31 days ) click count.
		 *
		 * @global $wpdb    WordPress database connection.
		 *
		 * @since 2.0.0
		 * @return void
		 */
		public static function get_last_month_click_count() {
			global $wpdb;

			$current_time    = current_time( 'U' );
			$last_month_time = $current_time - ( 31 * 24 * 60 * 60 );

			$query = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT( id ) as count
					FROM {$wpdb->prefix}wws_analytics
					WHERE timestamp BETWEEN '%s' AND '%s'
					AND timestamp != ''",
					$last_month_time,
					$current_time
				)
			);

			return $query ? $query : 0;
		}

		/**
		 * Get recent contact number details.
		 *
		 * @global $wpdb    WordPress database connection.
		 *
		 * @param int $limit
		 *
		 * @since 2.0.0
		 * @return void
		 */
		public static function get_recent_contact_numbers( $limit = 10 ) {
			global $wpdb;

			$query = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT visitor_ip, number, date
					FROM {$wpdb->prefix}wws_analytics
					WHERE number != 'N/A'
					ORDER BY id DESC
					LIMIT %d",
					$limit
				),
				ARRAY_A
			);

			return $query;
		}

		private function _delete_all_analytics() {
			global $wpdb;

			$wws_analytics_table = $wpdb->prefix.'wws_analytics';

			$wpdb->query( "TRUNCATE {$wws_analytics_table}" );

		}


	} // .WWS_Analytics

	$wws_analytics = new WWS_Analytics;

endif;

