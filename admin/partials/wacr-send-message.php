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

class Wacr_Shoot_Message
{
    public function wacr_send_message($wacr_mobile_no, $wacr_first_name, $wacr_array, $wacr_id, $wacr_template, $wacr_temp_name){

	$disableAbandoned = get_option('wacr_disable_abandoned_status');
	if($disableAbandoned == 'on'){
		return;
	}
	

	global $wpdb;
	$bearer_token = wp_unslash(get_option('wacr_bearer_token_whatsapp'));
	$mobile_number = wp_unslash(get_option('wacr_mobile_number_whatsapp'));
	require_once plugin_dir_path( __FILE__ ) . 'wacr-template-handle.php';
	$get_template_name = new Wacr_Templates();
	$get_template_name = $wacr_temp_name;		
	$array_result = $wpdb->get_results($wpdb->prepare("SELECT wacr_customer_id, wacr_customer_email, wacr_customer_first_name,wacr_customer_last_name,wacr_customer_mobile_no,wacr_cart_total,wacr_create_date_time,wacr_status, wacr_cart_total_json, wacr_cart_json ,wacr_message_sent FROM ".$wpdb->prefix."wacr_adandoned_order_list WHERE `id` = %s", $wacr_id));
	
	$recoverCartID = $wacr_id;
	$array = $wpdb->get_results($wpdb->prepare("SELECT wacr_template_id, wacr_head_param_count, wacr_body_param_count, wacr_head_params, wacr_body_params, wacr_head_text, wacr_body_text, wacr_button_info, wacr_other_params FROM ".$wpdb->prefix."wacr_templates WHERE wacr_template_name = %s", $get_template_name));

	
	$jsonData = stripslashes(html_entity_decode($array[0]->wacr_body_params));
	$body_params = json_decode($jsonData,true);


	$wacr_template_id = $array[0]->wacr_template_id;
	$wacr_head_param_count = $array[0]->wacr_head_param_count;
	$wacr_body_param_count = $array[0]->wacr_body_param_count;
	$wacr_head_params = $array[0]->wacr_head_params;
	$wacr_body_params = $array[0]->wacr_body_params;
	$wacr_head_text = $array[0]->wacr_head_text;
	$wacr_body_text = $array[0]->wacr_body_text;
	$wacr_button_info = $array[0]->wacr_button_info;
	$wacr_other_params = $array[0]->wacr_other_params;
	
	// customer details
	$customer_first_name = $array_result[0]->wacr_customer_first_name;
	$customer_last_name = $array_result[0]->wacr_customer_last_name;
	$customer_email = $array_result[0]->wacr_customer_email;
	$customer_phone = $array_result[0]->wacr_customer_mobile_no;
	$admin_email = wp_unslash(get_bloginfo('admin_email'));
	$customer_billing_address = $array_result[0]->wacr_customer_first_name;
	$customer_shipping_address = $array_result[0]->wacr_customer_first_name;
	$product_array = $array_result[0]->wacr_cart_total_json;
	$decoded_products = json_decode($product_array);
	foreach($decoded_products as $key => $value){
		$all_products[] = $value->product_name;

	}
	$cart_item_count = count($all_products);
	$wacr_array = implode(',', $all_products);
	

	$admin_phone = wp_unslash(get_bloginfo('admin_email'));
	$cart_items = $wacr_array;
	$cart_total = $array_result[0]->wacr_cart_total;

	//other variables
	$wacr_body_variables =  stripslashes(html_entity_decode(($wacr_body_params)));
	$wacrBodyVariables = json_decode($wacr_body_variables);
	$_cart_id = md5($wacr_id);
		
	$head_param_count = $array[0]->wacr_head_param_count;
	$body_param_count = $array[0]->wacr_body_param_count;
	$head_params = $array[0]->wacr_head_params;
	$body_params =  json_decode($array[0]->wacr_body_params);
	$button_param_count = $wacr_button_info;
	$mobile_number_id = wp_unslash(get_option('wacr_whatsapp_business_mobile_number_id'));
	$items = $wacr_array;
	$wacr_first_name_order = $wacr_first_name;


	

	if($wacr_head_param_count>0){

		$public_head_paramter_type = $wacr_head_params;
		if(isset($public_head_paramter_type)){
			switch ($public_head_paramter_type) {
				case "customer_first_name":
					$set_head_param = $customer_first_name;
				  break;
				case "customer_last_name":
					$set_head_param = $customer_last_name;
				  break;
				case "customer_email":
					$set_head_param = $customer_email;
				  break;
				case "admin_email":
					$set_head_param = $admin_email;
				  break;
				case "customer_phone":
					$set_head_param = $customer_phone;
				  break;
				case "customer_billing_address":
					$set_head_param = "not available";
				  break;
				case "customer_shipping_address":
					$set_head_param = "not available";
				  break;
				case "cart_item_count":
					$set_head_param = $cart_item_count;
				  break;
				case "admin_phone":
					$set_head_param = $wacr_mobile_no;
				  break;
				case "cart_items":
					$set_head_param = $items;
				  break;
				case "cart_total":
					  $set_head_param = $cart_total  . html_entity_decode(get_woocommerce_currency_symbol());
				  break;
				case "site_name":
					  $set_head_param = get_bloginfo( 'name' ); 
				  break;
				case "date":
					  $set_head_param = current_datetime()->format('Y-m-d H:i:s') ;
				  break;
				case "time":
					  $set_head_param = current_datetime()->format('Y-m-d H:i:s') ;
				  break;

				default:
				  $set_head_param = 'params';
			  }
		}
		$head_array = array (
			0 => 
			array (
			"type" => "text",
			"text" => "$set_head_param",
			),
		);
	}

	


	if($wacr_body_param_count>0){

		for($i=1; $i<=$wacr_body_param_count;$i++){
			$i2 = $i - 1;
			
			$public_paramter_type = $wacrBodyVariables[$i2];
			if(isset($public_paramter_type)){
				switch ($public_paramter_type) {
					case "customer_first_name":
						$set_body_param = $customer_first_name;
					  break;
					case "customer_last_name":
						$set_body_param = $customer_last_name;
					  break;
					case "customer_email":
						$set_body_param = $billing_email;
					  break;
					case "admin_email":
						$set_body_param = $wacr_admin_email;
					  break;
					case "customer_phone":
						$set_body_param = $billing_phone;
					  break;
					case "customer_billing_address":
						$set_body_param = $full_billing_address;
					  break;
					case "customer_shipping_address":
						$set_body_param = $full_shipping_address;
					  break;
					case "cart_item_count":
						$set_body_param = $cart_item_count;
					  break;
					case "admin_phone":
						$set_body_param = $wacr_mobile_no;
					  break;
					case "cart_items":
						$set_body_param = $items;
					  break;
					case "cart_total":
					  $set_body_param = $cart_total  . html_entity_decode(get_woocommerce_currency_symbol());
					  break;
					case "site_name":
					  $set_body_param = get_bloginfo( 'name' ); 
					  break;
					case "date":
					  $set_body_param = date("Y/m/d") ;
					  break;
					case "time":
					  $set_body_param = current_datetime()->format('Y-m-d H:i:s') ;
					  break;

					default:
					  $set_body_param = 'params';
				  }
			}
			
			$body_array[] = array (
					"type" => "text",
					"text" => "$set_body_param",
				);
		}
		
	}	

	//check limit
	$dailyDbCount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM ".$wpdb->prefix."wacr_message_logs WHERE `wacr_msg_status` = 'sent' AND date(wacr_updated_date_time) = CURDATE() ORDER BY wacr_updated_date_time DESC"));
	$userSetCount = get_option('wacr_daily_message_limit_whatsapp');
	
	if($dailyDbCount>$userSetCount){
		//insert message log
		$table_for_logs = $wpdb->prefix.'wacr_message_logs';
		$wpdb->insert($table_for_logs, array(
			'wacr_msg_type' => "limit_exceeded",
			'wacr_msg_status' => "limit",
			'wacr_template' => $get_template_name,
			'wacr_orderdetails' => $order_id,
			));
		return;
	}
	require_once plugin_dir_path(__FILE__ ) . 'wacr-api-functions.php';
	$response = new Wacr_API_functions();
	$language = $response->get_template_language($get_template_name);
	$curl = curl_init();
	if($button_param_count>0)
	{
		$json_array = array (
			"messaging_product" => "whatsapp","to" => "$wacr_mobile_no","type" => "template","template" => 
			array ("name" => "$get_template_name","language" => 
			array (
				"code" => $language,
			),
			"components" => 
			array (
				0 => 
				array (
				"type" => "header",
				"parameters" => $head_array,
				),
				1 => 
				array (
				"type" => "body",
				"parameters" => $body_array,
				),
				2 => 
				array (
				"type" => "button",
				"index"=> '0',
				"sub_type" => "url",
				"parameters" => array (
						0 => 
						array (
						"type" => "text",
						"text" => "?cartID=$recoverCartID",
						),
					)
				),
			),
			),
		);
		}else{
			$json_array = array (
				"messaging_product" => "whatsapp","to" => "$wacr_mobile_no","type" => "template","template" => 
				array ("name" => "$get_template_name","language" => 
				array (
					"code" => $language,
				),
				"components" => 
				array (
					0 => 
					array (
					"type" => "header",
					"parameters" => $head_array,
					),
					1 => 
					array (
					"type" => "body",
					"parameters" => $body_array,
					)
				),
				),
			);
		}

		$json_request_array = json_encode($json_array);
	
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://graph.facebook.com/v14.0/$mobile_number_id/messages",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => $json_request_array,
		  
		  CURLOPT_HTTPHEADER => array(
			"Authorization: Bearer $bearer_token",
			'Content-Type: application/json'
		  ),
		));
		
		$response = curl_exec($curl);
		
		curl_close($curl);

		$response_decode = get_object_vars(json_decode($response));
	
		if(!isset($response_decode['error'])){
			//insert message log
			$table_for_logs = $wpdb->prefix.'wacr_message_logs';
			$wpdb->insert($table_for_logs, array(
                'wacr_msg_type' => "order_abandoned",
                'wacr_msg_status' => "sent",
                'wacr_template' => $get_template_name,
                'wacr_orderdetails' => "Reminded Successfully",
                ));
		}else{
			if(isset($response_decode['error'])){
				//insert message log
				$table_for_logs = $wpdb->prefix.'wacr_message_logs';
				$wpdb->insert($table_for_logs, array(
					'wacr_msg_type' => "order_abandoned",
					'wacr_msg_status' => "error",
					'wacr_template' => $get_template_name,
					'wacr_orderdetails' => $response,
					));
			}
		}


	return $response;
    }
	
}

