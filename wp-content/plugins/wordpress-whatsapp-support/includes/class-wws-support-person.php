<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WWS_Support_Person.
 *
 * @since 2.4.0
 */
class WWS_Support_Person {

	/**
	 * Contains support person data.
	 *
	 * @since 2.4.0
	 *
	 * @var array
	 */
	protected $data = array(
		'id'            => 0,
		'name'          => '',
		'title'         => '',
		'image'         => '',
		'contact'       => '',
		'call_number'   => '',
		'pre_message'   => '',
		'schedule'      => array(),
	);

	/**
	 * Class constructor.
	 *
	 * @since 2.4.0
	 *
	 * @param string $support_person_id
	 */
	public function __construct( $support_person_id = '' ) {
		if ( $support_person_id ) {
			$this->read( $support_person_id );
		}
	}

	/**
	 * --------------------
	 * GETTER METHODS.
	 * --------------------
	 */

	/**
	 * Get support person ID.
	 *
	 * @since 2.4.0
	 *
	 * @return int Support person ID.
	 */
	public function get_id() {
		return absint( $this->data['id'] );
	}

	/**
	 * Get support person name.
	 *
	 * @since 2.4.0
	 *
	 * @return string Support person name.
	 */
	public function get_name() {
		return $this->data['name'];
	}

	/**
	 * Get support person title.
	 *
	 * @since 2.4.0
	 *
	 * @return string Support person title.
	 */
	public function get_title() {
		return $this->data['title'];
	}

	/**
	 * Get support person image.
	 *
	 * @since 2.4.0
	 *
	 * @return string Support person image.
	 */
	public function get_image() {
		return $this->data['image'];
	}

	/**
	 * Get support person WhatsApp number.
	 *
	 * @since 2.4.0
	 *
	 * @return string Support person WhatsApp number.
	 */
	public function get_contact() {
		return $this->data['contact'];
	}

	/**
	 * Get support person callback number.
	 *
	 * @since 2.4.0
	 *
	 * @return string Support person callback number.
	 */
	public function get_call_number() {
		return $this->data['call_number'];
	}

	/**
	 * Get WhatsApp Pre-message.
	 *
	 * @since 2.4.0
	 *
	 * @return string WhatsApp Pre-message.
	 */
	public function get_pre_message() {
		return $this->data['pre_message'];
	}

	/**
	 * Get support person schedule days.
	 *
	 * @since 2.4.0
	 *
	 * @return array Support person schedule days.
	 */
	public function get_schedule() {
		return $this->data['schedule'];
	}

	/**
	 * --------------------
	 * OTHER GETTER METHODS.
	 * --------------------
	 */

	/**
	 * Get delete support person URL.
	 *
	 * @since 2.4.0
	 *
	 * @return string URL to delete support person.
	 */
	public function get_delete_url() {
		$delete_url = add_query_arg(
			array(
				'support_person_id' => $this->get_id(),
			),
			admin_url()
		);

		return wp_nonce_url( $delete_url, 'wws_delete_support_person', 'wws_delete_support_person' );
	}

	/**
	 * Get edit support person URL.
	 *
	 * @since 2.4.0
	 *
	 * @return string URL to edit support person.
	 */
	public function get_edit_url() {
		$edit_url = add_query_arg(
			array(
				'page' => 'wc-whatsapp-support-persons',
				'tab'  => 'edit',
				'id'   => $this->get_id(),
			),
			admin_url( 'admin.php' )
		);

		return $edit_url;
	}

	/**
	 * --------------------
	 * SETTER METHODS.
	 * --------------------
	 */

	/**
	 * Set support person ID.
	 *
	 * @since 2.4.0
	 *
	 * @param int $id Support person ID.
	 * @return void
	 */
	protected function set_id( $id ) {
		$this->data['id'] = absint( $id );
	}

	/**
	 * Set support person name.
	 *
	 * @since 2.4.0
	 *
	 * @param string $name Support person name.
	 * @return void
	 */
	public function set_name( $name ) {
		$this->data['name'] = $name;
	}

	/**
	 * Set support person title.
	 *
	 * @since 2.4.0
	 *
	 * @param string $title Support person title.
	 * @return void
	 */
	public function set_title( $title ) {
		$this->data['title'] = $title;
	}

	/**
	 * Set support person image.
	 *
	 * @since 2.4.0
	 *
	 * @param string $image Support person image.
	 * @return void
	 */
	public function set_image( $image ) {
		$this->data['image'] = $image;
	}

	/**
	 * Set support person WhatsApp number.
	 *
	 * @since 2.4.0
	 *
	 * @param string $contact Support person WhatsApp number.
	 * @return void
	 */
	public function set_contact( $contact ) {
		$this->data['contact'] = $contact;
	}

	/**
	 * Set support person callback number.
	 *
	 * @since 2.4.0
	 *
	 * @param string $call_number Support person callback number.
	 * @return void
	 */
	public function set_call_number( $call_number ) {
		$this->data['call_number'] = $call_number;
	}

	/**
	 * Set pre-message.
	 *
	 * @since 2.4.0
	 *
	 * @param string $pre_message Pre-message.
	 * @return void
	 */
	public function set_pre_message( $pre_message ) {
		$this->data['pre_message'] = $pre_message;
	}

	/**
	 * Set support person schedule days.
	 *
	 * @since 2.4.0
	 *
	 * @param array $schedule Support person schedule days.
	 * @return void
	 */
	public function set_schedule( $schedule ) {
		$this->data['schedule'] = $schedule;
	}

	/**
	 * --------------------
	 * CONDITIONAL METHODS.
	 * --------------------
	 */

