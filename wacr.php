<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://techspawn.com/
 * @since             1.0.0
 * @package           wacr
 *
 * @wordpress-plugin
 * Plugin Name:       WhatsCart for WooCommerce
 * Plugin URI:        https://techspawn.com/
 * Description:       WhatsCart for WooCommerce plugin allows you to send left out cart reminders on WhatsApp to customers. Also can define scheduler to send automated notifications for abandoned cart users by selecting Whatsapp templates. his is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.8
 * Author:            TechSpawn
 * Author URI:        https://techspawn.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wacr
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
	// code-start
	function wooc_extra_register_fields() {
		$otpLen = get_option("wacr_otp_length");	
		?>
		<p class="form-row form-row-wide">
			<label for="wacr_reg_user_mobile"><?php _e( 'Phone', 'woocommerce' ); ?><span class="required">*</span></label>
			<input type="number" class="wacr_user_reg_form wacr_edit_input" name="wacr_reg_user_mobile" id="wacr_reg_user_mobile" placeholder="+919922xxxxxx" value="<?php esc_attr_e( $_POST['wacr_reg_user_mobile'] ); ?>" />
		<div class="wacr_user_reg_widget">
			<button type="button" class="wacr_reg_btn"> 
				<?php esc_html_e("Send OTP", 'wacr'); ?>
			</button>
			<input class="wacr_reg_user_mobile" maxlength="<?php echo esc_attr( $otpLen  );?>" type="text" />
			<button type="button" class="wacr_reg_btn_submit"> 
				<?php esc_html_e("Submit OTP", 'wacr'); ?>
			</button>
			<span class="wacr_close">&times;</span>
		</div>
		<label class="wacr_verified_success" for="wacr_verified_success"><?php _e( 'Mobile Number Verified Successfully!', 'woocommerce' ); ?><span class="required">*</span></label>
		</p>
		<div class="clear"></div>
		<?php
	}


	function wacr_save_extra_register_select_field( $customer_id ) {
		if ( isset( $_POST['wacr_reg_user_mobile'] ) ) {
			update_user_meta( $customer_id, 'billing_phone', $_POST['wacr_reg_user_mobile'] );
		}
	}


	$OTPforRegistration = get_option("wacr_enable_otp_register");

	if($OTPforRegistration == "on"){
		add_action( 'woocommerce_created_customer', 'wacr_save_extra_register_select_field' );
		add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );
	}
	// code-end

	if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) ||  is_array(get_site_option('active_sitewide_plugins')) && array_key_exists('woocommerce/woocommerce.php', get_site_option('active_sitewide_plugins'))) {
			add_action("init", "wacr_cart_link_fetcher");
	  }
	function wacr_cart_link_fetcher(){
		//confirm booking
		global $wpdb;
       	global $woocommerce;
		if(isset($_GET['confirmBooking'])):
		$order_idHash = $_GET['confirmBooking'];
		$randNum = $_GET['id'];
		$siteURL = get_site_url();
		if(!empty($order_idHash) && !empty($randNum)):
			if(wp_unslash(get_option('wacr_booking_verify')) == "on"):
				$DBorder_id = $wpdb->get_results($wpdb->prepare("SELECT wacr_order_number FROM ".$wpdb->prefix."wacr_cod_order_confirm WHERE wacr_random_number = %s", $randNum));	
				update_post_meta( $DBorder_id, 'wacr_order_sent_once', "no" );			
				$savedOrderID = $DBorder_id[0]->wacr_order_number;
				$fetched_Order_Id = $wpdb->get_results($wpdb->prepare("SELECT id FROM ".$wpdb->prefix."posts WHERE post_type = 'wcsb_appointment' AND md5(id) = %s", $order_idHash));	
				$DB_order_id = $fetched_Order_Id[0]->id;
				if($savedOrderID == $DB_order_id):
					update_post_meta($savedOrderID, 'booking_status', 'confirmed');
					endif;
				endif;
			endif;
		endif;
		
		//confirm order
		if(isset($_GET['confirmOrder'])):
			$order_idHash = $_GET['confirmOrder'];
			$randNum = $_GET['id'];

			$DBorder_id = $wpdb->get_results($wpdb->prepare("SELECT wacr_order_number FROM ".$wpdb->prefix."wacr_cod_order_confirm WHERE wacr_random_number = %s", $randNum));	
			update_post_meta( $DBorder_id, 'wacr_order_sent_once', "no" );			
			$savedOrderID = $DBorder_id[0]->wacr_order_number;
			$fetched_Order_Id = $wpdb->get_results($wpdb->prepare("SELECT id FROM ".$wpdb->prefix."posts WHERE post_type = 'shop_order' AND md5(id) = %s", $order_idHash));	
			$DB_order_id = $fetched_Order_Id[0]->id;
					
			if($savedOrderID == $DB_order_id){
				$order = new WC_Order($DB_order_id);
					if (!empty($order)) {
						$order->update_status( 'processing' );
						$wpdb->update($wpdb->prefix.'wacr_cod_order_confirm', array(	
									'wacr_col2' => "verified"), array(	
										'wacr_order_number' => "$DB_order_id"
							));
					}
			}
		endif;
		
		// get cart content
			if(isset($_GET['cartFetched'])):
				if($_GET['cartFetched'] == "true"):
					$woocommerce->session->set('wacr_user_mail', '');
					$woocommerce->session->set('wacr_user_phone', '');
					$woocommerce->session->set('wacr_user_first_name', '');
					$woocommerce->session->set('wacr_user_last_name', '');
					$woocommerce->session->set('wacr_wacr_country', '');
				endif;
			endif;
			$checkout_url = wc_get_cart_url();
			if(!is_admin() && isset($_GET['cartID'])){
			$session_id = WC()->session->get_customer_id();
			$abadoned_cart_id = $_GET['cartID'];
			$_wacr_all_ids = $wpdb->get_results($wpdb->prepare("SELECT id FROM ".$wpdb->prefix."wacr_adandoned_order_list"));
			foreach($_wacr_all_ids as $k => $row){
				if($abadoned_cart_id == md5($row->id)){
					$abadoned_cart_id = $row->id;
				}

			}
			$siteURL = get_site_url();
			$_wacr_all_ids = $wpdb->get_results($wpdb->prepare("SELECT id FROM ".$wpdb->prefix."wacr_adandoned_order_list"));
			$testt = $wpdb->get_results($wpdb->prepare("SELECT `wacr_message_api_response` FROM ".$wpdb->prefix."wacr_adandoned_order_list WHERE `id` = %s", $abadoned_cart_id));
					if(isset($abadoned_cart_id)){
						$show_all_data = $wpdb->get_results($wpdb->prepare("SELECT session_value  FROM ".$wpdb->prefix."woocommerce_sessions WHERE session_id = %s", $testt[0]->wacr_message_api_response));
						if(empty($show_all_data)){
							$show_all_data = $wpdb->get_results($wpdb->prepare("SELECT session_value  FROM ".$wpdb->prefix."wacr_sessions WHERE session_id = %s", $testt[0]->wacr_message_api_response));
						}
						foreach ($show_all_data as $row) {
							$session_content = $row->session_value;
							}
							$woocommerce->session->set_customer_session_cookie(true);	
							$wpdb->update($wpdb->prefix.'woocommerce_sessions', array('session_value' => $session_content), array('session_key' => $session_id)); 

							if(!empty($abadoned_cart_id) && $_GET['wacr_added'] != 'true'){
								header("Location: $siteURL?cartID=$abadoned_cart_id&wacr_added=true");
								exit();
							}
					}
							if($_GET['wacr_added'] == 'true')
							{
								header("Location: $checkout_url?cartFetched=true");
								exit();
							}
					}	

	}

				
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WACR_VERSION', '1.0.7' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wacr-activator.php
 */
function wacr_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wacr-activator.php';
	Wacr_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wacr-deactivator.php
 */
function wacr_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wacr-deactivator.php';
	Wacr_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'wacr_activate' );
register_deactivation_hook( __FILE__, 'wacr_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wacr.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function wacr_run() {

	$plugin = new Wacr();
	$plugin->run();

}
wacr_run();
