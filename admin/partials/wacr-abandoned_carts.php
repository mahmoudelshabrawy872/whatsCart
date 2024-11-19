<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Class WacR_list_Table
 */
class WacR_list_Table extends WP_List_Table {

	/**
	 * Prepares the list of items for displaying.
	 */
	public function prepare_items() {

		$order_by = isset( $_GET['orderby'] ) ? $_GET['orderby'] : '';
		$order = isset( $_GET['order'] ) ? $_GET['order'] : '';
		$search_term = isset( $_POST['s'] ) ? $_POST['s'] : '';

		$this->items = $this->WacR_list_table_data( $order_by, $order, $search_term );

		$WacR_columns = $this->get_columns();
		$WacR_hidden = $this->get_hidden_columns();
		$ldul_sortable = $this->get_sortable_columns();

		$this->_column_headers = [ $WacR_columns, $WacR_hidden, $ldul_sortable, ];

	}
	/**
	 * Wp list table bulk actions 
	 */
	public function get_bulk_actions() {

		return array(
			'WacR_delete'	=> esc_html( 'None', 'wacr' ),
		);
	}

	/**
	 * WP list table row actions
	 */
	public function handle_row_actions( $item, $column_name, $primary ) {

		if( $primary !== $column_name ) {
			return '';
		}

		$action = [];
		// $action['delete'] = '<a class="WacR-delete-post">'.esc_html( 'Delete', 'wacr' ).'</a>';
		return $this->row_actions( $action );
	}

	/**
	 * Display columns datas
	 */
	public function WacR_list_table_data( $order_by = '', $order = '', $search_term = '' ) {
		?>
		<h2><?php _e( 'Abandoned Carts ', 'wacr' ); ?></h2>
		<?php
		$data_array = [];
        global $wpdb;
        global $woocommerce;
        $show_reports = $wpdb->get_results(("SELECT id,wacr_customer_id,wacr_customer_first_name,wacr_customer_last_name,wacr_customer_mobile_no,wacr_cart_total,wacr_create_date_time,wacr_message_sent, wacr_status, wacr_cart_json  FROM ".$wpdb->prefix."wacr_adandoned_order_list ORDER BY id DESC"));

        
			foreach( $show_reports as $dkey => $dval ) {
                switch ($dval->wacr_status) {
                    case "0":
                     $status = "<p class='pending'>Pending</p>";
                      break;
                    case "1":
                      $status = "<p class='abandoned'>Abandoned</p>";
                      break;
                    case "2":
                    $status =  "<p class='recovered'>Recovered</p>";
                      break;
                    default:
                    esc_html_e('Unknown', 'wacr');
                  }  
				  
				if(!empty($dval->wacr_customer_first_name) || !empty($dval->wacr_customer_last_name)|| !empty($dval->wacr_customer_mobile_no)):
					$data_array[] = [
						'WacR_id'				=> $dval->id,
						'Wacr_Fname'				=> "$dval->wacr_customer_first_name $dval->wacr_customer_last_name",
						'WacR_Msg_count'		=> $dval->wacr_message_sent,
						'WacR_Mnum'			=> $dval->wacr_customer_mobile_no,
						'WacR_Total'		=> wc_price($dval->wacr_cart_total),
						'WacR_Status'		=> $status,
						'WacR_Delete'		=> "<button value='$dval->id' class='wacr-trigger-delete'><span class='fa fa-trash' aria-hidden='true'></span></button>",
						
					];
				endif;


			}
		

		?> <?php
	    return $data_array;

	}

	/**
	 * Gets a list of all, hidden and sortable columns
	 */
	public function get_hidden_columns() {
		return [];
	}

	/**
	 * Gets a list of columns.
	 */
	public function get_columns() {	

		$columns = array(
			'cb'				=> '<input type="checkbox" class="WacR-selected" />',
			'WacR_id'			=> esc_html( 'ID', 'wacr' ),
			'Wacr_Fname'			=> esc_html( 'Firstname', 'wacr' ),
			'WacR_Mnum'		=> esc_html( 'Mobile Number', 'wacr' ),
			'WacR_Msg_count'		=> esc_html( 'Sent', 'wacr' ),
			'WacR_Total'	=> esc_html( 'Total', 'wacr' ),
			'WacR_Status'	=> esc_html( 'Status', 'wacr' ),
			'WacR_Delete'	=> esc_html( 'Delete', 'wacr' ),
		);
		return $columns;
	}

	/**
	 * Return column value
	 */
	public function column_default( $item, $column_name ) {

		switch ($column_name) {
			case 'WacR_id':
			case 'Wacr_Fname':
			case 'WacR_Mnum':
			case 'WacR_Msg_count':
			case 'WacR_Status':
			case 'WacR_Total':
			case 'WacR_Delete':
			return $item[$column_name];
			default:
			return 'no list found';
		}
	}

	/**
	 * Rows check box
	 */
	public function column_cb( $items ) {

		$top_checkbox = '<input type="checkbox" class="WacR-selected" />';
		return $top_checkbox; 
	}
    // For back-end

    
}
echo "<div class='wrap'>";
$object = new WacR_list_Table();
$object->prepare_items();
$object->search_box('search', 'search_id');
$object->display();
echo "</div>";

?>
