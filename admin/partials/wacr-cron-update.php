<?php

/**
 * Cron update product stock and bulk update all products
 *
 * @link       http://www.techspawn.com
 * @since      1.2.10
 *
 * @package    Wacr
 * @subpackage Wacr/admin
 */

class Wacr_Cron_Update
{

    public function __construct()
    {    
      
        add_filter('cron_schedules', [$this, 'wacr_send_first_message']);
        
            // Schedule an action if it's not already scheduled
            if (!wp_next_scheduled('wacr_send_first_message')) {
                wp_schedule_event(time(), 'wacr_send_first_message', 'wacr_send_first_message');
            }
            if (!wp_next_scheduled('wacr_send_message_1')) {
                wp_schedule_event(time(), 'wacr_send_message_1', 'wacr_send_message_1');
            }
            if (!wp_next_scheduled('wacr_clear_junk_data')) {
                wp_schedule_event(time(), 'wacr_clear_junk_data', 'wacr_clear_junk_data');
            }
            if (!wp_next_scheduled('wacr_sync_session_db')) {
                wp_schedule_event(time(), 'wacr_sync_session_db', 'wacr_sync_session_db');
            }
           

        // Hook into that action that'll fire
        add_action('wacr_send_first_message', [$this, 'wacr_send_first_message_cb']);
        add_action('wacr_send_message_1', [$this, 'wacr_send_message_1_cb']);
        add_action('wacr_clear_junk_data', [$this, 'wacr_clear_junk_data_cb']);
        add_action('wacr_sync_session_db', [$this, 'wacr_sync_session_db_cb']);
    }



    function wacr_send_first_message($schedules)
    {   
        $default_cron_time = get_option('wacr_default_cron_time');
        if (!empty($default_cron_time)) {
            $schedules['wacr_send_first_message'] = array(
                'interval'  => $default_cron_time * 60,
                'display'   => esc_html("Every $default_cron_time Minutes", 'Wacr')
            );
        }
        $default_first_time = $default_cron_time;
        if (!empty($default_first_time) || $default_first_time == '0') {
            $schedules['wacr_send_message_1'] = array(
                'interval'  => $default_first_time * 60,
                'display'   => esc_html("Every $default_first_time Minutes", 'Wacr')
            );
        }
            $schedules['wacr_clear_junk_data'] = array(
                'interval'  =>  24 * 60 * 60,
                'display'   => esc_html("Everyday", 'Wacr')
            );
            $schedules['wacr_sync_session_db'] = array(
                'interval'  =>  23 * 60 * 60,
                'display'   => esc_html("Everyday", 'Wacr')
            );

        return $schedules;
    }

    public function wacr_send_message_1_cb()
    {
        global $wpdb;
        global $woocommerce;
        require_once plugin_dir_path( __FILE__ ) . '/wacr-send-message.php';
        $show_all_data = $wpdb->get_results($wpdb->prepare("SELECT id,wacr_customer_id,wacr_customer_first_name,wacr_customer_last_name,wacr_customer_mobile_no,wacr_cart_total,wacr_create_date_time,wacr_status, wacr_abandoned_date_time, wacr_cart_total_json, wacr_cart_json ,wacr_message_sent  FROM ".$wpdb->prefix."wacr_adandoned_order_list ORDER BY id DESC"));
        foreach($show_all_data as $key => $value){
            $wacr_customer_id = $value->wacr_customer_id;
            $wacr_customer_f_name = $value->wacr_customer_first_name;
            $wacr_status = $value->wacr_status;
            $wacr_id = $value->id;
            $wacr_message_sent = $value->wacr_message_sent;
            $wacr_mobile_no = $value->wacr_customer_mobile_no;
            $lastUpdatedTime = $value->wacr_abandoned_date_time;
            $product_array = json_decode($value->wacr_cart_total_json);
                        if(is_array($product_array)){
                            foreach($product_array as $k => $v){
                            $wacr_product_name_array[] = $v->product_name;	
                        }
                        }            
            //check dnd -init
            $dnd_status = $this->wacr_dnd_status();
            $dailyDbCount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM ".$wpdb->prefix."wacr_message_logs WHERE `wacr_msg_status` = 'sent' AND date(wacr_updated_date_time) = CURDATE() ORDER BY wacr_updated_date_time DESC"));
            $userSetCount = get_option('wacr_daily_message_limit_whatsapp');
            
            if($dnd_status == "true"){
                return;
            }


            $current_time = current_time('mysql');
            $difference_time = (strtotime($current_time) - strtotime($lastUpdatedTime)) / 60;

            //dynamic crons
            
            $DBorder_id = $wpdb->get_results($wpdb->prepare("SELECT id, wacr_trigger_template, wacr_trigger_time FROM ".$wpdb->prefix."wacr_dynamic_triggers WHERE 1"));	
            foreach($DBorder_id as $key => $values){
                $dynamic_ID = $values->id;
                $dynamic_TMP = $values->wacr_trigger_template;
                $dynamic_TIME = $values->wacr_trigger_time;
                $count = $key + 1;
                if($wacr_status == '1' && $wacr_message_sent == $key ){
                    if($dynamic_TIME !=''){ 
                        if($difference_time>$dynamic_TIME){ //
                            if($dailyDbCount>$userSetCount){
                            //insert message log
                            $table_for_logs = $wpdb->prefix.'wacr_message_logs';
                            $wpdb->insert($table_for_logs, array(
                            'wacr_msg_type' => "limit_exceeded",
                            'wacr_msg_status' => "limit",
                            'wacr_template' => 'N/A',
                            'wacr_orderdetails' => 'abandoned',
                            ));
                            return;
                            }    
                        $response = new Wacr_Shoot_Message();
                        $response = $response->wacr_send_message($wacr_mobile_no,$wacr_customer_f_name,$wacr_product_name_array,$wacr_id,$count, $dynamic_TMP);
                            $response_decode = get_object_vars(json_decode($response));
							echo $response;
                                if(!isset($response_decode['error'])){
                                    $wpdb->update($wpdb->prefix.'wacr_adandoned_order_list', array('wacr_message_sent' => "$count", 'wacr_abandoned_date_time' => current_time('mysql', false)), array('wacr_customer_id' => $wacr_customer_id)); 
                                }	
                            }
                    }
                }
        
            }




        }
        
    }


