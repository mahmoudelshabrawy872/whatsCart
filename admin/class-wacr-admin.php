<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://techspawn.com/
 * @since      1.0.0
 *
 * @package    Wacr
 * @subpackage Wacr/admin
 */

/**

 * @package    Wacr
 * @subpackage Wacr/admin
 * @author     TechSpawn <support@techspawn.com>
 */
class Wacr_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	
	public function __construct( $plugin_name, $version ) {

		

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		require_once plugin_dir_path( __FILE__ ) . 'partials/wacr-api-functions.php';
		require_once plugin_dir_path( __FILE__ ) . 'partials/wacr-cron-update.php';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	
	public function enqueue_styles() {
	if(isset($_GET['page']) ):
		if($_GET['page'] == "cart-abadonment-notifier" || $_GET['page'] == "wacr_abandoned_carts" || $_GET['page'] == "cart-abadonment-notifier"):
				wp_enqueue_style($this->plugin_name . 'css-datatable', plugin_dir_url(__FILE__) . 'css/jquery.dataTables.min.css', array(), $this->version, 'all');   
				wp_enqueue_style($this->plugin_name . 'css-btn-datatable', plugin_dir_url(__FILE__) . 'css/buttons.dataTables.min.css', array(), $this->version, 'all');   
				wp_enqueue_style($this->plugin_name . '-manage-templates', plugin_dir_url(__FILE__) . 'css/wacr-admin-central.css', array(), $this->version, 'all');
				wp_enqueue_style($this->plugin_name . 'css-bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.css', array(), $this->version, 'all');  
		endif;
	endif;
	if(isset($_GET['page']) ):
		if($_GET['page'] == "wacr_template_library"):
			wp_enqueue_style($this->plugin_name . '_template_library', plugin_dir_url(__FILE__) . 'css/wacr-template-library.css', array(), $this->version, 'all'); 
		endif;
	endif;

	if(isset($_GET['page']) ):
		if($_GET['page'] == "wacr_abandoned_carts"):
			wp_enqueue_style($this->plugin_name . '_abandoned_carts', plugin_dir_url(__FILE__) . 'css/wacr-abandoned.css', array(), $this->version, 'all'); 
		endif;
	endif;


	wp_enqueue_style($this->plugin_name . '_chosen_css', plugin_dir_url(__FILE__) . 'css/chosen.min.css', array(), $this->version, 'all'); 
	
	wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wacr-admin.css', array(), $this->version, 'all' ); 
	
	wp_enqueue_style($this->plugin_name . '-fontawesome', plugin_dir_url(__FILE__) . 'css/fontawesome.min.css', array(), $this->version, 'all');
	  
	  
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	
	public function enqueue_scripts() {
		

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wacr-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('wacr_ajax_js');
		wp_localize_script('wacr_ajax_js', 'wacr_admin_js_data', array('ajax_url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('ajax_nonce_can')));

		wp_enqueue_script( $this->plugin_name.'_trigger_dynamics', plugin_dir_url( __FILE__ ) . 'js/wacr-trigger-dynamic.js', array( 'jquery' ), $this->version, true );

		wp_enqueue_script('chosen', plugin_dir_url(__FILE__) . 'js/chosen.jquery.min.js', array('jquery'), $this->version . rand(), false);
		if(isset($_GET['page']) ):
		if($_GET['page'] == "cart-abadonment-notifier" || $_GET['page'] == "wacr_abandoned_carts" || $_GET['page'] == "cart-abadonment-notifier"):

			wp_enqueue_script( 'datatable', plugin_dir_url(__FILE__) . 'js/jquery.dataTables.min.js', array( 'jquery' ), '1.10.21', true );
			wp_enqueue_script( $this->plugin_name.'_manage_templates', plugin_dir_url( __FILE__ ) . 'js/wacr-manage-templates.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name.'_update_templates', plugin_dir_url( __FILE__ ) . 'js/wacr-update-template.js', array( 'jquery' ), $this->version, false );
			endif;
			if($_GET['page'] == "wacr_abandoned_carts"):
				wp_enqueue_script( $this->plugin_name.'_abandnoned_cart', plugin_dir_url( __FILE__ ) . 'js/wacr-abandoned-carts.js', array( 'jquery' ), $this->version, false );
			endif;
		endif;
		wp_enqueue_script('fontawesome', plugin_dir_url(__FILE__) . 'js/fontawsome-min.js', array('jquery'), $this->version . rand(), false);
			
		wp_enqueue_script('sweetalert', plugin_dir_url(__FILE__) . 'js/sweetalert2@10.js', array('jquery'), $this->version, true);
		global $wacr_op;
		$response = Wacr_API_functions::wacr_get_wp_templates();
		$encode_response = json_decode($response);
		$inc = 1;
		if(!empty($encode_response->data)){
		foreach($encode_response->data as $dkey => $dval )
		{
			$wacr_op[] = $dval->name;
			$inc++;
		} }
		$json_global_template = json_encode($wacr_op);
		
		wp_localize_script($this->plugin_name, 'wacr_ajax', 
		array("ajaxurl" => admin_url("admin-ajax.php"),
		"wacr_op" => $json_global_template,
		'check_nonce' => wp_create_nonce('mi-nonce')));

	}

	
	public function wacr_register_menu_page()
	{
	  add_menu_page(
		esc_html('WhatsCart for WooCommerce', $this->plugin_name . ''),
		esc_html('WhatsCart for WooCommerce', $this->plugin_name . ''),
		'manage_options',
		'cart-abadonment-notifier',
		array($this, $this->plugin_name . '_menu_page'),
		'dashicons-whatsapp',
		'65'
	  );
	  add_submenu_page(
        'cart-abadonment-notifier',
        esc_html('Settings', 'wacr'),
        esc_html('Settings', 'wacr'),
        'manage_options',
        'cart-abadonment-notifier'
      );
		if (get_option('wacr_license') == '' || get_option('wacr_license') == 'invalid') {
			add_submenu_page(
				'cart-abadonment-notifier',
				esc_html('Abandoned Carts', 'wacr'), 
				esc_html('Abandoned Carts', 'wacr'),
				'manage_options',
				'cart-abadonment-notifier'
			);
			add_submenu_page(
				'cart-abadonment-notifier',
				esc_html('Order Notification', 'wacr'), 
				esc_html('Order Notification', 'wacr'),
				'manage_options',
				'cart-abadonment-notifier'
			);
			

			add_submenu_page(
				'cart-abadonment-notifier',
				esc_html('Help', 'wacr'), 
				esc_html('Help', 'wacr'),
				'manage_options',
				'cart-abadonment-notifier'
			);
			
		}
		else
		{
			add_submenu_page(
				'cart-abadonment-notifier',
				esc_html('Abandoned Carts', 'wacr'), 
				esc_html('Abandoned Carts', 'wacr'),
				'manage_options',
				$this->plugin_name .'_abandoned_carts',
				array($this, $this->plugin_name . '_abandoned_carts'),
			);
			add_submenu_page(
				'cart-abadonment-notifier',
				esc_html('Order Notification', 'wacr'), 
				esc_html('Order Notification', 'wacr'),
				'manage_options',
				$this->plugin_name .'_order_notification',
				array($this, $this->plugin_name . '_order_notification'),
			);
			add_submenu_page(
				'cart-abadonment-notifier',
				esc_html('Template Library', 'wacr'), 
				esc_html('Template Library', 'wacr'),
				'manage_options',
				$this->plugin_name .'_template_library',
				array($this, $this->plugin_name . '_template_library'),
			);
			add_submenu_page(
				'cart-abadonment-notifier',
				esc_html('Compatibility', 'wacr'), 
				esc_html('Compatibility', 'wacr'),
				'manage_options',
				$this->plugin_name .'_compatibility',
				array($this, $this->plugin_name . '_compatibility'),
			);
			add_submenu_page(
				'cart-abadonment-notifier',
				esc_html('Help', 'wacr'), 
				esc_html('Help', 'wacr'),
				'manage_options',
				$this->plugin_name .'_documentation',
				array($this, $this->plugin_name . '_documentation'),
			);
			
			add_submenu_page(
				'wacr_documentation',
				esc_html('Edit Template', 'wacr'), 
				esc_html('Edit Template', 'wacr'),
				'manage_options',
				$this->plugin_name .'_edit_templates',
				array($this, $this->plugin_name . '_edit_templates'),
			);

			add_submenu_page(
				'wacr_documentation',
				esc_html('Edit Template', 'wacr'), 
				esc_html('Edit Template', 'wacr'),
				'manage_options',
				$this->plugin_name .'_create_templates',
				array($this, $this->plugin_name . '_create_templates'),
			);
		}

	}
	public function wacr_template_library()
	{
   	 require plugin_dir_path(__FILE__) . 'partials/wacr-template-library.php';
 	}
	public function wacr_compatibility()
	{
   	 require plugin_dir_path(__FILE__) . 'partials/wacr-compatibility.php';
 	}
	public function wacr_documentation()
	{
    require plugin_dir_path(__FILE__) . 'partials/wacr-documentation.php';
 	}

	public function wacr_edit_templates()
	{
	 require plugin_dir_path(__FILE__) . 'partials/wacr-edit-templates.php';
	}
	
	public function wacr_create_templates()
	{
	 require plugin_dir_path(__FILE__) . 'partials/wacr-create-templates.php';
	}

	public function wacr_abandoned_carts()
	{
    require plugin_dir_path(__FILE__) . 'partials/wacr-abandoned_carts.php';
 	}
	
	public function wacr_register_setting()
	{
		// general admin settings
		add_settings_section(  
			$this->plugin_name . '_general_section',
			esc_html('Admin User Details', 'wacr'),
			array($this, $this->plugin_name . '_admin_section_callback'),
			$this->plugin_name . '_general'
		);
		add_settings_field(
			$this->plugin_name . '_whatsapp_api_connection_status', 
			esc_html('WhatsApp API Connection Status', 'wacr'),
			array($this, $this->plugin_name . '_whatsapp_api_connection_status_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section', 
			array( 
				$this->plugin_name . '_whatsapp_api_connection_status' 
			)  
		); 

		add_settings_field(
			$this->plugin_name . '_bearer_token_whatsapp', 
			esc_html('WhatsApp Bearer Token', 'wacr'),
			array($this, $this->plugin_name . '_bearer_token_whatsapp_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section', 
			array( 
				$this->plugin_name . '_bearer_token_whatsapp' 
			)  
		); 

		

		add_settings_field(
			$this->plugin_name . '_mobile_number_whatsapp', 
			esc_html('WhatsApp Mobile Number', 'wacr'),
			array($this, $this->plugin_name . '_mobile_number_whatsapp_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section', 
			array(
				$this->plugin_name . '_mobile_number_whatsapp' 
			)  
		); 
		add_settings_field(
			$this->plugin_name . '_whatsapp_business_id', 
			esc_html('WhatsApp Business Account ID', 'wacr'),
			array($this, $this->plugin_name . '_whatsapp_business_id_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section', 
			array(
				$this->plugin_name . '_whatsapp_business_id' 
			)  
		);
		add_settings_field(
			$this->plugin_name . '_whatsapp_business_mobile_number_id', 
			esc_html('WhatsApp Phone number ID', 'wacr'),
			array($this, $this->plugin_name . '_whatsapp_business_mobile_number_id_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section', 
			array(
				$this->plugin_name . '_whatsapp_business_mobile_number_id'
			)  
		);

		add_settings_field(
			$this->plugin_name . '_disable_abandoned', 
			esc_html('Disable Abandoned Messages', 'wacr'),
			array($this, $this->plugin_name . '_disable_abandoned_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section', 
			array(
				$this->plugin_name . '_disable_abandoned_status'
			)  
		);

		add_settings_field(
			$this->plugin_name . '_time_interval_for_first', 
			esc_html('Abandoned Cart Time', 'wacr'),
			array($this, $this->plugin_name . '_time_interval_for_first_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section', 
			array(
				$this->plugin_name . '_time_interval_for_first' 
			)  
		);

		add_settings_field(
			$this->plugin_name . '_enable_cooldown', 
			esc_html('Enable DND', 'wacr'),
			array($this, $this->plugin_name . '_enable_cooldown_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section', 
			array(
				$this->plugin_name . '_enable_cooldown', $this->plugin_name . '_enable_cooldown_from', $this->plugin_name . '_enable_cooldown_to'
			)  
		);
		
		
		add_settings_field(
			$this->plugin_name . '_enable_clicktochat', 
			esc_html('Enable Chat Support Widget', 'wacr'),
			array($this, $this->plugin_name . '_enable_clicktochat_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section', 
			array(
				$this->plugin_name . '_enable_clicktochat', $this->plugin_name . '_ctc_mobile_number'
			)  
		);


		add_settings_field(
			$this->plugin_name . '_daily_message_limit_whatsapp', 
			esc_html('Daily Message Limit', 'wacr'),
			array($this, $this->plugin_name . '_daily_message_limit_whatsapp_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section', 
			array(
				$this->plugin_name . '_daily_message_limit_whatsapp' 
			)  
		);
		
		add_settings_field(
			$this->plugin_name, 
			wp_kses_post('<div class="wacr-form-divider"> OTP Settings <hr></div>', 'wacr'),
			array($this, $this->plugin_name . '_option_head_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section',   
			array('label_for' => $this->plugin_name . '_general')
		); 
		add_settings_field(
			$this->plugin_name . '_enable_otp_register', 
			esc_html('Enable Opt-in for Register', 'wacr'),
			array($this, $this->plugin_name . '_enable_otp_register_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section', 
			array(
				$this->plugin_name . '_enable_otp_register'
			)  
		);

		add_settings_field(
			$this->plugin_name . '_enable_otp_login', 
			esc_html('Enable Opt-in for Login', 'wacr'),
			array($this, $this->plugin_name . '_enable_otp_login_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section', 
			array(
				$this->plugin_name . '_enable_otp_login'
			)  
		);
		add_settings_field(
			$this->plugin_name . '_reg_without_pswd', 
			esc_html('Login with OTP', 'wacr'),
			array($this, $this->plugin_name . '_reg_without_pswd_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section', 
			array(
				$this->plugin_name . '_reg_without_pswd'
			)  
		);

		add_settings_field(
			$this->plugin_name . '_otp_template_global', 
			esc_html('OTP Template', 'wacr'),
			array($this, $this->plugin_name . '_otp_template_global_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section', 
			array(
				$this->plugin_name . '_otp_template_global'
			)  
		);

		add_settings_field(
			$this->plugin_name . '_timer_for_otp', 
			esc_html('OTP Expire Time', 'wacr'),
			array($this, $this->plugin_name . '_timer_for_otp_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section', 
			array(
				$this->plugin_name . '_timer_for_otp'
			)  
		);

		add_settings_field(
			$this->plugin_name . '_otp_length_and_type', 
			esc_html('OTP Settings', 'wacr'),
			array($this, $this->plugin_name . '_otp_length_and_type_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section', 
			array(
				$this->plugin_name . '_otp_length', $this->plugin_name . '_otp_type'
			)  
		);

		add_settings_field(
			$this->plugin_name.'_other_heading', 
			wp_kses_post('<div class="wacr-form-divider"> Others <hr></div>', 'wacr'),
			array($this, $this->plugin_name . '_option_head_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section',   
			array('label_for' => $this->plugin_name . '_general')
		); 

		add_settings_field(
			$this->plugin_name . '_order_on_whatsapp', 
			esc_html('Order On WhatsApp', 'wacr'),
			array($this, $this->plugin_name . '_order_on_whatsapp_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section', 
			array(
				$this->plugin_name . '_order_on_whatsapp', $this->plugin_name . '_order_on_whatsapp_num'
			)  
		);

		add_settings_field(
			$this->plugin_name . '_default_cron_time', 
			esc_html('Default Cron Time', 'wacr'),
			array($this, $this->plugin_name . '_default_cron_time_cb'),
			$this->plugin_name . '_general',
			$this->plugin_name . '_general_section', 
			array(
				$this->plugin_name . '_default_cron_time')  
		);


		//cron and template settings
		add_settings_section(  
			$this->plugin_name . '_notification_section',
			esc_html('Abandoned Time Settings', 'wacr'),
			array($this, $this->plugin_name . '_admin_section_callback'),
			$this->plugin_name . '_notification'
		);
		
		add_settings_field(
			$this->plugin_name . '_heading_for_cron_page', 
			esc_html('<div class="wacr-heading2"> Template Settings </div>', 'wacr'),
			array($this, $this->plugin_name . '_heading_for_cron_page'),
			$this->plugin_name . '_notification',
			$this->plugin_name . '_notification_section', 
			array('label_for' => $this->plugin_name . '_heading_for_cron_page')     
		);

		

		// timing options settings

		add_settings_section(  
			$this->plugin_name . '_timing_section',
			esc_html('Set Time Intervals', 'wacr'),
			array($this, $this->plugin_name . '_timing_section_callback'),
			$this->plugin_name . '_timing'
		);
		add_settings_field(
			$this->plugin_name . '_first_timing', 
			esc_html('First Message', 'wacr'),
			array($this, $this->plugin_name . '_first_timing_cb'),
			$this->plugin_name . '_notification',
			$this->plugin_name . '_notification_section', 
			array(
				$this->plugin_name . '_first_timing_template', $this->plugin_name . '_first_timing_1',  $this->plugin_name . '_first_timing_2'
			)  
		);
		add_settings_field(
			$this->plugin_name . '_second_timing', 
			esc_html('Second Message', 'wacr'),
			array($this, $this->plugin_name . '_second_timing_cb'),
			$this->plugin_name . '_notification',
			$this->plugin_name . '_notification_section', 
			array(
				$this->plugin_name . '_second_timing_template', $this->plugin_name . '_second_timing_1',  $this->plugin_name . '_second_timing_2'
			)  
		);
		add_settings_field(
			$this->plugin_name . '_third_timing', 
			esc_html('Third Message', 'wacr'),
			array($this, $this->plugin_name . '_third_timing_cb'),
			$this->plugin_name . '_notification',
			$this->plugin_name . '_notification_section', 
			array(
				$this->plugin_name . '_third_timing_template', $this->plugin_name . '_third_timing_1',  $this->plugin_name . '_third_timing_2'
			)  
		);
		add_settings_field(
			$this->plugin_name . '_fourth_timing', 
			esc_html('Fourth Message', 'wacr'),
			array($this, $this->plugin_name . '_fourth_timing_cb'),
			$this->plugin_name . '_notification',
			$this->plugin_name . '_notification_section', 
			array(
				$this->plugin_name . '_fourth_timing_template', $this->plugin_name . '_fourth_timing_1',  $this->plugin_name . '_fourth_timing_2'
			)  
		);
		add_settings_field(
			$this->plugin_name . '_fifth_timing', 
			esc_html('Fifth Message', 'wacr'),
			array($this, $this->plugin_name . '_fifth_timing_cb'),
			$this->plugin_name . '_notification',
			$this->plugin_name . '_notification_section', 
			array(
				$this->plugin_name . '_fifth_timing_template', $this->plugin_name . '_fifth_timing_1',  $this->plugin_name . '_fifth_timing_2'
			)  
		);

		//Compatibility settings
		add_settings_section(  
			$this->plugin_name . '_compatibility_section',
			esc_html('Booking Compatibility', 'wacr'),
			array($this, $this->plugin_name . '_compatibility_callback'),
			$this->plugin_name . '_compatibility'
		);

		// Booking Notify
		add_settings_field(
			$this->plugin_name . '_booking_notify', 
			esc_html('Booking Notify', 'wacr'),
			array($this, $this->plugin_name . '_booking_notify_cb'),
			$this->plugin_name . '_compatibility',
			$this->plugin_name . '_compatibility_section', 
			array( 
				$this->plugin_name . '_booking_notify', $this->plugin_name . '_booking_notify_temp' 
			)  
		); 

		// Booking Verify
		add_settings_field(
			$this->plugin_name . '_booking_verify', 
			esc_html('Booking Verify', 'wacr'),
			array($this, $this->plugin_name . '_booking_verify_cb'),
			$this->plugin_name . '_compatibility',
			$this->plugin_name . '_compatibility_section', 
			array( 
				$this->plugin_name . '_booking_verify', $this->plugin_name . '_booking_verify_temp' 
			)  
		); 
		
		// order success notification settings

		add_settings_section(  
			$this->plugin_name . '_order_notification_section',
			esc_html('Order Notification', 'wacr'),
			array($this, $this->plugin_name . '_admin_section_callback'),
			$this->plugin_name . '_order_notification'
		);
		// Order Complete
		add_settings_field(
			$this->plugin_name . '_order_notification_status', 
			esc_html('Enable Order Success Notification', 'wacr'),
			array($this, $this->plugin_name . '_order_notification_status_cb'),
			$this->plugin_name . '_order_notification',
			$this->plugin_name . '_order_notification_section', 
			array( 
				$this->plugin_name . '_order_notification_status', $this->plugin_name . '_order_notification_template' 
			)  
		); 
		// order notifications for admin
		add_settings_field(
			$this->plugin_name . '_admin_order_notification_status', 
			esc_html('Admin Order Notification', 'wacr'),
			array($this, $this->plugin_name . '_admin_order_notification_status_cb'),
			$this->plugin_name . '_order_notification',
			$this->plugin_name . '_order_notification_section', 
			array( 
				$this->plugin_name . '_admin_order_notification_status', $this->plugin_name . '_admin_order_notification_template', $this->plugin_name . '_admin_order_notification_mobile'
			)  
		); 

		//Order Update
		add_settings_field(
			$this->plugin_name . '_order_update_notification', 
			esc_html('Order Update Notification', 'wacr'),
			array($this, $this->plugin_name . '_order_update_notification_cb'),
			$this->plugin_name . '_order_notification',
			$this->plugin_name . '_order_notification_section', 
			array( 
				$this->plugin_name . '_order_update_notification', $this->plugin_name . '_order_update_temp' 
			)  
		); 
		
		//Order Verify
		add_settings_field(
			$this->plugin_name . '_order_confirmation', 
			esc_html('Verify COD Orders', 'wacr'),
			array($this, $this->plugin_name . '_order_confirmation_cb'),
			$this->plugin_name . '_order_notification',
			$this->plugin_name . '_order_notification_section', 
			array( 
				$this->plugin_name . '_order_confirmation', $this->plugin_name . '_order_confirmation_template'
			)  
		); 

		// general settings registers here

		register_setting($this->plugin_name . '_general',$this->plugin_name . '_bearer_token_whatsapp', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_mobile_number_whatsapp', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_whatsapp_business_id', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_whatsapp_business_mobile_number_id', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_disable_abandoned_status', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_time_interval_for_first', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_enable_cooldown', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_enable_cooldown_to', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_enable_cooldown_from', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_enable_clicktochat', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_daily_message_limit_whatsapp', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_enable_otp_register', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_template_otp_register', 'esc_attr');

		register_setting($this->plugin_name . '_general',$this->plugin_name . '_login_without_pswd', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_reg_without_pswd', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_timer_for_otp', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_otp_template_global', 'esc_attr');

		register_setting($this->plugin_name . '_general',$this->plugin_name . '_enable_otp_login', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_template_otp_login', 'esc_attr');
		
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_ctc_mobile_number', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_order_on_whatsapp', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_order_on_whatsapp_num', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_default_cron_time', 'esc_attr');


		//otp type length
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_otp_length', 'esc_attr');
		register_setting($this->plugin_name . '_general',$this->plugin_name . '_otp_type', 'esc_attr');

		// Bookupp compatibility
		register_setting($this->plugin_name . '_compatibility',$this->plugin_name . '_booking_notify', 'esc_attr');
		register_setting($this->plugin_name . '_compatibility',$this->plugin_name . '_booking_notify_temp', 'esc_attr');

		register_setting($this->plugin_name . '_compatibility',$this->plugin_name . '_booking_verify', 'esc_attr');
		register_setting($this->plugin_name . '_compatibility',$this->plugin_name . '_booking_verify_temp', 'esc_attr');


		// notification settings registers here
		
		register_setting($this->plugin_name . '_notification',$this->plugin_name . '_first_timing_template', 'esc_attr');
		register_setting($this->plugin_name . '_notification',$this->plugin_name . '_first_timing_1', 'esc_attr');
		register_setting($this->plugin_name . '_notification',$this->plugin_name . '_first_timing_2', 'esc_attr');
		register_setting($this->plugin_name . '_notification',$this->plugin_name . '_second_timing_template', 'esc_attr');
		register_setting($this->plugin_name . '_notification',$this->plugin_name . '_second_timing_1', 'esc_attr');
		register_setting($this->plugin_name . '_notification',$this->plugin_name . '_second_timing_2', 'esc_attr');
		register_setting($this->plugin_name . '_notification',$this->plugin_name . '_third_timing_template', 'esc_attr');
		register_setting($this->plugin_name . '_notification',$this->plugin_name . '_third_timing_1', 'esc_attr');
		register_setting($this->plugin_name . '_notification',$this->plugin_name . '_third_timing_2', 'esc_attr');
		register_setting($this->plugin_name . '_notification',$this->plugin_name . '_fourth_timing_template', 'esc_attr');
		register_setting($this->plugin_name . '_notification',$this->plugin_name . '_fourth_timing_1', 'esc_attr');
		register_setting($this->plugin_name . '_notification',$this->plugin_name . '_fourth_timing_2', 'esc_attr');
		register_setting($this->plugin_name . '_notification',$this->plugin_name . '_fifth_timing_template', 'esc_attr');
		register_setting($this->plugin_name . '_notification',$this->plugin_name . '_fifth_timing_1', 'esc_attr');
		register_setting($this->plugin_name . '_notification',$this->plugin_name . '_fifth_timing_2', 'esc_attr');

		// order success notification settings registers here
		register_setting($this->plugin_name . '_order_notification',$this->plugin_name . '_order_notification_status', 'esc_attr');
		register_setting($this->plugin_name . '_order_notification',$this->plugin_name . '_order_notification_template', 'esc_attr');

		// order success admin notification settings registers here
		register_setting($this->plugin_name . '_order_notification',$this->plugin_name . '_admin_order_notification_status', 'esc_attr');
		register_setting($this->plugin_name . '_order_notification',$this->plugin_name . '_admin_order_notification_template', 'esc_attr');
		register_setting($this->plugin_name . '_order_notification',$this->plugin_name . '_admin_order_notification_mobile', 'esc_attr');

		register_setting($this->plugin_name . '_order_notification',$this->plugin_name . '_order_update_notification', 'esc_attr');
		register_setting($this->plugin_name . '_order_notification',$this->plugin_name . '_order_update_temp', 'esc_attr');

		register_setting($this->plugin_name . '_order_notification',$this->plugin_name . '_order_confirmation', 'esc_attr');
		register_setting($this->plugin_name . '_order_notification',$this->plugin_name . '_order_confirmation_template', 'esc_attr');
	}

	public function wacr_compatibility_callback(){
		return;
	}
	public function wacr_heading_for_cron_page(){
		return;
	}
	
	public function wacr_admin_section_callback(){
		return;
	}
	public function wacr_option_head_cb(){
		return;
	}

	public function wacr_whatsapp_api_connection_status_cb(){
		$status = get_option('wacr_whatsapp_connection_status');
		if($status == "connected"){
			?>
			<span class='wacr-connected'>
			<?php esc_html_e("Connected", 'wacr');
			?>
			</span>
			<?php
		}else{
			?>
			<span class='wacr-disconnected'>
			<?php esc_html_e("Disconnected", 'wacr');
			?>
			</span>
			<?php
		}
	}

	public function wacr_bearer_token_whatsapp_cb($args) {  
		$option = get_option($args[0]);?>
		
		<textarea rows="1" cols="50" type="password" pattern="[A-Za-z0-9]" title="<?php esc_html_e('1000', 'wacr'); ?>" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" value="<?php esc_html_e($option, 'wacr'); ?>"><?php esc_html_e($option, 'wacr'); ?></textarea> 
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('Bearer Token to be used for all other WhatsApp Business API calls, you can get it from Meta WhatsApp Manager.', 'wacr');
			?>
		</label>
		<?php
	}
	
	
	public function wacr_mobile_number_whatsapp_cb($args) {  
		$option = get_option($args[0]);
		?>
		<input type="number" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" value="<?php esc_html_e($option, 'wacr'); ?>" placeholder="<?php esc_html_e('123-456-7890', 'wacr');?>"/>
		<label class="wacr_admin_descriptions">
			<?php
		echo esc_html('Enter your WhatsApp mobile number for your business','wacr');
		?></label>
		<?php
	}

	
	public function wacr_whatsapp_business_id_cb($args) {  
		$option = get_option($args[0]);
		?>
		<input type="number" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" value="<?php esc_html_e($option, 'wacr'); ?>" placeholder="<?php esc_html_e('123456789000', 'wacr');?>"/>
		<label class="wacr_admin_descriptions">
			<?php
		echo esc_html('Enter your unique WhatsApp Business ID', 'wacr');
		?>
		</label>
		<?php
    
	}
	
	public function wacr_whatsapp_business_mobile_number_id_cb($args) {  
		$option = get_option($args[0]);
		?>
		<input type="number" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" value="<?php esc_html_e($option, 'wacr'); ?>" placeholder="<?php esc_html_e('Enter whatsapp number id', 'wacr');?>"/>
		<label class="wacr_admin_descriptions">
		<?php
		echo esc_html('Enter your WhatsApp Mobile Number ID','wacr');
		?>
		 </label>
		 <?php
	}

	public function wacr_time_interval_for_first_cb($args) {  
		$option = get_option($args[0]);
		?>
		<input type="number" min="1" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" value="<?php if($option == '' || $option == '0'){esc_html_e('120', 'wacr');}else{esc_html_e($option, 'wacr');} ?>" placeholder="<?php esc_html_e('1234567890', 'wacr');?>"/> <?php esc_html_e('Minutes', 'wacr');?>
		
		<label class="wacr_admin_descriptions">
			<?php echo wp_kses('If user leaves the cart without purchasing or checkout, then after <b>Abandoned Cart Time</b>, the cart will be detected as Abandoned. For example: If we set Abandoned Cart Time to 2 minutes, and user leaves the cart without checkout, then after 2 Minutes we will consider the cart as abandoned. Now we will use dynamic triggers to send notifications with desired timing and message template.', 'wacr');?>
		</label>
		
		<?php
	}

	public function wacr_daily_message_limit_whatsapp_cb($args) {  
		$option = get_option($args[0]);
		if($option == ''){
			update_option('wacr_daily_message_limit_whatsapp', '1000');
		}
		?>
		<input type="number" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" value="<?php if($option == ''){ esc_html_e("1000", 'wacr');}else{esc_html_e($option, 'wacr');} ?>" placeholder="<?php esc_html_e('1000', 'wacr'); ?>"/> <?php esc_html_e('Messages', 'wacr'); ?>
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('Daily message quota limit for sending messages. Default value is 1000/day.', 'wacr');
			?>
		</label>
			<?php
	}
	public function wacr_enable_otp_register_cb($args) {  
		$wacr_opt_register = get_option($args[0]);
		?>
		<label class="switch_admin">
			<input type="checkbox" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" <?php echo ($wacr_opt_register == 'on') ? 'checked="checked"' : ''; ?>>
			<span class="slider_admin round"></span>
		</label>
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('Enable this feature to verify users using OTP sent to WhatsApp.', 'wacr');?>
		</label>
		

			<?php
	}
	public function wacr_enable_otp_login_cb($args) {  
		$wacr_opt_login = get_option($args[0]);
		?>
		<label class="switch_admin">
			<input type="checkbox" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" <?php echo ($wacr_opt_login == 'on') ? 'checked="checked"' : ''; ?>>
			<span class="slider_admin round"></span>
		</label>
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('Enable this feature to verify users during login using OTP sent to WhatsApp.', 'wacr');?>
		</label>
			<?php
	}
	
	public function wacr_reg_without_pswd_cb($args) {  
		$option = get_option($args[0]);
		?>
		 
			<label class="switch_admin">
				<input type="checkbox" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" <?php echo ($option == 'on') ? 'checked="checked"' : ''; ?>>
				<span class="slider_admin round"></span>
			</label>
		
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('User can login without password, by using verification code sent to their Whatsapp.', 'wacr');?>
		</label>
			<?php
	}

	public function wacr_otp_template_global_cb($args) {  
		$option = get_option($args[0]);
		
		 	$wacrOrderTemplate = get_option($args[0]);
		$response = Wacr_API_functions::wacr_get_wp_templates();
		$encode_response = json_decode($response);
		?>
		<select id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>"> 
		<option value="-1"> <?php esc_html_e("-- Select Template --", 'wacr'); ?> </option>
			<?php
				foreach($encode_response->data as $dkey => $dval )
					{ ?>		
						<option value="<?php esc_html_e($dval->name); ?>" <?php if($dval->name == $wacrOrderTemplate)
						echo esc_html("selected",'wacr');
						?>><?php esc_html_e($dval->name); ?> </option>

			<?php } ?>
		</select>	
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('Select the template for sending OTPs.', 'wacr');?>
		</label>
			<?php
	}

	public function wacr_timer_for_otp_cb($args) {  
		$option = get_option($args[0]);
		?>
		 
		 <input type="number" min="1" oninput="this.value = this.value.replace(/\D+/g, '')" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" value="<?php if($option == ''){ esc_html_e("1", 'wacr');}else{esc_html_e($option, 'wacr');} ?>"  ?>
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('Set OTP expiration time, the default time is 1 minute.', 'wacr');
			?>
		</label>
		
			<?php
	}
	//otp type and length
	public function wacr_otp_length_and_type_cb($args) {  
		$option = get_option($args[0]);
		$option1 = get_option($args[1]);
		?>
		<div class="wacr_otp_ltf">
			<div class="wacr_otp_ltf1">
				<span class="wacr_setting_label">
					<?php esc_attr_e("OTP Length: ", 'wacr'); ?>
				</span>
					<input type="number" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" value="<?php esc_html_e($option, 'wacr'); ?>" placeholder="<?php esc_html_e('For example: 6', 'wacr');?>"/>
					<br>
					
			</div>
			<label class="wacr_admin_descriptions">
			<?php echo esc_html('Set otp length, the recommended length is 6 characters.', 'wacr');?>
			</label>
			<div class="wacr_otp_ltf1">
				<span class="wacr_setting_label">
					<?php esc_attr_e("OTP Type: ", 'wacr'); ?>
				</span>
				<select id="<?php echo esc_attr($args[1]); ?>" name="<?php echo esc_attr($args[1]); ?>" value="<?php echo esc_attr($option1); ?>"> 
					<option <?php if($option1 == ''){ echo "selected='selected'";}?> value="-1"><?php esc_html_e("-- Select --", 'wacr'); ?></option>
					<option <?php if($option1 == 'num'){ echo "selected='selected'";}?> value="num"><?php esc_html_e("Numeric", 'wacr'); ?></option>
					<option <?php if($option1 == 'alphanum'){ echo "selected='selected'";}?>  value="alphanum"><?php esc_html_e("Alphanumeric", 'wacr'); ?></option>
					
				</select>
			</div>
			<label class="wacr_admin_descriptions">
			<?php echo esc_html('You can set OTP Type here. For example: numeric is 123456, and Alphanumeric is a1b2c3.', 'wacr');?>
			</label>	
		</div>
			<?php
	}	

	public function wacr_default_cron_time_cb($args){
		$option = get_option($args[0]);
		if($option == ''){
			$option = 60;
		}
		?>
		<input type="tel" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" value="<?php esc_html_e($option, 'wacr'); ?>" placeholder="<?php esc_html_e('Enter WP Cron Time', 'wacr');?>"/> <span class=""> <?php esc_html_e("Minutes", 'wacr'); ?></span>
		
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('WordPress cron time is used for background processes, by default is set to 60 minutes.', 'wacr');?>
		</label>
			<?php
	}
	
	public function wacr_order_on_whatsapp_cb($args) {  
		$wacr_opt_login = get_option($args[0]);
		$option = get_option($args[1]);
		?>
		<label class="switch_admin">
			<input type="checkbox" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" <?php echo ($wacr_opt_login == 'on') ? 'checked="checked"' : ''; ?>>
			<span class="slider_admin round"></span>
			
		</label>
		<br>
		<input type="tel" id="<?php echo esc_attr($args[1]); ?>" name="<?php echo esc_attr($args[1]); ?>" value="<?php esc_html_e($option, 'wacr'); ?>" placeholder="<?php esc_html_e('Enter Your Whatsapp Number', 'wacr');?>"/>
		
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('Order On WhatsApp', 'wacr');?>
		</label>
			<?php
	}
	
	// BookUpp compatibility -setting -codestart
	public function wacr_bookupp_comp_cb($args) {  
		$option = get_option($args[0]);
		$option1 = get_option($args[1]);
		?>
		 
			<label class="switch_admin">
				<input type="checkbox" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" <?php echo ($option == 'on') ? 'checked="checked"' : ''; ?>>
				<span class="slider_admin round"></span>
			</label>
			<br>
			
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('Enable this to send booking verification link and notifications, This requires BookUpp plugin to be installed.', 'wacr');?>
		</label>
			<?php
	}
	// -codeEnd
	
	public function wacr_menu_page()
	{
    require plugin_dir_path(__FILE__) . 'partials/wacr-admin-display.php';
 	}
	
	public function wacr_notification_settings()
	{
	 require plugin_dir_path(__FILE__) . 'partials/wacr-notification-settings.php';
	}
	
	public function wacr_admin_select_order_status_callback()
	{
	   $order_status = get_option("wacr_admin_select_order_status");
	?>
		<input type="password" autocomplete="new-password" name="<?php esc_attr_e($this->option_name . '_facebook_api_key'); ?>" id="<?php esc_attr_e($this->option_name . '_facebook_api_key'); ?>" value="<?php esc_attr_e($key); ?>">
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('setting description.', 'wacr');?>
		</label>

      <?php
	}
	

	public function wacr_first_template_option_cb($args) {  
		$option1 = (array)get_option($args[0]);
		?>
		<?php
			$response = Wacr_API_functions::wacr_get_wp_templates();
			$option_array = ["customer_first_name", "customer_last_name", "customer_email","admin_email", "customer_phone", "customer_billing_address","customer_shipping_address", "cart_item_count", "admin_phone","cart_items", "cart_total", "order_id", "time", "date", "site_name"];

			$encode_response = json_decode($response);
		?>
		<p> <?php esc_html_e("head options"); ?> </p>

		<select multiple="true" class="chzn-select" id="wacr_first_head_option" name="wacr_first_head_option[]"> 
			<?php

				foreach((array)$option_array as $dval )
					{ ?>		
						<option value="<?php esc_html_e($dval); ?>" <?php if (!empty($option1)) {
                                                                      if (in_array($dval, $option1)) {
                                                                        echo esc_html("selected",'wacr');
                                                                      }
                                                                    }
						?>><?php esc_html_e($dval); ?> </option>

			<?php } ?>
		</select>	
						
	

		<?php

		
	}

	public function wacr_enable_cooldown_time_cb($args) {  
		$option1 = get_option($args[0]);
		$option2 = get_option($args[1]);
		?>
		<?php esc_html_e("From: "); ?> <input type="time" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" value="<?php echo esc_attr($option1); ?>">	
	  	<?php esc_html_e(" To: "); ?>  <input type="time" id="<?php echo esc_attr($args[1]); ?>" name="<?php echo esc_attr($args[1]); ?>" value="<?php echo esc_attr($option2); ?>">	
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('setting description.', 'wacr');?>
		</label>

		<?php
	}
	public function wacr_update_widget_option_callback(){
		$wacr_template_id = sanitize_text_field($_POST['wacr_template_id']);
		update_option("wacr_layout_option", $wacr_template_id);
		echo "1";
		wp_die();
		
	}
		
	
	public function wacr_update_template_options_callback(){

		global $wpdb;
		global $woocommerce;
		
		$wacr_template_id = sanitize_text_field($_POST['wacr_template_id']);
		$wacr_template_name = sanitize_text_field($_POST['wacr_template_name']);
		$wacr_head_param_count = sanitize_text_field($_POST['wacr_head_param_count']); // head param count
		$wacr_body_param_count = sanitize_text_field($_POST['wacr_body_param_count']); //body param count
		$wacr_head_text = sanitize_text_field($_POST['wacr_head_text']); //head text
		$wacr_body_text = sanitize_text_field($_POST['wacr_body_text']); //body text
		$wacr_head_params = sanitize_text_field($_POST['wacr_head_params']);
		$wacr_button_param_count = sanitize_text_field($_POST['wacr_button_param_count']);

		$wacr_body_params = json_encode($_POST['wacr_body_params']);
		$wacr_other_params = sanitize_text_field($_POST['wacr_language_params']);

		$table_name_2 = $wpdb->prefix . 'wacr_templates'; 
		$sql_query = $wpdb->prepare("SELECT COUNT(id) FROM $table_name_2 WHERE wacr_template_id = %s", $wacr_template_id);
		$count = $wpdb->get_var($sql_query);
		
						if($count == "0"){
						
							$wpdb->insert($table_name_2, array(
								'wacr_template_id' => $wacr_template_id,
								'wacr_template_name' => $wacr_template_name,
								'wacr_head_param_count' => $wacr_head_param_count,
								'wacr_body_param_count' => $wacr_body_param_count,
								'wacr_head_params' => $wacr_head_params,
								'wacr_body_params' => $wacr_body_params,
								'wacr_head_text' => $wacr_head_text,
								'wacr_body_text' => $wacr_body_text,
								'wacr_button_info' => $wacr_button_param_count,
								'wacr_other_params' => $wacr_other_params,
								
								));
						}else{
							
							$update_array = array(
								'wacr_template_name' => $wacr_template_name,
								'wacr_head_param_count' => $wacr_head_param_count,
								'wacr_body_param_count' => $wacr_body_param_count,
								'wacr_head_params' => $wacr_head_params,
								'wacr_body_params' => $wacr_body_params,
								'wacr_head_text' => $wacr_head_text,
								'wacr_body_text' => $wacr_body_text,
								'wacr_button_info' => $wacr_button_param_count,
								'wacr_other_params' => $wacr_other_params,
								);

							$wpdb->update($table_name_2, $update_array, array('wacr_template_id' => $wacr_template_id));
						
						}
						
						echo "1";
						wp_die();

	}
	

	
	public function wacr_template_options_callback(){

		$head1 = sanitize_text_field($_POST['wacr_head1']);
		$head2 = sanitize_text_field($_POST['wacr_head2']);
		$head3 = sanitize_text_field($_POST['wacr_head3']);

		$body1 = sanitize_text_field($_POST['wacr_body1']);
	
		$body2 = json_encode($_POST['wacr_body2']);
		$body3 = json_encode($_POST['wacr_body3']);
		
		update_option("wacr_first_head_option", $head1);
		update_option("wacr_first_body_options", json_encode($body1));

		update_option("wacr_second_head_option", $head2);
		update_option("wacr_second_body_options", $body2);

		update_option("wacr_third_head_option", $head3);
		update_option("wacr_third_body_options", $body3);

		wp_die();
		return;

	}
	
	public function wacr_order_notification(){

	require plugin_dir_path(__FILE__) . 'partials/wacr-order-notification.php';
	}
	
	public function wacr_disable_abandoned_cb($args){
		$wacrAbandonedStatus = get_option($args[0]);
		?>
		
		<label class="switch_admin">
			<input type="checkbox" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" <?php echo ($wacrAbandonedStatus == 'on') ? 'checked="checked"' : ''; ?>>
			<span class="slider_admin round"></span>
		</label>
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('Enable this to stop detecting abandoned carts and notifications. However other features like order notifications and OTP will still work.', 'wacr');?>
		</label>
	  <?php
	}

	
	public function wacr_enable_cooldown_cb($args){
		$wacrOrderStatus = get_option($args[0]);
		$option1 = get_option($args[1]);
		$option2 = get_option($args[2]);
		?>
		
		<label class="switch_admin">
			<input type="checkbox" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" <?php echo ($wacrOrderStatus == 'on') ? 'checked="checked"' : ''; ?>>
			<span class="slider_admin round"></span>
		</label>
		<div class="wacr_dnd_time">
			<?php esc_html_e("From: "); ?> <input type="time" id="<?php echo esc_attr($args[1]); ?>" name="<?php echo esc_attr($args[1]); ?>" value="<?php echo esc_attr($option1); ?>" required>	
			<?php esc_html_e("To: "); ?>  <input type="time" id="<?php echo esc_attr($args[2]); ?>" name="<?php echo esc_attr($args[2]); ?>" value="<?php echo esc_attr($option2); ?>" required>	
		</div>
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('Enable and enter time which you set as do not disturb time. Any WhatsApp message will not be sent to user. This time is depend on your server location.', 'wacr');?>
		</label>
	  <?php
	}
	public function wacr_enable_clicktochat_cb($args){
		$wacrOrderStatus = get_option($args[0]);
		$option = get_option($args[1]);

		?>
		
		<label class="switch_admin">
			<input type="checkbox" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" <?php echo ($wacrOrderStatus == 'on') ? 'checked="checked"' : ''; ?>>
			<span class="slider_admin round"></span>
		</label>
		<br>
		<input type="number" id="<?php echo esc_attr($args[1]); ?>" name="<?php echo esc_attr($args[1]); ?>" value="<?php esc_html_e($option, 'wacr'); ?>" placeholder="<?php esc_html_e('Enter whatsapp number id', 'wacr');?>" required/>
		<label class="wacr_admin_descriptions">
			<?php echo wp_kses('Enable this to show <b> Click To Chat</b> widget on front-end. You can set widget design from "Manage Widgets".', 'wacr');?>
		</label>
	  <?php
	}
	
	public function wacr_order_notification_status_cb($args){
		$wacrOrderStatus = get_option($args[0]);
		?>
		
		<label class="switch_admin">
			<input type="checkbox" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" <?php echo ($wacrOrderStatus == 'on') ? 'checked="checked"' : ''; ?>>
			<span class="slider_admin round"></span>
		</label>
		<br>
		
		<?php
			$wacrOrderTemplate = get_option($args[1]);
			$response = Wacr_API_functions::wacr_get_wp_templates();
			$encode_response = json_decode($response);
			?>
			<select id="<?php echo esc_attr($args[1]); ?>" name="<?php echo esc_attr($args[1]); ?>" value="<?php echo esc_attr($option1); ?>"> 
			<option value="-1"> <?php esc_html_e("-- Select Template --", 'wacr'); ?> </option>
				<?php
					foreach($encode_response->data as $dkey => $dval )
						{ ?>		
							<option value="<?php esc_html_e($dval->name); ?>" <?php if($dval->name == $wacrOrderTemplate)
							echo esc_html("selected",'wacr');
							?>><?php esc_html_e($dval->name); ?> </option>

				<?php } ?>
			</select>	
			<label class="wacr_admin_descriptions">
				<?php echo esc_html('Send order complete notifications to customer using WhatsApp, create your template and assign it here.', 'wacr');?>
			</label> 
		<?php
	}

	public function wacr_admin_order_notification_status_cb($args){
		$wacrOrderStatus = get_option($args[0]);
		$option1 = get_option($args[1]);
		$option2 = get_option($args[2]);
		?>
		
		<label class="switch_admin">
			<input type="checkbox" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" <?php echo ($wacrOrderStatus == 'on') ? 'checked="checked"' : ''; ?>>
			<span class="slider_admin round"></span>
		</label>
		<br>	
	
		<?php
			$wacrOrderTemplate = get_option($args[1]);
			$response = Wacr_API_functions::wacr_get_wp_templates();
			$encode_response = json_decode($response);
			?>
			<select id="<?php echo esc_attr($args[1]); ?>" name="<?php echo esc_attr($args[1]); ?>" value="<?php echo esc_attr($option1); ?>">
			<option value="-1"> <?php esc_html_e("-- Select Template --", 'wacr'); ?> </option>

				<?php
					foreach($encode_response->data as $dkey => $dval )
						{ ?>	
							<option value="<?php esc_html_e($dval->name); ?>" <?php if($dval->name == $wacrOrderTemplate)
							echo esc_html("selected",'wacr');
							?>><?php esc_html_e($dval->name); ?> </option>

				<?php } ?>
			</select>	 
			<input type="number" id="<?php echo esc_attr($args[2]); ?>" name="<?php echo esc_attr($args[2]); ?>" value="<?php esc_html_e($option2, 'wacr'); ?>" placeholder="<?php esc_html_e('Enter Admin Mobile Number', 'wacr');?>" required/>

			<label class="wacr_admin_descriptions">
				<?php echo esc_html('Send notifications to admin about new orders.', 'wacr');?>
			</label>
		<?php
	}

	//booking compatibility notify
	public function wacr_booking_notify_cb($args){
		$wacrOrderStatus = get_option($args[0]);
		$option1 = get_option($args[1]);
		?>
		
		<label class="switch_admin">
			<input type="checkbox" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" <?php echo ($wacrOrderStatus == 'on') ? 'checked="checked"' : ''; ?>>
			<span class="slider_admin round"></span>
		</label>
		<br>
		<br>

		<input type="text" id="<?php echo esc_attr($args[1]); ?>" name="<?php echo esc_attr($args[1]); ?>" value="<?php esc_html_e($option1, 'wacr'); ?>" placeholder="<?php esc_html_e('Enter template name', 'wacr');?>"/>
		
		<?php
	}
	//booking compatibility verify
	public function wacr_booking_verify_cb($args){
		$wacrOrderStatus = get_option($args[0]);
		$option1 = get_option($args[1]);
		?>
		
		<label class="switch_admin">
			<input type="checkbox" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" <?php echo ($wacrOrderStatus == 'on') ? 'checked="checked"' : ''; ?>>
			<span class="slider_admin round"></span>
		</label>
		<br>
		<br>

		<input type="text" id="<?php echo esc_attr($args[1]); ?>" name="<?php echo esc_attr($args[1]); ?>" value="<?php esc_html_e($option1, 'wacr'); ?>" placeholder="<?php esc_html_e('Enter template name', 'wacr');?>"/>
		
		<?php
	}

	public function wacr_order_confirmation_cb($args){
		$wacrOrderStatus = get_option($args[0]);
		?>
		
		<label class="switch_admin">
			<input type="checkbox" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" <?php echo ($wacrOrderStatus == 'on') ? 'checked="checked"' : ''; ?>>
			<span class="slider_admin round"></span>
		</label>
		<br>
		
	  <?php
	  $wacrOrderTemplate = get_option($args[1]);
	  $response = Wacr_API_functions::wacr_get_wp_templates();
	  $encode_response = json_decode($response);
	  ?>
	  <select id="<?php echo esc_attr($args[1]); ?>" name="<?php echo esc_attr($args[1]); ?>" value="<?php echo esc_attr($option1); ?>"> 
	  <option value="-1"> <?php esc_html_e("-- Select Template --", 'wacr'); ?> </option>
		  <?php
			  foreach($encode_response->data as $dkey => $dval )
				  { ?>		
					  <option value="<?php esc_html_e($dval->name); ?>" <?php if($dval->name == $wacrOrderTemplate)
					  echo esc_html("selected",'wacr');
					  ?>><?php esc_html_e($dval->name); ?> </option>

		  <?php } ?>
	  </select>	 
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('Send verification link to user to confirm postpaid orders such as Cash on delivery, Cheque, and Bank Transfer.', 'wacr');?>
		</label>
	  <?php
	}
	public function wacr_order_update_notification_cb($args){
		$wacrOrderStatus = get_option($args[0]);
		?>
		
		<label class="switch_admin">
			<input type="checkbox" id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" <?php echo ($wacrOrderStatus == 'on') ? 'checked="checked"' : ''; ?>>
			<span class="slider_admin round"></span>
		</label>
		<br>
		<?php
			$wacrOrderTemplate = get_option($args[1]);
			$response = Wacr_API_functions::wacr_get_wp_templates();
			$encode_response = json_decode($response);
			?>
			<select id="<?php echo esc_attr($args[1]); ?>" name="<?php echo esc_attr($args[1]); ?>"> 
			<option value="-1"> <?php esc_html_e("-- Select Template --", 'wacr'); ?> </option>
				<?php
					foreach($encode_response->data as $dkey => $dval )
						{ ?>		
							<option value="<?php esc_html_e($dval->name); ?>" <?php if($dval->name == $wacrOrderTemplate)
							echo esc_html("selected",'wacr');
							?>><?php esc_html_e($dval->name); ?> </option>

				<?php } ?>
	  			</select>	
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('Send notifications when admin updates the order.', 'wacr');?>
		</label> 
	  	<?php
	}

	public function wacr_order_notification_template_cb($args){
		$wacrOrderTemplate = get_option($args[0]);
		$response = Wacr_API_functions::wacr_get_wp_templates();
		$encode_response = json_decode($response);
		?>
		<select id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" value="<?php echo esc_attr($option1); ?>"> 
		<option value="-1"> <?php esc_html_e("-- Select Template --", 'wacr'); ?> </option>
			<?php
				foreach($encode_response->data as $dkey => $dval )
					{ ?>		
						<option value="<?php esc_html_e($dval->name); ?>" <?php if($dval->name == $wacrOrderTemplate)
						echo esc_html("selected",'wacr');
						?>><?php esc_html_e($dval->name); ?> </option>

			<?php } ?>
		</select>	
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('setting description.', 'wacr');?>
		</label> 
	  <?php
	}

	public function wacr_order_update_temp_cb($args){
		$wacrOrderTemplate = get_option($args[0]);
		$response = Wacr_API_functions::wacr_get_wp_templates();
		$encode_response = json_decode($response);
		?>
		<select id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" value="<?php echo esc_attr($option1); ?>">
		<option value="-1"> <?php esc_html_e("-- Select Template --", 'wacr'); ?> </option>
			<?php
				foreach($encode_response->data as $dkey => $dval )
					{ ?>		
						<option value="<?php esc_html_e($dval->name); ?>" <?php if($dval->name == $wacrOrderTemplate)
						echo esc_html("selected",'wacr');
						?>><?php esc_html_e($dval->name); ?> </option>

			<?php } ?>
		</select>	
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('setting description.', 'wacr');?>
		</label>
	  <?php
	}

	public function wacr_order_confirmation_template_cb($args){
		$wacrOrderTemplate = get_option($args[0]);
		$response = Wacr_API_functions::wacr_get_wp_templates();
		$encode_response = json_decode($response);
		?>
		<select id="<?php echo esc_attr($args[0]); ?>" name="<?php echo esc_attr($args[0]); ?>" value="<?php echo esc_attr($option1); ?>"> 
			<?php
				foreach($encode_response->data as $dkey => $dval )
					{ ?>		
						<option value="<?php esc_html_e($dval->name); ?>" <?php if($dval->name == $wacrOrderTemplate)
						echo esc_html("selected",'wacr');
						?>><?php esc_html_e($dval->name); ?> </option>

			<?php } ?>
		</select>	
		<label class="wacr_admin_descriptions">
			<?php echo esc_html('setting description.', 'wacr');?>
		</label>
	  <?php
	}

	public function wacr_repair_database_tables_callback(){

		global $woocommerce;
		global $wpdb;
		$table_for_logs1 = $wpdb->prefix.'wacr_templates';
		$wpdb->get_results(("DROP TABLE $table_for_logs1"));
		$response = $wpdb->query($wpdb->prepare("TRUNCATE TABLE ".$wpdb->prefix . "wacr_templates"));
		echo $response;
		wp_die();
		return;
	}

	public function wacr_order_status_change_fire($order_id, $old_status, $new_status)
	{	
		global $wpdb;
		global $woocommerce;
		$confirmMessageSent = get_post_meta( $order_id, 'wacr_order_sent_once');
		if($confirmMessageSent != "yes" && $old_status == "pending"){
			return; // Exit if already processed
		}

		$order = wc_get_order($order_id);
		$mobile_number_id = wp_unslash(get_option('wacr_whatsapp_business_mobile_number_id'));
		$update_template = wp_unslash(get_option('wacr_order_update_temp'));
		$bearer_token = wp_unslash(get_option('wacr_bearer_token_whatsapp'));
		$mobile_number = wp_unslash(get_option('wacr_mobile_number_whatsapp'));
		#order details string
				$data  = $order->get_data(); //order data
				$order_id        = $data['id'];
				$billing_email      = $data['billing']['email'];
				$billing_phone      = $data['billing']['phone'];
				//billing
				$billing_first_name = $data['billing']['first_name'];
				$billing_last_name  = $data['billing']['last_name'];
	
		//check limit
		$dailyDbCount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM ".$wpdb->prefix."wacr_message_logs WHERE `wacr_msg_status` = 'sent' AND date(wacr_updated_date_time) = CURDATE() ORDER BY wacr_updated_date_time DESC"));
		$userSetCount = get_option('wacr_daily_message_limit_whatsapp');
		
		if($dailyDbCount>=$userSetCount){
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
			$language = $response->get_template_language($update_template);

		$curl = curl_init();
				$json_array = array (
					"messaging_product" => "whatsapp","to" => "$billing_phone","type" => "template","template" => 
					array ("name" => "$update_template","language" => 
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
									"text" => "$old_status"),
								1 => array (
									"type" => "text",
									"text" => "$new_status"),
								2 => array (
									"type" => "text",
									"text" => "$order_id"),	
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
						'wacr_msg_type' => "order_status_change",
						'wacr_msg_status' => "sent",
						'wacr_template' => "$update_template",
						'wacr_orderdetails' => $order_id,
						));
						
				}else{
					if(isset($response_decode['error'])){
						//insert message log
						$table_for_logs = $wpdb->prefix.'wacr_message_logs';
						$wpdb->insert($table_for_logs, array(
							'wacr_msg_type' => "order_status_change",
							'wacr_msg_status' => "error",
							'wacr_template' => "$update_template",
							'wacr_orderdetails' => $order_id,
							));
					}
				}
	
	}

	public function wacr_create_temp_cb_callback(){
		require_once plugin_dir_path( __FILE__ ) . 'partials/wacr-create-templates.php';
		$tmp_name = $_POST['name'];
		$tmp_head = $_POST['head'];
		$tmp_body = $_POST['body'];
		$response = new Wacr_AddTemplate();
        $response = $response->wacr_create_tmp_fn($tmp_name, $tmp_head, $tmp_body);
		$decoded_rsp = get_object_vars(json_decode($response));
		if(isset($decoded_rsp["id"])){
			echo "1";
		}else{
			$errorTitle = $decoded_rsp["error"]->error_user_title;
			echo "$errorTitle";
		}
		wp_die();
	}

	
	public function wacr_notify_for_booking($post_id) {
				require_once plugin_dir_path(__DIR__ ) . 'admin/collab/wacr-bookupp.php';
					$response = new Wacr_BookUpp();
					$language = $response->wacr_send_bookup_notify("$post_id");
	}

	public function wacr_verify_for_booking($post_id) {
		require_once plugin_dir_path(__DIR__ ) . 'admin/collab/wacr-bookupp.php';
			$response = new Wacr_BookUpp();
			$language = $response->wacr_send_bookup_verify("$post_id");
	}

	public function wacr_delete_abcarts_cb(){
		$wacr_abcartID = wp_unslash($_POST['wacr_abcartID']);
		global $wpdb;
		$table =  $wpdb->prefix."wacr_adandoned_order_list";
		$count = $wpdb->delete( $table, array( 'id' => $wacr_abcartID ) );
		echo "$count";
		wp_die();
	}

}
