<?php

// Preventing to direct access
defined( 'ABSPATH' ) OR die( 'Direct access not acceptable!' );

/**
* This class is adding button generator via Visual Composer.
* @author Sonu Kaushal
* @since 1.6
*/
class WWS_Visual_Composer {

	public function __construct() {

		add_action( 'vc_before_init', array( $this, 'support_button' ) );

	}

	/**
	* Adding support button in visual composer
	* @since 1.6
	*/
	public function support_button() {

		vc_map(
			array(
				"name"      => esc_html__( "WhatsApp Support Button", 'wc-wws' ),
				"base"      => "whatsappsupport",
				"class"     => "",
				"category"  => esc_html__( "Content", 'wc-wws'),
				"icon"      => esc_url( WWS_PLUGIN_URL . 'assets/img/admin/vc-icon-1.png' ),
				"params" => array(

					array(
						"type"          => "textfield",
						"class"         => "",
						"heading"       => esc_html__( "Support Number", 'wc-wws' ),
						"param_name"    => "number",
						"value"         => esc_html__( "911234567890", 'wc-wws' ),
						"description"   => esc_html__( "Enter mobile phone number with the international country code, without '+' character.", 'wc-wws' ),
					),

					array(
						"type"          => "textfield",
						"class"         => "",
						"heading"       => esc_html__( "Button Text", 'wc-wws' ),
						"param_name"    => "text",
						"value"         => esc_html__( "Contact Us", 'wc-wws' ),
					),

					array(
						"type"          => "colorpicker",
						"class"         => "",
						"heading"       => esc_html__( "Button Color", 'wc-wws' ),
						"param_name"    => "bg-color",
						"value"         => '#22c15e',
					),

					array(
						"type"          => "colorpicker",
						"class"         => "",
						"heading"       => esc_html__( "Button Text Color", 'wc-wws' ),
						"param_name"    => "text-color",
						"value"         => '#ffffff',
					),
					array(
						'type'          => 'dropdown',
						'heading'       => esc_html__( 'Button Bold Text', 'wc-wws' ),
						'param_name'    => 'text-bold',
						'value'         => array(
							esc_html__( 'No', 'wc-wws' )  => '300',
							esc_html__( 'Yes', 'wc-wws' ) => '700',
						),
					),

					array(
						'type'          => 'dropdown',
						'heading'       => esc_html__( 'Button Font Style', 'wc-wws' ),
						'param_name'    => 'font',
						'value'         => array(
							esc_html__( 'Theme Default', 'wc-wws' )   => '',
							esc_html__( 'Lobster', 'wc-wws' )         => 'Lobster',
							esc_html__( 'Satisfy', 'wc-wws' )         => 'Satisfy',
							esc_html__( 'Bree Serif', 'wc-wws' )      => 'Bree Serif',
							esc_html__( 'Oswald', 'wc-wws' )          => 'Oswald',
							esc_html__( 'Ubuntu', 'wc-wws' )          => 'Ubuntu',
							esc_html__( 'Dancing Script', 'wc-wws' )  => 'Dancing Script',
						),
					),

					array(
						"type"          => "textfield",
						"class"         => "",
						"heading"       => esc_html__( "Pre Populate Message", 'wc-wws' ),
						"param_name"    => "message",
						"value"         => esc_html__( "Hello...", 'wc-wws' ),
						"description"   => esc_html__( "This is what you will receive when user sent message first time.", 'wc-wws' ),
					),

					array(
						'type'          => 'dropdown',
						'heading'       => esc_html__( 'Full Width Button', 'wc-wws' ),
						'param_name'    => 'full-width',
						'value'         => array(
							esc_html__( 'No', 'wc-wws' )    => 'no',
							esc_html__( 'Yes', 'wc-wws' )   => 'yes',
						),
						"description"   => esc_html__( "This will expand button to full width.", 'wc-wws' )
					),

					array(
						'type'          => 'dropdown',
						'heading'       => esc_html__( 'Display On Mobile', 'wc-wws' ),
						'param_name'    => 'on-mobile',
						'value'         => array(
							esc_html__( 'Yes', 'wc-wws' )   => 'yes',
							esc_html__( 'No', 'wc-wws' )    => 'no',
						),
					),

					array(
						'type'          => 'dropdown',
						'heading'       => esc_html__( 'Display On Desktop', 'wc-wws' ),
						'param_name'    => 'on-desktop',
						'value'         => array(
							esc_html__( 'Yes', 'wc-wws' )   => 'yes',
							esc_html__( 'No', 'wc-wws' )    => 'no',
						),
					),


				)
			)
		);

	}


} // WWS_Visual_Composer

$wws_visual_composer = new WWS_Visual_Composer;