    public function wacr_sync_session_db_cb()
    {
        global $wpdb;
        $wpdb->query($wpdb->prepare("INSERT into " . $wpdb->prefix . "wacr_sessions SELECT * FROM " . $wpdb->prefix . "woocommerce_sessions ON DUPLICATE KEY UPDATE " . $wpdb->prefix . "wacr_sessions.session_id = " . $wpdb->prefix . "woocommerce_sessions.session_id"));

    }

    
    public function wacr_clear_junk_data_cb()
    {
        global $wpdb;
        $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "wacr_adandoned_order_list WHERE datediff(now(), wacr_last_access_time) > 45 AND wacr_status IN (0,1,2)"));
    }

    public function wacr_send_first_message_cb()
    {
    
    global $wpdb;
	global $woocommerce;
	$current_time = current_time('mysql', false);
	$table = $wpdb->prefix . 'wacr_adandoned_order_list';
	$table1 = $wpdb->prefix . "posts";
	$woo_sessions = $wpdb->prefix . 'woocommerce_sessions';
	$all = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."woocommerce_sessions");
	$test = $wpdb->get_results($all);
	foreach ($test as $row) {
		$session_id = $row->session_key;
        $session_key = $row->session_id;
		$session_content = unserialize($row->session_value);
        $cart = unserialize($session_content['cart']);
        $cart_totals = unserialize($session_content['cart_totals']);
        $last_access_time = $session_content['wacr_last_access_time'];
        $customer = unserialize($session_content['customer']);
        $cart_id_array = json_decode(json_encode($cart), true);
	

		$customer_first_name = '';
		$customer_last_name = '';
		$customer_email = '';
		$customer_mobile_no = '';
		$customer_country = '';
		$cart_total = '';
		$session = new WC_Session_Handler();
		$session_data = $session->get_session_data();
		$customer = maybe_unserialize( $session_data['customer'] );
		$get_time_difference = (strtotime($current_time) - strtotime($last_access_time)) / 60;
		$cron_time = get_option("wacr_time_interval_for_first");
	
			if($get_time_difference>$cron_time){
				$get_sql = $wpdb->prepare("SELECT COUNT(id) FROM $table WHERE wacr_customer_id = %s AND wacr_status IN (0,1)", $session_id);
				$result_count = $wpdb->get_var($get_sql);
				
		
					$prepare_array = array(
						'wacr_message_api_response' => "$session_key",
						'wacr_status' => 1
					   );
					
				if ($result_count > 0) {
					$wpdb->update($table, $prepare_array, array('wacr_customer_id' => $session_id)); 
		
		
				 } else {
					
				 }
			
		}
		

	}
      
    }


    public function wacr_dnd_status(){
        
        $dnd_status = get_option('wacr_enable_cooldown');

        if($dnd_status == "on"){
            $from_time = get_option('wacr_enable_cooldown_from');
            $to_time = get_option('wacr_enable_cooldown_to');
    
            $type = 'mysql';
                $gmt = false;
                if ('mysql' === $type) {
                    $type = 'H:i:s';
                }
                $timezone = $gmt ? new DateTimeZone('UTC') : wp_timezone();
                $datetime = new DateTime('now', $timezone);
                $current_time = $datetime->format($type);
                echo "$current_time => $from_time => $to_time";
                if ((strtotime($current_time) > strtotime($from_time)) && (strtotime($current_time) < strtotime($to_time))) {
                   
                    return "true";
                } else {
    
                    return "false";
                }
        }else{
            return "false";
        }
    }


}

new Wacr_Cron_Update();
