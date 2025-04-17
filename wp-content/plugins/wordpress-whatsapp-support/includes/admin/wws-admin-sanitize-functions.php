<?php
// Preventing to direct access
defined( 'ABSPATH' ) OR die( 'Direct access not acceptable!' );

// Plugin custom sanitization
function wws_sanitize_filter_by_schedule( $input ) {
	$sanitize_input = array();

	if ( is_array( $input ) ) {
		foreach( $input as $i_key => $i ) {
			$sanitize_input[$i_key] = array(
				'status'    => isset( $i['status'] ) ? 'yes' : 'no',
				'start'     => sanitize_text_field( $i['start'] ),
				'end'       => sanitize_text_field( $i['end'] ),
			);
		}
	} else {
		$sanitize_input = array(
			'mon' => array( 'status' => 'yes', 'start' => '00:00:00', 'end' => '23:59:59' ),
			'tue' => array( 'status' => 'yes', 'start' => '00:00:00', 'end' => '23:59:59' ),
			'wed' => array( 'status' => 'yes', 'start' => '00:00:00', 'end' => '23:59:59' ),
			'thu' => array( 'status' => 'yes', 'start' => '00:00:00', 'end' => '23:59:59' ),
			'fri' => array( 'status' => 'yes', 'start' => '00:00:00', 'end' => '23:59:59' ),
			'sat' => array( 'status' => 'yes', 'start' => '00:00:00', 'end' => '23:59:59' ),
			'sun' => array( 'status' => 'yes', 'start' => '00:00:00', 'end' => '23:59:59' )
		);
	}

	return $sanitize_input;
}

function wws_sanitize_absint( $input ) {
		return '0';
}
