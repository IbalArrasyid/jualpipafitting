<?php
defined( 'ABSPATH' ) || exit;

/**
 * WeCreativez plugins updater.
 *
 * @var 1.0.5
 * @author WeCreativez
 *
 */
class WWS_Plugin_Updater {

	/**
	 * The plugin remote update path
	 *
	 * @var string
	 */
	public $api_url;

	/**
	 * Plugin Slug (plugin_directory/plugin_file.php)
	 *
	 * @var string
	 */
	public $plugin_slug;

	/**
	 * Plugin name (plugin_file)
	 *
	 * @var string
	 */
	public $slug;

	/**
	 * Envato purchase code.
	 *
	 * @see https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-
	 * @var string
	 */
	public $license;

	/**
	 * Envato item ID.
	 *
	 * @var string
	 */
	public $item_id;

	/**
	 * Contain plugin activation or deactivation error messages.
	 *
	 * @var string
	 */
	public $error_message;

	/**
	 * Contain plugin activation or deactivation success messages.
	 *
	 * @var string
	 */
	public $success_message;

	/**
	 * Class constructor.
	 *
	 * @uses plugin_basename()
	 * @uses hook()
	 *
	 * @param string  $_api_url     The URL pointing to the custom API endpoint.
	 * @param string  $_plugin_file Path to the plugin file.
	 * @param array   $_api_data    Optional data to send with API calls.
	 */
	 
	 public $plugin_file;
	 public $api_data;
	 
	public function __construct( $_api_url, $_plugin_file, $_api_data = array() ) {
		$this->api_url     = trailingslashit( $_api_url );
		$this->plugin_slug = plugin_basename( $_plugin_file );
		$this->plugin_file = $_plugin_file;
		$this->slug        = dirname( plugin_basename( $_plugin_file ) );
		$this->api_data    = $_api_data;
		$this->license     = isset( $_api_data['license'] ) ? trim( $_api_data['license'] ) : '';
		$this->item_id     = isset( $_api_data['item_id'] ) ? trim( $_api_data['item_id'] ) : '';

		add_shortcode( "wcz_{$this->item_id}_activation_form", array( $this, 'form_html' ) );

		add_action( 'admin_init', array( $this, 'plugin_activate' ) );
		add_action( 'admin_init', array( $this, 'plugin_deactivate' ) );

		add_action( 'admin_notices', array( $this, 'admin_notices' ), 10 );
		add_action( 'admin_notices', array( $this, 'admin_activation_success' ), 20 );
		add_action( 'admin_notices', array( $this, 'admin_activation_error' ), 26 );

		if ( get_option( $this->license ) ) {

			// Make sure the code is valid before sending it to server
			if ( preg_match( "/^(\w{8})-((\w{4})-){3}(\w{12})$/", get_option( $this->license ) ) ) {
				// Define the alternative API for updating checking.
				add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_for_update' ), 10, 1 );

				// Define the alternative response for information checking.
				add_filter( 'plugins_api', array( $this, 'check_for_info' ), 10, 3 );

				// Run with plugin update process complete.
				add_action( 'upgrader_process_complete', array( $this, 'after_update' ), 10, 2 );

				// Add custom links with the plugin row meta.
				add_filter( 'plugin_row_meta', array( $this, 'add_plugin_update_notice' ), 10, 2 );

				// Add activation information in plugin update message.
				add_action( 'in_plugin_update_message-' . $this->plugin_slug, array( $this, 'update_message' ), 10, 2 );

				// Clear cached plugin information.
				add_action( 'admin_init', array( $this, 'delete_cached_plugin_information' ), 99 );
			} else {
				delete_option( $this->license );
			}
		}

	}

