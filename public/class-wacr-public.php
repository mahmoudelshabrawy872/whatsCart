<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://techspawn.com/
 * @since      1.0.0
 *
 * @package    Wacr
 * @subpackage Wacr/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wacr
 * @subpackage Wacr/public
 * @author     TechSpawn <support@techspawn.com>
 */
class Wacr_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		
		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style($this->plugin_name . '_click_to_chat', plugin_dir_url(__FILE__) . 'css/wacr-click-to-chat.css', array(), $this->version, 'all'); 
		wp_enqueue_style($this->plugin_name . '_public', plugin_dir_url(__FILE__) . 'css/wacr-public.css', array(), $this->version, 'all'); 
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script($this->plugin_name.'_sweetalert', plugin_dir_url(__FILE__) . 'js/sweetalert2@10.js', array('jquery'), $this->version, true);
		wp_enqueue_script('wacr_public_reg_user', plugin_dir_url( __FILE__ ) . '/js/wacr-user-reg.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name.'_click_to_chat', plugin_dir_url( __FILE__ ) . 'js/wacr-click-to-chat.js', array( 'jquery' ), $this->version, true );
        $wacr_enable_otp_login = get_option("wacr_enable_otp_login");
        if($wacr_enable_otp_login == 'on'){
            wp_enqueue_script('wacr_public_opt_in', plugin_dir_url( __FILE__ ) . '/js/wacr-opt-in.js', array( 'jquery' ), $this->version, true );
        }

		
		$wacr_reg_without_pswd = get_option("wacr_reg_without_pswd");
        if($wacr_reg_without_pswd == 'on'){
            wp_enqueue_script('wacr_public_login_in', plugin_dir_url( __FILE__ ) . '/js/wacr-login-otp.js', array( 'jquery' ), $this->version, true );
			wp_localize_script('wacr_public_login_in', 'wacr_expire_otp', array('otptime' => get_option('wacr_timer_for_otp'), 'nonce' => wp_create_nonce('ajax_nonce_can')));
        }

        wp_localize_script('wacr_public_opt_in', 'wacr_public_js_data', array('ajax_url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('ajax_nonce_can')));
        wp_localize_script('wacr_public_reg_user', 'wacr_public_js_data', array('ajax_url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('ajax_nonce_can')));


		}

	public function wacr_checkout_script() {
		
		wp_enqueue_script('wacr_public_js', plugin_dir_url( __FILE__ ) . '/js/wacr-get-mobile.js', array( 'jquery' ), $this->version, true );
		
		wp_localize_script('wacr_public_js', 'wacr_public_js_data', array('ajax_url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('ajax_nonce_can')));
		$this->wacr_add_additional_details();
		return;
		
	}
	public function wacr_wacr_get_templates()
	{
		global $wacr_op;
		$response = Wacr_API_functions::wacr_get_wp_templates();
		$encode_response = json_decode($response);
		$inc = 1;
		foreach($encode_response->data as $dkey => $dval )
		{
			$wacr_op[] = $dval->name;
			$inc++;
		} 
		echo json_encode($wacr_op);
		wp_die();
	}
	public function wacr_otp_login_field(){
	?>
		
			<button id="wacr_send_otp" type="button" class="wacr_send_otp"><?php esc_html_e( "Send OTP", 'wacr' );?></button>
			<div class="wacrLoginWithOTPflex">
			<input type="text" class="input" id="wacr_otp_validate" class="wacr_otp_validate" name="wacr_otp_validate">
			<button type="button" class="wacr_log_btn_submit"> 
				<?php esc_html_e("Submit OTP", 'wacr'); ?>
			</button>
			<span class="wacr_close_log">&times;</span>
			</div>
			<button id="wacr_send_otp" type="button" class="wacr_resend_submit"><?php esc_html_e( "Resend", 'wacr' );?></button>
			<div id="countdown"></div>

	<?php
	}

	public function wacr_reg_without_pswd_cb(){
		$isLoginOtp = get_option("wacr_reg_without_pswd"); 
	?>		
			<p>
				<button id="wacr_send_otp" type="button" class="wacr_send_otp"><?php esc_html_e( "Send OTP", 'wacr' );?></button>
			</p>
					
			<div class="wacrLoginWithOTPflex">
			<input type="text" class="input" id="wacr_otp_validate" class="wacr_otp_validate" name="wacr_otp_validate">
			<button type="button" class="wacr_log_btn_submit"> 
				<?php esc_html_e("Submit OTP", 'wacr'); ?>
			</button>
			<span class="wacr_close_log">&times;</span>
			</div>

			<?php if($isLoginOtp == "on"){ ?>
				<div id="wacr_otpexpin"><?php esc_html_e('OTP expires in: ', 'wacr'); ?> <span id="timer"></span></div>
				<p class="wacrotpor"> <?php esc_html_e( "OR", 'wacr' ) ?></p>
				<p>
					<input class="tgl wacr_login_withOtp tgl-skewed" id="cb3" type="checkbox"/>
					<label class="tgl-btn woocommerce-button button woocommerce-form-login__submit wp-element-button" data-tg-off="Login With OTP" data-tg-on="Login With Password" for="cb3"></label>
				</p>
			<?php } ?>
	<?php
	}

	public function wacr_save_triggers_ajax()
	{
		global $wpdb; 
		$table_name_trigger = $wpdb->prefix . 'wacr_dynamic_triggers';
		$charset_collate = $wpdb->get_charset_collate();
		if(strtolower($wpdb->get_var( "show tables like '$table_name_trigger'" )) != strtolower($table_name_trigger) ) 
		  {
			$tbl = "CREATE TABLE $table_name_trigger (
				`id`                  BIGINT(20) NOT NULL auto_increment,
				`wacr_trigger_title`         VARCHAR(100) NULL DEFAULT NULL,
				`wacr_trigger_template`      VARCHAR(100) NULL DEFAULT NULL,
				`wacr_trigger_time`  VARCHAR(100) NULL DEFAULT NULL,
				`wacr_trigger_meta` VARCHAR(100) NULL DEFAULT NULL,
				`wacr_created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
				)$charset_collate;";
				include_once ABSPATH . '/wp-admin/includes/upgrade.php';
				dbDelta($tbl);
			}
		$response = $wpdb->query($wpdb->prepare("TRUNCATE TABLE ".$wpdb->prefix . "wacr_dynamic_triggers"));

		$prepare_request = $_POST['prepare_request'];
		foreach($prepare_request as $key => $value)
		{
			$template_id = $value[0];
			$time_defined = $value[1];
			$wpdb->insert($table_name_trigger, array(
				'wacr_trigger_template' => $template_id,
				'wacr_trigger_time' => $time_defined,
				));
		} 
		esc_html_e("1");
		die();
	}

	public function wacr_delete_message_logs()
	{
		global $wpdb;
		$table_name = $wpdb->prefix."wacr_message_logs";
		if (isset($_POST['selected_logs'])) {
			$ids = implode(',', $_POST['selected_logs']);
			if (!empty($ids)) {
			$wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
			}
		}
	  die();
	}
	
	public function wacr_get_user_data_on_checkout(){
		
		if (isset($_POST) && $_POST['action'] == 'get_user_data') {

            if (!WC()->cart) { 
                return false;
            }
			
			if (isset($_POST['wacr_user_mail'])) {
				$wacr_user_mail = sanitize_text_field(wp_unslash($_POST['wacr_user_mail']));
				if(!empty($wacr_user_mail)){
					WC()->session->set('wacr_user_mail', $wacr_user_mail);
				}
			}
			if (isset($_POST['wacr_user_phone'])) {
				$wacr_user_phone = sanitize_text_field(wp_unslash($_POST['wacr_user_phone']));
				if(!empty($wacr_user_phone)){
					WC()->session->set('wacr_user_phone', $wacr_user_phone);
				}
			}
			if (isset($_POST['wacr_user_first_name'])) {
				$wacr_user_first_name = sanitize_text_field(wp_unslash($_POST['wacr_user_first_name']));
				if(!empty($wacr_user_first_name)){
					WC()->session->set('wacr_user_first_name', $wacr_user_first_name);
				}
			}
			if (isset($_POST['wacr_user_last_name'])) {
				$wacr_user_last_name = sanitize_text_field(wp_unslash($_POST['wacr_user_last_name']));
				if(!empty($wacr_user_last_name)){
					WC()->session->set('wacr_user_last_name', $wacr_user_last_name);
				}
			}
			if (isset($_POST['wacr_country'])) {
				$wacr_wacr_country = sanitize_text_field(wp_unslash($_POST['wacr_country']));
				if(!empty($wacr_wacr_country)){
					WC()->session->set('wacr_country', $wacr_wacr_country);
				}
			}
		}
		$this->wacr_save_data();
		
		return;
	}


	public function wacr_save_data(){
		
		$disableAbandoned = get_option('wacr_disable_abandoned_status');
		if($disableAbandoned == 'on'){
			return;
		}
		global $wpdb;
        global $woocommerce;
        
        $current_time = current_time('mysql', false);
        $user_data = array();
        $cart_information;
		$items = $woocommerce->cart->get_cart();
			foreach($items as $item => $values) { 
				$_product =  wc_get_product( $values['data']->get_id()); 
				$_product_title = $_product->get_title();
				$_product_quantity = $values['quantity'];

				$_product_price = get_post_meta($values['product_id']);

				$product_information_to_save[] = array (
					'product_name' => "$_product_title",
					'product_quantity' => "$_product_quantity",
					'product_price' => $_product_price['_regular_price'][0],
				);
			} 
		
        require_once plugin_dir_path( __FILE__ ) . '/partials/wacr-cart-handle.php';
        $wacr_class = new Wacr_Cart_Handle();
		
		$cart_information = $wacr_class->get_cart_details();
		$user_details = $wacr_class->get_user_details();
		$cart_total = WC()->cart->total;
		$wacr_customer_id = WC()->session->get_customer_id();
        $table = $wpdb->prefix.'wacr_adandoned_order_list';
        $sql_query = $wpdb->prepare("SELECT COUNT(id) FROM `$table` WHERE wacr_customer_id = '$wacr_customer_id'");
        $count = $wpdb->get_var($sql_query);
        if($count>0){ 
            $wpdb->update($table, array('wacr_customer_mobile_no' => $user_details['wacr_user_phone'],
            'wacr_customer_email' => $user_details['wacr_user_mail'],
            'wacr_customer_first_name' => $user_details['wacr_user_first_name'],
            'wacr_customer_last_name' => $user_details['wacr_user_last_name'],
            'wacr_cart_json' => serialize($cart_information['product_array']),
            'wacr_cart_total_json' => json_encode($product_information_to_save),
            'wacr_cart_total' => $cart_total,
            'wacr_cart_currency' => $cart_information['cart_currency'],
			'wacr_status' => '0',
            'wacr_last_access_time' => $cart_information['current_time']), array('wacr_customer_id' => $wacr_customer_id));
        }else{
            $wpdb->insert($table, array(
                'wacr_customer_id' => $wacr_customer_id,
                'wacr_customer_email' => $user_details['wacr_user_mail'],
                'wacr_customer_mobile_no' => $user_details['wacr_user_phone'],
                'wacr_customer_first_name' => $user_details['wacr_user_first_name'],
                'wacr_customer_last_name' => $user_details['wacr_user_last_name'],
                'wacr_customer_type' => 'REGISTERED',
                'wacr_cart_json' => serialize($cart_information['product_array']),
                'wacr_cart_total_json' => json_encode($product_information_to_save),
                'wacr_cart_total' => $cart_total,
                'wacr_cart_currency' => $cart_information['cart_currency'],
                'wacr_last_access_time' => $cart_information['current_time']
                ));
        }
		
		return;
		wp_die();
	}
	
	public function wacr_add_additional_details()
    {
        global $wpdb;
        global $woocommerce;
        if (!WC()->cart) { 
            return false;
        }
        $current_time = current_time('mysql', false); 
        WC()->session->set('wacr_last_access_time', $current_time);
        $customer_id = WC()->session->get_customer_id();

        $cart_table = $wpdb->prefix . 'wacr_adandoned_order_list';
        $get_sql = $wpdb->prepare("SELECT COUNT(id) FROM $cart_table WHERE wacr_customer_id = %s AND wacr_status IN (0,1,2)", $customer_id);
        $result_count = $wpdb->get_var($get_sql);
		
        if ($result_count > 0) {

            $wpdb->update($cart_table, array('wacr_last_access_time' => $current_time, 'wacr_message_sent' => 0,'wacr_status' => 0, 'wacr_abandoned_date_time' => $current_time), array('wacr_customer_id' => $customer_id));
        }

		$this->wacr_save_data();
		return;
		wp_die();
    }


	public function wacr_change_order_status_to_recovered($order_id, $posted_data, $order){

        global $wpdb;
        $check = true;
        $table_name = $wpdb->prefix . 'wacr_adandoned_order_list';
        if (is_a($order, 'WC_Order_Refund')) {
            $check = false;
        }

        if ($check) {
            $billing_phone  = $order->get_billing_phone();
            $country_code = $order->get_billing_country();
			$calling_code = WC()->countries->get_country_calling_code( $country_code );
           // $customernumber = preg_replace('/[^0-9]/', '', $billing_phone);
		    $customernumber = trim($calling_code,'+').$billing_phone;
            $country_code = $order->get_billing_country();
			$check_abandoned_entry_sql = $wpdb->prepare("SELECT id, wacr_customer_id FROM $table_name WHERE wacr_customer_mobile_no LIKE '$customernumber' AND wacr_status IN (0,1)"); 

			$matching_results = $wpdb->get_results($check_abandoned_entry_sql);

			if (is_array($matching_results) && COUNT($matching_results) > 0) {
				foreach ($matching_results as $result) {
					$customer_id = $result->wacr_customer_id;
					$wpdb->update($table_name, array("wacr_status" => 2), array('wacr_customer_id' => $customer_id));
				}
			}
        }
    
	}
	public function wacr_confirm_order_by_whatsapp( $order_id ) {
		global $wpdb;
		global $woocommerce;
	
		if ( ! $order_id )
			return;
			
		if( get_post_meta( $order_id, 'wacr_order_sent_once') ) {
			return; // Exit if already processed
		}

		$order = wc_get_order( $order_id );
		if ( in_array( $order->get_payment_method(), array( 'bacs', 'cod', 'cheque', '' ) ) ) {
			$order->update_status( 'on-hold' );
			$paymentMethod = $order->get_payment_method();
					switch ($paymentMethod) {
						case "cod":
							$paymentMethod = "Cash On Delivery";
						  break;
						case "bacs":
							$paymentMethod = "Direct Bank Transfer";
						  break;
						case "cheque":
							$paymentMethod = "Cheque";
						  break;
						default:
						  $paymentMethod = 'Online Transfer';
					  }
			$data  = $order->get_data(); //order data
			
			$current_date = $order->order_date;		
			$order_id        = $data['id'];
			$order_parent_id = $data['parent_id'];
			$customer_id     = $data['customer_id'];		
			$billing_email      = $data['billing']['email'];
			$billing_phone      = $data['billing']['phone'];	
			//billing
			$billing_first_name = $data['billing']['first_name'];
			$billing_last_name  = $data['billing']['last_name'];
			$billing_company    = $data['billing']['company'];
			$billing_address_1  = $data['billing']['address_1'];
			$billing_address_2  = $data['billing']['address_2'];
			$billing_city       = $data['billing']['city'];
			$billing_state      = $data['billing']['state'];
			$billing_postcode   = $data['billing']['postcode'];
			$billing_country    = $data['billing']['country'];
	
			$billing_address_complete = join(', ', array_filter(array($billing_address_1, $billing_address_2, $billing_city, $billing_state, $billing_postcode, $billing_country)));
			//shipping
			$shipping_first_name = $data['shipping']['first_name'];
			$shipping_last_name  = $data['shipping']['last_name'];
			$shipping_company    = $data['shipping']['company'];
			$shipping_address_1  = $data['shipping']['address_1'];
			$shipping_address_2  = $data['shipping']['address_2'];
			$shipping_city       = $data['shipping']['city'];
			$shipping_state      = $data['shipping']['state'];
			$shipping_postcode   = $data['shipping']['postcode'];
			$shipping_country    = $data['shipping']['country'];
	
			$shipping_address_complete = join(', ', array_filter(array($billing_address_1, $billing_address_2, $billing_city, $billing_state, $billing_postcode, $billing_country)));
			
			if(!isset($billing_email)||empty($billing_email)){
				$billing_email = $data['shipping']['email'];
			}
			if(!isset($billing_phone)||empty($billing_phone)){
				$billing_phone = $data['shipping']['phone'];
			}
			if(!isset($billing_first_name)||empty($billing_first_name)){
				$billing_first_name = $shipping_first_name;
			}
			if(!isset($billing_last_name)||empty($billing_last_name)){
				$billing_last_name = $shipping_last_name;
			}
			if(!isset($billing_company)||empty($billing_company)){
				$billing_company = $shipping_company;
			}
			if(!isset($billing_address_1)||empty($billing_address_1)){
				$billing_address_1 = $shipping_address_1;
			}
			if(!isset($billing_address_2)||empty($billing_address_2)){
				$billing_address_2 = $shipping_address_2;
			}
			if(!isset($billing_city)||empty($billing_city)){
				$billing_city = $shipping_city;
			}
			if(!isset($billing_state)||empty($billing_state)){
				$billing_state = $shipping_state;
			}
			if(!isset($billing_postcode)||empty($billing_postcode)){
				$billing_postcode = $shipping_postcode;
			}
			if(!isset($billing_country)||empty($billing_country)){
				$billing_country = $shipping_country;
			}

			
			//order item details
			
				foreach ( $order->get_items() as $item_id => $item ) {
					$product_id[] = $item->get_product_id();
					$variation_id = $item->get_variation_id();
					$product = $item->get_product(); // see link above to get $product info
					$product_name[] = $item->get_name();
					$quantity = $item->get_quantity();
					$subtotal = $item->get_subtotal();
					$total = $item->get_total();
					$tax = $item->get_subtotal_tax();
					$tax_class = $item->get_tax_class();
					$tax_status = $item->get_tax_status();
					$allmeta = $item->get_meta_data();
					$somemeta = $item->get_meta( '_whatever', true );
					$item_type = $item->get_type(); // e.g. "line_item"
				}
				if(isset($product_name)){
					$products_name = implode(", ",$product_name);
				}
				$order_total = $order->get_total();
			$currencySymbol = get_woocommerce_currency();
			//create a order confirmation link
			$order_idHash = md5($order_id);
			
			$randomNum = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(15/strlen($x)) )),1,15);
	
			$table_name = $wpdb->prefix . 'wacr_cod_order_confirm'; 
			$wpdb->insert($table_name, array(
				'wacr_random_number' => $randomNum,
				'wacr_order_number' => $order_id,		
				'wacr_col2' => $wacr_col2,
			));
	
			$site_URL =  home_url();
			$confirm_link =  "$site_URL/?confirmOrder=$order_idHash&id=$randomNum";
			
	
			//
			//send confirmation link
			$mobile_number_id = wp_unslash(get_option('wacr_whatsapp_business_mobile_number_id'));
			$confirm_template = wp_unslash(get_option('wacr_order_confirmation_template'));
			$bearer_token = wp_unslash(get_option('wacr_bearer_token_whatsapp'));
			$mobile_number = wp_unslash(get_option('wacr_mobile_number_whatsapp'));
			
			//get temp language
			require_once plugin_dir_path(__DIR__ ) . 'admin/partials/wacr-api-functions.php';
			$response = new Wacr_API_functions();
			$language = $response->get_template_language($confirm_template);

			//check limit
			$dailyDbCount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM ".$wpdb->prefix."wacr_message_logs WHERE `wacr_msg_status` = 'sent' AND date(wacr_updated_date_time) = CURDATE() ORDER BY wacr_updated_date_time DESC"));
            $userSetCount = get_option('wacr_daily_message_limit_whatsapp');
            
            if($dailyDbCount>$userSetCount){
				//insert message log
			$table_for_logs = $wpdb->prefix.'wacr_message_logs';
			$wpdb->insert($table_for_logs, array(
			'wacr_msg_type' => "limit_exceeded",
			'wacr_msg_status' => "limit",
			'wacr_template' => $template_name,
			'wacr_orderdetails' => $order_id,
			));
                return;
            }
			
			$curl = curl_init();
			$json_array = array (
				"messaging_product" => "whatsapp","to" => "$billing_phone","type" => "template","template" => 
				array ("name" => "$confirm_template","language" => 
				array (
					"code" => "$language",
				),
				"components" => 
				array (
					0 => 
					array (
					"type" => "header",
					"parameters" => array (
						0 => array (
							"type" => "text",
							"text" => "$billing_first_name"))
					),
					1 => 
					array (
					"type" => "body",
					"parameters" => array (
							0 => array (
								"type" => "text",
								"text" => "$order_id"), //Order id
							1 => array (
								"type" => "text",
								"text" => "$current_date"), //Date
							2 => array (
								"type" => "text",
								"text" => "$billing_email"), //Email
							3 => array (
								"type" => "text",
								"text" => "$order_total $currencySymbol"), //Total
							4 => array (
								"type" => "text",
								"text" => "$paymentMethod"), //Payment Method
							5 => array (
								"type" => "text",
								"text" => "$products_name"), //Items
							6 => array (
								"type" => "text",
								"text" => "$order_total $currencySymbol"), //Total
							7 => array (
								"type" => "text",
								"text" => "$billing_address_complete"), //Billing address
							8 => array (
								"type" => "text",
								"text" => "$shipping_address_complete"), //Shipping address
							9	 => array (
								"type" => "text",
								"text" => "$confirm_link") //link to confirm
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
			$response_decode = get_object_vars(json_decode($response));
		
			if(!isset($response_decode['error'])){
				//insert message log
				$table_for_logs = $wpdb->prefix.'wacr_message_logs';
				$wpdb->insert($table_for_logs, array(
					'wacr_msg_type' => "order_placed",
					'wacr_msg_status' => "sent",
					'wacr_template' => $confirm_template,
					'wacr_orderdetails' => $order_id,
					));
					update_post_meta( $order_id, 'wacr_order_sent_once', "yes" );	
			}else{
				if(isset($response_decode['error'])){
					//insert message log
					$table_for_logs = $wpdb->prefix.'wacr_message_logs';
					$wpdb->insert($table_for_logs, array(
						'wacr_msg_type' => "order_placed",
						'wacr_msg_status' => "error",
						'wacr_template' => $confirm_template,
						'wacr_orderdetails' => $response_decode['error']->error_data->details,
						));
				}
			}	
	
			update_post_meta( $order_id, 'wacr_confirm_sent', "yes" );
	   
		} else {
			$order->update_status( 'completed' );
		}
		
	}

	public function wacr_order_complete_notification($order_id){

		global $wpdb;
		global $woocommerce;
		$isVerify = get_option("wacr_order_confirmation");
		if($isVerify == "on"){
			return;
		}

		if( get_post_meta( $order_id, 'wacr_order_sent_once') ) {
			return; /// Exit if already processed
		}
		
		
		$order =  wc_get_order($order_id);
		$data  = $order->get_data();
		$order_id        = $data['id'];
		$order_parent_id = $data['parent_id'];
		$customer_id     = $data['customer_id'];
		$billing_email      = $data['billing']['email'];
		$billing_phone      = $data['billing']['phone'];
		$billing_first_name = $data['billing']['first_name'];
		$billing_last_name  = $data['billing']['last_name'];
		$billing_company    = $data['billing']['company'];
		$billing_address_1  = $data['billing']['address_1'];
		$billing_address_2  = $data['billing']['address_2'];
		$billing_city       = $data['billing']['city'];
		$billing_state      = $data['billing']['state'];
		$billing_postcode   = $data['billing']['postcode'];
		$billing_country    = $data['billing']['country'];

		$full_billing_address = "$billing_address_1 , $billing_city, $billing_state, $billing_country, $billing_postcode";
		
		## SHIPPING INFORMATION:

		$shipping_first_name = $data['shipping']['first_name'];
		$shipping_last_name  = $data['shipping']['last_name'];
		$shipping_company    = $data['shipping']['company'];
		$shipping_address_1  = $data['shipping']['address_1'];
		$shipping_address_2  = $data['shipping']['address_2'];
		$shipping_city       = $data['shipping']['city'];
		$shipping_state      = $data['shipping']['state'];
		$shipping_postcode   = $data['shipping']['postcode'];
		$shipping_country    = $data['shipping']['country'];

		$full_shipping_address = "$shipping_address_1 , $shipping_city, $shipping_state, $shipping_country, $shipping_postcode";

			
	if(!isset($billing_email)||empty($billing_email)){
			$billing_email = $data['shipping']['email'];
		}
		if(!isset($billing_phone)||empty($billing_phone)){
			$billing_phone = $data['shipping']['phone'];
		}
		if(!isset($billing_first_name)||empty($billing_first_name)){
			$billing_first_name = $shipping_first_name;
		}
		if(!isset($billing_last_name)||empty($billing_last_name)){
			$billing_last_name = $shipping_last_name;
		}
		if(!isset($billing_company)||empty($billing_company)){
			$billing_company = $shipping_company;
		}
		if(!isset($billing_address_1)||empty($billing_address_1)){
			$billing_address_1 = $shipping_address_1;
		}
		if(!isset($billing_address_2)||empty($billing_address_2)){
			$billing_address_2 = $shipping_address_2;
		}
		if(!isset($billing_city)||empty($billing_city)){
			$billing_city = $shipping_city;
		}
		if(!isset($billing_state)||empty($billing_state)){
			$billing_state = $shipping_state;
		}
		if(!isset($billing_postcode)||empty($billing_postcode)){
			$billing_postcode = $shipping_postcode;
		}
		if(!isset($billing_country)||empty($billing_country)){
			$billing_country = $shipping_country;
		}

		$items = $order->get_items();
		$order_total = $order->get_total();

		$paymentMethod = $order->get_payment_method();
					switch ($paymentMethod) {
						case "cod":
							$paymentMethod = "Cash On Delivery";
						  break;
						case "bacs":
							$paymentMethod = "Direct Bank Transfer";
						  break;
						case "cheque":
							$paymentMethod = "Cheque";
						  break;
						default:
						  $paymentMethod = 'Online Transfer';
					  }
		
			foreach ($items as $item_key => $item ){
			$item_id = $item->get_id();
			$product      = $item->get_product(); 
			
			## Access Order Items data properties (in an array of values) ##
			$item_data    = $item->get_data();

			$product_name[] = $item_data['name'];
			$product_id   = $item_data['product_id'];
			$variation_id = $item_data['variation_id'];
			$quantity     = $item_data['quantity'];
			$tax_class    = $item_data['tax_class'];
			$line_subtotal     = $item_data['subtotal'];
			$line_subtotal_tax = $item_data['subtotal_tax'];
			$line_total        = $item_data['total'];
			$line_total_tax    = $item_data['total_tax'];

			}
			
			$cart_item_count = count($product_name);
			$all_products = implode(',',$product_name);
			$mobile_number_id = wp_unslash(get_option('wacr_whatsapp_business_mobile_number_id'));
			$items = $all_products;
			$template_name = wp_unslash(get_option('wacr_order_notification_template'));
			$bearer_token = wp_unslash(get_option('wacr_bearer_token_whatsapp'));
			$mobile_number = wp_unslash(get_option('wacr_mobile_number_whatsapp'));
			
			$array = $wpdb->get_results($wpdb->prepare("SELECT wacr_template_id, wacr_head_param_count, wacr_body_param_count, wacr_head_params, wacr_body_params, wacr_head_text, wacr_body_text, wacr_button_info, wacr_other_params FROM ".$wpdb->prefix."wacr_templates WHERE wacr_template_name = %s", $template_name));
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
			$language = $wacr_other_params;

			if($wacr_head_param_count>0){

				$public_head_paramter_type = $wacr_head_params;
				if(isset($public_head_paramter_type)){
					switch ($public_head_paramter_type) {
						case "customer_first_name":
							$set_head_param = $billing_first_name;
						  break;
						case "customer_last_name":
							$set_head_param = $billing_last_name;
						  break;
						case "customer_email":
							$set_head_param = $billing_email;
						  break;
						case "admin_email":
							$set_body_param = get_bloginfo('admin_email');
						  break;
						case "customer_phone":
							$set_head_param = $billing_phone;
						  break;
						case "customer_billing_address":
							$set_head_param = $full_billing_address;
						  break;
						case "customer_shipping_address":
							$set_head_param = $full_shipping_address;
						  break;
						case "cart_item_count":
							$set_head_param = $cart_item_count;
						  break;
						case "payment_method":
							$set_head_param = $paymentMethod;
						  break;
						case "admin_phone":
							$set_head_param = $billing_last_name;
						  break;
						case "order_id":
							$set_head_param = $order_id;
						  break;
						case "cart_items":
							$set_head_param = $items;
						  break;
						case "cart_total":
						  	$set_head_param = $order_total . html_entity_decode(get_woocommerce_currency_symbol());
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

			$wacr_body_variables =  stripslashes(html_entity_decode(($wacr_body_params)));
			$wacrBodyVariables = json_decode($wacr_body_variables);

			if($wacr_body_param_count>0){

				for($i=1; $i<=$wacr_body_param_count;$i++){
					$i2 = $i - 1;
					
					$public_paramter_type = $wacrBodyVariables[$i2];
					if(isset($public_paramter_type)){
						switch ($public_paramter_type) {
							case "customer_first_name":
								$set_body_param = $billing_first_name;
							  break;
							case "customer_last_name":
								$set_body_param = $billing_last_name;
							  break;
							case "customer_email":
								$set_body_param = $billing_email;
							  break;
							case "admin_email":
								$set_body_param = get_bloginfo('admin_email');
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
							case "payment_method":
								$set_body_param = $paymentMethod;
							  break;
							case "cart_item_count":
								$set_body_param = $cart_item_count;
							  break;
							case "order_id":
								$set_body_param = $order_id;
						 	  break;
							case "admin_phone":
								$set_body_param = $billing_last_name;
							  break;
							case "cart_items":
								$set_body_param = $items;
							  break;
							case "cart_total":
							  $set_body_param = $order_total . html_entity_decode(get_woocommerce_currency_symbol());
							  break;
							case "site_name":
							  $set_body_param = get_bloginfo( 'name' ); 
							  break;
							case "date":
							  $set_body_param = current_datetime()->format('Y-m-d H:i:s') ;
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

		// information generated above // now code for message
		if(!isset($head_array)){
			$head_array = array();
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
			'wacr_template' => $template_name,
			'wacr_orderdetails' => $order_id,
			));
				return;
		}
		require_once plugin_dir_path(__DIR__ ) . 'admin/partials/wacr-api-functions.php';
				$response = new Wacr_API_functions();
				$language = $response->get_template_language($template_name);
				
				$billing_phone = str_replace( array( '\'', '"',
				',' , ';', '<', '>', '+', '-', '@' ), '', $billing_phone);
		$curl = curl_init();
		if($wacr_button_info>0){

			$json_array = array (
				"messaging_product" => "whatsapp","to" => "$billing_phone","type" => "template","template" => 
				array ("name" => "$template_name","language" => 
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
							"text" => "/index.php",
							),
						)
					),
				),
				),
			);
		}else{	
			$json_array = array (
				"messaging_product" => "whatsapp","to" => "$billing_phone","type" => "template","template" => 
				array ("name" => "$template_name","language" => 
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
                'wacr_msg_type' => "order_placed",
                'wacr_msg_status' => "sent",
                'wacr_template' => $template_name,
                'wacr_orderdetails' => $order_id,
                ));
				update_post_meta( $order_id, 'wacr_order_sent_once', "yes" );	
		}else{
			if(isset($response_decode['error'])){
				//insert message log
				$table_for_logs = $wpdb->prefix.'wacr_message_logs';
				$wpdb->insert($table_for_logs, array(
					'wacr_msg_type' => "order_placed",
					'wacr_msg_status' => "error",
					'wacr_template' => $template_name,
					'wacr_orderdetails' => $response_decode['error']->error_data->details,
					));
			}
		}

	
	}

	//admin order notifications
	public function wacr_admin_order_complete_notification($order_id){

		global $wpdb;
		global $woocommerce;

		if( get_post_meta( $order_id, 'wacr_order_sent_once') ) {
			return; /// Exit if already processed
		}
		$adminNumber = get_option('wacr_admin_order_notification_mobile');
		
		$order =  wc_get_order($order_id);
		$data  = $order->get_data();
		$order_id        = $data['id'];
		$order_parent_id = $data['parent_id'];
		$customer_id     = $data['customer_id'];
		$billing_email      = $data['billing']['email'];
		$billing_phone      = $data['billing']['phone'];
		$billing_first_name = $data['billing']['first_name'];
		$billing_last_name  = $data['billing']['last_name'];
		$billing_company    = $data['billing']['company'];
		$billing_address_1  = $data['billing']['address_1'];
		$billing_address_2  = $data['billing']['address_2'];
		$billing_city       = $data['billing']['city'];
		$billing_state      = $data['billing']['state'];
		$billing_postcode   = $data['billing']['postcode'];
		$billing_country    = $data['billing']['country'];

		$full_billing_address = "$billing_address_1 , $billing_city, $billing_state, $billing_country, $billing_postcode";
		
		## SHIPPING INFORMATION:

		$shipping_first_name = $data['shipping']['first_name'];
		$shipping_last_name  = $data['shipping']['last_name'];
		$shipping_company    = $data['shipping']['company'];
		$shipping_address_1  = $data['shipping']['address_1'];
		$shipping_address_2  = $data['shipping']['address_2'];
		$shipping_city       = $data['shipping']['city'];
		$shipping_state      = $data['shipping']['state'];
		$shipping_postcode   = $data['shipping']['postcode'];
		$shipping_country    = $data['shipping']['country'];

					
		if(!isset($billing_email)||empty($billing_email)){
			$billing_email = $data['shipping']['email'];
		}
		if(!isset($billing_phone)||empty($billing_phone)){
			$billing_phone = $data['shipping']['phone'];
		}
		if(!isset($billing_first_name)||empty($billing_first_name)){
			$billing_first_name = $shipping_first_name;
		}
		if(!isset($billing_last_name)||empty($billing_last_name)){
			$billing_last_name = $shipping_last_name;
		}
		if(!isset($billing_company)||empty($billing_company)){
			$billing_company = $shipping_company;
		}
		if(!isset($billing_address_1)||empty($billing_address_1)){
			$billing_address_1 = $shipping_address_1;
		}
		if(!isset($billing_address_2)||empty($billing_address_2)){
			$billing_address_2 = $shipping_address_2;
		}
		if(!isset($billing_city)||empty($billing_city)){
			$billing_city = $shipping_city;
		}
		if(!isset($billing_state)||empty($billing_state)){
			$billing_state = $shipping_state;
		}
		if(!isset($billing_postcode)||empty($billing_postcode)){
			$billing_postcode = $shipping_postcode;
		}
		if(!isset($billing_country)||empty($billing_country)){
			$billing_country = $shipping_country;
		}

		
		$full_shipping_address = "$shipping_address_1 , $shipping_city, $shipping_state, $shipping_country, $shipping_postcode";

		$items = $order->get_items();
		$order_total = $order->get_total();

		$paymentMethod = $order->get_payment_method();
					switch ($paymentMethod) {
						case "cod":
							$paymentMethod = "Cash On Delivery";
						  break;
						case "bacs":
							$paymentMethod = "Direct Bank Transfer";
						  break;
						case "cheque":
							$paymentMethod = "Cheque";
						  break;
						default:
						  $paymentMethod = "Online";
					  }
		
			foreach ($items as $item_key => $item ){
			$item_id = $item->get_id();
			$product      = $item->get_product(); 
			
			## Access Order Items data properties (in an array of values) ##
			$item_data    = $item->get_data();

			$product_name[] = $item_data['name'];
			$product_id   = $item_data['product_id'];
			$variation_id = $item_data['variation_id'];
			$quantity     = $item_data['quantity'];
			$tax_class    = $item_data['tax_class'];
			$line_subtotal     = $item_data['subtotal'];
			$line_subtotal_tax = $item_data['subtotal_tax'];
			$line_total        = $item_data['total'];
			$line_total_tax    = $item_data['total_tax'];

			}
			
			$cart_item_count = count($product_name);
			$all_products = implode(',',$product_name);
			$mobile_number_id = wp_unslash(get_option('wacr_whatsapp_business_mobile_number_id'));
			$items = $all_products;
			$template_name = wp_unslash(get_option('wacr_admin_order_notification_template'));
			$bearer_token = wp_unslash(get_option('wacr_bearer_token_whatsapp'));
			$mobile_number = wp_unslash(get_option('wacr_mobile_number_whatsapp'));
			
			$array = $wpdb->get_results($wpdb->prepare("SELECT wacr_template_id, wacr_head_param_count, wacr_body_param_count, wacr_head_params, wacr_body_params, wacr_head_text, wacr_body_text, wacr_button_info, wacr_other_params FROM ".$wpdb->prefix."wacr_templates WHERE wacr_template_name = %s", $template_name));
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
			$language = $wacr_other_params;


			if($wacr_head_param_count>0){

				$public_head_paramter_type = $wacr_head_params;
				if(isset($public_head_paramter_type)){
					switch ($public_head_paramter_type) {
						case "customer_first_name":
							$set_head_param = $billing_first_name;
						  break;
						case "customer_last_name":
							$set_head_param = $billing_last_name;
						  break;
						case "customer_email":
							$set_head_param = $billing_email;
						  break;
						case "admin_email":
							$set_body_param = get_bloginfo('admin_email');
						  break;
						case "customer_phone":
							$set_head_param = $billing_phone;
						  break;
						case "customer_billing_address":
							$set_head_param = $full_billing_address;
						  break;
						case "customer_shipping_address":
							$set_head_param = $full_shipping_address;
						  break;
						case "cart_item_count":
							$set_head_param = $cart_item_count;
						  break;
						case "payment_method":
							$set_head_param = $paymentMethod;
						  break;
						case "admin_phone":
							$set_head_param = $billing_last_name;
						  break;
						case "order_id":
							$set_head_param = $order_id;
						  break;
						case "cart_items":
							$set_head_param = $items;
						  break;
						case "cart_total":
						  	$set_head_param = $order_total . html_entity_decode(get_woocommerce_currency_symbol());
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

			$wacr_body_variables =  stripslashes(html_entity_decode(($wacr_body_params)));
			$wacrBodyVariables = json_decode($wacr_body_variables);

			if($wacr_body_param_count>0){

				for($i=1; $i<=$wacr_body_param_count;$i++){
					$i2 = $i - 1;
					
					$public_paramter_type = $wacrBodyVariables[$i2];
					if(isset($public_paramter_type)){
						switch ($public_paramter_type) {
							case "customer_first_name":
								$set_body_param = $billing_first_name;
							  break;
							case "customer_last_name":
								$set_body_param = $billing_last_name;
							  break;
							case "customer_email":
								$set_body_param = $billing_email;
							  break;
							case "admin_email":
								$set_body_param = get_bloginfo('admin_email');
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
							case "payment_method":
								$set_body_param = $paymentMethod;
							  break;
							case "cart_item_count":
								$set_body_param = $cart_item_count;
							  break;
							case "order_id":
								$set_body_param = $order_id;
						 	  break;
							case "admin_phone":
								$set_body_param = $billing_last_name;
							  break;
							case "cart_items":
								$set_body_param = $items;
							  break;
							case "cart_total":
							  $set_body_param = $order_total . html_entity_decode(get_woocommerce_currency_symbol());
							  break;
							case "site_name":
							  $set_body_param = get_bloginfo( 'name' ); 
							  break;
							case "date":
							  $set_body_param = current_datetime()->format('Y-m-d H:i:s') ;
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

		// information generated above // now code for message
		if(!isset($head_array)){
			$head_array = array();
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
			'wacr_template' => $template_name,
			'wacr_orderdetails' => $order_id,
			));
				return;
		}
		require_once plugin_dir_path(__DIR__ ) . 'admin/partials/wacr-api-functions.php';
				$response = new Wacr_API_functions();
				$language = $response->get_template_language($template_name);
				
				$adminNumber = str_replace( array( '\'', '"',
				',' , ';', '<', '>', '+', '-', '@' ), '', $adminNumber);
				
		$curl = curl_init();
		if($wacr_button_info>0){

			$json_array = array (
				"messaging_product" => "whatsapp","to" => "$adminNumber","type" => "template","template" => 
				array ("name" => "$template_name","language" => 
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
							"text" => "/index.php",
							),
						)
					),
				),
				),
			);
		}else{
			$json_array = array (
				"messaging_product" => "whatsapp","to" => "$adminNumber","type" => "template","template" => 
				array ("name" => "$template_name","language" => 
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
		// echo $response;
		$response_decode = get_object_vars(json_decode($response));
		
		if(!isset($response_decode['error'])){
			//insert message log
			$table_for_logs = $wpdb->prefix.'wacr_message_logs';
			$wpdb->insert($table_for_logs, array(
                'wacr_msg_type' => "order_placed",
                'wacr_msg_status' => "sent",
                'wacr_template' => $template_name,
                'wacr_orderdetails' => $order_id,
                ));
				update_post_meta( $order_id, 'wacr_order_sent_once', "yes" );	
		}else{
			if(isset($response_decode['error'])){
				//insert message log
				$table_for_logs = $wpdb->prefix.'wacr_message_logs';
				$wpdb->insert($table_for_logs, array(
					'wacr_msg_type' => "order_placed",
					'wacr_msg_status' => "error",
					'wacr_template' => $template_name,
					'wacr_orderdetails' => $response_decode['error']->error_data->details,
					));
			}
		}

	
	}

	public function wacr_click_to_chat(){
		$ctc_after = plugin_dir_url( __FILE__ ) . '/images/wa_after.webp';
		$img2 = plugin_dir_url( __FILE__ ) . '/images/img2.png';
		$supportContact = get_option('wacr_ctc_mobile_number', 'wacr');
		$layout_selected = get_option('wacr_layout_option');

		$mobile_ahref = "https://wa.me/$supportContact";
		$imageWeb2 =  plugin_dir_url( __FILE__ ). 'images/logo.webp';
		$imageWeb22 =  plugin_dir_url( __FILE__ ). 'images/layout2.png';
		$imageWeb33 =  plugin_dir_url( __FILE__ ). 'images/layout3.png';
		$imageWeb44 =  plugin_dir_url( __FILE__ ). 'images/layout4.png';

		?>
				<style>
					#chat-bot .messenger.expanded {
					background-image: url("<?php echo esc_url( $img2); ?>");
					}
				</style>
				<div id="chat-bot">
				<div class="messenger br10">
					<div class="canfw_whatsapp_headline"><?php esc_html_e('Start a Conversation', 'wacr');?>
					</div>
					
					<div class="chatroom">
						<div class="canfw_person_cards">
					
					<ul class="chatbox_content_ul_canfw">
						
						<li class="chat_list_canfw">
							<div class="chat_main_canfw">
								<a class="main_chatbox_content_canfw" href="<?php echo esc_url( $mobile_ahref) ?>">
									<div class="chatbox_firstdiv_image">
										<img class="avatar_of_users" src="<?php echo esc_url( $ctc_after) ?>" alt="avtar">
									</div>
									<div class="chatbox_seconddiv_title">
										<div class="main_title_canfw"><?php esc_html_e('WhatsApp Support', 'wacr');?></div>
										<div class="sub_title_canfw"><?php esc_html_e('Support Executive', 'wacr');?></div>
									</div>
								</a>
							</div>
							
						</li>
					</ul>
					</div>
			
				</div>

				</div>
				<?php
				if((empty($layout_selected)) || ($layout_selected == "layout1"))
				{
					?>

				<!----layout 1-->
				<div class="Layout1">
					<div class="icon expanded">
						<div class="user">
						<i class="bi bi-person-circle me-2"></i>
						<?php esc_html_e('Need Support?', 'wacr');?>
						
						</div>
						<div id="canfw_whatsapp_logo">
						<img class="canfw_whatsapp_logo" width="40px" src="<?php echo esc_url( $imageWeb2) ?>">
						</div>
					</div>
				</div>
					<?php

				}
				if($layout_selected == "layout2")
				{
					?>
					<!----layout 2-->
				<div class="Layout2">
					<div class="icon expanded">						
						<div id="canfw_whatsapp_logo">
						<img class="canfw_whatsapp_logo"  src="<?php echo esc_url( $imageWeb22) ?>">
						</div>
					</div>
				</div>

					<?php

				}
				if($layout_selected == "layout3")
				{
					?>

				<!----layout 3-->
				<div class="Layout3">
					<div class="icon expanded">
						<div id="canfw_whatsapp_logo">
						<img class="canfw_whatsapp_logo" src="<?php echo esc_url( $imageWeb33) ?>">
						</div>
					</div>
				</div>
					<?php
				}

				if($layout_selected == "layout4")
				{
					?>
					<!----layout 4-->
				<div class="Layout4">
					<div class="icon expanded">
						<div id="canfw_whatsapp_logo">
						<img class="canfw_whatsapp_logo" src="<?php echo esc_url( $imageWeb44) ?>">
						</div>
					</div>
				</div>

					<?php
				}
				?>
				</div>
		<?php
	}


	public function wacr_validate_and_send_otp(){
		global $woocommerce;

		$mobileNum = sanitize_text_field($_POST['mobilE']);
		if(!is_numeric($mobileNum) || strlen($mobileNum)<=10){
			echo "error";
			wp_die();
			return;
		}
		$session_id = WC()->session->get_customer_id();
		$woocommerce->session->set_customer_session_cookie(true);	
		$otpTemp = get_option('wacr_otp_template_global');
		$send_message = $this->wacr_send_customer_otp($session_id,$mobileNum,$otpTemp);

		if($send_message)
			{
				$return = 'msgSent';
			}
			echo $return;
		die();

	}

	public function wacr_check_otp_cb(){
		$mobileNum = $_POST['mobilE'];
		$otp = $_POST['otp'];
		//send OTP
		
		global $wpdb;
        $check = true;
        $table_name = $wpdb->prefix . 'wacr_user_otp';
    
			$customer_id = WC()->session->get_customer_id();
			$check_abandoned_entry_sql = $wpdb->prepare("SELECT * FROM $table_name WHERE wacr_user_id = '$customer_id' order by id DESC limit 1"); 
			
			$matching_results = $wpdb->get_results($check_abandoned_entry_sql);	
			if (is_array($matching_results) && COUNT($matching_results) > 0) {
				foreach ($matching_results as $result) {
					$wacr_user_otp = $result->wacr_user_otp;
					if($wacr_user_otp == $otp)
					{
						$return = "verified";
					}
					else
					{
						$return = "nonverified";
					}
				}
			}
		
		echo $return;
		wp_die();
	}



	

	public function wacr_get_user_info_login()
	{	
		
		$username = $_POST['username'];
		$user = get_user_by('login', $username);
		if(isset($user->ID))
		{
			$customer_id = $user->ID;
			$customer_wa_number = get_user_meta( $customer_id, 'billing_phone', true );
			$otpTemp = get_option('wacr_otp_template_global');
			$send_message = $this->wacr_send_customer_otp($customer_id,$customer_wa_number, $otpTemp);
			if($send_message)
			{
				$return = 'otp_generated';
			}

		}
		else
		{
			$return = 0;
		}
		echo $return;
		die();
		
	}
	public function wacr_verify_otp_user_login()
	{
		global $wpdb;
        $check = true;
        $table_name = $wpdb->prefix . 'wacr_user_otp';
		$isWithOTP = sanitize_text_field($_POST['wacr_login_withOtp']);
		$username = $_POST['username'];
		$wacr_otp_validate = $_POST['wacr_otp_validate'];
		$user = get_user_by('login', $username);
		$isLoginWithOTP = get_option('wacr_reg_without_pswd');
		$setusertimeOTP = get_option('wacr_timer_for_otp');
		if(isset($user->ID))
		{
			$customer_id = $user->ID;
			$user_id = $user->ID;
			$check_abandoned_entry_sql = $wpdb->prepare("SELECT * FROM $table_name WHERE wacr_user_id = '$customer_id' order by id DESC limit 1"); 
			$matching_results = $wpdb->get_results($check_abandoned_entry_sql);	
			if (is_array($matching_results) && COUNT($matching_results) > 0) {
				foreach ($matching_results as $result) {
					$wacr_user_otp = $result->wacr_user_otp;
					$wacr_created_date = $result->wacr_created_date;
					$now_date = current_datetime()->format('Y-m-d H:i:s');

					$get_time_difference = (strtotime($now_date) - strtotime($wacr_created_date)) / 60;

					if ($get_time_difference > $setusertimeOTP){
						$return = "expried";
						echo $return;
						die();
						}	

					if($wacr_user_otp == $wacr_otp_validate)
					{	
						if($isWithOTP == "true"){
							wp_set_current_user( $user_id, $user->user_login );
							wp_set_auth_cookie( $user_id );
							$return = "verifiedwithotp";

						}else{
							$return = "verified";
						}
					}
					else
					{
						$return = "nonverified";
					}
				}
				
			}
		}
		else
		{
			$return = "nonverified";
		}
		echo $return;
		die();
		
	}
	public function wacr_send_customer_otp($customer_id,$customer_wa_number, $otp)
	{	
		$otpLen = get_option("wacr_otp_length");
		$otpType = get_option("wacr_otp_type");
		
		
		if($otpType == "num"){
			$wacr_opt_in_code = substr(str_shuffle(str_repeat($x='0123456789', ceil($otpLen/strlen($x)))),1,$otpLen);
		}else{
			$wacr_opt_in_code = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($otpLen/strlen($x)) )),1,$otpLen);
		}
		
        $addingFiveMinutes = strtotime(date('Y-m-d H:i:s').' + 5 minute');
        $customer_wa_otp_expiry = date('Y-m-d H:i:s', $addingFiveMinutes);
		global $wpdb; 
		$table_name_trigger = $wpdb->prefix . 'wacr_user_otp';
		$charset_collate = $wpdb->get_charset_collate();
		if(strtolower($wpdb->get_var( "show tables like '$table_name_trigger'" )) != strtolower($table_name_trigger) ) 
		  {
			$tbl = "CREATE TABLE $table_name_trigger (
				`id`                  BIGINT(20) NOT NULL auto_increment,
				`wacr_user_id`         VARCHAR(100) NULL DEFAULT NULL,
				`wacr_user_otp`      VARCHAR(100) NULL DEFAULT NULL,
				`wacr_user_expiry`  VARCHAR(100) NULL DEFAULT NULL,
				`wacr_status`  VARCHAR(100) NULL DEFAULT NULL,
				`wacr_created_date` VARCHAR(100) NULL DEFAULT NULL,
				PRIMARY KEY (`id`)
				)$charset_collate;";
				include_once ABSPATH . '/wp-admin/includes/upgrade.php';
				dbDelta($tbl);
			}
			$wpdb->query("ALTER TABLE " . $table_name_trigger . " MODIFY wacr_created_date VARCHAR(100)");
			$now_date = current_datetime()->format('Y-m-d H:i:s');

            $wpdb->insert($table_name_trigger, array(
				'wacr_user_id' => $customer_id,
				'wacr_user_otp' => $wacr_opt_in_code,
				'wacr_user_expiry' => $customer_wa_otp_expiry,
				'wacr_created_date' => $now_date,
				'wacr_status' => 'not_verify'
				));
				//send otp for login
			$mobile_number_id = wp_unslash(get_option('wacr_whatsapp_business_mobile_number_id'));
			$reg_otp_template = $otp;
			//get template language
			require_once plugin_dir_path(__DIR__ ) . 'admin/partials/wacr-api-functions.php';
			$response = new Wacr_API_functions();
			$language = $response->get_template_language($reg_otp_template);
			
			$bearer_token = wp_unslash(get_option('wacr_bearer_token_whatsapp'));
			$mobile_number = wp_unslash(get_option('wacr_mobile_number_whatsapp'));
			$customer_wa_number = str_replace( array( '\'', '"',
			',' , ';', '<', '>', '+', '-', '@' ), '', $customer_wa_number);
			$curl = curl_init();
			$json_array = array (
				"messaging_product" => "whatsapp","to" => $customer_wa_number,"type" => "template","template" => 
				array ("name" => "$reg_otp_template","language" => 
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
								"text" => "$wacr_opt_in_code"), //Order id
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
	


	public function wacr_order_on_whatsapp($add_to_cart_html, $product, $args){
		

		$productLink = get_permalink( $product->get_id() );
		$productName = $product->get_title();
		$adminNumber = get_option('wacr_order_on_whatsapp_num');
		$text = "Hi, I would like to purchase this product. \n $productName: $productLink";
		$whatsAppLink ="https://api.whatsapp.com/send?phone=$adminNumber&text=$text";

		$after = "<br><a href='$whatsAppLink'><button>Buy On WhatsApp</button></a>"; // Add some text or HTML here as well
		return $add_to_cart_html . $after;
	}
	
	public function wacr_order_on_whatsapp_single_product(){
	
		global $post;
		$product = wc_get_product( $post->ID );
		$productLink = get_permalink( $product->get_id() );
		$productName = $product->get_title();
		$adminNumber = get_option('wacr_order_on_whatsapp_num');
		$text = "Hi, I would like to purchase this product. \n $productName: $productLink";
		$whatsAppLink ="https://api.whatsapp.com/send?phone=$adminNumber&text=$text";

		echo "<br><br> <a class='button' href='$whatsAppLink'> Buy on WhatsApp </a> 

		";
	}
}
