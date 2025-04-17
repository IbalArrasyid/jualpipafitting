<?php
return apply_filters( 'wws_ga_analytics_settings', array(
	'wws_ga_click_tracking_status'         => array(
		'title'    => __( 'Google Event Tracking', 'wc-wws' ),
		'desc'     => __( 'Enable/ Disable', 'wc-wws' ),
		'desc_tip' => __( 'You can enable or disable google event click tracking.', 'wc-wws' ),
		'id'       => 'wws_ga_click_tracking_status',
		'default'  => 'no',
		'type'     => 'checkbox',
	),
	'wws_ga_click_tracking_event_name'     => array(
		'title' => __( 'GA Event', 'wc-wws' ),
		'desc'  => __( 'Enter the name of custom the event to track on Google Analytics.', 'wc-wws' ),
		'id'    => 'wws_ga_click_tracking_event_name',
		'type'  => 'text',
		'class' => 'regular-text',
	),
	'wws_ga_click_tracking_event_category' => array(
		'title' => __( 'GA Event Category', 'wc-wws' ),
		'desc'  => __( 'Enter the name of the event category.', 'wc-wws' ),
		'id'    => 'wws_ga_click_tracking_event_category',
		'type'  => 'text',
		'class' => 'regular-text',
	),
	'wws_ga_click_tracking_event_label'    => array(
		'title' => __( 'GA Event Label', 'wc-wws' ),
		'desc'  => __( 'Enter the label of the event.', 'wc-wws' ),
		'id'    => 'wws_ga_click_tracking_event_label',
		'type'  => 'text',
		'class' => 'regular-text',
	),
) );