	/**
	 * Add our self-hosted autoupdate plugin to the filter transient
	 *
	 * @param object $transient
	 *
	 * @return object
	 */
	public function check_for_update( $transient ) {
		// pre_set_site_transient_update_plugins is called twice
		// we only want to act on the second run
		// Check if the transient contains the 'checked' information
		// If not, just return its value without hacking it
		if ( empty( $transient->checked ) ) {
			return $transient;
		}
		if ( ! isset( $transient->checked[ $this->plugin_slug ] ) ) {
			return $transient;
		}

		// Get current plugin version.
		$current_version = $transient->checked[ $this->plugin_slug ];

		// Get remote data.
		if ( ! $remote = $this->get_remote_plugin_information() ) {
			return $transient;
		}

		// Return if remote have no version data.
		if ( ! isset( $remote['version'] ) ) {
			return $transient;
		}

		// If a newer version is available, add the update
		if ( version_compare( $current_version, $remote['version'], '<' ) ) {
			// Create a standart object.
			$obj              = new stdClass();
			$obj->slug        = $this->slug;
			$obj->plugin      = $this->plugin_slug;
			$obj->new_version = isset( $remote['version'] ) ? $remote['version'] : false;
			$obj->url         = isset( $remote['homepage'] ) ? $remote['homepage'] : false;
			$obj->package     = isset( $remote['download_url'] ) ? $remote['download_url'] : false;
			$obj->icons       = array(
				'1x' => isset( $remote['icons']['1x'] ) ? $remote['icons']['1x'] : false,
				'2x' => isset( $remote['icons']['2x'] ) ? $remote['icons']['2x'] : false,
			);
			$obj->banners = array(
				'1x' => isset( $remote['banners']['low'] ) ? $remote['banners']['low'] : false,
				'2x' => isset( $remote['banners']['high'] ) ? $remote['banners']['high'] : false,
			);
			$obj->banners_rtl  = array();
			$obj->tested       = isset( $remote['tested'] ) ? $remote['tested'] : false;
			$obj->requires_php = isset( $remote['requires_php'] ) ? $remote['requires_php'] : false;

			$transient->response[ $this->plugin_slug ] = $obj;
			// $transient->checked[ $this->plugin_slug ]  = $remote['version'];
		}

		return $transient;
	}

	/**
	 * Add our self-hosted description to the filter
	 *
	 * @param boolean $false
	 * @param array   $action
	 * @param object  $arg
	 *
	 * @return bool|object
	 */
	public function check_for_info( $false, $action, $arg ) {
		// do nothing if this is not about getting plugin information
		if ( 'plugin_information' !== $action ) {
			return false;
		}

		// do nothing if it is not our plugin
		if ( $arg->slug !== $this->slug ) {
			return false;
		}

		// Get remote data.
		if ( ! $remote = $this->get_remote_plugin_information() ) {
			return false;
		}

		// Return if remote have no version data.
		if ( ! isset( $remote['version'] ) ) {
			return false;
		}

		// Create a standart object.
		$result                           = new stdClass();
		$result->name                     = isset( $remote['name'] ) ? $remote['name'] : false;
		$result->slug                     = $this->slug;
		$result->version                  = isset( $remote['version'] ) ? $remote['version'] : false;
		$result->tested                   = isset( $remote['tested'] ) ? $remote['tested'] : false;
		$result->requires                 = isset( $remote['requires'] ) ? $remote['requires'] : false;
		$result->author                   = isset( $remote['author'] ) ? $remote['author'] : false;
		$result->author_profile           = isset( $remote['author_profile'] ) ? $remote['author_profile'] : false;
		$result->download_link            = isset( $remote['download_url'] ) ? $remote['download_url'] : false;
		$result->trunk                    = isset( $remote['download_url'] ) ? $remote['download_url'] : false;
		$result->requires_php             = isset( $remote['requires_php'] ) ? $remote['requires_php'] : false;
		$result->last_updated             = isset( $remote['last_updated'] ) ? $remote['last_updated'] : false;
		$result->sections                 = isset( $remote['sections'] ) ? $remote['sections'] : false;
		$result->banners                  = isset( $remote['banners'] ) ? $remote['banners'] : false;
		$result->icons                    = isset( $remote['icons'] ) ? $remote['icons'] : false;
		$result->contributors             = isset( $remote['contributors'] ) ? $remote['contributors'] : false;
		$result->rating                   = isset( $remote['rating'] ) ? $remote['rating'] : false;
		$result->ratings                  = isset( $remote['ratings'] ) ? $remote['ratings'] : false;
		$result->num_ratings              = isset( $remote['num_ratings'] ) ? $remote['num_ratings'] : false;
		$result->support_threads          = isset( $remote['support_threads'] ) ? $remote['support_threads'] : false;
		$result->support_threads_resolved = isset( $remote['support_threads_resolved'] ) ? $remote['support_threads_resolved'] : false;
		$result->active_installs          = isset( $remote['active_installs'] ) ? $remote['active_installs'] : null;
		$result->added                    = isset( $remote['added'] ) ? $remote['added'] : false;
		$result->homepage                 = isset( $remote['homepage'] ) ? $remote['homepage'] : false;
		$result->reviews                  = isset( $remote['reviews'] ) ? $remote['reviews'] : false;
		$result->versions                 = isset( $remote['versions'] ) ? $remote['versions'] : false;
		$result->donate_link              = isset( $remote['donate_link'] ) ? $remote['donate_link'] : false;
		$result->translations             = isset( $remote['translations'] ) ? $remote['translations'] : false;

		return $result;
	}

