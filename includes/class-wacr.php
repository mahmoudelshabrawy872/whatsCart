<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://techspawn.com/
 * @since      1.0.0
 *
 * @package    Wacr
 * @subpackage Wacr/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wacr
 * @subpackage Wacr/includes
 * @author     TechSpawn <support@techspawn.com>
 */
class Wacr {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wacr_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WACR_VERSION' ) ) {
			$this->version = WACR_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wacr';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wacr_Loader. Orchestrates the hooks of the plugin.
	 * - Wacr_i18n. Defines internationalization functionality.
	 * - Wacr_Admin. Defines all hooks for the admin area.
	 * - Wacr_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wacr-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wacr-i18n.php';

		/**
		 * Rest API dependencies
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wacr-rest-api.php';
		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wacr-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		if (get_option('wacr_license') != '' || get_option('wacr_license') != 'invalid') {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wacr-public.php';
		}
		$this->loader = new Wacr_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wacr_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wacr_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wacr_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		

		// menu init
		$this->loader->add_action('admin_menu', $plugin_admin, 'wacr_register_menu_page');
		$this->loader->add_action('admin_init', $plugin_admin, 'wacr_register_setting');
		
		//list templates
		$this->loader->add_action('wp_ajax_wacr_list_templates', $plugin_admin, 'wacr_list_templates_callback');
		$this->loader->add_action('wp_ajax_nopriv_wacr_list_templates', $plugin_admin, 'wacr_list_templates_callback');
		
		$this->loader->add_action('wp_ajax_wacr_template_options', $plugin_admin, 'wacr_template_options_callback');
		$this->loader->add_action('wp_ajax_nopriv_wacr_template_options', $plugin_admin, 'wacr_template_options_callback');
		
		$this->loader->add_action('wp_ajax_wacr_update_template_options', $plugin_admin, 'wacr_update_template_options_callback');
		$this->loader->add_action('wp_ajax_nopriv_wacr_update_template_options', $plugin_admin, 'wacr_update_template_options_callback');

		$this->loader->add_action('wp_ajax_wacr_update_widget_option', $plugin_admin, 'wacr_update_widget_option_callback');
		$this->loader->add_action('wp_ajax_nopriv_wacr_update_widget_option', $plugin_admin, 'wacr_update_widget_option_callback');

		$this->loader->add_action('wp_ajax_wacr_create_temp_cb', $plugin_admin, 'wacr_create_temp_cb_callback');
		$this->loader->add_action('wp_ajax_nopriv_wacr_create_temp_cb', $plugin_admin, 'wacr_create_temp_cb_callback');
		

		//delete abandoned carts
		$this->loader->add_action( 'wp_ajax_nopriv_delete_abcarts', $plugin_admin, 'wacr_delete_abcarts_cb' );
        $this->loader->add_action( 'wp_ajax_delete_abcarts', $plugin_admin, 'wacr_delete_abcarts_cb' );


		//booking compatibility notify
		if(get_option("wacr_booking_notify") == "on"){
			$this->loader->add_action('save_post_wcsb_appointment', $plugin_admin, 'wacr_notify_for_booking');
		}

		//booking compatibility verify
		if(get_option("wacr_booking_verify") == "on"){
		$this->loader->add_action('save_post_wcsb_appointment', $plugin_admin, 'wacr_verify_for_booking');
		}

		$this->loader->add_action('wp_ajax_wacr_repair_database_tables', $plugin_admin, 'wacr_repair_database_tables_callback');
		$this->loader->add_action('wp_ajax_nopriv_wacr_repair_database_tables', $plugin_admin, 'wacr_repair_database_tables_callback');
		$OrderUpdateNotice = get_option('wacr_order_update_notification');
		if($OrderUpdateNotice == 'on'){
			$this->loader->add_action('woocommerce_order_status_changed', $plugin_admin, 'wacr_order_status_change_fire', 10, 3);
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		if (get_option('wacr_license') != '' || get_option('wacr_license') != 'invalid') {
		$plugin_public = new Wacr_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'woocommerce_thank_you', $plugin_public, 'is_express_delivery',  1, 1  );
		$this->loader->add_action( 'woocommerce_checkout_order_processed', $plugin_public, 'wacr_change_order_status_to_recovered' , 999, 4);

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_checkout_update_order_review', $plugin_public, 'wacr_save_data' );
		$this->loader->add_action( 'woocommerce_cart_actions', $plugin_public, 'wacr_add_additional_details' );
		$this->loader->add_action( 'woocommerce_add_to_cart', $plugin_public, 'wacr_add_additional_details' );
		$this->loader->add_action( 'woocommerce_cart_item_removed', $plugin_public, 'wacr_add_additional_details' );
		$this->loader->add_action( 'woocommerce_before_checkout_form', $plugin_public, 'wacr_checkout_script' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_user_data', $plugin_public, 'wacr_get_user_data_on_checkout' );
        $this->loader->add_action( 'wp_ajax_get_user_data', $plugin_public, 'wacr_get_user_data_on_checkout' );

		//sendOTP
		$this->loader->add_action( 'wp_ajax_nopriv_wacr_validate_and_send_otp', $plugin_public, 'wacr_validate_and_send_otp' );
        $this->loader->add_action( 'wp_ajax_wacr_validate_and_send_otp', $plugin_public, 'wacr_validate_and_send_otp' );


		//sendOTP
		$this->loader->add_action( 'wp_ajax_nopriv_wacr_check_otp', $plugin_public, 'wacr_check_otp_cb' );
        $this->loader->add_action( 'wp_ajax_wacr_check_otp', $plugin_public, 'wacr_check_otp_cb' );



		$this->loader->add_action( 'wp_ajax_nopriv_wacr_get_templates', $plugin_public, 'wacr_wacr_get_templates' );
        $this->loader->add_action( 'wp_ajax_wacr_get_templates', $plugin_public, 'wacr_wacr_get_templates' );
		$this->loader->add_action( 'wp_ajax_nopriv_wacr_save_triggers_ajax', $plugin_public, 'wacr_save_triggers_ajax' );
        $this->loader->add_action( 'wp_ajax_wacr_save_triggers_ajax', $plugin_public, 'wacr_save_triggers_ajax' );
		
		$this->loader->add_action( 'wp_ajax_wacr_get_user_info_login', $plugin_public, 'wacr_get_user_info_login' );
		$this->loader->add_action( 'wp_ajax_nopriv_wacr_get_user_info_login', $plugin_public, 'wacr_get_user_info_login' );
		$this->loader->add_action( 'wp_ajax_wacr_verify_otp_user_login', $plugin_public, 'wacr_verify_otp_user_login' );
		$this->loader->add_action( 'wp_ajax_nopriv_wacr_verify_otp_user_login', $plugin_public, 'wacr_verify_otp_user_login' );
       
		$this->loader->add_action( 'wp_ajax_nopriv_wacr_delete_message_logs', $plugin_public, 'wacr_delete_message_logs' );
        $this->loader->add_action( 'wp_ajax_wacr_delete_message_logs', $plugin_public, 'wacr_delete_message_logs' );
		
		$wacrOrderNotificationStatus = get_option('wacr_order_notification_status');
		if($wacrOrderNotificationStatus == 'on'){
			$this->loader->add_action('woocommerce_thankyou', $plugin_public, 'wacr_order_complete_notification',  10, 1);
		}

		$OrderOnWhatsApp = get_option('wacr_order_on_whatsapp');
		if($OrderOnWhatsApp == "on"):
			$this->loader->add_action('woocommerce_loop_add_to_cart_link', $plugin_public, 'wacr_order_on_whatsapp',  10, 3);
			$this->loader->add_action('woocommerce_after_add_to_cart_button', $plugin_public, 'wacr_order_on_whatsapp_single_product');
		endif;

		$wacrAdminNotificationStatus = get_option('wacr_admin_order_notification_status');
		if($wacrAdminNotificationStatus == 'on'){
			$this->loader->add_action('woocommerce_thankyou', $plugin_public, 'wacr_admin_order_complete_notification',  10, 1);
		}

		$orderConfirmNotice = get_option("wacr_order_confirmation");
		if($orderConfirmNotice == 'on'){
			$this->loader->add_action('woocommerce_thankyou', $plugin_public, 'wacr_confirm_order_by_whatsapp',  10, 1);
		}
		$statusClickToChat = get_option("wacr_enable_clicktochat");
		if($statusClickToChat == 'on'){
		$this->loader->add_action('wp_footer', $plugin_public, 'wacr_click_to_chat',  10, 1);
		}

		$wacr_enable_otp_login = get_option("wacr_enable_otp_login");
		if($wacr_enable_otp_login == 'on'){
			$this->loader->add_action( 'woocommerce_login_form', $plugin_public, 'wacr_otp_login_field' );
		}
		$wacr_reg_without_pswd = get_option("wacr_reg_without_pswd");
		if($wacr_reg_without_pswd == 'on'){
			$this->loader->add_action( 'woocommerce_login_form', $plugin_public, 'wacr_reg_without_pswd_cb' );
		}
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wacr_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
