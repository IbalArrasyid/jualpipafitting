<?php
return apply_filters( 'wws_gdpr_settings', array(
	'wws_gdpr_status'       => array(
		'title'    => __( 'GDPR', 'wc-wws' ),
		'desc'     => __( 'Enable/ Disable', 'wc-wws' ),
		'desc_tip' => __( 'Enable/ Disable GDPR compliant.', 'wc-wws' ),
		'id'       => 'wws_gdpr_status',
		'default'  => 'no',
		'type'     => 'checkbox',
	),
	'wws_gdpr_message'      => array(
		'title' => __( 'GDPR Message', 'wc-wws' ),
		'desc'  => wp_kses_post( sprintf( __( 'Use shortcode %s to add privacy page link.', 'wc-wws' ), '<code>{policy_url}</code>' ) ),
		'id'    => 'wws_gdpr_message',
		'type'  => 'textarea',
		'class' => 'regular-text',
		'css'   => 'height:120px',
	),
	'wws_gdpr_privacy_page' => array(
		'title'       => __( 'Privacy page', 'wc-wws' ),
		'desc_tip'    => __( 'Select your privacy page.', 'wc-wws' ),
		'id'          => 'wws_gdpr_privacy_page',
		'default'     => '',
		'type'        => 'dropdown_pages',
		'multiselect' => false,
		'class'       => 'wws-select2 regular-text',
	),
) );