	/**
	 * Delete plugin cached information after update.
	 *
	 * @param WP_Upgrader $upgrader_object
	 * @param array       $options
	 *
	 * @return void
	 */
	public function after_update( $upgrader_object, $options ) {
		if ( 'update' == $options['action'] && 'plugin' === $options['type'] ) {
			if ( is_array( $options['plugins'] ) && in_array( $this->plugin_slug, $options['plugins'] ) ) {
				// Just clean the cache when new plugin version is installed
				delete_transient( "wcz_{$this->item_id}_plugin_info" );
			}
		}
	}

	/**
	 * Add plugin update message.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $plugin_meta
	 * @param string   $plugin_file
	 *
	 * @return void
	 */
	public function add_plugin_update_notice( $plugin_meta, $plugin_file ) {
		if ( $this->plugin_slug === $plugin_file ) {
			$plugin_meta['view_details'] = sprintf( wp_kses_post(
				__( '<a href="%s" class="thickbox open-plugin-details-modal">View details</a>', 'wc-wws' ) ),
				admin_url( "plugin-install.php?tab=plugin-information&plugin={$this->slug}&TB_iframe=true&width=600&height=550" )
			);
			$plugin_meta['check_for_update'] = sprintf( wp_kses_post(
				__( '<a href="%s">Check for updates</a>', 'wc-wws' ) ),
				wp_nonce_url( '?wcz_check_update=plugin&item_id=' . $this->item_id )
			);
		}

		return $plugin_meta;
	}

	/**
	 * Display update message under the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param array $plugin_info_array
	 * @param object $plugin_info_object
	 *
	 * @return void
	 */
	public function update_message( $plugin_info_array, $plugin_info_object ) {
		$update_message = '';

		if ( empty( $plugin_info_array['package'] ) ) {
			$update_message .= esc_html__( ' To receive automatic updates license activation is required.', 'wc-wws' );
			$update_message .= sprintf( esc_html__( ' Please visit plugin settings to activate your %s.', 'wc-wws' ), $this->get_plugin_data( 'Name' ) );
			$update_message .= sprintf( wp_kses_post( __( ' <a href="%s" target="_blank">Click here</a> to manage site.', 'wc-wws' ) ), 'https://wecreativez.com/envato/manage-sites/' );

			echo $update_message; // WPCS: XSS ok.
		}
	}