	/**
	 * Check whether support person exists or not.
	 *
	 * @since 2.4.0
	 *
	 * @return boolean True if exists, false otherwise.
	 */
	public function is_exists() {
		return 0 !== $this->get_id() ? true : false;
	}

	/**
	 * Check whether support person is available at current time or not.
	 *
	 * @since 2.4.0
	 *
	 * @return boolean True if available, false otherwise.
	 */
	public function is_available() {
		$availability = false;
		$current_day  = strtolower( current_time( 'D' ) );
		$current_time = strtotime( current_time( 'Y-m-d H:i:s' ) );
		$schedule     = $this->get_schedule();

		if ( ! isset( $schedule[ $current_day ]['availability'] ) ) {
			return $availability;
		}

		if ( ! isset( $schedule[ $current_day ]['timing'] ) || ! is_array( $schedule[ $current_day ]['timing'] ) ) {
			return $availability;
		}


		foreach ( $schedule[ $current_day ]['timing'] as $slot ) {
			if ( ! $slot || ! is_array( $slot ) ) {
				continue;
			}

			$start_time = strtotime( current_time( 'Y-m-d' ) . ' ' . $slot['start'] );
			$end_time   = strtotime( current_time( 'Y-m-d' ) . ' ' . $slot['end'] );

			if ( in_array( $current_time, range( $start_time, $end_time ) ) ) {
				$availability = true;

				break;
			}
		}

		return $availability;
	}

	/**
	 * Check whether support person has image or not.
	 *
	 * @since 2.4.0
	 *
	 * @return boolean True if has image, false otherwise.
	 */
	public function has_image() {
		return '' !== $this->get_image() ? true : false;
	}

	/**
	 * Check whether support person has callback number or not.
	 *
	 * @since 2.4.0
	 *
	 * @return boolean True if has callback number, false otherwise.
	 */
	public function has_call_number() {
		return '' !== $this->get_call_number() ? true : false;
	}

	/**
	 * --------------------
	 * CURD METHODS.
	 * --------------------
	 */

	/**
	 * Create an new support person.
	 *
	 * @since 2.4.0
	 *
	 * @return WWS_Support_Person|bool WWS_Support_Person object, false otherwise.
	 */
	public function create() {
		$support_person_id = get_option( 'wws_multi_support_persons_auto_increment' );

		if ( ! $support_person_id ) {
			$support_person_id = 1;
		}

		$support_persons = get_option( 'wws_multi_support_persons' );

		if ( ! is_array( $support_persons ) ) {
			$support_persons = array();
		}

		$support_persons[ $support_person_id ] = array(
			'id'            => absint( $support_person_id ),
			'name'          => $this->get_name(),
			'title'         => $this->get_title(),
			'image'         => $this->get_image(),
			'contact'       => $this->get_contact(),
			'call_number'   => $this->get_call_number(),
			'pre_message'   => $this->get_pre_message(),
			'schedule'      => $this->get_schedule(),
		);

		$this->set_id( $support_person_id );

		$create = update_option( 'wws_multi_support_persons', $support_persons );

		if ( ! $create ) {
			return false;
		}

		$support_person_id++;

		update_option( 'wws_multi_support_persons_auto_increment', $support_person_id );

		return $this;
	}

	/**
	 * Read support_person data.
	 *
	 * @since 2.4.0
	 *
	 * @param int $support_person_id Support person ID.
	 * @return WWS_Support_Person WWS_Support_Person object.
	 */
	public function read( $support_person_id ) {
		$support_persons = get_option( 'wws_multi_support_persons' );

		if ( ! $support_persons ||! is_array( $support_persons ) ) {
			return $this->data;
		}

		if ( ! isset( $support_persons[ $support_person_id ] ) ) {
			return $this->data;
		}

		$support_person = $support_persons[ $support_person_id ];

		$this->set_id( $support_person_id );
		$this->set_name( $support_person['name'] );
		$this->set_title( $support_person['title'] );
		$this->set_image( $support_person['image'] );
		$this->set_contact( $support_person['contact'] );
		$this->set_call_number( $support_person['call_number'] );
		$this->set_pre_message( $support_person['pre_message'] );
		$this->set_schedule( $support_person['schedule'] );
	}

	/**
	 * Update an support_person.
	 *
	 * @since 2.4.0
	 *
	 * @return WWS_Support_Person|bool WWS_Support_Person object, false otherwise.
	 */
	public function update() {
		if ( ! $this->is_exists() ) {
			return false;
		}

		$support_person_id            = $this->get_id();
		$support_persons              = get_option( 'wws_multi_support_persons' );
		$support_persons[ $support_person_id ] = array(
			'name'          => $this->get_name(),
			'title'         => $this->get_title(),
			'image'         => $this->get_image(),
			'contact'       => $this->get_contact(),
			'call_number'   => $this->get_call_number(),
			'pre_message'   => $this->get_pre_message(),
			'schedule'      => $this->get_schedule(),
		);

		$update = update_option( 'wws_multi_support_persons', $support_persons );

		if ( ! $update ) {
			return false;
		}

		return $this;
	}

	/**
	 * Delete an existing angent.
	 *
	 * @since 2.4.0
	 *
	 * @return WWS_Support_Person|bool WWS_Support_Person object, false otherwise.
	 */
	public function delete() {
		if ( ! $this->is_exists() ) {
			return false;
		}

		$support_persons = get_option( 'wws_multi_support_persons' );

		if ( ! is_array( $support_persons ) ) {
			return false;
		}

		unset( $support_persons[ $this->get_id() ] );

		$delete = update_option( 'wws_multi_support_persons', $support_persons );

		if ( ! $delete ) {
			return false;
		}

		return $this;
	}
}
