<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://techspawn.com/
 * @since      1.0.0
 *
 * @package    Wacr
 * @subpackage Wacr/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wacr
 * @subpackage Wacr/includes
 * @author     TechSpawn <support@techspawn.com>
 */
class Wacr_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		
		wp_clear_scheduled_hook('wacr_send_first_message');
		wp_clear_scheduled_hook('wacr_clear_junk_data');
		

	}

}