	/**
	 * Plugin activation and deactivation form.
	 *
	 * @return void
	 */
	public function form_html() {
		ob_start();

		if ( ! get_option( $this->license ) ) : ?>
			<form action="#" method="post">
				<?php wp_nonce_field( "wcz_{$this->item_id}_plugin_activation", "wcz_{$this->item_id}_plugin_activation" ); ?>
				<h3><?php printf( esc_html__( 'Activate %s', 'wc-wws' ), $this->get_plugin_data( 'Name' ) ); ?></h3>
				<p><?php esc_html_e( 'Please enter your purchase code. Purchasing plugin license also grants access to premium support and auto-updates.', 'wc-wws' ); ?></p>
				<div>
					<input type="text" class="regular-text" name="license_key" placeholder="<?php esc_html_e( 'Envato Purchase Code', 'wc-wws' ); ?>">
					<input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Activate Plugin', 'wc-wws' ); ?>">
				</div>
				<p>
					<strong><?php esc_html_e( 'Important! One standard license is valid for only 1 website. Running multiple websites on a single license is a copyright violation.', 'wc-wws' ); ?></strong>
				</p>
				<p><?php printf( wp_kses_post( __( 'If you plan to use this plugin for several different domains, please <a href="%s">purchase additional licenses</a> for each of them.', 'wc-wws' ) ), 'https://codecanyon.net/item/x/' . $this->item_id ); ?></p>
				<div>
					<a href="https://wecreativez.com/envato/manage-sites/" class="button button-primary" target="_blank"><?php esc_html_e( 'Manage site', 'wc-wws' ); ?></a>
					<a href="https://wecreativez.com/article/where-is-my-envato-purchase-code/" class="button button-secondary" target="_blank"><?php esc_html_e( 'What is my purchase code?', 'wc-wws' ); ?></a>
					<a href="https://wecreativez.com/envato/support/" class="button button-secondary" target="_blank"><?php esc_html_e( 'Need our help?', 'wc-wws' ); ?></a>
					<a href="https://codecanyon.net/licenses/standard" class="button button-secondary" target="_blank"><?php esc_html_e( 'License agreement', 'wc-wws' ); ?></a>
				</div>
			</form>
			<?php else : ?>
			<form action="#" method="post">
				<?php wp_nonce_field( "wcz_{$this->item_id}_plugin_deactivation", "wcz_{$this->item_id}_plugin_deactivation" ); ?>
				<h3><?php printf( esc_html__( 'Deactivate %s', 'wc-wws' ), $this->get_plugin_data( 'Name' ) ); ?></h3>
				<div>
					<input type="text" class="regular-text" name="license_key" value="<?php echo esc_attr( get_option( $this->license ) ); ?>">
					<input type="submit" class="button button-secondary" value="<?php esc_attr_e( 'Deactivate Plugin', 'wc-wws' ); ?>">
				</div>
				<p>
					<a href="https://wecreativez.com/envato/manage-sites/" class="button button-primary" target="_blank"><?php esc_html_e( 'Manage site', 'wc-wws' ); ?></a>
					<a href="https://wecreativez.com/article/where-is-my-envato-purchase-code/" class="button button-secondary" target="_blank"><?php esc_html_e( 'What is my purchase code?', 'wc-wws' ); ?></a>
					<a href="https://wecreativez.com/envato/support/" class="button button-secondary" target="_blank"><?php esc_html_e( 'Need our help?', 'wc-wws' ); ?></a>
					<a href="https://codecanyon.net/licenses/standard" class="button button-secondary" target="_blank"><?php esc_html_e( 'License agreement', 'wc-wws' ); ?></a>
				</p>
			</form>
		<?php endif;
		return ob_get_clean();
	}

