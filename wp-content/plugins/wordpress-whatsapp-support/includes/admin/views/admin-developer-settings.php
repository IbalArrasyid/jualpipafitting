<?php
return apply_filters( 'wws_developer_settings', array(
	'wws_debug_status'   => array(
		'title'    => __( 'Debug', 'wc-wws' ),
		'desc'     => __( 'Enable/ Disable', 'wc-wws' ),
		'desc_tip' => __( 'By enabling debug option you allow us to view plugin settings, server environment, installed plugins.', 'wc-wws' ),
		'id'       => 'wws_debug_status',
		'default'  => 'no',
		'type'     => 'checkbox',
	),
	'wws_reset_settings' => array(
		'title'             => __( 'Reset Settings', 'wc-wws' ),
		'desc_tip'          => __( 'Reset all settings to default.', 'wc-wws' ),
		'value'             => __( 'Reset', 'wc-wws' ),
		'link'              => wp_nonce_url( '?wws_action=wws_reset_settings' ),
		'type'              => 'link',
		'class'             => 'button button-secondary',
		'custom_attributes' => array(
			'onClick' => 'return confirm( "' . __( 'Are you sure?', 'wc-wws' ) . '" )',
		),
	),
	'wws_delete_all'     => array(
		'title'    => __( 'Delete Plugin Setting', 'wc-wws' ),
		'desc'     => __( 'Enable/ Disable', 'wc-wws' ),
		'desc_tip' => __( 'If you want to delete plugin settings stored in database then enable this option and then click on the save changes button. Now delete the plugin from your plugins page.', 'wc-wws' ),
		'id'       => 'wws_delete_all',
		'default'  => 'no',
		'type'     => 'checkbox',
	),
) );
