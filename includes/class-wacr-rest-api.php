<?php
/**
 *
 * This class defines all code related to custom rest api endpoints.
 *
 * @since      1.2.13
 * @package    WacR
 * @subpackage WacR/includes
 * @author     techspawn1 <contact@techspawn.com>
 */
class WacR_Rest_Api
{
    public function __construct(){
        add_action( "rest_api_init", [$this, "wacr_register_rest_routes"]);
        add_action('woocommerce_api_loaded', [$this, "wacr_load_apifile"]);
    }

    /**
     * Registers all rest routes
     */
    public function wacr_register_rest_routes(){

        // *Register Locations rest route
        register_rest_route('wc/v3', '/test/', array(
            'callback' => [$this, 'wacr_test'],
            'methods' => 'POST',
            'permission_callback' => '__return_true',
        ));
    }

     /**
     * callback function for locations rest route
     */
    public function wacr_test()
    {
        global $wpdb;
        $_messageBody = "stop";
        $_mobileNumber = "9767114114";

        //now get customer from mobile number
        $test = $wpdb->update($wpdb->prefix.'wacr_adandoned_order_list', array('wacr_message_enable' => "stop"), array('wacr_customer_mobile_no' => $_mobileNumber));
               
        // die;
        //then update meta for detection of stop feature

        
        return $test;
    }

    

}

new WacR_Rest_Api();