	/**
	 * Plugin activation process.
	 *
	 * @return void
	 */
	public function plugin_activate() {
		if ( ! isset( $_POST["wcz_{$this->item_id}_plugin_activation"] )
		  || ! wp_verify_nonce( $_POST["wcz_{$this->item_id}_plugin_activation"], "wcz_{$this->item_id}_plugin_activation" ) ) {
			return;
		}

		// Get response.
		$remote = wp_remote_post( $this->api_url, array(
			'body'    => array(
				'wcz_elm_action' => 'activate_license',
				'item_id'        => $this->item_id,
				'license'        => trim( $_POST['license_key'] ),
				'url'            => home_url(),
			),
			'timeout' => 20,
		) );

		if ( ! is_wp_error( $remote ) && 200 == wp_remote_retrieve_response_code( $remote ) ) {
			$remote = json_decode( wp_remote_retrieve_body( $remote ), true ); // array return.

			if ( 'success' !== $remote['status'] ) {
				switch ( $remote['status'] ) {
					case 'missing_license':
						$this->error_message = esc_html__( 'Missing purchase code.', 'wc-wws' );
						break;

					case 'invalid_license':
						$this->error_message = esc_html__( 'Invalid purchase code.', 'wc-wws' );
						break;

					case 'license_blocked':
						$this->error_message = sprintf( wp_kses_post( __( 'Purchase code is blocked, please <a href="%s">contact us</a>.', 'wc-wws' ) ), esc_url( $this->item_support_url ) );
						break;

					case 'missing_item_id':
						$this->error_message = esc_html__( 'Missing item ID.', 'wc-wws' );
						break;

					case 'invalid_item':
						$this->error_message = esc_html__( 'Invalid item.', 'wc-wws' );
						break;

					case 'missing_url':
						$this->error_message = esc_html__( 'Missing website URL.', 'wc-wws' );
						break;

					case 'already_activate':
						$this->error_message = sprintf( wp_kses_post( __( 'Your license key has reached its activation limit. <a href="%s" target="_blank">Click here</a> to manage site.', 'wc-wws' ) ), 'https://wecreativez.com/envato/manage-sites/' );
						break;

					case 'custom_message':
						$this->error_message = ( isset( $remote['message'] ) ) ? wp_kses_post( $remote['message'] ) : '';
						break;

					default:
						$this->error_message = esc_html__( 'Unknown error. Please contact us.', 'wc-wws' );
						break;
				}
			} else {
				update_option( $this->license, sanitize_text_field( $_POST['license_key'] ) );
				delete_transient( "wcz_{$this->item_id}_plugin_info" );
				wp_clean_plugins_cache();

				$this->success_message = sprintf( wp_kses_post( __( '<strong>%s</strong> activated successfully.', 'wc-wws' ) ), $this->get_plugin_data( 'Name' ) );
			}
		}

	}

	/**
	 * Plugin deactivation process.
	 *
	 * @return void
	 */
	public function plugin_deactivate() {
		if ( ! isset( $_POST["wcz_{$this->item_id}_plugin_deactivation"] )
		  || ! wp_verify_nonce( $_POST["wcz_{$this->item_id}_plugin_deactivation"], "wcz_{$this->item_id}_plugin_deactivation" ) ) {
			return;
		}

		// Get response.
		$remote = wp_remote_post( $this->api_url, array(
			'body'    => array(
				'wcz_elm_action' => 'deactivate_license',
				'item_id'        => $this->item_id,
				'license'        => trim( $_POST['license_key'] ),
			),
			'timeout' => 20,
		) );

		if ( ! is_wp_error( $remote ) && 200 == wp_remote_retrieve_response_code( $remote ) ) {
			$remote = json_decode( wp_remote_retrieve_body( $remote ), true ); // array return.

			if ( 'success' !== $remote['status'] ) {
				switch ( $remote['status'] ) {
					case 'missing_license':
						$this->error_message = esc_html__( 'Missing purchase code.', 'wc-wws' );
						break;

					case 'invalid_license':
						$this->error_message = esc_html__( 'Invalid purchase code.', 'wc-wws' );
						break;

					case 'missing_item_id':
						$this->error_message = esc_html__( 'Missing item ID.', 'wc-wws' );
						break;

					case 'invalid_item':
						$this->error_message = esc_html__( 'Invalid item', 'wc-wws' );
						break;

					case 'custom_message':
						$this->error_message = isset( $remote['message'] ) ? wp_kses_post( $remote['message'] ) : '';
						break;

					default:
						$this->error_message = esc_html__( 'Unknown error. Please contact us.', 'wc-wws' );
						# code...
						break;
				}
			} else {
				delete_option( $this->license );
				delete_transient( "wcz_{$this->item_id}_plugin_info" );
				wp_clean_plugins_cache();

				$this->success_message = sprintf( wp_kses_post( __( '<strong>%s</strong> deactivated successfully.', 'wc-wws' ) ), $this->get_plugin_data( 'Name' ) );
			}
		}
	}

