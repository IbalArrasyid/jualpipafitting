<?php
return apply_filters( 'wws_widget_text_settings', array(
	array(
		'title'     => __( 'Trigger Button Text', 'wc-wws' ),
		'desc_tip'  => __( 'If you leave it blank than, trigger button shown as only icon.', 'wc-wws' ),
		'id'        => 'wws_trigger_button_text',
		'type'      => 'text',
		'class'     => 'regular-text',
	),
	array(
		'title'     => __( 'About Support.', 'wc-wws' ),
		'desc_tip'  => __( 'About Support.', 'wc-wws' ),
		'id'        => 'wws_about_support_text',
		'type'      => 'textarea',
		'class'     => 'regular-text',
		'css'	=> 'height:120px',
	),
	array(
		'title'     => __( 'Welcome Messages', 'wc-wws' ),
		'desc_tip'  => __( 'Welcome message from Support. Sometime this can be use as pre message.', 'wc-wws' ),
		'id'        => 'wws_welcome_message',
		'type'      => 'text',
		'class'     => 'regular-text',
	),
	array(
		'title'     => __( 'Input Placeholder Text', 'wc-wws' ),
		'desc_tip'  => __( 'Input placeholder.', 'wc-wws' ),
		'id'        => 'wws_input_placeholder_text',
		'type'      => 'text',
		'class'     => 'regular-text',
	),
	array(
		'title'     => __( 'Number Placeholder Text', 'wc-wws' ),
		'desc_tip'  => __( 'Enter the placeholder text for asking WhatsApp number from visitors.', 'wc-wws' ),
		'id'        => 'wws_number_placeholder_text',
		'type'      => 'text',
		'class'     => 'regular-text',
	),
	array(
		'title'     => __( 'Number Masking', 'wc-wws' ),
		'desc'      => array(
				wp_kses_post(
					'<a href="#" data-wws-mask="(999) 999-9999">U.S.A. Format</a> ·
					<a href="#" data-wws-mask="99999-99999">India Format</a>  ·
					<a href="#" data-wws-mask="">No Number Mask</a>'
				),
				__( '9 - Represents a numeric character (0-9)', 'wc-wws' ),
				'------------------------------------------------------------',
				sprintf( __( 'Facing issue with number masking? feel free to <a href="%s" target="_blank">contact us</a>', 'wc-wws' ), 'https://codecanyon.net/item/x/20963962/support' ),
			),
		'desc_tip'  => __( 'You can enter the phone number masking as per your need.', 'wc-wws' ),
		'id'        => 'wws_number_masking',
		'type'      => 'text',
		'class'     => 'regular-text',
	),
	array(
		'title'     => __( 'Predefined Text', 'wc-wws' ),
		'desc'      => wp_kses_post( sprintf( __( '%s to display current page title in chat.<br>%s to display current page URL in chat.<br>%s to break the line into a new line.', 'wc-wws' ), '<code>{title}</code>', '<code>{url}</code>', '<code>{br}</code>' ) ),
		'desc_tip'  => __( 'This will automatically append the following options along with user text.', 'wc-wws' ),
		'id'        => 'wws_predefined_text',
		'type'      => 'textarea',
		'class'     => 'regular-text',
		'css'	    => 'height:120px',
	),
	array(
		'title'     => __( 'Support Person Available Text', 'wc-wws' ),
		'id'        => 'wws_support_person_available_text',
		'type'      => 'text',
		'class'     => 'regular-text',
	),
	array(
		'title'     => __( 'Support Person Not Available Text', 'wc-wws' ),
		'id'        => 'wws_support_person_not_available_text',
		'type'      => 'text',
		'class'     => 'regular-text',
	),
) );
