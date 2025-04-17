<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * WWS_Enqueue_Scripts class responsable to load all the scripts and styles.
 */
class WWS_Enqueue_Scripts {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'public_enqueue_scripts' ), 200 );
		add_action( 'wp_enqueue_scripts', array( $this, 'public_dynamic_resources'), 200 );
	}

	/**
	 * Load all the required frontend scripts
	 * @return void
	 * @since 1.3
	 */
	public function public_enqueue_scripts() {
		// Get the template
		$template = apply_filters( 'wws_enqueue_css_layout', get_option( 'wws_layout' ) );

		/**
		 * Inputmask jQuery plugin by RobinHerbots.
		 *
		 * @version 5.0.6
		 * @link    https://github.com/RobinHerbots/Inputmask/
		*/
		wp_enqueue_script( 'wws-input-mask', WWS_PLUGIN_URL . 'assets/libraries/inputmask/jquery.inputmask.min.js', array( 'jquery' ), '5.0.6', true );

		// Load public scripts
		wp_enqueue_style( 'wws-public-style', WWS_PLUGIN_URL . 'assets/css/wws-public-style.css', array(), WWS_PLUGIN_VER );
		wp_enqueue_script( 'wws-public-script', WWS_PLUGIN_URL . 'assets/js/wws-public-script.js', array( 'jquery' ), WWS_PLUGIN_VER, true );
		wp_localize_script( 'wws-public-script', 'wwsObj', apply_filters( 'wws_public_script_object', array(
			'support_number'        => esc_html( apply_filters( 'wws_support_contact_number', get_option( 'wws_contact_number' ) ) ),
			'auto_popup'            => get_option('wws_auto_popup'),
			'auto_popup_time'       => intval( get_option( 'wws_auto_popup_time' ) ),
			'plugin_url'            => esc_url( WWS_PLUGIN_URL ),
			'is_mobile'             => ( wp_is_mobile() ) ? '1' : '0',
			'current_page_id'       => get_the_ID(),
			'current_page_url'      => get_permalink(),
			'popup_layout'          => apply_filters( 'wws_current_layout', intval( get_option( 'wws_layout' ) ) ),
			'group_invitation_id'   => esc_html( get_option('wws_group_id') ),
			'admin_url'             => admin_url( 'admin-ajax.php?ver=' . uniqid() ),
			'scroll_lenght'         => esc_html( get_option('wws_scroll_length') ),
			'pre_defined_text'      => str_replace(
				array( '{title}', '{url}', '{br}' ),
				array( get_the_title(), get_permalink(), '%0A' ),
				esc_html( apply_filters( 'wws_predefined_text', get_option( 'wws_predefined_text' ) ) )
			),
			'is_debug'              => get_option( 'wws_debug_status' ),
			'fb_ga_click_tracking'  => json_encode( array(
				'fb_click_tracking_status'          => get_option( 'wws_fb_click_tracking_status' ),
				'fb_click_tracking_event_name'      => get_option( 'wws_fb_click_tracking_event_name' ),
				'fb_click_tracking_event_label'     => get_option( 'wws_fb_click_tracking_event_label' ),
				'ga_click_tracking_status'          => get_option( 'wws_ga_click_tracking_status' ),
				'ga_click_tracking_event_name'      => get_option( 'wws_ga_click_tracking_event_name' ),
				'ga_click_tracking_event_category'  => get_option( 'wws_ga_click_tracking_event_category' ),
				'ga_click_tracking_event_label'     => get_option( 'wws_ga_click_tracking_event_label' ),
			) ),
			'is_gdpr'               => get_option( 'wws_gdpr_status' ),
			'numberMasking'         => get_option( 'wws_number_masking' ),
			'whatsapp_mobile_api'   => apply_filters( 'wws_whatsapp_mobile_api_url', 'https://api.whatsapp.com' ),
			'whatsapp_desktop_api'  => apply_filters( 'wws_whatsapp_desktop_api_url', 'https://api.whatsapp.com' ),
			'whatsapp_web_api'      => '',
			'version'               => WWS_PLUGIN_VER,
			'security_token'        => wp_create_nonce( "wws-security-token" ),
		) ) );

		// Load CSS for template
		wp_enqueue_style( 'wws-public-template', WWS_PLUGIN_URL . 'assets/css/wws-public-template-'. intval( $template ) .'.css', array(), WWS_PLUGIN_VER );
	}


	/**
	 * public dynamic js,css in wp_head
	 * @since 1.2
	 */
	public function public_dynamic_resources() {

		$x_axis_offset = get_option( 'wws_x_axis_offset' );
		$y_axis_offset = get_option( 'wws_y_axis_offset' );

		$dynamic_css = '';

		// Dynamic bg color
		$dynamic_css .= '.wws--bg-color {
			background-color: ' . esc_html( get_option( 'wws_layout_background_color' ) ) . ';
		}';

		// Dynamic text color
		$dynamic_css .= '.wws--text-color {
				color: ' . esc_html( get_option( 'wws_layout_text_color' ) ) . ';
		}';

		// RTL CSS
		if ( 'yes' === get_option( 'wws_rtl_status' ) ) {

			$dynamic_css .= '.wws-popup-container * { direction: rtl; }
				#wws-layout-1 .wws-popup__header,
				#wws-layout-2 .wws-popup__header,
				#wws-layout-6 .wws-popup__header {
					display: flex;
					flex-direction: row-reverse;
				}
				#wws-layout-1 .wws-popup__input-wrapper { float: left; }';

		}

		// Scroll length CSS
		if ( get_option( 'wws_scroll_length' ) ) {
			$dynamic_css .= '.wws-popup-container { display: none; }';
		}

		// Display only icon CSS
		if ( '' === get_option( 'wws_trigger_button_text' ) ) {
			$dynamic_css .= '.wws-popup__open-btn {
				display: flex;
				justify-content: center;
				align-items: center;
				width: 56px;
				height: 56px;
				border-radius: 50%;
				margin-top: 10px;
				cursor: pointer;
			}
			.wws-popup__open-btn svg {
				height: auto;
				width: 30px;
			}';
		} else {
			$dynamic_css .= '.wws-popup__open-btn {
				padding: 8px 20px;
				border-radius: 20px;
				display: inline-block;
				margin-top: 15px;
				cursor: pointer;
			}';
		}

		// Dynamic CSS according to Mobile
		if ( wp_is_mobile() == true ) {
			if ( get_option( 'wws_mobile_location' ) == 'tl' ) {
				$dynamic_css .= '.wws-popup-container--position {
					left: ' . intval( $x_axis_offset ) . 'px;
					top: ' . intval( $y_axis_offset ) . 'px;
				}
				.wws-popup__open-btn { float: left; }';
			}
			if ( get_option( 'wws_mobile_location' ) == 'tc' ) {
				$dynamic_css .= '.wws-popup-container--position {
					top: ' . intval( $y_axis_offset ) . 'px;
					left: 0;
					right: 0;
					margin-left: auto;
					margin-right: auto;
				}
				.wws-popup { margin: 0 auto; }
				.wws-popup__footer { text-align: center; }';
			}
			if ( get_option( 'wws_mobile_location' ) == 'tr' ) {
				$dynamic_css .= '.wws-popup-container--position {
					right: ' . intval( $x_axis_offset ) . 'px;
					top: ' . intval( $y_axis_offset ) . 'px;
				}
				.wws-popup__open-btn { float: right; }';
			}

			if ( get_option( 'wws_mobile_location' ) == 'bl' ) {
				$dynamic_css .= '.wws-popup-container--position {
					left: ' . intval( $x_axis_offset ) . 'px;
					bottom: ' . intval( $y_axis_offset ) . 'px;
				}
				.wws-popup__open-btn { float: left; }';
			}
			if ( get_option( 'wws_mobile_location' ) == 'bc' ) {
				$dynamic_css .= '.wws-popup-container--position {
					bottom: ' . intval( $y_axis_offset ) . 'px;
					left: 0;
					right: 0;
					margin-left: auto;
					margin-right: auto;
				}
				.wws-popup { margin: 0 auto; }
				.wws-popup__footer { text-align: center; }';
			}
			if ( get_option( 'wws_mobile_location' ) == 'br' ) {
				$dynamic_css .= '.wws-popup-container--position {
					right: ' . intval( $x_axis_offset ) . 'px;
					bottom: ' . intval( $y_axis_offset ) . 'px;
				}
				.wws-popup__open-btn { float: right; }';
			}
		}

		// Dynamic CSS according to Desktop
		if ( wp_is_mobile() != true ) {
			if ( get_option( 'wws_desktop_location' ) == 'tl' ) {
				 $dynamic_css .= '.wws-popup-container--position {
					left: ' . intval( $x_axis_offset ) . 'px;
					top: ' . intval( $y_axis_offset ) . 'px;
				}
				.wws-popup__open-btn { float: left; }
				.wws-gradient--position {
				  top: 0;
				  left: 0;
				  background: radial-gradient(ellipse at top left, rgba(29, 39, 54, 0.2) 0, rgba(29, 39, 54, 0) 72%);
				}';
			}

			if ( get_option( 'wws_desktop_location' ) == 'tc' ) {
				$dynamic_css .= '.wws-popup-container--position {
					top: ' . intval( $y_axis_offset ) . 'px;
					left: 0;
					right: 0;
					margin-left: auto;
					margin-right: auto;
				}
				.wws-popup__footer { text-align: center; }
				.wws-popup { margin: 0 auto; }
				.wws-gradient--position {
				  top: 0;
				  left: 0;
				  right: 0;
				  margin-left: auto;
				  margin-right: auto;
				  background: radial-gradient(ellipse at top, rgba(29, 39, 54, 0.2) 0, rgba(29, 39, 54, 0) 72%);
				}';
			}
			if ( get_option( 'wws_desktop_location' ) == 'tr' ) {
				$dynamic_css .= '.wws-popup-container--position {
					right: ' . intval( $x_axis_offset ) . 'px;
					top: ' . intval( $y_axis_offset ) . 'px;
				}
				.wws-popup__open-btn { float: right; }
				.wws-gradient--position {
				  top: 0;
				  right: 0;
				  background: radial-gradient(ellipse at top right, rgba(29, 39, 54, 0.2) 0, rgba(29, 39, 54, 0) 72%);
				}';
			}
			if ( get_option( 'wws_desktop_location' ) == 'bl' ) {
				$dynamic_css .= '.wws-popup-container--position {
					left: ' . intval( $x_axis_offset ) . 'px;
					bottom: ' . intval( $y_axis_offset ) . 'px;
				}
				.wws-popup__open-btn { float: left; }
				.wws-gradient--position {
				  bottom: 0;
				  left: 0;
				  background: radial-gradient(ellipse at bottom left, rgba(29, 39, 54, 0.2) 0, rgba(29, 39, 54, 0) 72%);
				}';
			}
			if ( get_option( 'wws_desktop_location' ) == 'bc' ) {
				$dynamic_css .= '.wws-popup-container--position {
					bottom: ' . intval( $y_axis_offset ) . 'px;
					left: 0;
					right: 0;
					margin-left: auto;
					margin-right: auto;
				}
				.wws-popup__footer { text-align: center; }
				.wws-popup { margin: 0 auto; }
				.wws-gradient--position {
				  bottom: 0;
				  left: 0;
				  right: 0;
				  margin-left: auto;
				  margin-right: auto;
				  background: radial-gradient(ellipse at bottom, rgba(29, 39, 54, 0.2) 0, rgba(29, 39, 54, 0) 72%);
				}';
			}
			if ( get_option( 'wws_desktop_location' ) == 'br' ) {
				$dynamic_css .= '.wws-popup-container--position {
					right: ' . intval( $x_axis_offset ) . 'px;
					bottom: ' . intval( $y_axis_offset ) . 'px;
				}
				.wws-popup__open-btn { float: right; }
				.wws-gradient--position {
				  bottom: 0;
				  right: 0;
				  background: radial-gradient(ellipse at bottom right, rgba(29, 39, 54, 0.2) 0, rgba(29, 39, 54, 0) 72%);
				}';
			}
		}
		if ( 'yes' === get_option( 'wws_trigger_button_only_icon' ) ) {
		   $dynamic_css .= '@media( max-width: 720px ) {
				.wws-popup__open-btn {
					padding: 0 !important;
					width: 60px !important;
					height: 60px !important;
					border-radius: 50% !important;
					display: flex !important;
					justify-content: center !important;
					align-items: center !important;
					font-size: 30px !important;
				}
				.wws-popup__open-btn > svg {
					padding-right: 0;
					width: 30px;
					height: 30px;
				}
				.wws-popup__open-btn span { display: none; }
			}';
		}
		$dynamic_css .= wp_kses_post( get_option( 'wws_custom_css' ) );

		wp_add_inline_style( 'wws-public-style', $dynamic_css );
	}

} // end of class WWS_Enqueue_Scripts

$wws_enqueue_scripts = new WWS_Enqueue_Scripts;