	/**
	 * Admin not licensed notice.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function admin_notices() {
		if ( get_option( $this->license ) ) {
			return;
		}

		?>
		<div class="notice notice-warning">
			<p>
			<?php
				printf( wp_kses_post(
					__( 'Hola! Would you like to receive automatic updates and unlock premium support? Please <a href="%2$s">activate</a> your copy of <strong>%1$s</strong>, or <a href="%3$s">purchase a new license</a>', 'wc-wws' ) ),
					$this->get_plugin_data( 'Name' ),
					$this->get_activation_page_url(),
					'https://codecanyon.net/item/x/' . $this->item_id
				);
			?>
			</p>
		</div>
		<?php
	}

	/**
	 * Show activation and deactivation error messages.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function admin_activation_error() {
		if ( $this->error_message ) {
			printf( '<div class="notice notice-error is-dismissible"><p>%s</p></div>', wp_kses_post( $this->error_message ) );
		}
	}

	/**
	 * Show activation and deactivation success messages.
	 *
	 * @since 1.0.3
	 *
	 * @return void
	 */
	public function admin_activation_success() {
		if ( $this->success_message ) {
			printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', wp_kses_post( $this->success_message ) );
		}
	}

	/**
	 * Get information about the remote version
	 *
	 * @return bool|array
	 */
	private function get_remote_plugin_information() {
		if ( ! $cached_data = get_transient( "wcz_{$this->item_id}_plugin_info" ) ) {
			// Get response.
			$request = wp_remote_post( $this->api_url, array(
				'body'    => array(
					'wcz_elm_action' => 'check_update',
					'item_id'        => $this->item_id,
					'license'        => get_option( $this->license ),
					'url'            => home_url(),
				),
				'timeout' => 20,
			) );

			if ( ! is_wp_error( $request ) || 200 === wp_remote_retrieve_response_code( $request ) ) {
				set_transient( "wcz_{$this->item_id}_plugin_info", json_decode( wp_remote_retrieve_body( $request ), true ), HOUR_IN_SECONDS * 120 );
			} else {
				set_transient( "wcz_{$this->item_id}_plugin_info", false, HOUR_IN_SECONDS * 120 );
			}
		}

		return get_transient( "wcz_{$this->item_id}_plugin_info" );
	}

	/**
	 * Delete cached information.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function delete_cached_plugin_information() {
		if ( ! isset( $_GET['wcz_check_update'] )
		  || ! isset( $_GET['item_id'] ) || ! wp_verify_nonce( $_GET['_wpnonce'] ) ) {
			return;
		}
		if ( 'plugin' !== $_GET['wcz_check_update'] ) {
			return;
		}

		$item_id = trim( $_GET['item_id'] );

		delete_transient( "wcz_{$item_id}_plugin_info" );
		wp_clean_plugins_cache();
		wp_safe_redirect( wp_get_referer() );
	}

	/**
	 * Get plugin activation page URL.
	 *
	 * @since 1.0.5
	 *
	 */
	private function get_activation_page_url() {
		if ( ! isset( $this->api_data['activation_page'] ) ) {
			return;
		}

		return admin_url( "admin.php?page=" . $this->api_data['activation_page'] );
	}

	/**
	 * Get plugin data.
	 *
	 * @since 1.0.0
	 *
	 * @link https://developer.wordpress.org/reference/functions/get_plugin_data/
	 *
	 * @param string $data
	 *
	 * @return void
	 */
	private function get_plugin_data( $data ) {
		if ( ! isset( $data ) ) {
			return;
		}
		if ( ! is_admin() ) {
			return;
		}
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_data = get_plugin_data( $this->plugin_file );

		if ( ! isset( $plugin_data[$data] ) ) {
			return false;
		}

		return $plugin_data[$data];
	}
}


$updater = new WWS_Plugin_Updater(
	'https://updatechecker.in/codecanyon/', // Plugin update API URL.
	WWS_PLUGIN_FILE, // Plugin file.
	array(
		'license'         => 'sk_wws_license_key', // Plugin license key.
		'item_id'         => '20963962', // Envato item id.
		'activation_page' => 'wc-whatsapp-support_plugin-support'
	)
);
