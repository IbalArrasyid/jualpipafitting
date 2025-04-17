<?php

// Preventing to direct access
defined( 'ABSPATH' ) OR die( 'Direct access not acceptable!' );

class WWS_Admin_Save_Settings {

	public function __construct() {
		add_action( 'admin_init', array( $this, 'add_multi_support_person' ) );
		add_action( 'admin_init', array( $this, 'edit_multi_support_person' ) );
		add_action( 'admin_init', array( $this, 'delete_multi_support_person' ) );
	}

	public function add_multi_support_person() {
		if ( ! isset( $_POST['wws_add_multi_account_submit'] ) ) {
			return;
		}

		$support_person_id = get_option( 'wws_multi_support_persons_auto_increment' );

		if ( ! $support_person_id ) {
			$support_person_id = 1;
		}

		$setting    = get_option( 'wws_multi_support_persons' );
		$post_data  = wp_unslash( $_POST['wws_multi_account'] );
		$setting[ $support_person_id ]  = apply_filters( 'wws_save_multi_account_settings', array(
			'id'            => absint( $support_person_id ),
			'contact'       => sanitize_text_field( $post_data['contact'] ),
			'name'          => sanitize_text_field( $post_data['name'] ),
			'title'         => sanitize_text_field( $post_data['title'] ),
			'image'         => esc_url_raw( $post_data['image'] ),
			'pre_message'   => sanitize_textarea_field( $post_data['pre_message'] ),
			'call_number'   => sanitize_text_field( $post_data['call_number'] ),
			'schedule'      => $post_data['schedule'],
		), $post_data );

		$support_person_id++;

		update_option( 'wws_multi_support_persons_auto_increment', $support_person_id );
		update_option( 'wws_multi_support_persons', $setting );

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'   => 'wc-whatsapp-support-persons',
					'notice' => __( 'Support person has been added successfully.', 'wc-wws' ),
				),
				admin_url( 'admin.php' )
			)
		);

		exit;
	}

	public function edit_multi_support_person() {
		if ( ! isset( $_POST['wws_edit_multi_account_submit'] ) ) {
			return;
		}

		$setting         = get_option( 'wws_multi_support_persons' );
		$key             = $_POST['wws_multi_account']['key'];
		$post_data       = wp_unslash( $_POST['wws_multi_account'] );
		$setting[ $key ] = apply_filters( 'wws_save_multi_account_settings', array(
			'contact'       => sanitize_text_field( $post_data['contact'] ),
			'name'          => sanitize_text_field( $post_data['name'] ),
			'title'         => sanitize_text_field( $post_data['title'] ),
			'image'         => esc_url_raw( $post_data['image'] ),
			'pre_message'   => sanitize_textarea_field( $post_data['pre_message'] ),
			'call_number'   => sanitize_text_field( $post_data['call_number'] ),
			'schedule'      => $post_data['schedule'],
		), $post_data );

		update_option( 'wws_multi_support_persons', $setting );

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'   => 'wc-whatsapp-support-persons',
					'tab'    => 'edit',
					'id'     => absint( $key ),
					'notice' => __( 'Support person has been edited successfully.', 'wc-wws' ),
				),
				admin_url( 'admin.php' )
			)
		);

		exit;

	}

	public function delete_multi_support_person() {
		if ( ! isset( $_GET['wws_delete_support_person'] ) || ! wp_verify_nonce( $_GET['wws_delete_support_person'], 'wws_delete_support_person' ) ) {
			return;
		}

		if ( ! isset( $_GET['support_person_id'] ) ) {
			return;
		}
		$support_person_id = absint( $_GET['support_person_id'] );
		$support_person    = new WWS_Support_Person( $support_person_id );

		if ( ! $support_person->is_exists() ) {
			return;
		}

		$support_person->delete();

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'   => 'wc-whatsapp-support-persons',
					'notice' => __( 'Support person has been deleted successfully.', 'wc-wws' ),
				),
				admin_url( 'admin.php' )
			)
		);

		exit;
	}

}

$wws_admin_save_settings = new WWS_Admin_Save_Settings;
