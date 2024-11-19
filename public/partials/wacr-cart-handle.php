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

class Wacr_Cart_Handle
{
    
    public function get_cart_details(){
        global $woocommerce;
        global $wpdb;
        
        if (!WC()->cart) { 
            return;
        }
        
        $cart_total = WC()->cart->total;
        $cart_currency = get_woocommerce_currency();
        $current_time = current_time('mysql', false);
        $session_id = WC()->session->get_customer_id();
        $total_products = WC()->cart->get_cart_contents();
        $product_array = array();

        foreach ($total_products as $product => $values) {
            $item = wc_get_product($values['data']->get_id());

            $product_title = $item->get_title();
            $product_quantity = $values['quantity'];
            $product_variation_price = '';
            $product_tax = '';

            if (isset($values['line_total'])) {
                $product_variation_price = $values['line_total'];
            }
            if (isset($values['line_tax'])) { 
                $product_tax = $values['line_tax'];
            }
            if ($values['variation_id']) { 
                $single_variation = new WC_Product_Variation($values['variation_id']);
                $product_variation_id = $values['variation_id'];
            } else {
                $product_attributes = false;
                $product_variation_id = '';
            }

            $product_array[] = array(
            'product_title' => $product_title . $product_attributes,
            'quantity' => $product_quantity,
            'product_id' => $values['product_id'],
            'product_variation_id' => $product_variation_id,
            'product_variation_price' => $product_variation_price,
            'product_tax' => $product_tax
            );
        }
		
       
        $results_array = array(
        'cart_total'     => $cart_total,
        'cart_currency' => $cart_currency,
        'current_time'     => $current_time,
        'session_id'     => $session_id,
        'product_array' => $product_array
        );

        return $results_array;
    }


    public function get_user_details(){
        global $wpdb;
        global $woocommerce;

       
        $user_data = array();

        if (is_user_logged_in()) { 
			$current_user = wp_get_current_user();
		}

		$wacr_user_mail = WC()->session->get('wacr_user_mail');
		if(empty($wacr_user_mail)){
			(isset($current_user->billing_email)) ? $wacr_user_mail = $current_user->billing_email : $wacr_user_mail = $current_user->user_email;
		}

		$wacr_user_phone = WC()->session->get('wacr_user_phone');
        if(isset($wacr_user_phone)){
		$countNumber = strlen($wacr_user_phone);}
		if(empty($wacr_user_phone) || strlen($wacr_user_phone) < 8){
			(isset($current_user->billing_phone)) ? $wacr_user_phone = $current_user->billing_phone : $wacr_user_phone = '';
		}
		
	
		$wacr_user_first_name = WC()->session->get('wacr_user_first_name');
		if(empty($wacr_user_first_name)){
			(isset($current_user->billing_first_name)) ? $wacr_user_first_name = $current_user->billing_first_name : $wacr_user_first_name = $current_user->user_firstname;
		}

		$wacr_user_last_name = WC()->session->get('wacr_user_last_name');
		if(empty($wacr_user_last_name)){
			(isset($current_user->billing_last_name)) ? $wacr_user_last_name = $current_user->billing_last_name : $wacr_user_last_name = $current_user->user_lastname;
		}
		

		$wacr_country = WC()->session->get('wacr_country');
		if(empty($wacr_country)){
			(isset($current_user->billing_country)) ? $wacr_country = $current_user->billing_country : $wacr_country = '';
		}

        $wacr_user_phone = str_replace( array( '\'', '"',
		',' , ';', '<', '>', '+', '-', '@' ), '', $wacr_user_phone);
        $wacrNumber = $this->get_formatted_mobile( $wacr_user_phone, $wacr_country );
       
		$user_data = array(
			'wacr_user_mail'    => $wacr_user_mail,
			'wacr_user_phone'        => $wacrNumber,
			'wacr_user_first_name'            => $wacr_user_first_name,
			'wacr_user_last_name'            => $wacr_user_last_name,
			'wacr_wacr_country'        => $wacr_country,
			);

        return $user_data;
    }



