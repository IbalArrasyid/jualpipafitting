<?php

require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

class WWS_Admin_Analytics_Table extends WP_List_Table {

	private $_table;

	/** Class constructor */
	public function __construct() {
		global $wpdb;

		$this->_table = $wpdb->prefix . 'wws_analytics';

		parent::__construct( [
			'singular' => esc_html__( 'Analytic', 'wc-wws' ), //singular name of the listed records
			'plural'   => esc_html__( 'Analytics', 'wc-wws' ), //plural name of the listed records
			'ajax'     => false, //should this table support ajax?
		] );
	}

	public function prepare_items() {
		$order_by    = isset( $_GET['orderby'] ) ? trim( $_GET['orderby'] ) : '';
		$order       = isset( $_GET['order'] ) ? trim( $_GET['order'] ) : '';
		$search_term = isset( $_POST['s'] ) ? trim( $_POST['s'] ) : '';

		$data = $this->wp_list_table_data( $order_by, $order, $search_term );

		$pre_page     = 10;
		$currnet_page = $this->get_pagenum();
		$total_items  = count( $data );

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $pre_page,
		) );

		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items           = array_slice( $data, (  ( $currnet_page - 1 ) * $pre_page ), $pre_page );
	}

	public function wp_list_table_data( $order_by = '', $order = '', $search_term = '' ) {
		global $wpdb;

		// Search results.
		if ( '' !== $search_term ) {
			return $wpdb->get_results(
				"SELECT *
				FROM $this->_table
				WHERE visitor_ip LIKE '%$search_term%'
				OR number LIKE '%$search_term%'
				OR message LIKE '%$search_term%'
				OR through LIKE '%$search_term%'
				OR referral LIKE '%$search_term%'
				OR device_type LIKE '%$search_term%'
				OR os LIKE '%$search_term%'
				OR browser LIKE '%$search_term%'
				OR date LIKE '%$search_term%'",
				ARRAY_A
			);
		} elseif ( '' !== $order_by && '' !== $order ) {
			if ( 'visitor_ip' === $order_by && 'asc' === $order ) {
				return $wpdb->get_results(
					"SELECT *
					FROM $this->_table
					ORDER BY visitor_ip ASC",
					ARRAY_A
				);
			}
			if ( 'visitor_ip' === $order_by && 'desc' === $order ) {
				return $wpdb->get_results(
					"SELECT *
					FROM $this->_table
					ORDER BY visitor_ip DESC",
					ARRAY_A
				);
			}
			if ( 'date' === $order_by && 'asc' === $order ) {
				return $wpdb->get_results(
					"SELECT *
					FROM $this->_table
					ORDER BY timestamp ASC",
					ARRAY_A
				);
			}
			if ( 'date' === $order_by && 'desc' === $order ) {
				return $wpdb->get_results(
					"SELECT *
					FROM $this->_table
					ORDER BY timestamp DESC",
					ARRAY_A
				);
			}
		} else { // Display all results.
			return $wpdb->get_results(
				"SELECT *
				FROM $this->_table
				ORDER BY id DESC",
				ARRAY_A
			);
		}
	}

	public function get_columns() {
		$columns = apply_filters( 'wws_analytics_get_columns', array(
			'cb'          => '<input type="checkbox" />',
			'visitor_ip'  => esc_html__( 'Visitors IP', 'wc-wws' ),
			'number'      => esc_html__( 'Number', 'wc-wws' ),
			'message'     => esc_html__( 'Message', 'wc-wws' ),
			'through'     => esc_html__( 'Through', 'wc-wws' ),
			'referral'    => esc_html__( 'Referral', 'wc-wws' ),
			'device_type' => esc_html__( 'Device Type', 'wc-wws' ),
			'os'          => esc_html__( 'OS', 'wc-wws' ),
			'browser'     => esc_html__( 'Browser', 'wc-wws' ),
			'date'        => esc_html__( 'Date', 'wc-wws' ),
		) );

		return $columns;
	}

	public function column_default( $item, $column_name ) {
		$value = __( 'No Value', 'wc-wws' );

		if ( 'visitor_ip' === $column_name
		|| 'number' === $column_name
		|| 'message' === $column_name
		|| 'through' === $column_name
		|| 'device_type' === $column_name
		|| 'os' === $column_name
		|| 'browser' === $column_name
		|| 'date' === $column_name ) {
			$value = $item[ $column_name ];
		}

		if ( 'referral' === $column_name ) {
			$value = sprintf( '<a href="%1$s" target="_blank">%1$s</a>', $item[ $column_name ] );
		}

		return apply_filters( 'wws_analytics_column_default_value', $value, $column_name, $item );
	}

	function column_visitor_ip( $item ) {
		$actions = array(
			'info'   => sprintf( '<a href="javascript:;" data-ip="%s">%s</a>', $item['visitor_ip'], esc_html__( 'Info', 'wc-wws' ) ),
			'delete' => sprintf( '<a href="?wws_delete_analytics=%s">%s</a>', $item['id'], esc_html__( 'Delete', 'wc-wws' ) ),
		);

		return sprintf( '%1$s %2$s', $item['visitor_ip'], $this->row_actions( $actions ) );
	}

	public function get_hidden_columns() {
		return array();
	}

	public function get_sortable_columns() {
		return array(
			'visitor_ip' => array( 'visitor_ip', true ),
			'date'       => array( 'date', true ),
		);
	}

	public function get_bulk_actions() {
		$actions = array(
			'delete' => __( 'Delete', 'wc-wws' ),
		);
		return $actions;
	}

	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="post[]" value="%s" />', $item['id'] );
	}
}
