<?php

/**
 * Create mandatory templates
 *
 * @link       http://www.techspawn.com
 * @since      1.2.10
 *
 * @package    Wacr
 * @subpackage Wacr/admin
 */


class Wacr_BookUpp
{   
   
    public function wacr_send_bookup_verify($bookingID){
	global $wpdb;
	$whatsapp_business_id = get_option('wacr_whatsapp_business_id');
	$bearer_token = get_option('wacr_bearer_token_whatsapp');
	$templateName = get_option('wacr_booking_verify_temp');
	$mobile_number_id = wp_unslash(get_option('wacr_whatsapp_business_mobile_number_id'));
	
	//get template language
		require_once plugin_dir_path(__DIR__ ) . '/partials/wacr-api-functions.php';
		$response = new Wacr_API_functions();
		$language = $response->get_template_language($templateName);
		
		$postMeta = get_post_meta($bookingID);
		$bookingFname = sanitize_text_field($postMeta['customer_fname'][0]);
		$bookingLname = sanitize_text_field($postMeta['customer_lname'][0]);
		$bookingMail = sanitize_text_field($postMeta['customer_email'][0]);
		$bookingFor = sanitize_text_field($postMeta['booking_for'][0]);
		$bookingTime = sanitize_text_field($postMeta['appointment_slot'][0]);
		$bookingDate = sanitize_text_field($postMeta['appointment_date'][0]);
		$bookingStatus = sanitize_text_field($postMeta['booking_status'][0]);
		$bookingMobileNumber = $postMeta["customer_phone"][0];
		$supportMail = get_bloginfo('admin_email');

	//get date and time
	$dateNtime = "$bookingDate, Time Slot: $bookingTime";

	//create a order confirmation link
	$bID_hash = md5($bookingID);
	$randomNum = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(15/strlen($x)) )),1,15);
	$table_name = $wpdb->prefix . 'wacr_cod_order_confirm'; 
	$wpdb->insert($table_name, array(
		'wacr_random_number' => $randomNum,
		'wacr_order_number' => $bookingID,		
		'wacr_col2' => $wacr_col2,
	));
	$site_URL =  home_url();
	$confirm_link =  "$site_URL/?confirmBooking=$bID_hash&id=$randomNum";

	//send message mechanism
	$curl = curl_init();
		$json_array = array (
			"messaging_product" => "whatsapp","to" => $bookingMobileNumber,"type" => "template","template" => 
			array ("name" => "$templateName","language" => 
			array (
				"code" => "$language",
			),
			"components" => 
			array (
				0 => 
				array (
				"type" => "body",
				"parameters" => array (
						0 => array (
							"type" => "text",
							"text" => "$bookingFname $bookingLname"), //bookingID
						1 => array (
							"type" => "text",
							"text" => "$dateNtime"),
						2 => array (
							"type" => "text",
							"text" => "$bookingFor"),
						3 => array (
							"type" => "text",
							"text" => "$bookingID"),
						4 => array (
							"type" => "text",
							"text" => "$confirm_link"),
						5 => array (
							"type" => "text",
							"text" => "$supportMail"),
				),
				)
			),
			),
		);
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
		return json_encode($response);


    }

    public function wacr_send_bookup_notify($bookingID){
        
        $whatsapp_business_id = get_option('wacr_whatsapp_business_id');
        $bearer_token = get_option('wacr_bearer_token_whatsapp');
        $templateName = get_option('wacr_booking_notify_temp');
		$mobile_number_id = wp_unslash(get_option('wacr_whatsapp_business_mobile_number_id'));
        
        //get template language
            require_once plugin_dir_path(__DIR__ ) . '/partials/wacr-api-functions.php';
			$response = new Wacr_API_functions();
			$language = $response->get_template_language($templateName);
            
            $postMeta = get_post_meta($bookingID);
			$bookingFname = sanitize_text_field($postMeta['customer_fname'][0]);
			$bookingLname = sanitize_text_field($postMeta['customer_lname'][0]);
			$bookingMail = sanitize_text_field($postMeta['customer_email'][0]);
			$bookingFor = sanitize_text_field($postMeta['booking_for'][0]);
			$bookingTime = sanitize_text_field($postMeta['appointment_slot'][0]);
			$bookingDate = sanitize_text_field($postMeta['appointment_date'][0]);
			$bookingStatus = sanitize_text_field($postMeta['booking_status'][0]);
            $bookingMobileNumber = $postMeta["customer_phone"][0];
            $supportMail = get_bloginfo('admin_email');

		//get date and time


		$dateNtime = "$bookingDate, Time Slot: $bookingTime";
        //send message mechanism
        $curl = curl_init();
			$json_array = array (
				"messaging_product" => "whatsapp","to" => $bookingMobileNumber,"type" => "template","template" => 
				array ("name" => "$templateName","language" => 
				array (
					"code" => "$language",
				),
				"components" => 
				array (
					0 => 
					array (
					"type" => "body",
					"parameters" => array (
							0 => array (
								"type" => "text",
								"text" => "$bookingFname $bookingLname"), //bookingID
                            1 => array (
								"type" => "text",
								"text" => "$dateNtime"),
							2 => array (
								"type" => "text",
								"text" => "$bookingFor"),
							3 => array (
								"type" => "text",
								"text" => "$bookingID"),
							4 => array (
								"type" => "text",
								"text" => "$supportMail"),
					),
					)
				),
				),
			);
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
			return json_encode($response);


    }
   
} 

new Wacr_BookUpp();
