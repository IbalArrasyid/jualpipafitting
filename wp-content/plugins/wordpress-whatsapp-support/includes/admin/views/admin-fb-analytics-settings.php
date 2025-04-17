<?php
return apply_filters( 'wws_fb_analytics_settings', array(
	'wws_fb_click_tracking_status'      => array(
		'title'    => __( 'Facebook Click Tracking', 'wc-wws' ),
		'desc'     => __( 'Enable/ Disable', 'wc-wws' ),
		'desc_tip' => __( 'You can enable or disable facebook pixel click tracking.', 'wc-wws' ),
		'id'       => 'wws_fb_click_tracking_status',
		'default'  => 'no',
		'type'     => 'checkbox',
	),
	'wws_fb_click_tracking_event_name'  => array(
		'title' => __( 'FB Event', 'wc-wws' ),
		'desc'  => __( 'Enter the name of custom event to track on Facebook pixel.', 'wc-wws' ),
		'id'    => 'wws_fb_click_tracking_event_name',
		'type'  => 'text',
		'class' => 'regular-text',
	),
	'wws_fb_click_tracking_event_label' => array(
		'title' => __( 'FB Event Label', 'wc-wws' ),
		'desc'  => __( 'Enter the label of custom event to track on Facebook pixel.', 'wc-wws' ),
		'id'    => 'wws_fb_click_tracking_event_label',
		'type'  => 'text',
		'class' => 'regular-text',
	),
) );