    public function get_formatted_mobile($mobileNumber, $country){
        
        switch($country){
            case "DE":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 49, $mobileNumber );
                return $mobileNumber;
                break;
            case "GG":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 44, $mobileNumber );
                return $mobileNumber;
                break;
            case "VA":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 39, $mobileNumber );
                return $mobileNumber;
                break;
            case "HU":
                $mobileNumber = $this->wacr_get_valid_mobile( 8, 36, $mobileNumber );
                return $mobileNumber;
                break;
            case "IS":  
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 354, $mobileNumber );
                return $mobileNumber;
                break;
            case "IE":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 353, $mobileNumber );
                return $mobileNumber;
                break;
            case "IM":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 44, $mobileNumber );
                return $mobileNumber;
                break;
            case "IT":
                $mobileNumber = $this->wacr_get_valid_mobile( 13, 39, $mobileNumber );
                return $mobileNumber;
                break;
            case "JE":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 44, $mobileNumber );
                return $mobileNumber;
                break;
            case "LV":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 371, $mobileNumber );
                return $mobileNumber;
                break;
            case "LI":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 423, $mobileNumber );
                return $mobileNumber;
                break;
            case "LT":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 370, $mobileNumber );
                return $mobileNumber;
                break;
            case "LU":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 352, $mobileNumber );
                return $mobileNumber;
                break;
            case "MK":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 389, $mobileNumber );
                return $mobileNumber;
                break;
            case "MT":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 356, $mobileNumber );
                return $mobileNumber;
                break;
            case "MD":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 373, $mobileNumber );
                return $mobileNumber;
                break;
            case "MC":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 377, $mobileNumber );
                return $mobileNumber;
                break;
            case "ME":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 382, $mobileNumber );
                return $mobileNumber;
                break;
            case "NL":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 31, $mobileNumber );
                return $mobileNumber;
                break;
            case "NO":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 47, $mobileNumber );
                return $mobileNumber;
                break;
            case "RO":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 40, $mobileNumber );
                return $mobileNumber;
                break;
            case "RU":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 7, $mobileNumber );
                return $mobileNumber;
                break;
            case "RS":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 381, $mobileNumber );
                return $mobileNumber;
                break;
            case "SK":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 421, $mobileNumber );
                return $mobileNumber;
                break;
            case "SI":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 386, $mobileNumber );
                return $mobileNumber;
                break;
            case "ES":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 34, $mobileNumber );
                return $mobileNumber;
                break;
            case "SJ":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 47, $mobileNumber );
                return $mobileNumber;
                break;
            case "SE":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 46, $mobileNumber );
                return $mobileNumber;
                break;
            case "CH":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 41, $mobileNumber );
                return $mobileNumber;
                break;
            case "UA":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 380, $mobileNumber );
                return $mobileNumber;
                break;
            case "GB":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 44, $mobileNumber );
                return $mobileNumber;
                break;
            case "GI":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 350, $mobileNumber );
                return $mobileNumber;
                break;
            case "GR":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 30, $mobileNumber );
                return $mobileNumber;
                break;
            case "AX":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 358, $mobileNumber );
                return $mobileNumber;
                break;
            case "AL":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 355, $mobileNumber );
                return $mobileNumber;
                break;
            case "AD":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 376, $mobileNumber );
                return $mobileNumber;
                break;
            case "AT":
                $mobileNumber = $this->wacr_get_valid_mobile( 11, 43, $mobileNumber );
                return $mobileNumber;
                break;
            case "BA":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 387, $mobileNumber );
                return $mobileNumber;
                break;
            case "BY":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 375, $mobileNumber );
                return $mobileNumber;
                break;
            case "BE":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 32, $mobileNumber );
                return $mobileNumber;
                break;
            case "BG":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 359, $mobileNumber );
                return $mobileNumber;
                break;
            case "CZ":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 420, $mobileNumber );
                return $mobileNumber;
                break;
            case "DK":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 45, $mobileNumber );
                return $mobileNumber;
                break;
            case "EE":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 372, $mobileNumber );
                return $mobileNumber;
                break;
            case "HR":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 385, $mobileNumber );
                return $mobileNumber;
                break;
            case "FI":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 358, $mobileNumber );
                return $mobileNumber;
                break;
            case "FR":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 33, $mobileNumber );
                return $mobileNumber;
                break;
            case "FO":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 298, $mobileNumber );
                return $mobileNumber;
                break;
            case "PL":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 48, $mobileNumber );
                return $mobileNumber;
                break;
            case "PT":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 351, $mobileNumber );
                return $mobileNumber;
                break;
            case "SM":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 378, $mobileNumber );
                return $mobileNumber;
                break;
            case "GH":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 233, $mobileNumber );
                return $mobileNumber;
                break;
            case "DZ":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 213, $mobileNumber );
                return $mobileNumber;
                break;
            case "AO":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 244, $mobileNumber );
                return $mobileNumber;
                break;
            case "BJ":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 229, $mobileNumber );
                return $mobileNumber;
                break;
            case "BW":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 267, $mobileNumber );
                return $mobileNumber;
                break;
            case "BF":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 226, $mobileNumber );
                return $mobileNumber;
                break;
            case "BI":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 257, $mobileNumber );
                return $mobileNumber;
                break;
            case "CM":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 237, $mobileNumber );
                return $mobileNumber;
                break;
            case "CV":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 238, $mobileNumber );
                return $mobileNumber;
                break;
            case "CF":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 236, $mobileNumber );
                return $mobileNumber;
                break;
            case "TD":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 235, $mobileNumber );
                return $mobileNumber;
                break;
            case "KM":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 269, $mobileNumber );
                return $mobileNumber;
                break;
            case "CD":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 243, $mobileNumber );
                return $mobileNumber;
                break;
            case "CG":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 242, $mobileNumber );
                return $mobileNumber;
                break;
            case "CI":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 225, $mobileNumber );
                return $mobileNumber;
                break;
            case "DJ":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 253, $mobileNumber );
                return $mobileNumber;
                break;
            case "EG":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 20, $mobileNumber );
                return $mobileNumber;
                break;
            case "GQ":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 240, $mobileNumber );
                return $mobileNumber;
                break;
            case "ER":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 291, $mobileNumber );
                return $mobileNumber;
                break;
            case "ET":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 251, $mobileNumber );
                return $mobileNumber;
                break;
            case "GA":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 63, $mobileNumber );
                return $mobileNumber;
                break;
            case "GM":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 220, $mobileNumber );
                return $mobileNumber;
                break;
            case "GN":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 224, $mobileNumber );
                return $mobileNumber;
                break;
            case "GW":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 245, $mobileNumber );
                return $mobileNumber;
                break;
            case "KE":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 254, $mobileNumber );
                return $mobileNumber;
                break;
            case "LS":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 266, $mobileNumber );
                return $mobileNumber;
                break;
            case "LR":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 231, $mobileNumber );
                return $mobileNumber;
                break;
            case "LY":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 218, $mobileNumber );
                return $mobileNumber;
                break;
            case "MG":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 261, $mobileNumber );
                return $mobileNumber;
                break;
            case "MW":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 265, $mobileNumber );
                return $mobileNumber;
                break;
            case "ML":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 223, $mobileNumber );
                return $mobileNumber;
                break;
            case "MR":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 222, $mobileNumber );
                return $mobileNumber;
                break;
            case "MU":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 230, $mobileNumber );
                return $mobileNumber;
                break;
            case "YT":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 262, $mobileNumber );
                return $mobileNumber;
                break;
            case "MA":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 212, $mobileNumber );
                return $mobileNumber;
                break;
            case "MZ":
                $mobileNumber = $this->wacr_get_valid_mobile( 12, 258, $mobileNumber );
                return $mobileNumber;
                break;
            case "NA":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 264, $mobileNumber );
                return $mobileNumber;
                break;
            case "NE":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 227, $mobileNumber );
                return $mobileNumber;
                break;
            case "NG":
                $mobileNumber = $this->wacr_get_valid_mobile( 8, 234, $mobileNumber );
                return $mobileNumber;
                break;
            case "RE":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 262, $mobileNumber );
                return $mobileNumber;
                break;
            case "RW":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 250, $mobileNumber );
                return $mobileNumber;
                break;
            case "SH":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 290, $mobileNumber );
                return $mobileNumber;
                break;
            case "ST":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 239, $mobileNumber );
                return $mobileNumber;
                break;
            case "SN":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 221, $mobileNumber );
                return $mobileNumber;
                break;
            case "SC":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 248, $mobileNumber );
                return $mobileNumber;
                break;
            case "SL":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 232, $mobileNumber );
                return $mobileNumber;
                break;
            case "SO":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 252, $mobileNumber );
                return $mobileNumber;
                break;
            case "ZA":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 27, $mobileNumber );
                return $mobileNumber;
                break;       
            case "SD":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 249, $mobileNumber );
                return $mobileNumber;
                break;
            case "SZ":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 268, $mobileNumber );
                return $mobileNumber;
                break;
            case "TZ":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 255, $mobileNumber );
                return $mobileNumber;
                break;
            case "TG":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 228, $mobileNumber );
                return $mobileNumber;
                break;
            case "TN":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 216, $mobileNumber );
                return $mobileNumber;
                break;
            case "UG":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 256, $mobileNumber );
                return $mobileNumber;
                break;
            case "ZM":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 260, $mobileNumber );
                return $mobileNumber;
                break;
            case "ZW":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 263, $mobileNumber );
                return $mobileNumber;
                break;
            case "AF":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 93, $mobileNumber );
                return $mobileNumber;
                break;
            case "AM":
                $mobileNumber = $this->wacr_get_valid_mobile( 8, 374, $mobileNumber );
                return $mobileNumber;
                break;
            case "AZ":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 994, $mobileNumber );
                return $mobileNumber;
                break;
            case "BH":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 973, $mobileNumber );
                return $mobileNumber;
                break;
            case "BD":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 880, $mobileNumber );
                return $mobileNumber;
                break;
            case "BT":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 975, $mobileNumber );
                return $mobileNumber;
                break;
            case "IO":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 246, $mobileNumber );
                return $mobileNumber;
                break;
            case "BN":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 673, $mobileNumber );
                return $mobileNumber;
                break;
            case "KH":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 855, $mobileNumber );
                return $mobileNumber;
                break;
            case "CN":
                $mobileNumber = $this->wacr_get_valid_mobile( 11, 86, $mobileNumber );
                return $mobileNumber;
                break;
            case "CX":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 61, $mobileNumber );
                return $mobileNumber;
                break;
            case "CC":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 61, $mobileNumber );
                return $mobileNumber;
                break;
            case "CY":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 357, $mobileNumber );
                return $mobileNumber;
                break;
            case "GE":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 995, $mobileNumber );
                return $mobileNumber;
                break;
            case "HK":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 852, $mobileNumber );
                return $mobileNumber;
                break;
            case "IN":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 91, $mobileNumber );
                return $mobileNumber;
                break;
            case "ID":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 62, $mobileNumber );
                return $mobileNumber;
                break;
            case "IR":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 98, $mobileNumber );
                return $mobileNumber;
                break;
            case "IQ":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 964, $mobileNumber );
                return $mobileNumber;
                break;
            case "IL":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 972, $mobileNumber );
                return $mobileNumber;
                break;
            case "JP":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 81, $mobileNumber );
                return $mobileNumber;
                break;
            case "JO":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 962, $mobileNumber );
                return $mobileNumber;
                break;
            case "KZ":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 77, $mobileNumber );
                return $mobileNumber;
                break;
            case "KP":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 850, $mobileNumber );
                return $mobileNumber;
                break;
            case "KR":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 82, $mobileNumber );
                return $mobileNumber;
                break;
            case "KW":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 965, $mobileNumber );
                return $mobileNumber;
                break;
            case "KG":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 996, $mobileNumber );
                return $mobileNumber;
                break;
            case "LA":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 856, $mobileNumber );
                return $mobileNumber;
                break;
            case "LB":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 961, $mobileNumber );
                return $mobileNumber;
                break;
            case "MO":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 853, $mobileNumber );
                return $mobileNumber;
                break;
            case "MY":
                $mobileNumber = $this->wacr_get_valid_mobile( 7, 60, $mobileNumber );
                return $mobileNumber;
                break;
            case "MV":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 960, $mobileNumber );
                return $mobileNumber;
                break;
            case "MN":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 976, $mobileNumber );
                return $mobileNumber;
                break;
            case "MM":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 95, $mobileNumber );
                return $mobileNumber;
                break;
            case "NP":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 977, $mobileNumber );
                return $mobileNumber;
                break;
            case "OM":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 968, $mobileNumber );
                return $mobileNumber;
                break;
            case "PK":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 92, $mobileNumber );
                return $mobileNumber;
                break;
            case "PS":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 970, $mobileNumber );
                return $mobileNumber;
                break;
            case "PH":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 63, $mobileNumber );
                return $mobileNumber;
                break;
            case "QA":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 974, $mobileNumber );
                return $mobileNumber;
                break;
            case "SA":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 966, $mobileNumber );
                return $mobileNumber;
                break;
            case "SG":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 65, $mobileNumber );
                return $mobileNumber;
                break;
            case "LK":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 94, $mobileNumber );
                return $mobileNumber;
                break;
            case "SY":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 963, $mobileNumber );
                return $mobileNumber;
                break;
            case "TW":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 886, $mobileNumber );
                return $mobileNumber;
                break;
            case "TJ":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 992, $mobileNumber );
                return $mobileNumber;
                break;
            case "TH":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 66, $mobileNumber );
                return $mobileNumber;
                break;
            case "TL":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 670, $mobileNumber );
                return $mobileNumber;
                break;
            case "TR":
                $mobileNumber = $this->wacr_get_valid_mobile( 11, 90, $mobileNumber );
                return $mobileNumber;
                break;
            case "TM":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 993, $mobileNumber );
                return $mobileNumber;
                break;
            case "AE":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 971, $mobileNumber );
                return $mobileNumber;
                break;
            case "UZ":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 998, $mobileNumber );
                return $mobileNumber;
                break;
            case "VN":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 84, $mobileNumber );
                return $mobileNumber;
                break;
            case "YE":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 967, $mobileNumber );
                return $mobileNumber;
                break;
            case "AU":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 61, $mobileNumber );
                return $mobileNumber;
                break;
            case "AS":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1684, $mobileNumber );
                return $mobileNumber;
                break;
            case "CK":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 682, $mobileNumber );
                return $mobileNumber;
                break;
            case "FJ":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 679, $mobileNumber );
                return $mobileNumber;
                break;
            case "PF":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 689, $mobileNumber );
                return $mobileNumber;
                break;
            case "GU":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1671, $mobileNumber );
                return $mobileNumber;
                break;
            case "KI":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 686, $mobileNumber );
                return $mobileNumber;
                break;
            case "MH":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 692, $mobileNumber );
                return $mobileNumber;
                break;
            case "FM":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 691, $mobileNumber );
                return $mobileNumber;
                break;
            case "NR":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 674, $mobileNumber );
                return $mobileNumber;
                break;
            case "NC":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 687, $mobileNumber );
                return $mobileNumber;
                break;
            case "NZ":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 64, $mobileNumber );
                return $mobileNumber;
                break;
            case "NU":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 683, $mobileNumber );
                return $mobileNumber;
                break;
            case "NF":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 672, $mobileNumber );
                return $mobileNumber;
                break;
            case "MP":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1670, $mobileNumber );
                return $mobileNumber;
                break;
            case "PW":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 680, $mobileNumber );
                return $mobileNumber;
                break;
            case "PG":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 675, $mobileNumber );
                return $mobileNumber;
                break;
            case "PN":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 872, $mobileNumber );
                return $mobileNumber;
                break;
            case "WS":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 685, $mobileNumber );
                return $mobileNumber;
                break;
            case "SB":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 677, $mobileNumber );
                return $mobileNumber;
                break;
            case "TK":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 690, $mobileNumber );
                return $mobileNumber;
                break;
            case "TO":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 676, $mobileNumber );
                return $mobileNumber;
                break;
            case "TV":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 688, $mobileNumber );
                return $mobileNumber;
                break;
            case "UM":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 32, $mobileNumber );
                return $mobileNumber;
                break;
            case "VU":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 678, $mobileNumber );
                return $mobileNumber;
                break;
            case "WF":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 681, $mobileNumber );
                return $mobileNumber;
                break;
            case "AQ":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 672, $mobileNumber );
                return $mobileNumber;
                break;
            case "GS":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 500, $mobileNumber );
                return $mobileNumber;
                break;
            case "AI":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1264, $mobileNumber );
                return $mobileNumber;
                break;
            case "AW":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 297, $mobileNumber );
                return $mobileNumber;
                break;
            case "AG":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1268, $mobileNumber );
                return $mobileNumber;
                break;
            case "BS":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1242, $mobileNumber );
                return $mobileNumber;
                break;
            case "BB":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1246, $mobileNumber );
                return $mobileNumber;
                break;
            case "BZ":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 501, $mobileNumber );
                return $mobileNumber;
                break;
            case "BM":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1441, $mobileNumber );
                return $mobileNumber;
                break;
            case "CA":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1, $mobileNumber );
                return $mobileNumber;
                break;
            case "KY":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 345, $mobileNumber );
                return $mobileNumber;
                break;
            case "VG":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1284, $mobileNumber );
                return $mobileNumber;
                break;
            case "CR":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 506, $mobileNumber );
                return $mobileNumber;
                break;
            case "CU":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 53, $mobileNumber );
                return $mobileNumber;
                break;
            case "DM":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1767, $mobileNumber );
                return $mobileNumber;
                break;
            case "DO":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1849, $mobileNumber );
                return $mobileNumber;
                break;
            case "SV":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 503, $mobileNumber );
                return $mobileNumber;
                break;
            case "GL":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 299, $mobileNumber );
                return $mobileNumber;
                break;
            case "GD":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1473, $mobileNumber );
                return $mobileNumber;
                break;
            case "GP":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 590, $mobileNumber );
                return $mobileNumber;
                break;
            case "GT":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 502, $mobileNumber );
                return $mobileNumber;
                break;
            case "HT":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 509, $mobileNumber );
                return $mobileNumber;
                break;
            case "HN":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 504, $mobileNumber );
                return $mobileNumber;
                break;
            case "JM":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1876, $mobileNumber );
                return $mobileNumber;
                break;
            case "MQ":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 596, $mobileNumber );
                return $mobileNumber;
                break;
            case "MX":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 52, $mobileNumber );
                return $mobileNumber;
                break;
            case "MS":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1664, $mobileNumber );
                return $mobileNumber;
                break;
            case "NI":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 505, $mobileNumber );
                return $mobileNumber;
                break;
            case "PA":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 507, $mobileNumber );
                return $mobileNumber;
                break;
            case "PR":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1939, $mobileNumber );
                return $mobileNumber;
                break;
            case "BL":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 590, $mobileNumber );
                return $mobileNumber;
                break;
            case "KN":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1869, $mobileNumber );
                return $mobileNumber;
                break;
            case "LC":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1758, $mobileNumber );
                return $mobileNumber;
                break;
            case "MF":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 590, $mobileNumber );
                return $mobileNumber;
                break;
            case "PM":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 508, $mobileNumber );
                return $mobileNumber;
                break;
            case "VC":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1784, $mobileNumber );
                return $mobileNumber;
                break;
            case "AN":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 599, $mobileNumber );
                return $mobileNumber;
                break;
            case "TT":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1868, $mobileNumber );
                return $mobileNumber;
                break;
            case "TC":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1649, $mobileNumber );
                return $mobileNumber;
                break;
            case "US":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 1, $mobileNumber );
                return $mobileNumber;
                break;
            case "VI":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 84, $mobileNumber );
                return $mobileNumber;
                break;
            case "AR":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 54, $mobileNumber );
                return $mobileNumber;
                break;
            case "BO":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 591, $mobileNumber );
                return $mobileNumber;
                break;
            case "CL":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 56, $mobileNumber );
                return $mobileNumber;
                break;
            case "CO":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 57, $mobileNumber );
                return $mobileNumber;
                break;
            case "BR":
                $mobileNumber = $this->wacr_get_valid_mobile( 11, 55, $mobileNumber );
                return $mobileNumber;
                break;
            case "EC":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 593, $mobileNumber );
                return $mobileNumber;
                break;
            case "FK":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 500, $mobileNumber );
                return $mobileNumber;
                break;
            case "GF":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 594, $mobileNumber );
                return $mobileNumber;
                break;
            case "GY":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 595, $mobileNumber );
                return $mobileNumber;
                break;
            case "PY":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 595, $mobileNumber );
                return $mobileNumber;
                break;
            case "PE":
                $mobileNumber = $this->wacr_get_valid_mobile( 9, 51, $mobileNumber );
                return $mobileNumber;
                break;
            case "SR":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 94, $mobileNumber );
                return $mobileNumber;
                break;
            case "UY":
                $mobileNumber = $this->wacr_get_valid_mobile( 10, 598, $mobileNumber );
                return $mobileNumber;
                break;
            case "VE":
                $mobileNumber = $this->wacr_get_valid_mobile( 7, 58, $mobileNumber );
                return $mobileNumber;
                break;
            
            default:
                $mobileNumber = $this->wacr_get_valid_mobile_without_country( $mobileNumber );

            }
    }


    public function wacr_get_valid_mobile( $mobileLength, $mobileCountry, $mobileNumber ) {

        // check if mobilenumber is equals to country number length
        if ( strlen( $mobileNumber ) === $mobileLength ) { 
            $mobileNumber = $mobileCountry . $mobileNumber;
        } elseif ( strlen( $mobileNumber ) === $mobileLength - 1 ) {
            $mobileNumber = "";
        } elseif ( strlen( $mobileNumber ) == $mobileLength + 1 ) {
            $_mobile = substr( $mobileNumber, 0, 1 );
            if ( ( $_mobile == "0" ) || ( $_mobile == $mobileCountry ) ) {
                $mobileNumber = substr( $mobileNumber, 1, $mobileLength );
                $mobileNumber = $mobileCountry . $mobileNumber;
            } else {
                $mobileNumber = "$mobileCountry$mobileNumber";
                $mobile_len = strlen($mobileCountry);
                $mobile_len = $mobileLength + $mobile_len;
                $mobileNumber = substr( $mobileNumber, 0, $mobile_len );
            }
        } elseif ( strlen( $mobileNumber ) == $mobileLength + 2 ) {
            $_mobile = substr( $mobileNumber, 0, 2 );
            if ( strcmp( $_mobile, $mobileCountry ) ) {
                $mobileNumber = "$mobileCountry$mobileNumber";
                $mobile_len = strlen($mobileCountry);
                $mobile_len = $mobileLength + $mobile_len;
                $mobileNumber = substr( $mobileNumber, 0, $mobile_len );
            }
        } elseif ( strlen( $mobileNumber ) == $mobileLength + 3 ) {
            $_mobile = substr( $mobileNumber, 0, 3 );
            // compare number
            if ( strcmp( $_mobile, $mobileCountry ) ) {
                $mobileNumber = "";
            }
        } elseif ( strlen( $mobileNumber ) >= $mobileLength + 4 ) {
            $_mobile = substr( $mobileNumber, 0, 4 );

            // compare number
            if ( strcmp( $_mobile, $mobileCountry ) ) {
                $mobileNumber = "";
            }
        }
       
        return $mobileNumber;
    }


    public function wacr_get_valid_mobile_without_country($mobile){
        return;
    }


}
new Wacr_Cart_Handle();
