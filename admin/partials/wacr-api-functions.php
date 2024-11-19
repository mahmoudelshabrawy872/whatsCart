<?php


class Wacr_API_functions {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function wacr_get_wp_templates()
    {
    $whatsapp_business_id = get_option('wacr_whatsapp_business_id');
	$bearer_token = get_option('wacr_bearer_token_whatsapp');

		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://graph.facebook.com/v15.0/$whatsapp_business_id/message_templates?limit=9999",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
			"Authorization: Bearer $bearer_token"
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$templateFetched = json_decode($response);
		if(!empty($templateFetched->data)){
			update_option('wacr_whatsapp_connection_status', "connected");
		}else{
			update_option('wacr_whatsapp_connection_status', "disconnected");
		}
		
		return($response);
		wp_die();

    }

	public function get_template_language($args){
		$tempx = $args;
		$response = new Wacr_API_functions();
        $response = $response->wacr_get_wp_templates();
		$res_arr = json_decode($response);
                    foreach($res_arr->data as $dkey => $dval )
                    {
						$temp = $dval->name;
						if($temp == $tempx){
							$language = $dval->language;
						}
					}
		return $language;
	}

}




	




?>
