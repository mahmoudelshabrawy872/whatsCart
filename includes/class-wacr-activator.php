<?php

/**
 * Fired during plugin activation
 *
 * @link       https://techspawn.com/
 * @since      1.0.0
 *
 * @package    Wacr
 * @subpackage Wacr/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wacr
 * @subpackage Wacr/includes
 * @author     TechSpawn <support@techspawn.com>
 */
class Wacr_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		global $wpdb; 
  		$table_name_1 = $wpdb->prefix . 'wacr_adandoned_order_list';
		$table_name_2 = $wpdb->prefix . 'wacr_templates'; 
		$table_name_3 = $wpdb->prefix . 'wacr_cod_order_confirm'; 
		$table_name_3 = $wpdb->prefix . 'wacr_booking_confirm'; 
		$table_name_4 = $wpdb->prefix . 'wacr_message_logs';
		$table_name_5 = $wpdb->prefix . 'wacr_dynamic_triggers';
		$table_name_6 = $wpdb->prefix . 'wacr_sessions';

  		$charset_collate = $wpdb->get_charset_collate();

		  if(strtolower($wpdb->get_var( "show tables like '$table_name_1'" )) != strtolower($table_name_1) ) 
		  {
			$tbl = "CREATE TABLE $table_name_1 (
				`id`                  BIGINT(20) NOT NULL auto_increment,
				`wacr_customer_id`         VARCHAR(100) NULL DEFAULT NULL,
				`wacr_customer_email`      VARCHAR(100) NULL DEFAULT NULL,
				`wacr_customer_mobile_no`  VARCHAR(100) NULL DEFAULT NULL,
				`wacr_customer_first_name` VARCHAR(100) NULL DEFAULT NULL,
				`wacr_customer_last_name`  VARCHAR(100) NULL DEFAULT NULL,
				`wacr_customer_type`       VARCHAR(50) NULL DEFAULT NULL,
				`wacr_message_enable`       VARCHAR(50) NULL DEFAULT NULL,
				`wacr_create_date_time`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`wacr_cart_json`           LONGTEXT NULL DEFAULT NULL,
				`wacr_cart_total_json`     LONGTEXT NULL DEFAULT NULL,
				`wacr_cart_total`          FLOAT NOT NULL,
				`wacr_cart_currency`       VARCHAR(50) NOT NULL,
				`wacr_abandoned_date_time` DATETIME NOT NULL default '0000-00-00 00:00:00',
				`wacr_message_sent`        INT NOT NULL DEFAULT '0',
				`wacr_status`              INT NOT NULL DEFAULT '0',
				`wacr_last_access_time`    DATETIME NOT NULL default '0000-00-00 00:00:00',
				`wacr_message_api_response` LONGTEXT NULL DEFAULT NULL,
				PRIMARY KEY (`id`)
				)$charset_collate;";
				include_once ABSPATH . '/wp-admin/includes/upgrade.php';
				dbDelta($tbl);
			}
			
			
			if(strtolower($wpdb->get_var( "show tables like '$table_name_2'" )) != strtolower($table_name_2) ) 
		  {
			$tbl = "CREATE TABLE $table_name_2 (
				`id`                  BIGINT(20) NOT NULL auto_increment,
				`wacr_template_id`         VARCHAR(50) NULL DEFAULT NULL,
				`wacr_template_name`         VARCHAR(50) NULL DEFAULT NULL,
				`wacr_head_param_count`      INT NOT NULL DEFAULT '0',
				`wacr_body_param_count`  INT NOT NULL DEFAULT '0',
				`wacr_head_params` 	LONGTEXT NULL DEFAULT NULL,
				`wacr_body_params` 	 LONGTEXT NULL DEFAULT NULL,
				`wacr_head_text`       LONGTEXT NULL DEFAULT NULL,
				`wacr_body_text`    LONGTEXT NULL DEFAULT NULL,
				`wacr_button_info`           LONGTEXT NULL DEFAULT NULL,
				`wacr_other_params`     LONGTEXT NULL DEFAULT NULL,
				`wacr_updated_date_time`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
				)$charset_collate;";
				include_once ABSPATH . '/wp-admin/includes/upgrade.php';
				dbDelta($tbl);
			}

			if(strtolower($wpdb->get_var( "show tables like '$table_name_3'" )) != strtolower($table_name_3) ) 
		  {
			$tbl = "CREATE TABLE $table_name_3 (
				`id`                  BIGINT(20) NOT NULL auto_increment,
				`wacr_random_number`         VARCHAR(50) NULL DEFAULT NULL,
				`wacr_order_number` VARCHAR(50) NULL DEFAULT NULL,
				`wacr_col2`         VARCHAR(50) NULL DEFAULT NULL,
				`wacr_updated_date_time`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
				)$charset_collate;";
				include_once ABSPATH . '/wp-admin/includes/upgrade.php';
				dbDelta($tbl);
			}

			if(strtolower($wpdb->get_var( "show tables like '$table_name_4'" )) != strtolower($table_name_4) ) 
		  {
			$tbl = "CREATE TABLE $table_name_4 (
				`id`                  BIGINT(20) NOT NULL auto_increment,
				`wacr_msg_type`         VARCHAR(50) NULL DEFAULT NULL,
				`wacr_msg_status` VARCHAR(50) NULL DEFAULT NULL,
				`wacr_template`         VARCHAR(50) NULL DEFAULT NULL,
				`wacr_orderdetails`         VARCHAR(500) NULL DEFAULT NULL,
				`wacr_updated_date_time`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
				)$charset_collate;";
				include_once ABSPATH . '/wp-admin/includes/upgrade.php';
				dbDelta($tbl);
			}

			if(strtolower($wpdb->get_var( "show tables like '$table_name_5'" )) != strtolower($table_name_5) ) 
		  {
			$tbl = "CREATE TABLE $table_name_5 (
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


			if(strtolower($wpdb->get_var( "show tables like '$table_name_6'" )) != strtolower($table_name_6) ) 
		  {
			$tbl = "CREATE TABLE $table_name_6 (
				`session_id`                  bigint UNSIGNED NOT NULL,
				`session_key`         char(32) COLLATE utf8mb4_unicode_520_ci NOT NULL,
				`session_value`      longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
				`session_expiry`  bigint UNSIGNED NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci";
				include_once ABSPATH . '/wp-admin/includes/upgrade.php';
				dbDelta($tbl);
			}


	}
	
		
}