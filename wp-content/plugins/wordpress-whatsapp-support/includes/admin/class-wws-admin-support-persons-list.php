<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

/**
 * Class WWS_Admin_Support_Persons_List.
 *
 * @since 2.4.0
 */
class WWS_Admin_Support_Persons_List extends WP_List_Table {

	/**
	 * Class constructor.
	 *
	 * @since 2.4.0
	 */
	public function __construct() {
		global $wpdb;

		parent::__construct(
			array(
				'singular' => esc_html__( 'Support Person', 'wc-wws' ),
				'plural'   => esc_html__( 'Support Persons', 'wc-wws' ),
				'ajax'     => false,
			)
		);

	}

	/**
	 * Prepares the list of items for displaying.
	 *
	 * @uses WP_List_Table::set_pagination_args()
	 *
	 * @since 2.4.0
	 */
	public function prepare_items() {
		$this->process_bulk_action();

		$data = $this->wp_list_table_data();

		$pre_page     = 40;
		$currnet_page = $this->get_pagenum();
		$total_items  = count( $data );

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $pre_page,
			)
		);

		$columns  = $this->get_columns();

		$this->_column_headers = array( $columns );
		$this->items           = array_slice( $data, ( ( $currnet_page - 1 ) * $pre_page ), $pre_page );

	}

	public function wp_list_table_data() {
		$support_persons  = array();
		$_support_persons = get_option( 'wws_multi_support_persons', array() );

		foreach ( $_support_persons as $_support_person_id => $_support_person ) {
			$support_persons[ $_support_person_id ]       = $_support_person;
			$support_persons[ $_support_person_id ]['id'] = $_support_person_id;
		}

		return $support_persons;
	}

	/**
	 * Gets a list of columns.
	 *
	 * The format is:
	 * - `'internal-name' => 'Title'`
	 *
	 * @since 2.4.0
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'cb'          => '<input type="checkbox" />',
			'name'        => esc_html__( 'Name', 'wc-wws' ),
			'title'       => esc_html__( 'Title', 'wc-wws' ),
			'contact'     => esc_html__( 'WhatsApp Number', 'wc-wws' ),
			'call_number' => esc_html__( 'Callback Number', 'wc-wws' ),
		);

		return $columns;
	}

	/**
	 * Default columns value.
	 *
	 * @since 2.4.0
	 *
	 * @param object|array $item
	 * @param string $column_name
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'name':
			case 'title':
			case 'contact':
			case 'call_number':
				return $item[ $column_name ];

			default:
				return __( 'No Value', 'wc-wws' );
		}
	}

	/**
	 * Support person name column.
	 *
	 * @since 2.4.0
	 *
	 * @param array $item Item array.
	 * @return string
	 */
	public function column_name( $item ) {
		$support_person = new WWS_Support_Person( $item['id'] );
		$actions        = array(
			'id'     => sprintf( 'ID: %d', absint( $item['id'] ) ),
			'edit'   => sprintf( '<a href="%s">%s</a>', esc_url( $support_person->get_edit_url() ), esc_html__( 'Edit', 'wc-wws' ) ),
			'delete' => sprintf( '<a href="%s" onclick="return confirm( \'%s\' )">%s</a>', esc_url( $support_person->get_delete_url() ), esc_attr__( 'Are you sure?', 'wc-wws' ), esc_html__( 'Delete', 'wc-wws' ) ),
		);

		return sprintf( '%1$s %2$s', $item['name'], $this->row_actions( $actions ) );
	}

	/**
	 * Checkbox column.
	 *
	 * @since 2.4.0
	 *
	 * @param array $item Item array.
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="support_persons_ids[]" value="%s" />', $item['id'] );
	}

	/**
	 * Gets a list of CSS classes for the WP_List_Table table tag.
	 *
	 * @since 2.4.0
	 *
	 * @return string[] Array of CSS classes for the table tag.
	 */
	public function get_table_classes() {
		return array( 'widefat', 'striped', $this->_args['plural'] );
	}

	/**
	 * Custom bulk actions.
	 *
	 * @since 2.4.0
	 *
	 * @return array Array of bulk actions.
	 */
	public function get_bulk_actions() {
		return array(
			'delete' => esc_html__( 'Delete', 'wc-wws' ),
		);
	}

	/**
	 * Process custom bulk actions.
	 *
	 * @since 2.4.0
	 *
	 * @return void
	 */
	public function process_bulk_action() {

		// security check!
		if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

			$nonce  = wp_unslash( $_POST['_wpnonce'] );
			$action = 'bulk-' . $this->_args['plural'];

			if ( ! wp_verify_nonce( $nonce, $action ) ) {
				return;
			}
		}

		$action = $this->current_action();

		if ( 'delete' === $action ) {
			$support_persons_ids = $_POST['support_persons_ids'];

			foreach ( $support_persons_ids as $support_persons_id ) {
				$support_person = new WWS_Support_Person( $support_persons_id );
				$support_person->delete();
			}

			wp_safe_redirect(
				add_query_arg(
					array(
						'page'   => 'wc-whatsapp-support-persons',
						'notice' => __( 'Deleted successfully.', 'wc-wws' ),
					),
					admin_url( 'admin.php' )
				)
			);

			exit;
		}
	}
}
