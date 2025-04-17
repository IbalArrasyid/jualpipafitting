<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get support persons.
 *
 * @since 2.4.0
 *
 * @return array|bool Array of WWS_Support_Person objects, false otherwise.
 */
function wws_get_support_persons() {
	$support_persons    = array();
	$db_support_persons = get_option( 'wws_multi_support_persons' );

	if ( ! $db_support_persons || ! is_array( $db_support_persons ) ) {
		return false;
	}

	foreach ( $db_support_persons as $db_support_person_id => $db_support_person ) {
		$support_persons[ $db_support_person_id ] = new WWS_Support_Person( $db_support_person_id );
	}

	return $support_persons;
}
